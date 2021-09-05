<!-- quick cart -->
<?php

// chỉ hiển thị quick cart ở rong trang sản phẩm và trang phải không được đặt là tin tức
if ( $pid > 0 && $__post->post_type == 'post' && $__cf_row[ 'cf_set_news_version' ] != 1 ) {
    include_once __DIR__ . '/quick_cart_inc.php';
}

?>
<!-- quick view -->
<div id="oi_ebe_quick_view" class="ebe-quick-view">
    <div class="quick-view-margin <?php echo $__cf_row['cf_post_class_style']; ?>">
        <div class="quick-view-close"><i onclick="close_ebe_quick_view();" class="fa fa-close cur"></i></div>
        <div class="quick-view-padding"></div>
    </div>
</div>
