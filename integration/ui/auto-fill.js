/**
 * auto-fill.js — Phache form auto-fill + realtime validation
 *
 * Deploy target: /template/js/auto-fill.js (Mat Bao)
 *
 * Load trong template/init/index_top.php trước </body>:
 *   <script src="<?= TEMPLATE_DIR ?>js/auto-fill.js" defer></script>
 *
 * Chức năng (Letri chốt 2/5/2026):
 *   1) Realtime validate phone format khi blur — show error inline
 *   2) Khi phone hợp lệ → AJAX POST /template/api/customer-lookup.php
 *      → match KH cũ → fill name/email/address (user có thể sửa)
 *   3) Disable submit button cho đến khi pass validation
 *
 * UX rule:
 *   - Validation chặn SUBMIT (return error)
 *   - KHÔNG chặn BLUR (auto-fill vẫn trigger)
 */

(function () {
  'use strict';

  function normalizePhoneVN(raw) {
    if (raw == null) return null;
    var digits = String(raw).replace(/\D+/g, '');
    if (digits.length >= 11 && digits.slice(0, 2) === '84') {
      digits = '0' + digits.slice(2);
    }
    if (digits.length === 12 && digits.slice(0, 3) === '084') {
      digits = '0' + digits.slice(3);
    }
    return /^0[35789]\d{8}$/.test(digits) ? digits : null;
  }

  function showError(input, msg) {
    input.classList.add('invalid');
    input.classList.remove('valid');
    var next = input.nextElementSibling;
    if (!next || !next.classList.contains('field-error')) {
      next = document.createElement('span');
      next.className = 'field-error';
      input.parentNode.insertBefore(next, input.nextSibling);
    }
    next.textContent = msg;
  }
  function clearError(input) {
    input.classList.remove('invalid');
    input.classList.add('valid');
    var next = input.nextElementSibling;
    if (next && next.classList.contains('field-error')) next.textContent = '';
  }

  /** Field map cho 3 form: cta, advisory, sign */
  var FORM_MAP = [
    { phone: 'cta_phone',      name: 'cta_name',  email: null,         address: null },
    { phone: 'txt_number',     name: 'txt_name',  email: 'txt_email',  address: null },
    { phone: 'dk_number',      name: 'dk_name',   email: 'dk_email',   address: 'dk_home' }
  ];

  function lookupCustomer(phone, form, fields) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/template/api/customer-lookup.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.timeout = 5000;
    xhr.onload = function () {
      if (xhr.status !== 200) return;
      try {
        var r = JSON.parse(xhr.responseText);
        if (!r.found) return;
        if (fields.name && r.name) {
          var n = form.elements[fields.name];
          if (n && !n.value) n.value = r.name;
        }
        if (fields.email && r.email) {
          var e = form.elements[fields.email];
          if (e && !e.value) e.value = r.email;
        }
        if (fields.address && r.address) {
          var a = form.elements[fields.address];
          if (a && !a.value) a.value = r.address;
        }
      } catch (e) { /* ignore */ }
    };
    xhr.send(JSON.stringify({ phone: phone }));
  }

  function bindForm(form, fields) {
    var phoneInput = form.elements[fields.phone];
    if (!phoneInput) return;

    phoneInput.addEventListener('blur', function () {
      var raw = phoneInput.value.trim();
      if (raw === '') { clearError(phoneInput); return; }
      var phone = normalizePhoneVN(raw);
      if (phone === null) {
        showError(phoneInput, 'Số điện thoại không đúng chuẩn (10 số, đầu 03/05/07/08/09).');
      } else {
        clearError(phoneInput);
        // Auto-fill — vẫn trigger dù form chưa pass full validation
        lookupCustomer(phone, form, fields);
      }
    });

    phoneInput.addEventListener('input', function () {
      // Cho phép user nhập tiếp, gỡ error tạm thời
      if (phoneInput.classList.contains('invalid')) clearError(phoneInput);
    });

    form.addEventListener('submit', function (ev) {
      var raw = phoneInput.value.trim();
      var phone = normalizePhoneVN(raw);
      if (phone === null) {
        showError(phoneInput, 'Số điện thoại không đúng chuẩn — không thể gửi.');
        ev.preventDefault();
        phoneInput.focus();
        return false;
      }
      // Normalize lại trước submit để backend nhận chuẩn
      phoneInput.value = phone;

      // Check name min 2 từ
      if (fields.name) {
        var nameInput = form.elements[fields.name];
        if (nameInput) {
          var nv = (nameInput.value || '').trim().replace(/\s+/g, ' ');
          if (nv.length < 3 || nv.indexOf(' ') === -1) {
            showError(nameInput, 'Họ tên cần ít nhất 2 từ (họ + tên).');
            ev.preventDefault();
            nameInput.focus();
            return false;
          }
          clearError(nameInput);
          nameInput.value = nv;
        }
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('form');
    forms.forEach(function (form) {
      FORM_MAP.forEach(function (fields) {
        if (form.elements[fields.phone]) bindForm(form, fields);
      });
    });
  });
})();
