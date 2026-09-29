<div class="panel">
    <h1 class="panel_header">Tìm sản phẩm</h1>
    
    <div class="panel_body">
        
        <div class="list_product">
        <?php
            if (isset($keyword) && !empty($keyword)) {
                if ($totalRows > 0) {
        ?>
                    <div class="success">
                        Có <b><?php echo $totalRows?></b> sản phẩm được tìm thấy.
                    </div>
        <?php
                } else {
        ?>
                    <div class="notice">
                        Không tìm thấy sản phẩm nào phù hợp với yêu cầu tìm kiếm.
                    </div>    
        <?php
                }
            }
            
            $totalProduct = count($listProduct);
            foreach ($listProduct as $k => $pro) {
                $pro['buy_url'] = getUrlUri($contactPage['page_permalink'],SITE_EXT,$pro['product_id'],$pro['product_name']);
        ?>
                <div class="product_item">
                    <div class="product_image">
                        <a href="<?php echo $pro['product_url']?>" title="<?php echo $pro['product_name']?>">
                            <img src="<?php echo $pro['image_thumb']?>" alt="<?php echo $pro['product_name']?>"/>
                        </a>
                    </div>
                    
                    <h2 class="product_name">
                        <a href="<?php echo $pro['product_url']?>" title="<?php echo $pro['product_name']?>">
                            <?php echo $pro['product_name']?>
                        </a>
                    </h2>
                    
                    <?php
                        if (isset($pro['price_promotion_formatted'])) {
                    ?>
                            <p class="product_price"><?php echo $pro['price_promotion_formatted']?></p>
                            <p class="product_old_price"><?php echo $pro['price_formatted']?></p>
                    <?php
                        } else {
                    ?>
                            <p class="product_price"><?php echo $pro['price_formatted']?></p>
                    <?php
                        }
                    ?>
                    
                    <a href="<?php echo $pro['buy_url']?>" title="Đặt mua <?php echo $pro['product_name']?>">
                        <button class="cms_button">Đặt mua</button>
                    </a>
                </div>
        <?php
                if (!(($k+1)%3) || $k == $totalProduct - 1) echo '<div class="clear"></div>';
            }
        ?>
        </div><!-- list_product -->
        
        <?php $pagi->show()?>
    </div><!-- panel_body -->
</div><!-- panel -->