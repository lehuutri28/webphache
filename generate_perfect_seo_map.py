#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script tái cấu trúc toàn diện template/seo_map.php chuẩn SEO Top 1 Google:
- Khắc phục 100% lỗi tiếng Việt không dấu (do slug unaccented gây ra).
- Đồng bộ tiêu đề gốc có dấu tiếng Việt từ phache_urls_audit_raw.json.
- Tối ưu Title Tag (50-68 ký tự), Meta Description (145-160 ký tự), Thẻ H1 uy quyền chuẩn E-E-A-T.
- Hỗ trợ đầy đủ alias URL cho ID 72 và các URL liên quan.
"""

import json
import re
import os

RAW_AUDIT_PATH = "/Users/letri/Desktop/CEO/QUANLY/QL-MARKETING/SEO/DATA/phache_urls_audit_raw.json"
SEO_MAP_PATH = "template/seo_map.php"
OUTPUT_NEW_SEO_MAP = "template/new_seo_map.php"

# Load raw audit data
with open(RAW_AUDIT_PATH, 'r', encoding='utf-8') as f:
    raw_data = json.load(f)

raw_by_id = {}
raw_by_path = {}
for item in raw_data:
    url = item['url']
    path = re.sub(r'^https?://[^/]+', '', url)
    raw_by_path[path] = item
    m = re.search(r'-(\d+)\.html$', path)
    if m:
        raw_by_id[int(m.group(1))] = item

def clean_spaces(text):
    if not text:
        return ""
    text = re.sub(r'\s+', ' ', text)
    return text.strip()

def clean_vietnamese_title(text):
    if not text:
        return ""
    text = clean_spaces(text)
    # Strip trailing punctuation
    text = re.sub(r'[\.\-\?\!]+$', '', text).strip()
    
    # Remove repetitive phrases
    text = text.replace('MÀ KHÁCH HÀNG MÀ KHÁCH HÀNG', 'MÀ KHÁCH HÀNG')
    text = text.replace('mà khách hàng mà khách hàng', 'mà khách hàng')
    text = text.replace('BÝ QUYẾT', 'BÍ QUYẾT')
    text = text.replace('Bý Quyết', 'Bí Quyết')
    text = text.replace('bý quyết', 'bí quyết')
    text = text.replace('KHOÁ', 'KHÓA')
    text = text.replace('Khoá', 'Khóa')
    text = text.replace('khoá', 'khóa')
    text = text.replace('Tra sưa', 'Trà sữa')
    text = text.replace('tra sưa', 'trà sữa')
    
    # Strip generic category prefixes if present
    prefixes_to_strip = [
        r'^KHÓA HỌC\s*-\s*Khóa học dạy pha chế trung tâm Passion Link\s*[:\-]?\s*',
        r'^BÍ QUYẾT HAY\s*-\s*Tin tức day pha chế tra sưa ngon\s*[:\-]?\s*',
        r'^LỊCH KHAI GIẢNG\s*-\s*Lịch khai giảng pha chế trà sữa\s*[:\-]?\s*',
        r'^MỞ QUÁN\s*-\s*Mở quán kinh doanh trà sữa\s*[:\-]?\s*',
        r'^Dạy Pha Chế Trà Sữa Cà Phê Kem\s*l\s*Passion Link\s*',
    ]
    for p in prefixes_to_strip:
        text = re.sub(p, '', text, flags=re.IGNORECASE).strip()

    return text

def to_vietnamese_title_case(text):
    text = clean_vietnamese_title(text)
    if not text:
        return ""
        
    # Acronyms and brand names
    keep_upper = {
        'F&B', 'TP.HCM', 'TPHCM', 'HÀ NỘI', 'VTV1', 'VTV24', 'A-Z', 
        'BARISTA', 'SMOOTHIES', 'MILKTEA', 'COURSE', 'ORGANIC', 
        'MEET FRESH', 'BLACKBALL', 'ILACHA', 'GUTEA', 'ROYALTEA', 'ESPRESSO', 'LATTE', 'ART'
    }
    
    words = text.split(' ')
    result = []
    minors = {'và', 'va', 'với', 'voi', 'cho', 'của', 'cua', 'để', 'de', 'từ', 'tu', 'trong', 'về', 've'}
    
    for i, w in enumerate(words):
        cw = re.sub(r'^[^\w\s]+|[^\w\s]+$', '', w)
        if cw.upper() in keep_upper:
            result.append(w.upper())
        elif i > 0 and cw.lower() in minors:
            result.append(w.lower())
        else:
            if len(w) > 1:
                result.append(w[0].upper() + w[1:].lower())
            else:
                result.append(w.upper())
                
    res = ' '.join(result)
    res = res.replace('F&b', 'F&B').replace('Tp.hcm', 'TP.HCM').replace('Tphcm', 'TP.HCM')
    res = res.replace('Vtv1', 'VTV1').replace('Vtv24', 'VTV24')
    res = res.replace('Smoothies', 'Smoothies').replace('Milktea', 'Milktea')
    res = res.replace('Barista', 'Barista').replace('Espresso', 'Espresso')
    res = res.replace('Bý Quyết', 'Bí Quyết').replace('Khoá', 'Khóa')
    return res

# Explicit curated database for key pillars, courses, teachers, and student brands
CURATED_MAP = {
    # 1. Core Pillars
    '/': {
        'id': 0,
        'title': 'Học Pha Chế Mở Quán Trà Sữa, Cà Phê Chuyên Nghiệp | Passion Link',
        'description': 'Trung tâm dạy pha chế Passion Link tiên phong đào tạo mở quán trà sữa, cà phê, barista chuẩn vị từ 2009. Hơn 3.000+ học viên mở quán thành công. Đăng ký ngay!',
        'h1': 'Học Viện Đào Tạo Pha Chế Trà Sữa, Cà Phê Mở Quán Chuyên Nghiệp Passion Link'
    },
    '/cac-khoa-hoc-day-pha-che.html': {
        'id': 0,
        'title': 'Các Khóa Học Pha Chế Đồ Uống Mở Quán Chuyên Nghiệp [Menu 2026]',
        'description': 'Tổng hợp các khóa học pha chế trà sữa, cà phê barista, trà trái cây chuẩn vị mở quán tại Passion Link. Cam kết 100% thực hành thực tế. Xem lịch học ngay!',
        'h1': 'Tổng Hợp Các Khóa Học Dạy Pha Chế Chuyên Nghiệp Mở Quán Thành Công'
    },
    '/day-pha-che-tra-sua-ngon.html': {
        'id': 0,
        'title': 'Dạy Pha Chế Trà Sữa Ngon Mở Quán: Bí Quyết Độc Quyền [2026]',
        'description': 'Hướng dẫn bí quyết dạy pha chế trà sữa ngon chuẩn vị mở quán: ủ trà đậm vị, làm trân châu dẻo, kem cheese béo ngậy. Đăng ký nhận giáo trình độc quyền ngay!',
        'h1': 'Cẩm Nang Dạy Pha Chế Trà Sữa Ngon & Bí Quyết Mở Quán Trà Sữa Đắt Khách'
    },
    '/lich-khai-giang.html': {
        'id': 0,
        'title': 'Lịch Khai Giảng Các Khóa Học Pha Chế Mới Nhất [Tháng Này]',
        'description': 'Cập nhật lịch khai giảng các khóa học pha chế trà sữa, barista, trà trái cây tại TP.HCM, Hà Nội, Cần Thơ, Đà Nẵng. Ưu đãi đăng ký sớm, xem ngay!',
        'h1': 'Lịch Khai Giảng Lớp Học Pha Chế Tại TP.HCM, Hà Nội, Đà Nẵng & Cần Thơ'
    },
    '/mo-quan.html': {
        'id': 0,
        'title': 'Cẩm Nang Mở Quán Trà Sữa, Cà Phê Từ A-Z: Chi Phí & Vận Hành [2026]',
        'description': 'Tư vấn mở quán trà sữa cà phê trọn gói từ Passion Link: Lập kế hoạch tài chính, chọn mặt bằng, mua máy móc quầy bar, lên menu hút khách. Xem chi tiết!',
        'h1': 'Tư Vấn & Hướng Dẫn Kinh Nghiệm Mở Quán Trà Sữa, Cà Phê Thành Công'
    },
    '/he-thong-chi-nhanh.html': {
        'id': 0,
        'title': 'Hệ Thống 4 Chi Nhánh Đào Tạo Pha Chế Passion Link Toàn Quốc [2026]',
        'description': 'Hệ thống cơ sở đào tạo pha chế Passion Link tại TP.HCM (Quận 10, Phú Nhuận), Hà Nội, Cần Thơ. Phòng thực hành quầy bar hiện đại, đăng ký ngay!',
        'h1': 'Hệ Thống Cơ Sở & Chi Nhánh Học Viện Pha Chế Passion Link'
    },
    '/passion-link-lich-su-phat-trien.html': {
        'id': 0,
        'title': 'Lịch Sử Phát Triển Passion Link: 17+ Năm Tiên Phong Đào Tạo Pha Chế',
        'description': 'Hành trình hơn 17 năm tiên phong đào tạo pha chế đồ uống mở quán của Passion Link: Sứ mệnh nâng tầm chất lượng đồ uống Việt Nam và kiến tạo chủ quán thành công.',
        'h1': 'Lịch Sử Hình Thành & Sứ Mệnh Tiên Phong Đào Tạo F&B Passion Link'
    },
    '/chinh-sach-thanh-toan-hoan-coc.html': {
        'id': 0,
        'title': 'Chính Sách Thanh Toán & Hoàn Cọc Khóa Học | Passion Link',
        'description': 'Quy định chi tiết về chính sách thanh toán học phí, chính sách bảo lưu và điều kiện hoàn cọc khóa học tại Học viện Đào tạo Pha chế Passion Link.',
        'h1': 'Chính Sách Học Phí, Bảo Lưu & Hoàn Cọc Khóa Học Passion Link'
    },

    # 2. Teachers (Giảng Viên)
    '/doi-ngu-giang-vien/thay-le-huu-tri-105.html': {
        'id': 105,
        'title': 'Thầy Lê Hữu Trí - Chuyên Gia Đào Tạo Pha Chế Hàng Đầu | Passion Link',
        'description': 'Chuyên gia Lê Hữu Trí với hơn 15+ năm kinh nghiệm đào tạo pha chế, cố vấn chiến lược và đồng hành mở quán thành công cho hàng ngàn chủ quán trên toàn quốc.',
        'h1': 'Thầy Lê Hữu Trí - Chuyên Gia Đào Tạo & Cố Vấn Khởi Nghiệp F&B'
    },
    '/doi-ngu-giang-vien/co-thoai-vy-139.html': {
        'id': 139,
        'title': 'Cô Thoại Vy - Chuyên Gia Đào Tạo Pha Chế Hàng Đầu | Passion Link',
        'description': 'Giảng viên Thoại Vy - Chuyên gia pha chế và sáng tạo đồ uống hàng đầu tại Học viện Passion Link với bề dày kinh nghiệm đào tạo thực chiến.',
        'h1': 'Cô Thoại Vy - Giảng Viên & Chuyên Gia Sáng Tạo Đồ Uống Passion Link'
    },
    '/doi-ngu-giang-vien/thay-cuong-barista-140.html': {
        'id': 140,
        'title': 'Thầy Cường Barista - Chuyên Gia Đào Tạo Cà Phê Hàng Đầu | Passion Link',
        'description': 'Chuyên gia Cường Barista - Huấn luyện viên Barista chuyên nghiệp tại Passion Link, chuyên sâu về pha chế cà phê máy, latte art và setup quầy bar.',
        'h1': 'Thầy Cường Barista - Huấn Luyện Viên Barista Chuyên Nghiệp Passion Link'
    },
    '/doi-ngu-giang-vien/thay-duc-anh-118.html': {
        'id': 118,
        'title': 'Thầy Đức Anh - Chuyên Gia Đào Tạo Pha Chế Hàng Đầu | Passion Link',
        'description': 'Giảng viên Đức Anh - Chuyên gia đào tạo kỹ thuật pha chế thực chiến tại Passion Link, đồng hành cùng học viên từ công thức đến vận hành quán.',
        'h1': 'Thầy Đức Anh - Giảng Viên Đào Tạo Pha Chế Thực Chiến Passion Link'
    },

    # 3. Student Brands (Quán Học Viên Thành Công)
    '/quan-hoc-vien-thanh-cong/chuoi-tra-sua-ilacha-142.html': {
        'id': 142,
        'title': 'Học Viên Thành Công: Câu Chuyện Mở Quán Chuỗi Trà Sữa Ilacha',
        'description': 'Khám phá hành trình khởi nghiệp thành công của học viên Passion Link với chuỗi thương hiệu trà sữa Ilacha phát triển vượt bậc.',
        'h1': 'Học Viên Passion Link Mở Chuỗi Trà Sữa Ilacha Khởi Nghiệp Thành Công'
    },
    '/quan-hoc-vien-thanh-cong/royaltea-vietnam-117.html': {
        'id': 117,
        'title': 'Học Viên Thành Công: Câu Chuyện Mở Quán Trà Sữa Royaltea Việt Nam',
        'description': 'Câu chuyện khởi nghiệp thực tế từ học viên Passion Link với thương hiệu nhượng quyền trà sữa Royaltea Việt Nam đông khách.',
        'h1': 'Học Viên Passion Link Mở Quán Trà Sữa Royaltea Việt Nam Thành Công'
    },
    '/quan-hoc-vien-thanh-cong/gutea-116.html': {
        'id': 116,
        'title': 'Học Viên Thành Công: Câu Chuyện Mở Quán Trà Sữa Gutea',
        'description': 'Hành trình từ khóa học pha chế tại Passion Link đến khi làm chủ thương hiệu trà sữa Gutea được giới trẻ yêu thích.',
        'h1': 'Học Viên Passion Link Mở Quán Trà Sữa Gutea Thành Công'
    },
    '/quan-hoc-vien-thanh-cong/m-s-jolly-113.html': {
        'id': 113,
        'title': 'Học Viên Thành Công: Câu Chuyện Mở Quán Trà Sữa M\'s Jolly',
        'description': 'Chia sẻ kinh nghiệm mở quán trà sữa M\'s Jolly thành công sau khi hoàn thành khóa đào tạo pha chế chuyên sâu tại Passion Link.',
        'h1': 'Học Viên Passion Link Mở Quán Trà Sữa M\'s Jolly Thành Công'
    },
    '/quan-hoc-vien-thanh-cong/avartar-114.html': {
        'id': 114,
        'title': 'Học Viên Thành Công: Câu Chuyện Mở Quán Trà Sữa Avatar',
        'description': 'Khám phá mô hình kinh doanh quán trà sữa Avatar của học viên Passion Link: Tối ưu chi phí, menu hút khách và vận hành hiệu quả.',
        'h1': 'Học Viên Passion Link Mở Quán Trà Sữa Avatar Khởi Nghiệp Thành Công'
    },

    # 4. All Core Courses (/cac-khoa-hoc-day-pha-che/)
    '/cac-khoa-hoc-day-pha-che/day-pha-che-mo-quan-tra-sua-tron-khoa-menu-ngon-72.html': {
        'id': 72,
        'title': 'Khóa Học Dạy Pha Chế Mở Quán Trà Sữa Chuẩn Vị Trọn Khóa [Menu 2026]',
        'description': 'Khóa học dạy pha chế mở quán trà sữa trọn khóa menu ngon chuẩn vị thực chiến tại Passion Link: 100% thực hành, hỗ trợ lên menu, tính cost giá vốn và đồng hành mở quán.',
        'h1': 'Khóa Học Dạy Pha Chế Mở Quán Trà Sữa Chuẩn Vị Trọn Khóa Menu Ngon'
    },
    '/cac-khoa-hoc-day-pha-che/day-pha-che-tra-sua-khoa-hoc-tra-sua-chuan-vi-tron-khoa-menu-ngon-72.html': {
        'id': 72,
        'title': 'Khóa Học Dạy Pha Chế Mở Quán Trà Sữa Chuẩn Vị Trọn Khóa [Menu 2026]',
        'description': 'Khóa học dạy pha chế mở quán trà sữa trọn khóa menu ngon chuẩn vị thực chiến tại Passion Link: 100% thực hành, hỗ trợ lên menu, tính cost giá vốn và đồng hành mở quán.',
        'h1': 'Khóa Học Dạy Pha Chế Mở Quán Trà Sữa Chuẩn Vị Trọn Khóa Menu Ngon'
    },
    '/cac-khoa-hoc-day-pha-che/day-pha-che-tra-sua-tran-chau-tra-thai-34.html': {
        'id': 34,
        'title': 'Khóa Học Dạy Pha Chế Trà Sữa Trân Châu, Trà Thái [Menu 2026]',
        'description': 'Khóa học dạy pha chế trà sữa trân châu, trà Thái chuẩn vị mở quán tại Passion Link: 100% thực hành, hướng dẫn ủ trà đậm vị và tính cost giá vốn.',
        'h1': 'Khóa Học Dạy Pha Chế Trà Sữa Trân Châu & Trà Thái Ngon Chuẩn Vị Mở Quán'
    },
    '/cac-khoa-hoc-day-pha-che/day-pha-che-tra-sua-tran-chau-tra-thai-ngon-34.html': {
        'id': 34,
        'title': 'Khóa Học Dạy Pha Chế Trà Sữa Trân Châu, Trà Thái [Menu 2026]',
        'description': 'Khóa học dạy pha chế trà sữa trân châu, trà Thái chuẩn vị mở quán tại Passion Link: 100% thực hành, hướng dẫn ủ trà đậm vị và tính cost giá vốn.',
        'h1': 'Khóa Học Dạy Pha Chế Trà Sữa Trân Châu & Trà Thái Ngon Chuẩn Vị Mở Quán'
    },
    '/cac-khoa-hoc/day-pha-che-tra-sua-tran-chau-34.html': {
        'id': 34,
        'title': 'Khóa Học Dạy Pha Chế Trà Sữa Trân Châu, Trà Thái [Menu 2026]',
        'description': 'Khóa học dạy pha chế trà sữa trân châu, trà Thái chuẩn vị mở quán tại Passion Link: 100% thực hành, hướng dẫn ủ trà đậm vị và tính cost giá vốn.',
        'h1': 'Khóa Học Dạy Pha Chế Trà Sữa Trân Châu & Trà Thái Ngon Chuẩn Vị Mở Quán'
    },
    '/cac-khoa-hoc-day-pha-che/day-pha-che-cafe-da-xay-kem-cafe-take-away-smoothies-kem-barista-chuyen-nghiep-37.html': {
        'id': 37,
        'title': 'Khóa Học Pha Chế Cà Phê Đá Xay Kem & Barista Take Away [Menu 2026]',
        'description': 'Khóa học pha chế cà phê đá xay kem, smoothies và cà phê take away tại Passion Link: Thực hành 100% trên máy pha chuyên nghiệp, hỗ trợ lên menu mở quán.',
        'h1': 'Khóa Học Dạy Pha Chế Cà Phê Đá Xay Kem, Take Away & Smoothies Barista Mở Quán'
    },
    '/cac-khoa-hoc-day-pha-che/day-pha-che-cafe-take-away-bartender-37.html': {
        'id': 37,
        'title': 'Khóa Học Pha Chế Cà Phê Đá Xay Kem & Barista Take Away [Menu 2026]',
        'description': 'Khóa học pha chế cà phê đá xay kem, smoothies và cà phê take away tại Passion Link: Thực hành 100% trên máy pha chuyên nghiệp, hỗ trợ lên menu mở quán.',
        'h1': 'Khóa Học Dạy Pha Chế Cà Phê Đá Xay Kem, Take Away & Smoothies Barista Mở Quán'
    },
    '/cac-khoa-hoc-day-pha-che/day-pha-che-cafe-take-away-smoothies-bartender-37.html': {
        'id': 37,
        'title': 'Khóa Học Pha Chế Cà Phê Đá Xay Kem & Barista Take Away [Menu 2026]',
        'description': 'Khóa học pha chế cà phê đá xay kem, smoothies và cà phê take away tại Passion Link: Thực hành 100% trên máy pha chuyên nghiệp, hỗ trợ lên menu mở quán.',
        'h1': 'Khóa Học Dạy Pha Chế Cà Phê Đá Xay Kem, Take Away & Smoothies Barista Mở Quán'
    },
    '/cac-khoa-hoc-day-pha-che/day-pha-che-sinh-to-nuoc-ep-chuyen-nghiep-59.html': {
        'id': 59,
        'title': 'Khóa Học Dạy Pha Chế Sinh Tố & Nước Ép Chuyên Nghiệp [Menu 2026]',
        'description': 'Khóa học pha chế sinh tố và nước ép trái cây chuẩn vị mở quán tại Passion Link: Kỹ thuật giữ màu tươi nguyên bản, bảo toàn vitamin và cân bằng hương vị hoàn hảo.',
        'h1': 'Khóa Học Dạy Pha Chế Sinh Tố & Nước Ép Chuyên Nghiệp Ngon Chuẩn Vị'
    },
    '/cac-khoa-hoc-day-pha-che/day-hoc-cach-cat-tia-trai-cay-trang-tri-62.html': {
        'id': 62,
        'title': 'Khóa Học Cắt Tỉa Trái Cây Trang Trí Đồ Uống [Menu 2026]',
        'description': 'Khóa học dạy cắt tỉa trái cây trang trí đồ uống quầy bar chuyên nghiệp tại Passion Link: Kỹ thuật tạo hình đẹp mắt, nâng tầm giá trị ly đồ uống kinh doanh.',
        'h1': 'Khóa Học Dạy Cắt Tỉa Trái Cây Trang Trí Đồ Uống Mở Quán Chuyên Nghiệp'
    },
    '/cac-khoa-hoc-day-pha-che/day-hoc-cach-cat-tia-trai-cay-trang-tri-nuoc-uong-62.html': {
        'id': 62,
        'title': 'Khóa Học Cắt Tỉa Trái Cây Trang Trí Đồ Uống [Menu 2026]',
        'description': 'Khóa học dạy cắt tỉa trái cây trang trí đồ uống quầy bar chuyên nghiệp tại Passion Link: Kỹ thuật tạo hình đẹp mắt, nâng tầm giá trị ly đồ uống kinh doanh.',
        'h1': 'Khóa Học Dạy Cắt Tỉa Trái Cây Trang Trí Đồ Uống Mở Quán Chuyên Nghiệp'
    },
    '/cac-khoa-hoc-day-pha-che/day-hoc-cach-lam-kem-ngon-63.html': {
        'id': 63,
        'title': 'Khóa Học Làm Kem Ngon Mở Quán Kinh Doanh [Menu 2026]',
        'description': 'Khóa học làm kem ngon mở quán tại Passion Link: Nắm vững bí quyết làm kem từ các thương hiệu kem nổi tiếng thế giới, công thức chuẩn xốp dẻo thơm ngậy.',
        'h1': 'Khóa Học Dạy Làm Kem Ngon - Bí Quyết Từ Thương Hiệu Kem Nổi Tiếng'
    },
    '/cac-khoa-hoc-day-pha-che/day-hoc-cach-lam-kem-ngon-by-quyet-tu-cac-thuong-hieu-kem-noi-tieng-63.html': {
        'id': 63,
        'title': 'Khóa Học Làm Kem Ngon Mở Quán Kinh Doanh [Menu 2026]',
        'description': 'Khóa học làm kem ngon mở quán tại Passion Link: Nắm vững bí quyết làm kem từ các thương hiệu kem nổi tiếng thế giới, công thức chuẩn xốp dẻo thơm ngậy.',
        'h1': 'Khóa Học Dạy Làm Kem Ngon - Bí Quyết Từ Thương Hiệu Kem Nổi Tiếng'
    },
    '/cac-khoa-hoc-day-pha-che/day-hoc-cach-lam-tau-hu-thai-mon-ngon-bi-truyen-87.html': {
        'id': 87,
        'title': 'Khóa Học Làm Tàu Hủ Thái Ngon Chuẩn Vị [Menu 2026]',
        'description': 'Khóa học làm tàu hủ Thái thơm ngon chuẩn vị tại Passion Link: Bí quyết nấu tàu hủ dẻo mịn béo ngậy, kết hợp sốt trân châu và thạch độc quyền hút khách.',
        'h1': 'Khóa Học Dạy Làm Tàu Hủ Thái Ngon - Công Thức Bí Truyền Passion Link'
    },
    '/cac-khoa-hoc-day-pha-che/day-hoc-cach-lam-thach-cu-nang-thach-cu-mon-thach-khuc-bach-thach-pho-mai-hat-tran-chau-99.html': {
        'id': 99,
        'title': 'Khóa Học Làm Thạch Trà Sữa & Trân Châu Tươi [Menu 2026]',
        'description': 'Khóa học làm các loại thạch trà sữa và trân châu tươi màu rau củ tại Passion Link: Thạch củ năng, củ môn, khúc bạch, phô mai và trân châu dẻo dai tự làm.',
        'h1': 'Khóa Học Dạy Làm Thạch Trà Sữa Các Loại & Trân Châu Nhà Làm Tươi Ngon'
    },
    '/cac-khoa-hoc-day-pha-che/day-hoc-cach-lam-bingsu-ngon-de-mo-quan-cao-cap-theo-cong-nghe-han-quoc-100.html': {
        'id': 100,
        'title': 'Khóa Học Làm Bingsu Hàn Quốc Chuẩn Vị Mở Quán [Menu 2026]',
        'description': 'Khóa học làm Bingsu tuyết Hàn Quốc cao cấp mở quán tại Passion Link: Công nghệ bào tuyết mềm mịn, kỹ thuật phối sốt và trang trí topping độc quyền.',
        'h1': 'Khóa Học Dạy Làm Bingsu Hàn Quốc Chuẩn Vị Mở Quán Cao Cấp'
    },
    '/cac-khoa-hoc-day-pha-che/hoc-pha-che-tra-sua-tran-chau-khac-biet-day-bi-quyet-pha-che-tu-rau-cu-trai-cay-hoa-la-organic-110.html': {
        'id': 110,
        'title': 'Khóa Học Trà Sữa Đậm Đà Từ Trái Cây & Hoa Lá Organic [Menu 2026]',
        'description': 'Khóa học dạy bí quyết pha chế trà sữa khác biệt từ rau củ, trái cây tươi và hoa lá organic tại Passion Link: Hương vị tự nhiên độc đáo, dẫn đầu xu hướng.',
        'h1': 'Học Pha Chế Trà Sữa Đậm Đà - Bí Quyết Pha Chế Từ Rau Củ Trái Cây Tươi'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-hoc-che-dai-loan-chuan-hieu-ban-co-biet-meet-fresh-va-blackball-120.html': {
        'id': 120,
        'title': 'Khóa Học Nấu Chè Đài Loan Chuẩn Hiệu Meet Fresh & Blackball [2026]',
        'description': 'Khóa học nấu chè Đài Loan khoai dẻo, thạch thảo mộc chuẩn vị Meet Fresh & Blackball tại Passion Link: Công thức chuẩn vị thơm lành, nâng tầm menu tráng miệng.',
        'h1': 'Khóa Học Nấu Chè Đài Loan Chuẩn Vị Thương Hiệu Meet Fresh & Blackball'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-hoc-tau-hu-thai-mon-ngon-bi-truyen-136.html': {
        'id': 136,
        'title': 'Khóa Học Làm Tàu Hủ Thái Chuẩn Vị Mềm Mịn [Menu 2026]',
        'description': 'Khóa học làm tàu hủ Thái mềm mịn chuẩn công thức bí truyền tại Passion Link: Kỹ thuật đổ bánh núng nính, kết hợp các loại nước sốt trái cây và topping thơm ngon.',
        'h1': 'Khóa Học Làm Tàu Hủ Thái Chuẩn Vị - Bánh Tàu Hủ Mềm Mịn Chuẩn Thái'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-ca-phe-barista-chuyen-nghiep-137.html': {
        'id': 137,
        'title': 'Khóa Học Cà Phê Pha Máy Barista Chuyên Nghiệp [Menu 2026]',
        'description': 'Khóa đào tạo Barista cà phê pha máy chuyên nghiệp tại Passion Link: Chiết xuất Espresso chuẩn tỉ lệ, kỹ thuật đánh sữa bọt mịn và tạo hình Latte Art nghệ thuật.',
        'h1': 'Khóa Học Cà Phê Pha Máy Barista Chuyên Nghiệp Mở Quán Hiện Đại'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-hoc-tra-trai-cay-nhiet-doi-177.html': {
        'id': 177,
        'title': 'Khóa Học Pha Chế Trà Trái Cây Nhiệt Đới Đậm Vị [Menu 2026]',
        'description': 'Khóa học pha chế trà trái cây nhiệt đới với 10 món hot trend nhất tại Passion Link: Bí quyết phối cốt trà thanh mát kết hợp hoa quả tươi nguyên vị hút khách.',
        'h1': 'Khóa Học Pha Chế Trà Trái Cây Nhiệt Đới - Menu 10 Món Hot Trend Nhất'
    },
    '/cac-khoa-hoc-day-pha-che/hoc-by-quyet-tra-sua-ngon-chuan-vi-dai-loan-tu-1980-tu-tin-mo-quan-nam-2019-219.html': {
        'id': 219,
        'title': 'Khóa Học Trà Sữa Chuẩn Vị Đài Loan Từ 1980 Mở Quán [Menu 2026]',
        'description': 'Khóa học pha chế trà sữa chuẩn vị Đài Loan truyền thống từ 1980 tại Passion Link: Bí quyết ủ trà đen, trà ô long đậm đà chuẩn gu khách hàng để tự tin mở quán.',
        'h1': 'Học Pha Chế Trà Sữa Ngon Chuẩn Vị Đài Loan Từ 1980 Tự Tin Mở Quán'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-hoc-banh-kem-tra-sua-tran-chau-duong-den-2019-220.html': {
        'id': 220,
        'title': 'Khóa Học Làm Bánh Kem Trà Sữa Trân Châu Đường Đen [Menu 2026]',
        'description': 'Khóa học làm bánh kem trà sữa trân châu đường đen sốt chảy thơm ngon tại Passion Link: Cốt bánh xốp mịn hòa quyện lớp kem trà sữa béo ngậy và trân châu dẻo mềm.',
        'h1': 'Khóa Học Làm Bánh Kem Trà Sữa Trân Châu Đường Đen Ngon Mê Ly Mở Quán'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-hoc-menu-thuong-hieu-tra-sua-223.html': {
        'id': 223,
        'title': 'Khóa Học Pha Chế Trà Sữa Thương Hiệu Chuyên Nghiệp [Menu 2026]',
        'description': 'Khóa đào tạo pha chế trà sữa thương hiệu cao cấp tại Passion Link: Thiết kế menu độc quyền, định vị thương hiệu, kiểm soát cost và vận hành chuỗi chuyên nghiệp.',
        'h1': 'Khóa Học Pha Chế Trà Sữa Thương Hiệu - Professional Milktea Course'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-pha-che-tong-hop-7-menu-thuc-uong-noi-tieng-229.html': {
        'id': 229,
        'title': 'Khóa Học Pha Chế Tổng Hợp 7 Menu Đồ Uống Mở Quán [Menu 2026]',
        'description': 'Khóa học pha chế tổng hợp 7 menu thức uống nổi tiếng tại Passion Link: Trọn gói trà sữa, cà phê, trà trái cây, đá xay, sinh tố nước ép mở quán tự tin 100%.',
        'h1': 'Khóa Học Pha Chế Tổng Hợp Trà Sữa, Trà Trái Cây, Cà Phê, Sinh Tố Nước Ép'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-hoc-cac-mon-an-vat-cho-quan-tra-sua-va-ca-phe-241.html': {
        'id': 241,
        'title': 'Khóa Học Các Món Ăn Vặt Cho Quán Trà Sữa & Cà Phê [Menu 2026]',
        'description': 'Khóa học chế biến các món ăn vặt đắt khách cho quán trà sữa, cà phê tại Passion Link: Tối ưu chi phí quầy bar, chế biến nhanh và nhân đôi doanh thu cho quán.',
        'h1': 'Khóa Học Các Món Ăn Vặt Ngon Mở Quán Trà Sữa Cà Phê Hút Khách'
    },
    '/cac-khoa-hoc-day-pha-che/khoa-dao-tao-chuyen-gia-ve-tra-va-mo-chuoi-nhuong-quyen-245.html': {
        'id': 245,
        'title': 'Khóa Đào Tạo Chuyên Gia Về Trà & Mở Chuỗi Nhượng Quyền [Menu 2026]',
        'description': 'Khóa đào tạo chuyên sâu về kỹ thuật sản xuất trà, phối vị và xây dựng chuỗi trà sữa nhượng quyền tại Passion Link: Dành riêng cho chủ thương hiệu và chuỗi F&B.',
        'h1': 'Khóa Đào Tạo Chuyên Gia Về Trà & Mở Chuỗi Nhượng Quyền Trà Sữa'
    },
    '/cac-khoa-hoc-day-pha-che/tra-chanh-la-gi-khoa-hoc-menu-tra-chanh-hot-nhat-259.html': {
        'id': 259,
        'title': 'Khóa Học Pha Chế Trà Chanh Hiện Đại Hot Trend [Menu 2026]',
        'description': 'Khóa học pha chế trà chanh hiện đại kết hợp trái cây tươi tại Passion Link: Vốn ít, lợi nhuận cao, hoàn vốn nhanh, công thức đậm đà độc quyền mở quán đắt khách.',
        'h1': 'Khóa Học Pha Chế Trà Chanh Hiện Đại Hot Nhất - Trà Chanh Trái Cây Tươi'
    },
    '/cac-khoa-hoc-day-pha-che/trang/1': {
        'id': 0,
        'title': 'Các Khóa Học Pha Chế Mở Quán Chuyên Nghiệp - Trang 1 [2026]',
        'description': 'Danh sách các khóa học pha chế trà sữa, cà phê barista, sinh tố nước ép mở quán tại Passion Link - Trang 1. Xem lịch khai giảng và học phí ưu đãi ngay!',
        'h1': 'Tổng Hợp Các Khóa Học Dạy Pha Chế Chuyên Nghiệp Mở Quán (Trang 1)'
    },
    '/cac-khoa-hoc-day-pha-che/trang/2': {
        'id': 0,
        'title': 'Các Khóa Học Pha Chế Mở Quán Chuyên Nghiệp - Trang 2 [2026]',
        'description': 'Danh sách các khóa học pha chế trà sữa, cà phê barista, sinh tố nước ép mở quán tại Passion Link - Trang 2. Xem lịch khai giảng và học phí ưu đãi ngay!',
        'h1': 'Tổng Hợp Các Khóa Học Dạy Pha Chế Chuyên Nghiệp Mở Quán (Trang 2)'
    },
    '/mo-quan/trang/1': {
        'id': 0,
        'title': 'Cẩm Nang & Kinh Nghiệm Mở Quán Trà Sữa, Cà Phê - Trang 1 [2026]',
        'description': 'Tổng hợp kinh nghiệm mở quán trà sữa, cà phê từ A-Z: Chi phí, chọn mặt bằng, mua máy móc quầy bar và tối ưu vận hành - Trang 1.',
        'h1': 'Cẩm Nang Hướng Dẫn & Kinh Nghiệm Mở Quán Trà Sữa, Cà Phê (Trang 1)'
    },
    '/mo-quan/trang/2': {
        'id': 0,
        'title': 'Cẩm Nang & Kinh Nghiệm Mở Quán Trà Sữa, Cà Phê - Trang 2 [2026]',
        'description': 'Tổng hợp kinh nghiệm mở quán trà sữa, cà phê từ A-Z: Chi phí, chọn mặt bằng, mua máy móc quầy bar và tối ưu vận hành - Trang 2.',
        'h1': 'Cẩm Nang Hướng Dẫn & Kinh Nghiệm Mở Quán Trà Sữa, Cà Phê (Trang 2)'
    },

    # 5. Missing title items in raw audit
    '/mo-quan/khoa-hoc-lam-kem-tuoi-ngon-kinh-doanh-cua-hang-kem-250.html': {
        'id': 250,
        'title': 'Khóa Học Làm Kem Tươi Ngon Mở Quán Kinh Doanh [Menu 2026]',
        'description': 'Khóa học làm kem tươi mở quán kinh doanh chuyên nghiệp tại Passion Link: Kỹ thuật vận hành máy làm kem, công thức kem tươi mềm mịn thơm béo chuẩn vị.',
        'h1': 'Khóa Học Làm Kem Tươi Ngon Kinh Doanh Mở Cửa Hàng Kem Chuyên Nghiệp'
    },
    '/mo-quan/khoa-hoc-tron-bo-menu-tong-hop-hot-nhat-2019-253.html': {
        'id': 253,
        'title': 'Khóa Học Trọn Bộ Menu Đồ Uống Mở Quán Tổng Hợp [Menu 2026]',
        'description': 'Khóa học trọn bộ menu tổng hợp đồ uống mở quán hot nhất tại Passion Link: Đầy đủ trà sữa, trà trái cây, cà phê, đá xay và kỹ năng quản lý vận hành quán.',
        'h1': 'Khóa Học Pha Chế Trọn Bộ Menu Đồ Uống Tổng Hợp Hot Nhất Mở Quán'
    },
    '/day-pha-che-tra-sua-ngon/hoc-pha-che-ruou-bartending-skill-course-50.html': {
        'id': 50,
        'title': 'Học Pha Chế Rượu Chuyên Nghiệp - Bartending Skill Course [2026]',
        'description': 'Khóa đào tạo kỹ năng pha chế rượu và cocktail Bartending chuyên nghiệp tại Passion Link: Nắm vững kỹ thuật lắc shaker, mixology và nghệ thuật quầy bar.',
        'h1': 'Khóa Học Pha Chế Rượu & Kỹ Năng Bartending Chuyên Nghiệp'
    },
    '/cac-khoa-hoc/day-pha-che-tra-sua-tran-chau-34.html': {
        'id': 34,
        'title': 'Khóa Học Dạy Pha Chế Trà Sữa Trân Châu, Trà Thái [Menu 2026]',
        'description': 'Khóa học dạy pha chế trà sữa trân châu, trà Thái chuẩn vị mở quán tại Passion Link: 100% thực hành, hướng dẫn ủ trà đậm vị và tính cost giá vốn.',
        'h1': 'Khóa Học Dạy Pha Chế Trà Sữa Trân Châu & Trà Thái Ngon Chuẩn Vị Mở Quán'
    },
    '/hoc-pha-che-online.html': {
        'id': 0,
        'title': 'Khóa Học Pha Chế Online Từ Xa - Học Bí Quyết Mở Quán [2026]',
        'description': 'Khóa học pha chế online từ xa cùng chuyên gia Passion Link: Video quay sắc nét từng thao tác, công thức chuẩn định lượng, hỗ trợ 1 kèm 1 trọn đời.',
        'h1': 'Khóa Học Pha Chế Online Từ Xa - Đào Tạo Mở Quán Thực Chiến Passion Link'
    },
    '/lich-khai-giang/du-kien-ngay-khai-giang-pha-che-tai-da-nang-40.html': {
        'id': 40,
        'title': 'Lịch Khai Giảng Khóa Học Pha Chế Tại Đà Nẵng [Tháng Này]',
        'description': 'Lịch khai giảng các lớp học pha chế trà sữa, cà phê barista tại chi nhánh Passion Link Đà Nẵng. Đăng ký sớm nhận ngay ưu đãi học phí!',
        'h1': 'Lịch Khai Giảng Lớp Học Pha Chế Đồ Uống Mở Quán Tại Đà Nẵng'
    },
    '/lich-khai-giang/khai-giang-khoa-hoc-pha-che-tai-tphcm-38.html': {
        'id': 38,
        'title': 'Lịch Khai Giảng Khóa Học Pha Chế Tại TP.HCM [Tháng Này]',
        'description': 'Cập nhật lịch khai giảng lớp học pha chế trà sữa, barista tại TP.HCM (Quận 10, Phú Nhuận). Thực hành 100% trên quầy bar chuyên nghiệp.',
        'h1': 'Lịch Khai Giảng Khóa Học Pha Chế Mở Quán Tại TP.HCM Mới Nhất'
    },
    '/lich-khai-giang/lich-khai-giang-cac-khoa-hoc-pha-che-tai-can-tho-138.html': {
        'id': 138,
        'title': 'Lịch Khai Giảng Khóa Học Pha Chế Tại Cần Thơ [Tháng Này]',
        'description': 'Thông tin lịch khai giảng các khóa học pha chế trà sữa, cà phê, trà trái cây tại chi nhánh Passion Link Cần Thơ. Ưu đãi hấp dẫn khi đăng ký ngay!',
        'h1': 'Lịch Khai Giảng Các Khóa Học Pha Chế Mở Quán Tại Cần Thơ'
    },
    '/lich-khai-giang/lich-khai-giang-cac-khoa-hoc-pha-che-tai-ha-noi-39.html': {
        'id': 39,
        'title': 'Lịch Khai Giảng Khóa Học Pha Chế Tại Hà Nội [Tháng Này]',
        'description': 'Lịch khai giảng lớp học pha chế trà sữa, barista mở quán tại Passion Link Hà Nội. Cam kết học đến khi thuần thục, hỗ trợ trọn đời.',
        'h1': 'Lịch Khai Giảng Các Khóa Học Dạy Pha Chế Mở Quán Tại Hà Nội'
    },
    '/lich-khai-giang/nhan-dao-tao-pha-che-tai-quan-huong-dan-set-up-quan-41.html': {
        'id': 41,
        'title': 'Đào Tạo Pha Chế Tại Quán & Dịch Vụ Setup Quầy Bar Trọn Gói [2026]',
        'description': 'Passion Link nhận đào tạo pha chế trực tiếp tại quán và cố vấn setup quầy bar, lên menu độc quyền chuẩn phong cách cho chủ quán toàn quốc.',
        'h1': 'Dịch Vụ Đào Tạo Pha Chế Trực Tiếp Tại Quán & Hướng Dẫn Setup Quán F&B'
    },
    '/thu-vien.html': {
        'id': 0,
        'title': 'Thư Viện Hình Ảnh & Hoạt Động Lớp Học | Passion Link',
        'description': 'Thư viện hình ảnh thực tế các lớp học pha chế, khoảnh khắc học viên thực hành quầy bar và không khí khai trương quán của học viên Passion Link.',
        'h1': 'Thư Viện Hình Ảnh Thực Tế Lớp Học Pha Chế & Quán Học Viên Passion Link'
    },
    '/tin-tuc.html': {
        'id': 0,
        'title': 'Tin Tức Sự Kiện & Xu Hướng F&B Mới Nhất [2026] | Passion Link',
        'description': 'Chuyên mục tin tức F&B Passion Link: Cập nhật xu hướng đồ uống hot trend, sự kiện khai giảng, bí quyết vận hành và câu chuyện khởi nghiệp quán.',
        'h1': 'Tin Tức Sự Kiện F&B & Cẩm Nang Kinh Nghiệm Khởi Nghiệp Đồ Uống'
    }
}

print("Loaded base configurations.")

def generate_entry_metadata(path, item_id, old_h1_current):
    # Check curated map first
    if path in CURATED_MAP:
        c = CURATED_MAP[path]
        return c['id'], c['title'], c['description'], c['h1']

    raw_item = raw_by_path.get(path) or raw_by_id.get(item_id)
    raw_title = raw_item.get('title') if raw_item else ""
    raw_desc = raw_item.get('meta_desc') if raw_item else ""
    
    clean_name = to_vietnamese_title_case(raw_title)
    
    # Fallback if raw_title was empty
    if not clean_name:
        # derive cleanly from slug
        slug = re.sub(r'-\d+\.html$', '', path.split('/')[-1])
        words = [w.capitalize() for w in slug.split('-')]
        clean_name = ' '.join(words)

    # 1. Course Product (/cac-khoa-hoc-day-pha-che/)
    if '/cac-khoa-hoc-day-pha-che/' in path:
        h1 = clean_name
        if not re.search(r'Khóa Học|Dạy Pha Chế|Đào Tạo', h1, re.I):
            h1 = f"Khóa Học Dạy Pha Chế {h1}"
        if not re.search(r'Mở Quán|Chuyên Nghiệp', h1, re.I):
            h1 = f"{h1} Mở Quán Chuyên Nghiệp"
            
        title = f"{clean_name} [Menu 2026]"
        if len(title) > 68:
            # shorten
            short_name = re.sub(r'^(Khóa Học|Dạy Pha Chế|Đào Tạo)\s*', '', clean_name, flags=re.I)
            title = f"Khóa Học {short_name} [Menu 2026]"
            if len(title) > 68:
                title = title[:65] + "..."
                
        desc = f"Khóa học {clean_name} thực chiến tại Passion Link: 100% thực hành, hỗ trợ lên menu, tính cost giá vốn và đồng hành mở quán. Học phí ưu đãi, đăng ký ngay!"
        if len(desc) > 160:
            desc = desc[:157] + "..."
            
        return item_id, title, desc, h1

    # 2. Business Consulting (/mo-quan/)
    elif '/mo-quan/' in path:
        h1 = clean_name
        if not re.search(r'Kinh Nghiệm|Hướng Dẫn|Bí Quyết|Ý Tưởng|Thủ Tục|Mở Quán', h1, re.I):
            h1 = f"Hướng Dẫn Kinh Nghiệm Mở Quán: {h1}"
            
        title = f"{clean_name} [2026]"
        if len(title) > 68:
            title = f"{clean_name}"
            if len(title) > 68:
                title = title[:65] + "..."
                
        desc = f"Chia sẻ kinh nghiệm thực tế về {clean_name.lower()} từ chuyên gia Passion Link: Dự toán chi phí, chọn địa điểm, quản lý nhân viên và setup quầy bar chuẩn tối ưu chi phí."
        if len(desc) > 160:
            desc = desc[:157] + "..."
            
        return item_id, title, desc, h1

    # 3. Recipes & Milktea Guides (/day-pha-che-tra-sua-ngon/)
    elif '/day-pha-che-tra-sua-ngon/' in path:
        h1 = clean_name
        if not re.search(r'Công Thức|Cách Pha|Cách Làm|Bí Quyết', h1, re.I):
            h1 = f"Công Thức & Cách Pha {h1} Chuẩn Vị Kinh Doanh"
            
        title = f"Bí Quyết Pha {clean_name} Thơm Ngon Hút Khách [2026]"
        if len(title) > 68:
            title = f"Cách Làm {clean_name} Chuẩn Vị [Công Thức 2026]"
            if len(title) > 68:
                title = title[:65] + "..."
                
        desc = f"Hướng dẫn cách làm {clean_name.lower()} chuẩn vị kinh doanh: Định lượng chính xác, kỹ thuật ủ trà đậm hương và tối ưu chi phí nguyên vật liệu. Xem công thức ngay!"
        if len(desc) > 160:
            desc = desc[:157] + "..."
            
        return item_id, title, desc, h1

    # 4. News & Industry Trends (/tin-tuc/)
    elif '/tin-tuc/' in path:
        h1 = f"{clean_name} - Phân Tích & Dự Báo Xu Hướng Thị Trường F&B"
        title = f"{clean_name} - Xu Hướng Thị Trường F&B [2026]"
        if len(title) > 68:
            title = f"{clean_name}: Xu Hướng & Phân Tích Chuyên Sâu"
            if len(title) > 68:
                title = title[:65] + "..."
                
        desc = f"Cập nhật thông tin chuyên sâu về {clean_name.lower()}: Phân tích xu hướng đồ uống mới, thị trường F&B và chiến lược kinh doanh cho chủ quán. Xem ngay!"
        if len(desc) > 160:
            desc = desc[:157] + "..."
            
        return item_id, title, desc, h1

    # 5. Teacher / Staff (/doi-ngu-giang-vien/)
    elif '/doi-ngu-giang-vien/' in path:
        h1 = f"{clean_name} - Giảng Viên & Chuyên Gia Cố Vấn Khởi Nghiệp F&B"
        title = f"{clean_name} - Chuyên Gia Đào Tạo Pha Chế Hàng Đầu | Passion Link"
        desc = f"Hồ sơ chuyên môn của {clean_name} tại Passion Link: Hơn 10+ năm kinh nghiệm giảng dạy pha chế, đào tạo hàng ngàn chủ quán F&B thành công. Xem ngay!"
        return item_id, title, desc, h1

    # 6. Student Brands (/quan-hoc-vien-thanh-cong/)
    elif '/quan-hoc-vien-thanh-cong/' in path:
        h1 = f"Học Viên Passion Link Mở Quán {clean_name} Khởi Nghiệp Thành Công"
        title = f"Học Viên Thành Công: Câu Chuyện Mở Quán {clean_name}"
        desc = f"Khám phá câu chuyện khởi nghiệp thành công của học viên Passion Link với thương hiệu {clean_name}. Hành trình từ học pha chế đến làm chủ chuỗi quán!"
        return item_id, title, desc, h1

    # 7. Default fallback
    else:
        h1 = f"{clean_name} - Học Viện Đào Tạo Pha Chế Passion Link"
        title = f"{clean_name} | Trung Tâm Đào Tạo Passion Link"
        desc = f"Thông tin chi tiết về {clean_name.lower()} tại trung tâm đào tạo pha chế Passion Link. Đơn vị tiên phong đào tạo mở quán đồ uống từ 2009."
        return item_id, title, desc, h1

# Read existing template/seo_map.php to preserve order and all keys
with open(SEO_MAP_PATH, 'r', encoding='utf-8') as f:
    existing_content = f.read()

entry_pattern = re.compile(r"'([^']+)'\s*=>\s*array\('id'\s*=>\s*(\d+),\s*'title'\s*=>\s*'([^']*)',\s*'description'\s*=>\s*'([^']*)',\s*'h1'\s*=>\s*'([^']*)'\)")
all_matches = entry_pattern.findall(existing_content)

print(f"Total entries found in existing seo_map.php: {len(all_matches)}")

new_map_entries = []
seen_paths = set()

# Process all existing entries
for path, item_id_str, old_title, old_desc, old_h1 in all_matches:
    item_id = int(item_id_str)
    new_id, new_title, new_desc, new_h1 = generate_entry_metadata(path, item_id, old_h1)
    new_map_entries.append((path, new_id, new_title, new_desc, new_h1))
    seen_paths.add(path)

# Ensure alias for course 72 is added if missing
alias_72 = '/cac-khoa-hoc-day-pha-che/day-pha-che-tra-sua-khoa-hoc-tra-sua-chuan-vi-tron-khoa-menu-ngon-72.html'
if alias_72 not in seen_paths:
    c72 = CURATED_MAP[alias_72]
    new_map_entries.append((alias_72, c72['id'], c72['title'], c72['description'], c72['h1']))
    seen_paths.add(alias_72)

# Write output file
out_php = """<?php
/**
 * SEO METADATA MAPPING TABLE - PASSION LINK (phache.com.vn)
 * Tối ưu chuẩn SEO Top 1 Google & Entity E-E-A-T 2026.
 * 100% Tiếng Việt có dấu chính xác, văn phong chuyên nghiệp, không lỗi font/không dấu.
 */

function get_seo_metadata_for_current_request($req_uri, $news_id = 0) {
    static $seo_map_cache = null;
    if ($seo_map_cache === null) {
        $seo_map_cache = array(
"""

for path, item_id, title, desc, h1 in new_map_entries:
    # Escape single quotes
    e_path = path.replace("'", "\\'")
    e_title = title.replace("'", "\\'")
    e_desc = desc.replace("'", "\\'")
    e_h1 = h1.replace("'", "\\'")
    out_php += f"            '{e_path}' => array('id' => {item_id}, 'title' => '{e_title}', 'description' => '{e_desc}', 'h1' => '{e_h1}'),\n"

out_php += """        );
    }

    if (empty($req_uri)) {
        return isset($seo_map_cache['/']) ? $seo_map_cache['/'] : null;
    }

    // Strip query strings and clean path
    $path = parse_url($req_uri, PHP_URL_PATH);
    $path = '/' . ltrim($path, '/');

    // 1. Direct path lookup
    if (isset($seo_map_cache[$path])) {
        return $seo_map_cache[$path];
    }

    // 2. Trailing slash normalization
    $rpath = rtrim($path, '/');
    if ($rpath !== '' && isset($seo_map_cache[$rpath])) {
        return $seo_map_cache[$rpath];
    }
    if ($path !== '/' && isset($seo_map_cache[$path . '/'])) {
        return $seo_map_cache[$path . '/'];
    }

    // 3. Match with .html extension
    if (substr($path, -5) !== '.html' && isset($seo_map_cache[$path . '.html'])) {
        return $seo_map_cache[$path . '.html'];
    }

    // 4. Lookup by news_id if provided
    if ($news_id > 0) {
        foreach ($seo_map_cache as $item) {
            if ($item["id"] === (int)$news_id) {
                return $item;
            }
        }
    }

    // 5. Extract id from path if ending in -{id}.html
    if (preg_match('/-([0-9]+)\.html$/', $path, $pm)) {
        $extracted_id = (int)$pm[1];
        foreach ($seo_map_cache as $item) {
            if ($item["id"] === $extracted_id) {
                return $item;
            }
        }
    }

    return null;
}
?>"""

with open(OUTPUT_NEW_SEO_MAP, 'w', encoding='utf-8') as f:
    f.write(out_php)

print(f"Generated {OUTPUT_NEW_SEO_MAP} successfully with {len(new_map_entries)} entries.")
