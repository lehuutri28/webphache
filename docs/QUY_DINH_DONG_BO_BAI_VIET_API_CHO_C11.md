# TÀI LIỆU KỸ THUẬT: QUY ĐỊNH ĐỒNG BỘ NỘI DUNG & MASTER PROMPT CHO AGENT C11 (N8N WORKFLOW)

**Người ban hành:** C12 - Senior Full-Stack Web Developer & Technical SEO Lead (`phache.com.vn`)  
**Người tiếp nhận:** C11 - Automation & n8n Workflow Specialist Lead  
**Dự án:** Hệ thống sản xuất nội dung tự động & Đăng bài trực tiếp lên chuyên mục Tin tức (`https://phache.com.vn/tin-tuc/`)  
**Ngày cập nhật:** 01/10/2026  
**Trạng thái API:** Đã kiểm thử thành công 100% trên Production (HTTP 201 Created)

---

## MỤC LỤC
1. [Tổng Quan Kiến Trúc 2 API & Phân Định Search Intent](#1-tổng-quan-kiến-trúc-2-api--phân-định-search-intent)
2. [Chi Tiết Quy Chuẩn Dữ Liệu Đầu Vào (API Payload Schema)](#2-chi-tiết-quy-chuẩn-dữ-liệu-đầu-vào-api-payload-schema)
3. [Các Quy Tắc Nội Dung Và Khắc Phục Lỗi Triệt Để (Theo Bản Đánh Giá Sửa Bài AI)](#3-các-quy-tắc-nội-dung-và-khắc-phục-lỗi-triệt-để-theo-bản-đánh-giá-sửa-bài-ai)
4. [Master Prompt Nâng Cấp Dành Cho C11 Thiết Kế n8n Workflow](#4-master-prompt-nâng-cấp-dành-cho-c11-thiết-kế-n8n-workflow)
5. [Cấu Trúc Các Node Trong Luồng n8n Khuyến Nghị Cho C11](#5-cấu-trúc-các-node-trong-luồng-n8n-khuyến-nghị-cho-c11)
6. [Quy Trình Kiểm Tra & Báo Lỗi Tự Động](#6-quy-trình-kiểm-tra--báo-lỗi-tự-động)

---

## 1. TỔNG QUAN KIẾN TRÚC 2 API & PHÂN ĐỊNH SEARCH INTENT

Hệ thống Backend tại `phache.com.vn` vận hành **2 cổng API riêng biệt** tương ứng với 2 chuyên mục trọng điểm, phục vụ 2 nhóm Search Intent hoàn toàn khác nhau của người dùng:

```
                  ┌─────────────────────────────────────────┐
                  │    n8n Workflow WF-018 (Agent C11)      │
                  │   Phân loại Search Intent theo đề tài   │
                  └────────────────────┬────────────────────┘
                                       │
            ┌──────────────────────────┴──────────────────────────┐
            ▼                                                     ▼
┌───────────────────────────────────────┐   ┌───────────────────────────────────────┐
│     NHÓM 1: MỞ QUÁN & KINH DOAN       │   │      NHÓM 2: TIN TỨC & XU HƯỚNG       │
│  • Search Intent: Commercial / Trans  │   │  • Search Intent: Informational / Nav │
│  • Đối tượng: Chủ quán, Khởi nghiệp   │   │  • Đối tượng: Giới trẻ, Barista, F&B  │
│  • Bảng Cost, Menu ma trận, Mặt bằng  │   │  • Trend đồ uống, Review, Sự kiện PL  │
└───────────────────┬───────────────────┘   └───────────────────┬───────────────────┘
                    │                                           │
                    ▼                                           ▼
┌───────────────────────────────────────┐   ┌───────────────────────────────────────┐
│   POST /api/publish-mo-quan.php       │   │    POST /api/publish-news.php         │
│   (Default category_id = 25)          │   │    (Default category_id = 31)         │
│   URL: /mo-quan/[slug]-[id].html      │   │    URL: /tin-tuc/[slug]-[id].html     │
└───────────────────────────────────────┘   └───────────────────────────────────────┘
```

---

### 1.1 Chi Tiết 2 Endpoint Đăng Bài Viết

#### A. Endpoint 1: Chuyên Mục Mở Quán (Commercial & Business Intent)
* **Endpoint:** `https://phache.com.vn/api/publish-mo-quan.php`
* **HTTP Method:** `POST`
* **Chuyên mục mặc định:** `category_id = 25` (Kinh nghiệm mở quán)
* **Định dạng URL bài viết:** `https://phache.com.vn/mo-quan/[slug]-[id].html`
* **Xác thực:** Bearer Token trong Header:
  ```http
  Authorization: Bearer {{ $env.PHACHE_API_SECRET }}
  Content-Type: application/json
  ```
* **Mục tiêu chuyển đổi:** Khóa học Pha Chế Mở Quán Cafe Chuyên Nghiệp (ID 571), Khóa Trà Sữa Mở Quán (ID 72), Dịch vụ Setup Quán Trọn Gói Passion Link, Nguồn nguyên liệu sỉ **Vua An Toàn**.
* **Hotline bán hàng & tư vấn bắt buộc:** **`090 892 44 60`** (Hotline Zalo/Điện thoại phòng kinh doanh).

#### B. Endpoint 2: Chuyên Mục Tin Tức & Xu Hướng (Informational & Trend Intent)
* **Endpoint:** `https://phache.com.vn/api/publish-news.php`
* **HTTP Method:** `POST`
* **Chuyên mục mặc định:** `category_id = 31` (Tin tức F&B & Sự kiện)
* **Định dạng URL bài viết:** `https://phache.com.vn/tin-tuc/[slug]-[id].html`
* **Xác thực:** Bearer Token trong Header:
  ```http
  Authorization: Bearer {{ $env.PHACHE_API_SECRET }}
  Content-Type: application/json
  ```
* **Mục tiêu chuyển đổi:** Khóa học tổng hợp (ID 22), Lịch khai giảng học viện Passion Link, Workshop trải nghiệm quầy bar, Đăng ký học thử, Tuyển sinh.
* **Hotline tư vấn đào tạo:** **`0977.300.098`** hoặc **`090 892 44 60`**.

---

### 1.2 Bảng Ma Trận Phân Định Search Intent & Quy Hoạch Nội Dung (Bắt Buộc C11 Tuân Thủ 100%)

| Tiêu Chí | Trang Mở Quán (`/mo-quan/`) | Trang Tin Tức (`/tin-tuc/`) |
| :--- | :--- | :--- |
| **API Endpoint** | `POST https://phache.com.vn/api/publish-mo-quan.php` | `POST https://phache.com.vn/api/publish-news.php` |
| **Category ID** | `25` | `31` |
| **Search Intent** | **Commercial Investigation / Transactional** (Khảo sát thương mại & Ra quyết định đầu tư) | **Informational / Navigational** (Tìm hiểu thông tin, giải trí, xu hướng thị trường) |
| **Đối Tượng Độc Giả** | Chủ quán cafe, trà sữa sắp mở; Nhà đầu tư F&B; Quản lý quầy bar cần tối ưu chi phí & doanh thu. | Khách hàng trẻ, Barista mới vào nghề, Người yêu thích đồ uống, Học viên tìm hiểu lịch học. |
| **Các Chủ Đề Đặc Trưng** | • Tính toán chi phí đầu tư mở quán (vốn 50tr, 100tr, 200tr)<br>• Bảng tính Cost giá vốn & Định giá bán lẻ tối ưu lãi > 70%<br>• Thiết kế ma trận Menu 3 tầng (Món dẫn, Món chủ lực, Món lợi nhuận)<br>• Nguyên tắc bố trí quầy bar công thái học tăng tốc độ ra món<br>• Thủ tục pháp lý mở quán, giấy phép VSATTP, đăng ký kinh doanh<br>• Tiêu chí chọn máy pha cà phê, máy dập nắp, máy xay công nghiệp<br>• Nguồn hàng nguyên liệu tận gốc giá xưởng (**Vua An Toàn**). | • Bắt trend đồ uống mới lạ (Trà chanh giã tay, Cafe muối, Matcha dừa...)<br>• Review thị trường F&B Việt Nam & Quốc tế qua từng quý/năm<br>• Kỹ năng pha chế nâng cao (Latte Art, Cupping, Sensory)<br>• Tin tức sự kiện khai giảng, workshop, lễ tốt nghiệp Passion Link<br>• Câu chuyện thành công của cựu học viên Passion Link<br>• Bí quyết bảo quản nông sản, thảo mộc, hương vị tự nhiên. |
| **Tone of Voice** | Thực chiến, sắc bén, định lượng số liệu rõ ràng, tư duy tài chính kinh doanh an toàn, cố vấn trực tiếp từ Thầy Lê Hữu Trí. | Năng động, tươi mới, truyền cảm hứng đam mê pha chế, gợi mở trải nghiệm vị giác. |
| **Hotline Hiển Thị** | **`090 892 44 60`** (Hotline Tư Vấn Setup & Nguyên Liệu Sỉ) | **`0977.300.098`** (Hotline Tuyển Sinh Học Viện Passion Link) |
| **Khóa Học Trọng Tâm** | [Khóa Mở Quán Cafe Chuyên Nghiệp (571)](https://phache.com.vn/khoa-hoc-pha-che-mo-quan-cafe-chuyen-nghiep.html), [Khóa Trà Sữa Chuẩn Vị (72)](https://phache.com.vn/day-pha-che-tra-sua-ngon.html) | [Khóa Pha Chế Tổng Hợp (22)](https://phache.com.vn/day-pha-che-tong-hop.html), [Khóa Barista Cấp Tốc (137)](https://phache.com.vn/khoa-hoc-barista.html) |

---

### 1.3 Endpoint Khai Thác Thư Viện Ảnh Đồ Uống Thực Tế (Media Library Reference API)
* **Endpoint:** `https://phache.com.vn/api/media-library.php`
* **HTTP Method:** `GET` hoặc `POST`
* **Xác thực:** Cùng mã Bearer Token `Authorization: Bearer {{ $env.PHACHE_API_SECRET }}`
* **Mục đích:** Cung cấp cho C11 kho ảnh thực tế gồm hơn **1.680+ bức ảnh món đồ uống chụp đẹp của Passion Link** (từ `/upload/images/`, `/upload/news/`, `/upload/gallery/`) để:
  1. Làm **Ảnh tham chiếu (Reference Image)** cho các mô hình AI sinh ảnh (Midjourney Image-to-Image, Flux, DALL-E, Gemini Multimodal).
  2. Hoặc lấy trực tiếp URL ảnh thật có sẵn làm ảnh đại diện bài viết.
* **Tham số truy vấn (Query Params / JSON Body):**
  - `keyword`: Từ khóa tìm kiếm món đồ uống (VD: `tra-sua`, `tra-dao`, `ca-phe`, `matcha`, `kem`, `olong`, `boba`...).
  - `limit`: Số lượng ảnh muốn lấy (mặc định 12, tối đa 50).
  - `random`: `true` hoặc `false` (nếu `true`, xáo trộn ngẫu nhiên để lấy các ảnh khác nhau mỗi lần chạy).
* **Ví dụ gọi từ n8n (HTTP Request Node):**
  ```http
  GET https://phache.com.vn/api/media-library.php?keyword=tra-dao&limit=5&random=true
  Authorization: Bearer {{ $env.PHACHE_API_SECRET }}
  ```

---

### 1.4 Endpoint Tiếp Nhận Upload Hình Ảnh Chuẩn Hóa (Upload Media API)
* **Endpoint:** `https://phache.com.vn/api/upload-media.php`
* **HTTP Method:** `POST`
* **Xác thực:** Cùng mã Bearer Token `Authorization: Bearer {{ $env.PHACHE_API_SECRET }}`
* **Quy chuẩn kích thước & dung lượng:**
  - **Tỷ lệ khuyến nghị:** `16:9` (1200x675px) cho ảnh đại diện, hoặc `800x500px` cho ảnh thân bài.
  - **Dung lượng:** Dưới **150 KB** (API tự động nén tối ưu hiển thị nhanh chuẩn Google Core Web Vitals).
* **Quy chuẩn Chú thích ảnh (Figcaption & Alt):**
  - **TUYỆT ĐỐI KHÔNG để lộ câu lệnh Prompt AI** vào thẻ `alt` hoặc `<figcaption>` (Ví dụ lỗi: *"A high resolution photo of iced milk tea, realistic, 8k..."* ❌).
  - Phải dùng chú thích tiếng Việt tự nhiên, chân thực (Ví dụ chuẩn: *"Ảnh: Giảng viên Passion Link hướng dẫn kỹ thuật đánh bọt sữa mịn cho học viên tại quầy bar"* ✅).

---

## 2. CHI TIẾT QUY CHUẨN DỮ LIỆU ĐẦU VÀO (API PAYLOAD SCHEMA)

C11 cần định dạng JSON gửi từ Node HTTP Request trong n8n khớp chính xác với cấu trúc dưới đây:

### Bảng Đặc Tả Trường Dữ Liệu (Payload Fields)

| Tên Trường (Key) | Kiểu Dữ Liệu | Bắt Buộc | Mô Tả & Quy Tắc Khắc Phục Lỗi |
| :--- | :--- | :---: | :--- |
| `title` | String | **Bắt buộc** | Tiêu đề bài viết (50 - 65 ký tự, max 200). Công thức CTR Magnet: `[Từ Khóa Chính] + [Lợi Ích/Con Số] + [Năm 2026]`. |
| `content_html` | String (HTML) | **Bắt buộc** | Toàn bộ thân bài viết định dạng HTML (1.500 - 2.500 từ). Tuân thủ phân cấp `<h2>`, `<h3>`, bảng biểu `<table>`. **Tuyệt đối không chứa thẻ `<h1>`**. |
| `description` | String | Khuyến nghị (Rất quan trọng) | Đoạn tóm tắt bài viết hiển thị trên Card chuyên mục và `<meta name="description">` chuẩn Google SEO (130 - 160 ký tự).<br>• **Quy tắc vàng:** **TUYỆT ĐỐI KHÔNG gửi chuỗi placeholder** như `"Tóm tắt bài viết"`, `"Mô tả"`, `"N/A"`. Phải tóm tắt ngắn gọn 2 câu cô đọng giá trị cốt lõi.<br>• **Bảo vệ tự động:** Nếu trống hoặc dính chuỗi rác, Backend API tự động bóc tách thông minh 155 ký tự từ đoạn mở bài. |
| `keywords` | String | Khuyến nghị | Danh sách 4 - 8 từ khóa LSI ngữ nghĩa, ngăn cách bằng dấu phẩy. |
| `category_id` | Integer | Tùy chọn | ID chuyên mục (Mặc định `25` cho `publish-mo-quan.php`, hoặc `31` cho `publish-news.php`). |
| `slug` | String | Tùy chọn | Đường dẫn URL tiếng Việt không dấu (VD: `cong-thuc-tra-sua-dam-vi-mo-quan-hut-khach-2026`). |
| `image_url` | String (URL) | Khuyến nghị | URL công khai của ảnh đại diện (Tỷ lệ 16:9, kích thước 1200x675 hoặc 800x500). API sẽ tự tải về lưu vào `/upload/news/`. |
| `image_alt` | String | Tùy chọn | Văn bản mô tả ảnh cho SEO (tự nhiên, không chứa text prompt tiếng Anh). |
| `faqs` | Array of Objects | Khuyến nghị | Mảng Q&A: `[ { "q": "Câu hỏi?", "a": "Câu trả lời..." } ]`. API tự sinh Accordion và chèn Schema `FAQPage` JSON-LD. |

---

## 3. CÁC QUY TẮC NỘI DUNG VÀ KHẮC PHỤC LỖI TRIỆT ĐỂ (THEO BẢN ĐÁNH GIÁ SỬA BÀI AI)

Khi n8n sinh nội dung, C11 phải thiết lập các rào chắn kỹ thuật (Guards) để loại bỏ 100% các sai sót nhân viên đã phản ánh:

### 3.1 Quy Tắc Nguyên Liệu Nhà Máy Độc Quyền (VUA AN TOÀN, WECHA, SAFE KING, ROYAL'S)
* **Bắt buộc 100%:** Trong mọi công thức pha chế, định lượng và bảng tính chi phí giá vốn (Cost table), bắt buộc sử dụng tên thương hiệu độc quyền của nhà máy công ty:
  - **TRÀ:** Bắt buộc dùng **Trà Vua An Toàn** hoặc **Wecha** (VD: *Trà Ô Long Nướng Vua An Toàn*, *Trà Đen Ceylon Vua An Toàn*, *Trà Lài Hoàng Gia Vua An Toàn*, *Hồng Trà Wecha*...).
  - **BỘT:** Bắt buộc dùng **Bột Béo Cao Cấp Vua An Toàn** hoặc **Royal's** (VD: *Bột Sữa Béo Vua An Toàn*, *Bột Béo Royal's*, *Bột Matcha Thượng Hạng Vua An Toàn*, *Bột Frappe Vua An Toàn*...).
  - **SIRO & SỐT:** Bắt buộc dùng **Safe King** hoặc **Vua An Toàn** (VD: *Sốt Kem Muối Dừa Vua An Toàn*, *Syrup Đường Đen Vua An Toàn*, *Siro Đào Safe King*, *Siro Dâu Safe King*...).
  - **CÀ PHÊ:** Bắt buộc dùng **Cà Phê Mộc Robusta Thượng Hạng Vua An Toàn** hoặc **Passion Link**.
* **CẤM TUYỆT ĐỐI:** Không nhắc đến bất kỳ thương hiệu đối thủ nào bên ngoài thị trường (như Lipton, Monin, Torani, B'one, Frima, Kievit, Rich's, v.v.).

### 3.2 Quy Tắc Hotline Bán Hàng & Tư Vấn
* **Hotline chuẩn:** Bắt buộc sử dụng hotline chính thức:
  - Cho bài viết Mở Quán / Mua Nguyên Liệu: **`090 892 44 60`**
  - Cho bài viết Đào Tạo Học Viện: **`0977.300.098`**
* **CẤM TUYỆT ĐỐI:** Không để AI tự bịa ra các số điện thoại rác như `090 123 4567`, `090 999 9999` hoặc số ngẫu nhiên!

### 3.3 Quy Tắc Video Module (Tuyệt Đối Không Dùng File MP4 Cục Bộ 404)
* Khi bài viết chèn khối video thao tác quầy bar thực chiến của Thầy Lê Hữu Trí, **bắt buộc dùng Responsive YouTube Iframe Embed** từ kênh YouTube chính thức của Thầy Trí / Passion Link (ví dụ mã video: `07pucUJVXP4`):
  ```html
  <div style="position:relative; width:100%; aspect-ratio:16/9; border-radius:12px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.3);">
    <iframe src="https://www.youtube.com/embed/07pucUJVXP4" title="Thầy Lê Hữu Trí Hướng Dẫn Kỹ Thuật Barista" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%; height:100%; border:none;"></iframe>
  </div>
  ```
* **CẤM TUYỆT ĐỐI:** Không dùng thẻ `<video><source src="...mp4"></video>` trỏ vào file nội bộ chưa upload lên hosting khiến video bị lỗi màn hình đen / 404.

### 3.4 Quy Tắc Chú Thích Ảnh & Thẻ Alt (Clean Figcaption)
* Toàn bộ thẻ `<figure>` và `<figcaption>` phải có văn bản thuần Việt mô tả đúng nội dung bức ảnh, gợi cảm giác thực tế tại quầy bar Passion Link.
* **CẤM TUYỆT ĐỐI:** Không bao giờ để sót bất kỳ từ tiếng Anh của Prompt sinh ảnh AI (như *"photorealistic, 8k resolution, canon eos, soft studio lighting..."*) vào thẻ `alt` hay `<figcaption>`.

### 3.5 Quy Tắc Kiểm Soát Trùng Lặp Bài Viết (Anti-Duplication)
* Trước khi bắn API xuất bản, n8n phải kiểm tra xem đề tài hoặc slug này đã tồn tại chưa để tránh nhân đôi bài viết (như sự cố bài 679 nhân bản 678 và bài 680 nhân bản 677 trước đây).

---

## 4. MASTER PROMPT NÂNG CẤP DÀNH CHO C11 THIẾT KẾ N8N WORKFLOW

Dưới đây là bản Master Prompt đã được nâng cấp toàn diện, bổ sung đầy đủ các rào chắn kỹ thuật để nạp trực tiếp vào Node LLM trong n8n:

```text
Bạn là Trưởng Khoa Đào Tạo & Chuyên Gia Cố Vấn F&B Cấp Cao tại Học Viện Pha Chế Passion Link (17 năm kinh nghiệm thực chiến, đồng hành cùng hơn 10.000 chủ quán cafe, trà sữa thành công toàn quốc) và Chuyên Gia Cung Ứng Nguyên Liệu tại Nhà Máy VUA AN TOÀN.

NHIỆM VỤ:
Viết bài viết chuyên sâu đỉnh cao chuẩn On-Page SEO Top 1 Google và tối ưu cho Google AI Overviews (GEO) dựa trên từ khóa và Search Intent được cung cấp.

BỘ NGUYÊN TẮC BẮT BUỘC 100%:

1. XÁC ĐỊNH SEARCH INTENT & ĐÍCH ĐẾN:
   - Nếu từ khóa thuộc nhóm MỞ QUÁN / KINH DOANH (kinh nghiệm mở quán, vốn đầu tư, tính cost, menu ma trận, setup bar, pháp lý):
     + Mục tiêu: Kích thích đăng ký Khóa Học Mở Quán Cafe (ID 571) hoặc Khóa Học Mở Quán Trà Sữa (ID 72) và đặt mua nguyên liệu sỉ Vua An Toàn.
     + Hotline liên hệ bắt buộc: 090 892 44 60.
     + Category ID: 25.
   - Nếu từ khóa thuộc nhóm TIN TỨC / XU HƯỚNG / KỸ THUẬT PHA CHẾ:
     + Mục tiêu: Cung cấp kiến thức trend, kỹ thuật chiết xuất, hướng nghiệp, học thử quầy bar.
     + Hotline liên hệ: 0977.300.098 hoặc 090 892 44 60.
     + Category ID: 31.

2. NGUYÊN LIỆU ĐỘC QUYỀN (VUA AN TOÀN, WECHA, SAFE KING, ROYAL'S):
   - Mọi công thức pha chế và bảng tính chi phí giá vốn (Cost table) BẮT BUỘC phải dùng tên sản phẩm nhà máy:
     * Trà: Trà Ô Long Nướng Vua An Toàn, Trà Đen Ceylon Vua An Toàn, Trà Lài Hoàng Gia Vua An Toàn, Hồng Trà Wecha...
     * Bột: Bột Béo Cao Cấp Vua An Toàn, Bột Sữa Royal's, Bột Matcha Thượng Hạng Vua An Toàn...
     * Sốt/Siro: Sốt Kem Muối Dừa Vua An Toàn, Syrup Đường Đen Vua An Toàn, Siro Đào Safe King...
     * Cà phê: Cà Phê Mộc Robusta Thượng Hạng Vua An Toàn, Cà Phê Hạt Cân Bằng Passion Link...
   - CẤM TUYỆT ĐỐI nhắc tên các thương hiệu ngoài thị trường (Lipton, Monin, Torani, B'one, Frima, Kievit...).

3. TIÊU ĐỀ & TÓM TẮT BÀI VIẾT (DESCRIPTION):
   - Tiêu đề: 50 - 65 ký tự, chứa Từ Khóa Chính + Lợi Ích Cụ Thể + Năm 2026.
   - Tóm tắt (description): 130 - 155 ký tự súc tích, trực diện, hấp dẫn, chứa từ khóa chính. TUYỆT ĐỐI KHÔNG xuất chuỗi placeholder kiểu "Tóm tắt bài viết" hay "Mô tả bài viết".

4. ĐOẠN MỞ BÀI (BLUF - Bottom Line Up Front):
   - Trả lời trực diện câu hỏi cốt lõi của người dùng ngay trong 100 từ đầu tiên để Google AI Overviews dễ trích xuất Featured Snippet.

5. THÂN BÀI (content_html):
   - TUYỆT ĐỐI KHÔNG DÙNG THẺ <h1>. Chỉ dùng <h2> cho đề mục lớn và <h3> cho công thức, bước thực hiện.
   - BẮT BUỘC CÓ ÍT NHẤT 1 BẢNG (table) định lượng nguyên liệu chi tiết (ml, gram) và tính toán chi phí giá vốn (Cost từng thành phần) đảm bảo lợi nhuận gộp > 70%.
   - Nếu chèn video, BẮT BUỘC dùng mã nhúng iframe YouTube Responsive của Thầy Lê Hữu Trí (https://www.youtube.com/embed/07pucUJVXP4). CẤM dùng thẻ video mp4 nội bộ gây lỗi 404.
   - Chú thích ảnh (figcaption) và thuộc tính alt: Bắt buộc dùng văn bản tiếng Việt tự nhiên, mô tả hoạt động thực tế tại quầy bar Passion Link. CẤM TUYỆT ĐỐI để lộ từ khóa tiếng Anh của Prompt sinh ảnh AI.

6. CÂU HỎI THƯỜNG GẶP (faqs):
   - Tạo từ 2 đến 4 câu hỏi thực tế sát sườn với thắc mắc của người làm đồ uống, giải đáp chuyên sâu chuẩn E-E-A-T.

7. ĐỊNH DẠNG ĐẦU RA BẮT BUỘC:
   - Trả về duy nhất 1 chuỗi JSON hợp lệ (Valid JSON), KHÔNG bọc trong markdown code block (không dùng ```json), theo cấu trúc:
{
  "title": "Tiêu đề chuẩn SEO",
  "slug": "duong-dan-khong-dau-chuan-seo",
  "description": "Đoạn tóm tắt 130-155 ký tự hấp dẫn, chứa từ khóa chính, không có placeholder.",
  "keywords": "từ khóa chính, từ khóa phụ 1, từ khóa phụ 2, vua an toan, passion link",
  "category_id": 25,
  "image_alt": "Mô tả ảnh tự nhiên bằng tiếng Việt chuẩn ngữ nghĩa",
  "image_caption": "Chú thích ảnh tiếng Việt chân thực tại xưởng Passion Link",
  "content_html": "<p>Đoạn mở bài BLUF...</p><h2>...</h2>",
  "faqs": [
    {
      "q": "Câu hỏi thực tế 1?",
      "a": "Câu trả lời chuyên sâu..."
    },
    {
      "q": "Câu hỏi thực tế 2?",
      "a": "Câu trả lời chuyên sâu..."
    }
  ]
}
```

---

## 5. CẤU TRÚC CÁC NODE TRONG LUỒNG N8N KHUYẾN NGHỊ CHO C11

Quy trình n8n được thiết kế gồm 8 bước tự động hóa khép kín có điều hướng thông minh (Smart Router):

```
[Node 1: Trigger] 
  │ (Schedule định kỳ 08:30 hàng ngày, hoặc Webhook / Google Sheets)
  ▼
[Node 2: Lấy Topic & Intent]
  │ (Đọc hàng mới từ Google Sheets Content Calendar hoặc ma trận từ khóa)
  ▼
[Node 3: AI Master Content Generator]
  │ (Gọi LLM với Master Prompt ở Mục 4: sinh title, description, content, cost table, faqs)
  ▼
[Node 4: Media Fetcher & Upload]
  │ (Lấy ảnh từ Media Library API hoặc sinh ảnh rồi nạp vào POST /api/upload-media.php)
  ▼
[Node 5: Data Assembly & Validation]
  │ (Code Node ghép payload JSON, xác thực không dính placeholder, hotline đúng 090 892 44 60)
  ▼
[Node 6: Switch Node (Điều Hướng Intent & Endpoint)]
  ├── Nếu Category = 25 (Mở Quán) ──► [Node 7A: POST /api/publish-mo-quan.php]
  └── Nếu Category = 31 (Tin Tức)  ──► [Node 7B: POST /api/publish-news.php]
  ▼
[Node 8: Telegram / Zalo Bot Notification]
  │ (Gửi thông báo thành công: Canonical URL live trên website + Ảnh Thumbnail)
```

---

## 6. QUY TRÌNH KIỂM TRA & BÁO LỖI TỰ ĐỘNG

API trả về các mã HTTP Status chuẩn quốc tế để Node HTTP Request trong n8n xử lý logic:

| HTTP Code | Ý Nghĩa | Hướng Xử Lý Của n8n |
| :---: | :--- | :--- |
| **201** | `Created` (Xuất bản thành công) | Đọc `data.canonical_url` và gửi thông báo báo cáo Telegram/Zalo. |
| **400** | `Bad Request` (JSON hỏng hoặc rỗng) | Kiểm tra lại cú pháp JSON đầu ra từ Node AI. |
| **401** | `Unauthorized` (Sai hoặc thiếu Token) | Kiểm tra lại biến môi trường `PHACHE_API_SECRET` trong n8n. |
| **405** | `Method Not Allowed` | Đảm bảo phương thức của HTTP Request Node là `POST`. |
| **422** | `Unprocessable Entity` (Thiếu title hoặc content quá ngắn) | Cho phép n8n tự động Retry lại Node AI để sinh lại nội dung đầy đủ hơn. |
| **429** | `Too Many Requests` (Quá 10 bài/phút) | Thêm Wait Node 60 giây trong n8n trước khi gửi bài kế tiếp. |
| **500** | `Internal Server Error` | Gửi cảnh báo khẩn cấp về Telegram để kỹ thuật kiểm tra kết nối CSDL máy chủ. |

---

*Tài liệu này được biên soạn bởi C12 và lưu trữ chính thức tại `CODE-WEBSITE/CODE-PHACHE.COM.VN/docs/QUY_DINH_DONG_BO_BAI_VIET_API_CHO_C11.md`. Bàn giao trực tiếp cho C11 triển khai luồng n8n hoàn chỉnh.*
