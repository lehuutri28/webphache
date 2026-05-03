# Legacy phache.com.vn — PHP code cũ

> Letri 27/4: "website phache.com.vn code cũ lâu năm PHP"

## Cách upload code legacy

### Cách 1 — git clone từ repo hiện tại (nếu có)
```bash
cd /Users/letri/Projects/erp-project/claude/phache-website/legacy
git clone <repo-url> .
```

### Cách 2 — copy thư mục từ Mac/server cũ
```bash
rsync -av --exclude=node_modules --exclude=.git \
  ~/path/to/phache-old/ \
  /Users/letri/Projects/erp-project/claude/phache-website/legacy/
```

### Cách 3 — tải file zip rồi giải nén
```bash
cd /Users/letri/Projects/erp-project/claude/phache-website/legacy
unzip ~/Downloads/phache-old.zip
```

## Sau khi upload

Em (Opus) sẽ tự động:
1. Inspect tech stack (PHP version, framework: Laravel/CodeIgniter/CakePHP/raw PHP?)
2. Liệt kê endpoint hiện có
3. Liệt kê DB schema (mysql.sql / migrations / Eloquent models)
4. Đề xuất plan tích hợp ERP — endpoint nào cần thêm, file nào cần sửa
5. Build wrapper PHP gọi API ERP

## Cấu trúc dự kiến phache PHP

```
legacy/
├── public/                # web root (index.php)
├── src/ hoặc app/         # business logic
├── config/                # DB + API keys
├── views/ hoặc templates/ # blade/twig/php template
├── database/              # SQL dump + migration
└── composer.json hoặc cấu hình PHP
```

## RULE
- KHÔNG sửa code legacy directly — chỉ ADD wrapper trong `../integration/`
- KHÔNG xoá file legacy — archive only (theo rule no-delete-archive-only)
- KHÔNG commit credentials thật vào git — dùng `.env.example`

→ Letri upload code → em check tech stack → spec integration.
