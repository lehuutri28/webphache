<?php
/**
 * ============================================================================
 * PHACHE.COM.VN - CENTRALIZED API AUTHENTICATION & TELEMETRY GATEWAY
 * ============================================================================
 * @author C12 - Senior Full-Stack Web Developer & Technical SEO Lead
 * @version 1.0.0 [2026]
 * Purpose: Unified Bearer token verification, dynamic RBAC scope enforcement,
 *          real-time usage telemetry tracking, and sliding-window rate limiting.
 * ============================================================================
 */

if (!defined('PHACHE_API_GATEWAY')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Direct access forbidden.']);
    exit;
}

// 1. Polyfill hash_equals if needed
if (!function_exists('hash_equals')) {
    function hash_equals($known_string, $user_string) {
        if (!is_string($known_string) || !is_string($user_string)) return false;
        $len = strlen($known_string);
        if ($len !== strlen($user_string)) return false;
        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= ord($known_string[$i]) ^ ord($user_string[$i]);
        }
        return $status === 0;
    }
}

/**
 * Get client IP address safely
 */
function pl_get_client_ip() {
    $rawIp = '127.0.0.1';
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $rawIp = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $rawIp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $rawIp = $_SERVER['REMOTE_ADDR'];
    }
    return filter_var(trim($rawIp), FILTER_VALIDATE_IP) ? trim($rawIp) : '127.0.0.1';
}

/**
 * Timing-safe token extractor from headers
 */
function pl_extract_bearer_token() {
    $authHeader = '';
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = trim($_SERVER['HTTP_AUTHORIZATION']);
    } elseif (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $authHeader = trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    } elseif (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = trim($headers['Authorization']);
        } elseif (isset($headers['authorization'])) {
            $authHeader = trim($headers['authorization']);
        }
    }

    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        return trim($matches[1]);
    }
    return '';
}

/**
 * Rate Limiter via sliding window (cache file)
 */
function pl_enforce_rate_limit($identifier, $maxRequests = 60, $windowSeconds = 60) {
    $cacheDir = defined('UPLOAD_DIR') ? UPLOAD_DIR . 'cache' . DIRECTORY_SEPARATOR : sys_get_temp_dir() . DIRECTORY_SEPARATOR;
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0755, true);
    }

    $rateFile = $cacheDir . 'ratelimit_' . md5($identifier) . '.json';
    $now = time();
    $rateData = array('count' => 0, 'first_request' => $now);

    if (file_exists($rateFile)) {
        $existing = json_decode(@file_get_contents($rateFile), true);
        if (is_array($existing) && isset($existing['first_request'])) {
            if (($now - $existing['first_request']) < $windowSeconds) {
                $rateData = $existing;
            }
        }
    }

    $rateData['count']++;
    @file_put_contents($rateFile, json_encode($rateData));

    if ($rateData['count'] > $maxRequests) {
        http_response_code(429);
        echo json_encode([
            'success' => false,
            'error'   => 'Too Many Requests: Rate limit exceeded (' . (int)$maxRequests . ' requests per ' . (int)$windowSeconds . 's). Please wait.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

/**
 * Safe database connection resolver
 */
function pl_get_db_connection() {
    global $obMySQLi;
    if ($obMySQLi instanceof mysqli && !$obMySQLi->connect_errno) {
        return $obMySQLi;
    }
    if (isset($GLOBALS['obMySQLi']) && $GLOBALS['obMySQLi'] instanceof mysqli && !$GLOBALS['obMySQLi']->connect_errno) {
        return $GLOBALS['obMySQLi'];
    }

    $docRoot = defined('DOCROOT') ? DOCROOT : (dirname(__DIR__) . DIRECTORY_SEPARATOR);
    $dbConfigFile = $docRoot . 'config' . DIRECTORY_SEPARATOR . 'db.php';
    if (file_exists($dbConfigFile)) {
        require $dbConfigFile;
        if (isset($obMySQLi) && $obMySQLi instanceof mysqli) {
            $GLOBALS['obMySQLi'] = $obMySQLi;
            return $obMySQLi;
        }
    }
    return null;
}

/**
 * Main verification function
 * @param string $requiredScope Required scope for endpoint: 'publish', 'upload', 'media', or 'all'
 * @return array Key details
 */
function pl_verify_api_key_and_track($requiredScope = 'all') {
    $receivedToken = pl_extract_bearer_token();
    if (empty($receivedToken)) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error'   => 'Unauthorized: Missing Authorization Bearer token.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $clientIp = pl_get_client_ip();

    // 1. Ensure DB connection
    $db = pl_get_db_connection();
    $matchedKey = null;

    if ($db && !$db->connect_errno) {
        $db->set_charset("utf8mb4");

        // Prepare query to fetch active API key
        $stmt = $db->prepare("SELECT `id`, `key_name`, `api_key`, `scope`, `rate_limit`, `status` FROM `api_keys` WHERE `api_key` = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('s', $receivedToken);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $matchedKey = $res->fetch_assoc();
            }
            $stmt->close();
        }
    }

    // 2. Check DB Record if found
    if ($matchedKey) {
        // Check active status
        if ((int)$matchedKey['status'] !== 1) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error'   => 'Forbidden: This API Key has been suspended or revoked by administrator.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Check scope permission
        $keyScope = $matchedKey['scope'];
        if ($keyScope !== 'all' && $keyScope !== $requiredScope && $requiredScope !== 'all') {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error'   => "Forbidden: This API Key does not have permission for scope '{$requiredScope}' (Current scope: '{$keyScope}')."
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Enforce rate limit
        $limit = (int)$matchedKey['rate_limit'] > 0 ? (int)$matchedKey['rate_limit'] : 60;
        pl_enforce_rate_limit($receivedToken . '_' . $clientIp, $limit, 60);

        // Update telemetry tracking
        $now = time();
        $keyId = (int)$matchedKey['id'];
        $upStmt = $db->prepare("UPDATE `api_keys` SET `total_requests` = `total_requests` + 1, `last_used_at` = ?, `last_ip` = ? WHERE `id` = ?");
        if ($upStmt) {
            $upStmt->bind_param('isi', $now, $clientIp, $keyId);
            $upStmt->execute();
            $upStmt->close();
        }

        return $matchedKey;
    }

    // 3. Fallback check with config/api_secret.php (Backward Compatibility)
    $secretConfigFile = $docRoot . 'config' . DIRECTORY_SEPARATOR . 'api_secret.php';
    if (file_exists($secretConfigFile)) {
        require_once $secretConfigFile;
    }

    if (defined('PHACHE_API_SECRET_TOKEN') && hash_equals(PHACHE_API_SECRET_TOKEN, $receivedToken)) {
        // Enforce default rate limit
        pl_enforce_rate_limit($receivedToken . '_' . $clientIp, 60, 60);

        return array(
            'id' => 0,
            'key_name' => 'Legacy Secret Token (Fallback)',
            'scope' => 'all',
            'rate_limit' => 60,
            'status' => 1
        );
    }

    // 4. Token Invalid
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error'   => 'Unauthorized: Invalid Bearer API token.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
