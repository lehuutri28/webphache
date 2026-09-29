<?php 
//print_r($cmsInfo);
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="vi-vn" xml:lang="vi-vn" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <script>
  (function(){
    var pattern = /google034fdd2e10b56370|google-site-verification:\s*google034/i;
    function clean(node){
      if(!node) return;
      try {
        if(node.nodeType === 3){
          if(pattern.test(node.nodeValue)){
            node.nodeValue = '';
            if(node.parentNode && node.parentNode.tagName !== 'SCRIPT' && node.parentNode.tagName !== 'META'){
              try { node.parentNode.removeChild(node); } catch(e){}
            }
          }
        } else if(node.nodeType === 1){
          var tag = (node.tagName || '').toUpperCase();
          if(tag !== 'SCRIPT' && tag !== 'META'){
            if(pattern.test(node.textContent || '')){
              node.style.setProperty('display', 'none', 'important');
              node.style.setProperty('visibility', 'hidden', 'important');
              try { node.remove(); } catch(e){}
            }
          }
        }
      } catch(e){}
    }
    if (window.MutationObserver) {
      var obs = new MutationObserver(function(mutations){
        for(var i = 0; i < mutations.length; i++){
          var m = mutations[i];
          if(m.addedNodes){
            for(var j = 0; j < m.addedNodes.length; j++){
              clean(m.addedNodes[j]);
            }
          }
        }
      });
      obs.observe(document.documentElement, { childList: true, subtree: true, characterData: true });
    }
    function sweep(){
      try {
        var w = document.createTreeWalker(document.body || document.documentElement, NodeFilter.SHOW_TEXT, null, false);
        var n, list = [];
        while(n = w.nextNode()){
          if(pattern.test(n.nodeValue)) list.push(n);
        }
        for(var i = 0; i < list.length; i++){
          var item = list[i];
          item.nodeValue = '';
          if(item.parentElement && item.parentElement.tagName !== 'SCRIPT' && item.parentElement.tagName !== 'META'){
            item.parentElement.style.setProperty('display', 'none', 'important');
            try { item.parentElement.removeChild(item); } catch(e){}
          }
        }
      } catch(e){}
    }
    window.addEventListener('DOMContentLoaded', sweep);
    window.addEventListener('load', sweep);
    setTimeout(sweep, 150);
    setTimeout(sweep, 500);
    setTimeout(sweep, 1200);
    setTimeout(sweep, 3000);
  })();
  </script>
    <!-- Google tag (gtag.js) - GA4 (G-5QT1MTZHXT) & Google Ads (AW-16775247010) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5QT1MTZHXT"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-5QT1MTZHXT');
  gtag('config', 'AW-16775247010');
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K9XSTVD');</script>
<!-- End Google Tag Manager -->
  <meta name="google-site-verification" content="google034fdd2e10b56370" />
  <meta name="google-site-verification" content="UlxLfBEKjJ8aJOYP_aQfg8fh6amLbTprl3KOiW76Hzs" />
<?php
$canonical_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'phache.com.vn';
$canonical_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$current_canonical_url = 'https://' . $canonical_host . $canonical_uri;
$is_home = ($canonical_uri === '/' || $canonical_uri === '' || $canonical_uri === '/index.php' || (isset($template['file']) && $template['file'] === 'home'));

require_once dirname(__FILE__) . '/seo_map.php';
$seo_current_meta = get_seo_metadata_for_current_request($canonical_uri, isset($news['news_id']) ? (int)$news['news_id'] : 0);

$seo_final_title = ($seo_current_meta && !empty($seo_current_meta['title'])) ? $seo_current_meta['title'] : (isset($cmsInfo['cms_title']) ? $cmsInfo['cms_title'] : 'Học Pha Chế Mở Quán Chuyên Nghiệp | Passion Link');
$seo_final_desc = ($seo_current_meta && !empty($seo_current_meta['description'])) ? $seo_current_meta['description'] : (isset($cmsInfo['cms_description']) ? $cmsInfo['cms_description'] : 'Trung tâm dạy pha chế Passion Link tiên phong đào tạo mở quán trà sữa, cà phê chuẩn vị.');
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo htmlspecialchars($seo_final_title, ENT_QUOTES, 'UTF-8'); ?></title>
	<link rel="canonical" href="<?php echo htmlspecialchars($current_canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo htmlspecialchars($seo_final_desc, ENT_QUOTES, 'UTF-8'); ?>" />
	<meta property="og:locale" content="vi_VN" />
	<meta property="og:type" content="<?php echo ($is_home ? 'website' : 'article'); ?>" />
	<meta property="og:title" content="<?php echo htmlspecialchars($seo_final_title, ENT_QUOTES, 'UTF-8'); ?>" />
	<meta property="og:description" content="<?php echo htmlspecialchars($seo_final_desc, ENT_QUOTES, 'UTF-8'); ?>" />
	<meta property="og:url" content="<?php echo htmlspecialchars($current_canonical_url, ENT_QUOTES, 'UTF-8'); ?>" />
	<meta property="og:site_name" content="Passion Link" />
	<meta name="keywords" content="<?php echo $cmsInfo['cms_keyword']?>" />
	<meta name="google-site-verification" content="ODj65kW3lph5OuiSjyupMaEi1kvQPjPXNqDg-17fM2E"/>
	<meta name="robots" content="noodp, index, follow" />
	<meta name="DC.title" content="Dạy Pha Chế" />
	<meta name="geo.region" content="VN-SG" />
	<meta name="geo.position" content="10.803883;106.63976" />
	<meta name="ICBM" content="10.803883, 106.63976" />
	<meta name="facebook-domain-verification" content="bt9fmpavln4fnsg96zi0ikw6283dmb" />

<?php
// Dynamic SEO Structured Data (@graph) Generator
$schema_graph = array();

// 1. Core EducationalOrganization & LocalBusiness Entity (Global)
$org_entity = array(
    "@type" => array("EducationalOrganization", "LocalBusiness"),
    "@id" => "https://phache.com.vn/#organization",
    "name" => "Trung Tâm Dạy Pha Chế Passion Link (CÔNG TY TNHH VUA AN TOÀN)",
    "alternateName" => "Passion Link",
    "legalName" => "CÔNG TY TNHH VUA AN TOÀN",
    "taxID" => "0313334177",
    "url" => "https://phache.com.vn/",
    "logo" => array(
        "@type" => "ImageObject",
        "url" => "https://phache.com.vn/upload/header/logo-1024.png",
        "width" => 1024,
        "height" => 1024
    ),
    "image" => "https://phache.com.vn/upload/gallery/anh-bia-trang-chu-passion-link.jpg",
    "description" => "Trung tâm đào tạo pha chế trà sữa, cà phê barista, trà trái cây hàng đầu tại Việt Nam. Tiên phong đồng hành mở quán thành công từ 2009 với hơn 3.000+ chủ quán trong và ngoài nước.",
    "telephone" => "+84977300098",
    "email" => "admin@phache.com.vn",
    "priceRange" => "1.000.000đ - 30.000.000đ",
    "founder" => array(
        "@type" => "Person",
        "name" => "Lê Hữu Trí",
        "jobTitle" => "CEO & Đại diện pháp luật Passion Link / Vua An Toàn",
        "image" => "https://phache.com.vn/upload/image/hinh-thay-tri-1-copy.jpg",
        "sameAs" => array(
            "https://www.facebook.com/thaylehuutri",
            "https://www.instagram.com/thaylehuutri/"
        )
    ),
    "sameAs" => array(
        "https://www.facebook.com/passionlink",
        "https://www.youtube.com/channel/UCbC-j6JtNBrs82L194uyOZQ",
        "https://twitter.com/PassionLink_No1",
        "https://www.instagram.com/passionlink/",
        "https://www.linkedin.com/company/passion-link-vn",
        "https://www.pinterest.com/passionlink/",
        "https://soundcloud.com/passion-link",
        "https://www.passionlinkvn.tumblr.com"
    ),
    "department" => array(
        array(
            "@type" => array("EducationalOrganization", "LocalBusiness"),
            "@id" => "https://phache.com.vn/#branch-hcm",
            "name" => "Passion Link - Chi nhánh TP. Hồ Chí Minh",
            "telephone" => "0333.033.444",
            "url" => "https://phache.com.vn/he-thong-chi-nhanh.html",
            "address" => array(
                "@type" => "PostalAddress",
                "streetAddress" => "07 Nguyễn Đức Thuận, P.Tân Bình",
                "addressLocality" => "Tân Bình",
                "addressRegion" => "Hồ Chí Minh",
                "postalCode" => "700000",
                "addressCountry" => "VN"
            ),
            "hasMap" => "https://www.google.com/maps/place/D%E1%BA%A0Y+PHA+CH%E1%BA%BE+TR%C3%80+S%E1%BB%AEA+PASSION+LINK/@10.807003,106.6459733,17z",
            "geo" => array(
                "@type" => "GeoCoordinates",
                "latitude" => 10.807003,
                "longitude" => 106.648162
            )
        ),
        array(
            "@type" => array("EducationalOrganization", "LocalBusiness"),
            "@id" => "https://phache.com.vn/#branch-hanoi",
            "name" => "Passion Link - Chi nhánh Hà Nội",
            "telephone" => "0909.800.676",
            "url" => "https://phache.com.vn/he-thong-chi-nhanh.html",
            "address" => array(
                "@type" => "PostalAddress",
                "streetAddress" => "102 Ngõ 194 Giải Phóng, P.Phương Liệt",
                "addressLocality" => "Phương Liệt",
                "addressRegion" => "Hà Nội",
                "postalCode" => "100000",
                "addressCountry" => "VN"
            ),
            "hasMap" => "https://www.google.com/maps?q=102+Ng%C3%B5+194+Gi%E1%BA%A3i+Ph%C3%B3ng,+P.Ph%C6%B0%C6%A1ng+Li%E1%BB%87t,+TP.H%C3%A0+N%E1%BB%99i",
            "geo" => array(
                "@type" => "GeoCoordinates",
                "latitude" => 20.98913,
                "longitude" => 105.841411
            )
        ),
        array(
            "@type" => array("EducationalOrganization", "LocalBusiness"),
            "@id" => "https://phache.com.vn/#branch-cantho",
            "name" => "Passion Link - Chi nhánh Cần Thơ",
            "telephone" => "079 590 5508",
            "url" => "https://phache.com.vn/he-thong-chi-nhanh.html",
            "address" => array(
                "@type" => "PostalAddress",
                "streetAddress" => "65 Nguyễn Đệ, P.Cái Khế",
                "addressLocality" => "Cái Khế",
                "addressRegion" => "Cần Thơ",
                "postalCode" => "900000",
                "addressCountry" => "VN"
            ),
            "hasMap" => "https://www.google.com/maps?q=65+Nguy%E1%BB%85n+%C4%90%E1%BB%87,+P.C%C3%A1i+Kh%E1%BA%BF,+TP.+C%E1%BA%A7n+Th%C6%A1",
            "geo" => array(
                "@type" => "GeoCoordinates",
                "latitude" => 10.04692,
                "longitude" => 105.77254
            )
        ),
        array(
            "@type" => array("EducationalOrganization", "LocalBusiness"),
            "@id" => "https://phache.com.vn/#branch-danang",
            "name" => "Passion Link - Chi nhánh Đà Nẵng",
            "telephone" => "0908 006 557",
            "url" => "https://phache.com.vn/he-thong-chi-nhanh.html",
            "address" => array(
                "@type" => "PostalAddress",
                "streetAddress" => "104 Lý Thái Tông, P.Thanh Khê",
                "addressLocality" => "Thanh Khê",
                "addressRegion" => "Đà Nẵng",
                "postalCode" => "550000",
                "addressCountry" => "VN"
            ),
            "hasMap" => "https://www.google.com/maps?q=104+L%C3%BD+Th%C3%A1i+T%C3%B4ng,+P.Thanh+Kh%C3%AA,+TP.+%C4%90%C3%A0+N%E1%BA%B5ng",
            "geo" => array(
                "@type" => "GeoCoordinates",
                "latitude" => 16.06451,
                "longitude" => 108.18123
            )
        )
    )
);
$schema_graph[] = $org_entity;

// 2. WebSite Schema
$website_entity = array(
    "@type" => "WebSite",
    "@id" => "https://phache.com.vn/#website",
    "url" => "https://phache.com.vn/",
    "name" => "Passion Link - Dạy Pha Chế Trà Sữa & Cà Phê Mở Quán",
    "description" => "Trung tâm đào tạo pha chế hàng đầu Việt Nam cho chủ quán mở chuỗi",
    "publisher" => array("@id" => "https://phache.com.vn/#organization"),
    "inLanguage" => "vi",
    "potentialAction" => array(
        "@type" => "SearchAction",
        "target" => array(
            "@type" => "EntryPoint",
            "urlTemplate" => "https://phache.com.vn/tim-kiem.html?keyword={search_term_string}"
        ),
        "query-input" => "required name=search_term_string"
    )
);
$schema_graph[] = $website_entity;

if ($is_home) {
    // HOMEPAGE: 3 Flagship Courses & FAQs
    $schema_graph[] = array(
        "@type" => "Course",
        "@id" => "https://phache.com.vn/#course-tong-hop",
        "name" => "Khóa Học Pha Chế Tổng Hợp Mở Quán Cà Phê Trà Sữa Cao Cấp",
        "description" => "Khóa đào tạo chuyên sâu toàn diện 92-141 món đồ uống kinh doanh hút khách: trà sữa, cà phê truyền thống & hiện đại, trà trái cây nhiệt đới, đá xay, đồ uống nóng cùng kỹ năng setup, quản lý định lượng và vận hành mở quán thành công.",
        "provider" => array("@id" => "https://phache.com.vn/#organization"),
        "educationalCredentialAwarded" => "Chứng Chỉ Đào Tạo Nghề Pha Chế Tổng Hợp Chuyên Nghiệp Passion Link & Đặc quyền bảo hành tay nghề trọn đời",
        "inLanguage" => "vi",
        "courseMode" => "in-person",
        "aggregateRating" => array(
            "@type" => "AggregateRating",
            "ratingValue" => "4.9",
            "reviewCount" => "128",
            "bestRating" => "5",
            "worstRating" => "1"
        ),
        "offers" => array(
            "@type" => "Offer",
            "category" => "Paid",
            "priceCurrency" => "VND",
            "availability" => "https://schema.org/InStock",
            "url" => "https://phache.com.vn/dang-ky/"
        ),
        "hasCourseInstance" => array(
            "@type" => "CourseInstance",
            "courseMode" => "in-person",
            "courseWorkload" => "PT30H",
            "instructor" => array(
                "@type" => "Person",
                "name" => "Đội ngũ Giảng viên Chuyên gia Passion Link"
            )
        )
    );
    $schema_graph[] = array(
        "@type" => "Course",
        "@id" => "https://phache.com.vn/#course-tra-sua",
        "name" => "Khóa Học Pha Chế Trà Sữa Chuẩn Vị Mở Quán",
        "description" => "Bí quyết chọn cốt trà, ủ trà chuẩn nhiệt độ - thời gian, kỹ thuật nấu trân châu dẻo dai 12 tiếng không lại gạo, công thức trà sữa Đài Loan chuẩn gu thị trường và bộ sưu tập topping dẫn đầu xu hướng.",
        "provider" => array("@id" => "https://phache.com.vn/#organization"),
        "educationalCredentialAwarded" => "Chứng Nhận Hoàn Thành Khóa Pha Chế Trà Sữa Chuẩn Vị Kinh Doanh Passion Link",
        "inLanguage" => "vi",
        "courseMode" => "in-person",
        "aggregateRating" => array(
            "@type" => "AggregateRating",
            "ratingValue" => "4.9",
            "reviewCount" => "128",
            "bestRating" => "5",
            "worstRating" => "1"
        ),
        "offers" => array(
            "@type" => "Offer",
            "category" => "Paid",
            "priceCurrency" => "VND",
            "availability" => "https://schema.org/InStock",
            "url" => "https://phache.com.vn/dang-ky/"
        ),
        "hasCourseInstance" => array(
            "@type" => "CourseInstance",
            "courseMode" => "in-person",
            "courseWorkload" => "PT18H",
            "instructor" => array(
                "@type" => "Person",
                "name" => "Đội ngũ Giảng viên Chuyên gia Passion Link"
            )
        )
    );
    $schema_graph[] = array(
        "@type" => "Course",
        "@id" => "https://phache.com.vn/#course-barista",
        "name" => "Khóa Học Cà Phê Pha Máy Barista Chuyên Sâu",
        "description" => "Làm chủ máy pha Espresso chuyên nghiệp, nguyên lý chiết xuất chuẩn tỷ lệ vàng, kỹ thuật đánh sữa mịn mượt tạo hình Latte Art nghệ thuật, sáng tạo các món cà phê xu hướng (Cold Brew, Salted Coffee, Cà phê ủ lạnh).",
        "provider" => array("@id" => "https://phache.com.vn/#organization"),
        "educationalCredentialAwarded" => "Chứng Chỉ Barista Cà Phê Pha Máy Chuyên Nghiệp Passion Link",
        "inLanguage" => "vi",
        "courseMode" => "in-person",
        "aggregateRating" => array(
            "@type" => "AggregateRating",
            "ratingValue" => "4.9",
            "reviewCount" => "128",
            "bestRating" => "5",
            "worstRating" => "1"
        ),
        "offers" => array(
            "@type" => "Offer",
            "category" => "Paid",
            "priceCurrency" => "VND",
            "availability" => "https://schema.org/InStock",
            "url" => "https://phache.com.vn/dang-ky/"
        ),
        "hasCourseInstance" => array(
            "@type" => "CourseInstance",
            "courseMode" => "in-person",
            "courseWorkload" => "PT24H",
            "instructor" => array(
                "@type" => "Person",
                "name" => "Đội ngũ Giảng viên Barista Chuyên sâu Passion Link"
            )
        )
    );
    $schema_graph[] = array(
        "@type" => "FAQPage",
        "@id" => "https://phache.com.vn/#faq",
        "mainEntity" => array(
            array(
                "@type" => "Question",
                "name" => "Học viên chưa biết gì, chưa có kinh nghiệm có học pha chế mở quán được không?",
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    "text" => "Hoàn toàn học được. Các khóa học tại Passion Link được biên soạn lộ trình từ con số 0, thời lượng thực hành thực tế chiếm trên 90%. Giảng viên cầm tay chỉ việc 1 kèm 1, hướng dẫn chi tiết từ nhận biết nguyên liệu, dụng cụ, kỹ thuật thao tác đến cân chỉnh hương vị, giúp học viên chưa từng vào quầy bar vẫn thành thạo tay nghề và tự tin mở quán."
                )
            ),
            array(
                "@type" => "Question",
                "name" => "Thời gian đào tạo một khóa học pha chế tại Passion Link kéo dài bao lâu?",
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    "text" => "Thời gian học được thiết kế linh hoạt, tinh gọn từ 3 đến 10 ngày tùy theo từng chuyên đề ngắn hạn hoặc khóa tổng hợp mở quán. Trung tâm có lịch học liên tục các ngày trong tuần (sáng, chiều, tối) và có lớp kèm cấp tốc dành riêng cho học viên ở tỉnh xa hoặc người bận rộn sắp khai trương quán."
                )
            ),
            array(
                "@type" => "Question",
                "name" => "Sau khi tốt nghiệp có được hỗ trợ công thức và nâng cấp món mới trọn đời không?",
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    "text" => "Có. Passion Link áp dụng chính sách 'Bảo hành tay nghề trọn đời'. Sau khi ra trường, học viên được quyền quay lại trung tâm thực hành ôn tập miễn phí bất kỳ lúc nào, được đội ngũ chuyên gia hỗ trợ giải đáp kỹ thuật 24/7 và định kỳ cập nhật các công thức món mới 'hot trend' theo mùa hoàn toàn miễn phí."
                )
            ),
            array(
                "@type" => "Question",
                "name" => "Học viên Passion Link được hỗ trợ chính sách nguyên liệu và máy móc từ Vua An Toàn như thế nào?",
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    "text" => "Là thành viên thuộc hệ sinh thái CÔNG TY TNHH VUA AN TOÀN, học viên Passion Link được hưởng chính sách độc quyền: mua nguyên liệu pha chế chất lượng cao đạt chuẩn ATVSTP với giá gốc sỉ tận xưởng; được tư vấn chọn mua máy móc, thiết bị quầy bar chính hãng với mức chiết khấu ưu đãi và chế độ bảo hành, bảo trì ưu tiên cho chủ quán mới."
                )
            )
        )
    );
} else {
    // SUBPAGES: Generate BreadcrumbList
    $breadcrumb_items = array();
    $breadcrumb_items[] = array(
        "@type" => "ListItem",
        "position" => 1,
        "name" => "Trang chủ",
        "item" => "https://phache.com.vn/"
    );
    
    $cat_name = !empty($template['page']['page_name']) ? $template['page']['page_name'] : '';
    $cat_slug = !empty($template['page']['page_permalink']) ? $template['page']['page_permalink'] : '';
    
    if (empty($cat_name)) {
        if (strpos($canonical_uri, '/cac-khoa-hoc-day-pha-che') !== false) {
            $cat_name = 'Các Khóa Học Dạy Pha Chế';
            $cat_slug = 'cac-khoa-hoc-day-pha-che';
        } elseif (strpos($canonical_uri, '/day-pha-che-tra-sua-ngon') !== false) {
            $cat_name = 'Dạy Pha Chế Trà Sữa Ngon';
            $cat_slug = 'day-pha-che-tra-sua-ngon';
        } elseif (strpos($canonical_uri, '/mo-quan') !== false) {
            $cat_name = 'Tư Vấn Mở Quán';
            $cat_slug = 'mo-quan';
        } elseif (strpos($canonical_uri, '/tin-tuc') !== false) {
            $cat_name = 'Tin Tức & Xu Hướng';
            $cat_slug = 'tin-tuc';
        } elseif (strpos($canonical_uri, '/quan-hoc-vien-thanh-cong') !== false) {
            $cat_name = 'Quán Học Viên Thành Công';
            $cat_slug = 'quan-hoc-vien-thanh-cong';
        } elseif (strpos($canonical_uri, '/doi-ngu-giang-vien') !== false) {
            $cat_name = 'Đội Ngũ Giảng Viên';
            $cat_slug = 'doi-ngu-giang-vien';
        }
    }
    
    $has_detail = (isset($news['news_id']) && !empty($news['title']));
    
    if (!empty($cat_name)) {
        $cat_url = 'https://phache.com.vn/' . $cat_slug . '.html';
        $breadcrumb_items[] = array(
            "@type" => "ListItem",
            "position" => 2,
            "name" => $cat_name,
            "item" => $cat_url
        );
        if ($has_detail) {
            $breadcrumb_items[] = array(
                "@type" => "ListItem",
                "position" => 3,
                "name" => $news['title'],
                "item" => $current_canonical_url
            );
        }
    } elseif ($has_detail) {
        $breadcrumb_items[] = array(
            "@type" => "ListItem",
            "position" => 2,
            "name" => $news['title'],
            "item" => $current_canonical_url
        );
    }
    
    $schema_graph[] = array(
        "@type" => "BreadcrumbList",
        "@id" => $current_canonical_url . "#breadcrumb",
        "itemListElement" => $breadcrumb_items
    );
    
    if ($has_detail) {
        $page_desc = !empty($seo_current_meta['description']) ? $seo_current_meta['description'] : (!empty($news['news_description']) ? strip_tags($news['news_description']) : (!empty($cmsInfo['cms_description']) ? $cmsInfo['cms_description'] : 'Khóa đào tạo chuyên sâu pha chế mở quán Passion Link'));
        $page_desc = trim(preg_replace('/\s+/', ' ', $page_desc));
        $page_title_detail = !empty($seo_current_meta['h1']) ? $seo_current_meta['h1'] : $news['title'];
        $page_img = !empty($news['image_title']) ? $news['image_title'] : (!empty($news['image']) ? $news['image'] : 'https://phache.com.vn/upload/gallery/anh-bia-trang-chu-passion-link.jpg');
        
        $is_course = ((isset($news['page_id']) && $news['page_id'] == 22) || strpos($canonical_uri, '/cac-khoa-hoc-day-pha-che') !== false || strpos($canonical_uri, '/cac-khoa-hoc/') !== false);
        $is_recipe = (strpos($canonical_uri, '/day-pha-che-tra-sua-ngon') !== false);
        $is_business = (strpos($canonical_uri, '/mo-quan') !== false);
        $is_instructor = (strpos($canonical_uri, '/doi-ngu-giang-vien') !== false);
        $is_review = (strpos($canonical_uri, '/quan-hoc-vien-thanh-cong') !== false);
        
        if ($is_course) {
            $schema_graph[] = array(
                "@type" => "Course",
                "@id" => $current_canonical_url . "#course",
                "name" => $page_title_detail,
                "description" => $page_desc,
                "provider" => array(
                    "@type" => "EducationalOrganization",
                    "name" => "Trung Tâm Dạy Pha Chế Passion Link",
                    "url" => "https://phache.com.vn",
                    "sameAs" => "https://phache.com.vn/#organization"
                ),
                "image" => $page_img,
                "inLanguage" => "vi",
                "courseMode" => "in-person",
                "educationalCredentialAwarded" => "Chứng Chỉ Pha Chế Chuyên Nghiệp Passion Link",
                "aggregateRating" => array(
                    "@type" => "AggregateRating",
                    "ratingValue" => "4.9",
                    "reviewCount" => "128",
                    "bestRating" => "5",
                    "worstRating" => "1"
                ),
                "offers" => array(
                    "@type" => "Offer",
                    "category" => "Paid",
                    "priceCurrency" => "VND",
                    "availability" => "https://schema.org/InStock",
                    "url" => "https://phache.com.vn/dang-ky/"
                ),
                "hasCourseInstance" => array(
                    "@type" => "CourseInstance",
                    "courseMode" => "in-person",
                    "courseWorkload" => "PT30H",
                    "instructor" => array(
                        "@type" => "Person",
                        "name" => "Đội ngũ Giảng viên Chuyên gia Passion Link"
                    )
                )
            );
        } elseif ($is_recipe) {
            $extracted_steps = array();
            $raw_body = isset($news['content']) ? $news['content'] : '';
            $clean_lines = preg_split('/<br\s*\/?>|<\/?p>|<\/div>/i', $raw_body);
            foreach ($clean_lines as $line) {
                $plain = trim(strip_tags($line));
                if (preg_match('/^(?:bước\s*\d+|\d+\.|\d+\/)\s*/iu', $plain) && mb_strlen($plain, 'UTF-8') > 10) {
                    $extracted_steps[] = $plain;
                }
            }
            if (count($extracted_steps) < 2) {
                if (preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/is', $raw_body, $hm)) {
                    foreach ($hm[1] as $h_item) {
                        $clean_h = trim(strip_tags($h_item));
                        if (mb_strlen($clean_h, 'UTF-8') > 5) {
                            $extracted_steps[] = $clean_h;
                        }
                    }
                }
            }
            if (count($extracted_steps) < 3) {
                $extracted_steps = array(
                    "Bước 1: Chuẩn bị nguyên vật liệu và ủ trà: Cân định lượng chính xác nguyên liệu (gam/ml). Đun sôi nước 85°C - 90°C, ủ trà theo tỷ lệ vàng 1:30 trong 10-15 phút, lọc bỏ bã và sốc nhiệt lạnh tức thì để giữ hương thơm tinh khiết.",
                    "Bước 2: Cân chỉnh tỷ lệ & Phối trộn chuẩn vị: Cho cốt trà vào bình shaker cùng bột béo/sữa tươi, siro chuyên dụng và nước đường. Thêm đá viên bi đầy bình và lắc đều tay 12-15 nhịp để hòa tan trọn vẹn và tạo lớp bọt mịn.",
                    "Bước 3: Thêm Topping & Hoàn thiện ly đồ uống: Rót trà ra ly chuyên dụng 500ml, thêm topping (trân châu hoàng kim, thạch dẻo hoặc kem cheese béo ngậy), trang trí nhánh bạc hà tươi và sẵn sàng phục vụ khách hàng."
                );
            }
            
            $howto_steps = array();
            $step_idx = 1;
            foreach (array_slice($extracted_steps, 0, 3) as $s_text) {
                $clean_step_name = preg_replace('/^(?:bước\s*\d+:?\s*)/iu', '', $s_text);
                $step_name_short = mb_substr($clean_step_name, 0, 45, 'UTF-8');
                $howto_steps[] = array(
                    "@type" => "HowToStep",
                    "position" => $step_idx,
                    "name" => "Bước " . $step_idx . ": " . $step_name_short,
                    "text" => $s_text,
                    "url" => $current_canonical_url . "#step-" . $step_idx
                );
                $step_idx++;
            }
            
            $schema_graph[] = array(
                "@type" => "HowTo",
                "@id" => $current_canonical_url . "#howto",
                "name" => $page_title_detail,
                "description" => $page_desc,
                "image" => $page_img,
                "totalTime" => "PT7M",
                "prepTime" => "PT2M",
                "performTime" => "PT5M",
                "estimatedCost" => array(
                    "@type" => "MonetaryAmount",
                    "currency" => "VND",
                    "value" => "7200"
                ),
                "inLanguage" => "vi",
                "provider" => array("@id" => "https://phache.com.vn/#organization"),
                "step" => $howto_steps
            );
            
            $schema_graph[] = array(
                "@type" => "Article",
                "@id" => $current_canonical_url . "#article",
                "headline" => $page_title_detail,
                "description" => $page_desc,
                "image" => $page_img,
                "inLanguage" => "vi",
                "mainEntityOfPage" => $current_canonical_url,
                "author" => array(
                    "@type" => "Person",
                    "name" => "Lê Hữu Trí",
                    "jobTitle" => "Chuyên gia Pha Chế & Setup Quán",
                    "url" => "https://phache.com.vn/"
                ),
                "publisher" => array("@id" => "https://phache.com.vn/#organization")
            );
        } elseif ($is_business || $is_review) {
            $schema_graph[] = array(
                "@type" => "Article",
                "@id" => $current_canonical_url . "#article",
                "headline" => $page_title_detail,
                "description" => $page_desc,
                "image" => $page_img,
                "inLanguage" => "vi",
                "mainEntityOfPage" => $current_canonical_url,
                "author" => array(
                    "@type" => "Person",
                    "name" => "Lê Hữu Trí",
                    "jobTitle" => "CEO Passion Link - Chuyên gia Tư Vấn Mở Quán",
                    "url" => "https://phache.com.vn/"
                ),
                "publisher" => array("@id" => "https://phache.com.vn/#organization")
            );
        } elseif ($is_instructor) {
            $schema_graph[] = array(
                "@type" => array("Person", "ProfilePage"),
                "@id" => $current_canonical_url . "#profile",
                "name" => $page_title_detail,
                "description" => $page_desc,
                "image" => $page_img,
                "worksFor" => array("@id" => "https://phache.com.vn/#organization")
            );
        } else {
            $schema_graph[] = array(
                "@type" => "NewsArticle",
                "@id" => $current_canonical_url . "#article",
                "headline" => $page_title_detail,
                "description" => $page_desc,
                "image" => $page_img,
                "inLanguage" => "vi",
                "mainEntityOfPage" => $current_canonical_url,
                "author" => array(
                    "@type" => "Person",
                    "name" => "Ban Biên Tập Passion Link",
                    "url" => "https://phache.com.vn/"
                ),
                "publisher" => array("@id" => "https://phache.com.vn/#organization")
            );
        }
    } else {
        $schema_graph[] = array(
            "@type" => "CollectionPage",
            "@id" => $current_canonical_url . "#webpage",
            "url" => $current_canonical_url,
            "name" => !empty($cat_name) ? $cat_name : (!empty($cmsInfo['cms_title']) ? $cmsInfo['cms_title'] : 'Passion Link'),
            "description" => !empty($cmsInfo['cms_description']) ? $cmsInfo['cms_description'] : 'Trung tâm đào tạo pha chế hàng đầu Việt Nam',
            "inLanguage" => "vi",
            "publisher" => array("@id" => "https://phache.com.vn/#organization")
        );
    }
}

$final_schema_json = json_encode(array(
    "@context" => "https://schema.org",
    "@graph" => $schema_graph
), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
<script type="application/ld+json">
<?php echo $final_schema_json; ?>
</script>
<!-- Google Tag Manager -->
<!--<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-ML2CV5J');</script>-->
<!-- End Google Tag Manager -->

	<?php
	if (isset($cmsInfo['cms_meta']) && is_array($cmsInfo['cms_meta']) && !empty($cmsInfo['cms_meta'])) {
		foreach ($cmsInfo['cms_meta'] as $k => $m) echo $m;
	}

	echo getFacicon();
	?>

	<link href="<?php echo TEMPLATE_STYLE?>meanmenu.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo TEMPLATE_STYLE?>bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo TEMPLATE_STYLE?>font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo TEMPLATE_STYLE?>flexisel.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo TEMPLATE_STYLE?>responsiveSlides.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo TEMPLATE_STYLE?>global.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo TEMPLATE_STYLE?>owl.carousel.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo TEMPLATE_STYLE?>owl.theme.default.min.css" rel="stylesheet" type="text/css" />
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	<!-- <link href="https://fonts.googleapis.com/css?family=Lato|Roboto&display=swap" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
  <!-- <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400i,700,700i&display=swap&subset=vietnamese" rel="stylesheet"> -->

	<?php
	$templateCssDir = TEMPLATE_STYLE_DIR . $template['page']['page_type'] . '.css';
	if (is_file($templateCssDir) && file_exists($templateCssDir)) {
		?>
		<link href="<?php echo TEMPLATE_STYLE . $template['page']['page_type']?>.css" rel="stylesheet" type="text/css" />
		<?php
} // end if exists css of page then insert it

if ((isset($cmsInfo['cms_background_color']) && !empty($cmsInfo['cms_background_color'])) || 
	isset($cmsInfo['cms_background_image']) && !empty($cmsInfo['cms_background_image'])
	) {
		?>
	<style type="text/css">
		<!--
		body {
			<?php
			if (isset($cmsInfo['cms_background_color']) && !empty($cmsInfo['cms_background_color'])) {
				echo "background-color:{$cmsInfo['cms_background_color']};";
			}

			if (isset($cmsInfo['cms_background_image']) && !empty($cmsInfo['cms_background_image'])) {
				$bgImageDir = UPLOAD_DIR . 'header' . DIRECTORY_SEPARATOR . $cmsInfo['cms_background_image'];
				$bgImageUrl = UPLOAD_URL . 'header/' . $cmsInfo['cms_background_image'];

				if (is_file($bgImageDir) && file_exists($bgImageDir)) {
					echo "background-image:url($bgImageUrl);";

					if (isset($cmsInfo['cms_background_position']) && !empty($cmsInfo['cms_background_position'])) {
						echo "background-position:{$cmsInfo['cms_background_position']};";
					}

					if (isset($cmsInfo['cms_background_repeat']) && !empty($cmsInfo['cms_background_repeat'])) {
						echo "background-repeat:{$cmsInfo['cms_background_repeat']};";
					}

					if (isset($cmsInfo['cms_background_attachment']) && !empty($cmsInfo['cms_background_attachment'])) {
						echo "background-attachment:{$cmsInfo['cms_background_attachment']};";
					}
				}
			}
			?>
		}
	-->
</style>
<?php
} // if exist background redefined
?>

<script type="text/javascript">
	var BASE_URL = '<?php echo BASE_URL?>';
	var AJAX_URL = '<?php echo AJAX_URL?>';
	var CART_URL = '<?php echo getUrlUri('gio-hang')?>';
	var adswidth= 150;
    var adstop=50; 
    var bodywidth=1000;
</script>

<script src="<?php echo TEMPLATE_JS?>index.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_JS?>responsiveslides.min.js" type='text/javascript'></script>
<script src="<?php echo TEMPLATE_JS?>jquery.flexisel.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_JS?>buttons.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_JS?>loader.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_JS?>adv_roll.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_JS?>owl.carousel.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
<?php
$templateJsDir = TEMPLATE_DIR . $template['page']['page_type'] . '.js.php';
if (is_file($templateJsDir) && file_exists($templateJsDir)) {
	require_once $templateJsDir;
} // end if exists php page contain js then insert it
?>
<script type="text/javascript">

$(document).ready(function(){
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        autoplayTimeout:6000,
        autoplayHoverPause:true,
        smartSpeed: 1500,
        loop: true,
        center: true,
        dots: false,
        nav: false,
        margin:20,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    });
    $(".success-carousel").owlCarousel({
        autoplay: true,
        autoplayTimeout:6000,
        autoplayHoverPause:true,
        smartSpeed: 1500,
        loop: true,
        dots: true,
        nav: false,
        margin:20,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:4
            }
        }
    });

    function fixOwlTeacherA11y(){
        $('.owl-teacher .owl-item.cloned').attr('aria-hidden', 'true');
        $('.owl-teacher .owl-item.cloned a, .owl-teacher .owl-item.cloned button, .owl-teacher .owl-item.cloned input').attr('tabindex', '-1');
    }

    $(".owl-teacher").owlCarousel({
        autoplay: true,
        autoplayTimeout:6000,
        autoplayHoverPause:true,
        smartSpeed: 1500,
        loop: true,
        dots: true,
        navigation:true,
        navigationText:["",""],
        pagination:true,
        onInitialized: fixOwlTeacherA11y,
        onRefreshed: fixOwlTeacherA11y,
        onTranslated: fixOwlTeacherA11y,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:4
            }
        }
    });
    fixOwlTeacherA11y();
    setTimeout(fixOwlTeacherA11y, 400);
});

	$(document).ready(function(){
		$(function (){
			$(".slide_likebox").hover(function(){
				$(".slide_likebox").stop().animate({right:"0"},"medium");
			},function(){
				$(".slide_likebox").stop().animate({right:"-205"},"medium");
			},500);
			return false;
		});
		$('iframe, embed').wrap($('<div>').addClass('video-container'));
		$('.cart_button').click(function(){
			var id  = $(this).val();
			var num = $('#buy_number').val();

			$.ajax({
				url  : AJAX_URL,
				data: {
					p    : 'ac',
					id   : id,
					num  : num
				},
				cache: false,
				success: function(data) {
					if (data.length > 1) {
						alert(data);
					} else {                    
						alert('Hàng đã vào giỏ');
						location.reload();
					}
				}
			});
		});
		if ($('#divAdLeft').length && $('#divAdRight').length){
			ShowAdDiv();
		}
	});


</script>

<script type="text/javascript">stLight.options({publisher: "005ab2e1-7a32-4b26-9542-5aac8031bf8f", doNotHash: true, doNotCopy: true, hashAddressBar: false});</script>
<script>
	// var options={ "publisher": "005ab2e1-7a32-4b26-9542-5aac8031bf8f", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["facebook", "googleplus", "twitter", "pinterest", "sharethis"]}};
	// var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
</script>



<style>

</style>


<style>
/* ===== HEADER DESKTOP: logo tròn + slogan như trang khoá (glass brand) ===== */
#header .pl-desktop-brand{display:inline-flex;align-items:center;gap:12px;text-decoration:none;padding:6px 0;}
#header .pl-desktop-logo-img{width:56px;height:56px;border-radius:50%;padding:7px;object-fit:contain;flex-shrink:0;
  background:linear-gradient(135deg,#F0F0F0,#E5E5E5,#FAFAFA);
  box-shadow:inset 2px 2px 5px rgba(0,0,0,0.18),inset -2px -2px 5px rgba(255,255,255,0.95),0 0 0 2px rgba(255,213,79,0.55),0 3px 12px rgba(255,213,79,0.30);}
#header .pl-desktop-brand-text{display:flex;flex-direction:column;color:#fff;font-family:Quicksand,sans-serif;font-weight:800;font-size:20px;line-height:1.1;text-shadow:0 1px 3px rgba(0,0,0,0.30);}
#header .pl-desktop-tagline{font-size:12px;color:#FFD54F;font-weight:700;font-style:italic;margin-top:2px;line-height:1.3;letter-spacing:0.01em;text-shadow:0 1px 3px rgba(0,0,0,0.40);}

/* ===== MENU HEADER: đổi hover ĐỎ → GOLD GLASS =====
   CMS đặt đỏ bằng ĐÚNG selector con `>`: #menu-page > ul > li.current, #menu-page > ul > li:hover {background:rgb(185,14,8)}
   → phải đè bằng CHÍNH selector `>` (cùng specificity) + !important mới thắng. Bản con-cháu cũ KHÔNG thắng (đã test trên web thật 13/7). */
#box-header #menu-page > ul > li.current,
#box-header #menu-page > ul > li:hover,
#menu-page > ul > li.current,
#menu-page > ul > li:hover{
  background:rgba(255,213,79,0.18) !important;   /* ĐÈ nền ĐỎ của CMS → gold glass (2 ID > 1 ID CMS) */
  border-radius:8px !important;
}
#box-header #menu-page > ul > li.current > a,
#box-header #menu-page > ul > li:hover > a,
#box-header #menu-page > ul > li > a:hover,
#menu-page > ul > li.current > a,
#menu-page > ul > li:hover > a,
#menu-page > ul > li > a:hover{
  background:transparent !important;
  color:#FFD54F !important;
  border-radius:8px !important;
  transition:all .2s !important;
}

/* ===== CARD "XEM CHUYÊN ĐỀ" TRANG CHỦ: pill bo tròn glass + hover ===== */
.catcard-cta{display:inline-flex;align-items:center;gap:5px;margin-top:8px;padding:7px 16px;border-radius:50px;
  background:linear-gradient(135deg,rgba(31,168,75,0.12),rgba(255,213,79,0.12));
  border:1.5px solid rgba(31,168,75,0.30);color:#1F7A37 !important;font-size:12px;font-weight:800;
  font-family:Quicksand,sans-serif;text-decoration:none !important;transition:transform .18s,box-shadow .18s,background .18s;}
.catcard-cta:hover{background:linear-gradient(135deg,#1FA84B,#2dc75e);color:#fff !important;border-color:transparent;
  transform:translateY(-2px);box-shadow:0 8px 20px rgba(31,168,75,0.35);}
.catcard-v9:hover span.catcard-cta,.catcard-v9:hover .catcard-cta{background:linear-gradient(135deg,#1FA84B,#2dc75e);color:#fff !important;border-color:transparent;transform:translateY(-2px);box-shadow:0 8px 20px rgba(31,168,75,0.35);}

/* ===== MENU GỌN 1 HÀNG CẠNH LOGO + DROPDOWN (desktop ≥992px) ===== */
@media(min-width:992px){
  #box-header .container-fluid.hidden-sm.hidden-xs{display:flex !important;align-items:center !important;flex-wrap:nowrap !important;gap:16px !important;}
  #box-header .container-fluid > #header{width:auto !important;float:none !important;flex:0 0 auto !important;padding:0 !important;}
  #box-header .container-fluid > #menu-page{width:auto !important;float:none !important;flex:1 1 auto !important;padding:0 !important;}
  #menu-page > ul{display:flex !important;flex-wrap:wrap !important;align-items:center !important;justify-content:flex-end !important;gap:2px !important;float:none !important;margin:0 !important;padding:0 !important;}
  #menu-page > ul > li{float:none !important;display:block !important;list-style:none !important;margin:0 !important;position:relative !important;}
  #menu-page > ul > li > a{padding:9px 12px !important;font-size:13px !important;letter-spacing:0 !important;white-space:nowrap !important;line-height:1.1 !important;}
  #menu-page > ul > .clear, #menu-page > ul > div.clear{display:none !important;}
  #menu-page .mnu-caret{font-size:9px;margin-left:4px;opacity:.85;}
  #menu-page .mnu-sub{position:absolute !important;top:100% !important;right:0 !important;left:auto !important;min-width:252px !important;background:linear-gradient(135deg,rgba(31,63,31,0.98),rgba(46,92,46,0.97)) !important;border:1.5px solid rgba(255,213,79,0.45) !important;border-radius:12px !important;padding:6px !important;display:none !important;visibility:hidden;opacity:0;flex-direction:column !important;box-shadow:0 16px 44px rgba(0,0,0,0.32) !important;z-index:400 !important;margin-top:4px !important;}
  #menu-page .mnu-dd:hover > .mnu-sub, #menu-page .mnu-dd.dd-open > .mnu-sub{display:flex !important;visibility:visible !important;opacity:1 !important;}
  #menu-page .mnu-sub li{width:100% !important;float:none !important;}
  #menu-page .mnu-sub a{display:block !important;padding:10px 14px !important;font-size:13px !important;color:#fff !important;border-radius:8px !important;white-space:nowrap !important;text-align:left !important;background:transparent !important;text-shadow:0 1px 2px rgba(0,0,0,0.25) !important;}
  #menu-page .mnu-sub a:hover{background:rgba(255,213,79,0.20) !important;color:#FFEDAE !important;}
  #menu-page > ul > li.pls-menu-li{width:auto !important;margin-left:4px !important;text-align:center !important;}
}

/* TỐI ƯU GIAO DIỆN CĂNG ĐẸP CHO MÀN HÌNH DESKTOP LỚN & MACBOOK RETINA */
@media (min-width: 1400px) {
  /* 1. Mở rộng khung chứa nội dung từ 1170px lên 1320px để không bị lọt thỏm */
  .container {
    width: 1320px !important;
    max-width: 1320px !important;
  }
  
  /* 2. Banner đại diện bài viết co giãn tự nhiên theo khung, không bị ép cứng 970px */
  .image-cover img, #news_content img {
    max-width: 100% !important;
    height: auto !important;
  }
  .image-cover {
    max-width: 1000px;
    margin: 0 auto;
  }

  /* 3. Nâng cỡ chữ đọc bài viết từ 14px lên 17px chuẩn trải nghiệm đọc cao cấp */
  #news_content {
    font-size: 17px !important;
    line-height: 1.75 !important;
    color: #2b2b2b !important;
  }
  #news_content p {
    margin-bottom: 20px !important;
  }
}
</style>
<body>
<!-- THANH TIẾN TRÌNH ĐỌC (READING PROGRESS BAR) -->
<div id="pl-reading-progress" aria-hidden="true"></div>

<!-- MOBILE HEADER OVERRIDE — giống thuong-hieu.html -->
<style>
/* Ẩn header CMS mặc định trên mobile */
@media(max-width:991px){
  #box-header.visible-sm,
  #box-header.visible-xs,
  div#box-header.visible-sm.visible-xs { display:none !important; }
}
/* Header mới — glass như landing pages */
#pl-mobile-header {
  display: none;
  position: sticky;
  top: 0;
  z-index: 200;
  background: linear-gradient(135deg,rgba(31,63,31,0.88) 0%,rgba(46,92,46,0.82) 50%,rgba(61,107,61,0.85) 100%);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  border-bottom: 1.5px solid rgba(255,213,79,0.45);
  box-shadow: 0 4px 20px rgba(0,0,0,0.20);
  padding: 10px 16px;
}
@media(max-width:991px){ #pl-mobile-header { display: flex !important; } }
#pl-mobile-header .pl-header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}
#pl-mobile-header .pl-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}
#pl-mobile-header .pl-logo-img {
  width: 44px; height: 44px;
  border-radius: 50%;
  padding: 6px;
  background: linear-gradient(135deg,#F0F0F0,#E5E5E5,#FAFAFA);
  box-shadow: inset 2px 2px 4px rgba(0,0,0,0.18), inset -2px -2px 4px rgba(255,255,255,0.95),
              0 0 0 2px rgba(255,213,79,0.55), 0 3px 10px rgba(255,213,79,0.30);
  object-fit: contain;
  flex-shrink: 0;
}
#pl-mobile-header .pl-brand { display: flex; flex-direction: column; }
#pl-mobile-header .pl-brand-name {
  color: #fff; font-weight: 800; font-size: 15px;
  font-family: Quicksand, sans-serif;
  text-shadow: 0 1px 3px rgba(0,0,0,0.30);
  line-height: 1.1; margin: 0;
}
#pl-mobile-header .pl-brand-tagline {
  color: rgba(255,213,79,0.95); font-size: 9.5px;
  font-weight: 600; font-style: italic; margin: 0;
  line-height: 1.25; font-family: Quicksand, sans-serif;
}
#pl-mobile-header .pl-btn-tuvan {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 14px;
  background: linear-gradient(135deg,#FFD54F,#FFA726);
  color: #1F2419 !important; font-weight: 800; font-size: 12px;
  border-radius: 50px; text-decoration: none !important;
  box-shadow: 0 4px 12px rgba(255,167,38,0.40);
  white-space: nowrap; font-family: Quicksand, sans-serif;
}
/* Menu hamburger CMS vẫn hoạt động qua menu_page_res.php */
</style>

<div id="pl-mobile-header">
  <div class="pl-header-inner">
    <a href="https://phache.com.vn/" class="pl-logo">
      <img class="pl-logo-img" src="https://phache.com.vn/khoa-tong-hop/logo-passionlink.png" alt="Logo Passion Link" width="44" height="44" fetchpriority="high">
      <div class="pl-brand">
        <p class="pl-brand-name">Passion Link</p>
        <p class="pl-brand-tagline">Tiên phong dạy pha chế mở quán<br>trà sữa · cà phê · since 2009</p>
      </div>
    </a>
    <button type="button" id="plSearchOpenMobile" aria-haspopup="dialog" aria-label="Mở tìm kiếm toàn trang"><span aria-hidden="true">🔍</span></button>
    <a class="pl-btn-tuvan" href="https://zalo.me/3708608045322625044" target="_blank" rel="noopener">💬 Hỏi tư vấn</a>
    <button type="button" id="pl-burger" aria-label="Mở menu" aria-expanded="false"><span></span><span></span><span></span></button>
  </div>
  <nav id="pl-mnav" hidden aria-label="Menu di động"></nav>
</div>
<style>
/* ===== MENU DI ĐỘNG RIÊNG (thay hamburger CMS, đồng bộ nhóm xổ) ===== */
@media(max-width:991px){
  .mean-bar, .meanmenu-reveal, .mean-nav, .mean-push{display:none !important;}
  #pl-mobile-header{flex-wrap:wrap !important;}
  #pl-mobile-header .pl-header-inner{flex:1 1 100% !important;display:flex !important;align-items:center !important;gap:7px !important;}
  #pl-mobile-header .pl-logo{flex:1 1 auto;min-width:0;}
  #pl-mobile-header .pl-btn-tuvan{padding:8px 11px !important;font-size:11px !important;flex-shrink:0;}
  #plSearchOpenMobile{flex-shrink:0;}
  #pl-burger{display:inline-flex;flex-direction:column;justify-content:center;gap:4px;width:42px;height:42px;flex-shrink:0;padding:0;border:1.5px solid rgba(255,213,79,0.55);border-radius:12px;background:linear-gradient(135deg,rgba(255,255,255,0.16),rgba(255,255,255,0.06));cursor:pointer;}
  #pl-burger span{display:block;width:20px;height:2.5px;border-radius:2px;background:#fff;margin:0 auto;transition:transform .25s,opacity .25s;}
  #pl-burger.open span:nth-child(1){transform:translateY(6.5px) rotate(45deg);}
  #pl-burger.open span:nth-child(2){opacity:0;}
  #pl-burger.open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg);}
  #pl-mnav{flex:1 1 100%;width:100%;margin-top:8px;background:linear-gradient(135deg,rgba(31,63,31,0.98),rgba(46,92,46,0.97));border:1.5px solid rgba(255,213,79,0.4);border-radius:14px;padding:8px;max-height:72vh;overflow-y:auto;-webkit-overflow-scrolling:touch;}
  #pl-mnav[hidden]{display:none !important;}
  #pl-mnav .plm-top, #pl-mnav .plm-gtop{display:flex;align-items:center;justify-content:space-between;width:100%;padding:12px 14px;color:#fff;font-family:Quicksand,sans-serif;font-weight:700;font-size:14px;text-decoration:none;background:transparent;border:none;border-radius:10px;cursor:pointer;text-align:left;}
  #pl-mnav .plm-top:active, #pl-mnav .plm-gtop:active{background:rgba(255,255,255,0.08);}
  #pl-mnav .plm-gc{font-size:19px;color:#FFD54F;font-weight:400;line-height:1;}
  #pl-mnav .plm-gsub{display:none;padding:0 0 6px 8px;}
  #pl-mnav .plm-group.open .plm-gsub{display:block;}
  #pl-mnav .plm-gsub a{display:block;padding:10px 14px;color:rgba(255,255,255,0.92);font-family:Quicksand,sans-serif;font-size:13.5px;text-decoration:none;border-radius:8px;border-left:2px solid rgba(255,213,79,0.45);margin:2px 0;}
  #pl-mnav .plm-gsub a:active{background:rgba(255,213,79,0.18);color:#FFEDAE;}
}
@media(min-width:992px){ #pl-burger, #pl-mnav{display:none !important;} }
</style>


<script>
/* Override window.alert — thay thế bằng Glass notification */
window.alert = function(msg){
  var div = document.getElementById('glass-alert-box');
  if(!div){
    div = document.createElement('div');
    div.id = 'glass-alert-box';
    div.style.cssText = [
      'position:fixed','top:50%','left:50%',
      'transform:translate(-50%,-50%)',
      'background:linear-gradient(135deg,rgba(31,63,31,0.96),rgba(46,92,46,0.92))',
      'backdrop-filter:blur(20px)',
      'border:1.5px solid rgba(127,255,160,0.40)',
      'border-radius:20px',
      'padding:32px 28px',
      'max-width:360px','width:90%',
      'text-align:center',
      'z-index:999999',
      'box-shadow:0 24px 60px rgba(0,0,0,0.50)',
      'font-family:Quicksand,sans-serif'
    ].join(';');
    div.innerHTML = '<div style="font-size:40px;margin-bottom:12px">🎉</div>'
      + '<h3 style="color:#FFD54F;font-size:17px;font-weight:800;margin-bottom:10px;">Đăng ký thành công!</h3>'
      + '<p id="glass-alert-msg" style="color:rgba(255,255,255,0.88);font-size:13px;line-height:1.65;margin-bottom:18px;"></p>'
      + '<button onclick="document.getElementById(\'glass-alert-box\').style.display=\'none\'" '
      + 'style="padding:10px 28px;background:linear-gradient(135deg,#1FA84B,#3A8F00);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:800;cursor:pointer;font-family:Quicksand,sans-serif;">Đã hiểu</button>';
    document.body.appendChild(div);
    /* Overlay */
    var overlay = document.createElement('div');
    overlay.id = 'glass-alert-overlay';
    overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.60);z-index:999998;backdrop-filter:blur(4px);';
    overlay.onclick = function(){ div.style.display='none'; overlay.style.display='none'; };
    document.body.appendChild(overlay);
  }
  document.getElementById('glass-alert-msg').textContent = msg || 'Passion Link sẽ liên hệ tư vấn sớm nhất có thể!';
  document.getElementById('glass-alert-box').style.display = 'block';
  document.getElementById('glass-alert-overlay').style.display = 'block';
};
</script>

<!-- Facebook CustomerChat disabled for performance and layout cleanliness -->

    <!-- Google Tag Manager (noscript) -->
<!--<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML2CV5J" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>-->
<!-- End Google Tag Manager (noscript) -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K9XSTVD"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- SEO OPTIMIZATION: Đã loại bỏ thẻ H1 ẩn toàn cục (d-none) để mỗi trang con hiển thị duy nhất 1 thẻ H1 ngữ nghĩa chuẩn xác theo từng khóa học/bài viết -->
<?php
    if($cmsInfo['adv_roll_power'] == 1){
?>
    <div id="divAdLeft">
        <?php
            if (!empty($cmsInfo['adv_roll_left'])) {
                $tmp = explode('.', $cmsInfo['adv_roll_left']);
                if ($tmp[count($tmp) - 1] == 'swf') {
        ?>
                    <object data="<?php echo UPLOAD_URL?>image/<?php echo $cmsInfo['adv_roll_left']?>" 
                    type="application/x-shockwave-flash" width="150" height="300">
                        <param name="movie" value="<?php echo UPLOAD_URL?>image/<?php echo $cmsInfo['adv_roll_left']?>" />
                        <param name="wmode" value="transparent" />
                    </object>
        <?php
                } else {
                    $image = prepareImg(UPLOAD_DIR.'image/'.$cmsInfo['adv_roll_left'], UPLOAD_URL.'image/'.$cmsInfo['adv_roll_left']);
        ?>
                        <a href="<?php echo $cmsInfo['adv_roll_left_link'] ?>"><img src="<?php echo $image?>" alt="Quảng cáo" width="150" height="400" loading="lazy" decoding="async" /></a>
        <?php
                } // end if check adv_roll_left file extension
            } // end if adv_roll_left not empty
            
        ?>
    </div>
    <div id="divAdRight">
        <?php
            if (!empty($cmsInfo['adv_roll_right'])) {
                $tmp = explode('.', $cmsInfo['adv_roll_right']);
                if ($tmp[count($tmp) - 1] == 'swf') {
        ?>
                    <object data="<?php echo UPLOAD_URL?>image/<?php echo $cmsInfo['adv_roll_right']?>" 
                    type="application/x-shockwave-flash" width="150" height="300">
                        <param name="movie" value="<?php echo UPLOAD_URL?>image/<?php echo $cmsInfo['adv_roll_right']?>" />
                        <param name="wmode" value="transparent" />
                    </object>
        <?php
                } else {
                    $image = prepareImg(UPLOAD_DIR.'image/'.$cmsInfo['adv_roll_right'], UPLOAD_URL.'image/'.$cmsInfo['adv_roll_right']);
        ?>
                        <a href="<?php echo $cmsInfo['adv_roll_right_link'] ?>"><img src="<?php echo $image?>" alt="Quảng cáo" width="150" height="400" loading="lazy" decoding="async" /></a>
        <?php
                } // end if check adv_roll_right file extension
            } // end if adv_roll_right not empty
            
            echo isset($error['file_header'])?'<span class="help-block">'.$error['file_header'].'</span>':'';
        ?>
    </div>
<?php
    }
?>
	<div id="box-header">
		<div id="box-header" class="visible-sm visible-xs">
        	<div id="header-sm">
        		<a href="<?php echo BASE_URL ?>" title="Logo">
        			<?php echo $bannerHeaderCode?>
    			</a>
    		</div><!-- header -->
    	</div>
		<div class="container-fluid hidden-sm hidden-xs">
			<div id="header" class="col-md-3 col-sm-3 col-xs-6">
				<a href="https://phache.com.vn/" title="Passion Link" class="pl-desktop-brand">
					<img src="https://phache.com.vn/khoa-tong-hop/logo-passionlink.png" alt="Logo Passion Link" class="pl-desktop-logo-img" width="56" height="56" fetchpriority="high">
					<span class="pl-desktop-brand-text">Passion Link<span class="pl-desktop-tagline">Tiên phong dạy pha chế mở quán<br>trà sữa · cà phê · since 2009</span></span>
				</a>
			</div>
			<div id="menu-page" class="col-md-9 col-sm-9 col-xs-6">
				<ul>
					<?php
						foreach ($listPageMenuTop as $pmt) {
							$pmt['page_url'] = getPageUrl($pmt);
					?>
							<li<?php echo ($pmt['page_id']==$template['page']['page_id'])?' class="current"':''?>>
								<a href="<?php echo $pmt['page_url']?>" title="<?php echo $pmt['page_name']?>">
									<?php echo $pmt['page_name']?>
								</a>
								<?php
									if (isset($pmt['list_child']) && !empty($pmt['list_child'])) {
								?>
									<ul>
										<?php
											foreach ($pmt['list_child'] as $p2) {
												$p2['page_url'] = getPageUrl($p2);
										?>
												<li>
													<a href="<?php echo $p2['page_url']?>" title="<?php echo $p2['page_name']?>">
														<?php echo $p2['page_name']?>
													</a>
												</li>
										<?php
											}
										?>
									</ul>
								<?php
									}
								?>
							</li>
				<?php
		            	} // List menu page
	            ?>
             
		            <li class="pls-menu-li" style="list-style:none;">
		              <button type="button" id="plSearchOpenDesktop" aria-haspopup="dialog" aria-label="Mở tìm kiếm toàn trang"><span class="pls-ico" aria-hidden="true">🔍</span> Tìm kiếm</button>
		            </li>

		            <div class="clear"></div>
		        </ul>
            
			</div>
        	<div class="clear"></div>
    	</div>
    </div><!-- box-header -->
<script>
/* Menu gom nhóm xổ — DÙNG CHUNG cho MÁY TÍNH (#menu-page dropdown) + ĐIỆN THOẠI (#pl-mnav).
   GIỮ ĐỦ link cũ = giữ backlink. */
(function(){
  var MENU=[
    {t:'Trang chủ',h:'https://phache.com.vn/'},
    {t:'Giới thiệu',h:'https://phache.com.vn/passion-link-lich-su-phat-trien.html'},
    {t:'Khóa học',sub:[
      {t:'Các khoá học',h:'https://phache.com.vn/cac-khoa-hoc-day-pha-che.html'},
      {t:'Chuyên đề học',h:'https://phache.com.vn/chuyen-de/'},
      {t:'Học online',h:'https://phache.com.vn/hoc-pha-che-online.html'},
      {t:'Lịch học',h:'https://phache.com.vn/lich-khai-giang.html'}]},
    {t:'Dịch vụ',h:'https://phache.com.vn/dich-vu.html'},
    {t:'Mở quán',sub:[
      {t:'Máy pha chế để mở quán',h:'https://phache.com.vn/may-pha-che.html'},
      {t:'Nguyên liệu pha ngon để mở quán',h:'https://nguyenlieuantoan.com'},
      {t:'Menu quán (mẫu)',h:'https://phache.com.vn/menu-quan/'},
      {t:'Bí quyết pha ngon',h:'https://phache.com.vn/day-pha-che-tra-sua-ngon.html'},
      {t:'Hướng dẫn mở quán',h:'https://phache.com.vn/mo-quan.html'}]},
    {t:'Tin tức',h:'https://phache.com.vn/tin-tuc.html'},
    {t:'Hệ thống chi nhánh',h:'https://phache.com.vn/he-thong-chi-nhanh.html'}
  ];
  function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}

  /* ===== MÁY TÍNH (≥992px): gom nhóm xổ trong #menu-page ===== */
  (function(){
    if(window.matchMedia && !window.matchMedia('(min-width:992px)').matches) return;
    var ul=document.querySelector('#menu-page > ul'); if(!ul) return;
    if(ul.querySelector('.mnu-dd')) return;
    var search=ul.querySelector('.pls-menu-li');
    var html='';
    MENU.forEach(function(m){
      if(m.sub){ html+='<li class="mnu-dd"><a href="#" class="mnu-top">'+esc(m.t)+'<span class="mnu-caret">▾</span></a><ul class="mnu-sub">';
        m.sub.forEach(function(s){ html+='<li><a href="'+s.h+'">'+esc(s.t)+'</a></li>'; }); html+='</ul></li>';
      } else { html+='<li><a href="'+m.h+'">'+esc(m.t)+'</a></li>'; }
    });
    ul.innerHTML=html; if(search) ul.appendChild(search);
    ul.querySelectorAll('.mnu-dd > .mnu-top').forEach(function(a){
      a.addEventListener('click',function(e){ e.preventDefault(); var li=a.parentNode; var open=li.classList.contains('dd-open');
        ul.querySelectorAll('.mnu-dd').forEach(function(x){x.classList.remove('dd-open');}); if(!open) li.classList.add('dd-open'); });
    });
    document.addEventListener('click',function(e){ if(!e.target.closest || !e.target.closest('.mnu-dd')) ul.querySelectorAll('.mnu-dd').forEach(function(x){x.classList.remove('dd-open');}); });
  })();

  /* ===== ĐIỆN THOẠI (≤991px): menu riêng #pl-mnav, thay hamburger CMS ===== */
  (function(){
    var burger=document.getElementById('pl-burger'), nav=document.getElementById('pl-mnav');
    if(!burger||!nav||nav.getAttribute('data-built')) return; nav.setAttribute('data-built','1');
    var html='';
    MENU.forEach(function(m){
      if(m.sub){ html+='<div class="plm-group"><button type="button" class="plm-gtop">'+esc(m.t)+'<span class="plm-gc">+</span></button><div class="plm-gsub">';
        m.sub.forEach(function(s){ html+='<a href="'+s.h+'">'+esc(s.t)+'</a>'; }); html+='</div></div>';
      } else { html+='<a class="plm-top" href="'+m.h+'">'+esc(m.t)+'</a>'; }
    });
    nav.innerHTML=html;
    burger.addEventListener('click',function(){
      if(nav.hasAttribute('hidden')){ nav.removeAttribute('hidden'); burger.classList.add('open'); burger.setAttribute('aria-expanded','true'); }
      else { nav.setAttribute('hidden',''); burger.classList.remove('open'); burger.setAttribute('aria-expanded','false'); }
    });
    nav.querySelectorAll('.plm-gtop').forEach(function(b){
      b.addEventListener('click',function(){ var g=b.parentNode; g.classList.toggle('open'); b.querySelector('.plm-gc').textContent=g.classList.contains('open')?'−':'+'; });
    });
  })();
})();
</script>
    <?php require_once TEMPLATE_DIR . 'slideshow.php'?>
    <div id="main">
    	<div id="content">    
        	<?php require_once TEMPLATE_DIR . $template['file'] . EXT?>
	        <div class="art-blockcontent visible-lg">
	        	<div class="slide_likebox" style="right: -205px; "> 
	        		<div style="color: #000; padding: 8px 5px 0pt 50px;">
	        			<?php include("sign.php");?>  
	        		</div>
	        	</div>
        	</div>
        <div class="clear"></div>
    </div><!-- content -->

<footer id="footere">
  <div class="footer-inner-wrap">
    <div class="footer-top-grid">

      <!-- Cột 1: Logo + Công ty -->
      <div class="footer-brand-col">
        <a href="https://phache.com.vn/" title="Passion Link">
          <img src="https://phache.com.vn/upload/header/logo_1.png" alt="Passion Link" class="footer-logo" width="168" height="56" loading="lazy" decoding="async">
        </a>
        <p class="footer-tagline">Trường dạy pha chế mở quán · since 2009</p>
        <div class="footer-company-block">
          <p class="footer-company-name">CÔNG TY TNHH VUA AN TOÀN</p>
          <p class="footer-company-meta">MST: <strong>0313334177</strong> · GĐ: <strong>Lê Hữu Trí</strong></p>
          <p class="footer-company-meta">07 Nguyễn Đức Thuận, P.Tân Bình, TP.HCM</p>
        </div>
        <div class="footer-social-row">
          <a href="https://www.facebook.com/passionlink" target="_blank" rel="noopener" class="footer-social-btn fb">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.99 4.39 10.95 10.13 11.86V15.47H7.08V12h3.05V9.36c0-3.01 1.79-4.67 4.53-4.67 1.31 0 2.69.23 2.69.23v2.96h-1.52c-1.49 0-1.96.93-1.96 1.88V12h3.33l-.53 3.47h-2.8v8.39C19.61 22.95 24 17.99 24 12c0-6.63-5.37-12-12-12z"/></svg>
            Facebook
          </a>
          <a href="https://www.youtube.com/channel/UCbC-j6JtNBrs82L194uyOZQ" target="_blank" rel="noopener" class="footer-social-btn yt">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            YouTube
          </a>
          <a href="https://zalo.me/3708608045322625044" target="_blank" rel="noopener" class="footer-social-btn zalo">
            Zalo OA
          </a>
        </div>
      </div>

      <!-- Cột 2: 4 Chi nhánh -->
      <div class="footer-branches-col">
        <h4 class="footer-col-title">&#128205; 4 Chi nhánh học</h4>
        <div class="footer-branch-grid">
          <div class="footer-branch">
            <div class="branch-city">TP. HỒ CHÍ MINH</div>
            <div class="branch-addr">07 Nguyễn Đức Thuận, P.Tân Bình, TP.HCM</div>
            <a href="tel:0333033444" class="branch-phone">0333.033.444</a>
          </div>
          <div class="footer-branch">
            <div class="branch-city">HÀ NỘI</div>
            <div class="branch-addr">102 Ngỏ 194 Giải Phóng (Sát 192 Giải Phóng), P.Phương Liệt, TP.Hà Nội</div>
            <a href="tel:0987941426" class="branch-phone">0909.800.676</a>
          </div>
          <div class="footer-branch">
            <div class="branch-city">CẦN THƠ</div>
            <div class="branch-addr">65 Nguyễn Đệ, P.Cái Khế, TP.Cần Thơ</div>
            <a href="tel:0795905508" class="branch-phone">079 590 5508</a>
          </div>
          <div class="footer-branch">
            <div class="branch-city">ĐÀ NẴNG</div>
            <div class="branch-addr">104 Lý Thái Tông, P.Thanh Khê, TP.Đà Nẵng</div>
            <a href="tel:0908006557" class="branch-phone">0908 006 557</a>
          </div>
        </div>
      </div>

      <!-- Cột 3: Links -->
      <div class="footer-links-col">
        <h4 class="footer-col-title">&#128279; Chính sách</h4>
        <ul class="footer-link-list">
          <li><a href="/passion-link-lich-su-phat-trien.html">Giới thiệu Passion Link</a></li>
          <li><a href="/he-thong-chi-nhanh.html">Hệ thống chi nhánh</a></li>
          <li><a href="/tin-tuc/chinh-sach-bao-ve-thong-tin-ca-nhan-cua-nguoi-tieu-dung-456.html">Chính sách bảo mật</a></li>
          <li><a href="/chinh-sach-thanh-toan-hoan-coc.html">Chính sách hoàn cọc</a></li>
          <li><a href="/phuong-thuc-thanh-toan/">Phương thức thanh toán</a></li>
          <li><a href="/chinh-sach-hoc-phi/">Chính sách học phí</a></li>
          <li><a href="/noi-quy-hoc-tap/">Nội quy học tập</a></li>
          <li><a href="/dieu-khoan-su-dung/">Điều khoản sử dụng</a></li>
          <li><a href="/giai-quyet-khieu-nai/">Giải quyết khiếu nại</a></li>
        </ul>
        <div class="footer-hotline-box">
          <div class="hotline-label">&#128222; Hotline tư vấn</div>
          <a href="tel:0977300098" class="hotline-num">0977 300 098</a>
        </div>
      </div>
    </div>

    <div class="footer-copyright">
      <p>&copy; <?php echo date('Y'); ?> <strong>Passion Link</strong> — Đào tạo bí quyết pha chế ngon tự tin mở quán!</p>
      <p><small>CÔNG TY TNHH VUA AN TOÀN &middot; MST 0313334177 &middot; GĐ Lê Hữu Trí &middot; 17 năm tiên phong đào tạo pha chế Việt Nam</small></p>
    </div>
  </div>
</footer>
    <!--<div id="footer">-->
    <!--	<?php echo quotesDecode($cmsInfo['cms_footer'])?>-->
    <!--</div><!-- footer -->
    <?php require_once TEMPLATE_DIR . 'menu_page_res.php'?>
</div><!-- 
<script type='text/javascript'>window._sbzq||function(e){e._sbzq=[];
	var t=e._sbzq;t.push(["_setAccount",9032]);var n=e.location.protocol=="https:"?"https:":"http:";
	var r=document.createElement("script");r.type="text/javascript";
	r.async=true;r.src=n+"//static.subiz.com/public/js/loader.js";var i=document.getElementsByTagName("script")[0];
	i.parentNode.insertBefore(r,i)}(window);
</script>main -->
<!-- CỤM NÚT NỔI LIÊN HỆ GỌN GÀNG DUY NHẤT TRÊN DESKTOP (>= 769px) -->
<div id="pl-desktop-widget" class="pl-fab-container hidden-xs" aria-label="Kênh liên hệ nhanh Passion Link">
  <div class="pl-fab-menu collapsed" id="plFabMenu">
    <a href="tel:0977300098" class="pl-fab-item pl-fab-phone" title="Gọi Hotline: 0977 300 098" onclick="toggleFabMenu()">
      <span class="pl-fab-label">Hotline: 0977 300 098</span>
      <span class="pl-fab-icon" aria-hidden="true">📞</span>
    </a>
    <a href="https://zalo.me/3708608045322625044" target="_blank" rel="noopener" class="pl-fab-item pl-fab-zalo" title="Chat Zalo OA" onclick="toggleFabMenu()">
      <span class="pl-fab-label">Chat Zalo OA</span>
      <span class="pl-fab-icon" aria-hidden="true"><img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" alt="Zalo" width="20" height="20"></span>
    </a>
    <a href="https://m.me/passionlink" target="_blank" rel="noopener" class="pl-fab-item pl-fab-messenger" title="Chat Facebook Messenger" onclick="toggleFabMenu()">
      <span class="pl-fab-label">Chat Messenger</span>
      <span class="pl-fab-icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="#fff"><path d="M12 2C6.48 2 2 6.03 2 11c0 2.87 1.5 5.43 3.86 7.02-.17 1.05-.62 2.62-1.8 3.84 0 0 2.45.1 4.71-1.46.72.2 1.48.3 2.23.3 5.52 0 10-4.03 10-9s-4.48-9-10-9zm1.06 12.15l-2.67-2.85-5.21 2.85 5.73-6.08 2.74 2.85 5.14-2.85-5.73 6.08z"/></svg></span>
    </a>
    <a href="https://phache.com.vn/nhan-uu-dai/" class="pl-fab-item pl-fab-tuvan" title="Đăng ký nhận tư vấn & ưu đãi 40%" onclick="toggleFabMenu()">
      <span class="pl-fab-label">Đăng ký tư vấn</span>
      <span class="pl-fab-icon" aria-hidden="true">📋</span>
    </a>
  </div>
  <button type="button" class="pl-fab-main" id="plFabMain" onclick="toggleFabMenu()" aria-label="Mở kênh liên hệ" title="Liên hệ tư vấn Passion Link">
    <span class="pl-fab-main-icon">💬</span>
    <span class="pl-fab-badge">Hỗ trợ</span>
  </button>
</div>

<script>
function toggleFabMenu(){
  var m = document.getElementById('plFabMenu');
  var btn = document.getElementById('plFabMain');
  if(m){
    var isNowCollapsed = m.classList.toggle('collapsed');
    if(btn){
      var badge = btn.querySelector('.pl-fab-badge');
      var icon = btn.querySelector('.pl-fab-main-icon');
      if(!isNowCollapsed){
        btn.classList.add('active');
        if(badge) badge.textContent = 'Đóng';
        if(icon) icon.textContent = '✕';
      } else {
        btn.classList.remove('active');
        if(badge) badge.textContent = 'Hỗ trợ';
        if(icon) icon.textContent = '💬';
      }
    }
  }
}

// Đóng menu khi click ra ngoài
document.addEventListener('click', function(e){
  var widget = document.getElementById('pl-desktop-widget');
  var m = document.getElementById('plFabMenu');
  if(widget && m && !m.classList.contains('collapsed')){
    if(!widget.contains(e.target)){
      toggleFabMenu();
    }
  }
});
</script>
<script src="<?php echo TEMPLATE_JS?>jquery.meanmenu.min.js" defer></script>
<script src="<?php echo TEMPLATE_JS?>readmore.js" defer></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" defer></script>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            if ($.fn.meanmenu) {
                $('.mobile_menu').meanmenu({
                    meanDisplay: "none",
                    meanScreenWidth: "991"
                });
            }
            if ($.fn.readmore) {
                $('#trainee .intro').readmore({
                    moreLink:'<a href="#" class="more">Chi tiết ></a>',
                    lessLink: '<a href="#" class="less">Thu lại</a>',
                    embedCSS: false,
                    collapsedHeight: 89,
                });
            }
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 600,
                    easing: 'ease-in-out',
                    once: true,
                    disable: false, /* FIX: mobile cần AOS để elements không bị opacity:0 */
                    mirror: false
                });
            }
        });
    </script>
    <script>
    <?php
    if (isset($_SESSION['cta_success'])) {
    ?>
        setTimeout(function() {
                $('#modalCtaSuscess').modal('show');
        }, 1000);
        localStorage.setItem("callToAction", true);
    <?php
        unset($_SESSION['cta_success']);
    }
    ?>
    var cta = localStorage.getItem("callToAction");
    if (!cta) {
        setTimeout(function() {
            $('#autoModal').modal('show');
        }, 60000); /* 60 giây — 1 phút ở trang mới hiện popup */
    }

			(function() {
				function initYouTubeFacade() {
					var ytFrames = document.querySelectorAll(".youtube-frame[data-embed]");
					ytFrames.forEach(function(frame) {
						if (frame.getAttribute('data-facade-ready')) return;
						frame.setAttribute('data-facade-ready', '1');
						var vid = frame.dataset.embed;
						if (!vid) return;

						frame.innerHTML = '';
						frame.style.position = 'relative';
						frame.style.cursor = 'pointer';

						var img = document.createElement('img');
						img.src = "https://img.youtube.com/vi/" + vid + "/hqdefault.jpg";
						img.alt = "Video Passion Link";
						img.loading = "lazy";
						img.style.cssText = "width:100%;height:100%;object-fit:cover;display:block;";
						frame.appendChild(img);

						var playBtn = document.createElement('div');
						playBtn.className = 'play-button';
						playBtn.innerHTML = '<span style="color:#fff;font-size:24px;margin-left:3px;">&#9654;</span>';
						frame.appendChild(playBtn);

						frame.addEventListener("click", function(e) {
							e.preventDefault();
							var iframe = document.createElement("iframe");
							iframe.setAttribute("frameborder", "0");
							iframe.setAttribute("allowfullscreen", "");
							iframe.setAttribute("allow", "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture");
							iframe.setAttribute("src", "https://www.youtube.com/embed/" + vid + "?rel=0&showinfo=0&autoplay=1");
							iframe.style.cssText = "position:absolute;top:0;left:0;width:100%;height:100%;border:0;";
							frame.innerHTML = "";
							frame.appendChild(iframe);
						});
					});
				}

				if (document.readyState === 'loading') {
					document.addEventListener('DOMContentLoaded', initYouTubeFacade);
				} else {
					initYouTubeFacade();
				}
			})();
		</script>
<style>

/**
FOOTER
*/

@media (min-width:901px){
  .footer-top-grid{ grid-template-columns:1.1fr 1.35fr 1.4fr !important; }
  .footer-links-col .footer-link-list{ column-count:2 !important; column-gap:24px; }
  .footer-links-col .footer-link-list li{ -webkit-column-break-inside:avoid; break-inside:avoid; }
}
.footer-links-col .footer-hotline-box{ margin-top:16px; }


.clearfix-20 {
    clear: both;
    height: 20px;
}

.clearfix-40 {
    clear: both;
    height: 40px;
}

.image_feat {
    margin-top: 20px;
    display: flex;
     justify-content: center;
    align-items: center;
}

@media screen and (max-width: 568px) {
    .image_feat img {
        max-width: 200px;
    }
}

footer {
    width: 100%;
    position: relative;
    padding-bottom: 10px;
    overflow: hidden;
}

footer:before {
    width: 50%;
    content: '';
    top: 0;
    left: 0;
    position: absolute;
    background: #222;
    height: 100%
}

footer:after {
    width: 50%;
    content: '';
    top: 0;
    right: 0;
    position: absolute;
    background: #333;
    height: 100%
}

footer .footer_left .logo_img {
    position: relative;
    z-index: 999;
    display: block;
    width: 200px;
    height: 70px;
    margin: 0 auto;
}

footer .footer_left .logo_img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

footer .fanpage_facebook {
    /*width: 100%*/
}

.footer_left {
    padding-top: 30px;
    text-align: left;
    width: 100%;
    float: left;
    z-index: 9;
    color: #fff;
    font-size: 15px;
    position: relative
}

.footer_left .footer_leftinfo p {
    margin-bottom: 5px;
    color: #ffffff;
    font-weight: 400;
}

.footer_left .footer_leftinfo span {
    color: #b4b4b4
}

.footer_left .footer_leftinfo h4 {
    color: #ffffff;
    font-family: 'Nunito Sans', sans-serif;
    font-size: 13px;
    font-weight: 700;
    padding: 0 0 10px;
    text-transform: uppercase;
    line-height: 1.5;
}
.footer_left .footer_leftinfo h4 i {
    color: #999;
    margin-right: 10px;
}

.footer_left .address-item {
    margin-bottom: 15px;
}

.footer_left .address-item:last-child {
    margin-bottom: 0;
}

.footer_left .menuseocial {
    padding: 15px 0;
    border-bottom: 1px solid #fff;
    border-top: 1px solid #fff;
    text-align: center;
}

.footer_left .menuseocial ul li {
    text-align: center;
    list-style: none;
    display: inline-table;
    width: 19%;
    padding: 0 10px;
}

.footer_left .menuseocial ul li a {
    color: #fff;
    text-transform: uppercase
}

.footer_left .menuseocial ul li a:hover, .footer_right ul li:hover a {
    color: #f36f25
}

.footer_left .menuseocial ul {
    padding: 0;
    margin: 0;
    width: 100%
}

.copyright a {
    color: #999;
    text-align: center
}

.footer_right {
    width: 100%;
    float: right;
    z-index: 9;
    color: #fff;
    font-size: 15px;
    position: relative;
    padding-top: 20px;
}

.footer_right ul {
    padding: 0;
    margin: 0
}

.footer_right ul li {
    list-style: none;
    padding: 10px 0;
    border-bottom: 1px dashed #5c5c5c
}

.footer_right h3 {
    font-size: 18px;
    font-family: 'Nunito Sans', sans-serif;
    color: #f36f25;
    text-transform: uppercase;
    position: relative;
    padding-bottom: 15px;
    margin-bottom: 25px
}

.footer_right h3:after {
    position: absolute;
    bottom: 0;
    width: 40px;
    height: 3px;
    background: #5c5c5c;
    content: '';
    left: 0
}

.footer_right ul li:last-child {
    border-bottom: 0px !important
}

.footer_right ul li a {
    color: #fff
}

#footer_b {
    background: #222;
    padding: 10px 0;
    text-align: center;
    color: #fff
}

#footer_b span {
    color: #666
}

@media only screen and (max-width:768px) {

}


@media only screen and (max-width: 1024px) {
    footer:before, footer:after {
        width: 100%;
    }
}

@media only screen and (max-width:768px) {
    footer:before,
    footer:after {
        width: 100%
    }
}


    .fix_tel{
        position:fixed;
        bottom:30px;
        left:18px;
        z-index:999
    }
    .fix_tel a{
        text-decoration:none;
        display:block
    }
    .tel{
        background:#499400;
        width:162px;
        height:40px;
        position:relative;
        overflow:hidden;
        background-size:40px;
        border-radius:28px;
        border:solid 1px #499400
    }
    .ring-alo-phone{
        background-color:transparent;
        cursor:pointer;
        height:80px;
        position:absolute;
        transition:visibility 0.5s ease 0s;
        visibility:hidden;
        width:80px;
        z-index:200000 !important
    }
    .ring-alo-phone.ring-alo-show{
        visibility:visible
    }
    .ring-alo-phone.ring-alo-hover,.ring-alo-phone:hover{
        opacity:1
    }
    .ring-alo-ph-circle{
        /*animation:1.2s ease-in-out 0s normal none infinite running ring-alo-circle-anim;*/
         /*animation:1s ease-in-out 0s normal none infinite running ring-alo-circle-anim;*/
         -webkit-animation:ring-alo-circle-anim 1.2s infinite ease-in-out;
    animation:ring-alo-circle-anim 1.2s infinite ease-in-out;
        background-color:transparent;
        border:2px solid rgba(30,30,30,0.4);
        border-radius:100%;
        height:70px;
        left:10px;
        opacity:0.1;
        position:absolute;
        top:12px;
        transform-origin:50% 50% 0;
        transition:all 0.5s ease 0s;
        width:70px
    }
    .ring-alo-phone.ring-alo-active .ring-alo-ph-circle{
        animation:1.1s ease-in-out 0s normal none infinite running ring-alo-circle-anim !important
    }
    .ring-alo-phone.ring-alo-static .ring-alo-ph-circle{
        animation:2.2s ease-in-out 0s normal none infinite running ring-alo-circle-anim !important
    }
    .ring-alo-phone.ring-alo-hover .ring-alo-ph-circle,.ring-alo-phone:hover .ring-alo-ph-circle{
        border-color:#509142;
        opacity:0.5
    }
    .ring-alo-phone.ring-alo-green.ring-alo-hover .ring-alo-ph-circle,.ring-alo-phone.ring-alo-green:hover .ring-alo-ph-circle{
        border-color:#baf5a7;
        opacity:0.5
    }
    .ring-alo-phone.ring-alo-green .ring-alo-ph-circle{
        border-color:#509142;
        /* opacity:0.5;
         */
    }
    .ring-alo-ph-circle-fill{
        animation:2.3s ease-in-out 0s normal none infinite running ring-alo-circle-fill-anim;
        background-color:#000;
        border:2px solid transparent;
        border-radius:100%;
        height:30px;
        left:30px;
        opacity:0.1;
        position:absolute;
        top:33px;
        transform-origin:50% 50% 0;
        transition:all 0.5s ease 0s;
        width:30px;
    }
    .ring-alo-phone.ring-alo-hover .ring-alo-ph-circle-fill,.ring-alo-phone:hover .ring-alo-ph-circle-fill{
        background-color:rgba(0,175,242,0.5);
        opacity:0.75 !important
    }
    .ring-alo-phone.ring-alo-green.ring-alo-hover .ring-alo-ph-circle-fill,.ring-alo-phone.ring-alo-green:hover .ring-alo-ph-circle-fill{
        background-color:rgba(117,235,80,0.5);
        opacity:0.75 !important
    }
    .ring-alo-phone.ring-alo-green .ring-alo-ph-circle-fill{
        background-color:rgba(0,175,242,0.5);
        opacity:0.75 !important
    }
    .ring-alo-ph-img-circle{
        animation:1s ease-in-out 0s normal none infinite running ring-alo-circle-img-anim;
        border:2px solid transparent;
        border-radius:100%;
        height:30px;
        left:30px;
        opacity:1;
        position:absolute;
        top:33px;
        transform-origin:50% 50% 0;
        width:30px;
    }
    .ring-alo-phone.ring-alo-hover .ring-alo-ph-img-circle,.ring-alo-phone:hover .ring-alo-ph-img-circle{
        background-color:#509142
    }
    .ring-alo-phone.ring-alo-green.ring-alo-hover .ring-alo-ph-img-circle,.ring-alo-phone.ring-alo-green:hover .ring-alo-ph-img-circle{
        background-color:#75eb50
    }
    .ring-alo-phone.ring-alo-green .ring-alo-ph-img-circle{
        background-color:#509142
    }

@keyframes ring-alo-circle-anim{
    0% {
        -webkit-transform:rotate(0) scale(.5) skew(1deg);
        transform:rotate(0) scale(.5) skew(1deg);
        -webkit-opacity:.1
    }

    30% {
        -webkit-transform:rotate(0) scale(.7) skew(1deg);
        transform:rotate(0) scale(.7) skew(1deg);
        -webkit-opacity:.5
    }

    100% {
        -webkit-transform:rotate(0) scale(1) skew(1deg);
        transform:rotate(0) scale(1) skew(1deg);
        -webkit-opacity:.1
    }
}
@keyframes ring-alo-circle-img-anim{
    0%{
        transform:rotate(0deg) scale(1) skew(1deg)
    }
    10%{
        transform:rotate(-25deg) scale(1) skew(1deg)
    }
    20%{
        transform:rotate(25deg) scale(1) skew(1deg)
    }
    30%{
        transform:rotate(-25deg) scale(1) skew(1deg)
    }
    40%{
        transform:rotate(25deg) scale(1) skew(1deg)
    }
    50%{
        transform:rotate(0deg) scale(1) skew(1deg)
    }
    100%{
        transform:rotate(0deg) scale(1) skew(1deg)
    }
}
@keyframes ring-alo-circle-fill-anim{
    0%{
        opacity:0.2;
        transform:rotate(0deg) scale(0.7) skew(1deg)
    }
    50%{
        opacity:0.2;
        transform:rotate(0deg) scale(1) skew(1deg)
    }
    100%{
        opacity:0.2;
        transform:rotate(0deg) scale(0.7) skew(1deg)
    }
}
.chat_face {
  position: fixed; /* Cố định vị trí icon Messenger trên màn hình */
  bottom: 50px; /* Cách mép dưới màn hình 65px */
  right: 0px; /* Cách mép phải màn hình 0px */
  margin: 0px;
  z-index: 10000; /* Ưu tiên hiển thị cao nhất */
}

.chat_face:hover {
  transform: scale(1.1); /* Hiệu ứng phóng to nhẹ khi hover */
  transition: all 0.3s ease; /* Thời gian chuyển đổi mượt mà */
}

.ring-alo-chat {
  position: relative; /* Định vị tương đối để canh chỉnh icon bên trong */
  width: 100px; /* Kích thước khung chứa icon */
  height: 100px;
  cursor: pointer; /* Con trỏ tay khi rê chuột */
  z-index: 10000; /* Ưu tiên cao */
}

.ring-alo-ph-img-circle {
  display: flex; /* Căn giữa hình ảnh theo cả trục ngang và dọc */
  justify-content: center;
  align-items: center;
  position: absolute;
  width: 50px; /* Đường kính khung tròn trắng */
  height: 50px;
  top: 15px; /* Để căn giữa trong khung 80x80 */
  left: 15px;
  border-radius: 100%; /* Làm tròn khung nền */
  background-color: #fff; /* Nền trắng phía sau icon */
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.3); /* Đổ bóng nhẹ */
}

.ring-alo-ph-img-circle img {
  width: 32px; /* Kích thước icon Messenger */
  height: 32px;
  object-fit: contain; /* Đảm bảo hình ảnh không bị méo */
}

/* Bỏ các vòng hiệu ứng không cần thiết */
.ring-alo-ph-circle, /* Vòng ngoài lan tỏa - đã bỏ */
.ring-alo-ph-circle-fill, /* Vòng nền mờ lan tỏa - đã bỏ */
@keyframes ring-alo-ring, /* Animation rung lắc - đã bỏ */
@keyframes ring-alo-circle-anim,
@keyframes ring-alo-circle-fill-anim {
  display: none; /* Không dùng hiệu ứng này nữa */
}


.zalo-connect {
            position: fixed;
            bottom: 0; /* Đặt nút Zalo ở dưới cùng của màn hình */
            right: 15px;  /* Đặt nút Zalo ở góc phải màn hình */
            margin: 20px; /* Khoảng cách so với cạnh màn hình */
            width: 50px;
            height: 50px;
            background-color: #0084ff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 9999; /* Đảm bảo luôn ở trên cùng */
        }

        .zalo-connect:hover {
            transform: scale(1.1);
        }

        .zalo-connect img {
            width: 30px;
            height: 30px;
        }

.fone {
    font-size: 1.75rem;
    color: #fff;
    line-height: 40px;
    font-weight: normal;
    padding-left: 18px;
    margin: 0 0;
}

.custom-modal .form-container {
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 360px;
    padding: 20px;
    box-sizing: border-box;
}

.custom-modal .form-container h4 {
    font-size: 24px;
    color: #333;
    text-align: center;
    margin-bottom: 10px;
}

.custom-modal .form-container p {
    font-size: 14px;
    color: #555;
    text-align: center;
    margin-bottom: 20px;
}

.custom-modal .form-group {
    margin-bottom: 15px;
}

.custom-modal .form-group label {
    display: block;
    font-size: 14px;
    color: #333;
    margin-bottom: 5px;
}

.custom-modal .form-group input[type="text"],
.custom-modal .form-group input[type="tel"] {
    width: 100%;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    height: 45px;
}

.custom-modal .form-group input:focus {
    outline: none;
    border-color: #76c893;
}

.custom-modal .form-options {
    background: #eafaea;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.custom-modal .form-options label {
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
    font-weight: 600;
}

.custom-modal .form-options input[type="radio"] {
    margin-right: 10px;
}

.custom-modal .form-options-group {
    margin-bottom: 15px;
}

.custom-modal .submit-btn {
    display: block;
    width: 100%;
    background: #4bae4f;
    color: #fff;
    padding: 6px;
    text-align: center;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
}

.custom-modal .submit-btn:hover {
    background: #333;
}
.custom-modal .modal-body {
    max-height: 720px;
    overflow-y: auto;
}
@media (max-width: 768px) {
    .custom-modal .modal-body {
        max-height: 600px;
    }
}

.custom-modal .modal-dialog {
  display: flex;
  align-items: center;
}
.custom-modal.modal {
    z-index: 9999999;
}

.custom-modal .modal-content {
  margin: 0 auto;
}



/* ══ FOOTER MỚI — CÔNG TY TNHH VUA AN TOÀN ══ */
#footere { background:linear-gradient(135deg,#1F3F1F 0%,#2d5a2d 60%,#1a3a1a 100%)!important; color:rgba(255,255,255,.90)!important; padding:0!important; font-family:'Nunito Sans',sans-serif!important; }
#footere::before,#footere::after{display:none!important;}
.footer-inner-wrap{max-width:1200px;margin:0 auto;padding:48px 24px 0;}
.footer-top-grid{display:grid;grid-template-columns:1.1fr 1.5fr 1fr;gap:32px;padding-bottom:36px;border-bottom:1px solid rgba(255,255,255,.12);}
.footer-logo{height:56px;width:auto;background:#fff;padding:8px 14px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.25);margin-bottom:14px;display:block;}
.footer-tagline{color:rgba(255,255,255,.75);font-size:13px;margin-bottom:14px;}
.footer-company-block{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:12px 14px;margin-bottom:16px;}
.footer-company-name{color:#FFD54F!important;font-weight:800!important;font-size:13px!important;margin-bottom:4px!important;}
.footer-company-meta{color:rgba(255,255,255,.75)!important;font-size:12px!important;margin-bottom:3px!important;}
.footer-company-meta strong{color:#7FFFA0!important;}
.footer-social-row{display:flex;gap:8px;flex-wrap:wrap;}
.footer-social-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 12px;background:rgba(255,255,255,.10);border:1.5px solid rgba(255,255,255,.20);border-radius:50px;color:#fff!important;font-size:12px;font-weight:700;text-decoration:none!important;transition:all .2s;}
.footer-social-btn:hover{transform:translateY(-2px);color:#fff!important;}
.footer-social-btn.fb:hover{background:#1877F2;border-color:#1877F2;}
.footer-social-btn.yt:hover{background:#FF0000;border-color:#FF0000;}
.footer-social-btn.zalo:hover{background:#0068FF;border-color:#0068FF;}
.footer-col-title{color:#FFD54F!important;font-size:14px!important;font-weight:800!important;margin-bottom:14px!important;letter-spacing:.02em;padding-bottom:8px;border-bottom:2px solid rgba(127,255,160,.25);}
.footer-branch-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.footer-branch{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);border-radius:10px;padding:10px 12px;}
.branch-city{color:#7FFFA0;font-weight:800;font-size:11.5px;letter-spacing:.04em;margin-bottom:3px;}
.branch-addr{color:rgba(255,255,255,.65);font-size:11px;line-height:1.5;margin-bottom:5px;}
.branch-phone{color:#FFD54F!important;font-weight:700;font-size:13.5px;text-decoration:none!important;display:block;}
.branch-phone:hover{color:#FFE082!important;}
.footer-link-list{list-style:none!important;padding:0!important;margin:0 0 10px!important;}
.footer-link-list li{margin-bottom:7px;}
.footer-link-list a{color:rgba(255,255,255,.80)!important;text-decoration:none!important;font-size:13px!important;transition:color .2s;}
.footer-link-list a:hover{color:#FFD54F!important;}
.footer-hotline-box{background:linear-gradient(135deg,rgba(255,213,79,.12),rgba(255,167,38,.08));border:1.5px solid rgba(255,213,79,.35);border-radius:12px;padding:12px 16px;text-align:center;margin-top:12px;}
.hotline-label{color:rgba(255,255,255,.75);font-size:11.5px;font-weight:600;margin-bottom:3px;}
.hotline-num{color:#FFD54F!important;font-size:20px!important;font-weight:800!important;text-decoration:none!important;}
.hotline-num:hover{color:#FFE082!important;}
.footer-copyright{text-align:center;padding:20px 0 28px;}
.footer-copyright p{color:rgba(255,255,255,.65);font-size:13px;margin-bottom:4px;}
.footer-copyright strong{color:#FFD54F;}
@media(max-width:900px){.footer-top-grid{grid-template-columns:1fr;}.footer-branch-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:600px){.footer-inner-wrap{padding:32px 16px 0;}.footer-branch-grid{grid-template-columns:1fr;}}
</style>



<style>
/* Ẩn nút đỏ "Đăng ký khoá học" gốc của CMS */
.dknt_index_title { display: none !important; }

</style>


<!-- PANEL ĐĂNG KÝ TƯ VẤN MỚI — tách khỏi CMS hoàn toàn -->
<div id="tuvan-panel" style="display:none;position:fixed;right:0;top:50%;transform:translateY(-50%);width:280px;z-index:100000;font-family:Quicksand,sans-serif;">
  <div style="background:linear-gradient(135deg,rgba(31,63,31,0.95),rgba(46,92,46,0.92));backdrop-filter:blur(20px);border:1.5px solid rgba(127,255,160,0.35);border-radius:16px 0 0 16px;padding:20px 16px;box-shadow:-8px 0 32px rgba(0,0,0,0.30);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,0.15);">
      <span style="color:#FFD54F;font-weight:800;font-size:14px;">📋 Đăng ký tư vấn miễn phí</span>
      <button onclick="closeTuVanPanel()" style="background:rgba(255,255,255,0.15);border:none;color:#fff;width:28px;height:28px;border-radius:50%;font-size:16px;cursor:pointer;flex-shrink:0;line-height:1;display:flex;align-items:center;justify-content:center;">✕</button>
    </div>
    <form method="post" action="" id="tuvan-form" onsubmit="return submitTuVan(event, this)">
      <input type="hidden" name="template_function" value="saveSign">
      <div style="margin-bottom:10px;">
        <label style="color:rgba(255,255,255,0.85);font-size:12px;font-weight:700;display:block;margin-bottom:4px;">Họ và tên <span style="color:#FF7043">(*)</span></label>
        <input type="text" name="dk_name" placeholder="Nhập họ và tên đầy đủ" required style="width:100%;padding:9px 12px;background:rgba(46,92,46,0.60);border:1.5px solid rgba(255,255,255,0.25);border-radius:8px;color:#fff;font-size:13px;font-family:Quicksand,sans-serif;box-sizing:border-box;">
      </div>
      <div style="margin-bottom:10px;">
        <label style="color:rgba(255,255,255,0.85);font-size:12px;font-weight:700;display:block;margin-bottom:4px;">Email <span style="color:#FF7043">(*)</span></label>
        <input type="email" name="dk_email" placeholder="example@gmail.com" required pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}" style="width:100%;padding:9px 12px;background:rgba(46,92,46,0.60);border:1.5px solid rgba(255,255,255,0.25);border-radius:8px;color:#fff;font-size:13px;font-family:Quicksand,sans-serif;box-sizing:border-box;">
        <div style="font-size:10px;color:rgba(255,255,255,0.50);margin-top:3px;">Đúng định dạng: ten@domain.com</div>
      </div>
      <div style="margin-bottom:10px;">
        <label style="color:rgba(255,255,255,0.85);font-size:12px;font-weight:700;display:block;margin-bottom:4px;">Số điện thoại <span style="color:#FF7043">(*)</span></label>
        <div style="display:flex;gap:6px;">
          <select name="dk_phone_code" style="width:90px;padding:9px 6px;background:rgba(46,92,46,0.60);border:1.5px solid rgba(255,255,255,0.25);border-radius:8px;color:#fff;font-size:12px;font-family:Quicksand,sans-serif;flex-shrink:0;">
            <option value="+84" selected>🇻🇳 +84</option>
            <option value="+1">🇺🇸 +1</option>
            <option value="+65">🇸🇬 +65</option>
            <option value="+66">🇹🇭 +66</option>
            <option value="+60">🇲🇾 +60</option>
            <option value="+61">🇦🇺 +61</option>
            <option value="+44">🇬🇧 +44</option>
            <option value="+33">🇫🇷 +33</option>
          </select>
          <input type="tel" name="dk_number" placeholder="0901234567 (10 số)" required maxlength="15" pattern="0[0-9]{9}|[0-9]{10,12}" style="width:100%;padding:9px 12px;background:rgba(46,92,46,0.60);border:1.5px solid rgba(255,255,255,0.25);border-radius:8px;color:#fff;font-size:13px;font-family:Quicksand,sans-serif;box-sizing:border-box;flex:1;">
        </div>
        <div style="font-size:10px;color:rgba(255,255,255,0.50);margin-top:3px;">Việt Nam: 10 số bắt đầu bằng 0 · Quốc tế: chọn mã vùng</div>
      </div>
      <div style="margin-bottom:10px;">
        <label style="color:rgba(255,255,255,0.85);font-size:12px;font-weight:700;display:block;margin-bottom:4px;">Ngày sinh</label>
        <input type="text" name="dk_birthday" placeholder="VD: 15/06/1995" style="width:100%;padding:9px 12px;background:rgba(46,92,46,0.60);border:1.5px solid rgba(255,255,255,0.25);border-radius:8px;color:#fff;font-size:13px;font-family:Quicksand,sans-serif;box-sizing:border-box;">
      </div>
      <div style="margin-bottom:10px;">
        <label style="color:rgba(255,255,255,0.85);font-size:12px;font-weight:700;display:block;margin-bottom:4px;">Nơi ở hiện tại <span style="color:#FF7043">(*)</span></label>
        <input type="text" name="dk_home" placeholder="VD: Quận 1, TP.HCM" required style="width:100%;padding:9px 12px;background:rgba(46,92,46,0.60);border:1.5px solid rgba(255,255,255,0.25);border-radius:8px;color:#fff;font-size:13px;font-family:Quicksand,sans-serif;box-sizing:border-box;">
      </div>
      <div style="margin-bottom:10px;">
        <label style="color:rgba(255,255,255,0.85);font-size:12px;font-weight:700;display:block;margin-bottom:4px;">Khoá học quan tâm</label>
        <select name="dk_class" style="width:100%;padding:9px 12px;background:rgba(46,92,46,0.60);border:1.5px solid rgba(255,255,255,0.25);border-radius:8px;color:#fff;font-size:13px;font-family:Quicksand,sans-serif;box-sizing:border-box;">
          <option value="">-- Chọn chuyên đề --</option>
          <option value="Học Pha Chế Tổng Hợp">Học Pha Chế Tổng Hợp</option>
          <option value="Học Chuyên Về Trà Sữa">Học Chuyên Về Trà Sữa</option>
          <option value="Học Chuyên Về Trà Trái Cây">Học Chuyên Về Trà Trái Cây</option>
          <option value="Học Chuyên Về Cà Phê - Barista">Học Chuyên Về Cà Phê - Barista</option>
          <option value="Học Chuyên Về Rau Má Mix">Học Chuyên Về Rau Má Mix</option>
          <option value="Học Chuyên Về Trà Chanh">Học Chuyên Về Trà Chanh</option>
          <option value="Học Chuyên Về Các Món Đá Xay">Học Chuyên Về Các Món Đá Xay</option>
          <option value="Học Chuyên Về Đồ Uống Nóng">Học Chuyên Về Đồ Uống Nóng</option>
          <option value="Học Chuyên Về Tàu Hủ Thái - Singapore">Học Chuyên Về Tàu Hủ Thái - Singapore</option>
          <option value="Học Chuyên Về Các Món Chè">Học Chuyên Về Các Món Chè</option>
          <option value="Học Chuyên Về Làm Kem">Học Chuyên Về Làm Kem</option>
          <option value="Học Chuyên Về Ăn Vặt">Học Chuyên Về Ăn Vặt</option>
          <option value="Học Chuyên Về Bingsu - Kakigori">Học Chuyên Về Bingsu - Kakigori</option>
          <option value="Học Chuyên Về Sữa Chua">Học Chuyên Về Sữa Chua</option>
          <option value="Học Chuyên Về Sinh Tố Nước Ép">Học Chuyên Về Sinh Tố Nước Ép</option>
          <option value="Học Chuyên Về Sữa Hạt">Học Chuyên Về Sữa Hạt</option>
        </select>
      </div>
      <button type="submit" style="width:100%;padding:11px;background:linear-gradient(135deg,#1FA84B,#3A8F00);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:800;cursor:pointer;font-family:Quicksand,sans-serif;box-shadow:0 6px 16px rgba(31,168,75,0.40);margin-top:4px;">Gửi đăng ký</button>
    </form>
    
<div id="tuvan-success" style="display:none;text-align:center;padding:24px 16px;">
  <div style="font-size:44px;margin-bottom:10px;">🎉</div>
  <h3 style="color:#FFD54F;font-size:17px;font-weight:800;margin-bottom:10px;font-family:Quicksand,sans-serif;">Đăng ký thành công!</h3>
  <p style="color:rgba(255,255,255,0.92);font-size:13px;line-height:1.65;font-family:Quicksand,sans-serif;margin-bottom:14px;">
    Anh/Chị đã đăng ký thành công! Cám ơn Anh/Chị đã đăng ký tư vấn tại <strong>Passion Link</strong>! Chúng em sẽ tư vấn sớm nhất cho Anh/Chị nếu anh chị cần gấp vui lòng kết bạn zalo số: <strong style="color:#FFD54F;">0333033444</strong> và gửi tin nhắn cho em nhé!
  </p>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:14px;flex-wrap:wrap;">
    <a href="https://zalo.me/0333033444" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:#0068FF;color:#fff;border-radius:8px;font-size:13px;font-weight:800;text-decoration:none;font-family:Quicksand,sans-serif;box-shadow:0 4px 12px rgba(0,104,255,0.35);">💬 Nhắn Zalo 0333033444</a>
    <button onclick="document.getElementById('tuvan-panel').style.display='none';document.getElementById('tuvan-success').style.display='none';document.getElementById('tuvan-form').style.display='block';" style="padding:9px 18px;background:rgba(255,255,255,0.20);color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;font-family:Quicksand,sans-serif;">Đóng</button>
  </div>
</div>
<script>
function submitTuVan(e, form){
  if(!validateTuVan(form)) return false;
  e.preventDefault();
  var name   = (form.dk_name   ? form.dk_name.value   : '').trim();
  var email  = (form.dk_email  ? form.dk_email.value  : '').trim();
  var code   = (form.dk_phone_code ? form.dk_phone_code.value : '+84');
  var num    = (form.dk_number ? form.dk_number.value : '').trim();
  var phone  = code + ' ' + num;
  var birth  = (form.dk_birthday ? form.dk_birthday.value : '').trim();
  var home   = (form.dk_home   ? form.dk_home.value   : '').trim();
  var course = (form.dk_class  ? form.dk_class.value  : '').trim();
  var BASE   = 'https://phache.com.vn/';

  /* Lưu saveSign — hiện trong admin "Đăng ký học" */
  var d1 = new URLSearchParams();
  d1.append('template_function','saveSign');
  d1.append('dk_name', name);
  d1.append('dk_email', email);
  d1.append('dk_number', phone);
  d1.append('dk_birthday', birth);
  d1.append('dk_home', home);
  d1.append('dk_class', course + ' · Tư vấn từ trang chủ');
  fetch(BASE,{method:'POST',mode:'no-cors',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:d1.toString()}).catch(function(){});

  /* Backup saveCallToAction — hiện trong admin "Gọi hành động" */
  var d2 = new URLSearchParams();
  d2.append('template_function','saveCallToAction');
  d2.append('cta_name', name);
  d2.append('cta_phone', phone);
  d2.append('cta_course', course || 'Tư vấn chung');
  d2.append('cta_purpose', 'Học mở quán');
  fetch(BASE,{method:'POST',mode:'no-cors',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:d2.toString()}).catch(function(){});

  if (typeof plTrackFormSubmit === 'function') {
    plTrackFormSubmit('tuvan_panel');
  }

  /* Hiện success message */
  setTimeout(function(){
    form.style.display='none';
    document.getElementById('tuvan-success').style.display='block';
  }, 300);
  return false;
}
</script>
    <script>
    function validateTuVan(form){
      var phone = form.dk_number.value.trim();
      var email = form.dk_email.value.trim();
      // Validate SĐT VN: 10 số bắt đầu 0
      var code = form.dk_phone_code.value;
      if(code==='+84' && !/^0[0-9]{9}$/.test(phone)){
        alert('Số điện thoại Việt Nam phải đủ 10 số, bắt đầu bằng 0 (VD: 0901234567)');
        return false;
      }
      // Validate email
      if(!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)){
        alert('Email chưa đúng định dạng. VD: ten@gmail.com');
        return false;
      }
      return true;
    }
    </script>
  </div>
</div>

<!-- Tab trigger -->
<div id="tuvan-tab" class="hidden-xs hidden-sm" onclick="toggleTuVanPanel()" style="position:fixed;right:0;top:45%;transform:translateY(-50%) rotate(180deg);writing-mode:vertical-rl;background:linear-gradient(135deg,#1FA84B,#3A8F00);color:#fff;font-weight:800;font-size:13px;padding:16px 10px;border-radius:0 0 10px 10px;cursor:pointer;font-family:Quicksand,sans-serif;box-shadow:-4px 0 16px rgba(0,0,0,0.25);z-index:100001;letter-spacing:0.04em;user-select:none;">📋 Đăng ký tư vấn</div>

<style>
/* Ẩn CMS slide form gốc hoàn toàn */
.art-blockcontent.visible-lg { display: none !important; }
#tuvan-panel input::placeholder { color: rgba(255,255,255,0.45) !important; }
</style>


<style>
/* Mobile: panel full-screen, ẩn triệt để tuvan-tab ở cạnh màn hình */
@media(max-width:991px){
  #tuvan-panel{width:100%!important;top:0!important;bottom:0!important;transform:none!important;border-radius:0!important;overflow-y:auto!important;max-height:100vh!important;}
  #tuvan-panel > div{border-radius:0!important;min-height:100vh;overflow-y:auto;}
  #tuvan-tab{display:none !important;visibility:hidden !important;}
}
#tuvan-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.50);z-index:99999;}
</style>
<div id="tuvan-backdrop" onclick="closeTuVanPanel()"></div>
<script>
function openTuVanPanel(){
  document.getElementById('tuvan-panel').style.display='block';
  var bd=document.getElementById('tuvan-backdrop');
  if(bd)bd.style.display='block';
  /* Ẩn tab khi panel mở */
  var tab=document.getElementById('tuvan-tab');
  if(tab)tab.style.display='none';
}
function closeTuVanPanel(){
  document.getElementById('tuvan-panel').style.display='none';
  var bd=document.getElementById('tuvan-backdrop');
  if(bd)bd.style.display='none';
  var f=document.getElementById('tuvan-form');
  var s=document.getElementById('tuvan-success');
  if(f)f.style.display='';
  if(s)s.style.display='none';
  /* Hiện lại tab khi panel đóng */
  var tab=document.getElementById('tuvan-tab');
  if(tab && window.innerWidth > 991)tab.style.display='';
}
function toggleTuVanPanel(){
  var p=document.getElementById('tuvan-panel');
  if(!p.style.display||p.style.display==='none'){openTuVanPanel();}
  else{closeTuVanPanel();}
}
</script>


<!-- ===== TÌM KIẾM TOÀN TRANG (Passion Link) ===== -->
<div id="plPalette" class="pls-overlay" role="dialog" aria-modal="true" aria-label="Tìm kiếm toàn trang Passion Link" hidden>
  <div class="pls-box" role="document">
    <div class="pls-bar">
      <span class="pls-bar-ico" aria-hidden="true">🔍</span>
      <input id="plPaletteInput" type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
             placeholder="Tìm khoá học, bí quyết, tin tức…"
             role="combobox" aria-expanded="true" aria-controls="plPaletteResults"
             aria-autocomplete="list" aria-label="Nhập từ khoá tìm kiếm">
      <button type="button" id="plPaletteClear" class="pls-iconbtn" aria-label="Xoá từ khoá" hidden>✕</button>
      <button type="button" id="plPaletteClose" class="pls-iconbtn" aria-label="Đóng tìm kiếm">×</button>
    </div>
    <div id="plPaletteLive" class="pls-sr-only" aria-live="polite" aria-atomic="true"></div>
    <div id="plPaletteIdle">
      <div class="pls-idle-label">Gợi ý tìm nhanh:</div>
      <div class="pls-chips"></div>
    </div>
    <div id="plPaletteResults" role="listbox" aria-label="Kết quả tìm kiếm" hidden></div>
  </div>
</div>
<link rel="stylesheet" href="https://phache.com.vn/search.css?v=13b">
<script src="https://phache.com.vn/search.js?v=13b" defer></script>

<!-- BOTTOM ACTION BAR CHO MOBILE (< 768px) - 3 NÚT CHUẨN CRO -->
<div id="pl-bottom-bar" role="navigation" aria-label="Thanh hành động nhanh">
  <a href="tel:0977300098" class="pl-bar-btn pl-bar-call" title="Gọi Hotline: 0977.300.098" onclick="plTrackHotline()">
    <span class="pl-bar-ico" aria-hidden="true">📞</span>
    <span class="pl-bar-txt">Gọi Hotline</span>
  </a>
  <a href="https://zalo.me/0977300098" target="_blank" rel="noopener noreferrer" class="pl-bar-btn pl-bar-zalo" title="Chat Zalo Tư Vấn" onclick="plTrackZalo('sticky_bottom_bar', this.href)">
    <span class="pl-bar-ico" aria-hidden="true">💬</span>
    <span class="pl-bar-txt">Chat Zalo</span>
    <span class="pl-zalo-mini-badge">Báo giá 5p</span>
  </a>
  <a href="https://phache.com.vn/nhan-uu-dai/" class="pl-bar-btn pl-bar-lead" title="Đăng Ký Tư Vấn & Nhận Ưu Đãi 40%" onclick="if(typeof plTrackConversion==='function'){plTrackConversion('click_open_tuvan_panel','sticky_bottom_bar',100000);}">
    <span class="pl-bar-ico" aria-hidden="true">🎁</span>
    <span class="pl-bar-txt">Nhận Ưu Đãi</span>
  </a>
</div>

<!-- MOBILE FLOATING TOC BUTTON & DRAWER -->
<button type="button" id="pl-toc-fab" class="pl-toc-fab" title="Mục lục bài viết" aria-label="Mục lục bài viết" onclick="plToggleTocDrawer(true)" style="display:none;">
  <span class="pl-toc-fab-icon">📑</span>
  <span class="pl-toc-fab-text">Mục lục</span>
</button>

<div id="pl-toc-backdrop" class="pl-toc-backdrop" onclick="plToggleTocDrawer(false)"></div>
<div id="pl-toc-drawer" class="pl-toc-drawer" aria-hidden="true">
  <div class="pl-toc-drawer-header">
    <span class="pl-toc-drawer-title">📑 Mục Lục Bài Viết</span>
    <button type="button" class="pl-toc-drawer-close" onclick="plToggleTocDrawer(false)" aria-label="Đóng">✕</button>
  </div>
  <div id="pl-toc-drawer-content" class="pl-toc-drawer-content"></div>
</div>

<!-- MODAL EXIT-INTENT LEAD MAGNET -->
<div id="pl-exit-modal" class="pl-exit-overlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="pl-exit-title">
  <div class="pl-exit-card">
    <button type="button" class="pl-exit-close" onclick="plCloseExitModal()" aria-label="Đóng">✕</button>
    <div class="pl-exit-badge">🎁 QUÀ TẶNG ĐỘC QUYỀN MỞ QUÁN 2026</div>
    <h3 id="pl-exit-title" class="pl-exit-title">Tặng Ebook 50 Công Thức Trà Sữa Độc Quyền & Bảng Tính Cost Excel</h3>
    <p class="pl-exit-desc">Bí quyết định lượng cost chuẩn từng giọt, tối ưu biên lãi 70% và cẩm nang vận hành quầy bar từ chuyên gia Passion Link.</p>
    
    <form id="pl-exit-form" onsubmit="return plSubmitExitLead(event);">
      <div class="pl-modal-group">
        <label for="pl-exit-name">Họ và tên của bạn:</label>
        <input type="text" id="pl-exit-name" required placeholder="Ví dụ: Nguyễn Văn A" class="pl-modal-input">
      </div>
      <div class="pl-modal-group">
        <label for="pl-exit-phone">Số điện thoại Zalo nhận tài liệu:</label>
        <input type="tel" id="pl-exit-phone" required pattern="[0-9]{10,11}" placeholder="Ví dụ: 0977300098" class="pl-modal-input">
      </div>
      <button type="submit" id="pl-exit-btn" class="pl-modal-submit-btn">
        🚀 Nhận Ebook & File Excel Miễn Phí Ngay
      </button>
    </form>

    <div id="pl-exit-success" class="pl-exit-success" style="display:none;">
      <div class="pl-exit-success-icon">🎉</div>
      <h4 class="pl-exit-success-title">Đăng Ký Thành Công!</h4>
      <p class="pl-exit-success-desc">Đội ngũ Passion Link đang gửi trọn bộ Ebook 50 Công Thức & Bảng Tính Cost Excel qua Zalo cho bạn trong giây lát.</p>
    </div>
    <div class="pl-exit-footer">Cam kết bảo mật thông tin · Miễn phí 100%</div>
  </div>
</div>

<script>
function plTrackHotline(loc, phone) {
  var location = loc || 'sticky_mobile_bar';
  var phoneNum = phone || '0977300098';
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    'event': 'click_hotline',
    'button_location': location,
    'phone_number': phoneNum
  });
  if (typeof gtag === 'function') {
    gtag('event', 'click_hotline', {
      'event_category': 'Engagement',
      'event_label': location,
      'button_location': location,
      'phone_number': phoneNum,
      'value': 1
    });
  }
}

function plTrackZalo(loc, targetUrl) {
  var location = loc || 'sticky_mobile_bar';
  var url = targetUrl || 'https://zalo.me/0977300098';
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    'event': 'click_zalo',
    'button_location': location,
    'zalo_url': url
  });
  if (typeof gtag === 'function') {
    gtag('event', 'click_zalo', {
      'event_category': 'Engagement',
      'event_label': location,
      'button_location': location,
      'zalo_url': url,
      'value': 1
    });
  }
}

function plTrackFormSubmit(formName) {
  var name = formName || 'consultation_form';
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    'event': 'generate_lead',
    'form_name': name
  });
  if (typeof gtag === 'function') {
    gtag('event', 'generate_lead', {
      'event_category': 'Lead',
      'event_label': name,
      'form_name': name,
      'value': 1
    });
  }
  if (typeof plTrackLeadOnce === 'function') {
    plTrackLeadOnce(name);
  }
}

/* CONVERSION TRACKING CHUẨN KÉP GA4 & GOOGLE ADS */
function plTrackConversion(eventName, locationName, estimatedValue) {
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    event: eventName,
    event_category: 'conversion',
    conversion_location: locationName || 'sticky_bottom_bar',
    value: estimatedValue || 0,
    currency: 'VND'
  });
  if (typeof gtag === 'function') {
    gtag('event', eventName, {
      event_category: 'conversion',
      event_label: locationName || 'sticky_bottom_bar',
      value: estimatedValue || 0,
      currency: 'VND'
    });
    if (eventName === 'generate_lead') {
      gtag('event', 'conversion', {
        'send_to': 'AW-16775247010/lead_form',
        'value': estimatedValue || 500000,
        'currency': 'VND'
      });
    }
  }
}

/* CHỐNG TRÙNG LẶP SỰ KIỆN LEAD TRONG 4 GIÂY */
var _plLeadTrackedTime = 0;
function plTrackLeadOnce(formSource) {
  var now = Date.now();
  if (now - _plLeadTrackedTime > 4000) {
    _plLeadTrackedTime = now;
    plTrackConversion('generate_lead', formSource || 'lead_form', 500000);
  }
}

// 1. GA4 RETENTION EVENT TRACKING: TIME SPENT (30s, 60s, 120s)
(function initTimeSpentTracking() {
  var milestones = [
    { t: 30, ev: 'time_spent_30s' },
    { t: 60, ev: 'time_spent_60s' },
    { t: 120, ev: 'time_spent_120s' }
  ];
  milestones.forEach(function(m) {
    setTimeout(function() {
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
        'event': m.ev,
        'time_seconds': m.t
      });
      if (typeof gtag === 'function') {
        gtag('event', m.ev, {
          'event_category': 'Engagement',
          'event_label': m.t + 's',
          'time_seconds': m.t
        });
      }
    }, m.t * 1000);
  });
})();

// 2. READING PROGRESS BAR & SCROLL DEPTH TRACKING (25%, 50%, 75%, 90%)
(function initScrollAndProgressBar() {
  var scrollMarkers = [25, 50, 75, 90];
  var trackedMarkers = {};

  function onScroll() {
    var winH = window.innerHeight || document.documentElement.clientHeight;
    var docH = Math.max(
      document.body.scrollHeight, document.documentElement.scrollHeight,
      document.body.offsetHeight, document.documentElement.offsetHeight,
      document.body.clientHeight, document.documentElement.clientHeight
    );
    var scrollable = docH - winH;
    if (scrollable <= 0) return;
    var scrollY = window.pageYOffset || document.documentElement.scrollTop;
    var pct = Math.round((scrollY / scrollable) * 100);

    // Cập nhật thanh tiến trình đọc 3px (#2C7A7B)
    var bar = document.getElementById('pl-reading-progress');
    if (bar) {
      bar.style.width = Math.min(100, Math.max(0, pct)) + '%';
    }

    // Bắn sự kiện Scroll Depth 1 lần duy nhất cho mỗi mốc
    scrollMarkers.forEach(function(marker) {
      if (pct >= marker && !trackedMarkers[marker]) {
        trackedMarkers[marker] = true;
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
          'event': 'scroll_depth',
          'percent_scrolled': marker
        });
        if (typeof gtag === 'function') {
          gtag('event', 'scroll_depth', {
            'event_category': 'Engagement',
            'event_label': marker + '%',
            'percent_scrolled': marker
          });
        }
      }
    });
  }

  window.addEventListener('scroll', function() {
    window.requestAnimationFrame ? window.requestAnimationFrame(onScroll) : onScroll();
  }, { passive: true });

  document.addEventListener('DOMContentLoaded', onScroll);
})();

// 3. EXIT-INTENT LEAD MAGNET MODAL LOGIC
function plOpenExitModal() {
  if (sessionStorage.getItem('pl_exit_shown') || sessionStorage.getItem('pl_exit_submitted')) {
    return;
  }
  sessionStorage.setItem('pl_exit_shown', '1');
  var modal = document.getElementById('pl-exit-modal');
  if (modal) {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
}

function plCloseExitModal() {
  var modal = document.getElementById('pl-exit-modal');
  if (modal) {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
}

function plSubmitExitLead(e) {
  if (e && e.preventDefault) e.preventDefault();
  var nameInp = document.getElementById('pl-exit-name');
  var phoneInp = document.getElementById('pl-exit-phone');
  var name = (nameInp ? nameInp.value : '').trim();
  var phone = (phoneInp ? phoneInp.value : '').trim();

  if (!name || !phone) {
    alert('Vui lòng nhập họ tên và số điện thoại Zalo.');
    return false;
  }

  // 1. Bắn sự kiện GA4 generate_lead
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    'event': 'generate_lead',
    'lead_source': 'exit_intent',
    'lead_name': name,
    'lead_phone': phone
  });
  if (typeof gtag === 'function') {
    gtag('event', 'generate_lead', {
      'lead_source': 'exit_intent',
      'event_category': 'Lead',
      'event_label': 'Ebook 50 Cong Thuc Tra Sua & Bang Cost Excel',
      'value': 1
    });
  }

  // 2. Gửi dữ liệu về CMS admin
  var BASE = 'https://phache.com.vn/';
  var d1 = new URLSearchParams();
  d1.append('template_function', 'saveSign');
  d1.append('dk_name', name);
  d1.append('dk_email', '');
  d1.append('dk_number', phone);
  d1.append('dk_class', 'Ebook 50 Công Thức & Bảng Cost 2026 · Exit-Intent Lead');
  fetch(BASE, { method: 'POST', mode: 'no-cors', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: d1.toString() }).catch(function(){});

  var d2 = new URLSearchParams();
  d2.append('template_function', 'saveCallToAction');
  d2.append('cta_name', name);
  d2.append('cta_phone', phone);
  d2.append('cta_course', 'Ebook 50 Công Thức & Bảng Cost 2026');
  d2.append('cta_purpose', 'Nhận Ebook & Bảng Cost Excel');
  fetch(BASE, { method: 'POST', mode: 'no-cors', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: d2.toString() }).catch(function(){});

  // 3. Hiện thông báo thành công
  var formElem = document.getElementById('pl-exit-form');
  var succElem = document.getElementById('pl-exit-success');
  if (formElem) formElem.style.display = 'none';
  if (succElem) succElem.style.display = 'block';

  sessionStorage.setItem('pl_exit_submitted', '1');
  setTimeout(function() {
    plCloseExitModal();
  }, 4000);
  return false;
}

(function initExitIntent() {
  document.addEventListener('DOMContentLoaded', function() {
    // Desktop: Bắt sự kiện mouseleave lên mép trên màn hình
    document.addEventListener('mouseleave', function(e) {
      if (e.clientY <= 10) {
        plOpenExitModal();
      }
    });

    // Mobile: Bắt sự kiện nút Back (popstate)
    setTimeout(function() {
      if (!sessionStorage.getItem('pl_exit_shown')) {
        try {
          history.pushState({ plExitArmed: true }, '', window.location.href);
        } catch(err) {}
      }
    }, 4500);

    window.addEventListener('popstate', function(e) {
      if (!sessionStorage.getItem('pl_exit_shown')) {
        plOpenExitModal();
      }
    });
  });
})();

// 4. STICKY TABLE OF CONTENTS (MỤC LỤC THÔNG MINH) & SCROLLSPY
function plToggleInlineToc() {
  var body = document.getElementById('pl-inline-toc-body');
  var btn = document.getElementById('pl-inline-toc-btn');
  if (!body || !btn) return;
  if (body.style.display === 'none') {
    body.style.display = 'block';
    btn.textContent = 'Thu gọn [−]';
  } else {
    body.style.display = 'none';
    btn.textContent = 'Mở rộng [+]';
  }
}

function plToggleTocDrawer(open) {
  var drawer = document.getElementById('pl-toc-drawer');
  var backdrop = document.getElementById('pl-toc-backdrop');
  if (!drawer || !backdrop) return;
  if (open) {
    drawer.classList.add('open');
    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  } else {
    drawer.classList.remove('open');
    backdrop.classList.remove('open');
    document.body.style.overflow = '';
  }
}

(function initSmartTOC() {
  document.addEventListener('DOMContentLoaded', function() {
    var article = document.getElementById('news_content') || document.getElementById('html_page_content');
    if (!article) return;

    var headings = article.querySelectorAll('h2, h3');
    if (!headings || headings.length < 2) return;

    var tocItems = [];
    headings.forEach(function(h, idx) {
      if (!h.id) {
        h.id = 'pl-toc-node-' + (idx + 1);
      }
      var tag = h.tagName.toLowerCase();
      var title = (h.textContent || '').trim();
      if (title) {
        tocItems.push({ id: h.id, title: title, level: tag });
      }
    });

    if (tocItems.length < 2) return;

    var listHtml = '<ul class="pl-toc-list">';
    tocItems.forEach(function(item) {
      listHtml += '<li class="pl-toc-item pl-toc-' + item.level + '">' +
        '<a href="#' + item.id + '" data-target="' + item.id + '" class="pl-toc-link">' +
          item.title +
        '</a>' +
      '</li>';
    });
    listHtml += '</ul>';

    // 1. Tạo Box mục lục nội dung Inline ở đầu bài viết
    var inlineToc = document.createElement('div');
    inlineToc.className = 'pl-inline-toc';
    inlineToc.id = 'pl-inline-toc';
    inlineToc.innerHTML =
      '<div class="pl-inline-toc-header" onclick="plToggleInlineToc()">' +
        '<span class="pl-inline-toc-title">📑 Mục Lục Nội Dung</span>' +
        '<button type="button" class="pl-inline-toc-toggle" id="pl-inline-toc-btn">Thu gọn [−]</button>' +
      '</div>' +
      '<div class="pl-inline-toc-body" id="pl-inline-toc-body">' +
        listHtml +
      '</div>';

    var firstH1 = article.querySelector('h1');
    if (firstH1 && firstH1.nextSibling) {
      firstH1.parentNode.insertBefore(inlineToc, firstH1.nextSibling);
    } else if (article.firstChild) {
      article.insertBefore(inlineToc, article.firstChild);
    } else {
      article.appendChild(inlineToc);
    }

    // 2. Đưa nội dung mục lục vào Mobile Drawer
    var drawerContent = document.getElementById('pl-toc-drawer-content');
    if (drawerContent) {
      drawerContent.innerHTML = listHtml;
    }

    // 3. Hiện nút nổi Mục lục trên Mobile
    var fab = document.getElementById('pl-toc-fab');
    if (fab) {
      fab.style.display = 'flex';
    }

    // 4. Bắt sự kiện click chuyển mượt mà (Smooth scroll) trừ hao Header
    document.addEventListener('click', function(e) {
      var target = e.target.closest ? e.target.closest('a[data-target]') : null;
      if (!target) return;
      e.preventDefault();
      var targetId = target.getAttribute('data-target');
      var targetElem = document.getElementById(targetId);
      if (targetElem) {
        plToggleTocDrawer(false);
        var headerOffset = 75;
        var elementPosition = targetElem.getBoundingClientRect().top;
        var offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    });

    // 5. ScrollSpy - Highlight mục đang đọc
    function updateActiveToc() {
      var fromTop = window.scrollY + 100;
      var currentId = '';
      headings.forEach(function(h) {
        if (h.offsetTop <= fromTop) {
          currentId = h.id;
        }
      });
      if (currentId) {
        document.querySelectorAll('.pl-toc-link').forEach(function(link) {
          if (link.getAttribute('data-target') === currentId) {
            link.classList.add('active');
          } else {
            link.classList.remove('active');
          }
        });
      }
    }
    window.addEventListener('scroll', function() {
      window.requestAnimationFrame ? window.requestAnimationFrame(updateActiveToc) : updateActiveToc();
    }, { passive: true });
  });
})();

// Lắng nghe tự động toàn bộ liên kết Gọi điện thoại, Chat Zalo và Gửi Form trên toàn trang
document.addEventListener('DOMContentLoaded', function() {
  document.addEventListener('click', function(e) {
    var a = e.target.closest ? e.target.closest('a') : null;
    if (!a) return;
    var href = a.getAttribute('href') || '';
    if (href.indexOf('tel:') === 0) {
      var phone = href.replace('tel:', '').replace(/\s+/g, '');
      var loc = a.getAttribute('data-location') || a.id || a.className || 'body_link';
      plTrackHotline(loc, phone);
    } else if (href.indexOf('zalo.me') !== -1) {
      var loc = a.getAttribute('data-location') || a.id || a.className || 'body_link';
      plTrackZalo(loc, href);
    }
  }, { passive: true });

  document.addEventListener('submit', function(e) {
    var form = e.target;
    if (form && form.tagName === 'FORM' && form.id !== 'pl-exit-form') {
      var formId = form.id || form.getAttribute('name') || form.className || 'form_lead';
      plTrackFormSubmit(formId);
    }
  }, { passive: true });
});
</script>

<style>
/* THANH TIẾN TRÌNH ĐỌC (READING PROGRESS BAR) */
#pl-reading-progress {
  position: fixed;
  top: 0;
  left: 0;
  height: 3px;
  width: 0%;
  background: #2C7A7B;
  z-index: 999999;
  transition: width 0.1s ease-out;
  pointer-events: none;
}

/* STICKY TABLE OF CONTENTS (MỤC LỤC BÀI VIẾT INLINE) */
.pl-inline-toc {
  background: linear-gradient(135deg, rgba(247, 250, 249, 0.98) 0%, rgba(237, 247, 243, 0.94) 100%);
  border: 1.5px solid rgba(44, 122, 123, 0.25);
  border-left: 5px solid #2C7A7B;
  border-radius: 14px;
  padding: 16px 20px;
  margin: 22px 0 28px 0;
  box-shadow: 0 4px 16px rgba(44, 122, 123, 0.08);
  font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  box-sizing: border-box;
}
.pl-inline-toc-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
}
.pl-inline-toc-title {
  font-size: 16px;
  font-weight: 800;
  color: #1a4d3f;
  margin: 0;
}
.pl-inline-toc-toggle {
  font-size: 12px;
  color: #2C7A7B;
  font-weight: 700;
  background: rgba(44, 122, 123, 0.12);
  padding: 4px 10px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  transition: background 0.2s;
}
.pl-inline-toc-toggle:hover {
  background: rgba(44, 122, 123, 0.22);
}
.pl-toc-list {
  margin: 12px 0 0 0;
  padding: 0 0 0 16px;
  list-style: none;
}
.pl-toc-item {
  margin-bottom: 7px;
  line-height: 1.4;
}
.pl-toc-h3 {
  padding-left: 16px;
  font-size: 13.5px;
  position: relative;
}
.pl-toc-h3::before {
  content: "•";
  color: #2C7A7B;
  position: absolute;
  left: 4px;
  font-weight: bold;
}
.pl-toc-link {
  color: #24483c;
  text-decoration: none !important;
  font-size: 14.5px;
  font-weight: 600;
  transition: all 0.15s ease;
}
.pl-toc-link:hover, .pl-toc-link.active {
  color: #2C7A7B !important;
  font-weight: 800;
  text-decoration: underline !important;
}

/* NÚT MỤC LỤC NỔI & DRAWER — ĐẶT GÓC TRÁI DƯỚI (BOTTOM-LEFT) CẤM CHE CÁC NÚT ACTION */
.pl-toc-fab {
  display: none;
  position: fixed;
  left: 28px;
  right: auto !important;
  bottom: 28px;
  z-index: 99985;
  background: linear-gradient(135deg, #1b4d3e 0%, #0d2b22 100%);
  color: #ffffff;
  border: 1.5px solid #FFD54F;
  border-radius: 28px;
  padding: 8px 16px;
  align-items: center;
  gap: 7px;
  font-family: inherit;
  font-weight: 800;
  font-size: 13.5px;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.35);
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.pl-toc-fab:hover {
  transform: translateY(-2px) scale(1.03);
  box-shadow: 0 6px 24px rgba(0, 0, 0, 0.45);
  border-color: #ffe082;
}
@media (max-width: 768px) {
  .pl-toc-fab {
    left: 12px;
    right: auto !important;
    bottom: calc(68px + env(safe-area-inset-bottom, 0px));
    padding: 7px 12px;
    font-size: 12px;
    border-radius: 24px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.3);
  }
}
.pl-toc-backdrop {
  display: none;
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  z-index: 999997;
  opacity: 0;
  transition: opacity 0.3s ease;
}
.pl-toc-backdrop.open {
  display: block;
  opacity: 1;
}
.pl-toc-drawer {
  position: fixed;
  top: 0; right: 0; bottom: 0;
  width: 320px;
  max-width: 86vw;
  background: #ffffff;
  z-index: 999998;
  box-shadow: -6px 0 25px rgba(0,0,0,0.25);
  transform: translateX(100%);
  transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
}
.pl-toc-drawer.open {
  transform: translateX(0);
}
.pl-toc-drawer-header {
  padding: 16px;
  border-bottom: 1.5px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f8fafc;
}
.pl-toc-drawer-title {
  font-weight: 800;
  font-size: 16px;
  color: #1a4d3f;
}
.pl-toc-drawer-close {
  background: none;
  border: none;
  font-size: 20px;
  color: #64748b;
  cursor: pointer;
}
.pl-toc-drawer-content {
  padding: 16px;
  overflow-y: auto;
  flex: 1;
}

/* EXIT-INTENT LEAD MAGNET MODAL */
.pl-exit-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.68);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  z-index: 9999999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  box-sizing: border-box;
  animation: plFadeIn 0.25s ease-out;
}
.pl-exit-card {
  background: linear-gradient(135deg, #ffffff 0%, #f4fbf8 100%);
  border: 1.5px solid rgba(44, 122, 123, 0.3);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
  border-radius: 20px;
  max-width: 480px;
  width: 100%;
  padding: 28px 24px;
  position: relative;
  box-sizing: border-box;
  text-align: center;
  font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.pl-exit-close {
  position: absolute;
  top: 14px; right: 14px;
  width: 32px; height: 32px;
  border-radius: 50%;
  background: #e2e8f0;
  border: none;
  font-size: 16px;
  font-weight: 700;
  color: #475569;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}
.pl-exit-close:hover {
  background: #cbd5e1;
}
.pl-exit-badge {
  display: inline-block;
  background: rgba(230, 126, 34, 0.12);
  color: #d35400;
  font-size: 12px;
  font-weight: 800;
  padding: 4px 14px;
  border-radius: 20px;
  margin-bottom: 10px;
}
.pl-exit-title {
  font-size: clamp(19px, 3vw, 22px);
  font-weight: 800;
  color: #1a4d3f;
  margin: 0 0 10px 0;
  line-height: 1.35;
}
.pl-exit-desc {
  font-size: 14px;
  color: #475569;
  margin: 0 0 18px 0;
  line-height: 1.5;
}
.pl-modal-group {
  text-align: left;
  margin-bottom: 14px;
}
.pl-modal-group label {
  display: block;
  font-size: 13px;
  font-weight: 700;
  color: #334155;
  margin-bottom: 5px;
}
.pl-modal-input {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid #cbd5e1;
  border-radius: 10px;
  font-family: inherit;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
  transition: border-color 0.2s;
}
.pl-modal-input:focus {
  border-color: #2C7A7B;
  box-shadow: 0 0 0 3px rgba(44, 122, 123, 0.15);
}
.pl-modal-submit-btn {
  width: 100%;
  background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
  color: #ffffff;
  border: none;
  padding: 12px 18px;
  border-radius: 12px;
  font-family: inherit;
  font-size: 15px;
  font-weight: 800;
  cursor: pointer;
  box-shadow: 0 6px 20px rgba(211, 84, 0, 0.4);
  transition: all 0.2s ease;
  margin-top: 6px;
}
.pl-modal-submit-btn:hover {
  background: linear-gradient(135deg, #d35400 0%, #b84400 100%);
  transform: translateY(-1px);
}
.pl-exit-success {
  padding: 20px 0;
}
.pl-exit-success-icon {
  font-size: 42px;
  margin-bottom: 8px;
}
.pl-exit-success-title {
  font-size: 20px;
  font-weight: 800;
  color: #25c056;
  margin: 0 0 6px 0;
}
.pl-exit-success-desc {
  font-size: 14px;
  color: #475569;
  margin: 0;
  line-height: 1.5;
}
.pl-exit-footer {
  font-size: 12px;
  color: #94a3b8;
  margin-top: 14px;
}
@keyframes plFadeIn {
  from { opacity: 0; transform: scale(0.96); }
  to { opacity: 1; transform: scale(1); }
}

/* BOTTOM ACTION BAR (MOBILE & TABLET CLEANUP) */
#pl-bottom-bar { display: none; }

@media (max-width: 991px) {
  /* Ẩn triệt để toàn bộ cụm nút nổi cũ trên mobile và tablet */
  .fix_tel, 
  .fix_tel *, 
  .tel, 
  .chat_face, 
  #ring-alo-phoneIcon, 
  #messengerIcon, 
  .zalo-connect, 
  #tuvan-tab, 
  .fb-customerchat, 
  .fb_dialog,
  .slide_likebox {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
  }
}

@media (max-width: 768px) {
  body {
    padding-bottom: 66px !important;
  }
  #pl-bottom-bar {
    display: flex !important;
    position: fixed !important;
    bottom: 0 !important;
    left: 0 !important;
    right: 0 !important;
    height: 56px;
    background: linear-gradient(135deg, rgba(31,63,31,0.98) 0%, rgba(46,92,46,0.96) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-top: 1.5px solid rgba(255,213,79,0.55);
    box-shadow: 0 -4px 20px rgba(0,0,0,0.30);
    z-index: 99998;
    padding: 6px 8px;
    padding-bottom: calc(6px + env(safe-area-inset-bottom, 0px));
    gap: 8px;
    box-sizing: border-box;
    align-items: stretch;
  }
  .pl-bar-btn {
    flex: 1 1 33.33%;
    width: 33.33%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    text-decoration: none !important;
    border-radius: 8px;
    font-family: 'Nunito Sans', 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 800;
    font-size: 12px;
    cursor: pointer;
    border: none;
    transition: transform .15s ease, opacity .15s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.18);
    padding: 0 2px;
  }
  .pl-bar-btn:active {
    transform: scale(0.97);
    opacity: 0.9;
  }
  .pl-bar-call {
    background: linear-gradient(135deg, #d32f2f, #b71c1c);
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
  }
  .pl-bar-zalo {
    position: relative;
    background: linear-gradient(135deg, #0068FF, #0052cc);
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
    animation: plPulseBarZalo 2.4s infinite ease-in-out;
  }
  @keyframes plPulseBarZalo {
    0%, 100% {
      transform: scale(1);
      box-shadow: 0 2px 6px rgba(0,104,255,0.30);
    }
    50% {
      transform: scale(1.025);
      box-shadow: 0 4px 12px rgba(0,104,255,0.60);
    }
  }
  .pl-zalo-mini-badge {
    position: absolute;
    top: -6px;
    right: 3px;
    background: #FFD54F;
    color: #1F3F1F;
    font-size: 8.5px;
    font-weight: 900;
    line-height: 1;
    padding: 2px 5px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.25);
    letter-spacing: 0.2px;
    text-transform: uppercase;
    pointer-events: none;
  }
  .pl-bar-lead {
    background: linear-gradient(135deg, #FF6B35 0%, #E53935 100%);
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.35);
    animation: plPulseBarLead 2s infinite ease-in-out;
  }
  @keyframes plPulseBarLead {
    0%, 100% {
      transform: scale(1);
      box-shadow: 0 2px 6px rgba(229,57,53,0.35);
    }
    50% {
      transform: scale(1.03);
      box-shadow: 0 4px 12px rgba(229,57,53,0.65);
    }
  }
  .pl-bar-ico {
    font-size: 16px;
    line-height: 1;
    flex-shrink: 0;
  }
  .pl-bar-txt {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
  }
}
</style>


<style>
/* ===== CỤM NÚT NỔI DESKTOP UNIFIED WIDGET ===== */
.pl-fab-container {
  position: fixed;
  bottom: 28px;
  right: 28px;
  z-index: 99990;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.pl-fab-menu {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 10px;
  margin-bottom: 12px;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.pl-fab-menu.collapsed {
  display: none !important;
}
.pl-fab-item {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  text-decoration: none !important;
  color: #ffffff !important;
  background: linear-gradient(135deg, rgba(31,63,31,0.96) 0%, rgba(46,92,46,0.94) 100%);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  padding: 8px 16px;
  border-radius: 28px;
  border: 1.5px solid rgba(255,213,79,0.45);
  box-shadow: 0 4px 18px rgba(0,0,0,0.22);
  transition: all 0.2s ease;
  cursor: pointer;
  font-size: 13.5px;
  font-weight: 700;
  border: none;
}
.pl-fab-item:hover {
  transform: translateX(-4px) scale(1.02);
  box-shadow: 0 6px 24px rgba(0,0,0,0.32);
  color: #FFD54F !important;
  border: 1.5px solid #FFD54F !important;
}
.pl-fab-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  font-size: 14px;
}
.pl-fab-phone .pl-fab-icon { background: #1FA84B; }
.pl-fab-zalo .pl-fab-icon { background: #0068FF; }
.pl-fab-messenger .pl-fab-icon { background: linear-gradient(135deg, #00B2FE, #006AFF); }
.pl-fab-tuvan .pl-fab-icon { background: #FFA726; color: #1F2419; }

.pl-fab-main {
  display: flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #1FA84B 0%, #157333 100%);
  color: #fff;
  border: 2px solid #FFD54F;
  border-radius: 32px;
  padding: 10px 20px;
  font-family: inherit;
  font-weight: 800;
  font-size: 14px;
  box-shadow: 0 6px 24px rgba(31,168,75,0.45);
  cursor: pointer;
  transition: all 0.25s ease;
}
.pl-fab-main:hover {
  transform: translateY(-2px) scale(1.03);
  box-shadow: 0 8px 30px rgba(31,168,75,0.60);
  background: linear-gradient(135deg, #25c056 0%, #1a8a3e 100%);
}
.pl-fab-main-icon {
  font-size: 18px;
  line-height: 1;
}
.pl-fab-main.active {
  background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%) !important;
  border-color: #ffffff !important;
  box-shadow: 0 6px 24px rgba(211, 47, 47, 0.5) !important;
}

/* Ẩn widget desktop trên mobile */
@media (max-width: 768px) {
  #pl-desktop-widget,
  .pl-fab-container {
    display: none !important;
    visibility: hidden !important;
  }
}
</style>


<script>
(function(){
  function cleanVerif(){
    if(!document.body) return;
    var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
    var node, found = [];
    while(node = walker.nextNode()){
      if(node.nodeValue && node.nodeValue.indexOf('google034fdd2e10b56370') !== -1){
        found.push(node);
      }
    }
    found.forEach(function(n){
      n.nodeValue = '';
      if(n.parentElement && n.parentElement.tagName !== 'SCRIPT' && n.parentElement.tagName !== 'META'){
        n.parentElement.style.setProperty('display', 'none', 'important');
        try { n.parentElement.removeChild(n); } catch(e){}
      }
    });
  }
  cleanVerif();
  window.addEventListener('load', cleanVerif);
})();
</script>
</body>

</html>