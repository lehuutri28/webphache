<div class="panel">
    <h3 class="panel_header">Danh mục sản phẩm</h3>
    
    <div class="panel_body">
        <ul id="menu_category">
        <?php
            foreach ($listCateLv1 as $c1) {
                $c1['category_url'] = getUrlUri($categoryPage['page_permalink'],SITE_EXT,$c1['category_id'],$c1['category_name']);
                
                $hasChild = 0;
                if (isset($c1['list_child']) && !empty($c1['list_child'])) $hasChild = 1;
        ?>
                <li<?php echo $hasChild?' class="has_child"':''?>>                        
                    <a href="<?php echo $c1['category_url']?>" title="<?php echo $c1['category_name']?>">
                        <?php echo $c1['category_name']?>
                    </a>
                    <?php
                        if ($hasChild) {
                    ?>
                            <ul>
                            <?php
                                foreach ($c1['list_child'] as $c2) {
                                    $c2['category_url'] = getUrlUri($categoryPage['page_permalink'],SITE_EXT,$c2['category_id'],$c2['category_name']);
                            ?>
                                    <li>                        
                                        <a href="<?php echo $c2['category_url']?>" title="<?php echo $c2['category_name']?>">
                                            <?php echo $c2['category_name']?>
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
            }
        ?>
        </ul><!-- menu_category -->
    </div><!-- panel_body -->
</div><!-- panel -->