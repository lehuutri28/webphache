# RESTORE GUIDE — phache.com.vn

**Mục tiêu**: Khôi phục lại site từ con số 0 trong **10-15 phút** trên hosting mới (cPanel hoặc VPS).

**Bundle này gồm**:
```
source/                      — toàn bộ code (1,420 file)
pha5bd2c_db.sql.gz           — DB chính phache.com.vn (1.8 MB → 11 MB)
pha5bd2c_tonghop.sql.gz      — DB WordPress LMS (5 MB → 24 MB)
pha5bd2c_wc.sql.gz           — DB Laravel ecommerce (44 KB → 184 KB)
REPORT.md                    — báo cáo phân tích kiến trúc + plan dev
RESTORE_GUIDE.md             — file này
```

**Còn thiếu (chỉ ảnh, không ảnh hưởng restore code/DB)**:
- `upload/` (~2.5 GB ảnh) — cần backup riêng qua FTP nếu muốn full restore visual

---

## QUY TRÌNH RESTORE (10-15 phút)

### Bước 1 — Upload code lên server mới (3-5 phút)

**Cách A: cPanel mới**
1. cPanel → File Manager → vào `public_html/`
2. Upload `phache_full_restore_*.tar.gz`
3. Chuột phải → Extract → chọn `public_html/` làm đích
4. Sau extract, **move toàn bộ files trong `public_html/source/` ra `public_html/`** rồi xóa `source/` rỗng
5. Đảm bảo các file ẩn `.htaccess` được upload (cPanel mặc định ẩn → bật "Show Hidden Files" trước)

**Cách B: SSH/SFTP**
```bash
scp phache_full_restore_*.tar.gz user@host:/home/USER/
ssh user@host
cd /home/USER/public_html/
tar -xzf ../phache_full_restore_*.tar.gz --strip-components=1 source/
mv RESTORE_GUIDE.md REPORT.md ..  # docs ngoài public
```

### Bước 2 — Tạo database + import (3-5 phút)

**cPanel**:
1. cPanel → MySQL Databases → tạo 3 DB:
   - `XYZ_db` (đổi prefix XYZ theo cPanel mới — Mat Bao gốc dùng `pha5bd2c_`)
   - `XYZ_tonghop`
   - `XYZ_wc`
2. Tạo 1 user MySQL `XYZ_un`, password mạnh
3. Add user vào cả 3 DB với quyền ALL PRIVILEGES
4. cPanel → phpMyAdmin → chọn DB `XYZ_db` → tab Import → chọn file `pha5bd2c_db.sql.gz` → Go
5. Lặp lại cho 2 DB còn lại

**SSH**:
```bash
mysql -u root -p -e "CREATE DATABASE XYZ_db; CREATE DATABASE XYZ_tonghop; CREATE DATABASE XYZ_wc;"
mysql -u root -p XYZ_db < <(gunzip -c pha5bd2c_db.sql.gz)
mysql -u root -p XYZ_tonghop < <(gunzip -c pha5bd2c_tonghop.sql.gz)
mysql -u root -p XYZ_wc < <(gunzip -c pha5bd2c_wc.sql.gz)
```

### Bước 3 — Sửa connection string (1 phút)

Mở file `public_html/config/db.php` và đổi:
```php
$obMySQLi = new mysqli(
    'localhost',         // giữ nguyên trừ khi DB ở host khác
    'XYZ_un',            // user MySQL mới (đổi)
    'NEW_PASSWORD',      // password mới (đổi)
    'XYZ_db'             // tên DB mới (đổi)
);
```

### Bước 4 — Kiểm tra (2 phút)

1. Mở `https://NEW_DOMAIN/` → phải thấy trang chủ
2. Mở `https://NEW_DOMAIN/lich-khai-giang.html` → phải thấy trang con (test mod_rewrite)
3. Mở `https://NEW_DOMAIN/index.php?t=admin` → phải thấy trang login admin
4. Submit thử form `template/advisory.php` → check phpMyAdmin → table `form_advisory` có row mới = OK

### Bước 5 — DNS (chỉ khi đổi server) (5 phút + đợi propagation)

Nếu đổi sang server/IP mới:
1. Domain registrar → A record → trỏ về IP mới
2. Đợi DNS propagation: 15 phút – vài giờ
3. cPanel mới → AutoSSL hoặc Let's Encrypt → tạo cert HTTPS

---

## NẾU CÓ LỖI THƯỜNG GẶP

| Lỗi | Nguyên nhân | Fix |
|---|---|---|
| 500 Internal Server Error | `.htaccess` mod_rewrite chưa enable | `a2enmod rewrite` (Apache) hoặc enable trong cPanel → Security → mod_rewrite |
| White page (trắng tinh) | DB connect fail | Check `config/db.php` credentials, check user có quyền vào DB chưa |
| `Failed to connect to MySQL` | DB không tồn tại / user sai | Tạo lại DB qua phpMyAdmin |
| Hình ảnh 404 | Folder `upload/` chưa upload | Upload riêng từ backup FTP (folder này không có trong bundle) |
| URL .html trả 404 | `.htaccess` thiếu hoặc bị disable | Verify file `.htaccess` ở root public_html, mode 644 |
| `create_function()` deprecated warning | PHP 8.0+ không support nữa | Dùng PHP 7.4 cho đến khi codebase được upgrade |

---

## KIỂM TRA INTEGRITY BUNDLE

Trước khi tin dùng restore, verify bundle không bị lỗi:
```bash
# Test extract toàn bộ vào /tmp:
tar -tzf phache_full_restore_*.tar.gz | wc -l
# Phải ra: 1690 (số file dự kiến)

# Test giải nén thật:
mkdir /tmp/test_restore && tar -xzf phache_full_restore_*.tar.gz -C /tmp/test_restore
ls /tmp/test_restore/source/    # phải thấy: admin/ template/ library/ ...
ls /tmp/test_restore/*.sql.gz   # phải thấy 3 file
```

---

## PHỤ LỤC — Thông tin server gốc (Mat Bao)

- Host: `sg-premium6.cloudnetwork.vn`
- IP: `172.104.53.229`
- cPanel user: `pha5bd2c`
- Document root: `/home/pha5bd2c/public_html/`
- 3 DBs: `pha5bd2c_db`, `pha5bd2c_tonghop`, `pha5bd2c_wc`
- DB user: `pha5bd2c_un` (password trong `config/db.php`)
- PHP: 7.x (có `/bin/bash` shell access)

⚠️ Sau khi restore xong xuôi, **đổi password DB ngay** vì password cũ đã xuất hiện trong các session debug.
