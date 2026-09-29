<?php
require_once dirname(__FILE__) . '/seo_map.php';
$seo_html_meta = get_seo_metadata_for_current_request(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/', 0);
$html_h1 = (!empty($seo_html_meta) && !empty($seo_html_meta['h1'])) ? $seo_html_meta['h1'] : $template['page']['page_name'];

$html_content = quotesDecode($template['page']['page_content']);
// Demote any secondary H1 inside content to H2 to guarantee exact 1 H1 per page
$html_content = preg_replace('/<h1\b([^>]*)>(.*?)<\/h1>/is', '<h2 class="sub-h2"$1>$2</h2>', $html_content);
?>
<div id="html_page" class="container">
    <h1 class="h1-title"><?php echo htmlspecialchars($html_h1, ENT_QUOTES, 'UTF-8'); ?></h1>
    
    <div id="html_page_content" class="panel_content"><?php echo $html_content; ?></div>
    <?php include dirname(__FILE__) . '/widget_drink_calculator.php'; ?>
</div>