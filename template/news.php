<style type="text/css" media="screen">
@import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700;800&display=swap');

/* ==========================================================================
   CHUYÊN MỤC TIN TỨC: PHÔNG CHỮ MẶC ĐỊNH LÀ QUICKSAND BOLD
   ========================================================================== */
.news_page,
.news_page *,
#wrap-list-news,
#wrap-list-news *,
#list_news,
#list_news *,
#news_content,
#news_content * {
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
}

/* Áp dụng mặc định trọng số Bold (700) cho toàn bộ trang tin tức */
.news_page {
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.8;
    letter-spacing: 0.01em;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Nội dung bài viết tin tức: Phông chữ mặc định Quicksand Bold */
#news_content {
    font-size: 16.5px;
    color: #2c3e50;
    line-height: 1.85;
}

#news_content p,
#news_content li,
#news_content div:not(.pl-geo-head):not(.pl-qrb-badge):not(.pl-calculator-container):not(.pl-retention-video-wrapper),
#news_content span:not(.calc-pulse-icon):not(.pl-geo-icon),
#news_content td,
#news_content th,
.news_page p,
.news_page li,
.news_page a,
.news_page span:not(.calc-pulse-icon):not(.pl-geo-icon),
.news_page .description,
.news_page .des_course,
.news_page .des_success,
.news_page .intro,
.news_page label,
.news_page input,
.news_page textarea {
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
    font-weight: 700;
    line-height: 1.85;
}

/* Tiêu đề & Chữ nhấn mạnh: Quicksand Extra Bold (800) */
.news_page h1,
.news_page h2,
.news_page h3,
.news_page h4,
.news_page h5,
.news_page h6,
.news_page strong,
.news_page b,
.news_page .h1-title,
.news_page .course-main-title,
.news_page .sub-h2,
.news_page .h2-title,
.news_page .h3-relate,
.news_page .news_title,
.news_page .news_title a,
#news_content h1,
#news_content h2,
#news_content h3,
#news_content h4,
#news_content h5,
#news_content h6,
#news_content strong,
#news_content b {
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
    font-weight: 800 !important;
}

/* Danh sách tin tức (Archive List) & Header */
#wrap-list-news .h1-title {
    font-size: 26px;
    color: #1F3F1F;
    text-transform: uppercase;
    margin-bottom: 25px;
    border-bottom: 2px solid #2E7D32;
    padding-bottom: 12px;
    display: block !important;
    line-height: 1.4 !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
}

.news_page .news_item {
    margin-bottom: 30px;
}

.news_page .news_item .box-effect {
    border-radius: 14px;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    background: #fff;
    margin-bottom: 0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.news_page .news_item .box-effect:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(31,168,75,0.18);
}

.news_page .news_img {
    overflow: hidden;
    border-radius: 8px 8px 0 0;
}

.news_page .news_img img {
    width: 100%;
    height: auto;
    aspect-ratio: 16/10;
    object-fit: cover;
    transition: transform 0.3s ease;
    display: block;
}

.news_page .news_item .box-effect:hover .news_img img {
    transform: scale(1.03);
}

.news_page .news_info {
    margin-top: 14px !important;
    line-height: 1.5 !important;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

/* ==========================================================================
   KHẮC PHỤC TRIỆT ĐỂ: TIÊU ĐỀ XUỐNG DÒNG KHÔNG BỊ CHE KHUẤT
   ========================================================================== */
.news_page .news_info .news_title,
.news_page .news_title,
.news_item .news_info .news_title,
#list_news .news_info .news_title {
    height: auto !important;
    min-height: 52px !important;
    max-height: none !important;
    overflow: visible !important;
    margin: 0 0 10px 0 !important;
    padding: 0 !important;
    line-height: 1.45 !important;
}

.news_page .news_title a,
.news_item .news_title a,
#list_news .news_title a {
    font-size: 16px !important;
    font-weight: 800 !important;
    color: #1F3F1F !important;
    text-decoration: none !important;
    line-height: 1.42 !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    margin: 0 !important;
    padding: 0 !important;
    transition: color 0.2s ease;
    word-break: break-word;
    text-transform: uppercase !important;
}

.news_page .news_title a:hover,
.news_item .news_title a:hover,
#list_news .news_title a:hover {
    color: #2E7D32 !important;
}

.news_page .news_info .description,
.news_page .description,
.news_item .news_info .description,
#list_news .news_info .description {
    font-size: 14px !important;
    color: #4b5563 !important;
    line-height: 1.6 !important;
    margin: 0 !important;
    padding: 0 !important;
    height: auto !important;
    min-height: 68px !important;
    max-height: none !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 3 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}

/* Responsive mobile: Tự co giãn linh hoạt khi màn hình nhỏ */
@media (max-width: 767px) {
    #wrap-list-news .h1-title,
    .news_page .h1-title,
    .news_page .course-main-title {
        font-size: 20px !important;
        line-height: 1.4 !important;
        margin-bottom: 18px !important;
        padding-bottom: 8px !important;
    }
    .news_page .news_info .news_title,
    .news_page .news_title,
    .news_item .news_info .news_title,
    #list_news .news_info .news_title {
        min-height: auto !important;
        margin-bottom: 8px !important;
    }
    .news_page .news_title a,
    .news_item .news_title a,
    #list_news .news_title a {
        font-size: 15px !important;
        -webkit-line-clamp: 3 !important;
    }
    .news_page .news_info .description,
    .news_page .description,
    .news_item .news_info .description,
    #list_news .news_info .description {
        min-height: auto !important;
        font-size: 13.5px !important;
        -webkit-line-clamp: 3 !important;
    }
}

/* Khối tin tức liên quan */
#list_news_other li a {
    font-size: 16px;
    font-weight: 700;
    color: #2e7d32;
    transition: color 0.2s ease;
}
#list_news_other li a:hover {
    color: #1b5e20;
    text-decoration: underline;
}

/* Khoảng cách đoạn văn bản để đọc mượt mà */
#news_content p {
    margin-bottom: 18px;
}
#news_content ul,
#news_content ol {
    margin-bottom: 20px;
    padding-left: 22px;
}
#news_content li {
    margin-bottom: 8px;
}

.col-centered {
    float: none;
    margin: 0 auto;
}
.block.img-responsive{
position: relative;
}
.block.img-responsive p{
position: absolute;
    bottom: 0;
    background: #e5e6eb;
    z-index: 99;
    width: 100%;
    text-align: center;
}
.carousel-control { 
    width: 8%;
    width: 0px;
}
.carousel-control.left,
.carousel-control.right { 
    margin-right: 40px;
    margin-left: 32px; 
    background-image: none;
    opacity: 1;
}
.carousel-control > a > span {
    color: white;
      font-size: 29px !important;
}

.carousel-col { 
    position: relative; 
    min-height: 1px; 
    padding: 5px; 
    float: left;
 }

 .active > div { display:none; }
 .active > div:first-child { display:block; }

/*xs*/
@media (max-width: 767px) {
  .carousel-inner .active.left { left: -50%; }
  .carousel-inner .active.right { left: 50%; }
    .carousel-inner .next        { left:  50%; }
    .carousel-inner .prev            { left: -50%; }
  .carousel-col                { width: 50% }
    .active > div:first-child + div { display:block; }
}

/*sm*/
@media (min-width: 768px) and (max-width: 991px) {
  .carousel-inner .active.left { left: -50%; }
  .carousel-inner .active.right { left: 50%; }
    .carousel-inner .next        { left:  50%; }
    .carousel-inner .prev            { left: -50%; }
  .carousel-col                { width: 50%; }
    .active > div:first-child + div { display:block; }
}

/*md*/
@media (min-width: 992px) and (max-width: 1199px) {
  .carousel-inner .active.left { left: -33%; }
  .carousel-inner .active.right { left: 33%; }
    .carousel-inner .next        { left:  33%; }
    .carousel-inner .prev            { left: -33%; }
  .carousel-col                { width: 33%; }
    .active > div:first-child + div { display:block; }
  .active > div:first-child + div + div { display:block; }
}

/*lg*/
@media (min-width: 1200px) {
  .carousel-inner .active.left { left: -33%; }
  .carousel-inner .active.right { left: 33%; }
    .carousel-inner .next        { left:  33%; }
    .carousel-inner .prev            { left: -33%; }
  .carousel-col                { width: 33%; }
    .active > div:first-child + div { display:block; }
  .active > div:first-child + div + div { display:block; }
}

.block {
 width: 100%;
    height: auto;
}

.red {background: red;}

.blue {background: blue;}

.green {background: green;}

.yellow {background: yellow;}

.course-main-title {
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: clamp(20px, 3.5vw, 28px);
    font-weight: 800;
    color: #1F3F1F;
    line-height: 1.35;
    margin-top: 15px;
    margin-bottom: 25px;
    text-transform: uppercase;
    border-bottom: 2px solid #2E7D32;
    padding-bottom: 12px;
    word-break: break-word;
}
.sub-h2 {
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: clamp(18px, 2.5vw, 22px);
    font-weight: 700;
    color: #2E7D32;
    margin-top: 25px;
    margin-bottom: 15px;
}

/* Quick Recipe Box Component (Glassmorphism & High-end Bar Feel) */
.pl-quick-recipe-box {
    background: linear-gradient(135deg, rgba(240, 249, 244, 0.95), rgba(255, 255, 255, 0.98));
    border: 2px solid #2E7D32;
    border-radius: 12px;
    padding: 22px;
    margin: 25px 0 30px 0;
    box-shadow: 0 4px 18px rgba(46, 125, 50, 0.1);
}
.pl-qrb-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    border-bottom: 1px dashed #A5D6A7;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.pl-qrb-badge {
    background: #2E7D32;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.pl-qrb-std {
    color: #2E7D32;
    font-size: 13px;
    font-weight: 700;
}
.pl-qrb-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}
@media (max-width: 767px) {
    .pl-qrb-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}
.pl-qrb-item {
    background: #fff;
    border: 1px solid #E0E0E0;
    border-radius: 8px;
    padding: 12px 10px;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}
.pl-qrb-item.highlight {
    background: #E8F5E9;
    border-color: #81C784;
}
.pl-qrb-icon {
    font-size: 22px;
    margin-bottom: 4px;
}
.pl-qrb-label {
    font-size: 12px;
    color: #555;
    margin-bottom: 4px;
    display: block;
}
.pl-qrb-val {
    font-size: 15px;
    color: #1F3F1F;
    font-weight: 800;
    display: block;
}
.pl-qrb-item.highlight .pl-qrb-val {
    color: #2E7D32;
}
.pl-qrb-secret {
    background: #FFFDE7;
    border-left: 4px solid #FBC02D;
    padding: 12px 16px;
    border-radius: 6px;
}
.pl-qrb-secret-badge {
    font-size: 12px;
    font-weight: 800;
    color: #F57F17;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.pl-qrb-secret p {
    font-size: 14px;
    color: #333;
    line-height: 1.5;
    margin: 0;
}

/* HTML Table for Ingredients */
.pl-recipe-table-wrap {
    margin: 25px 0 30px 0;
}
.pl-table-title {
    font-family: 'Quicksand', sans-serif;
    font-size: 18px;
    font-weight: 800;
    color: #2E7D32;
    margin-bottom: 12px;
}
.pl-recipe-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 10px;
}
.pl-recipe-table th {
    background: #2E7D32;
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    padding: 12px 14px;
    text-align: left;
}
.pl-recipe-table td {
    padding: 12px 14px;
    font-size: 14px;
    border-bottom: 1px solid #ECEFF1;
    color: #333;
    vertical-align: middle;
}
.pl-recipe-table tbody tr:nth-child(even) {
    background: #F9FBE7;
}
.pl-recipe-table tbody tr:hover {
    background: #F1F8E9;
}
.pl-recipe-table td strong {
    color: #2E7D32;
}
.pl-qty-badge {
    display: inline-block;
    background: #E8F5E9;
    color: #1B5E20;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 13px;
    border: 1px solid #C8E6C9;
}
.pl-table-note {
    font-size: 13px;
    color: #666;
    font-style: italic;
    margin-top: 6px;
}

/* Internal Link Silo Box */
.pl-silo-box {
    background: #F4FBF7;
    border: 2px solid #81C784;
    border-radius: 12px;
    padding: 24px 20px;
    margin: 35px 0 25px 0;
    box-shadow: 0 4px 15px rgba(46, 125, 50, 0.08);
}
.pl-silo-head {
    text-align: center;
    margin-bottom: 20px;
}
.pl-silo-badge {
    background: #E8F5E9;
    color: #2E7D32;
    border: 1px solid #A5D6A7;
    font-size: 12px;
    font-weight: 800;
    padding: 4px 14px;
    border-radius: 20px;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 10px;
}
.pl-silo-head h3 {
    font-family: 'Quicksand', sans-serif;
    font-size: clamp(18px, 3vw, 22px);
    font-weight: 800;
    color: #1F3F1F;
    margin: 0 0 8px 0;
}
.pl-silo-head p {
    font-size: 14px;
    color: #555;
    max-width: 750px;
    margin: 0 auto;
    line-height: 1.5;
}
.pl-silo-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media (max-width: 767px) {
    .pl-silo-grid {
        grid-template-columns: 1fr;
    }
}
.pl-silo-card {
    background: #fff;
    border: 1px solid #C8E6C9;
    border-radius: 10px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.2s, box-shadow 0.2s;
}
.pl-silo-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(46, 125, 50, 0.12);
}
.pl-silo-card.highlight {
    border-color: #2E7D32;
    background: #FAFCF9;
}
.pl-silo-card-tag {
    font-size: 11px;
    font-weight: 800;
    color: #2E7D32;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}
.pl-silo-card-tag.hot {
    color: #D84315;
}
.pl-silo-card h4 {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 10px 0;
    line-height: 1.35;
}
.pl-silo-card h4 a {
    color: #1F3F1F;
    text-decoration: none;
}
.pl-silo-card h4 a:hover {
    color: #2E7D32;
}
.pl-silo-card p {
    font-size: 13px;
    color: #666;
    line-height: 1.45;
    margin: 0 0 16px 0;
    flex-grow: 1;
}
.pl-silo-link-btn {
    display: inline-block;
    background: #2E7D32;
    color: #fff !important;
    text-decoration: none !important;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 18px;
    border-radius: 6px;
    text-align: center;
    transition: background 0.2s;
}
.pl-silo-link-btn:hover {
    background: #1B5E20;
}
.pl-silo-card.highlight .pl-silo-link-btn {
    background: #D84315;
}
.pl-silo-card.highlight .pl-silo-link-btn:hover {
    background: #BF360C;
}

/* ==================== DX-05: GEO AI SEARCH KEY TAKEAWAYS BOX ==================== */
.pl-geo-takeaways-box {
    background: linear-gradient(135deg, rgba(240, 249, 245, 0.95) 0%, rgba(255, 255, 255, 0.98) 100%);
    border: 1.5px solid #2E7D32;
    border-left: 6px solid #2E7D32;
    border-radius: 12px;
    padding: 18px 22px;
    margin: 20px 0 25px 0;
    box-shadow: 0 4px 16px rgba(46, 125, 50, 0.08);
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.pl-geo-head {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px dashed rgba(46, 125, 50, 0.3);
}
.pl-geo-icon {
    font-size: 20px;
    line-height: 1;
}
.pl-geo-title {
    font-size: 15px;
    font-weight: 800;
    color: #1F3F1F;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.pl-geo-list {
    margin: 0;
    padding-left: 20px;
    color: #2D3748;
    font-size: 14.5px;
    line-height: 1.65;
}
.pl-geo-list li {
    margin-bottom: 8px;
}
.pl-geo-list li:last-child {
    margin-bottom: 0;
}
.pl-geo-list strong {
    color: #1F3F1F;
}

/* ==================== DX-04: MID-ARTICLE LEAD CAPTURE CARD ==================== */
.pl-mid-lead-card {
    background: linear-gradient(135deg, #FFFDF7 0%, #FFF9E6 100%);
    border: 2px solid #FFB300;
    border-radius: 16px;
    padding: 24px 22px;
    margin: 32px auto;
    max-width: 780px;
    box-shadow: 0 8px 24px rgba(255, 179, 0, 0.16);
    text-align: center;
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    box-sizing: border-box;
}
.pl-mid-lead-badge {
    display: inline-block;
    background: #FFB300;
    color: #1F3F1F;
    font-size: 12px;
    font-weight: 800;
    padding: 4px 14px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}
.pl-mid-lead-title {
    font-size: clamp(18px, 2.8vw, 22px);
    font-weight: 800;
    color: #B78103;
    margin: 0 0 8px 0;
    line-height: 1.35;
}
.pl-mid-lead-desc {
    font-size: 14px;
    color: #5D4037;
    margin: 0 auto 16px auto;
    max-width: 620px;
    line-height: 1.55;
}
.pl-mid-lead-form {
    max-width: 540px;
    margin: 0 auto;
}
.pl-mid-input-wrapper {
    display: flex;
    gap: 8px;
    align-items: stretch;
}
.pl-mid-input {
    flex: 1 1 65%;
    padding: 12px 14px;
    border: 1.5px solid #FFB300;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14.5px;
    font-weight: 600;
    color: #1F3F1F;
    background: #ffffff;
    box-sizing: border-box;
    outline: none;
    transition: all 0.2s ease;
}
.pl-mid-input:focus {
    border-color: #E65100;
    box-shadow: 0 0 0 3px rgba(230, 81, 0, 0.15);
}
.pl-mid-btn {
    flex: 1 1 35%;
    padding: 12px 16px;
    background: linear-gradient(135deg, #FF6F00 0%, #E65100 100%);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(230, 81, 0, 0.35);
    transition: all 0.2s ease;
    white-space: nowrap;
}
.pl-mid-btn:hover {
    background: linear-gradient(135deg, #E65100 0%, #BF360C 100%);
    transform: translateY(-1px);
}
.pl-mid-success {
    background: #E8F5E9;
    color: #1B5E20;
    border: 1.5px solid #81C784;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 700;
    margin-top: 14px;
    max-width: 540px;
    margin-left: auto;
    margin-right: auto;
}
.pl-mid-footer {
    font-size: 12px;
    color: #8D6E63;
    margin-top: 10px;
    font-style: italic;
}

@media (max-width: 600px) {
    .pl-mid-input-wrapper {
        flex-direction: column;
    }
    .pl-mid-btn {
        width: 100%;
    }
}

/* ==================== DX-02: IN-ARTICLE 1-TOUCH ZALO CARD ==================== */
.pl-inarticle-zalo-card {
    background: linear-gradient(135deg, #F0F7FF 0%, #E3F2FD 100%);
    border: 1.5px solid #90CAF9;
    border-radius: 16px;
    padding: 22px 24px;
    margin: 30px auto;
    max-width: 820px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    box-shadow: 0 6px 20px rgba(0, 104, 255, 0.08);
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    box-sizing: border-box;
}
.pl-izalo-left {
    flex: 1 1 340px;
}
.pl-izalo-badge {
    display: inline-block;
    background: rgba(0, 104, 255, 0.12);
    color: #0068FF;
    font-size: 12px;
    font-weight: 800;
    padding: 3px 12px;
    border-radius: 16px;
    margin-bottom: 8px;
    letter-spacing: 0.3px;
}
.pl-izalo-title {
    font-size: 18px;
    font-weight: 800;
    color: #0D47A1;
    margin: 0 0 6px 0;
    line-height: 1.35;
}
.pl-izalo-desc {
    font-size: 13.5px;
    color: #37474F;
    margin: 0;
    line-height: 1.5;
}
.pl-izalo-right {
    flex: 0 0 auto;
    text-align: center;
}
.pl-izalo-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #0068FF 0%, #0052cc 100%);
    color: #ffffff !important;
    text-decoration: none !important;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14.5px;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(0, 104, 255, 0.35);
    transition: all 0.2s ease;
    animation: plPulseBarZalo 2.6s infinite ease-in-out;
}
.pl-izalo-btn:hover {
    background: linear-gradient(135deg, #0052cc 0%, #003da6 100%);
    transform: translateY(-1px);
}
.pl-izalo-ico {
    font-size: 18px;
    line-height: 1;
}
.pl-izalo-hotline {
    font-size: 12px;
    color: #546E7A;
    margin-top: 6px;
    font-weight: 600;
}
.pl-izalo-hotline a {
    color: #D32F2F;
    font-weight: 800;
    text-decoration: none;
}

@media (max-width: 600px) {
    .pl-inarticle-zalo-card {
        flex-direction: column;
        align-items: stretch;
        padding: 18px 16px;
        gap: 14px;
    }
    .pl-izalo-right {
        text-align: stretch;
    }
    .pl-izalo-btn {
        width: 100%;
        box-sizing: border-box;
    }
}

/* ==================== DX-03: VIDEO SHORTS & PRACTICE FACADE ==================== */
.pl-video-wrapper {
    margin: 30px auto;
    max-width: 820px;
}
.pl-video-facade-container {
    position: relative;
    width: 100%;
    margin: 0 auto;
    border-radius: 16px;
    overflow: hidden;
    background: #000000;
    box-shadow: 0 10px 30px rgba(0,0,0,0.22);
}
.pl-video-facade-16-9 {
    max-width: 760px;
    aspect-ratio: 16 / 9;
}
.pl-video-facade-shorts {
    max-width: 360px;
    aspect-ratio: 9 / 16;
}
.pl-video-facade {
    position: relative;
    width: 100%;
    height: 100%;
    cursor: pointer;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.pl-video-poster {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.pl-video-facade:hover .pl-video-poster {
    transform: scale(1.03);
}
.pl-video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.28);
    transition: background 0.2s ease;
}
.pl-video-facade:hover .pl-video-overlay {
    background: rgba(0, 0, 0, 0.15);
}
.pl-video-play-btn {
    position: relative;
    z-index: 2;
    transition: transform 0.2s ease;
    filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4));
}
.pl-video-facade:hover .pl-video-play-btn {
    transform: scale(1.12);
}
.pl-video-caption {
    position: absolute;
    bottom: 12px;
    left: 12px;
    right: 12px;
    z-index: 2;
    background: rgba(0, 0, 0, 0.72);
    color: #ffffff;
    font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 13px;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 8px;
    text-align: center;
    backdrop-filter: blur(4px);
}
.pl-video-responsive iframe {
    width: 100%;
    height: 100%;
    border: none;
}
</style>

<div class="news_page">

    <?php 

   // print_r($news);

//      $listnews_img_slide = json_decode($news['news_img_slide'],TRUE);
//   print_r($listnews_img_slide);
// //  print_r($listnews_img_slide);
//  foreach($listnews_img_slide as $keylistnews_img_slide => $valuelistnews_img_slide) {
//                    $ad_code .= '<div class="item ';
//                    if($keylistnews_img_slide == 0){
//                     $ad_code .= 'active'; 
//                    }else{
//                     $ad_code .= ' '; 
//                    }
//                    $ad_code .=' ">
//                             <div class="carousel-col">
//                                 <div class="block img-responsive"><img src="'. NEWS_URL.$valuelistnews_img_slide.'" alt=""></div>
//                             </div>
//                         </div>';
//  } 
//  print_r($ad_code);



    function prefix_insert_post_ads( $content,$newag,$atitle ) {
        $ad_code = '<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-12 col-centered">

            <div id="carousel" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="8500">
                <div class="carousel-inner">';
                    $listnews_img_slide = json_decode($newag,TRUE);
                    //$abc = 0;
                    foreach($listnews_img_slide as $keylistnews_img_slide => $valuelistnews_img_slide) {
                        $ad_code .= '<div class="item ';
                   if($keylistnews_img_slide == 0){
                    $ad_code .= 'active'; 
                   }else{
                    $ad_code .= ' '; 
                   }
                   $ad_code .=' ">
                             <div class="carousel-col">
                                <div class="block img-responsive"><img src="'. NEWS_URL.$valuelistnews_img_slide['image'].'" alt="'.$valuelistnews_img_slide['title'].'"><p>'.$valuelistnews_img_slide['title'].'</p></div>
                                
                            </div>
                        </div>';
                    } 
                $ad_code .= '                   
                </div>

                <!-- Controls -->
                <div class="left carousel-control">
                    <a href="#carousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left fa fa-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                </div>
                <div class="right carousel-control">
                    <a href="#carousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right fa fa-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>';
//print_r($ad_code);
        return prefix_insert_after_paragraph( $ad_code, 5, $content );
    //return $content;
    }
    function prefix_insert_after_paragraph( $insertion, $paragraph_id, $content ) {
        $closing_p = '</p>';
        $paragraphs = explode( $closing_p, $content );
        foreach ($paragraphs as $index => $paragraph) {
     
            if ( trim( $paragraph ) ) {
                $paragraphs[$index] .= $closing_p;
            }
     
            if ( $paragraph_id == $index + 1 ) {
                $paragraphs[$index] .= $insertion;
            }
        }
        return implode( '', $paragraphs );
    }

    if (!function_exists('geo_generate_key_takeaways_box')) {
        function geo_generate_key_takeaways_box($title, $news = array()) {
            $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $page_id = isset($news['page_id']) ? (int)$news['page_id'] : 0;
            $is_business = (strpos($uri, '/mo-quan') !== false) || ($page_id == 24);
            $is_course = (strpos($uri, '/cac-khoa-hoc-day-pha-che') !== false) || ($page_id == 22);

            if ($is_business) {
                $takeaways = array(
                    '<strong>Kế hoạch dòng tiền:</strong> Quản trị chi phí cốt lõi gồm COGS (30-32%), mặt bằng (10-15%) và nhân sự (15-20%) để đảm bảo an toàn tài chính.',
                    '<strong>Thời gian hoàn vốn:</strong> Các mô hình quán F&B tinh gọn thường đạt điểm hòa vốn sau <strong>3 - 6 tháng</strong> vận hành bài bản.',
                    '<strong>Khác biệt menu:</strong> Định hình 2-3 món chủ lực (Signature drinks) độc bản giúp tăng tỷ lệ khách quen quay lại trên 65%.',
                    '<strong>Hỗ trợ trọn gói:</strong> Tải miễn phí file Excel kế hoạch dòng tiền và nhận tư vấn mặt bằng 1-1 từ giảng viên Passion Link.'
                );
            } elseif ($is_course) {
                $takeaways = array(
                    '<strong>Phương pháp đào tạo:</strong> <strong>90% thời lượng thực hành</strong> quầy bar thực tế, cầm tay chỉ việc 1 kèm 1 đến khi thành thạo.',
                    '<strong>Bảo hành tay nghề:</strong> Học viên được quyền quay lại ôn tập, cập nhật công thức món mới <strong>trọn đời hoàn toàn miễn phí</strong>.',
                    '<strong>Chuyển giao mở quán:</strong> Hướng dẫn trọn gói thiết kế quầy bar, chọn mua máy móc chính hãng và tối ưu chi phí nguyên vật liệu (Cost < 32%).',
                    '<strong>Đặc quyền hệ sinh thái:</strong> Hưởng chính sách giá sỉ nguyên liệu chuẩn ATVSTP độc quyền từ CÔNG TY TNHH VUA AN TOÀN.'
                );
            } else {
                $takeaways = array(
                    '<strong>Định lượng chuẩn vị:</strong> Công thức được chuẩn hóa theo tiêu chuẩn quầy bar Passion Link, đảm bảo đồng nhất chất lượng 100 ly như 1.',
                    '<strong>Kiểm soát chi phí (Cost):</strong> Giá vốn nguyên vật liệu tối ưu ở mức <strong>6.800đ - 7.500đ/ly</strong>, giúp đạt biên lợi nhuận gộp <strong>68% - 72%</strong>.',
                    '<strong>Kỹ thuật chuyên sâu:</strong> Hướng dẫn chi tiết nhiệt độ ủ trà, thời gian hãm và kỹ thuật phối vị để giữ trọn tầng hương tự nhiên.',
                    '<strong>Tư vấn mở quán:</strong> Học viên có thể liên hệ trực tiếp đội ngũ chuyên gia để nhận tư vấn menu và danh mục trang thiết bị chuyên dụng.'
                );
            }

            $html = '<div class="pl-geo-takeaways-box">
                <div class="pl-geo-head">
                    <span class="pl-geo-icon">📌</span>
                    <span class="pl-geo-title">TÓM TẮT NHANH CHO CHỦ QUÁN (KEY TAKEAWAYS)</span>
                </div>
                <ul class="pl-geo-list">';
            foreach ($takeaways as $t) {
                $html .= '<li>' . $t . '</li>';
            }
            $html .= '</ul>
            </div>';

            return $html;
        }
    }

    if (!function_exists('seo_normalize_article_headings')) {
        function seo_normalize_article_headings($content, $title, $news = array()) {
            if (empty($content)) {
                $res = '<h1 class="h1-title course-main-title">' . htmlspecialchars($title) . '</h1>';
                if (function_exists('geo_generate_key_takeaways_box')) {
                    $res .= "\n" . geo_generate_key_takeaways_box($title, $news);
                }
                return $res;
            }
            
            // Check if content already contains an <h1> tag
            if (preg_match('/<h1\b[^>]*>(.*?)<\/h1>/is', $content, $m)) {
                $h1_inner_text = trim(strip_tags(html_entity_decode($m[1], ENT_QUOTES, 'UTF-8')));
                if ($h1_inner_text === '') {
                    $content = preg_replace('/<h1\b[^>]*>.*?<\/h1>/is', '<h1 class="h1-title course-main-title">' . htmlspecialchars($title) . '</h1>', $content, 1);
                } else {
                    $count = 0;
                    $content = preg_replace_callback('/<h1\b([^>]*)>(.*?)<\/h1>/is', function($match) use (&$count, $title) {
                        $count++;
                        if ($count === 1) {
                            $attrs = $match[1];
                            $attrs = preg_replace('/\bclass=(["\'])(.*?)\b(d-none|hidden)\b(.*?)\1/i', 'class=$1$2$4$1', $attrs);
                            $attrs = preg_replace('/style=(["\'])(.*?)\bdisplay\s*:\s*none\b;?(.*?)\1/i', 'style=$1$2$3$1', $attrs);
                            $h1_val = !empty($title) ? htmlspecialchars($title) : $match[2];
                            return '<h1' . $attrs . '>' . $h1_val . '</h1>';
                        } else {
                            return '<h2 class="sub-h2"' . $match[1] . '>' . $match[2] . '</h2>';
                        }
                    }, $content);
                }
            } else {
                $h1_tag = '<h1 class="h1-title course-main-title">' . htmlspecialchars($title) . '</h1>' . "\n";
                $content = $h1_tag . $content;
            }

            // Inject Key Takeaways box right below H1 (DX-05)
            if (strpos($content, 'class="pl-geo-takeaways-box"') === false && function_exists('geo_generate_key_takeaways_box')) {
                $takeaways = geo_generate_key_takeaways_box($title, $news);
                if (preg_match('/<\/h1>/i', $content, $m_h1, PREG_OFFSET_CAPTURE)) {
                    $pos = $m_h1[0][1] + strlen($m_h1[0][0]);
                    $content = substr_replace($content, "\n" . $takeaways . "\n", $pos, 0);
                } else {
                    $content = $takeaways . "\n" . $content;
                }
            }
            
            return $content;
        }
    }


    if (!function_exists('seo_enrich_recipe_article_content')) {
        function seo_enrich_recipe_article_content($content, $title, $news) {
            $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $page_id = isset($news['page_id']) ? (int)$news['page_id'] : 0;
            $news_id = isset($news['news_id']) ? (int)$news['news_id'] : 0;
            
            $is_recipe = ($page_id === 22) || (strpos($uri, '/day-pha-che-tra-sua-ngon/') !== false);
            
            if (!$is_recipe) {
                return $content;
            }
            
            // 1. Quick Recipe Box Component
            $quick_box = '
            <div class="pl-quick-recipe-box">
                <div class="pl-qrb-top">
                    <span class="pl-qrb-badge">⚡ THÔNG SỐ CÔNG THỨC CHUẨN MENU</span>
                    <span class="pl-qrb-std">Chuẩn Định Lượng Barista Passion Link</span>
                </div>
                <div class="pl-qrb-grid">
                    <div class="pl-qrb-item">
                        <div class="pl-qrb-icon">⏱️</div>
                        <div class="pl-qrb-meta">
                            <span class="pl-qrb-label">Thời gian làm:</span>
                            <strong class="pl-qrb-val">5 - 7 phút</strong>
                        </div>
                    </div>
                    <div class="pl-qrb-item">
                        <div class="pl-qrb-icon">💰</div>
                        <div class="pl-qrb-meta">
                            <span class="pl-qrb-label">Giá vốn (Cost):</span>
                            <strong class="pl-qrb-val">6.800đ - 7.500đ</strong>
                        </div>
                    </div>
                    <div class="pl-qrb-item">
                        <div class="pl-qrb-icon">🏷️</div>
                        <div class="pl-qrb-meta">
                            <span class="pl-qrb-label">Giá bán menu:</span>
                            <strong class="pl-qrb-val">25.000đ - 32.000đ</strong>
                        </div>
                    </div>
                    <div class="pl-qrb-item highlight">
                        <div class="pl-qrb-icon">📈</div>
                        <div class="pl-qrb-meta">
                            <span class="pl-qrb-label">Biên lợi nhuận:</span>
                            <strong class="pl-qrb-val">68% - 72%</strong>
                        </div>
                    </div>
                </div>
                <div class="pl-qrb-secret">
                    <div class="pl-qrb-secret-badge">🍃 BÍ QUYẾT Ủ TRÀ ĐỘC QUYỀN PASSION LINK</div>
                    <p>Cân chính xác tỷ lệ <strong>1g trà : 30ml nước sôi</strong> ở nhiệt độ chuẩn <strong>85°C - 90°C</strong>. Ủ kín trong <strong>10 - 15 phút</strong>, lọc cốt trà và sốc nhiệt lạnh bằng đá viên ngay lập tức để khóa trọn hương tinh dầu hoa cỏ, loại bỏ hoàn toàn vị chát gắt của tanin.</p>
                </div>
            </div>';

            // 2. Curated & Dynamic Ingredients Table Database
            $tables_db = array(
                58 => array(
                    array('Cốt trà đào hảo hạng', '120ml', 'Ủ 5g trà túi lọc/hồng trà với 120ml nước sôi 90°C trong 10 phút, lọc bã và sốc nhiệt lạnh.'),
                    array('Siro đào (Monin/Teisseire)', '25ml', 'Tạo hương thơm đào chín tự nhiên, đậm đà đặc trưng.'),
                    array('Nước ngâm đào lon Hosen', '15ml', 'Tăng độ ngọt thanh và vị chua nhẹ cân bằng cho ly trà.'),
                    array('Nước đường mía tự nấu', '15ml - 20ml', 'Cân chỉnh theo khẩu vị khách (ngọt vừa / ít ngọt).'),
                    array('Đào ngâm giòn Hosen', '40g', 'Cắt lát 3-4 miếng đào dày giòn sần sật để làm topping.'),
                    array('Đá viên tinh khiết', '200g', 'Đá bi tinh khiết làm lạnh sâu giữ vị trà bền lâu.')
                ),
                256 => array(
                    array('Cốt hồng trà / trà đen số 9', '100ml', 'Ủ 5g trà đen với 100ml nước sôi 95°C trong 12 phút, sốc nhiệt lạnh.'),
                    array('Bột béo thực vật Non-dairy', '28g - 30g', 'Dùng bột sữa cao cấp Vua An Toàn giúp ngậy béo tự nhiên mà không át mùi trà.'),
                    array('Sữa đặc có đường', '15ml', 'Tạo độ sánh dẻo và ngọt hậu sâu lắng.'),
                    array('Nước đường cát nấu', '20ml', 'Tỷ lệ nấu chuẩn 2 đường : 1 nước.'),
                    array('Trân châu đen caramel', '50g', 'Nấu dẻo dai 12 tiếng không bị sượng cứng.'),
                    array('Đá viên bi', '180g', 'Lắc đều trong bình shaker 15 nhịp.')
                ),
                254 => array(
                    array('Cốt trà Oolong nướng / Oolong xanh', '110ml', 'Ủ 5g trà Oolong với 110ml nước sôi 90°C trong 10 phút, hương khói thơm ngậy.'),
                    array('Bột sữa pha chế chuyên dụng', '28g', 'Hòa tan trực tiếp vào cốt trà nóng để giải phóng tối đa chất béo thực vật.'),
                    array('Sữa tươi thanh trùng không đường', '20ml', 'Tăng độ ngậy thanh và giúp hậu vị kéo dài.'),
                    array('Nước đường mía', '20ml', 'Cân bằng độ ngọt vừa phải.'),
                    array('Trân châu trắng giòn 3Q', '45g', 'Topping dai giòn sần sật cực kỳ bắt vị.'),
                    array('Đá viên tinh khiết', '180g', 'Lắc đều tay tạo bọt mịn.')
                ),
                255 => array(
                    array('Cốt lục trà nhài (trà xanh ướp hoa)', '110ml', 'Ủ 5g lục trà lài với 110ml nước 80°C - 85°C trong 7-8 phút (không ủ nước sôi 100°C tránh cháy trà).'),
                    array('Bột kem béo cao cấp', '26g', 'Tạo lớp nền béo ngậy hài hòa với vị thanh mát của trà xanh.'),
                    array('Sữa đặc', '15ml', 'Tạo độ sánh mượt.'),
                    array('Nước đường cát', '18ml', 'Gia giảm theo khẩu vị.'),
                    array('Thạch phô mai tươi / Trân châu trắng', '45g', 'Topping thơm béo giòn ngậy.'),
                    array('Đá viên', '180g', 'Lắc đều 12 nhịp.')
                ),
                258 => array(
                    array('Cốt trà Bá Tước Earl Grey (tinh dầu Bergamot)', '110ml', 'Ủ 5g trà Earl Grey với 110ml nước sôi 92°C trong 10 phút.'),
                    array('Bột sữa pha chế Vua An Toàn', '28g', 'Hòa tan đều khi cốt trà còn nóng ấm.'),
                    array('Sữa tươi nguyên kem', '20ml', 'Tăng độ mịn màng cho kết cấu trà sữa.'),
                    array('Nước đường', '18ml', 'Làm bật nốt hương cam Bergamot quý tộc.'),
                    array('Thạch củ năng cẩm thạch', '40g', 'Giòn ngọt tự nhiên.'),
                    array('Đá viên', '180g', 'Lắc lạnh sâu.')
                ),
                257 => array(
                    array('Cốt trà thảo mộc hoa cúc kỷ tử', '120ml', 'Hãm 6g thảo mộc với 120ml nước sôi 90°C trong 12 phút.'),
                    array('Sữa hạt yến mạch / Hạnh nhân', '60ml', 'Cung cấp dinh dưỡng thực vật lành mạnh, ít calo.'),
                    array('Mật ong nguyên chất hoặc đường phèn', '20ml', 'Tạo vị ngọt thanh tao, tốt cho sức khỏe.'),
                    array('Táo đỏ thái lát & Hạt chia', '30g', 'Topping thảo dược bồi bổ cơ thể.'),
                    array('Đá viên bi', '150g', 'Khuấy đều và thưởng thức.')
                ),
                146 => array(
                    array('Whipping cream chuyên dụng', '100ml', 'Để thật lạnh ở ngăn mát 4°C trước khi đánh bông.'),
                    array('Topping cream Base', '100ml', 'Tạo độ đứng foam và chống tách nước khi phủ lên trà sữa.'),
                    array('Sữa tươi không đường', '50ml', 'Điều chỉnh độ lỏng mượt của lớp kem.'),
                    array('Bột mặn cheese cao cấp', '15g', 'Tạo vị béo ngậy mặn mà đặc trưng cuốn hút.'),
                    array('Muối biển tinh khiết', '1.5g', 'Cân bằng vị giác làm kem không bị ngấy.')
                ),
                147 => array(
                    array('Cốt khoai lang tím tươi hấp nghiền dẻo', '40g', 'Hấp chín khoai lang tím và miết mịn tạo màu tím tự nhiên.'),
                    array('Bột khoai lang tím cao cấp', '15g', 'Tăng cường hương thơm nồng nàn và màu sắc quyến rũ.'),
                    array('Cốt hồng trà thơm', '80ml', 'Ủ nóng 90°C trong 10 phút.'),
                    array('Bột béo thực vật', '25g', 'Hòa tan cùng cốt trà.'),
                    array('Nước đường', '15ml', 'Cân chỉnh độ ngọt thanh.'),
                    array('Đá viên', '180g', 'Lắc đều tay.')
                ),
                148 => array(
                    array('Cốt hồng trà đặc sản', '90ml', 'Ủ trà đen đậm đà làm nền hương vị.'),
                    array('Bột Cacao nguyên chất / Sốt Chocolate Hershey', '20g', 'Khuấy tan cùng 30ml nước sôi để bung trọn hương cacao.'),
                    array('Bột béo pha chế', '28g', 'Tạo độ béo ngậy sánh mịn.'),
                    array('Sữa đặc có đường', '20ml', 'Tạo vị ngọt đậm đà phong cách trà sữa socola.'),
                    array('Đá viên', '180g', 'Lắc đều 15 nhịp.')
                ),
                149 => array(
                    array('Cốt trà Thái xanh Chatramue', '100ml', 'Hãm 10g trà với 200ml nước sôi 90°C trong 10 phút, lọc kỹ qua túi vải.'),
                    array('Bột béo thực vật', '25g', 'Tạo màu xanh ngọc bích bắt mắt.'),
                    array('Sữa đặc có đường', '25ml', 'Chuẩn gu trà sữa Thái ngọt béo.'),
                    array('Sữa tươi thanh trùng', '20ml', 'Làm dịu vị ngọt gắt.'),
                    array('Thạch trà thái xanh dẻo', '40g', 'Topping mát lạnh sảng khoái.'),
                    array('Đá viên', '180g', 'Lắc đều tay.')
                ),
                57 => array(
                    array('Nước cốt chanh tươi nguyên chất', '30ml', 'Vắt chanh tươi bỏ hạt để không bị đắng.'),
                    array('Vỏ chanh xanh bào nhuyễn', '1g', 'Bào lớp vỏ xanh chứa tinh dầu thơm lừng.'),
                    array('Sữa đặc có đường', '45ml', 'Tạo độ sánh dẻo và béo thơm.'),
                    array('Sữa chua có đường', '50g (1/2 hộp)', 'Tăng độ chua thanh dịu nhẹ.'),
                    array('Nước đường cát', '20ml', 'Cân bằng độ chua của chanh.'),
                    array('Đá bào tuyết mịn', '220g', 'Xay tốc độ cao tạo form chanh tuyết xốp mịn không bị dăm đá.')
                ),
                171 => array(
                    array('Chuối chín đông đá', '80g (1 quả vừa)', 'Lột vỏ cắt khoanh cấp đông giúp sinh tố sánh dẻo như kem.'),
                    array('Táo xanh / đỏ gọt vỏ cắt hạt lựu', '70g', 'Tạo vị ngọt thanh và độ xốp mọng nước.'),
                    array('Sữa tươi không đường', '60ml', 'Giúp máy xay vận hành trơn tru.'),
                    array('Sữa chua men sống', '50g', 'Hỗ trợ tiêu hóa và tạo độ ngậy chua thanh.'),
                    array('Mật ong hoa rừng', '15ml', 'Tạo vị ngọt thanh tự nhiên.'),
                    array('Đá viên nhỏ', '100g', 'Xay nhuyễn 30 giây.')
                ),
                172 => array(
                    array('Táo xanh New Zealand tươi', '250g (2 quả)', 'Rửa sạch ngâm muối loãng, cắt miếng ép lấy nước cốt.'),
                    array('Nước cốt chanh tươi', '5ml', 'Chống oxy hóa giúp nước ép giữ màu xanh ngọc đẹp mắt.'),
                    array('Nước đường mía', '15ml', 'Cân bằng vị chua giòn của táo xanh.'),
                    array('Đá viên tinh khiết', '150g', 'Làm lạnh tức thì khi uống.')
                ),
                173 => array(
                    array('Cà rốt Đà Lạt tươi', '150g', 'Gọt vỏ rửa sạch, ép kiệt bã lấy nước cốt giàu Vitamin A.'),
                    array('Táo tươi mọng nước', '100g', 'Ép cùng cà rốt làm dịu mùi hăng tự nhiên.'),
                    array('Gừng tươi gọt vỏ', '5g', 'Tạo nốt cay ấm kích thích tuần hoàn máu.'),
                    array('Nước cốt chanh', '5ml', 'Giữ màu sắc tươi sáng.'),
                    array('Nước đường', '10ml', 'Tùy chỉnh theo khẩu vị.'),
                    array('Đá viên', '120g', 'Phục vụ lạnh sảng khoái.')
                ),
                175 => array(
                    array('Trái cây mix theo mùa (Dâu tây, Xoài, Bơ)', '120g (40g mỗi loại)', 'Trái cây chín tới cắt hạt lựu cấp đông.'),
                    array('Sữa chua men tự nhiên', '100g (1 hộp)', 'Tạo kết cấu sánh mịn và vị chua thanh.'),
                    array('Sữa đặc có đường', '25ml', 'Tạo độ béo ngọt hài hòa.'),
                    array('Nước cốt dừa thơm', '15ml', 'Tạo điểm nhấn béo ngậy nhiệt đới.'),
                    array('Đá bào tuyết', '150g', 'Xay mịn ở công suất cao.')
                ),
                176 => array(
                    array('Chuối chín đông đá', '80g (1 quả)', 'Cung cấp năng lượng kali dồi dào, tạo độ sánh kem.'),
                    array('Kiwi xanh New Zealand', '70g (1 quả)', 'Vị chua dịu ngọt và giàu Vitamin C.'),
                    array('Sữa hạt óc chó / Hạnh nhân', '50ml', 'Bổ sung chất béo tốt không cholesterol.'),
                    array('Sữa chua Hy Lạp', '50g', 'Tăng cường protein lành mạnh.'),
                    array('Mật ong', '15ml', 'Tạo ngọt tự nhiên.'),
                    array('Đá viên bi', '120g', 'Xay nhuyễn mịn màng.')
                ),
                177 => array(
                    array('Cốt trà nhài / Trà Ô long thanh mát', '120ml', 'Ủ 5g trà với 120ml nước 85°C trong 8 phút, sốc nhiệt lạnh.'),
                    array('Mứt / Puree chanh dây & xoài nhiệt đới', '30ml', 'Tạo nốt chua ngọt bùng nổ hương vị mùa hè.'),
                    array('Nước đường mía', '20ml', 'Cân bằng vị chua hoa quả.'),
                    array('Trái cây tươi cắt lát (Dưa hấu, Cam vàng, Táo, Thơm)', '60g', 'Thả trực tiếp vào ly làm topping tươi mát.'),
                    array('Hạt chia ngâm nở', '15g', 'Topping giòn bùi giàu chất xơ.'),
                    array('Đá viên tinh khiết', '200g', 'Lắc đều trong bình shaker.')
                ),
                220 => array(
                    array('Cốt trà đen đậm đà pha trà sữa', '80ml', 'Tạo hương vị trà sữa nền cho bánh kem.'),
                    array('Kem phô mai tươi Mascarpone / Cream Cheese', '120g', 'Đánh mềm mịn cùng 30g đường bột.'),
                    array('Whipping cream Anchor', '150ml', 'Đánh bông mềm 70% tạo lớp kem phủ béo ngậy.'),
                    array('Bánh bông lan chiffon mềm xốp', '1 cốt bánh 12cm', 'Cốt bánh mềm xốp thấm đượm vị trà sữa.'),
                    array('Trân châu đen nấu đường đen Hàn Quốc', '60g', 'Phủ sốt trân châu đường đen ấm dẻo lên đỉnh bánh trước khi dùng.')
                ),
                259 => array(
                    array('Cốt trà xanh lài hoặc trà đen', '120ml', 'Ủ 5g trà với 120ml nước 85°C trong 8 phút.'),
                    array('Nước cốt chanh tươi lọc hạt', '20ml', 'Vắt nhẹ tay lấy nước cốt thơm không đắng.'),
                    array('Kim quất tươi đập dập', '2 quả (khoảng 15g)', 'Tạo nốt hương tinh dầu quất thơm ngát đặc trưng.'),
                    array('Nước đường mía nguyên chất', '35ml', 'Cân chỉnh ngọt chua đậm đà.'),
                    array('Vài lát chanh tươi trang trí', '2 lát mỏng', 'Thả vào ly tạo vẻ ngoài hấp dẫn.'),
                    array('Đá viên tinh khiết', '200g', 'Lắc thật mạnh tay trong shaker 15 nhịp.')
                ),
                144 => array(
                    array('Công thức 1: Tỷ lệ ủ cốt trà chuẩn thương hiệu', '1g trà : 30ml nước sôi', 'Nhiệt độ 85°C - 90°C, ủ 10-15 phút, sốc nhiệt lạnh giữ hương thơm bền lâu.'),
                    array('Công thức 2: Tỷ lệ phối trộn bột béo cân bằng', '100ml trà : 28g bột sữa : 15ml sữa đặc', 'Hòa tan khi trà còn ấm 60°C để giải phóng hoàn toàn chất béo thực vật.'),
                    array('Công thức 3: Tỷ lệ nấu và ủ trân châu hoàng kim', '1 phần trân châu : 6 phần nước sôi', 'Luộc 25 phút - Ủ 25 phút - Trộn đường đen dẻo dai 12 tiếng không sượng.')
                ),
                143 => array(
                    array('Liều lượng cà phê bột (Dose)', '18g - 20g bột mịn', 'Dùng cà phê mộc 100% Arabica & Robusta rang mộc medium-dark.'),
                    array('Lực nén tay nén (Tamping pressure)', '15kg - 20kg phẳng đều', 'Tránh hiện tượng rãnh dòng chảy (channeling) làm hỏng vị cà phê.'),
                    array('Nhiệt độ nước chiết xuất', '91°C - 94°C', 'Nhiệt độ chuẩn chiết xuất trọn vẹn dầu thơm và hương vị phong phú.'),
                    array('Áp suất máy pha', '9 Bar tiêu chuẩn', 'Áp suất lý tưởng tạo lớp Crema dày vàng óng ả.'),
                    array('Thời gian chiết xuất & Lượng chiết (Yield)', '25 - 30 giây ra 36ml - 40ml Espresso', 'Tỷ lệ pha chuẩn 1:2 cho vị đắng êm, hậu ngọt sâu lắng.')
                ),
                164 => array(
                    array('Tiêu chí 1: Màu sắc bột Matcha', 'Xanh ngọc bích tươi sáng (Vivid Green)', 'Matcha cao cấp thu hoạch búp trà bóng râm vụ xuân có màu xanh ngọc bích rực rỡ, không bị úa vàng.'),
                    array('Tiêu chí 2: Độ mịn của hạt bột', 'Mịn màng 5 - 10 micron', 'Xay bằng cối đá granite, miết trên mu bàn tay tan mịn như phấn không lợn cợn.'),
                    array('Tiêu chí 3: Mùi hương đặc trưng', 'Hương cốm non thơm ngát', 'Hương thơm ngọt dịu của cỏ non và cốm tươi, không khét gắt hay có mùi cỏ khô cũ.'),
                    array('Tiêu chí 4: Vị giác (Umami)', 'Vị Umami ngọt sâu, chát thanh nhẹ', 'Giàu axit amin L-theanine tạo vị ngọt hậu umami rõ nét, chỉ chát nhẹ đầu lưỡi.'),
                    array('Tiêu chí 5: Độ bền bọt khi đánh chasen', 'Lớp bọt mịn màng bền lâu', 'Đánh bằng chổi tre chasen tạo lớp bọt foam mịn mượt, lâu tan.')
                )
            );

            $table_rows = isset($tables_db[$news_id]) ? $tables_db[$news_id] : null;
            
            if (!$table_rows) {
                // Smart fallback generator for other recipes
                $lower_title = mb_strtolower($title, 'UTF-8');
                if (strpos($lower_title, 'cà phê') !== false || strpos($lower_title, 'cafe') !== false || strpos($lower_title, 'espresso') !== false) {
                    $table_rows = array(
                        array('Cà phê mộc nguyên chất pha phin / máy', '25g bột (hoặc 35ml Espresso)', 'Cà phê rang mộc chuẩn gu Việt, hương thơm nồng nàn.'),
                        array('Sữa đặc có đường', '25ml', 'Tạo độ ngọt béo dẻo mượt.'),
                        array('Sữa tươi thanh trùng', '30ml', 'Tăng độ ngậy thanh tự nhiên.'),
                        array('Nước đường mía', '10ml', 'Cân chỉnh theo khẩu vị.'),
                        array('Đá viên bi tinh khiết', '180g', 'Làm lạnh sâu.')
                    );
                } elseif (strpos($lower_title, 'sinh tố') !== false || strpos($lower_title, 'smoothie') !== false) {
                    $table_rows = array(
                        array('Trái cây tươi chín mọng tuyển chọn', '120g - 140g', 'Cắt hạt lựu, cấp đông tạo độ dẻo xốp tự nhiên.'),
                        array('Sữa chua có đường / men sống', '50g (1/2 hộp)', 'Tạo độ chua thanh và cung cấp men vi sinh tốt.'),
                        array('Sữa đặc có đường', '30ml', 'Tạo độ ngọt sánh béo.'),
                        array('Sữa tươi không đường', '40ml', 'Giúp máy xay mịn màng.'),
                        array('Đá bào tuyết', '150g', 'Xay công suất cao trong 35 giây.')
                    );
                } elseif (strpos($lower_title, 'nước ép') !== false || strpos($lower_title, 'nuoc ep') !== false) {
                    $table_rows = array(
                        array('Trái cây tươi giàu dinh dưỡng', '250g - 300g', 'Rửa sạch ép chậm giữ trọn vitamin và khoáng chất.'),
                        array('Nước cốt chanh tươi', '5ml', 'Chống oxy hóa giữ màu sắc tươi tắn.'),
                        array('Nước đường mía tự nhiên', '15ml - 20ml', 'Làm dịu vị chua gắt của trái cây.'),
                        array('Đá viên bi', '150g', 'Phục vụ mát lạnh.')
                    );
                } elseif (strpos($lower_title, 'kem') !== false) {
                    $table_rows = array(
                        array('Whipping cream / Topping cream chuyên dụng', '120ml', 'Để lạnh sâu trước khi đánh form kem.'),
                        array('Sữa tươi thanh trùng không đường', '50ml', 'Tạo độ sánh mượt.'),
                        array('Bột tạo béo / Bột cheese mặn', '20g', 'Tạo hương vị thơm béo quyến rũ.'),
                        array('Đường cát / Muối biển', '15g đường + 1.5g muối', 'Cân bằng vị béo không gây ngấy.')
                    );
                } else {
                    $table_rows = array(
                        array('Cốt trà hảo hạng chuyên dụng', '120ml', 'Ủ 5g trà với 120ml nước 85°C - 90°C trong 10-12 phút, sốc nhiệt lạnh.'),
                        array('Bột béo thực vật Non-dairy Vua An Toàn', '28g', 'Hòa tan khi cốt trà còn nóng giúp vị béo ngậy mượt mà.'),
                        array('Sữa đặc hoặc sữa tươi thanh trùng', '15ml - 20ml', 'Tạo độ sánh dẻo và ngọt hậu sâu lắng.'),
                        array('Nước đường mía nguyên chất', '20ml', 'Tỷ lệ nấu chuẩn 2 đường : 1 nước.'),
                        array('Topping trân châu / Thạch tươi tự làm', '45g - 50g', 'Dai giòn chuẩn vị quán nổi tiếng.'),
                        array('Đá viên bi tinh khiết', '180g', 'Lắc đều 15 nhịp trong bình shaker.')
                    );
                }
            }

            $table_html = '
            <div class="pl-recipe-table-wrap">
                <h3 class="pl-table-title">📋 BẢNG ĐỊNH LƯỢNG NGUYÊN LIỆU CHUẨN BARISTA (CHO 1 LY 500ML)</h3>
                <div class="table-responsive">
                    <table class="pl-recipe-table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Thành Phần Nguyên Liệu</th>
                                <th style="width: 25%;">Định Lượng Chuẩn</th>
                                <th style="width: 40%;">Ghi Chú Kỹ Thuật & Barista Passion Link</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($table_rows as $row) {
                $table_html .= '
                            <tr>
                                <td><strong>' . htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8') . '</strong></td>
                                <td><span class="pl-qty-badge">' . htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8') . '</span></td>
                                <td>' . htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8') . '</td>
                            </tr>';
            }
            $table_html .= '
                        </tbody>
                    </table>
                </div>
                <p class="pl-table-note"><em>* Ghi chú: Định lượng trên được chuẩn hóa theo tiêu chuẩn định lượng quầy bar chuyên nghiệp Passion Link, đảm bảo đồng nhất chất lượng 100 ly như 1 khi vận hành quán.</em></p>
            </div>';

            // 3. Reverse Silo Links Box (2 mandatory contextual links to Course 223 and Course 552)
            $silo_box = '
            <div class="pl-silo-box">
                <div class="pl-silo-head">
                    <span class="pl-silo-badge">💡 GÓC TƯ VẤN DÀNH CHO CHỦ QUÁN F&B</span>
                    <h3>Nâng Tầm Kỹ Năng Pha Chế & Làm Chủ Quán Cùng Passion Link</h3>
                    <p>Sở hữu công thức chỉ là bước khởi đầu. Để tự tin cạnh tranh trên thị trường, định hình phong cách đồ uống độc bản và quản trị chi phí giá vốn (cost) tối ưu, bạn cần một nền tảng đào tạo thực chiến chuẩn quốc tế:</p>
                </div>
                <div class="pl-silo-grid">
                    <div class="pl-silo-card">
                        <div class="pl-silo-card-tag">Khóa Học Chuyên Sâu 223</div>
                        <h4><a href="https://phache.com.vn/cac-khoa-hoc-day-pha-che/khoa-hoc-menu-thuong-hieu-tra-sua-223.html" title="Khóa học menu thương hiệu trà sữa 223">Khóa Học Menu Thương Hiệu Trà Sữa Độc Quyền (Khóa 223)</a></h4>
                        <p>Dành riêng cho chủ quán muốn tạo sự khác biệt: Bí quyết ủ 30+ loại cốt trà hảo hạng, công thức kem cheese béo ngậy, topping handmade dẫn đầu thị trường và kỹ thuật định giá menu siêu lợi nhuận.</p>
                        <a href="https://phache.com.vn/cac-khoa-hoc-day-pha-che/khoa-hoc-menu-thuong-hieu-tra-sua-223.html" class="pl-silo-link-btn">Khám Phá Chi Tiết Khóa 223 &rarr;</a>
                    </div>
                    <div class="pl-silo-card highlight">
                        <div class="pl-silo-card-tag hot">Khóa Học Mở Quán 552</div>
                        <h4><a href="https://phache.com.vn/cac-khoa-hoc-day-pha-che/khoa-pha-che-tong-hop-7-menu-thuc-uong-noi-tieng-229.html" title="Khóa học pha chế tổng hợp mở quán 552">Khóa Học Pha Chế Tổng Hợp 7 Menu Mở Quán (Khóa 552)</a></h4>
                        <p>Khóa học toàn diện bao gồm 92-141 món hot nhất: Trà sữa, Cà phê pha máy Barista, Trà trái cây, Đồ đá xay, Đồ uống nóng. Hỗ trợ trọn gói từ setup quầy bar, lên danh mục thiết bị đến bảo hành tay nghề trọn đời.</p>
                        <a href="https://phache.com.vn/cac-khoa-hoc-day-pha-che/khoa-pha-che-tong-hop-7-menu-thuc-uong-noi-tieng-229.html" class="pl-silo-link-btn">Xem Lộ Trình Khóa Tổng Hợp 552 &rarr;</a>
                    </div>
                </div>
            </div>';

            // 4. Inject Quick Recipe Box right after first paragraph (BLUF)
            if (strpos($content, 'class="pl-quick-recipe-box"') === false) {
                if (preg_match('/<\/p>/i', $content)) {
                    $p_parts = explode('</p>', $content, 2);
                    $content = $p_parts[0] . '</p>' . "\n" . $quick_box . "\n" . $p_parts[1];
                } else {
                    $content = $quick_box . "\n" . $content;
                }
            }

            // 5. Replace ingredients list or insert HTML Table
            if (strpos($content, 'class="pl-recipe-table"') === false) {
                if (preg_match('/(<h[23][^>]*>.*?NGUYÊN\s*LIỆU.*?<\/h[23]>)(.*?)(<h[23]|$)/is', $content)) {
                    $content = preg_replace('/(<h[23][^>]*>.*?NGUYÊN\s*LIỆU.*?<\/h[23]>)(.*?)(<h[23]|$)/is', '$1' . "\n" . $table_html . "\n" . '$3', $content, 1);
                } else {
                    // Fallback append table
                    $content .= "\n" . $table_html;
                }
            }

            // 6. Append Silo Box at the end
            if (strpos($content, 'class="pl-silo-box"') === false) {
                $content .= "\n" . $silo_box;
            }

            return $content;
        }
    }

    if (!function_exists('seo_inject_mid_article_lead_card')) {
        function seo_inject_mid_article_lead_card($content, $title, $news) {
            if (strpos($content, 'class="pl-mid-lead-card"') !== false) {
                return $content;
            }

            $lead_card = '
            <div class="pl-mid-lead-card">
                <div class="pl-mid-lead-badge">🎁 ĐĂNG KÝ TƯ VẤN 1-1 TỪ CHUYÊN GIA PASSION LINK</div>
                <h4 class="pl-mid-lead-title">Nhận Trọn Bộ Bảng Tính Cost 50+ Món & Lộ Trình Mở Quán Thực Chiến</h4>
                <p class="pl-mid-lead-desc">Để lại số điện thoại hoặc Zalo, chuyên viên Passion Link sẽ liên hệ tư vấn mô hình quán phù hợp và gửi tặng file Excel kế hoạch dòng tiền mở quán miễn phí.</p>
                <form class="pl-mid-lead-form" onsubmit="return plSubmitMidLead(event, this);">
                    <div class="pl-mid-input-wrapper">
                        <input type="tel" name="phone" required pattern="[0-9]{10,11}" placeholder="Nhập số điện thoại / Zalo của bạn..." class="pl-mid-input" onfocus="plTrackMidFormStart()">
                        <button type="submit" class="pl-mid-btn">Gửi Nhận Ngay 🚀</button>
                    </div>
                </form>
                <div class="pl-mid-success" style="display:none;">
                    🎉 <strong>Đã tiếp nhận thông tin!</strong> Chuyên viên Passion Link sẽ phản hồi hỗ trợ bạn qua Zalo trong ít phút.
                </div>
                <div class="pl-mid-footer">🔒 Cam kết bảo mật thông tin · Miễn phí tư vấn 100%</div>
            </div>';

            if (preg_match_all('/<\/h2>/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
                if (isset($matches[0][1])) {
                    $pos = $matches[0][1][1] + strlen($matches[0][1][0]);
                    return substr_replace($content, "\n" . $lead_card . "\n", $pos, 0);
                }
            }

            $paragraphs = explode('</p>', $content);
            if (count($paragraphs) >= 4) {
                $mid_index = (int)(count($paragraphs) / 2);
                $paragraphs[$mid_index] .= "\n" . $lead_card . "\n";
                return implode('</p>', $paragraphs);
            }

            return $content . "\n" . $lead_card;
        }
    }

    if (!function_exists('seo_inject_inarticle_zalo_card')) {
        function seo_inject_inarticle_zalo_card($content, $title, $news) {
            if (strpos($content, 'class="pl-inarticle-zalo-card"') !== false) {
                return $content;
            }

            $zalo_card = '
            <div class="pl-inarticle-zalo-card">
                <div class="pl-izalo-left">
                    <div class="pl-izalo-badge">💬 HỖ TRỢ TRỰC TIẾP 1-1 QUA ZALO</div>
                    <h4 class="pl-izalo-title">Cần Tư Vấn Menu Mở Quán & Báo Giá Học Phí?</h4>
                    <p class="pl-izalo-desc">Đội ngũ giảng viên Passion Link trực tuyến từ 8h00 - 21h30 sẵn sàng hỗ trợ bạn khảo sát mặt bằng, gợi ý danh mục thiết bị và gửi công thức cập nhật.</p>
                </div>
                <div class="pl-izalo-right">
                    <a href="https://zalo.me/0977300098" target="_blank" rel="noopener noreferrer" class="pl-izalo-btn" onclick="plTrackZalo(\'in_article_card\', this.href)">
                        <span class="pl-izalo-ico">💬</span>
                        <span>Chat Zalo Với Chuyên Gia</span>
                    </a>
                    <div class="pl-izalo-hotline">Hotline: <a href="tel:0977300098">0977.300.098</a> (24/7)</div>
                </div>
            </div>';

            return $content . "\n" . $zalo_card . "\n";
        }
    }

    if (!function_exists('pl_render_video_facade')) {
        function pl_render_video_facade($video_url, $title) {
            if (empty($video_url)) return '';

            $video_id = '';
            $is_shorts = (strpos($video_url, '/shorts/') !== false);

            if (preg_match('/(?:youtube\.com\/(?:embed\/|v\/|watch\?v=|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $video_url, $m)) {
                $video_id = $m[1];
            }

            if (empty($video_id)) {
                return '<div class="container center pl-video-wrapper"><div class="pl-video-responsive"><iframe width="560" height="315" src="' . htmlspecialchars($video_url) . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe></div></div>';
            }

            $embed_src = 'https://www.youtube.com/embed/' . $video_id . '?autoplay=1&rel=0';
            $thumbnail_src = 'https://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg';
            $aspect_class = $is_shorts ? 'pl-video-facade-shorts' : 'pl-video-facade-16-9';

            return '
            <div class="container center pl-video-wrapper">
                <div class="pl-video-facade-container ' . $aspect_class . '">
                    <div class="pl-video-facade" data-src="' . htmlspecialchars($embed_src) . '" data-title="' . htmlspecialchars($title) . '" onclick="plActivateVideoFacade(this)">
                        <img src="' . htmlspecialchars($thumbnail_src) . '" alt="' . htmlspecialchars($title) . '" class="pl-video-poster" loading="lazy" decoding="async">
                        <div class="pl-video-overlay"></div>
                        <div class="pl-video-play-btn" aria-label="Xem video">
                            <svg viewBox="0 0 68 48" width="68" height="48">
                                <path class="pl-yt-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#E53935"></path>
                                <path d="M 45,24 27,14 27,34" fill="#FFFFFF"></path>
                            </svg>
                        </div>
                        <div class="pl-video-caption">▶ Chạm để xem video thực hành tại quầy bar Passion Link</div>
                    </div>
                </div>
            </div>';
        }
    }

    if (!function_exists('pl_render_dynamic_faq_schema')) {
        function pl_render_dynamic_faq_schema($title, $news) {
            $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $page_id = isset($news['page_id']) ? (int)$news['page_id'] : 0;
            $is_business = (strpos($uri, '/mo-quan') !== false) || ($page_id == 24);
            $is_course = (strpos($uri, '/cac-khoa-hoc-day-pha-che') !== false) || ($page_id == 22);

            $faqs = array();
            if ($is_business) {
                $faqs[] = array(
                    'q' => 'Mở quán kinh doanh trà sữa, cà phê cần chuẩn bị bao nhiêu vốn?',
                    'a' => 'Số vốn mở quán phụ thuộc vào mô hình: Kiot hoặc xe đẩy Takeaway từ 80 - 150 triệu; Quán quy mô vừa 40-60m² từ 250 - 400 triệu; Quán lớn hoặc không gian sân vườn từ 500 triệu trở lên. Passion Link hỗ trợ học viên lập bảng dự toán chi tiết từng hạng mục để tối ưu vốn đầu tư ban đầu.'
                );
                $faqs[] = array(
                    'q' => 'Chi phí giá vốn nguyên liệu (Cost) đồ uống trong quán nên chiếm bao nhiêu phần trăm?',
                    'a' => 'Chuẩn biên lợi nhuận ngành F&B đồ uống khuyến nghị chi phí giá vốn (Cost nguyên liệu + bao bì) chỉ nên chiếm từ 28% đến 32% giá bán lẻ. Biên lợi nhuận gộp đạt 68% - 72% để đảm bảo trang trải chi phí mặt bằng, nhân sự và có lợi nhuận ròng an toàn.'
                );
                $faqs[] = array(
                    'q' => 'Thời gian thu hồi vốn trung bình của quán cà phê, trà sữa là bao lâu?',
                    'a' => 'Với tỷ lệ lấp đầy ổn định và quản trị định lượng chuẩn, thời gian thu hồi vốn trung bình của các mô hình quán do học viên Passion Link vận hành dao động từ 4 đến 8 tháng.'
                );
            } elseif ($is_course) {
                $faqs[] = array(
                    'q' => 'Chưa có kinh nghiệm hoặc chưa từng pha chế có theo học khóa học được không?',
                    'a' => 'Hoàn toàn được. Lộ trình đào tạo tại Passion Link được thiết kế từ căn bản đến chuyên sâu, hơn 90% thời lượng là thực hành trực tiếp tại quầy bar chuẩn quốc tế với giảng viên kèm 1-1.'
                );
                $faqs[] = array(
                    'q' => 'Sau khi hoàn thành khóa học, Passion Link có hỗ trợ gì cho học viên mở quán?',
                    'a' => 'Học viên được áp dụng chính sách Bảo hành tay nghề trọn đời: ôn tập thực hành miễn phí bất cứ lúc nào, hỗ trợ tư vấn thiết kế quầy bar, lên danh mục máy móc và cung ứng nguyên liệu giá sỉ tận gốc từ hệ sinh thái Vua An Toàn.'
                );
                $faqs[] = array(
                    'q' => 'Thời gian đào tạo một khóa học pha chế tại Passion Link kéo dài bao lâu?',
                    'a' => 'Thời gian học linh hoạt từ 3 đến 10 ngày tùy chuyên đề hoặc khóa tổng hợp. Có lớp cấp tốc kèm riêng cho học viên ở tỉnh hoặc chuẩn bị khai trương quán.'
                );
            } else {
                $faqs[] = array(
                    'q' => 'Cách pha chế công thức này có thể áp dụng để kinh doanh mở quán được không?',
                    'a' => 'Được. Tất cả công thức tại Passion Link đều được chuẩn hóa theo định lượng thực chiến quầy bar, đảm bảo hương vị đậm đà đồng nhất và tối ưu chi phí giá vốn (Cost) dưới 30% để chủ quán đạt lợi nhuận cao nhất.'
                );
                $faqs[] = array(
                    'q' => 'Bảo quản nguyên liệu và cốt trà/cà phê như thế nào để giữ trọn vị ngon trong ngày?',
                    'a' => 'Cốt trà nên được ủ ở nhiệt độ chuẩn 85°C - 90°C và bảo quản trong bình giữ nhiệt chuyên dụng từ 4 - 6 tiếng. Không nên dùng trà ủ qua đêm để tránh bị biến chất và mất hương thơm tự nhiên.'
                );
                $faqs[] = array(
                    'q' => 'Làm sao để đăng ký học trực tiếp công thức và kỹ thuật tạo menu độc quyền tại Passion Link?',
                    'a' => 'Bạn có thể đăng ký trực tuyến tại website phache.com.vn hoặc nhắn tin Zalo số hotline 0977.300.098 để được chuyên viên tư vấn lịch học và nhận ưu đãi học phí mới nhất.'
                );
            }

            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array()
            );

            foreach ($faqs as $item) {
                $schema['mainEntity'][] = array(
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $item['a']
                    )
                );
            }

            return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
        }
    }

    if (!function_exists('seo_enrich_complete_article_content')) {
        function seo_enrich_complete_article_content($content, $title, $news) {
            $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $page_id = isset($news['page_id']) ? (int)$news['page_id'] : 0;
            $is_recipe = ($page_id === 22) || (strpos($uri, '/day-pha-che-tra-sua-ngon/') !== false);

            if ($is_recipe && function_exists('seo_enrich_recipe_article_content')) {
                $content = seo_enrich_recipe_article_content($content, $title, $news);
            }

            // DX-04: Inject Mid-article Lead Card after 2nd H2 tag
            if (function_exists('seo_inject_mid_article_lead_card')) {
                $content = seo_inject_mid_article_lead_card($content, $title, $news);
            }

            // DX-02: Inject In-article Zalo Card near the end
            if (function_exists('seo_inject_inarticle_zalo_card')) {
                $content = seo_inject_inarticle_zalo_card($content, $title, $news);
            }

            return $content;
        }
    }

     ?>
    <div class="panel_content">
    <?php
        require_once(dirname(__FILE__) . '/seo_map.php');
        $seo_news_meta = get_seo_metadata_for_current_request(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/', isset($news['news_id']) ? $news['news_id'] : 0);
        $article_seo_h1 = !empty($seo_news_meta['h1']) ? $seo_news_meta['h1'] : (isset($news['title']) ? $news['title'] : '');

        if (isset($news['news_id'])) {
            if($news['page_id'] == 22 && !empty($news['image_title'])) {
    ?>
                <div class="image-cover">
                    <img src="<?php echo $news['image_title'];?>" alt="<?php echo $article_seo_h1;?>" width="970" height="360" loading="eager" fetchpriority="high"/>
                </div>
        <?php 
            }
        ?>
        <div class="container">
            
            <div id="news_content">

                <?php 
                $raw_article_content = !empty($news['news_img_slide']) ? prefix_insert_post_ads($news['content'], $news['news_img_slide'], $article_seo_h1) : $news['content'];
                $normalized_article_content = seo_normalize_article_headings($raw_article_content, $article_seo_h1, $news);
                echo seo_enrich_complete_article_content($normalized_article_content, $article_seo_h1, $news);
                ?>
                
            </div>

            <?php echo pl_render_dynamic_faq_schema($article_seo_h1, $news); ?>

            <?php include dirname(__FILE__) . '/widget_drink_calculator.php'; ?>
  
    <?php 
     $req_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
     $is_core_course = ($news['page_id'] == 22) || (strpos($req_uri, '/cac-khoa-hoc-day-pha-che') !== false);
     if (!empty($news['news_videos']) || $is_core_course) {
         $vid_target = !empty($news['news_videos']) ? $news['news_videos'] : 'https://www.youtube.com/watch?v=07pucUJVXP4';
         echo pl_render_video_facade($vid_target, $article_seo_h1);
     }
    ?>


            <div id="news_share">
                <span class='st_facebook_hcount' displayText='Facebook'></span>
                <span class='st_fblike_hcount' displayText='Facebook Like'></span>
                <span class='st_plusone_hcount' displayText='Google +1'></span>
                <span class='st_twitter_hcount' displayText='Tweet'></span>
                <span class='st_sharethis_hcount' displayText='ShareThis'></span>
            </div>
            <?php  if ($news['page_id'] == 22) :  ?>
                <?php if (isset($listTrainee) && !empty($listTrainee)) : ?>
                    <div id="trainee">
                        <h2 class="h2-title">Chia sẽ của học viên</h2>
                        <div class="trainee-content">
                            <?php foreach ($listTrainee as $k => $tr) : ?> 
                                <div class="item<?php echo ($k % 2) ? ' right' : '' ?>">
                                    <div class="img">
                                        <img class="img-responsive" src="<?php echo $tr['trainee_img'] ?>" alt="<?php echo $tr['trainee_name'] ?>" width="100" height="100" loading="lazy" decoding="async">
                                        <span><?php echo $tr['trainee_name'] ?></span>
                                    </div>
                                    <div class="intro">
                                        <?php echo quotesDecode($tr['trainee_content']) ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>
                <?php if (isset($listSuccess) && !empty($listSuccess)) : ?>
                    <div id="success">
                        <div class="content_success container">
                            <h2 class="h2-title">Quán học viên thành công</h2>
                            <div class="wrap_success">
                                <?php 
                                    foreach ($listSuccess as $k => $ls) {
                                        $ls['title']        = quotesDecode($ls['news_title']); 
                                        $ls['news_url']     = getUrlUri($pageSuccess['page_permalink'],SITE_EXT,$ls['news_id'],$ls['title']);
                                        $ls['img_url']      = prepareImg(NEWS_DIR.$ls['news_image'], NEWS_URL.$ls['news_image']);
                                        $ls['img_thumb']    = TTHUMB_URL ."&amp;src=". base64_encode($ls['img_url']) . '&amp;w=335';
                                    ?>
                                        <div class="col-md-3 col-sm-6 item">
                                            <div class="item_success">
                                                <div class="img_success">
                                                    <a href="<?php echo $ls['news_url'] ?>" title="<?php echo $ls['news_title'] ?>">
                                                        <img src="<?php echo $ls['img_thumb'] ?>" alt="<?php echo $ls['news_title'] ?>" width="335" height="220" loading="lazy" decoding="async">
                                                    </a>
                                                </div>
                                                <div class="title">
                                                    <a href="<?php echo $ls['news_url'] ?>" title="<?php echo $ls['news_title'] ?>">
                                                        <?php echo limitCharsUnicode($ls['news_title'], 50) ?>
                                                    </a>
                                                </div>
                                                <div class="des_success">
                                                    <?php echo limitCharsUnicode($ls['news_description'], 100) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <div class="form_dangky">
                    <?php include("advisory.php") ?>
                </div>
                <h3 class="h3-relate">Các khóa học khác</h3>
                <div id="course">
                    <div class="row">
                        <?php
                            foreach ($listNewsOther as $n) {
                                $n['title']         = quotesDecode($n['news_title']);
                                $n['news_url']      = getUrlUri($template['page']['page_permalink'],'.html',$n['news_id'],$n['title']);
                                $n['img_url']       = prepareImg(NEWS_DIR . $n['news_image'], NEWS_URL . $n['news_image']);
                                $n['img_thumb']     = TTHUMB_URL ."&amp;src=". base64_encode($n['img_url']) . '&amp;w=300';
                        ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="item_course">
                                        <div class="col-md-5 col-sm-4 img_course">
                                            <a href="<?php echo $n['news_url'] ?>" title="<?php echo $n['news_title'] ?>">
                                                <img src="<?php echo $n['img_thumb'] ?>" alt="<?php echo $n['news_title'] ?>" width="300" height="200" loading="lazy" decoding="async">
                                            </a>
                                        </div>
                                        <div class="col-md-7 col-sm-8 des_course">
                                            <a href="<?php echo $n['news_url'] ?>" title="<?php echo $n['news_title'] ?>">
                                                <h3><?php echo limitCharsUnicode($n['news_title'], 33) ?></h3>
                                            </a>
                                            <div><?php echo limitCharsUnicode($n['news_description'], 80) ?></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <h3 class="h3-relate">Tin tức khác</h3>
                <ul id="list_news_other">
                    <?php
                        foreach ($listNewsOther as $n) {
                            $n['title']    = quotesDecode($n['news_title']);
                            $n['news_url'] = getUrlUri($template['page']['page_permalink'],'.html',$n['news_id'],$n['title']);
                    ?>
                            <li>
                                <h2>
                                    <a href="<?php echo $n['news_url']?>" title="<?php echo $n['news_title']?>">
                                        <?php echo $n['title']?>
                                    </a>
                                    <span style="color: #909090">&nbsp;(<?php echo date('d/m', $n['news_date_created'])?>)</span>
                                </h2>
                            </li>
                    <?php } ?>
                </ul>
            <?php endif ?>
        </div>
        <div class="container">
        	<div class="fb-comments" data-href="<?php echo rtrim(BASE_URL,'/') . $_SERVER['REQUEST_URI'] ?>" data-numposts="10" data-width="100%"></div>
    	</div>
    <?php
    } else {   
    ?>
        <div class="container" id="wrap-list-news">
            <h1 class="h1-title">
                <?php echo !empty($seo_news_meta['h1']) ? $seo_news_meta['h1'] : $template['page']['page_name']; ?>
            </h1>
            <div id="list_news">
                <?php
                    if (isset($listNewspage) && is_array($listNewspage)) {
                        foreach ($listNewspage as $n) {
                            $n['title']    = quotesDecode($n['news_title']); 
                            $n['news_url'] = getUrlUri($template['page']['page_permalink'],'.html',$n['news_id'],$n['title']);
                            $n['img_url']  = prepareImg(NEWS_DIR.$n['news_image'], NEWS_URL.$n['news_image']);
                            $n['img_thumb'] = TTHUMB_URL . '&amp;src=' . base64_encode($n['img_url']) . '&amp;w=600&amp;h=400';

                            // Tối ưu mô tả tóm tắt hiển thị: Chống hiển thị placeholder thô "Tóm tắt bài viết"
                            $n_desc = !empty($n['news_description']) ? trim(strip_tags(quotesDecode($n['news_description']))) : '';
                            if (empty($n_desc) || preg_match('/^(?:tóm tắt bài viết|tóm tắt|mô tả bài viết|mô tả|description|summary|excerpt)\b/iu', $n_desc)) {
                                if (!empty($n['news_content'])) {
                                    $rawClean = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $n['news_content']);
                                    $rawClean = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $rawClean);
                                    $rawClean = preg_replace('/<video\b[^>]*>(.*?)<\/video>/is', '', $rawClean);
                                    $rawClean = preg_replace('/<figure\b[^>]*>(.*?)<\/figure>/is', '', $rawClean);
                                    $rawClean = preg_replace('/<table\b[^>]*>(.*?)<\/table>/is', '', $rawClean);
                                    $rawClean = preg_replace('/<h[1-6]\b[^>]*>(.*?)<\/h[1-6]>/is', '', $rawClean);
                                    $rawClean = trim(preg_replace('/\s+/', ' ', strip_tags(quotesDecode($rawClean))));
                                    $n_desc = !empty($rawClean) ? limitCharsUnicode($rawClean, 150) : 'Khám phá bí quyết pha chế và kinh nghiệm mở quán thực chiến tại Passion Link.';
                                } else {
                                    $n_desc = 'Khám phá bí quyết pha chế và kinh nghiệm mở quán thực chiến tại Passion Link.';
                                }
                            } else {
                                $n_desc = limitCharsUnicode($n_desc, 150);
                            }
                ?>
                        <div class="news_item col-md-4 col-sm-6">
                            <div class="box-effect">
                                <div class="news_img">
                                        <a href="<?php echo $n['news_url']?>" title="<?php echo $n['news_title']?>">
                                            <img src="<?php echo $n['img_thumb']?>" alt="<?php echo $n['news_title']?>" width="360" height="240" loading="lazy" decoding="async"/>
                                        </a>
                                </div>
                                <div class="news_info">
                                    <div class="news_title">
                                        <a href="<?php echo $n['news_url']?>" title="<?php echo $n['news_title']?>">
                                            <?php echo limitCharsUnicode($n['title'], 75) ?>
                                        </a>
                                    </div>
                                    <div class="description"><?php echo $n_desc ?></div>
                                </div>
                            </div>
                        </div>
                <?php
                        } // end foreach list news
                    }
                ?>
            </div>

        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" style="margin-top:20px;margin-bottom:25px;">
                <?php
                    if (isset($pagi) && is_object($pagi)) {
                        $pagi->show();
                    }
                ?>
                </div>
            </div>
        </div>
        <?php } ?>

    </div><!-- panel_content -->
</div><!-- panel -->
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
<!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
<!-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->
<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
<script type="text/javascript">
$(document).ready(function () {
    $('.carousel[data-type="multi"] .item').each(function() {
    var next = $(this).next();
    if (!next.length) {
        next = $(this).siblings(':first');
    }
    next.children(':first-child').clone().appendTo($(this));

    for (var i = 0; i < 2; i++) {
        next = next.next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }

        next.children(':first-child').clone().appendTo($(this));
    }
});
});
var ToC =
  "<nav role='navigation' class='table-of-contents'>" +
    "<h2>Phụ lục:</h2>" +
    "<ul>";

var newLine, el, title, link;

$("#news_content h3").each(function() {

  el = $(this);
  title = el.text();
  link = "#" + el.attr("id");

  newLine =
    "<li>" +
      "<a href='" + link + "'>" +
        title +
      "</a>" +
    "</li>";

  ToC += newLine;

});

ToC +=
   "</ul>" +
  "</nav>";

$(".all-questions").prepend(ToC);
</script>

<script>
/* ==================== DX-04: MID-ARTICLE LEAD CAPTURE HANDLER ==================== */
var plMidFormStarted = false;
function plTrackMidFormStart() {
    if (!plMidFormStarted) {
        plMidFormStarted = true;
        if (typeof gtag === 'function') {
            gtag('event', 'form_start', {
                'form_name': 'mid_article_lead',
                'event_category': 'Lead'
            });
        }
    }
}

function plSubmitMidLead(e, form) {
    if (e && e.preventDefault) e.preventDefault();
    var input = form.querySelector('input[name="phone"]');
    var phone = input ? input.value.trim() : '';
    if (!phone || phone.length < 9) {
        alert('Vui lòng nhập đúng số điện thoại hoặc Zalo!');
        return false;
    }

    // 1. GA4 event
    if (typeof gtag === 'function') {
        gtag('event', 'generate_lead', {
            'form_name': 'mid_article_lead',
            'event_category': 'Lead',
            'event_label': 'Mid-Article Quick Consultation',
            'value': 100000
        });
    }

    // 2. Gửi AJAX về CMS admin (dual redundant endpoints: saveSign + saveCallToAction)
    var BASE = 'https://phache.com.vn/';
    var d1 = new URLSearchParams();
    d1.append('template_function', 'saveSign');
    d1.append('dk_name', 'Khách Zalo đăng ký giữa bài viết');
    d1.append('dk_email', '');
    d1.append('dk_number', phone);
    d1.append('dk_class', 'Tư Vấn Mở Quán 1-1 (Mid-Article Lead)');
    fetch(BASE, { method: 'POST', mode: 'no-cors', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: d1.toString() }).catch(function(){});

    var d2 = new URLSearchParams();
    d2.append('template_function', 'saveCallToAction');
    d2.append('cta_name', 'Khách Zalo đăng ký giữa bài viết');
    d2.append('cta_phone', phone);
    d2.append('cta_course', 'Tư Vấn Mở Quán & Báo Giá 1-1');
    d2.append('cta_purpose', 'Mid-Article Quick Consultation Lead');
    fetch(BASE, { method: 'POST', mode: 'no-cors', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: d2.toString() }).catch(function(){});

    // 3. Hiển thị thông báo thành công
    var successDiv = form.parentElement.querySelector('.pl-mid-success');
    if (successDiv) {
        form.style.display = 'none';
        successDiv.style.display = 'block';
    }

    return false;
}

/* ==================== DX-03: VIDEO FACADE ACTIVATION ==================== */
function plActivateVideoFacade(el) {
    var src = el.getAttribute('data-src');
    var title = el.getAttribute('data-title') || 'Video thực hành';
    if (!src) return;

    // GA4 tracking
    if (typeof gtag === 'function') {
        gtag('event', 'video_interaction', {
            'video_url': src,
            'video_title': title,
            'action': 'play_facade',
            'event_category': 'Video'
        });
    }

    var iframe = document.createElement('iframe');
    iframe.setAttribute('src', src);
    iframe.setAttribute('title', title);
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
    iframe.setAttribute('allowfullscreen', '1');
    iframe.style.width = '100%';
    iframe.style.height = '100%';
    iframe.style.position = 'absolute';
    iframe.style.top = '0';
    iframe.style.left = '0';
    iframe.style.border = 'none';

    el.innerHTML = '';
    el.appendChild(iframe);
}
</script>