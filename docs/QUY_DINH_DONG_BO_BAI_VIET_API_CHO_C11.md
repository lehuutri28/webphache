# TÀI LIỆU KỸ THUẬT: QUY ĐỊNH ĐỒNG BỘ NỘI DUNG & MASTER PROMPT CHO AGENT C11 (N8N WORKFLOW)

**Người ban hành:** C12 - Senior Full-Stack Web Developer & Technical SEO Lead (`phache.com.vn`)  
**Người tiếp nhận:** C11 - Automation & n8n Workflow Specialist Lead  
**Dự án:** Hệ thống sản xuất nội dung tự động & Đăng bài trực tiếp lên chuyên mục Tin tức (`https://phache.com.vn/tin-tuc/`)  
**Ngày cập nhật:** 01/10/2026  
**Trạng thái API:** Đã kiểm thử thành công 100% trên Production (HTTP 201 Created)

---

## MỤC LỤC
1. [Tổng Quan Kiến Trúc & Hợp Đồng Dữ Liệu (Data Contract)](#1-tổng-quan-kiến-trúc--hợp-đồng-dữ-liệu-data-contract)
2. [Chi Tiết Quy Chuẩn Dữ Liệu Đầu Vào (API Payload Schema)](#2-chi-tiết-quy-chuẩn-dữ-liệu-đầu-vào-api-payload-schema)
3. [Quy Tắc Định Dạng Nội Dung Chuẩn SEO Top 1 & AI Overview (GEO)](#3-quy-tắc-định-dạng-nội-dung-chuẩn-seo-top-1--ai-overview-geo)
4. [Master Prompt Dành Cho C11 Thiết Kế n8n Workflow](#4-master-prompt-dành-cho-c11-thiết-kế-n8n-workflow)
5. [Cấu Trúc Các Node Trong Luồng n8n Khuyến Nghị Cho C11](#5-cấu-trúc-các-node-trong-luồng-n8n-khuyến-nghị-cho-c11)
6. [Quy Trình Kiểm Tra & Báo Lỗi Tự Động](#6-quy-trình-kiểm-tra--báo-lỗi-tự-động)

---

## 1. TỔNG QUAN KIẾN TRÚC & HỢP ĐỒNG DỮ LIỆU (DATA CONTRACT)

Hệ thống Backend API tại `phache.com.vn` đã mở sẵn cổng giao tiếp RESTful bảo mật cao để tiếp nhận các bài viết do n8n đẩy về:

### 1.1 Endpoint Đăng Bài Viết (Publish News API)
* **Endpoint:** `https://phache.com.vn/api/publish-news.php`
* **HTTP Method:** `POST`
* **Xác thực:** Bearer Token trong Header:
  ```http
  Authorization: Bearer {{ $env.PHACHE_API_SECRET }}
  Content-Type: application/json
  ```
* **Cơ chế An toàn:**
  - Token được lưu trữ nội bộ tại `config/api_secret.php` trên máy chủ (chống rò rỉ theo luật `gemini.md`).
  - Rate Limiting: Tối đa 10 requests / phút từ cùng 1 địa chỉ IP.
  - Tự động kiểm định và băm tên file ảnh (chống trùng lặp, chống tải mã độc).
  - Tự động đặt thứ tự hiển thị `news_order = MAX(news_order) + 1` để bài viết luôn đứng **Top 1 trang danh mục**.

### 1.2 Endpoint Khai Thác Thư Viện Ảnh Đồ Uống Thực Tế (Media Library Reference API)
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
* **Dữ liệu trả về (JSON Response):**
  ```json
  {
    "success": true,
    "total_found": 1053,
    "returned": 5,
    "data": [
      {
        "id": 1,
        "title": "Cach Lam Tra Dao Hibiscus",
        "filename": "cach-lam-tra-dao-hibiscus.jpg",
        "url": "https://phache.com.vn/upload/images/cach-lam-tra-dao-hibiscus.jpg",
        "thumb_url": "https://phache.com.vn/index.php?t=ajax&p=tthumb&src=...",
        "dimensions": "1200x800",
        "size_kb": 178
### 1.3 Endpoint Tiếp Nhận Upload Hình Ảnh Từ Vertex AI / n8n (Upload Media API - P0)
* **Endpoint:** `https://phache.com.vn/api/upload-media.php`
* **HTTP Method:** `POST`
* **Xác thực:** Cùng mã Bearer Token `Authorization: Bearer {{ $env.PHACHE_API_SECRET }}`
* **Mục đích:** Tiếp nhận hình ảnh do Vertex AI sinh ra (dạng Base64 hoặc Image URL), lưu vĩnh viễn vào máy chủ `phache.com.vn/upload/news/`, chấm dứt hoàn toàn sự phụ thuộc vào link ảnh ngoài `wecha.vn`.
* **Định dạng dữ liệu gửi lên (Hỗ trợ 3 hình thức):**
  1. **Hình thức 1 (Vertex AI Base64 - Khuyến nghị cho n8n):**
     ```json
     {
       "image_base64": "data:image/png;base64,iVBORw0KGgo...",
       "filename": "tra-sua-nuong-hoang-kim",
       "caption": "Ly trà sữa nướng hoàng kim thơm ngon béo ngậy tại Passion Link",
       "folder": "news"
     }
     ```
  2. **Hình thức 2 (Image URL công khai):**
     ```json
     {
       "image_url": "https://example.com/generated-drink.png",
       "filename": "tra-trai-cay-nhiet-doi",
       "caption": "Trà trái cây nhiệt đới giải nhiệt mùa hè",
       "folder": "news"
     }
     ```
  3. **Hình thức 3 (Multipart/form-data):** Gửi qua field `file` hoặc `image`.

* **Dữ liệu API trả về (HTTP 201 Created):**
  ```json
  {
    "success": true,
    "message": "Upload hình ảnh thành công lên phache.com.vn!",
    "data": {
      "filename": "pl_tra-sua-nuong-hoang-kim_1790896415_c9af9b.png",
      "folder": "news",
      "url": "https://phache.com.vn/upload/news/pl_tra-sua-nuong-hoang-kim_1790896415_c9af9b.png",
      "thumb_url": "https://phache.com.vn/index.php?t=ajax&p=tthumb&src=...",
      "dimensions": "1200x800",
      "width": 1200,
      "height": 800,
      "size_kb": 145.2,
      "mime_type": "image/png",
      "caption": "Ly trà sữa nướng hoàng kim thơm ngon béo ngậy tại Passion Link",
      "html_tag": "<figure class=\"pl-article-figure\" style=\"margin:24px auto;text-align:center;max-width:100%;\">\n  <img src=\"https://phache.com.vn/upload/news/pl_tra-sua-nuong-hoang-kim_1790896415_c9af9b.png\" alt=\"Ly trà sữa nướng hoàng kim thơm ngon béo ngậy tại Passion Link\" loading=\"lazy\" decoding=\"async\" style=\"border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.08);max-width:100%;height:auto;\" />\n  <figcaption style=\"font-size:14px;color:#64748b;font-style:italic;margin-top:8px;\">Ly trà sữa nướng hoàng kim thơm ngon béo ngậy tại Passion Link</figcaption>\n</figure>"
    }
  }
  ```
* **Bảo vệ Kép (Layer 2 Safeguard trong `publish-news.php`):**
  - Ngay cả khi trong nội dung bài viết gửi sang `publish-news.php` vẫn còn sót bất kỳ link ảnh nào từ `wecha.vn`, hệ thống Backend sẽ **TỰ ĐỘNG cào ảnh đó về lưu trữ nội bộ tại `phache.com.vn/upload/news/` và rewrite lại thẻ `<img src="...">` thành domain `phache.com.vn`** trước khi ghi vào CSDL!

---

## 2. CHI TIẾT QUY CHUẨN DỮ LIỆU ĐẦU VÀO (API PAYLOAD SCHEMA)

C11 cần định dạng JSON gửi từ Node HTTP Request trong n8n khớp chính xác với cấu trúc dưới đây:

### Bảng Đặc Tả Trường Dữ Liệu (Payload Fields)

| Tên Trường (Key) | Kiểu Dữ Liệu | Bắt Buộc | Mô Tả & Quy Tắc Chuẩn SEO |
| :--- | :--- | :---: | :--- |
| `title` | String | **Bắt buộc** | Tiêu đề bài viết (50 - 65 ký tự, max 200). Công thức CTR Magnet: `[Từ Khóa Chính] + [Lợi Ích/Con Số] + [Năm 2026]`. |
| `content_html` | String (HTML) | **Bắt buộc** | Toàn bộ thân bài viết định dạng HTML. Độ dài từ 1.200 đến 2.500 từ. Tuân thủ phân cấp `<h2>`, `<h3>`, bảng biểu `<table>`. **Tuyệt đối không chứa thẻ `<h1>`**. |
| `description` | String | Khuyến nghị (Rất quan trọng) | Đoạn tóm tắt bài viết hiển thị trên thẻ Card danh mục và thẻ `<meta name="description">` chuẩn Google SEO (130 - 160 ký tự).<br>• **Hỗ trợ các trường bí danh (Aliases):** `description`, `summary`, `excerpt`, `short_description`, `meta_description`, `tom_tat`.<br>• **Quy tắc vàng:** **TUYỆT ĐỐI KHÔNG gửi chuỗi placeholder** như `"Tóm tắt bài viết"`, `"Mô tả"`, `"N/A"`. Trong Prompt n8n, phải yêu cầu AI sinh tóm tắt súc tích, hấp dẫn, chứa từ khóa chính.<br>• **Bảo vệ tự động:** Nếu để trống hoặc nếu gửi chuỗi rác/placeholder, Backend API sẽ **tự động bóc tách thông minh 155 ký tự** từ đoạn văn mở đầu bài viết (đã loại bỏ sạch sẽ thẻ heading, video module, bảng cost) để hiển thị mượt mà. |
| `keywords` | String | Khuyến nghị | Danh sách 4 - 8 từ khóa LSI ngữ nghĩa, ngăn cách bằng dấu phẩy. |
| `category_id` | Integer | Tùy chọn | ID chuyên mục (Mặc định là `31`).<br>• `31`: **Tin tức chung & Xu hướng F&B** (Mục tiêu chính)<br>• `25`: **Kinh nghiệm mở quán trà sữa, cà phê**<br>• `24`: **Công thức pha chế đồ uống ngon**<br>• `22`: **Các khóa học dạy pha chế** |
| `slug` | String | Tùy chọn | Đường dẫn URL tiếng Việt không dấu (VD: `bi-quyet-nau-tra-sua-dam-vi-2026`). Nếu để trống, API tự động chuyển từ `title`. |
| `image_url` | String (URL) | Khuyến nghị | URL công khai của ảnh đại diện (Tỷ lệ 16:9, kích thước khuyến nghị 1200x630). API sẽ tự tải về, kiểm tra định dạng và lưu vào `/upload/news/`. |
| `image_alt` | String | Tùy chọn | Văn bản mô tả ảnh đại diện cho SEO (Nếu để trống, API tự gán theo `title`). |
| `faqs` | Array of Objects | Khuyến nghị | Mảng chứa các câu hỏi thường gặp: `[ { "q": "Câu hỏi?", "a": "Câu trả lời..." } ]`. API sẽ tự động sinh giao diện Accordion và chèn mã Schema `FAQPage` JSON-LD chuẩn Google. |

### Payload Mẫu Hoàn Chỉnh (JSON Example)

```json
{
  "title": "Bí Quyết Pha Trà Đào Cam Sả Đậm Vị Mở Quán [Menu 2026]",
  "slug": "bi-quyet-pha-tra-dao-cam-sa-dam-vi-mo-quan-2026",
  "description": "Bí quyết pha trà đào cam sả thanh mát đậm đà chuẩn vị Passion Link. Hướng dẫn chi tiết định lượng giá vốn dưới 6.000đ/ly, tối ưu biên lãi mở quán.",
  "keywords": "trà đào cam sả, cách làm trà đào cam sả, công thức trà đào, học pha chế trà đào, mở quán trà sữa",
  "category_id": 31,
  "image_url": "https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=1200&q=80",
  "image_alt": "Ly trà đào cam sả thanh mát thơm ngậy tại quầy bar Passion Link",
  "content_html": "<p>Trà đào cam sả là thức uống giải nhiệt quốc dân không bao giờ lỗi thời trên menu của mọi quán nước...</p><h2>1. Bảng Định Lượng & Chi Phí Cost Cho Ly 500ml</h2><table border=\"1\" style=\"width:100%; border-collapse:collapse;\"><thead><tr style=\"background:#f1f5f9;\"><th style=\"padding:8px;\">Nguyên Liệu</th><th style=\"padding:8px;\">Định Lượng</th><th style=\"padding:8px;\">Giá Vốn (Cost)</th></tr></thead><tbody><tr><td style=\"padding:8px;\">Cốt Trà Earl Grey</td><td style=\"padding:8px;\">120ml</td><td style=\"padding:8px;\">1.100 đ</td></tr></tbody></table><h2>2. Bí Quyết Ủ Trà & Nấu Nước Sả Giữ Hương</h2><p>Cốt trà đào cần được hãm ở nhiệt độ 88°C - 90°C để không bị cháy lá...</p>",
  "faqs": [
    {
      "q": "Nên dùng trà đen hay trà túi lọc để pha trà đào cam sả?",
      "a": "Dùng trà đen Ceylon hoặc Earl Grey dạng lá ủ giúp nền trà thơm sâu, không bị nhạt nhòa khi kết hợp cùng đá và nước cam tươi."
    },
    {
      "q": "Giá vốn ly trà đào cam sả nên chiếm bao nhiêu phần trăm giá bán?",
      "a": "Chuẩn F&B khuyến nghị giá vốn nguyên liệu chỉ nên chiếm từ 20% đến 25% giá bán lẻ để quán đạt biên lợi nhuận gộp trên 75%."
    }
  ]
}
```

---

## 3. QUY TẮC ĐỊNH DẠNG NỘI DUNG CHUẨN SEO TOP 1 & AI OVERVIEW (GEO)

Khi prompt cho mô hình LLM viết nội dung trong n8n, C11 cần cài đặt các quy tắc khắt khe sau:

1. **Tuyệt đối không sinh thẻ `<h1>` trong `content_html`:**
   - Hệ thống frontend của website đã tự động hiển thị thẻ `<h1>` từ trường `title`.
   - Nội dung chỉ được bắt đầu phân cấp từ các thẻ `<h2>`, sau đó tới `<h3>`.
2. **Quy tắc 100 từ đầu tiên (BLUF - Bottom Line Up Front):**
   - Đoạn mở đầu phải trả lời trực diện câu hỏi cốt lõi của người dùng / Search Intent.
   - Định nghĩa khái niệm ngắn gọn, súc tích trong 2 - 3 câu để Google AI Overviews và Perplexity dễ dàng trích dẫn nguồn (GEO - Generative Engine Optimization).
3. **Bắt buộc có tối thiểu 1 Bảng Biểu (Rich Data Table):**
   - Bảng định lượng nguyên liệu (ml, gram), bảng chi phí giá vốn (Cost), hoặc bảng so sánh các giải pháp.
   - AI bot và Search bot đặc biệt ưu tiên hiển thị Featured Snippet cho các trang có bảng số liệu rõ ràng.
4. **Tối ưu hình ảnh bên trong bài viết (In-article Images):**
   - Mọi thẻ `<img>` nếu có phải có thuộc tính `alt` chứa từ khóa ngữ nghĩa LSI.
   - API đã tích hợp sẵn tính năng tự động bổ sung `loading="lazy"` và `decoding="async"`.
5. **Khối FAQ (Hỏi Đáp) Chuẩn E-E-A-T:**
   - Đưa vào mảng `faqs` từ 2 đến 4 câu hỏi thực tế mà người mở quán F&B hay thắc mắc.
   - API sẽ tự động ghép khối Accordion HTML và xuất mã Schema `FAQPage` JSON-LD tự động.
6. **Khối Kêu Gọi Hành Động (CTA Chuyển Đổi):**
   - Nếu trong nội dung chưa có thông tin liên hệ, API sẽ tự động bổ sung khối Banner chuyển đổi Passion Link kèm liên kết Zalo Hotline `0977.300.098` và trang khóa học tổng hợp.

---

## 4. MASTER PROMPT DÀNH CHO C11 THIẾT KẾ N8N WORKFLOW

Dưới đây là nội dung Prompt hoàn chỉnh để C11 tích hợp vào Node AI (OpenAI GPT-4o, Claude 3.5 Sonnet, hoặc Google Gemini 1.5 Pro) trong n8n:

```text
Bạn là Trưởng Khoa Đào Tạo & Chuyên Gia Cố Vấn F&B tại Học Viện Đào Tạo Pha Chế Passion Link (17 năm kinh nghiệm thực chiến, đồng hành cùng hơn 10.000 chủ quán trà sữa, cà phê trên toàn quốc).

NHIỆM VỤ:
Viết một bài viết chuyên sâu đỉnh cao chuẩn On-Page SEO Top 1 Google và tối ưu cho AI Overviews (GEO) dựa trên từ khóa / chủ đề F&B được cung cấp.

BỘ QUY TẮC NỘI DUNG BẮT BUỘC:
1. TIÊU ĐỀ (title):
   - Độ dài: 50 - 65 ký tự.
   - Công thức CTR Magnet: [Từ Khóa Chính] + [Lợi Ích/Con Số Độc Nhất] + [Năm 2026].
   - Ví dụ: Bí Quyết Nấu Trà Sữa Đậm Vị Mở Quán Hút Khách [Menu 2026]

2. ĐOẠN MỞ BÀI (BLUF - Bottom Line Up Front):
   - Ngay trong 100 từ đầu tiên, trả lời trực diện câu hỏi cốt lõi của người dùng. Không viết mở bài sáo rỗng kiểu "Trong những năm gần đây...".
   - Định nghĩa rõ ràng khái niệm, số liệu quan trọng để Google AI trích dẫn làm Featured Snippet.

3. THÂN BÀI (content_html):
   - TUYỆT ĐỐI KHÔNG DÙNG THẺ <h1>. Chỉ dùng <h2> cho các đề mục lớn và <h3> cho các bước công thức/kỹ thuật.
   - BẮT BUỘC CÓ 1 BẢNG (table) định lượng nguyên liệu chuẩn ml/gram và tính toán chi phí giá vốn (Cost) chi tiết từng thành phần để chủ quán tối ưu biên lãi gộp > 70%.
   - Lồng ghép khéo léo triết lý đào tạo thực chiến của Passion Link và kinh nghiệm thực chiến từ chuyên gia.
   - Giọng văn: Chuyên nghiệp, am hiểu sâu sắc ngành F&B, nhiệt huyết, truyền cảm hứng kinh doanh an toàn.

4. CÂU HỎI THƯỜNG GẶP (faqs):
   - Tạo từ 2 đến 4 câu hỏi thường gặp (Q&A) thực tế nhất về kỹ thuật pha chế, bảo quản nguyên liệu hoặc quản trị chi phí mở quán.

5. ĐỊNH DẠNG ĐẦU RA BẮT BUỘC:
   - Bạn PHẢI trả về duy nhất một chuỗi JSON hợp lệ (Valid JSON), KHÔNG bọc trong markdown code block (không dùng ```json ... ```), theo đúng cấu trúc schema sau:
{
  "title": "Tiêu đề chuẩn SEO",
  "slug": "duong-dan-khong-dau-chuan-seo",
  "description": "Meta description từ 140 - 155 ký tự có từ khóa và lời kêu gọi hành động.",
  "keywords": "từ khóa chính, từ khóa phụ 1, từ khóa phụ 2, passion link",
  "category_id": 31,
  "image_query": "từ khóa tiếng anh để tìm ảnh đẹp trên Unsplash (VD: iced milk tea tapioca pearl high resolution)",
  "image_alt": "Văn bản mô tả ảnh chứa từ khóa chính",
  "content_html": "<p>Nội dung HTML đầy đủ...</p><h2>...</h2>",
  "faqs": [
    {
      "q": "Câu hỏi 1?",
      "a": "Câu trả lời chi tiết..."
    },
    {
      "q": "Câu hỏi 2?",
      "a": "Câu trả lời chi tiết..."
    }
  ]
}
```

---

## 5. CẤU TRÚC CÁC NODE TRONG LUỒNG N8N KHUYẾN NGHỊ CHO C11

Quy trình n8n được thiết kế gồm 7 bước tự động hóa khép kín:

```
[Node 1: Trigger] 
  │ (Schedule định kỳ 08:00 Thứ 3 & Thứ 6 hàng tuần, hoặc Webhook / Google Sheets)
  ▼
[Node 2: Lấy Topic & Keyword]
  │ (Đọc hàng mới từ Google Sheets Content Calendar hoặc mảng từ khóa KGR < 0.25)
  ▼
[Node 3: AI Master Content Generator]
  │ (Gọi OpenAI GPT-4o / Claude 3.5 / Gemini 1.5 Pro với Master Prompt ở Mục 4)
  ▼
[Node 4: AI Image Generator / Unsplash Fetcher]
  │ (Lấy `image_query` từ Node 3 gọi Unsplash API / DALL-E 3 để nhận direct image URL)
  ▼
[Node 5: Data Assembly & Validation]
  │ (Code Node trong n8n ghép toàn bộ dữ liệu thành gói Payload JSON hoàn chỉnh)
  ▼
[Node 6: HTTP Request Node (Bắn về phache.com.vn)]
  │ POST https://phache.com.vn/api/publish-news.php
  │ Header: Authorization: Bearer {{ $env.PHACHE_API_SECRET }}
  │ Body: Toàn bộ JSON từ Node 5
  ▼
[Node 7: Telegram / Zalo Bot Notification]
  │ (Gửi thông báo thành công: Link bài viết live trên website + Ảnh Thumbnail)
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
