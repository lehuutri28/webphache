# NHẬT KÝ & KẾ HOẠCH BÀI VIẾT TỰ ĐỘNG HÓA N8N (WF-018) - WEBSITE PHACHE.COM.VN
> **Tài liệu chiến lược nội dung SEO Top 1 & Phối hợp kỹ thuật C11 - C12**  
> **Người phê duyệt:** Sếp Letri (Giám đốc Điều hành - CEO)  
> **Đơn vị thực thi:**  
> - **C11:** Enterprise Automation Specialist (Chịu trách nhiệm n8n Workflow WF-018 sinh bài, tạo ảnh & bắn API)  
> - **C12:** Senior Full-Stack Web Developer & SEO Technical Lead (Chịu trách nhiệm API Ingestion, On-Page Schema, Quicksand Typography & Live Verification)  
> **Khởi tạo:** 02/10/2026 | **Chu kỳ thực thi:** 03/10/2026 – 12/10/2026 (10 Ngày liên tục)

---

## 📌 I. QUY ĐỊNH KỸ THUẬT & CHU TRÌNH XUẤT BẢN (SOP 24H)

### 1. Quy Định Thời Gian Viết & Đăng Bài
Để bài viết vừa được Google lập chỉ mục (index) nhanh, vừa đón đúng đỉnh sóng tìm kiếm (Peak Search Traffic) của dân văn phòng và chủ quán F&B:
* **Thời gian Viết & Chuẩn bị Media (Writing & Asset Generation):** Thực hiện vào buổi chiều hôm trước (từ 15:00 đến 17:30) hoặc hoàn thành trước 07:30 sáng ngày đăng.
* **Thời gian Xuất Bản Chính Thức (Live Publishing Golden Hour):** Đúng **08:30 sáng** mỗi ngày (Khung giờ vàng tìm kiếm ý tưởng đồ uống mở quán).

### 2. Tiêu Chuẩn Kỹ Thuật Nội Dung Bắt Buộc (SEO Top 1 & Conversion)
1. **Phông Chữ Mặc Định:** Toàn bộ bài viết tự động kế thừa bộ style **Quicksand Bold (700)** cho đoạn văn/danh sách và **Quicksand Extra Bold (800)** cho Tiêu đề H1-H6, `strong`, `b` đã được C12 tối ưu tại `template/news.php`.
2. **Quy Chuẩn Hình Ảnh (Zero Wecha Dependency):** 
   - Tuyệt đối **không** dùng link ảnh ngoài (`wecha.vn/images/...`).
   - C11 sinh ảnh từ Vertex AI (Base64) ➡️ Gửi qua endpoint `POST https://phache.com.vn/api/upload-media.php` để lưu trữ vĩnh viễn tại thư mục máy chủ `/upload/news/` trên domain `phache.com.vn`.
   - Mỗi bài viết có tối thiểu: **1 ảnh đại diện** (Featured Image tỷ lệ 16:9, tối thiểu 1200x675px) + **1 đến 2 ảnh minh họa thân bài** (tỷ lệ 16:9 hoặc 4:3).
3. **Cấu Trúc Heading & Dữ Liệu Bảng (Table):**
   - Duy nhất **1 thẻ H1** (tiêu đề bài viết).
   - Thân bài phân cấp rõ ràng `H2` và `H3`.
   - Bắt buộc có **ít nhất 1 bảng Table HTML** phân tích chi tiết: Định lượng nguyên liệu (gram/ml), Giá vốn (Cost ly nước), Giá bán đề xuất và Tỷ lệ lợi nhuận gộp (> 70%).
4. **Cấu Trúc FAQPage Schema:** Cung cấp mảng `faq_items` gồm tối thiểu 3 câu hỏi thực chiến để API tự động sinh mã cấu trúc Schema `FAQPage` chuẩn Google Rich Snippets.
5. **Mạng Lưới Internal Link Silo:** Mỗi bài viết bắt buộc trỏ ngữ cảnh (Contextual Link) về khóa học đích và công cụ tính chi phí tương ứng trên `phache.com.vn`.
6. **Chiến Lược Bán Nguyên Liệu Nhà Máy Độc Quyền (WECHA, SAFE KING, ROYAL'S - Bắt buộc 100%):**
   - **Nguồn chân lý dữ liệu:** Bảng tính Google Sheet sản phẩm nhà máy: [Google Sheet Danh Mục Sản Phẩm Nhà Máy](https://docs.google.com/spreadsheets/d/17gNnZnicD7_u5aCt3QGE9s_Qw6QB6FEO9XO_DInXMzU/edit?gid=0#gid=0) (Tab `san_pham`).
   - Mọi công thức pha chế, định lượng và bảng tính chi phí giá vốn (Cost table) trong bài viết bắt buộc chỉ sử dụng nguyên liệu do nhà máy của công ty sản xuất:
     * **TRÀ:** Bắt buộc dùng thương hiệu **WECHA** (Trà Đen Assam Wecha, Trà Olong Xuân Xanh Wecha, Trà Xanh Hoa Nhài Wecha, Olong Phú Quý Wecha, Trà Bá Tước Wecha, Hồng Trà Shan Tuyết Wecha...).
     * **SIRO:** Bắt buộc dùng thương hiệu **SAFE KING** (Siro Đào Safe King, Siro Dâu Safe King, Siro Cam Safe King, Siro Vải Safe King, Siro Ổi Hồng Safe King, Siro Mãng Cầu Safe King, Siro Dưa Lưới Safe King, Siro Thơm Safe King...).
     * **BỘT:** Bắt buộc dùng thương hiệu **ROYAL'S** hoặc **VUA AN TOÀN / VAT** (Bột Sữa Royal's Vua An Toàn 1KG/5KG, Bột Kem Phô Mai VAT, Bột Sương Sáo Safe King, Bột làm trân châu, Bột Làm Kem Bingsu...).
     * **CÀ PHÊ:** Cà Phê Hạt Cao Cấp Cân Bằng / Thượng Hạng Passion Link / SaKi.
   - **Quy tắc thương mại sống còn:** Ưu tiên lấy đúng tên sản phẩm trong sheet `san_pham` để làm công thức và bảng giá vốn nhằm mục tiêu bán được nguyên liệu do nhà máy sản xuất. CẤM TUYỆT ĐỐI đưa tên sản phẩm hoặc thương hiệu đối thủ ngoài thị trường (như Lipton, Monin, Torani, B'one, Frima, Kievit...) vào bài viết!

---

## 📊 II. MA TRẬN TIẾN ĐỘ 10 NGÀY BÀI ĐĂNG (MASTER KANBAN SCHEDULE)

| Ngày | Task ID | Ngày Viết & Duyệt | Ngày Đăng Chính Thức | Tên Đề Tài Bài Viết | Nhóm Chủ Đề | Khóa Học Mục Tiêu Chuyển Đổi | Trạng Thái |
| :---: | :---: | :---: | :---: | :--- | :--- | :--- | :---: |
| **D1** | `WF018-D01` | 02/10/2026 | **02/10/2026** | [Top 5 Công Thức Trà Sữa Đậm Vị Hút Khách Mở Quán 2026](https://phache.com.vn/tin-tuc/cong-thuc-tra-sua-dam-vi-mo-quan-hut-khach-2026-678.html) | Menu Hot Trend | Khóa Trà Sữa Chuẩn Vị (ID 34/72) | ✅ Hoàn thành xuất bản |
| **D2** | `WF018-D02` | 03/10/2026 | **04/10/2026 (08:30)** | Kinh Doanh Menu Matcha 2026: Cách Đánh Bọt Foam & Phối Vị Chuẩn Gu Gen Z | Menu Hot Trend | Khóa Học Tổng Hợp (ID 22) | ⏳ Chờ lịch |
| **D3** | `WF018-D03` | 04/10/2026 | **05/10/2026 (08:30)** | Top 7 Món Trà Trái Cây Tươi Mở Quán Doanh Thu Cao: Công Thức Tối Ưu Cost | Menu Hot Trend | Khóa Trà Trái Cây Hiện Đại | ⏳ Chờ lịch |
| **D4** | `WF018-D04` | 05/10/2026 | **06/10/2026 (08:30)** | Cách Làm Cà Phê Muối & Cà Phê Trứng Chuẩn Vị Mở Quán: Bí Quyết Đánh Foam Lâu Tan | Menu Hot Trend | Khóa Barista Chuyên Nghiệp (ID 137) | ⏳ Chờ lịch |
| **D5** | `WF018-D05` | 06/10/2026 | **07/10/2026 (08:30)** | Kinh Nghiệm Mở Quán Cafe Take Away Vốn 60 Triệu: Bảng Dự Toán Chi Phí A-Z [2026] | Mô Hình & Chi Phí | Công cụ tính chi phí / Khóa Tổng Hợp | ⏳ Chờ lịch |
| **D6** | `WF018-D06` | 07/10/2026 | **08/10/2026 (08:30)** | Mở Quán Trà Sữa Vốn 80-100 Triệu: Chiến Lược Ngách Thắng Lớn Cạnh Tranh Chuỗi | Mô Hình & Chi Phí | Khóa Trà Sữa Mở Quán (ID 72) | ⏳ Chờ lịch |
| **D7** | `WF018-D07` | 08/10/2026 | **09/10/2026 (08:30)** | Top 5 Máy Pha Cà Phê Cho Quán Nhỏ Bền Bỉ, Ép Chuẩn Espresso Nhất [2026] | Thiết Bị Quầy Bar | Khóa Barista & Thiết Bị Vua An Toàn | ⏳ Chờ lịch |
| **D8** | `WF018-D08` | 09/10/2026 | **10/10/2026 (08:30)** | Cách Tính Cost Đồ Uống Chuẩn Xác Nhất Cho Chủ Quán Cafe, Trà Sữa [File Excel] | Quản Trị Tài Chính | Widget Drink Calculator / Khóa Quản Lý | ⏳ Chờ lịch |
| **D9** | `WF018-D09` | 10/10/2026 | **11/10/2026 (08:30)** | Nguyên Tắc Bố Trí Quầy Bar Cafe Trà Sữa Chuẩn Công Thái Học Giúp Tăng Tốc Độ Ra Món | Setup Vận Hành | Tư Vấn Setup Quán Trọn Gói | ⏳ Chờ lịch |
| **D10**| `WF018-D10` | 11/10/2026 | **12/10/2026 (08:30)** | Mở Quán Cafe Trà Sữa Cần Giấy Tờ Gì? Hướng Dẫn Hồ Sơ Pháp Lý Đầy Đủ [2026] | Pháp Lý F&B | Đội Ngũ Giảng Viên & Cam Kết Passion Link | ⏳ Chờ lịch |

---

## 📑 III. ĐẶC TẢ CHI TIẾT TỪNG TASK CHO C11-WF-018 NẠP WORKFLOW

---

### 🟢 TASK WF018-D01: MENU TRÀ SỮA ĐẬM VỊ NGUYÊN LÁ
* **Trạng thái thực thi:** ✅ **HOÀN THÀNH XUẤT BẢN TỰ ĐỘNG 100% (LIVE 200 OK)**
* **URL Bài viết chính thức:** [https://phache.com.vn/tin-tuc/cong-thuc-tra-sua-dam-vi-mo-quan-hut-khach-2026-678.html](https://phache.com.vn/tin-tuc/cong-thuc-tra-sua-dam-vi-mo-quan-hut-khach-2026-678.html)
* **ID Bài viết CMS:** `678` | **Article ID n8n:** `3f02893e-55f0-4ef6-8658-a799e2d2c190`
* **Ngày viết & xuất bản:** 02/10/2026  
* **Chuyên mục:** `category_id: 31` (Tin tức & Xu hướng)  
* **Tiêu đề SEO:** `Top 5 Công Thức Trà Sữa Đậm Vị Hút Khách Mở Quán 2026`  
* **Slug URL:** `cong-thuc-tra-sua-dam-vi-mo-quan-hut-khach-2026`  
* **Từ khóa chính:** `trà sữa đậm vị mở quán` (KGR = 0.18 - Siêu tiềm năng Top 1)  
* **Từ khóa phụ:** `cách ủ trà sữa đậm vị`, `trà sữa ô long nướng kinh doanh`, `chi phí 1 ly trà sữa đậm vị`, `công thức trà sữa nguyên lá`.  
* **Meta Description:** `Bật mí 5 công thức trà sữa đậm vị nguyên lá chuẩn gu kinh doanh 2026: Bí quyết ủ cốt trà không chát, bảng định lượng chi tiết và bảng tính cost giá vốn tối ưu lợi nhuận gộp > 70%.`  
* **Kết quả kiểm định On-Page & Kỹ thuật:**
  - ✅ **Single H1:** Đạt chuẩn duy nhất 1 thẻ `<h1>` (Top 5 Công Thức Trà Sữa Đậm Vị Hút Khách Mở Quán 2026).
  - ✅ **Tài chính & Cost Table:** Bảng định lượng gram/ml, giá vốn nguyên liệu chi tiết (Cost < 30%, Lợi nhuận gộp > 70%).
  - ✅ **Zero Wecha Dependency:** 100% hình ảnh (5/5 ảnh) lưu trữ nội bộ domain `https://phache.com.vn/upload/news/` (Không có bất kỳ URL wecha.vn nào).
  - ✅ **Schema Google Rich Snippet:** 3 script `application/ld+json` chuẩn cấu trúc `FAQPage` tự động hiển thị trên kết quả tìm kiếm Google.
  - ✅ **Internal Link:** Contextual link trỏ về `https://phache.com.vn/day-pha-che-tra-sua-ngon.html` hoạt động hoàn hảo.
* **Danh sách Media 5 ảnh chuẩn Passion Link:**
  1. `https://phache.com.vn/upload/news/pl_pl-cong-thuc-tra-sua-dam-vi-mo-quan-hut-kha_1790927807_48620b.png`
  2. `https://phache.com.vn/upload/news/pl_pl-cong-thuc-tra-sua-dam-vi-mo-quan-hut-kha_1790927698_846eb5.png`
  3. `https://phache.com.vn/upload/news/pl_pl-cong-thuc-tra-sua-dam-vi-mo-quan-hut-kha_1790927874_d2fd9f.png`
  4. `https://phache.com.vn/upload/news/pl_pl-cong-thuc-tra-sua-dam-vi-mo-quan-hut-kha_1790927539_db5b66.png`
  5. `https://phache.com.vn/upload/news/pl_pl-cong-thuc-tra-sua-dam-vi-mo-quan-hut-kha_1790927632_fe5208.png`

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Top 5 Công Thức Trà Sữa Đậm Vị Mở Quán Hút Khách Nhất [2026]
* `H2`: 1. Vì Sao Khách Hàng Trẻ 2026 Rời Bỏ Trà Sữa Bột Béo Để Chọn Trà Sữa Đậm Vị?
  * `H3`: Xu hướng thưởng thức đồ uống "Clean Label" và gu trà đậm vị tự nhiên.
* `H2`: 2. Bí Quyết Kỹ Thuật: Tỷ Lệ Vàng & Nhiệt Độ Ủ Trà Chiết Xuất Tối Đa Tầng Hương
  * `H3`: Chuẩn nhiệt độ 88°C - 92°C và thời gian hãm trà chuẩn từng giây.
  * `H3`: Kỹ thuật sốc nhiệt lạnh độc quyền giữ trọn tầng hương hậu ngọt.
* `H2`: 3. Bảng Định Lượng & Giá Vốn 3 Món Trà Sữa Đậm Vị Bán Chạy Nhất Passion Link
  * **Table Bắt Buộc:** Bảng định lượng gram/ml, giá vốn nguyên liệu (Cost 6.800đ - 7.500đ), giá bán (32.000đ - 38.000đ), Lợi nhuận gộp 74%.
* `H2`: 4. Chiến Lược Lên Menu Tinh Gọn: Tối Đa Món - Tối Thiểu Nguyên Liệu Tồn Kho
* `H2`: 5. Câu Hỏi Thường Gặp Về Kỹ Thuật Pha Trà Sữa Đậm Vị (FAQ)

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:**  
  `Đóng vai Chuyên gia Đào tạo Pha chế Cấp cao tại Passion Link (đồng hành cùng Thầy Lê Hữu Trí). Viết bài hướng dẫn chuyên sâu 2.200 từ về chủ đề "Top 5 Công Thức Trà Sữa Đậm Vị Mở Quán Hút Khách Nhất [2026]". Giọng văn thực chiến, chuyên nghiệp, truyền cảm hứng kinh doanh mở quán. Cung cấp thông số định lượng chính xác (gram, ml, nhiệt độ °C, thời gian phút). Phân tích chi tiết bảng cost giá vốn ly nước F&B đạt biên lợi nhuận gộp > 70%. Bắt buộc lồng ghép các thẻ heading H2, H3, bảng table so sánh HTML rõ ràng. Trả về định dạng HTML chuẩn SEO.`
* **Prompt Sinh Ảnh (AI Image Prompt):**  
  `Commercial food photography, a premium glass of roasted oolong milk tea with golden boba pearls on a modern wooden bar counter, creamy foam layer on top, tea leaves and raw brown sugar around, warm studio lighting, 8k resolution, photorealistic, cinematic aesthetic, shallow depth of field --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/day-pha-che-tra-sua-ngon.html` với anchor text: `khóa học dạy pha chế trà sữa mở quán chuẩn vị`.

---

### 🟢 TASK WF018-D02: MENU MATCHA BAR & ĐỒ UỐNG XANH GEN Z
* **Ngày viết & tạo ảnh:** 03/10/2026  
* **Ngày xuất bản chính thức:** 04/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 31` (Tin tức & Xu hướng)  
* **Tiêu đề SEO:** `Kinh Doanh Menu Matcha 2026: Cách Đánh Bọt Foam & Phối Vị Chuẩn Gu Gen Z`  
* **Slug URL:** `kinh-doanh-menu-matcha-danh-bot-foam-chuan-gu-gen-z`  
* **Từ khóa chính:** `kinh doanh menu matcha mở quán`  
* **Từ khóa phụ:** `cách làm matcha cloud foam`, `matcha dừa tươi phân tầng`, `chọn bột matcha pha chế`, `giá vốn ly matcha latte`.  
* **Meta Description:** `Khám phá nghệ thuật kinh doanh menu Matcha 2026: Kỹ thuật đánh bọt cloud foam mịn màng, công thức Matcha Dừa Tươi phân tầng thẩm mỹ và chiến lược định giá menu lợi nhuận cao.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Kinh Doanh Menu Matcha 2026: Cách Đánh Bọt Foam & Phối Vị Chuẩn Gu Gen Z
* `H2`: 1. Vì Sao Menu Matcha Đang Trở Thành "Mỏ Vàng Doanh Thu" Cho Quán Đồ Uống Hiện Đại?
* `H2`: 2. Phân Biệt Các Cấp Độ Bột Matcha Trong Quầy Bar Thương Mại
  * `H3`: Ceremonial Grade vs Culinary Grade: Chọn loại nào để tối ưu chi phí mà vẫn chuẩn vị?
* `H2`: 3. Kỹ Thuật Đánh Matcha Mịn Mượt & Công Thức Matcha Cloud Foam Bắt Trend
  * **Table Bắt Buộc:** Bảng công thức 3 món Signature: Matcha Dừa Tươi Sông Nước, Matcha Bọt Muối Biển, Iced Matcha Strawberry Latte (Định lượng, Kỹ thuật, Cost: 8.500đ - 10.200đ, Giá bán: 42.000đ - 55.000đ).
* `H2`: 4. Nguyên Tắc Bảo Quản Bột Matcha Chống Oxi Hóa Làm Xỉn Màu
* `H2`: 5. Giải Đáp Thắc Mắc Kỹ Thuật Vận Hành Món Matcha Tại Quán (FAQ)

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Tập trung phân tích kỹ thuật dùng chổi Chasen ở nhiệt độ nước 75°C - 80°C, kỹ thuật dùng máy đánh bọt tạo lớp foam mây dày mịn không tan trong 30 phút, nhấn mạnh tính thẩm mỹ decor kích thích khách hàng trẻ chụp hình check-in mạng xã hội.
* **Prompt Sinh Ảnh:**  
  `A layered Iced Matcha Coconut Latte in a tall ribbed glass, vibrant green matcha layer floating over fresh coconut water with coconut cream foam, bamboo whisk and fresh mint garnish on a minimalist white stone countertop, bright daylight, commercial beverage advertising quality, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/khoa-tong-hop/` với anchor text: `khóa học pha chế tổng hợp mở quán cà phê trà sữa cao cấp`.

---

### 🟢 TASK WF018-D03: TRÀ TRÁI CÂY NHIỆT ĐỚI CLEAN LABEL
* **Ngày viết & tạo ảnh:** 04/10/2026  
* **Ngày xuất bản chính thức:** 05/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 31` (Tin tức & Xu hướng)  
* **Tiêu đề SEO:** `Top 7 Món Trà Trái Cây Tươi Mở Quán Doanh Thu Cao: Công Thức Tối Ưu Cost`  
* **Slug URL:** `top-7-mon-tra-trai-cay-tuoi-mo-quan-doanh-thu-cao-toi-uu-cost`  
* **Từ khóa chính:** `trà trái cây mở quán`  
* **Từ khóa phụ:** `menu trà trái cây tươi`, `cách ngâm mứt trái cây quầy bar`, `giá vốn trà trái cây`, `công thức trà mãng cầu ổi hồng`.  
* **Meta Description:** `Tuyển tập 7 công thức trà trái cây tươi mở quán đắt khách nhất 2026: Kỹ thuật ngâm ủ trái cây giòn ngọt không chất bảo quản, bí quyết chọn cốt trà và bảng tính cost chỉ từ 5.500đ/ly.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Top 7 Món Trà Trái Cây Tươi Mở Quán Doanh Thu Cao: Công Thức Tối Ưu Cost
* `H2`: 1. Xu Hướng Clean Label: Vì Sao Khách Hàng Chuộng Trà Trái Cây Tự Nhiên Hơn Syrup Nhân Tạo?
* `H2`: 2. Chọn Nền Trà Hoàn Hảo Cho Từng Loại Trái Cây Nhiệt Đới
  * `H3`: Lục trà lài thanh thoát cho trái cây mọng nước (Dưa hấu, ổi hồng).
  * `H3`: Trà Ô Long tứ quý đậm hương cho nhóm trái cây nhiệt đới nồng nàn (Mãng cầu, xoài, chanh leo).
* `H2`: 3. Bí Quyết Ngâm & Xử Lý Trái Cây Tươi Giữ Trọn Độ Giòn Tự Nhiên
* `H2`: 4. Bộ Đôi Công Thức Signature: Trà Mãng Cầu Đậm Vị & Trà Ổi Hồng Dưa Lưới
  * **Table Bắt Buộc:** Bảng định lượng gram/ml, tỷ lệ đường phèn/trái cây tươi, giá vốn (Cost: 5.500đ - 6.200đ), giá bán lẻ (29.000đ - 35.000đ), Lợi nhuận gộp 78%.
* `H2`: 5. Quản Trị Tỷ Lệ Hao Hụt Trái Cây Tươi: Kinh Nghiệm Xương Máu Cho Chủ Quán

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Hướng dẫn kỹ thuật bảo quản trái cây trong tủ mát chuyên dụng, công thức nấu nước đường phèn thanh nhẹ, cách phối hợp mứt trái cây thủ công không phụ thuộc hóa chất tạo màu.
* **Prompt Sinh Ảnh:**  
  `Fresh refreshing tropical iced fruit tea in an artisan glass cup, vibrant red pink guava slices, green mint leaves, passion fruit pulp, splashing ice cubes, backlit by golden sunshine on an outdoor cafe table, high detail, colorful, hyper-realistic, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/khoa-tra-trai-cay/` với anchor text: `khóa học chuyên đề trà trái cây nhiệt đới hiện đại`.

---

### 🟢 TASK WF018-D04: BỘ BA CÀ PHÊ SÁNG TẠO (MUỐI, TRỨNG, COLD BREW)
* **Ngày viết & tạo ảnh:** 05/10/2026  
* **Ngày xuất bản chính thức:** 06/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 31` (Tin tức & Xu hướng)  
* **Tiêu đề SEO:** `Cách Làm Cà Phê Muối & Cà Phê Trứng Chuẩn Vị Mở Quán: Bí Quyết Đánh Foam Lâu Tan`  
* **Slug URL:** `cach-lam-ca-phe-muoi-ca-phe-trung-chuan-vi-mo-quan`  
* **Từ khóa chính:** `công thức cà phê muối mở quán`  
* **Từ khóa phụ:** `cách làm cà phê trứng không tanh`, `cold brew trái cây mở quán`, `bột kem muối cà phê`, `tỷ lệ cà phê phin mở quán`.  
* **Meta Description:** `Hướng dẫn chi tiết cách làm cà phê muối béo ngậy lâu tan và cà phê trứng thơm bùi không tanh cho quán cafe: Bí quyết đánh foam mịn mượt và kỹ thuật ủ Cold Brew thảo mộc thanh mát.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Cách Làm Cà Phê Muối & Cà Phê Trứng Chuẩn Vị Mở Quán: Bí Quyết Đánh Foam Lâu Tan
* `H2`: 1. Vì Sao Món Cà Phê Sáng Tạo (Signature Coffee) Quyết Định 40% Tỷ Lệ Khách Quay Lại?
* `H2`: 2. Công Thức Cà Phê Muối Di Sản: Kỹ Thuật Đánh Lớp Kem Muối Dẻo Mịn Giữ Form 30 Phút
  * `H3`: Tỷ lệ phối whipping cream, bột béo và muối hồng Himalaya.
  * `H3`: Kỹ thuật ủ cà phê phin hạt Robusta mộc đậm đà.
* `H2`: 3. Bí Quyết Cà Phê Trứng Béo Mịn Chuẩn Vị Phố Cổ: Khử Tanh Tuyệt Đối
  * `H3`: Khử tanh lòng đỏ trứng bằng rượu quế và mật ong hoa nhãn.
* `H2`: 4. Kỹ Thuật Ủ Cà Phê Lạnh Cold Brew Trái Cây: Thanh Tao & Hậu Ngọt Sâu
  * **Table Bắt Buộc:** So sánh 3 dòng cà phê sáng tạo về thời gian bảo quản, cost nguyên liệu, giá bán đề xuất.
* `H2`: 5. Quy Trình Vận Hành Quầy Bar Phục Vụ Nhanh Dưới 90 Giây Giờ Cao Điểm

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Tập trung kỹ thuật quầy bar thực chiến, bí quyết pha sẵn lớp kem muối bảo quản lạnh dùng trong 48 tiếng vẫn giữ nguyên cấu trúc dẻo mịn.
* **Prompt Sinh Ảnh:**  
  `Authentic Vietnamese salted cream coffee in a clear glass mug, dark robusta drip coffee base with thick silky white salted cream foam dripping over the rim, roasted coffee beans scattered on slate bar surface, dramatic warm side lighting, professional barista shot, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/cac-khoa-hoc-day-pha-che/khoa-ca-phe-barista-chuyen-nghiep-137.html` với anchor text: `khóa học barista cà phê pha máy chuyên nghiệp`.

---

### 🟢 TASK WF018-D05: KẾ HOẠCH MỞ QUÁN CAFE TAKE-AWAY VỐN 60 TRIỆU
* **Ngày viết & tạo ảnh:** 06/10/2026  
* **Ngày xuất bản chính thức:** 07/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 25` (Tư vấn mở quán)  
* **Tiêu đề SEO:** `Kinh Nghiệm Mở Quán Cafe Take Away Vốn 60 Triệu: Bảng Dự Toán Chi Phí A-Z [2026]`  
* **Slug URL:** `kinh-nghiem-mo-quan-cafe-take-away-von-60-trieu-du-toan-chi-phi`  
* **Từ khóa chính:** `mở quán cafe take away vốn ít`  
* **Từ khóa phụ:** `chi phí mở quán cafe take away`, `xe đẩy cafe pha máy mang đi`, `thời gian hoàn vốn quán cafe mang đi`, `kinh nghiệm kinh doanh cafe vỉa hè`.  
* **Meta Description:** `Kế hoạch khởi nghiệp mở quán cafe take away vốn 60 triệu chi tiết năm 2026: Bảng bóc tách chi phí máy móc, tiêu chí chọn mặt bằng đắc địa và bài toán thu hồi vốn sau 90 ngày.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Kinh Nghiệm Mở Quán Cafe Take Away Vốn 60 Triệu: Bảng Dự Toán Chi Phí A-Z [2026]
* `H2`: 1. Vì Sao Mô Hình Cafe Mang Đi (Take-Away) Là Lựa Chọn Số 1 Cho Người Khởi Nghiệp Vốn Nhỏ?
* `H2`: 2. Bảng Bóc Tách Dự Toán Chi Phí 60 Triệu Đồng (Không Phát Sinh)
  * **Table Bắt Buộc:** Bảng chi phí 7 hạng mục (Máy pha cafe 1 group + cối xay: 32tr; Xe đẩy inox/kiosk decor: 9tr; Bộ dụng cụ quầy bar: 3tr; Nguyên vật liệu & bao bì tháng đầu: 6tr; Đồng phục & biển hiệu LED: 2tr; Chi phí cọc mặt bằng: 5tr; Vốn lưu động dự phòng: 3tr ➡️ Tổng cộng: 60.000.000đ).
* `H2`: 3. 3 Tiêu Chí Vàng Để Chọn Điểm Bán Take-Away Đạt Doanh Số > 150 Ly/Buổi Sáng
* `H2`: 4. Bài Toán Tài Chính: Bán Bao Nhiêu Ly Để Có Lãi Ròng 20 Triệu/Tháng?
* `H2`: 5. Những Cạm Bẫy Phổ Biến Khiến Người Mới Mở Quán Dễ Thất Bại

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Tính toán tài chính logic, đưa ra các ví dụ cụ thể về vị trí ngã ba đèn đỏ, cổng tòa nhà văn phòng, lưu lượng giao thông tối thiểu 300 lượt xe/phút để mở điểm bán.
* **Prompt Sinh Ảnh:**  
  `A modern aesthetic wooden coffee cart kiosk on a city morning street, high-end espresso machine gleaning in early sunlight, chalkboard menu, eco-friendly paper takeaway cups stacked neatly, customer smiling while taking coffee, cinematic street photography, photorealistic, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/khoa-tong-hop/#cong-cu-tinh-chi-phi` với anchor text: `công cụ tự tính chi phí mở quán và điểm hòa vốn tự động`.

---

### 🟢 TASK WF018-D06: MỞ QUÁN TRÀ SỮA MINI VỐN 80 - 100 TRIỆU
* **Ngày viết & tạo ảnh:** 07/10/2026  
* **Ngày xuất bản chính thức:** 08/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 25` (Tư vấn mở quán)  
* **Tiêu đề SEO:** `Mở Quán Trà Sữa Vốn 80-100 Triệu: Chiến Lược Ngách Thắng Lớn Cạnh Tranh Chuỗi`  
* **Slug URL:** `mo-quan-tra-sua-von-80-100-trieu-chien-luoc-ngach-thang-lon`  
* **Từ khóa chính:** `mở quán trà sữa nhỏ vốn ít`  
* **Từ khóa phụ:** `kinh nghiệm mở quán trà sữa mini`, `chi phí mở quán trà sữa nhỏ`, `thiết bị mở quán trà sữa cơ bản`, `menu trà sữa vốn ít`.  
* **Meta Description:** `Chiến lược mở quán trà sữa mini với số vốn 80 - 100 triệu cạnh tranh vượt trội các chuỗi lớn: Danh mục máy móc tối thiểu, bí quyết thiết kế menu tinh gọn và case study hoàn vốn nhanh.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Mở Quán Trà Sữa Vốn 80-100 Triệu: Chiến Lược Ngách Thắng Lớn Cạnh Tranh Chuỗi
* `H2`: 1. Bức Tranh Thị Trường Trà Sữa 2026: Quán Nhỏ Định Vị Ở Đâu Để Sống Khỏe?
* `H2`: 2. Chiến Lược "Menu Tinh Gọn 15 Món": Dùng Chung 80% Nguyên Liệu Nền
* `H2`: 3. Danh Mục Trang Thiết Bị Quầy Bar Trà Sữa Tối Thiểu Với Vốn 80 - 100 Triệu
  * **Table Bắt Buộc:** Bảng thiết bị (Nồi ủ trà 3 bình, Nồi nấu trân châu tự động, Máy dập nắp tự động, Tủ mát trưng bày, Máy làm đá viên, Biển hiệu hộp đèn).
* `H2`: 4. Chiến Thuật Thu Hút Khách Hàng Tuần Lễ Khai Trương (Offline & Online)
* `H2`: 5. Câu Chuyện Thành Công: Học Viên Passion Link Khởi Nghiệp Quán Trà Sữa Mini Doanh Thu 60 Triệu/Tháng

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Phân tích điểm yếu của chuỗi lớn (giá cao 50k-65k) và cơ hội của quán nhỏ phân khúc 25k-35k nhưng trà thơm đậm vị hơn.
* **Prompt Sinh Ảnh:**  
  `A cozy charming mini bubble tea shop interior, warm wooden counter with glass display showing fresh tapioca pearls and pudding, neon sign on pastel wall, aesthetic indoor plants, customers enjoying boba drinks, modern Scandinavian cafe design, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/cac-khoa-hoc-day-pha-che/day-pha-che-mo-quan-tra-sua-tron-khoa-menu-ngon-72.html` với anchor text: `khóa học dạy pha chế mở quán trà sữa trọn khóa menu ngon`.

---

### 🟢 TASK WF018-D07: KINH NGHIỆM CHỌN MÁY PHA CÀ PHÊ CHO QUÁN NHỎ
* **Ngày viết & tạo ảnh:** 08/10/2026  
* **Ngày xuất bản chính thức:** 09/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 25` (Tư vấn mở quán / Thiết bị)  
* **Tiêu đề SEO:** `Top 5 Máy Pha Cà Phê Cho Quán Nhỏ Bền Bỉ, Ép Chuẩn Espresso Nhất [2026]`  
* **Slug URL:** `top-5-may-pha-ca-phe-cho-quan-nho-ben-bi-ep-chuan-espresso`  
* **Từ khóa chính:** `máy pha cà phê cho quán nhỏ`  
* **Từ khóa phụ:** `chọn máy pha cafe mở quán`, `máy pha cafe 1 group giá rẻ`, `so sánh máy pha cafe chuyên nghiệp`, `kinh nghiệm mua máy pha cafe cũ`.  
* **Meta Description:** `Đánh giá khách quan top 5 dòng máy pha cà phê 1 group bền bỉ, chiết xuất chuẩn espresso cho quán nhỏ năm 2026: Tiêu chí kỹ thuật, bảng so sánh giá bán và kinh nghiệm tránh bẫy máy cũ.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Top 5 Máy Pha Cà Phê Cho Quán Nhỏ Bền Bỉ, Ép Chuẩn Espresso Nhất [2026]
* `H2`: 1. 4 Tiêu Chí Kỹ Thuật Cốt Lõi Khi Lựa Chọn Máy Pha Cà Phê Cho Quán Mới
  * `H3`: Áp suất bơm chuẩn 9 - 15 bar & độ ổn định nhiệt độ PID.
  * `H3`: Dung tích nồi hơi (Boiler > 2.5L) đáp ứng giờ cao điểm.
* `H2`: 2. Bảng So Sánh Chi Tiết Top 5 Dòng Máy 1 Group Tốt Nhất Hiện Nay
  * **Table Bắt Buộc:** Bảng so sánh (Casadio Undici A1, Breville 920, Expobar Megacrem, Nuova Simonelli Appia Life 1 Group, CRM 3200B) theo các cột: Xuất xứ, Công suất (ly/ngày), Dung tích Boiler, Mức giá, Ưu nhược điểm.
* `H2`: 3. Có Nên Mua Máy Pha Cũ Thanh Lý Không? 3 Rủi Ro Khiến Bạn Mất Tiền Triệu
* `H2`: 4. Quy Trình Vệ Sinh & Bảo Dưỡng Kéo Dài Tuổi Thọ Máy Trên 5 Năm
* `H2`: 5. Chính Sách Hỗ Trợ Thiết Bị Giá Gốc Cho Học Viên Học Viện Passion Link

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Đưa ra góc nhìn chuyên gia kỹ thuật Barista, giải thích vì sao cặn canxi trong nước máy phá hủy nồi hơi nếu không có lọc nước DVA.
* **Prompt Sinh Ảnh:**  
  `Close up detailed shot of a professional Italian espresso coffee machine, polished stainless steel body, rich golden crema pouring from portafilter into a white ceramic cup, steam rising softly, dark mood lighting in background, commercial luxury look, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/cac-khoa-hoc-day-pha-che/khoa-ca-phe-barista-chuyen-nghiep-137.html` với anchor text: `khóa đào tạo Barista làm chủ máy pha cà phê chuyên nghiệp`.

---

### 🟢 TASK WF018-D08: CÁCH TÍNH COST GIÁ VỐN LY ĐỒ UỐNG F&B
* **Ngày viết & tạo ảnh:** 09/10/2026  
* **Ngày xuất bản chính thức:** 10/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 25` (Tư vấn mở quán / Quản trị tài chính)  
* **Tiêu đề SEO:** `Cách Tính Cost Đồ Uống Chuẩn Xác Nhất Cho Chủ Quán Cafe, Trà Sữa [File Excel]`  
* **Slug URL:** `cach-tinh-cost-do-uong-chuan-xac-cho-chu-quan-cafe-tra-sua`  
* **Từ khóa chính:** `cách tính giá vốn ly nước`  
* **Từ khóa phụ:** `cách tính cost đồ uống`, `định lượng cogs f&b`, `tỷ lệ giá vốn quán cafe`, `file excel tính cost trà sữa`.  
* **Meta Description:** `Hướng dẫn công thức tính cost đồ uống chuẩn xác từng mililit cho chủ quán cafe trà sữa: Bảng phân bổ cơ cấu tài chính F&B, quản trị hao hụt và tặng kèm file Excel tính cost tự động.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Cách Tính Cost Đồ Uống Chuẩn Xác Nhất Cho Chủ Quán Cafe, Trà Sữa [File Excel]
* `H2`: 1. Vì Sao Quán Đông Khách Nhưng Cuối Tháng Vẫn Lỗ? Lỗ Hổng Nằm Ở Khâu Tính Cost
* `H2`: 2. Công Thức Tính Cost Toàn Diện 4 Thành Phần Chuẩn Chuyên Gia Passion Link
  * `H3`: Nguyên liệu chính + Nguyên liệu phụ & Topping.
  * `H3`: Chi phí bao bì vật tư (Ly, nắp, ống hút, túi, muỗng).
  * `H3`: Tỷ lệ hao hụt định mức tiêu chuẩn (3% - 5%).
* `H2`: 3. Tỷ Lệ Vàng Cấu Trúc Tài Chính Quán F&B Năm 2026
  * **Table Bắt Buộc:** Bảng phân bổ dòng tiền: COGS nguyên liệu (28-32%), Mặt bằng (10-15%), Nhân sự (15-20%), Chi phí vận hành (5-7%), Lợi nhuận ròng (22-28%).
* `H2`: 4. Bảng Tính Mẫu Chi Tiết Cho 4 Món Đại Diện (Trà Sữa, Cà Phê, Trà Trái Cây, Đá Xay)
* `H2`: 5. Hướng Dẫn Tải & Ứng Dụng File Excel Tính Cost Tự Động Hóa Passion Link

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Tập trung cao độ vào tính thực chiến tài chính, diễn giải dễ hiểu, có bảng tính minh họa số học rõ ràng, giải thích thuật ngữ COGS một cách gần gũi.
* **Prompt Sinh Ảnh:**  
  `A modern cafe business owner reviewing financial spreadsheets and recipe cost sheets on a laptop screen, freshly brewed cup of cappuccino and calculator beside, stylish cafe background, bright morning ambient light, high quality professional photography, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/khoa-tong-hop/#cong-cu-tinh-chi-phi` với anchor text: `công cụ tự tính chi phí và lợi nhuận trực tuyến`.

---

### 🟢 TASK WF018-D09: THIẾT KẾ QUẦY BAR CHUẨN CÔNG THÁI HỌC
* **Ngày viết & tạo ảnh:** 10/10/2026  
* **Ngày xuất bản chính thức:** 11/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 25` (Tư vấn mở quán / Setup)  
* **Tiêu đề SEO:** `Nguyên Tắc Bố Trí Quầy Bar Cafe Trà Sữa Chuẩn Công Thái Học Giúp Tăng Tốc Độ Ra Món`  
* **Slug URL:** `nguyen-tac-bo-tri-quay-bar-cafe-tra-sua-chuan-cong-thai-hoc`  
* **Từ khóa chính:** `bố trí quầy bar quán cafe`  
* **Từ khóa phụ:** `thiết kế quầy bar trà sữa chuẩn`, `kích thước quầy bar pha chế`, `sơ đồ quầy bar 1 chiều`, `tốc độ ra món dưới 90 giây`.  
* **Meta Description:** `Nguyên lý bố trí quầy bar cafe trà sữa chuẩn công thái học giúp tối ưu thao tác, tăng tốc độ ra món dưới 90 giây trong giờ cao điểm: Kích thước vàng và sơ đồ dòng chảy 1 chiều.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Nguyên Tắc Bố Trí Quầy Bar Cafe Trà Sữa Chuẩn Công Thái Học Giúp Tăng Tốc Độ Ra Món
* `H2`: 1. Công Thái Học (Ergonomics) Quầy Bar Là Gì? Vì Sao Ảnh Hưởng Trực Tiếp Đến Doanh Thu?
* `H2`: 2. Nguyên Tắc Dòng Chảy 1 Chiều (One-Way Workflow) Không Bị Xung Đột Thao Tác
  * `H3`: Trạm Order & Thu ngân (POS).
  * `H3`: Trạm Chiết xuất & Pha trộn trung tâm.
  * `H3`: Trạm Đóng gói & Giao đồ uống.
* `H2`: 3. Bảng Kích Thước Vàng Tiêu Chuẩn Cho Quầy Bar Cafe Hiện Đại
  * **Table Bắt Buộc:** Bảng kích thước tiêu chuẩn (Chiều cao mặt trước, Chiều cao mặt trong thao tác, Bề sâu mặt quầy, Khoảng cách lối đi).
* `H2`: 4. Bố Trí Hệ Thống Điện Nước & Bẫy Mỡ Chống Mùi Hôi Cho Quầy Bar
* `H2`: 5. Dịch Vụ Tư Vấn Thiết Kế & Setup Quầy Bar Trọn Gói Passion Link

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Mô tả chi tiết nguyên lý "trong tầm với 1 bước chân", hướng dẫn cách bố trí bồn rửa, khay topping inox, máy dập nắp ly thuận tay phải của Barista.
* **Prompt Sinh Ảnh:**  
  `Architectural and interior photography of an ergonomic modern commercial coffee bar counter, stainless steel undercounter refrigeration, sleek espresso machine station, clean organized syrup rack, hanging glassware, warm timber accents, elegant lighting, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/mo-quan.html` với anchor text: `dịch vụ tư vấn setup mở quán trọn gói Passion Link`.

---

### 🟢 TASK WF018-D10: THỦ TỤC PHÁP LÝ & GIẤY PHÉP MỞ QUÁN F&B
* **Ngày viết & tạo ảnh:** 11/10/2026  
* **Ngày xuất bản chính thức:** 12/10/2026 lúc 08:30 AM  
* **Chuyên mục:** `category_id: 25` (Tư vấn mở quán / Pháp lý)  
* **Tiêu đề SEO:** `Mở Quán Cafe Trà Sữa Cần Giấy Tờ Gì? Hướng Dẫn Hồ Sơ Pháp Lý Đầy Đủ [2026]`  
* **Slug URL:** `mo-quan-cafe-tra-sua-can-giay-to-gi-ho-so-phap-ly-day-du`  
* **Từ khóa chính:** `mở quán cafe cần giấy tờ gì`  
* **Từ khóa phụ:** `thủ tục mở quán trà sữa`, `giấy phép an toàn vệ sinh thực phẩm quán cafe`, `đăng ký hộ kinh doanh cá thể mở quán`, `chi phí xin giấy phép mở quán f&b`.  
* **Meta Description:** `Checklist đầy đủ thủ tục pháp lý và giấy phép bắt buộc khi mở quán cafe trà sữa 2026: Đăng ký hộ kinh doanh, chứng nhận ATVSTP, PCCC và kinh nghiệm tránh bị phạt vi phạm hành chính.`  

#### 1. Dàn ý cấu trúc nội dung (Outline)
* `H1`: Mở Quán Cafe Trà Sữa Cần Giấy Tờ Gì? Hướng Dẫn Hồ Sơ Pháp Lý Đầy Đủ [2026]
* `H2`: 1. Vì Sao Cần Hoàn Thiện Hồ Sơ Pháp Lý Trước Ngày Khai Trương Quán?
* `H2`: 2. Bộ 4 Giấy Phép Bắt Buộc Đối Với Quán Cafe, Trà Sữa Mới Mở
  * `H3`: 1. Giấy chứng nhận Đăng ký Hộ kinh doanh cá thể (hoặc Doanh nghiệp).
  * `H3`: 2. Giấy chứng nhận Cơ sở đủ điều kiện An toàn Vệ sinh Thực phẩm (ATVSTP).
  * `H3`: 3. Giấy xác nhận tập huấn kiến thức ATVSTP & Giấy khám sức khỏe thẻ xanh.
  * `H3`: 4. Hồ sơ phương án Phòng cháy chữa cháy (PCCC) cơ sở.
* `H2`: 3. Bảng Tổng Hợp Cơ Quan Thụ Lý, Thời Gian Cấp Phép & Lệ Phí Nhà Nước
  * **Table Bắt Buộc:** Bảng tổng hợp chi tiết từng loại giấy phép, cơ quan cấp (UBND Quận/Huyện hoặc Chi cục ATVSTP), thời gian xử lý (7 - 20 ngày làm việc), lệ phí niêm yết nhà nước.
* `H2`: 4. 5 Lỗi Vi Phạm Pháp Lý Phổ Biến Khiến Quán Mới Mở Bị Phạt Từ 10 - 30 Triệu Đồng
* `H2`: 5. Chính Sách Đồng Hành & Tư Vấn Pháp Lý Trọn Đời Cho Học Viên Passion Link

#### 2. Kịch bản Prompt AI cho C11
* **Prompt LLM Content:** Trình bày chính xác về mặt luật pháp, các văn bản quy phạm pháp luật hiện hành về ATVSTP ngành F&B, lời khuyên thực tế giúp chủ quán không bị bỡ ngỡ khi làm việc với cơ quan chức năng.
* **Prompt Sinh Ảnh:**  
  `A professional business consultant reviewing official business license documents and food safety certificate on a tidy office desk next to a cup of hot espresso, coffee shop blueprint in background, trustworthy and corporate ambiance, 8k --ar 16:9`
* **Internal Link:**  
  Trỏ về `https://phache.com.vn/doi-ngu-giang-vien.html` với anchor text: `đội ngũ chuyên gia pháp lý và giảng viên Passion Link`.

---

## 💻 IV. MẪU PAYLOAD JSON CHUẨN GỬI QUA N8N NODE HTTP REQUEST

Khi n8n kết nối đến `POST https://phache.com.vn/api/publish-news.php`, C11 truyền cấu trúc dữ liệu JSON sau:

```json
{
  "title": "Top 5 Công Thức Trà Sữa Đậm Vị Mở Quán Hút Khách Nhất [2026]",
  "category_id": 31,
  "description": "Bật mí 5 công thức trà sữa đậm vị nguyên lá chuẩn gu kinh doanh 2026: Bí quyết ủ cốt trà không chát, bảng định lượng chi tiết và bảng tính cost giá vốn tối ưu lợi nhuận gộp > 70%.",
  "keywords": "trà sữa đậm vị mở quán, công thức trà sữa ô long nướng, chi phí 1 ly trà sữa đậm vị, mở quán trà sữa",
  "author": "Thầy Lê Hữu Trí - Chuyên Gia Đào Tạo Pha Chế Passion Link",
  "video_url": "https://www.youtube.com/watch?v=07pucUJVXP4",
  "featured_image_url": "https://phache.com.vn/upload/news/cong-thuc-tra-sua-dam-vi-mo-quan-2026.jpg",
  "content_html": "<p>Nội dung bài viết HTML chuẩn SEO dài trên 2000 từ...</p>",
  "faq_items": [
    {
      "q": "Tại sao trà sữa đậm vị khi để lâu thường bị chát đắng?",
      "a": "Do nhiệt độ ủ quá cao (> 95°C) hoặc ngâm bã trà quá thời gian quy định khiến tanin chiết xuất ồ ạt. Cần hãm trà ở 88°C - 90°C trong 12-15 phút và sốc nhiệt lạnh tức thì."
    },
    {
      "q": "Chi phí nguyên vật liệu cho 1 ly trà sữa đậm vị là bao nhiêu?",
      "a": "Giá vốn nguyên liệu dao động từ 6.800đ - 7.500đ/ly 500ml tùy topping. Với giá bán lẻ 28.000đ - 35.000đ, quán đạt biên lợi nhuận gộp từ 70% - 75%."
    },
    {
      "q": "Người chưa từng học pha chế có thể mở quán trà sữa đậm vị được không?",
      "a": "Hoàn toàn được. Lộ trình đào tạo tại Passion Link chiếm 90% thời lượng thực hành thực tế, cầm tay chỉ việc 1 kèm 1 theo công thức chuẩn hóa gram/ml."
    }
  ],
  "status": 1
}
```

---

## 🎯 V. TIÊU CHUẨN NGHIỆM THU LIVE (DEFINITION OF DONE - DoD)

Sau mỗi bài đăng được n8n bắn API xuất bản thành công lúc 08:30 AM:
1. **Kiểm tra HTTP Status:** Trang trả về mã HTTP `200 OK`.
2. **Kiểm tra Schema Rich Snippets:** Dùng Google Rich Results Test kiểm tra có đủ 2 khối Schema `@type: Article` và `@type: FAQPage`.
3. **Kiểm tra Phông Chữ:** Xác nhận toàn bộ bài viết hiển thị đúng phông **Quicksand Bold (700)** và tiêu đề **800**.
4. **Kiểm tra Hình Ảnh:** Ảnh đại diện và ảnh thân bài hiển thị rõ nét từ đường dẫn nội bộ `https://phache.com.vn/upload/news/...`.
5. **Cập nhật Nhật ký:** C11/C12 đánh dấu `[x]` vào Bảng Kanban mục II và lưu ID bài viết thực tế vào tài liệu này.

---

## 🛠️ VI. BIÊN BẢN KỸ THUẬT: QUY ĐỊNH & XỬ LÝ DỨT ĐIỂM TÓM TẮT BÀI VIẾT (SUMMARY / DESCRIPTION)

**Chuyên viên thực hiện:** C12 - Senior Full-Stack Web Developer & Technical SEO Lead  
**Thời gian xử lý:** 02/10/2026  
**Trạng thái:** ✅ Đã hoàn thành 100% - Đã triển khai Production & Xác thực Live  

---

### 1. Phân Tích Nguyên Nhân Gốc Rễ (Root Cause Analysis)
* **Thực trạng phát hiện:** Trên trang danh mục `https://phache.com.vn/tin-tuc.html`, 4 bài viết đăng tự động qua API bởi n8n (ID 677, 678, 679, 680) hiển thị phần tóm tắt là chữ giữ chỗ thô: `<div class="description">Tóm tắt bài viết</div>`. Trong khi đó, các bài người viết hiển thị 1-2 câu tóm tắt nội dung hấp dẫn, chứa từ khóa.
* **Nguyên nhân tại luồng n8n (WF-018):** Trong node JSON template của n8n, trường `description` đang bị gán cứng chuỗi placeholder `"Tóm tắt bài viết"` hoặc prompt LLM chưa sinh ra đoạn tóm tắt thực tế.
* **Nguyên nhân tại Backend API cũ (`api/publish-news.php`):** Code cũ chỉ kiểm tra `if (!empty($payload['description']))`. Do chuỗi `"Tóm tắt bài viết"` không rỗng, API đã chấp nhận và lưu thẳng vào cột `news_description` trong CSDL MySQL `news`.
* **Nguyên nhân tại Template (`template/news.php` dòng 1824):** Code giao diện chỉ gọi `limitCharsUnicode($n['news_description'], 150)` mà không có bộ lọc chống chuỗi rác placeholder.

---

### 2. Quy Định Chuẩn Cho API & Prompt n8n (Payload Contract)

#### A. Tên trường dữ liệu (Field Aliases)
Hệ thống API Backend hiện hỗ trợ đầy đủ các bí danh trường sau để n8n linh hoạt sử dụng:
* `description` (Khuyến nghị chuẩn)
* `summary`
* `excerpt`
* `short_description`
* `meta_description`
* `tom_tat` / `mo_ta`

#### B. Tiêu chuẩn nội dung tóm tắt
* **Độ dài vàng:** **130 – 160 ký tự**.
* **Cấu trúc:** Tóm tắt 1-2 câu súc tích nêu bật điểm giá trị nhất của bài viết, chứa từ khóa chính ngay 50 ký tự đầu.
* **Định dạng:** Văn bản thuần túy (Plain text UTF-8), tuyệt đối không chèn mã HTML, không chứa icon rác.
* **Quy tắc cấm kỵ cho C11:** **TUYỆT ĐỐI KHÔNG gửi chuỗi placeholder** như `"Tóm tắt bài viết"`, `"Mô tả"`, `"N/A"`, `"None"`.

#### C. Cấu hình Prompt gợi ý trong Node n8n LLM
```text
"description": "Viết 1 đoạn tóm tắt bài viết súc tích, hấp dẫn dài từ 130 đến 160 ký tự. Nêu bật giải pháp hoặc công thức chính trong bài, chứa từ khóa chính ngay đầu câu để kích thích người đọc bấm vào xem."
```

---

### 3. Cơ Chế Bảo Vệ Đa Tầng Đã Triển Khai (Multi-Layer Safeguards)

1. **Tầng 1 (Tại Backend Ingestion - `api/publish-news.php`):**
   * Hàm `pl_is_dummy_description()`: Nhận diện và loại bỏ tự động mọi chuỗi rác/placeholder (`Tóm tắt bài viết`, `Mô tả`, chuỗi ngắn < 20 ký tự).
   * Hàm `pl_generate_clean_excerpt()`: Khi `description` bị thiếu hoặc là chuỗi placeholder, API tự động trích xuất thông minh 155 ký tự từ thân bài viết HTML (đã loại bỏ sạch sẽ thẻ video, heading `<h2>/<h3>`, bảng cost, script/style, lấy đúng đoạn văn mở đầu tự nhiên).
2. **Tầng 2 (Tại Frontend Render - `template/news.php`):**
   * Tại dòng 1824, bổ sung lớp bảo vệ trực tiếp: Nếu `news_description` rỗng hoặc phát hiện chuỗi placeholder, giao diện web tự động trích xuất tóm tắt trực tiếp từ `news_content` để hiển thị mượt mà.
3. **Tầng 3 (Data Healing CSDL Live):**
   * Đã kích hoạt action `heal_descriptions` thông qua API xác thực Bearer token, vĩnh viễn sửa 4 bài viết cũ trên máy chủ:
     - **ID 677:** `Chiến lược xây dựng menu cà phê đột phá 2026 giúp quán đông khách nườm nượp: Định vị sản phẩm chủ lực, kỹ thuật decor ấn tượng và tối ưu cost giá vốn dưới 15%.`
     - **ID 678:** `Khám phá 5 công thức trà sữa đậm vị độc quyền hút khách mở quán 2026: Kỹ thuật ủ trà đậm đà, tỷ lệ phối sữa chuẩn vị và cách làm topping tươi giữ chân thực khách.`
     - **ID 679:** `Bí quyết pha chế 5 món trà sữa đậm vị hot trend 2026: Cốt trà đậm sâu không ngọt gắt, định lượng giá vốn chuẩn và quy trình vận hành tối ưu cho chủ quán.`
     - **ID 680:** `Cẩm nang mở quán cà phê đắt khách 2026: Bí quyết thiết kế menu tinh gọn, bảng tính cost chi tiết từng món và phương pháp gia tăng biên lợi nhuận bền vững.`

---
*Kế hoạch & Biên bản kỹ thuật đã được lưu trữ vĩnh viễn tại file mã nguồn: `task-log-danh-sach-bai-viet-website-phache.md` trên hệ thống máy chủ.*
