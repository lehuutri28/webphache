<?php
// WIDGET BẢNG TÍNH GIÁ VỐN & DỰ TOÁN CHI PHÍ LỢI NHUẬN MỞ QUÁN - PASSION LINK
// Hỗ trợ 2 chế độ: (1) Tính Cost Đồ Uống Từng Món - (2) Dự Toán Dòng Tiền & Lợi Nhuận Mở Quán (Mô hình Excel thực chiến)
// Vanilla JS nhẹ (< 8KB), Responsive 100% Mobile & Desktop, 24h Client Cache TTL
?>
<div class="pl-calc-wrapper" id="pl-drink-calculator">
  <div class="pl-calc-card">
    
    <!-- CHỌN CHẾ ĐỘ TÍNH TOÁN (TAB SWITCHER) -->
    <div class="pl-calc-tabs" role="tablist">
      <button type="button" class="pl-tab-btn active" id="pl-tab-drink" onclick="plSwitchTab('drink')" role="tab" aria-selected="true">
        🍹 1. Tính Cost Đồ Uống
      </button>
      <button type="button" class="pl-tab-btn" id="pl-tab-business" onclick="plSwitchTab('business')" role="tab" aria-selected="false">
        📊 2. Dự Toán Lợi Nhuận Mở Quán
      </button>
    </div>

    <!-- ==================== TAB 1: TÍNH COST ĐỒ UỐNG ==================== -->
    <div id="pl-panel-drink" class="pl-tab-panel active">
      <div class="pl-calc-header">
        <div class="pl-calc-badge">⚡ CHUẨN ĐỊNH LƯỢNG BARISTA PASSION LINK</div>
        <h3 class="pl-calc-title">Bảng Tính Giá Vốn & Định Giá Đồ Uống Chuẩn Vị</h3>
        <p class="pl-calc-desc">Tự động tính chi phí nguyên vật liệu (Cost) và gợi ý giá bán lẻ đạt biên lợi nhuận chuẩn ngành F&B (68% - 72%).</p>
      </div>

      <!-- CHỌN LOẠI ĐỒ UỐNG -->
      <div class="pl-calc-row">
        <label class="pl-calc-label">Chọn loại đồ uống:</label>
        <div class="pl-calc-pills" id="pl-drink-types">
          <button type="button" class="pl-pill-btn active" id="pl-btn-drink-tra_sua" onclick="plSelectDrinkType('tra_sua', this)">🧋 Trà Sữa</button>
          <button type="button" class="pl-pill-btn" id="pl-btn-drink-tra_trai_cay" onclick="plSelectDrinkType('tra_trai_cay', this)">🍋 Trà Trái Cây</button>
          <button type="button" class="pl-pill-btn" id="pl-btn-drink-ca_phe" onclick="plSelectDrinkType('ca_phe', this)">☕ Cà Phê</button>
        </div>
      </div>

      <!-- CHỌN SIZE LY -->
      <div class="pl-calc-row">
        <label class="pl-calc-label">Chọn dung tích ly (Size):</label>
        <div class="pl-calc-sizes" id="pl-drink-sizes">
          <button type="button" class="pl-size-btn active" id="pl-btn-size-M" onclick="plSelectDrinkSize('M', this)">Size M (500ml)</button>
          <button type="button" class="pl-size-btn" id="pl-btn-size-L" onclick="plSelectDrinkSize('L', this)">Size L (700ml)</button>
        </div>
      </div>

      <!-- BẢNG NHẬP NGUYÊN LIỆU -->
      <div class="pl-calc-grid">
        <div class="pl-calc-input-group">
          <label for="pl-cost-tea" id="pl-label-tea">Cốt trà / Cafe (đ):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-cost-tea" value="1800" min="0" step="100" class="pl-calc-input" oninput="plCalculateDrink()" onchange="plCalculateDrink()">
            <span>đ</span>
          </div>
        </div>

        <div class="pl-calc-input-group">
          <label for="pl-cost-milk" id="pl-label-milk">Sữa tươi / Sữa đặc / Bột béo (đ):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-cost-milk" value="2500" min="0" step="100" class="pl-calc-input" oninput="plCalculateDrink()" onchange="plCalculateDrink()">
            <span>đ</span>
          </div>
        </div>

        <div class="pl-calc-input-group">
          <label for="pl-cost-sugar">Nước đường / Syrup / Sốt (đ):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-cost-sugar" value="1000" min="0" step="100" class="pl-calc-input" oninput="plCalculateDrink()" onchange="plCalculateDrink()">
            <span>đ</span>
          </div>
        </div>

        <div class="pl-calc-input-group">
          <label for="pl-cost-topping">Topping (Trân châu, thạch, foam...) (đ):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-cost-topping" value="3500" min="0" step="100" class="pl-calc-input" oninput="plCalculateDrink()" onchange="plCalculateDrink()">
            <span>đ</span>
          </div>
        </div>

        <div class="pl-calc-input-group pl-col-full">
          <label for="pl-cost-pack">Bao bì (Ly, nắp, ống hút, túi, màng ép) (đ):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-cost-pack" value="1800" min="0" step="100" class="pl-calc-input" oninput="plCalculateDrink()" onchange="plCalculateDrink()">
            <span>đ</span>
          </div>
        </div>
      </div>

      <!-- KẾT QUẢ TÍNH TOÁN TAB 1 -->
      <div class="pl-calc-result-box">
        <div class="pl-res-item">
          <span class="pl-res-label">Tổng Chi Phí Giá Vốn (Cost):</span>
          <span class="pl-res-value pl-val-cost" id="pl-res-cost">10.600 đ</span>
        </div>

        <div class="pl-res-item pl-res-highlight">
          <span class="pl-res-label">Giá Bán Lẻ Gợi Ý (Biên lãi ~70%):</span>
          <span class="pl-res-value pl-val-price" id="pl-res-price">35.000 đ</span>
        </div>

        <div class="pl-res-meta">
          <span class="pl-meta-tag" id="pl-res-profit">Lãi gộp: 24.400 đ/ly</span>
          <span class="pl-meta-tag pl-tag-green" id="pl-res-margin">Tỷ suất lợi nhuận: 69.7%</span>
        </div>
      </div>

      <!-- CTA ACTION TAB 1 -->
      <div class="pl-calc-actions">
        <button type="button" class="pl-calc-btn-lead" onclick="if(typeof plOpenExitModal==='function'){plOpenExitModal();}else{window.location.href='/nhan-uu-dai/';}">
          🎁 Tải Bảng Tính Cost Excel 50+ Món Miễn Phí
        </button>
        <a href="/cac-khoa-hoc-day-pha-che/khoa-pha-che-tong-hop-7-menu-thuc-uong-noi-tieng-229.html" class="pl-calc-btn-course">
          🎓 Xem Khóa Pha Chế Tổng Hợp 7 Menu Mở Quán
        </a>
      </div>
    </div>

    <!-- ==================== TAB 2: DỰ TOÁN LỢI NHUẬN & CHI PHÍ MỞ QUÁN ==================== -->
    <div id="pl-panel-business" class="pl-tab-panel" style="display:none;">
      <div class="pl-calc-header">
        <div class="pl-calc-badge" style="background: rgba(229,57,53,0.12); color: #d32f2f;">📈 MÔ HÌNH TÀI CHÍNH F&B THỰC CHIẾN</div>
        <h3 class="pl-calc-title">Bảng Dự Toán Chi Phí & Kế Hoạch Lợi Nhuận Mở Quán</h3>
        <p class="pl-calc-desc">Dựa trên ma trận tài chính thực tế từ file kế hoạch dòng tiền Passion Link giúp chủ quán tính chính xác điểm hòa vốn và thời gian hoàn vốn.</p>
      </div>

      <!-- PRESET MÔ HÌNH NHANH -->
      <div class="pl-calc-row">
        <label class="pl-calc-label">Chọn mô hình quán tham khảo:</label>
        <div class="pl-calc-pills" id="pl-biz-presets">
          <button type="button" class="pl-pill-btn active" id="pl-btn-biz-kiot" onclick="plSelectBizPreset('kiot', this)">🛵 Kiot / Takeaway (150tr)</button>
          <button type="button" class="pl-pill-btn" id="pl-btn-biz-vua" onclick="plSelectBizPreset('vua', this)">🪑 Quán vừa 40-60m² (300tr)</button>
          <button type="button" class="pl-pill-btn" id="pl-btn-biz-lon" onclick="plSelectBizPreset('lon', this)">🏢 Quán lớn / Chuỗi (550tr)</button>
        </div>
      </div>

      <!-- THÔNG SỐ ĐẦU VÀO KINH DOANH -->
      <div class="pl-calc-grid">
        <div class="pl-calc-input-group">
          <label for="pl-biz-capital">Tổng vốn đầu tư ban đầu (đ):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-biz-capital" value="150000000" min="20000000" step="5000000" class="pl-calc-input pl-biz-input" oninput="plCalculateBiz()" onchange="plCalculateBiz()">
            <span>đ</span>
          </div>
        </div>

        <div class="pl-calc-input-group">
          <label for="pl-biz-cups">Lượng bán dự kiến (ly / ngày):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-biz-cups" value="120" min="10" step="10" class="pl-calc-input pl-biz-input" oninput="plCalculateBiz()" onchange="plCalculateBiz()">
            <span>ly</span>
          </div>
        </div>

        <div class="pl-calc-input-group">
          <label for="pl-biz-price">Giá bán bình quân (đ / ly):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-biz-price" value="28000" min="10000" step="1000" class="pl-calc-input pl-biz-input" oninput="plCalculateBiz()" onchange="plCalculateBiz()">
            <span>đ</span>
          </div>
        </div>

        <div class="pl-calc-input-group">
          <label for="pl-biz-rent">Tiền thuê mặt bằng (đ / tháng):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-biz-rent" value="9000000" min="0" step="1000000" class="pl-calc-input pl-biz-input" oninput="plCalculateBiz()" onchange="plCalculateBiz()">
            <span>đ</span>
          </div>
        </div>

        <div class="pl-calc-input-group">
          <label for="pl-biz-staff">Chi phí nhân sự / barista (đ / tháng):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-biz-staff" value="12000000" min="0" step="1000000" class="pl-calc-input pl-biz-input" oninput="plCalculateBiz()" onchange="plCalculateBiz()">
            <span>đ</span>
          </div>
        </div>

        <div class="pl-calc-input-group">
          <label for="pl-biz-utilities">Điện, nước, internet, rác (đ / tháng):</label>
          <div class="pl-input-addon">
            <input type="number" id="pl-biz-utilities" value="3500000" min="0" step="500000" class="pl-calc-input pl-biz-input" oninput="plCalculateBiz()" onchange="plCalculateBiz()">
            <span>đ</span>
          </div>
        </div>
      </div>

      <!-- KẾT QUẢ DỰ TOÁN KINH DOANH TAB 2 -->
      <div class="pl-calc-result-box pl-biz-result-box">
        <div class="pl-res-item">
          <span class="pl-res-label">Doanh thu dự kiến / tháng:</span>
          <span class="pl-res-value" id="pl-res-biz-revenue" style="color:#A7F3D0;">100.800.000 đ</span>
        </div>

        <div class="pl-res-item">
          <span class="pl-res-label">Chi phí giá vốn nguyên liệu (COGS 32%):</span>
          <span class="pl-res-value" id="pl-res-biz-cogs">32.256.000 đ</span>
        </div>

        <div class="pl-res-item">
          <span class="pl-res-label">Tổng chi phí vận hành / tháng:</span>
          <span class="pl-res-value" id="pl-res-biz-costs">59.780.000 đ</span>
        </div>

        <div class="pl-res-item pl-res-highlight">
          <span class="pl-res-label">LỢI NHUẬN RÒNG DỰ KIẾN (EBIT):</span>
          <span class="pl-res-value pl-val-price" id="pl-res-biz-profit" style="color:#FFEB3B;">41.020.000 đ / tháng</span>
        </div>

        <div class="pl-res-meta">
          <span class="pl-meta-tag pl-tag-green" id="pl-res-biz-margin">Tỷ suất lãi ròng: 40.7%</span>
          <span class="pl-meta-tag" id="pl-res-biz-breakeven">Hòa vốn: 47 ly/ngày</span>
          <span class="pl-meta-tag" id="pl-res-biz-payback" style="background:#FF9800; color:#fff;">Hoàn vốn sau: ~3.7 tháng</span>
        </div>
      </div>

      <!-- HÀNH ĐỘNG ZALO VÀ TẢI EXCEL (DX-01 + DX-02) -->
      <div class="pl-calc-actions">
        <button type="button" class="pl-calc-btn-zalo" onclick="plSendCalcViaZalo()">
          📲 Gửi Bảng Tính Này Cho Em Qua Zalo (Hotline 0977.300.098)
        </button>
        <a href="/upload/tai-lieu/ke-hoach-dong-tien-mo-quan-passion-link.xlsx" download="ke-hoach-dong-tien-mo-quan-passion-link.xlsx" class="pl-calc-btn-excel" onclick="plTrackExcelDownload()">
          📥 Tải File Excel Kế Hoạch Dòng Tiền & Chi Phí (Chuẩn Passion Link)
        </a>
      </div>
      <div class="pl-calc-note-ttl">
        * Dữ liệu tạm thời được lưu cục bộ trên trình duyệt trong 24 giờ để bạn tiện đối chiếu khi khảo sát mặt bằng.
      </div>
    </div>

  </div>
</div>

<style>
/* CSS CHO BẢNG TÍNH ĐA NĂNG (COST & DỰ TOÁN MỞ QUÁN) */
.pl-calc-wrapper {
  margin: 35px auto;
  max-width: 820px;
  width: 100%;
  box-sizing: border-box;
}
.pl-calc-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(244, 250, 247, 0.94) 100%);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border: 1.5px solid rgba(44, 122, 123, 0.28);
  box-shadow: 0 16px 45px rgba(31, 63, 31, 0.12), 0 3px 10px rgba(0,0,0,0.04);
  border-radius: 20px;
  padding: 24px;
  box-sizing: border-box;
  font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: #1a3028;
}

/* TAB SWITCHER */
.pl-calc-tabs {
  display: flex;
  background: rgba(44, 122, 123, 0.10);
  border-radius: 14px;
  padding: 5px;
  gap: 6px;
  margin-bottom: 22px;
}
.pl-tab-btn {
  flex: 1 1 50%;
  padding: 12px 14px;
  border: none;
  background: transparent;
  border-radius: 10px;
  font-family: inherit;
  font-size: 15px;
  font-weight: 800;
  color: #285e61;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}
.pl-tab-btn:hover {
  background: rgba(255, 255, 255, 0.65);
}
.pl-tab-btn.active {
  background: #ffffff;
  color: #1F3F1F;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
}

.pl-calc-header {
  text-align: center;
  margin-bottom: 20px;
}
.pl-calc-badge {
  display: inline-block;
  background: rgba(44, 122, 123, 0.12);
  color: #2C7A7B;
  font-weight: 800;
  font-size: 12px;
  padding: 4px 14px;
  border-radius: 20px;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
}
.pl-calc-title {
  font-size: clamp(20px, 3.2vw, 24px);
  font-weight: 800;
  color: #1a4d3f;
  margin: 0 0 8px 0;
  line-height: 1.35;
}
.pl-calc-desc {
  font-size: 14px;
  color: #4a665a;
  margin: 0;
  line-height: 1.5;
}
.pl-calc-row {
  margin-bottom: 18px;
}
.pl-calc-label {
  display: block;
  font-weight: 700;
  font-size: 14px;
  color: #234e3f;
  margin-bottom: 8px;
}
.pl-calc-pills, .pl-calc-sizes {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
.pl-pill-btn, .pl-size-btn {
  flex: 1 1 auto;
  min-width: 100px;
  background: rgba(255, 255, 255, 0.9);
  border: 1.5px solid rgba(44, 122, 123, 0.25);
  padding: 10px 14px;
  border-radius: 12px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 700;
  color: #285e61;
  cursor: pointer;
  transition: all 0.2s ease;
  text-align: center;
}
.pl-pill-btn:hover, .pl-size-btn:hover {
  background: rgba(44, 122, 123, 0.08);
  border-color: #2C7A7B;
}
.pl-pill-btn.active, .pl-size-btn.active {
  background: #2C7A7B;
  color: #ffffff;
  border-color: #2C7A7B;
  box-shadow: 0 4px 12px rgba(44, 122, 123, 0.35);
}

.pl-calc-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
  margin-bottom: 22px;
}
.pl-col-full {
  grid-column: span 2;
}
.pl-calc-input-group label {
  display: block;
  font-size: 13px;
  font-weight: 700;
  color: #2a473a;
  margin-bottom: 5px;
}
.pl-input-addon {
  position: relative;
  display: flex;
  align-items: center;
}
.pl-calc-input {
  width: 100%;
  padding: 10px 38px 10px 12px;
  border: 1.5px solid rgba(44, 122, 123, 0.25);
  border-radius: 10px;
  background: #ffffff;
  font-family: inherit;
  font-size: 15px;
  font-weight: 700;
  color: #1a4d3f;
  box-sizing: border-box;
  outline: none;
  transition: border-color 0.2s;
}
.pl-calc-input:focus {
  border-color: #2C7A7B;
  box-shadow: 0 0 0 3px rgba(44, 122, 123, 0.15);
}
.pl-input-addon span {
  position: absolute;
  right: 12px;
  font-size: 13px;
  font-weight: 700;
  color: #718096;
  pointer-events: none;
}

.pl-calc-result-box {
  background: linear-gradient(135deg, #1c4b3e 0%, #2C7A7B 100%);
  border-radius: 16px;
  padding: 18px 22px;
  color: #ffffff;
  box-shadow: 0 8px 24px rgba(44, 122, 123, 0.30);
  margin-bottom: 20px;
}
.pl-biz-result-box {
  background: linear-gradient(135deg, #17382d 0%, #1e5645 50%, #246a55 100%);
}
.pl-res-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 7px 0;
  border-bottom: 1px dashed rgba(255, 255, 255, 0.25);
}
.pl-res-highlight {
  border-bottom: none;
  padding-top: 10px;
  padding-bottom: 10px;
}
.pl-res-label {
  font-size: 14px;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.92);
}
.pl-res-highlight .pl-res-label {
  font-size: 15px;
  font-weight: 800;
  color: #FFD54F;
}
.pl-res-value {
  font-size: 16px;
  font-weight: 800;
  letter-spacing: 0.5px;
}
.pl-val-price {
  font-size: clamp(21px, 3.5vw, 25px);
  color: #FFD54F;
  text-shadow: 0 2px 8px rgba(0,0,0,0.25);
}
.pl-res-meta {
  display: flex;
  gap: 10px;
  margin-top: 12px;
  flex-wrap: wrap;
}
.pl-meta-tag {
  background: rgba(255, 255, 255, 0.18);
  padding: 5px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 700;
}
.pl-tag-green {
  background: #25c056;
  color: #ffffff;
}

.pl-calc-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}
.pl-calc-btn-lead, .pl-calc-btn-course, .pl-calc-btn-zalo, .pl-calc-btn-excel {
  flex: 1 1 240px;
  padding: 13px 18px;
  border-radius: 12px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 800;
  text-align: center;
  text-decoration: none !important;
  cursor: pointer;
  transition: all 0.2s ease;
  box-sizing: border-box;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.pl-calc-btn-lead {
  background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
  color: #ffffff !important;
  border: none;
  box-shadow: 0 4px 14px rgba(211, 84, 0, 0.35);
}
.pl-calc-btn-lead:hover {
  background: linear-gradient(135deg, #d35400 0%, #b84400 100%);
  transform: translateY(-1px);
}
.pl-calc-btn-course {
  background: #ffffff;
  color: #2C7A7B !important;
  border: 2px solid #2C7A7B;
}
.pl-calc-btn-course:hover {
  background: rgba(44, 122, 123, 0.08);
  transform: translateY(-1px);
}
.pl-calc-btn-zalo {
  background: linear-gradient(135deg, #0068FF 0%, #0050cc 100%);
  color: #ffffff !important;
  border: none;
  box-shadow: 0 4px 14px rgba(0, 104, 255, 0.35);
}
.pl-calc-btn-zalo:hover {
  background: linear-gradient(135deg, #0050cc 0%, #003da6 100%);
  transform: translateY(-1px);
}
.pl-calc-btn-excel {
  background: #ffffff;
  color: #1F3F1F !important;
  border: 2px solid #2E7D32;
  box-shadow: 0 2px 8px rgba(46, 125, 50, 0.15);
}
.pl-calc-btn-excel:hover {
  background: rgba(46, 125, 50, 0.08);
  transform: translateY(-1px);
}
.pl-calc-note-ttl {
  font-size: 12px;
  color: #6a8276;
  margin-top: 10px;
  text-align: center;
  font-style: italic;
}

@media (max-width: 600px) {
  .pl-calc-card { padding: 18px 14px; }
  .pl-calc-grid { grid-template-columns: 1fr; gap: 10px; }
  .pl-col-full { grid-column: span 1; }
  .pl-res-item { flex-direction: column; align-items: flex-start; gap: 4px; }
  .pl-res-value { align-self: flex-end; }
  .pl-calc-actions { flex-direction: column; }
  .pl-tab-btn { font-size: 13px; padding: 10px 8px; }
}
</style>

<script>
// LOGIC CHO WIDGET BẢNG TÍNH ĐA NĂNG
var plCalcState = {
  currentTab: 'drink',
  drinkType: 'tra_sua',
  drinkSize: 'M',
  bizPreset: 'kiot',
  gaTimer: null
};

function plSwitchTab(tab) {
  plCalcState.currentTab = tab;
  var btnDrink = document.getElementById('pl-tab-drink');
  var btnBiz = document.getElementById('pl-tab-business');
  var panelDrink = document.getElementById('pl-panel-drink');
  var panelBiz = document.getElementById('pl-panel-business');

  if (tab === 'drink') {
    if (btnDrink) { btnDrink.classList.add('active'); btnDrink.setAttribute('aria-selected', 'true'); }
    if (btnBiz) { btnBiz.classList.remove('active'); btnBiz.setAttribute('aria-selected', 'false'); }
    if (panelDrink) panelDrink.style.display = 'block';
    if (panelBiz) panelBiz.style.display = 'none';
  } else {
    if (btnDrink) { btnDrink.classList.remove('active'); btnDrink.setAttribute('aria-selected', 'false'); }
    if (btnBiz) { btnBiz.classList.add('active'); btnBiz.setAttribute('aria-selected', 'true'); }
    if (panelDrink) panelDrink.style.display = 'none';
    if (panelBiz) panelBiz.style.display = 'block';
    plCalculateBiz();
  }
}

function plFormatMoney(num) {
  return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ";
}

/* ================== LOGIC TAB 1: TÍNH COST ================== */
var plDrinkPresets = {
  tra_sua: {
    tea: 1800, milk: 2500, sugar: 1000, topping: 3500, pack: 1800,
    labelTea: "Cốt trà Ô long / Hồng trà (đ):",
    labelMilk: "Bột béo thực vật / Sữa tươi (đ):"
  },
  tra_trai_cay: {
    tea: 1500, milk: 4200, sugar: 1200, topping: 2000, pack: 1800,
    labelTea: "Cốt lục trà lài / Ô long xanh (đ):",
    labelMilk: "Trái cây tươi (đào, dâu, xoài...) (đ):"
  },
  ca_phe: {
    tea: 2800, milk: 2000, sugar: 800, topping: 1500, pack: 1500,
    labelTea: "Hạt Robusta / Arabica nguyên chất (đ):",
    labelMilk: "Sữa đặc / Kem béo pha phin (đ):"
  }
};

function plCalculateDrink() {
  var elTea = document.getElementById('pl-cost-tea');
  var elMilk = document.getElementById('pl-cost-milk');
  var elSugar = document.getElementById('pl-cost-sugar');
  var elTopping = document.getElementById('pl-cost-topping');
  var elPack = document.getElementById('pl-cost-pack');

  var tea = elTea ? (parseFloat(elTea.value) || 0) : 0;
  var milk = elMilk ? (parseFloat(elMilk.value) || 0) : 0;
  var sugar = elSugar ? (parseFloat(elSugar.value) || 0) : 0;
  var topping = elTopping ? (parseFloat(elTopping.value) || 0) : 0;
  var pack = elPack ? (parseFloat(elPack.value) || 0) : 0;

  var totalCost = tea + milk + sugar + topping + pack;
  var rawPrice = totalCost > 0 ? (totalCost / 0.30) : 0;
  var suggestedPrice = Math.ceil(rawPrice / 1000) * 1000;
  if (suggestedPrice < totalCost) suggestedPrice = totalCost * 1.5;

  var grossProfit = suggestedPrice - totalCost;
  var marginPercent = suggestedPrice > 0 ? Math.round((grossProfit / suggestedPrice) * 1000) / 10 : 0;

  var resCost = document.getElementById('pl-res-cost');
  var resPrice = document.getElementById('pl-res-price');
  var resProfit = document.getElementById('pl-res-profit');
  var resMargin = document.getElementById('pl-res-margin');

  if (resCost) resCost.textContent = plFormatMoney(totalCost);
  if (resPrice) resPrice.textContent = plFormatMoney(suggestedPrice);
  if (resProfit) resProfit.textContent = "Lãi gộp: " + plFormatMoney(grossProfit) + "/ly";
  if (resMargin) resMargin.textContent = "Tỷ suất lợi nhuận: " + marginPercent + "%";

  // Debounce GA4 event
  if (plCalcState.gaTimer) clearTimeout(plCalcState.gaTimer);
  plCalcState.gaTimer = setTimeout(function(){
    if (typeof gtag === 'function' && totalCost > 0) {
      gtag('event', 'calculator_used', {
        'calc_mode': 'drink_cost',
        'drink_type': plCalcState.drinkType,
        'cost': totalCost,
        'size': plCalcState.drinkSize
      });
    }
  }, 1500);
}

function plSelectDrinkType(type, btn) {
  plCalcState.drinkType = type;
  var container = document.getElementById('pl-drink-types');
  if (container) {
    var btns = container.querySelectorAll('.pl-pill-btn');
    for (var i = 0; i < btns.length; i++) {
      btns[i].classList.remove('active');
    }
  }
  if (btn) {
    btn.classList.add('active');
  } else {
    var b = document.getElementById('pl-btn-drink-' + type);
    if (b) b.classList.add('active');
  }
  plApplyDrinkPreset(type, plCalcState.drinkSize);
}

function plSelectDrinkSize(size, btn) {
  plCalcState.drinkSize = size;
  var container = document.getElementById('pl-drink-sizes');
  if (container) {
    var btns = container.querySelectorAll('.pl-size-btn');
    for (var i = 0; i < btns.length; i++) {
      btns[i].classList.remove('active');
    }
  }
  if (btn) {
    btn.classList.add('active');
  } else {
    var b = document.getElementById('pl-btn-size-' + size);
    if (b) b.classList.add('active');
  }
  plApplyDrinkPreset(plCalcState.drinkType, size);
}

function plApplyDrinkPreset(type, size) {
  plCalcState.drinkType = type;
  plCalcState.drinkSize = size;
  var p = plDrinkPresets[type] || plDrinkPresets.tra_sua;
  var mult = (size === 'L') ? 1.3 : 1.0;

  var elLabelTea = document.getElementById('pl-label-tea');
  var elLabelMilk = document.getElementById('pl-label-milk');
  if (elLabelTea) elLabelTea.textContent = p.labelTea;
  if (elLabelMilk) elLabelMilk.textContent = p.labelMilk;

  var elTea = document.getElementById('pl-cost-tea');
  var elMilk = document.getElementById('pl-cost-milk');
  var elSugar = document.getElementById('pl-cost-sugar');
  var elTopping = document.getElementById('pl-cost-topping');
  var elPack = document.getElementById('pl-cost-pack');

  if (elTea) elTea.value = Math.round(p.tea * mult);
  if (elMilk) elMilk.value = Math.round(p.milk * mult);
  if (elSugar) elSugar.value = Math.round(p.sugar * mult);
  if (elTopping) elTopping.value = Math.round(p.topping * (size === 'L' ? 1.2 : 1.0));
  if (elPack) elPack.value = Math.round(p.pack * (size === 'L' ? 1.15 : 1.0));

  plCalculateDrink();
}

/* ================== LOGIC TAB 2: DỰ TOÁN MỞ QUÁN ================== */
var plBizPresets = {
  kiot: { capital: 150000000, cups: 120, price: 28000, rent: 9000000, staff: 12000000, utilities: 3500000 },
  vua:  { capital: 300000000, cups: 200, price: 32000, rent: 18000000, staff: 22000000, utilities: 6000000 },
  lon:  { capital: 550000000, cups: 350, price: 38000, rent: 35000000, staff: 42000000, utilities: 12000000 }
};

var plLastBizCalcResult = {};

function plSelectBizPreset(name, btn) {
  plCalcState.bizPreset = name;
  var container = document.getElementById('pl-biz-presets');
  if (container) {
    var btns = container.querySelectorAll('.pl-pill-btn');
    for (var i = 0; i < btns.length; i++) {
      btns[i].classList.remove('active');
    }
  }
  if (btn) {
    btn.classList.add('active');
  } else {
    var b = document.getElementById('pl-btn-biz-' + name);
    if (b) b.classList.add('active');
  }

  var p = plBizPresets[name] || plBizPresets.kiot;
  var elCap = document.getElementById('pl-biz-capital');
  var elCups = document.getElementById('pl-biz-cups');
  var elPrice = document.getElementById('pl-biz-price');
  var elRent = document.getElementById('pl-biz-rent');
  var elStaff = document.getElementById('pl-biz-staff');
  var elUtil = document.getElementById('pl-biz-utilities');

  if (elCap) elCap.value = p.capital;
  if (elCups) elCups.value = p.cups;
  if (elPrice) elPrice.value = p.price;
  if (elRent) elRent.value = p.rent;
  if (elStaff) elStaff.value = p.staff;
  if (elUtil) elUtil.value = p.utilities;

  plCalculateBiz();
}

function plCalculateBiz() {
  var elCap = document.getElementById('pl-biz-capital');
  var elCups = document.getElementById('pl-biz-cups');
  var elPrice = document.getElementById('pl-biz-price');
  var elRent = document.getElementById('pl-biz-rent');
  var elStaff = document.getElementById('pl-biz-staff');
  var elUtil = document.getElementById('pl-biz-utilities');

  var capital = elCap ? (parseFloat(elCap.value) || 0) : 0;
  var cups = elCups ? (parseFloat(elCups.value) || 0) : 0;
  var price = elPrice ? (parseFloat(elPrice.value) || 0) : 0;
  var rent = elRent ? (parseFloat(elRent.value) || 0) : 0;
  var staff = elStaff ? (parseFloat(elStaff.value) || 0) : 0;
  var utilities = elUtil ? (parseFloat(elUtil.value) || 0) : 0;

  var monthlyRevenue = cups * price * 30;
  // Chuẩn COGS ngành F&B đồ uống Passion Link: ~32% doanh thu
  var cogs = monthlyRevenue * 0.32;
  // Chi phí Marketing & duy trì khác: ~3% doanh thu
  var marketing = monthlyRevenue * 0.03;
  var fixedCosts = rent + staff + utilities;
  var totalMonthlyCosts = cogs + fixedCosts + marketing;
  var monthlyNetProfit = monthlyRevenue - totalMonthlyCosts;

  var marginPercent = monthlyRevenue > 0 ? (monthlyNetProfit / monthlyRevenue) * 100 : 0;

  // Điểm hòa vốn: Số ly/ngày tối thiểu
  // Doanh thu trên 1 ly = price; Lãi gộp trên 1 ly = price * (1 - 0.32 - 0.03) = price * 0.65
  var contributionPerCup = price * 0.65;
  var breakevenCupsPerDay = (contributionPerCup > 0 && fixedCosts > 0) ? Math.ceil((fixedCosts / 30) / contributionPerCup) : 0;

  // Thời gian hoàn vốn: Số tháng
  var paybackMonths = (monthlyNetProfit > 0 && capital > 0) ? (capital / monthlyNetProfit).toFixed(1) : 'Chưa hòa vốn';

  var resRev = document.getElementById('pl-res-biz-revenue');
  var resCogs = document.getElementById('pl-res-biz-cogs');
  var resCosts = document.getElementById('pl-res-biz-costs');
  var resProfit = document.getElementById('pl-res-biz-profit');
  var resMargin = document.getElementById('pl-res-biz-margin');
  var resBreakeven = document.getElementById('pl-res-biz-breakeven');
  var resPayback = document.getElementById('pl-res-biz-payback');

  if (resRev) resRev.textContent = plFormatMoney(monthlyRevenue);
  if (resCogs) resCogs.textContent = plFormatMoney(cogs);
  if (resCosts) resCosts.textContent = plFormatMoney(totalMonthlyCosts);

  if (resProfit) {
    if (monthlyNetProfit > 0) {
      resProfit.textContent = plFormatMoney(monthlyNetProfit) + " / tháng";
      resProfit.style.color = "#FFEB3B";
    } else {
      resProfit.textContent = "Chưa có lãi (" + plFormatMoney(monthlyNetProfit) + ")";
      resProfit.style.color = "#FF8A80";
    }
  }

  if (resMargin) resMargin.textContent = "Tỷ suất lãi ròng: " + marginPercent.toFixed(1) + "%";
  if (resBreakeven) resBreakeven.textContent = "Hòa vốn: " + breakevenCupsPerDay + " ly/ngày";
  if (resPayback) resPayback.textContent = (paybackMonths !== 'Chưa hòa vốn') ? ("Hoàn vốn sau: ~" + paybackMonths + " tháng") : "Cần tăng số ly bán";

  plLastBizCalcResult = {
    capital: capital,
    cups: cups,
    price: price,
    rent: rent,
    staff: staff,
    revenue: monthlyRevenue,
    profit: monthlyNetProfit,
    payback: paybackMonths,
    breakeven: breakevenCupsPerDay
  };

  // Lưu cache 24h vào localStorage
  try {
    localStorage.setItem('pl_moquan_calc_v2', JSON.stringify({
      data: { capital: capital, cups: cups, price: price, rent: rent, staff: staff, utilities: utilities, preset: plCalcState.bizPreset },
      timestamp: Date.now()
    }));
  } catch(e) {}

  // GA4 event
  if (plCalcState.gaTimer) clearTimeout(plCalcState.gaTimer);
  plCalcState.gaTimer = setTimeout(function(){
    if (typeof gtag === 'function' && monthlyRevenue > 0) {
      gtag('event', 'mo_quan_profit_calculator', {
        'capital': capital,
        'cups_per_day': cups,
        'avg_price': price,
        'monthly_revenue': monthlyRevenue,
        'monthly_net_profit': monthlyNetProfit,
        'payback_months': paybackMonths
      });
      gtag('event', 'calculator_used', {
        'calc_mode': 'mo_quan_business_plan',
        'monthly_revenue': monthlyRevenue
      });
    }
  }, 1500);
}

function plSendCalcViaZalo() {
  var res = plLastBizCalcResult;
  var msg = "Chào Passion Link! Em vừa lập bảng dự toán mở quán trên website:\n" +
            "- Vốn dự kiến: " + (res.capital ? Math.round(res.capital / 1000000) + " triệu" : "150 triệu") + "\n" +
            "- Bán dự kiến: " + (res.cups || 120) + " ly/ngày (giá tb " + (res.price ? (res.price/1000) + "k" : "28k") + ")\n" +
            "- Doanh thu ước tính: " + (res.revenue ? Math.round(res.revenue / 1000000) + " triệu/tháng" : "") + "\n" +
            "- Lợi nhuận ròng: " + (res.profit ? Math.round(res.profit / 1000000) + " triệu/tháng" : "") + "\n" +
            "Nhờ thầy/cô Passion Link xem qua và tư vấn lộ trình học mở quán + danh mục máy móc phù hợp giúp em với ạ!";

  // Sao chép tóm tắt vào clipboard
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(msg).catch(function(){});
  }

  // Bắn sự kiện GA4
  if (typeof gtag === 'function') {
    gtag('event', 'click_zalo', {
      'button_location': 'calculator_zalo_cta',
      'event_label': 'Gui Bang Tinh Mo Quan Qua Zalo'
    });
  }

  // Mở Zalo OA / Hotline
  window.open('https://zalo.me/0977300098', '_blank');
}

function plTrackExcelDownload() {
  if (typeof gtag === 'function') {
    gtag('event', 'file_download', {
      'file_name': 'ke-hoach-dong-tien-mo-quan-passion-link.xlsx',
      'file_extension': 'xlsx',
      'link_text': 'Tai File Excel Ke Hoach Dong Tien Mo Quan'
    });
  }
}

// KHỞI TẠO BẢNG TÍNH & PHỤC HỒI CACHE 24H
function plInitCalculator() {
  // Khôi phục dữ liệu từ localStorage (nếu chưa quá 24h)
  try {
    var rawCache = localStorage.getItem('pl_moquan_calc_v2');
    if (rawCache) {
      var parsed = JSON.parse(rawCache);
      var now = Date.now();
      // TTL 24 giờ = 86.400.000 ms
      if (now - parsed.timestamp < 86400000 && parsed.data) {
        if (parsed.data.capital && document.getElementById('pl-biz-capital')) document.getElementById('pl-biz-capital').value = parsed.data.capital;
        if (parsed.data.cups && document.getElementById('pl-biz-cups')) document.getElementById('pl-biz-cups').value = parsed.data.cups;
        if (parsed.data.price && document.getElementById('pl-biz-price')) document.getElementById('pl-biz-price').value = parsed.data.price;
        if (parsed.data.rent && document.getElementById('pl-biz-rent')) document.getElementById('pl-biz-rent').value = parsed.data.rent;
        if (parsed.data.staff && document.getElementById('pl-biz-staff')) document.getElementById('pl-biz-staff').value = parsed.data.staff;
        if (parsed.data.utilities && document.getElementById('pl-biz-utilities')) document.getElementById('pl-biz-utilities').value = parsed.data.utilities;
        if (parsed.data.preset) {
          plCalcState.bizPreset = parsed.data.preset;
          var container = document.getElementById('pl-biz-presets');
          if (container) {
            var btns = container.querySelectorAll('.pl-pill-btn');
            for (var i = 0; i < btns.length; i++) {
              btns[i].classList.remove('active');
            }
            var activeB = document.getElementById('pl-btn-biz-' + parsed.data.preset);
            if (activeB) activeB.classList.add('active');
          }
        }
      } else {
        localStorage.removeItem('pl_moquan_calc_v2');
      }
    }
  } catch(e) {}

  plCalculateDrink();
  plCalculateBiz();

  // Tự động mở Tab 2 nếu đang ở chuyên mục hoặc bài viết Mở Quán (/mo-quan/)
  if (window.location.pathname.indexOf('/mo-quan') !== -1) {
    plSwitchTab('business');
  }
}

// Chạy khởi tạo ngay lập tức nếu DOM đã sẵn sàng, hoặc gắn lắng nghe DOMContentLoaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', plInitCalculator);
} else {
  plInitCalculator();
}
</script>
