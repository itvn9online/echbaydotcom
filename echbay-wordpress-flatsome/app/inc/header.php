<?php

//
//print_r( $__cf_row );

global $global_dymanic_meta;

//global $arr_dymanic_meta;
//echo 'arr_dymanic_meta: ' . $arr_dymanic_meta . '<br>' . "\n";
global $dynamic_meta;

global $url_og_url;
if ( empty( $url_og_url ) ) {
    $url_og_url = get_permalink();
}
//echo 'url_og_url: ' . $url_og_url . '<br>' . "\n";

global $image_og_image;
if ( empty( $image_og_image ) ) {
    $image_og_image = $__cf_row[ 'cf_og_image' ];
}
//echo 'image_og_image: ' . $image_og_image . '<br>' . "\n";


// các thể meta khác nếu có
$arr_dymanic_meta = array();
$arr_dymanic_meta[] = '<meta itemprop="url" content="' . $url_og_url . '" />';
$arr_dymanic_meta[] = '<meta property="og:url" content="' . $url_og_url . '" />';

$arr_dymanic_meta[] = '<meta itemprop="image" content="' . $image_og_image . '" />';
$arr_dymanic_meta[] = '<meta property="og:image" content="' . $image_og_image . '" />';


$dynamic_meta .= implode( "\n", $arr_dymanic_meta );
//echo 'dynamic_meta: ' . $dynamic_meta . '<br>' . "\n";
//echo __FILE__ . ':' . __LINE__ . '<br>' . "\n";


global $web_name;
global $web_og_type;
global $import_ecommerce_ga;

global $schema_BreadcrumbList;
//echo 'schema_BreadcrumbList';
//print_r( $schema_BreadcrumbList );

include EB_THEME_PLUGIN_INDEX . 'BreadcrumbList.php';

//
if ( cf_on_off_echbay_seo == 1 ) {
    // trang chủ -> dùng theo config
    if ( is_home() ||
        is_front_page() ) {
        //echo 'is_front_page';
    }
    // với phần page template -> lấy thêm thông tin do echbay SEO không được xử lý tại mục này
    else if ( is_page_template() ) {
        //echo 'is_page_template';

        //
        if ( $__cf_row[ 'cf_abstract' ] == '' ) {
            $__cf_row[ 'cf_abstract' ] = $__cf_row[ 'cf_title' ];
        }
        $__cf_row[ 'cf_title' ] = wp_title( '|', false, 'right' ) . $__cf_row[ 'cf_abstract' ];
    }
    // các phần còn lại đã được xử lý SEO -> bỏ
    /*
    else if ( is_page() ) {
        //echo 'is_page';
    }
    */

    //
    echo _eb_tieu_de_chuan_seo( $__cf_row[ 'cf_title' ] );
}
echo WGR_show_header_favicon();

include EB_THEME_PLUGIN_INDEX . 'seo.php';

// tạo thêm pid cho page template -> có thể việc mua hàng cũng sẽ diễn ra ở đây
if ( $pid === 0 && is_page_template() ) {
    $pid = get_the_ID();
}


?>
<!-- <meta name="theme-color" content="<?php echo $__cf_row['cf_default_bg']; ?>" /> -->
<style type="text/css">
/* EchBay custom CSS for replace default CSS by plugin or theme */
<?php /* do phần css chứa các url ảnh nên cần thay thế lại luôn nếu có */
if ( $__cf_row['cf_replace_content'] != '' ) {
$__cf_row['cf_default_css'] = WGR_replace_for_all_content( $__cf_row['cf_replace_content'], $__cf_row['cf_default_css'] );
}
echo $__cf_row['cf_default_css'] . $__cf_row['cf_default_themes_css'];
?>
</style>
<script type="text/javascript">
<?php


include EB_THEME_PLUGIN_INDEX . 'data_id.php';


?>
var web_link = '<?php echo str_replace( '/', '\/', web_link ); ?>';
</script> 
<!-- HEAD by <?php echo $arr_private_info_setting['author']; ?> --> 
<?php echo $__cf_row['cf_js_head']; ?> 
<!-- // Global site format by <?php echo $arr_private_info_setting['author']; ?> -->
<?php


//
EBE_print_product_img_css_class( $eb_background_for_post );

// reset lại mục này, để còn insert CSS xuống footer nếu có
$eb_background_for_post = array();
