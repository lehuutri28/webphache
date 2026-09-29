# BÁO CÁO PHÂN TÍCH & KẾ HOẠCH TRIỂN KHAI — phache.com.vn

**Mục tiêu dự án**: Tích hợp **chatbot AI** vào website phache.com.vn và **đẩy data từ form liên hệ về CRM** (chạy trên VPS) để chăm sóc khách hàng.

**Khách hàng**: PASSION LINK — Dạy Pha Chế (Trà sữa, Cà phê, Kem)
**Tài liệu này dành cho**: Claude Code agent triển khai dự án + dev cùng đọc.
**Phiên bản**: 1.1 — đã pull đầy đủ source code, .htaccess, index.php, sitemap.xml và 3 DB dumps.

---

## 0. ADDENDUM v1.1 — Phát hiện sau khi pull đủ root files + DB

### 0.1 URL Pattern thật (từ `.htaccess`) — quan trọng cho preserve backlink

```
.html-style URL ↔ index.php query string mapping:

/page-slug.html                              →  index.php?p=page-slug
/page-slug/trang/N                           →  index.php?p=page-slug&pageNum=N
/page-slug/N/article-slug.html               →  index.php?p=page-slug&id=N
/page-slug/article-slug-N.html               →  index.php?p=page-slug&id=N (dấu - phía trước id)
/page-slug/N/article-slug/trang/M            →  pagination cho detail
/administrator                               →  index.php?t=admin
/* (HTTP)                                    →  /* (HTTPS) [301]
www.phache.com.vn/*                          →  phache.com.vn/* [301]
```

→ **Mọi URL .html hiện tại Google đã index sẽ vẫn hoạt động** miễn là KHÔNG đổi pattern này. Sitemap có **216 URL** đang index.

### 0.2 Database thật (3 schemas)

| DB | Tables | Mục đích |
|---|---|---|
| **`pha5bd2c_db`** | 11 | DB chính phache.com.vn |
| `pha5bd2c_tonghop` | 38 (prefix `wpnb_`) | **WordPress LMS riêng** (MasterStudy + Paid Memberships Pro) — KHÁC site phache, có thể trên subdomain |
| `pha5bd2c_wc` | 37 | **Site ecommerce Laravel riêng** (wecha drink) — KHÔNG liên quan dự án |

→ **Scope chatbot + CRM chỉ áp dụng cho `pha5bd2c_db`** (phache.com.vn). 2 DB còn lại không động vào.

### 0.3 11 tables trong `pha5bd2c_db`

```
form_advisory          ⭐ 3,032 rows — lead "đăng ký/tư vấn nhanh"
form_sign              ⭐ 98,460 rows — lead "đăng ký học viên"  
call_to_action         ⭐ 225 rows — lead CTA (form rời, prefix cta_*)
support_online         module chat 2011 (Yahoo/Skype) — đã chết
news, page, page_type  CMS content
gallery, gallery_imgs  media
cms_info               site config
trainee_evaluate       đánh giá học viên
```

**Tổng lead lịch sử: ~101,717 records** — quý cho CRM migration sau này.

### 0.4 Schema chính xác 3 lead tables (đã verify từ DB dump)

```sql
form_advisory:
  advisory_id INT AI PK
  advisory_name TEXT, advisory_email TEXT, advisory_number TEXT
  advisory_demand VARCHAR(255)    -- 'Đăng ký khóa học' | 'Cần tư vấn khóa học'
  advisory_content TEXT           -- nội dung câu hỏi
  viewed TINYINT DEFAULT 0
  FULLTEXT(name,email,number,content)
  
form_sign:
  sign_id INT AI PK
  sign_name TEXT, sign_email TEXT, sign_number VARCHAR(255)
  sign_birthday TEXT, sign_home TEXT
  sign_day VARCHAR(50)            -- ngày khai giảng
  viewed TINYINT DEFAULT 0
  
call_to_action:                   -- naming khác (cta_* không phải pattern advisory_*)
  cta_id INT AI PK
  cta_name VARCHAR(255), cta_phone VARCHAR(255)
  cta_course VARCHAR(255)         -- khóa học khách quan tâm
  cta_purpose VARCHAR(255)
  cta_create DATETIME
```

→ Phase A `CrmClient::push()` cần wrap CẢ 3 hàm save (advisory, sign, **callToAction**), không chỉ 2 như spec ban đầu.

### 0.5 Sự thật về form Contact

❌ **Không có table `form_contact`** trong DB. Form `template/contact.php` chỉ là UI shell, không có `template_function` hidden field, submit không lưu đâu cả → **bug cũ hoặc form chưa được hoàn thành**.

→ Khi làm Phase A, **bonus task**: hoặc disable form contact, hoặc tạo table mới + saveContact() để khớp UI hiện có.

### 0.6 File entry `index.php` (2.9 KB)

Routing tối giản:
```
$_GET['t'] = 'client' (default) | 'admin' | 'ajax'
$_GET['p'] = page slug

t=client → load template/index.php (layout) qua init_top → init_bottom
t=admin  → load admin/index.php (yêu cầu session ss_admin)
t=ajax   → ajax dispatcher (gọi function theo $template name)
```

### 0.7 Bonus: full backup từ 2021 trong Downloads

`Downloads/backup-11.11.2021_15-13-08_pha5bd2c.tar.gz` (1.4 GB) — full account backup từ Mat Bao tự tạo. Có thể extract để so sánh với code hiện tại nếu muốn. Không bắt buộc cho dự án.

---

## 1. EXECUTIVE SUMMARY

### Hiện trạng
- Website chạy trên **shared hosting Mat Bao** (cPanel, server `sg-premium6.cloudnetwork.vn`).
- Source code: **Custom PHP từ 2011** (tác giả "asmking"), pattern include cổ điển, dùng `mysqli` thuần, không dùng framework hiện đại (Laravel/Symfony/CodeIgniter).
- 340 file PHP, ~57.000 dòng code, 34 MB sau khi nén.
- Database: 3 schema MySQL — `pha5bd2c_db` (chính, 13 MB), `pha5bd2c_tonghop` (23 MB), `pha5bd2c_wc` (1 MB).
- Hosting đang dùng **5.6 GB / 6 GB disk** — chỉ còn 511 MB → cần cẩn trọng khi deploy.
- 3 form thu lead đã có sẵn và đang lưu vào DB: `form_advisory`, `form_sign`, `form_contact` (predicted).

### Phạm vi công việc
**Phase A** (ưu tiên, ~3 giờ code + test): Lead capture forms hiện có push thêm webhook về CRM trên VPS, không thay đổi UX form.

**Phase B** (~6-8 giờ code + test): Thêm widget chatbot AI (popup góc dưới phải), trả lời tự động, khi user để lại contact thì tự push CRM giống Phase A, lưu hội thoại để sales đọc context.

### Các quyết định cần user xác nhận trước khi code (xem mục 8)
- URL endpoint CRM + auth method
- Mapping field giữa form ↔ CRM
- AI provider cho chatbot (Claude / OpenAI)
- Knowledge base cho bot (auto-crawl hay manual)

---

## 2. KIẾN TRÚC CODEBASE

### 2.1 Cấu trúc thư mục

```
legacy/source/
├── admin/                  Admin panel (33 module: advisory, news, page, etc.)
│   ├── func/
│   │   ├── ajax.php        367 dòng — AJAX endpoints cho admin
│   │   └── template.php    1374 dòng — save/edit functions cho admin
│   ├── init/               Init logic theo từng page admin
│   ├── bootstrap/          CSS framework
│   ├── *.php               File template cho mỗi page admin
│   └── style/, js/, img/
├── config/
│   ├── db.php              ⚠️ MySQL credentials hardcoded
│   ├── init.php            Define các constants (PRODUCT_DIR, ADMIN_*, etc.)
│   └── .htaccess           Block access trực tiếp
├── includes/
│   └── functions/
│       └── news.functions.php
├── library/
│   ├── class/              Captcha, HtmlPurifier, Pagination, PHPMailer, TimThumb, Upload
│   └── function/
│       └── db.php          ⭐ 1218 dòng, 86 functions — TOÀN BỘ data layer
├── template/               Front-end public site
│   ├── func/
│   │   ├── ajax.php
│   │   ├── index.php       Helper functions chung
│   │   └── template.php    ⭐ Save functions (saveAdvisory, saveSign, etc.)
│   ├── init/               Init logic theo từng page front-end
│   │   ├── home.php, contact.php, gallery.php, news.php, html.php, search.php
│   │   └── index_top.php   ⭐ DISPATCHER chính
│   ├── style/, js/, img/, fonts/, media/
│   ├── *.php               File layout cho mỗi page (home, contact, advisory, news, gallery, sign…)
│   └── suachatbox          File HTML wrapper cũ (không phải chatbox thật, chỉ là layout dự phòng)
├── khoahoc/                Landing page khóa học
├── day-pha-che-online/     Landing page khóa học online
├── day-pha-che-tai-quan/   Landing page khóa học tại quán
└── PHPMailer/              PHPMailer 5.x (cũ, có thể cân nhắc upgrade)
```

### 2.2 Pattern hoạt động

**Bootstrap & Routing** (cốt lõi nằm trong `template/init/index_top.php`):

```php
// 1. Init constants từ config/init.php
// 2. Lấy info page hiện tại từ URL (qua $_GET, mod_rewrite)
// 3. Load template/func/{file}.php nếu tồn tại (functions cho page)
// 4. Nếu POST có 'template_function', call dynamic function:
if (isset($_POST['template_function']) && function_exists($_POST['template_function'])) {
    $dataSend = $_POST['template_function']();
}
// 5. Load template/init/{file}.php (init logic page)
// 6. Render template/{file}.php (HTML)
```

→ **Mọi form submit đều dùng pattern**: `<input type="hidden" name="template_function" value="saveAdvisory" />`. Function được gọi dynamic từ POST data — đây là lỗ hổng tiềm năng cần lưu ý nhưng KHÔNG phải scope task này.

**Data layer** (toàn bộ trong `library/function/db.php`):

86 functions theo pattern CRUD cho từng entity:
- `getAdvisory()`, `insertAdvisory()`, `updateAdvisory()`, `deleteAdvisory()`
- `getSign()`, `insertSign()`, `updateSign()`, `deleteSign()`
- `getContact()`, `insertContact()`, ... (giả định)
- `getOrder()`, `insertOrder()`, ...
- `getPage()`, `getNews()`, `getProduct()`, `getGallery()`, ...

Tất cả đều dùng `$GLOBALS['obMySQLi']` (singleton mysqli instance từ `config/db.php`).

Ví dụ:
```php
function insertAdvisory($strFields, $strValues) {
    $sql = "INSERT INTO `form_advisory` ($strFields) VALUES ($strValues);";
    $GLOBALS['obMySQLi']->query($sql) or die(ERROR_DB.'[Them 1 khach hang]');
}
```

⚠️ **An toàn**: Input chỉ qua `stripQuotes()` + `strip_tags()` — quote không escape đúng, có nguy cơ SQL injection nếu user nhập backtick/special chars. Trong scope task này chỉ ghi nhận, **không sửa** trừ khi user yêu cầu.

---

## 3. CÁC FORM HIỆN CÓ (LEAD CAPTURE)

### 3.1 Form "Đăng ký / Tư vấn nhanh" (Advisory)

- **Trang**: `template/advisory.php` (panel block, hiển thị trong sidebar nhiều trang)
- **Save function**: `saveAdvisory()` ở `template/func/template.php` line 117
- **Insert function**: `insertAdvisory()` ở `library/function/db.php` line 1133
- **Bảng DB**: `form_advisory`
- **Fields**:

| HTML name | Field DB | Yêu cầu |
|---|---|---|
| `txt_name` | `advisory_name` | * |
| `txt_email` | `advisory_email` | * |
| `txt_number` | `advisory_number` | * |
| `sel_demand` | `advisory_demand` | * (Đăng ký khóa học / Cần tư vấn khóa học) |
| `txt_content` | `advisory_content` | optional |
| `txt_captcha` | (chỉ validate, không lưu) | * |

### 3.2 Form "Đăng ký học viên" (Sign)

- **Trang**: `template/sign.php`
- **Save function**: `saveSign()` ở `template/func/template.php` line 177
- **Insert function**: `insertSign()` ở `library/function/db.php` line 1190
- **Bảng DB**: `form_sign`
- **Fields** (từ code đã xem):

| HTML name | Field DB | Yêu cầu |
|---|---|---|
| `dk_name` | `sign_name` | * |
| `dk_email` | `sign_email` | * |
| `dk_number` | `sign_number` | * |
| `dk_birthday` | `sign_birthday` | optional |
| `dk_home` | `sign_home` | optional |
| `dk_class` | `sign_day` | optional |

### 3.3 Form "Liên hệ" (Contact) — chính là "box liên hệ" user nói

- **Trang**: `template/contact.php`
- **Save function**: `saveContact()` (cần xác nhận tên thực tế trong code)
- **Bảng DB**: `form_contact` (predicted)
- **Fields** (từ HTML đã xem):

| HTML name | Field dự kiến | Yêu cầu |
|---|---|---|
| `txt_name` | `contact_name` | * |
| `txt_phone` | `contact_phone` | * |
| `txt_email` | `contact_email` | * |
| `txta_address` | `contact_address` | * (nếu có sản phẩm) |
| (message) | `contact_message` | optional |

### 3.4 Form "Đặt hàng sản phẩm" (Order) — bonus

Có module Order tích hợp với Cart (`$_SESSION['ss_cart']`). Nếu user dùng để bán khóa học/sản phẩm thì cũng nên push lead về CRM.

---

## 4. PHASE A — TÍCH HỢP CRM WEBHOOK

### 4.1 Mục tiêu
Khi 1 form được submit thành công và data đã ghi vào DB, **gọi thêm 1 HTTP POST** sang CRM (trên VPS user) để CRM tự động tạo lead/contact record. Không thay đổi UX form, không block user nếu CRM offline.

### 4.2 Thiết kế kỹ thuật

**File mới**: `library/class/crm/CrmClient.php`

```php
<?php
/**
 * CrmClient — gửi lead về CRM trên VPS khi form được submit.
 * 
 * Thiết kế:
 *  - HTTP POST JSON tới CRM_ENDPOINT
 *  - Auth qua header (Bearer/X-API-Key — quyết định ở config)
 *  - Timeout ngắn (5s) để không block user
 *  - Retry 1 lần khi 5xx
 *  - Log mọi lỗi vào file (không die)
 *  - Không throw exception ra ngoài (fire-and-forget pattern)
 */
class CrmClient {
    
    public static function push($source, array $lead) {
        $payload = [
            'source'     => $source,           // 'advisory' | 'sign' | 'contact' | 'chatbot'
            'name'       => $lead['name']  ?? null,
            'phone'      => $lead['phone'] ?? null,
            'email'      => $lead['email'] ?? null,
            'message'    => $lead['message'] ?? null,
            'metadata'   => $lead['metadata'] ?? [],   // demand, address, conversation, utm…
            'created_at' => date('c'),
            'origin_url' => $_SERVER['HTTP_REFERER'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'ip'         => $_SERVER['REMOTE_ADDR'] ?? null,
        ];
        
        $ch = curl_init(CRM_ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . CRM_API_KEY,   // hoặc X-API-Key tuỳ user
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_FAILONERROR    => false,
        ]);
        
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);
        
        if ($code >= 200 && $code < 300) {
            self::log('OK', $source, $code);
            return true;
        }
        
        self::log('FAIL', $source, $code, $err, substr($body, 0, 500));
        return false;
    }
    
    private static function log($level, $source, $code, $err = null, $body = null) {
        $line = sprintf("[%s] %s %s code=%s err=%s body=%s\n",
            date('Y-m-d H:i:s'), $level, $source, $code, $err, $body);
        error_log($line, 3, DOCROOT . 'logs/crm.log');
    }
}
```

**File mới**: `config/crm.php` (tách credentials khỏi code)

```php
<?php
// CHỈNH ở đây cho production
define('CRM_ENDPOINT', 'https://crm.yourvps.com/api/leads');
define('CRM_API_KEY',  '__REPLACE_ME__');
```

**Sửa `template/func/template.php`** — chỉ thêm 4-5 dòng/function, không động vào logic cũ:

```php
function saveAdvisory() {
    // ... existing code (validate, insertAdvisory) ...
    
    if (empty($error)) {
        // ... existing insert ...
        insertAdvisory($strFields, $strValues);
        
        // === NEW: push to CRM (fire-and-forget) ===
        require_once CLASS_DIR . 'crm/CrmClient.php';
        CrmClient::push('advisory', [
            'name'    => $arrInput['advisory_name'],
            'email'   => $arrInput['advisory_email'],
            'phone'   => $arrInput['advisory_number'],
            'message' => $arrInput['advisory_content'],
            'metadata' => [
                'demand' => $arrInput['advisory_demand'],
            ],
        ]);
        
        // ... existing redirect ...
    }
}
```

Lặp tương tự cho `saveSign()` và `saveContact()`.

### 4.3 Checklist deploy Phase A

1. Tạo folder `logs/` ngoài public_html (hoặc trong nhưng có .htaccess deny)
2. Upload `library/class/crm/CrmClient.php`
3. Upload `config/crm.php` đã điền credentials thật
4. Sửa `template/func/template.php` (3 functions)
5. Sửa `admin/func/template.php` nếu admin cũng tạo lead manual
6. Test: submit form từ trang test → check `logs/crm.log` → check CRM nhận được lead

### 4.4 Rủi ro & xử lý
| Rủi ro | Mitigation |
|---|---|
| CRM offline → user thấy chậm | Timeout 5s + fire-and-forget, không block redirect |
| Duplicate lead khi user submit 2 lần | CRM tự dedupe bằng email+phone, hoặc thêm `idempotency_key = md5(email+phone+timestamp)` |
| API key leak qua git | `config/crm.php` thêm vào `.gitignore`; sample là `config/crm.php.example` |
| Hosting đầy 511 MB → log file phình | Logrotate trong cron: giữ 7 ngày |

---

## 5. PHASE B — CHATBOT AI WIDGET

### 5.1 UX

- Bubble góc dưới phải mọi page (z-index cao, position fixed)
- Click → mở khung chat 350x500px
- Greeting: "Chào bạn! Mình là trợ lý ảo của Passion Link. Bạn cần tư vấn khoá học nào? 🎓☕"
- Sau N-tin / khi user hỏi câu liên quan giá/đăng ký → bot xin tên + sđt → push CRM
- Hiển thị "đang gõ…" khi đợi AI response
- Mobile: full-screen modal khi tap

### 5.2 Kiến trúc

```
[Browser]                               [phache.com.vn server]
  Widget JS (vanilla)                    PHP API endpoints
  ─────────────                          ─────────────
  ChatWidget.js          ──POST──>      template/api/chatbot.php
                                           ├─ saveMessage(session_id, role, content)
                                           ├─ buildContext(session_id, knowledge_base)
                                           └─ callAI(messages) ───>  Claude/OpenAI API
                            <──JSON─    
  Hiển thị tin nhắn

  Khi đủ context (name+phone)            template/api/chatbot.php
  ──POST lead──>                            └─ CrmClient::push('chatbot', $lead)
```

### 5.3 Schema DB mới

```sql
CREATE TABLE `chat_session` (
  `session_id` varchar(40) NOT NULL,
  `created_at` datetime NOT NULL,
  `last_active` datetime NOT NULL,
  `lead_pushed` tinyint(1) DEFAULT 0,
  `name` varchar(255),
  `phone` varchar(50),
  `email` varchar(255),
  `referrer` varchar(500),
  `user_agent` varchar(500),
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `chat_message` (
  `id` int unsigned AUTO_INCREMENT PRIMARY KEY,
  `session_id` varchar(40) NOT NULL,
  `role` enum('user','assistant','system') NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL,
  KEY `idx_session` (`session_id`),
  CONSTRAINT FOREIGN KEY (`session_id`) REFERENCES `chat_session`(`session_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 5.4 File mới

```
template/
├── api/
│   ├── chatbot.php           Endpoint nhận POST từ widget
│   └── chatbot_stream.php    (optional) SSE streaming response
├── js/
│   └── chatbot-widget.js     Vanilla JS widget (~300 dòng)
├── style/
│   └── chatbot.css           Styling widget
└── chatbot-config.php        Inject script vào layout

library/class/ai/
├── ClaudeClient.php          Wrapper API Claude (recommend)
├── OpenAIClient.php          Wrapper API OpenAI (alternative)
└── KnowledgeBase.php         Build system prompt từ data site

includes/functions/
└── chat.functions.php        getChatSession, insertMessage, etc.
```

### 5.5 System prompt (sample, tiếng Việt)

```
Bạn là trợ lý tư vấn của trung tâm dạy pha chế Passion Link.
- Trung tâm tại 160 Hoàng Hoa Thám, Phường 12, Tân Bình, TPHCM.
- Dạy pha chế trà sữa, cà phê, kem; có khoá Online và Tại Quán.
- Học phí khoảng [đính kèm bảng giá nếu user cấp].

Quy tắc:
1. Trả lời ngắn gọn (≤3 câu), thân thiện, tự nhiên, ưu tiên tiếng Việt.
2. Khi user hỏi giá cụ thể từng khoá, hãy trả lời nếu KB có; không có thì xin
   tên + sđt + nói "Tư vấn viên sẽ báo giá chi tiết qua điện thoại trong giờ làm".
3. Nếu user thể hiện ý định đăng ký → xin tên + sđt + email.
4. Không được tự bịa giá, không hứa khuyến mãi không có trong KB.
5. Nếu user hỏi ngoài chủ đề pha chế → kéo về việc tư vấn khoá học.

Knowledge base:
[INJECT từ KnowledgeBase::build() — nội dung trang khoahoc/, day-pha-che-*]
```

### 5.6 Công cụ tools (function calling)

Cho AI gọi 1 tool để **lưu lead**:

```json
{
  "name": "save_lead",
  "description": "Lưu thông tin liên hệ khách khi đã thu thập đủ tên + sđt",
  "parameters": {
    "type": "object",
    "properties": {
      "name":  {"type": "string"},
      "phone": {"type": "string"},
      "email": {"type": "string"},
      "interest": {"type": "string", "description": "Khoá học khách quan tâm"}
    },
    "required": ["name", "phone"]
  }
}
```

Khi AI gọi tool này → backend `chatbot.php` chạy `CrmClient::push('chatbot', $lead)` → trả về `{"success": true}` cho AI để bot xác nhận với khách.

### 5.7 Estimate cost API
- Claude Haiku: ~$0.25/1M input tokens, $1.25/1M output. Với 200 chat/day, mỗi chat ~5 turns × 500 tokens ≈ 500K tokens/day → **~$0.40/ngày = $12/tháng**
- OpenAI GPT-4o-mini: rẻ hơn ~30%

---

## 6. PHASE C — DUMP DATABASE (PRE-REQ KHI CODE)

Trước khi code Phase A/B, cần **dump 3 DB hiện tại** để hiểu schema và test local:

### Cách lấy:
1. Vào cPanel → phpMyAdmin
2. Chọn từng DB (`pha5bd2c_db`, `pha5bd2c_tonghop`, `pha5bd2c_wc`)
3. Tab **Export** → SQL → Quick → Go
4. File rơi về `~/Downloads`, di chuyển vào `legacy/dumps/`

Hoặc nén tự động qua API (script đã verify hoạt động):
```js
// Trong console cPanel:
fetch('/cpsess.../execute/Mysql/dump_database?dbname=pha5bd2c_db')
```

### Setup local dev (khuyến nghị)
```bash
brew install mysql php httpd
brew services start mysql
mysql -u root < legacy/dumps/pha5bd2c_db.sql
# Sửa config/db.php cho local trỏ về root
php -S localhost:8000 -t legacy/source/
```

---

## 7. ROADMAP CHO CLAUDE CODE

### Sprint 1 — Setup & Phase A (1-2 ngày)

```
git init; git add .; git commit -m "Initial: pull from production"
git checkout -b feat/crm-webhook
```

**Prompt mẫu cho Claude Code agent**:

```
Repo: legacy/source/
Đọc REPORT.md trước.

Task: Thực hiện Phase A — tích hợp CrmClient.

Yêu cầu chi tiết:
1. Tạo file library/class/crm/CrmClient.php theo spec mục 4.2.
2. Tạo file config/crm.php và config/crm.php.example.
3. Sửa template/func/template.php — sau insertAdvisory/insertSign/insertContact, gọi CrmClient::push().
4. Tạo logs/.htaccess deny all + logs/.gitkeep.
5. Update .gitignore: thêm config/crm.php và logs/*.log.
6. Viết test script test_crm.php gửi 1 sample lead → in HTTP code.

Constraints:
- Không thay đổi file ngoài scope kể trên.
- Giữ nguyên indent style của codebase (4 spaces, không tab).
- Không đụng vào logic validate/captcha hiện có.
- Code PHP 7.4-compatible (server đang chạy 7.x).

Verification step (bắt buộc): chạy `php -l` trên mỗi file đã sửa.
```

### Sprint 2 — Phase B chatbot (2-3 ngày)

```
git checkout -b feat/chatbot
```

**Prompt mẫu**:

```
Repo: legacy/source/
Đã hoàn thành Phase A. Đọc REPORT.md mục 5 trước.

Task: Phase B — chatbot AI widget.

Subtasks (làm theo thứ tự, commit từng cái):
1. Migration: tạo file migrations/001_chat_tables.sql theo schema mục 5.3.
2. Backend:
   - library/class/ai/ClaudeClient.php — wrapper API anthropic.
   - library/class/ai/KnowledgeBase.php — đọc các page từ table page và export thành text block.
   - includes/functions/chat.functions.php — CRUD cho chat_session, chat_message.
   - template/api/chatbot.php — endpoint POST {session_id, message} → AI → response.
3. Frontend:
   - template/js/chatbot-widget.js — vanilla JS widget self-contained.
   - template/style/chatbot.css.
   - Inject vào layout chính: tìm trong template/index.php chỗ trước </body>, thêm <script src="...chatbot-widget.js"></script>.
4. Function calling save_lead → CrmClient::push (đã có ở Phase A).

Constraints:
- Vanilla JS, không thêm React/Vue/jQuery (jQuery có sẵn nhưng widget tự đứng).
- Mobile responsive.
- Không lưu API key vào git.

Verification: 
- chrome devtools test 5 mock dialogs.
- check chat_message bảng có row mới sau test.
- check logs/crm.log có entry "OK chatbot" sau khi save_lead.
```

### Sprint 3 — Test & Deploy (0.5 ngày)

```
- Cron logrotate cho logs/crm.log.
- Smoke test 4 form trên staging.
- Deploy: rsync chỉ những file đã đổi (KHÔNG đẩy upload/, demo/).
- Verify trên prod: submit form thật, check CRM nhận lead.
```

---

## 8. DECISIONS USER CẦN XÁC NHẬN

Trước khi Claude Code bắt đầu Sprint 1, user cần trả lời **4 câu**:

1. **CRM**: 
   - URL endpoint nhận lead: `___________________`
   - Auth method: `[ ] Bearer Token  [ ] X-API-Key header  [ ] Basic Auth  [ ] Khác: ____`
   - API key/token: `___________________`
   - Có Postman collection / OpenAPI spec không? Nếu có, attach vào `legacy/docs/crm-api.md`.

2. **Field mapping**: CRM của bạn nhận field name gì cho 1 lead? (Tên đề xuất ở mục 4.2 là `name/phone/email/source/metadata` — nếu CRM bạn dùng tên khác thì điền dưới đây)
   - name → `___`
   - phone → `___`
   - email → `___`
   - source → `___`
   - notes/message → `___`

3. **AI provider chatbot**:
   - `[ ] Claude (Anthropic)`  — recommend cho TV chuẩn + reasoning
   - `[ ] GPT-4o-mini (OpenAI)` — rẻ hơn 30%, ổn cho conv ngắn
   - `[ ] Cả 2 (fallback)`
   - API key đã có chưa? Nếu chưa, tạo tại: https://console.anthropic.com hoặc https://platform.openai.com

4. **Knowledge base bot**: 
   - `[ ] Auto-build từ pages trong DB` (nội dung khoahoc/, day-pha-che-*)
   - `[ ] Manual: tôi attach file FAQ.md / brochure.pdf vào legacy/docs/`
   - `[ ] Cả 2`
   - Bot có được phép quote giá khoá học cụ thể không? `[ ] Có  [ ] Luôn nói "tư vấn viên sẽ báo lại"`

---

## 9. CÁC GHI CHÚ AN TOÀN & DEBT KỸ THUẬT

Không nằm trong scope task hiện tại, nhưng nên ghi nhận để xử lý sau:

| Vấn đề | Mức độ | Khuyến nghị |
|---|---|---|
| **MySQL credentials hardcoded** trong `config/db.php` | High | Move sang env var / file ngoài public_html |
| **`stripQuotes` + `strip_tags`** không escape đủ → SQL injection nhẹ | High | Dùng prepared statements (mysqli prepare/bind) |
| **`create_function`** trong saveAdvisory (deprecated từ PHP 7.2) | Medium | Replace bằng arrow function / closure |
| **PHPMailer 5.x** quá cũ | Medium | Upgrade lên 6.x |
| **Mã facebook pixel** lưu trong file plain `backup ma facebook pixel` | Low | Move vào DB hoặc admin setting |
| **Disk hosting 92% đầy** | Critical | Xóa `backup/`, `demo/`, `demoold/` zip files (đã ngoài scope nhưng cần làm) |
| **DB password đã xuất hiện trong session chat hôm nay** | High | **Đổi mật khẩu DB ngay sau khi user xong project** |

---

## 10. PHỤ LỤC

### A. Server info
- Host: `sg-premium6.cloudnetwork.vn`
- IP: `172.104.53.229`
- cPanel user: `pha5bd2c`
- Home dir: `/home/pha5bd2c/`
- Document root: `/home/pha5bd2c/public_html/`
- PHP shell: `/bin/bash` (full shell access — có thể dùng SSH nếu cần)
- 3 databases: `pha5bd2c_db`, `pha5bd2c_tonghop`, `pha5bd2c_wc`

### B. File quan trọng đã review
- `library/function/db.php` — 1218 dòng, 86 functions
- `template/init/index_top.php` — bootstrap chính
- `template/func/template.php` — saveX functions
- `admin/func/template.php` — saveX1 functions (admin)
- `template/contact.php`, `template/advisory.php`, `template/sign.php` — UI form
- `config/db.php` — DB connection (⚠️ chứa password)
- `config/init.php` — constants

### C. Lệnh shell hữu ích cho Claude Code

```bash
# Kiểm tra cấu trúc nhanh
find legacy/source -name "*.php" | wc -l   # 340 file
wc -l legacy/source/library/function/db.php  # 1218 dòng

# Tìm function bằng tên
grep -rn "function saveContact" legacy/source/

# Lint toàn project
find legacy/source -name "*.php" -exec php -l {} \; | grep -v "No syntax errors"

# Backup before edit
cp -r legacy/source legacy/source.bak.$(date +%s)
```

### D. Liên hệ rõ ràng
- Domain: phache.com.vn
- Email công ty: admin@phache.com.vn
- ĐT: 0908-924-460
- Địa chỉ: 160 Hoàng Hoa Thám, P.12, Tân Bình, TPHCM

---

**Tài liệu này được sinh tự động ngày 2026-04-29 sau khi Claude (Cowork mode) phân tích source code phache.com.vn.** Có chỉnh sửa nào nên cập nhật tài liệu cùng commit để Claude Code đọc đúng phiên bản mới nhất.
