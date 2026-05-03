# Phache Website (phache.com.vn)

> Folder chứa code legacy + integration với ERP Vua An Toàn.
> Tạo: 2026-04-27 — chỉ đạo Letri.

## Mục đích

1. **Legacy** — lưu nguyên code cũ phache.com.vn (không sửa)
2. **Integration** — code mới liên kết tự động với ERP Wecha (đặt hàng → tạo order ERP, đồng bộ SP, sync KH)

## Cấu trúc

```
phache-website/
├── README.md (file này)
├── legacy/              # Code phache.com.vn cũ — chờ Letri upload/git clone
│   └── README.md
├── integration/         # Code mới tích hợp ERP
│   ├── webhook/         # Nhận event từ ERP (đơn mới, SP cập nhật, KH mới)
│   ├── api-client/      # Gọi API ERP (POST /api/orders, GET /api/products)
│   ├── sync/            # Đồng bộ batch (cron đêm pull data)
│   └── jobs/            # n8n workflow / pg-boss job định kỳ
└── docs/
    ├── architecture.md  # Sơ đồ tích hợp 2 chiều
    └── migration-plan.md # Kế hoạch chuyển từ phache cũ → ERP
```

## Tích hợp ERP — design pattern

### Luồng đặt hàng (Phache → ERP)
```
Khách đặt trên phache.com.vn
  → Phache backend POST https://erp.wecha.vn/api/orders
  → ERP tạo order + emit event order.created
  → ERP push notification về Phache (webhook callback)
```

### Sync sản phẩm (ERP → Phache)
```
Admin update SP trên ERP
  → ERP emit event product.updated
  → n8n workflow nhận → POST https://phache.com.vn/api/products/sync
  → Phache cache product cho hiển thị web
```

### Sync khách hàng (2 chiều)
```
KH mới đặt trên Phache → tạo customer ERP (1 chiều)
KH update info trên ERP → push về Phache (1 chiều)
```

## Authentication

- Phache → ERP: API key dài hạn (env `WECHA_ERP_API_KEY`)
- ERP → Phache: HMAC signed webhook (env `PHACHE_WEBHOOK_SECRET`)

## Status hiện tại

- [ ] Letri upload code phache cũ vào `legacy/`
- [ ] Inspect tech stack phache (PHP/Node/Static?)
- [ ] Build webhook receiver
- [ ] Build api-client
- [ ] Build sync job
- [ ] Test end-to-end
- [ ] Deploy

## Reference

- ERP API docs: https://erp.wecha.vn/api (Swagger)
- Memory: `project_chatbot_noi_bo_must_keep` (cần test sau deploy)
- File này thuộc git pos-system repo riêng — không phải module ERP NestJS
