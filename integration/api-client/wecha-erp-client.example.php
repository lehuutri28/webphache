<?php
/**
 * Wecha ERP API Client (mẫu PHP) — gọi từ phache.com.vn
 *
 * Cách dùng:
 *   require_once 'wecha-erp-client.php';
 *   $erp = new WechaErpClient(getenv('WECHA_ERP_API_URL'), getenv('WECHA_ERP_API_KEY'));
 *
 *   // Tạo order khi khách đặt trên phache
 *   $orderId = $erp->createOrder([
 *       'customer_phone' => '0905123456',
 *       'items' => [['barcode' => '8938511025022', 'qty' => 2]],
 *       'branch_code' => 'NMVAT',
 *       'source' => 'phache_web'
 *   ]);
 *
 *   // Lấy SP để hiển thị catalog
 *   $products = $erp->listProducts(['category' => 'GOODS_VAT_TRADING', 'is_for_sale' => true]);
 *
 * Memory rule: feedback_security_nl_sx_no_leak_pos
 *   API ERP đã filter is_for_sale + category.is_for_sale + branch_products.link_purpose='sale'
 *   → Phache web KHÔNG có nguy cơ thấy NL sản xuất
 */

class WechaErpClient
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;

    public function __construct(string $baseUrl, string $apiKey, int $timeout = 30)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->timeout = $timeout;
    }

    public function createOrder(array $payload): array
    {
        return $this->request('POST', '/api/orders', $payload);
    }

    public function listProducts(array $filter = []): array
    {
        $query = http_build_query($filter);
        return $this->request('GET', '/api/catalog?' . $query);
    }

    public function getCustomerByPhone(string $phone): ?array
    {
        try {
            return $this->request('GET', '/api/customers?phone=' . urlencode($phone));
        } catch (\Exception $e) {
            return null;
        }
    }

    private function request(string $method, string $path, ?array $body = null): array
    {
        $ch = curl_init($this->baseUrl . $path);
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Source: phache.com.vn',
        ];
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
        }
        $resp = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new \RuntimeException("ERP API curl error: $err");
        }
        if ($code >= 400) {
            throw new \RuntimeException("ERP API HTTP $code: $resp");
        }
        return json_decode($resp, true) ?? [];
    }
}
