# Architecture — Phache ↔ Wecha ERP integration

**Phiên bản**: 1.0 — 2026-05-02
**Plan ref**: `~/.claude/plans/lively-leaping-squirrel.md`
**Brief**: `orchestrator-ceo/briefs/2026-05-02-BRIEF-PHACHE-WEBSITE-REDESIGN-ERP.md`

## Tổng quan

```
                      ┌──────────────────────────────────────┐
                      │  KHÁCH HÀNG WEB (browser)            │
                      └───────────────┬──────────────────────┘
                                      │ HTTPS
                                      ▼
       ┌──────────────────────────────────────────────────────┐
       │  phache.com.vn (Mat Bao shared, PHP 5.6 raw 2011)    │
       │                                                       │
       │  ┌─ template/ ─────────────────────────────────────┐ │
       │  │  index.php → init_top → page.php               │ │
       │  │  api/customer-lookup.php  ◄── B.3 auto-fill    │ │
       │  │  api/courses-list.php     ◄── B.4 cache+flock  │ │
       │  │  style/wecha-glass-overlay.css ◄── B.2 theme   │ │
       │  │  js/auto-fill.js          ◄── B.3 frontend     │ │
       │  └─────────────────────────────────────────────────┘ │
       │                                                       │
       │  ┌─ library/function/ ─────────────────────────────┐ │
       │  │  normalize_phone.php  ◄── A.2 helper            │ │
       │  │  validate_lead.php    ◄── A.2 chặn rác          │ │
       │  │  CrmClient.php        ◄── A.3 HTTP wrapper      │ │
       │  │  CrmRetryWorker.php   ◄── A.5 cron 5 phút       │ │
       │  │  db.php (cũ)          ─── insert* functions     │ │
       │  └─────────────────────────────────────────────────┘ │
       │                                                       │
       │  ┌─ pha5bd2c_db (MySQL) ──────────────────────────┐  │
       │  │  form_advisory (3k) form_sign (98k) cta (225) │  │
       │  └────────────────────────────────────────────────┘  │
       │                                                       │
       │  ┌─ logs/crm-queue/ ──────────────────────────────┐  │
       │  │  Fail-safe queue khi ERP down                  │  │
       │  └────────────────────────────────────────────────┘  │
       └──────────────────────────────────────────────────────┘
                                      │ HTTPS + Bearer JWT + X-Database-Id
                                      ▼
       ┌──────────────────────────────────────────────────────┐
       │  erp.wecha.vn/api (NestJS, tenant Passion Link)      │
       │                                                       │
       │  GET  /customers?search=<phone>                      │
       │  POST /customers (classification=lead_tiem_nang)     │
       │  POST /customers/:id/interactions                    │
       │  GET  /courses/available?branch_id=<uuid>            │
       │                                                       │
       │  Service account: service-phache@passionlink.vn      │
       │  JWT TTL 365d, scope=all, perms=customers.read+write,│
       │    customer-crm.interactions.write,                  │
       │    courses.available.read                            │
       └──────────────────────────────────────────────────────┘
```

## Auth flow (UPDATED 2/5/2026 — theo Opus CEO ERP brief)

```
[Phache PHP CrmClient] ──── HTTP request ───▶ [erp.wecha.vn/api]
   │                                              │
   │ Headers:                                     │
   │   Authorization: Bearer <30d JWT>            │
   │   X-Source: phache.com.vn                    │
   │                                              │
   │                                              ├─ JwtAuthGuard verify JWT
   │                                              ├─ Extract user.tenantId = Vua An Toàn
   │                                              ├─ Check role 'phache_web_service'
   │                                              ├─ Filter perm: customers.view/create
   │                                              │   + customer_crm.interactions.write
   │                                              │   + courses.available.read
   │                                              ├─ Rate limit: 60 req/phút
   │                                              └─ Filter query theo tenantId
```

### Service account (chốt 2/5/2026)

| Field | Giá trị |
|-------|---------|
| Email | `service-phache@vuaantoan.com` |
| Tenant | `2eb99a91-6c1f-4c60-89c5-f19a6a3ee62f` (Vua An Toàn) |
| Role | `phache_web_service` (scope=all, 4 perm tối thiểu) |
| JWT TTL | **30 ngày + AUTO-ROTATE** |
| Rate limit | 60 req/phút server-side |

### Auto-rotate flow

```
TokenRotator.php (cron 0 3 * * * — 3h sáng mỗi ngày)
   │
   ├─ Đọc JWT hiện tại (cache/jwt-current.txt → fallback env WECHA_ERP_JWT)
   ├─ Decode payload base64 → check exp
   │
   ├─ Còn > 5 ngày trước expire → SKIP, exit 0
   │
   └─ ≤ 5 ngày trước expire → POST /auth/service-token/rotate
       │
       ├─ ERP verify token còn valid + role phache_web_service còn perm rotate
       ├─ Mint token mới TTL 30d
       ├─ Mark token cũ expire sau 6h grace period
       └─ Return { token: "<new-jwt>" }
       │
       ├─ Phache atomic write cache/jwt-current.txt (chmod 600)
       │   tmp file → rename → no race condition
       └─ CrmClient lần sau khởi tạo sẽ đọc token mới tự động
```

### Permissions tối thiểu (Opus CEO ERP brief mục 27-46)

```json
[
  "customers.view",                  // tra cứu KH theo SĐT
  "customers.create",                // tạo customer mới khi lead chuyển đổi
  "customer_crm.interactions.write", // ghi interaction "lead-from-website"
  "courses.available.read"           // list khoá học public
]
```

**KHÔNG cấp**: `customers.delete/export`, `orders.*`, `inventory.*`, `manufacturing.*`, `users.*`, `roles.*`, `customers.view_debt`, `customers.view_secret`.

⚠️ **2 BLOCKER Opus phải verify TRƯỚC khi cấp JWT (Brief mục 47-77)**:
1. **Endpoint `/courses/available`**: với JWT scope=all role hạn chế, có filter `tenantId` đúng không? (KHÔNG được leak khoá tenant khác)
2. **Endpoint `/customers?search=`** (commit `b5061cb` đã sửa scope=all): output phải ẩn `secretReason`, `assignedToUserId` cho non-Manager.

## Data flow Phase A — Lead push

```
1. User submit form contact/advisory/sign/cta
   │
   ▼
2. PHP saveCallToAction()/saveAdvisory()/saveSign()
   │
   ├─ validate_lead() ── A.2 ──┐
   │                           ▼
   │                       Reject nếu rác (HTTP 400)
   │
   ├─ insert<Form>() ── lưu DB phache cũ (KHÔNG đổi)
   │
   └─ try { CrmClient::pushLead() } catch (...) { error_log }
      │
      ├─ GET /customers?search=<phone>
      │   ├─ Match → POST /customers/:id/interactions (referenceType=lead_web)
      │   └─ No match → POST /customers (lead_tiem_nang) + interaction
      │
      └─ Fail (5xx/timeout/no creds) → enqueue file logs/crm-queue/<uuid>.json
                                       │
                                       ▼
                              Cron 5 phút (CrmRetryWorker.php)
                                       │
                                       └─ retry exp backoff, max 10 attempts
```

## Data flow Phase B — Auto-fill

```
1. User blur phone field
   │
   ▼ (auto-fill.js)
2. JS normalizePhoneVN(raw) → phone hợp lệ?
   │
   ├─ No → showError(input)
   │
   └─ Yes → AJAX POST /template/api/customer-lookup.php { phone }
            │
            ▼
            Server-side CrmClient::findCustomerByPhone()
            │
            └─ GET /customers?search=<phone>&limit=1
               │
               ├─ Found → JSON { found:true, name, email, address }
               │   │
               │   ▼
               │   JS fill form fields (chỉ fill nếu trống — user có thể sửa)
               │
               └─ Not found → JSON { found:false } (UX: cho khách nhập mới)
```

## Files mapping

| Local path | Deploy target (Mat Bao) | Phase |
|------------|------------------------|-------|
| `integration/api-client/normalize_phone.php` | `/library/function/normalize_phone.php` | A.2 |
| `integration/api-client/validate_lead.php`   | `/library/function/validate_lead.php`   | A.2 |
| `integration/api-client/CrmClient.php`       | `/library/function/CrmClient.php`       | A.3 |
| `integration/api-client/CrmRetryWorker.php`  | `/library/function/CrmRetryWorker.php`  | A.5 |
| `integration/api-client/TokenRotator.php`    | `/library/function/TokenRotator.php`    | A.5 |
| `integration/api-client/.env.example`        | `/config/.env` (Letri tự gõ)            | A.1 |
| `integration/webhook/customer-lookup.php`    | `/template/api/customer-lookup.php`     | B.3 |
| `integration/webhook/courses-list.php`       | `/template/api/courses-list.php`        | B.4 |
| `integration/ui/wecha-glass-overlay.css`     | `/template/style/wecha-glass-overlay.css` | B.2 |
| `integration/ui/auto-fill.js`                | `/template/js/auto-fill.js`             | B.3 |
| `integration/sync/migrate-historical-leads.php` | LOCAL only — chạy 1 lần              | A.6 |
| `integration/legacy-patches/A4-wrap-save-functions.md` | instruction patch file cũ   | A.4 |

## SEO preserved

| Resource | Constraint |
|----------|-----------|
| 216 URL `.html` | KHÔNG đổi pattern |
| `.htaccess` | KHÔNG đụng |
| `<title>`, meta, schema.org JSON-LD | KHÔNG đổi |
| Class HTML cũ | KHÔNG đổi (CSS overlay áp qua selector class hiện có) |
| Internal links | KHÔNG đổi href |
| Backlink external | Bảo toàn vì URL pattern không đổi |

## Memory rules áp dụng

- ⭐⭐⭐ `feedback_no_password_in_chat` — JWT/.env Letri tự gõ
- ⭐⭐⭐ `feedback_no_delete_archive_only` — wrap KHÔNG sửa logic cũ
- ⭐⭐⭐ `feedback_ui_wecha_crystal_glass` — palette + font + glass effect
- ⭐⭐⭐ `feedback_hybrid_deploy_strategy` — code thêm + backup + smoke
- ⭐⭐ `feedback_kieudang_must_commit_immediately` — commit CSS ngay sau apply
- ⭐⭐ `feedback_letri_plain_vietnamese` — chat Letri tiếng Việt đời thường
