<?php
// WIDGET BẢNG TÍNH GIÁ VỐN ĐỒ UỐNG (DRINK COST CALCULATOR) - PASSION LINK
// Vanilla JS nhẹ (< 5KB), Giao diện Glassmorphism, Responsive 100% Mobile & Desktop
?>
<div class="pl-calc-wrapper" id="pl-drink-calculator">
  <div class="pl-calc-card">
    <div class="pl-calc-header">
      <div class="pl-calc-badge">📊 CÔNG CỤ DÀNH CHO CHỦ QUÁN</div>
      <h3 class="pl-calc-title">Bảng Tính Giá Vốn & Định Giá Đồ Uống Mở Quán</h3>
      <p class="pl-calc-desc">Tự động tính chi phí nguyên vật liệu (Cost) và gợi ý giá bán lẻ đạt biên lợi nhuận chuẩn ngành F&B (68% - 72%).</p>
    </div>

    <!-- CHỌN LOẠI ĐỒ UỐNG -->
    <div class="pl-calc-row">
      <label class="pl-calc-label">1. Chọn loại đồ uống:</label>
      <div class="pl-calc-pills" id="pl-drink-types">
        <button type="button" class="pl-pill-btn active" data-type="tra_sua">🧋 Trà Sữa</button>
        <button type="button" class="pl-pill-btn" data-type="tra_trai_cay">🍋 Trà Trái Cây</button>
        <button type="button" class="pl-pill-btn" data-type="ca_phe">☕ Cà Phê</button>
      </div>
    </div>

    <!-- CHỌN SIZE LY -->
    <div class="pl-calc-row">
      <label class="pl-calc-label">2. Chọn dung tích ly (Size):</label>
      <div class="pl-calc-sizes" id="pl-drink-sizes">
        <button type="button" class="pl-size-btn active" data-size="M">Size M (500ml)</button>
        <button type="button" class="pl-size-btn" data-size="L">Size L (700ml)</button>
      </div>
    </div>

    <!-- BẢNG NHẬP NGUYÊN LIỆU -->
    <div class="pl-calc-grid">
      <div class="pl-calc-input-group">
        <label for="pl-cost-tea" id="pl-label-tea">Cốt trà / Cafe (đ):</label>
        <div class="pl-input-addon">
          <input type="number" id="pl-cost-tea" value="1800" min="0" step="100" class="pl-calc-input">
          <span>đ</span>
        </div>
      </div>

      <div class="pl-calc-input-group">
        <label for="pl-cost-milk" id="pl-label-milk">Sữa tươi / Sữa đặc / Bột béo (đ):</label>
        <div class="pl-input-addon">
          <input type="number" id="pl-cost-milk" value="2500" min="0" step="100" class="pl-calc-input">
          <span>đ</span>
        </div>
      </div>

      <div class="pl-calc-input-group">
        <label for="pl-cost-sugar">Nước đường / Syrup / Sốt (đ):</label>
        <div class="pl-input-addon">
          <input type="number" id="pl-cost-sugar" value="1000" min="0" step="100" class="pl-calc-input">
          <span>đ</span>
        </div>
      </div>

      <div class="pl-calc-input-group">
        <label for="pl-cost-topping">Topping (Trân châu, thạch, foam...) (đ):</label>
        <div class="pl-input-addon">
          <input type="number" id="pl-cost-topping" value="3500" min="0" step="100" class="pl-calc-input">
          <span>đ</span>
        </div>
      </div>

      <div class="pl-calc-input-group pl-col-full">
        <label for="pl-cost-pack">Bao bì (Ly, nắp, ống hút, túi, màng ép) (đ):</label>
        <div class="pl-input-addon">
          <input type="number" id="pl-cost-pack" value="1800" min="0" step="100" class="pl-calc-input">
          <span>đ</span>
        </div>
      </div>
    </div>

    <!-- KẾT QUẢ TÍNH TOÁN (GLASSMORPHISM CARD) -->
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

    <!-- CTA ACTION -->
    <div class="pl-calc-actions">
      <button type="button" class="pl-calc-btn-lead" onclick="if(typeof plOpenExitModal==='function'){plOpenExitModal();}else{window.location.href='/cac-khoa-hoc-day-pha-che/khoa-pha-che-tong-hop-7-menu-thuc-uong-noi-tieng-229.html';}">
        🎁 Tải Bảng Tính Cost Excel 50+ Món Miễn Phí
      </button>
      <a href="/cac-khoa-hoc-day-pha-che/khoa-pha-che-tong-hop-7-menu-thuc-uong-noi-tieng-229.html" class="pl-calc-btn-course">
        🎓 Khóa Học Pha Chế Mở Quán Chuyên Nghiệp
      </a>
    </div>
  </div>
</div>

<style>
/* GLASSMORPHISM STYLING FOR DRINK COST CALCULATOR */
.pl-calc-wrapper {
  margin: 35px auto;
  max-width: 760px;
  width: 100%;
  box-sizing: border-box;
}
.pl-calc-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(240, 249, 245, 0.90) 100%);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border: 1.5px solid rgba(44, 122, 123, 0.25);
  box-shadow: 0 14px 40px rgba(44, 122, 123, 0.12), 0 2px 8px rgba(0,0,0,0.04);
  border-radius: 20px;
  padding: 26px;
  box-sizing: border-box;
  font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: #1a3028;
}
.pl-calc-header {
  text-align: center;
  margin-bottom: 22px;
}
.pl-calc-badge {
  display: inline-block;
  background: rgba(44, 122, 123, 0.12);
  color: #2C7A7B;
  font-weight: 700;
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
  background: rgba(255, 255, 255, 0.85);
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
  font-weight: 600;
  color: #375347;
  margin-bottom: 5px;
}
.pl-input-addon {
  position: relative;
  display: flex;
  align-items: center;
}
.pl-calc-input {
  width: 100%;
  padding: 10px 34px 10px 12px;
  border: 1.5px solid rgba(44, 122, 123, 0.25);
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.9);
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
.pl-res-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 6px 0;
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
  color: rgba(255, 255, 255, 0.9);
}
.pl-res-highlight .pl-res-label {
  font-size: 15px;
  font-weight: 800;
  color: #FFD54F;
}
.pl-res-value {
  font-size: 17px;
  font-weight: 800;
  letter-spacing: 0.5px;
}
.pl-val-price {
  font-size: clamp(22px, 3.5vw, 26px);
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
.pl-calc-btn-lead, .pl-calc-btn-course {
  flex: 1 1 240px;
  padding: 12px 18px;
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

@media (max-width: 600px) {
  .pl-calc-card { padding: 18px 14px; }
  .pl-calc-grid { grid-template-columns: 1fr; gap: 10px; }
  .pl-col-full { grid-column: span 1; }
  .pl-res-item { flex-direction: column; align-items: flex-start; gap: 4px; }
  .pl-res-value { align-self: flex-end; }
  .pl-calc-actions { flex-direction: column; }
}
</style>

<script>
// VANILLA JS LOGIC CHO BẢNG TÍNH GIÁ VỐN
(function(){
  var presets = {
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

  var currentType = 'tra_sua';
  var currentSize = 'M';
  var gaTimer = null;

  function formatMoney(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " đ";
  }

  function calculate() {
    var tea = parseFloat(document.getElementById('pl-cost-tea').value) || 0;
    var milk = parseFloat(document.getElementById('pl-cost-milk').value) || 0;
    var sugar = parseFloat(document.getElementById('pl-cost-sugar').value) || 0;
    var topping = parseFloat(document.getElementById('pl-cost-topping').value) || 0;
    var pack = parseFloat(document.getElementById('pl-cost-pack').value) || 0;

    var totalCost = tea + milk + sugar + topping + pack;
    // Mục tiêu lãi 70% -> Giá bán = Cost / (1 - 0.70)
    var rawPrice = totalCost > 0 ? (totalCost / 0.30) : 0;
    // Làm tròn đến 1000đ
    var suggestedPrice = Math.ceil(rawPrice / 1000) * 1000;
    if (suggestedPrice < totalCost) suggestedPrice = totalCost * 1.5;

    var grossProfit = suggestedPrice - totalCost;
    var marginPercent = suggestedPrice > 0 ? Math.round((grossProfit / suggestedPrice) * 1000) / 10 : 0;

    document.getElementById('pl-res-cost').textContent = formatMoney(totalCost);
    document.getElementById('pl-res-price').textContent = formatMoney(suggestedPrice);
    document.getElementById('pl-res-profit').textContent = "Lãi gộp: " + formatMoney(grossProfit) + "/ly";
    document.getElementById('pl-res-margin').textContent = "Tỷ suất lợi nhuận: " + marginPercent + "%";

    // Debounce bắn sự kiện GA4
    if (gaTimer) clearTimeout(gaTimer);
    gaTimer = setTimeout(function(){
      if (typeof gtag === 'function' && totalCost > 0) {
        gtag('event', 'calculator_used', {
          'drink_type': currentType,
          'cost': totalCost,
          'size': currentSize
        });
      }
      if (window.dataLayer && totalCost > 0) {
        window.dataLayer.push({
          'event': 'calculator_used',
          'drink_type': currentType,
          'cost': totalCost,
          'size': currentSize
        });
      }
    }, 1500);
  }

  function applyPreset(type, size) {
    currentType = type;
    currentSize = size;
    var p = presets[type] || presets.tra_sua;
    var mult = (size === 'L') ? 1.3 : 1.0;

    document.getElementById('pl-label-tea').textContent = p.labelTea;
    document.getElementById('pl-label-milk').textContent = p.labelMilk;

    document.getElementById('pl-cost-tea').value = Math.round(p.tea * mult);
    document.getElementById('pl-cost-milk').value = Math.round(p.milk * mult);
    document.getElementById('pl-cost-sugar').value = Math.round(p.sugar * mult);
    document.getElementById('pl-cost-topping').value = Math.round(p.topping * (size === 'L' ? 1.2 : 1.0));
    document.getElementById('pl-cost-pack').value = Math.round(p.pack * (size === 'L' ? 1.15 : 1.0));

    calculate();
  }

  document.addEventListener('DOMContentLoaded', function(){
    // Lắng nghe nút chọn đồ uống
    var pills = document.querySelectorAll('#pl-drink-types .pl-pill-btn');
    pills.forEach(function(btn){
      btn.addEventListener('click', function(){
        pills.forEach(function(b){ b.classList.remove('active'); });
        this.classList.add('active');
        applyPreset(this.getAttribute('data-type'), currentSize);
      });
    });

    // Lắng nghe nút chọn size
    var sizes = document.querySelectorAll('#pl-drink-sizes .pl-size-btn');
    sizes.forEach(function(btn){
      btn.addEventListener('click', function(){
        sizes.forEach(function(b){ b.classList.remove('active'); });
        this.classList.add('active');
        applyPreset(currentType, this.getAttribute('data-size'));
      });
    });

    // Lắng nghe input thay đổi
    var inputs = document.querySelectorAll('.pl-calc-input');
    inputs.forEach(function(inp){
      inp.addEventListener('input', calculate);
    });

    calculate();
  });
})();
</script>
