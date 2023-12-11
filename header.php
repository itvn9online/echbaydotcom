<!DOCTYPE html>
<html lang="<?php echo $__cf_row['cf_content_language']; ?>" class="no-js no-svg" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">

<head>
    <!-- header -->
    <meta charset="<?php bloginfo('charset'); ?>" />
    <link rel="profile" href="http://gmgp.org/xfn/11" />
    <?php

    if (is_singular() && pings_open(get_queried_object())) {
    ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php
    }

    // _eb_tieu_de_chuan_seo($__cf_row['cf_title']);
    echo WGR_show_header_favicon();


    /*
 * Do bản kết hợp với flatsome có sẵn 1 số thuộc tính nên bỏ qua các thuộc tính trùng trong SEO
 */
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include EB_THEME_PLUGIN_INDEX . 'seo.php';

    ?>
    <!-- Global site format by <?php echo $arr_private_info_setting['author']; ?> -->
    <base href="<?php echo web_link; ?>" />
    <?php


    // nạp phần font awesome trước -> include trực tiếp xem có bị lỗi của google page speed không
    $load_font_awesome = '';
    if (is_file(EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/brands.min.css')) {
        $load_font_awesome .= file_get_contents(EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/brands.min.css', 1);
        $load_font_awesome .= file_get_contents(EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/solid.min.css', 1);
        $load_font_awesome = str_replace('../webfonts/', EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/webfonts/', $load_font_awesome);
        //echo EB_THEME_OUTSOURCE . '' . '<br>' . "\n";
        //echo ABSPATH . '<br>' . "\n";
        $load_font_awesome = str_replace(ABSPATH, '', $load_font_awesome);
        echo '<style>' . $load_font_awesome . '</style>';
    }
    // nếu không có file css mà có file zip -> gọi hàm giải nén
    else if (is_file(EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web.zip')) {
        //echo __FILE__ . ':' . __LINE__ . '<br>' . "\n";
        WGR_unzip1_vendor_code(false);
    }


    // thêm NAV menu cho bản mobile
    if ($__cf_row['cf_search_nav_mobile'] == 'none') {
        $__cf_row['cf_default_css'] .= 'body.style-for-mobile{margin-top:0}';
    }
    // chuyển sang nạp thông qua ajax xem có nhanh web hơn không
    else {
        include EB_THEME_PLUGIN_INDEX . 'mobile/nav.php';
    }


    // một số css liên quan tới việc hiển thị màn hình đầu tiên, giúp tăng điểm trên google page speed
    //print_r( $arr_for_add_css );
    //_eb_add_css( $arr_for_add_css );
    //_eb_add_compiler_css( $arr_for_add_css + $arr_for_add_theme_css );
    _eb_add_compiler_css($arr_for_add_css);
    _eb_add_compiler_css([
        EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/fontawesome.min.css' => 0,
        EB_THEME_OUTSOURCE . 'fontawesome-free-5.15.4-web/css/v4-shims.min.css' => 0,
    ]);
    //print_r( $arr_for_add_css );


    // các css ít quan trọng hơn sẽ được add về phía sau, dưới dạng link
    //_eb_add_compiler_link_css( $arr_for_add_link_css, 'link' );
    //_eb_add_compiler_css( $arr_for_add_theme_css );


    // phần style này phải đặt ở cuối cùng, để nó replace tất cả các style tĩnh trước đó
    ?>
    <style type="text/css">
        /* EchBay custom CSS for replace default CSS by plugin or theme */
        <?php

        /* do phần css chứa các url ảnh nên cần thay thế lại luôn nếu có */
        if ($__cf_row['cf_replace_content'] != '') {
            $__cf_row['cf_default_css'] = WGR_replace_for_all_content($__cf_row['cf_replace_content'], $__cf_row['cf_default_css']);
        }

        echo $__cf_row['cf_default_css'] . $__cf_row['cf_default_themes_css'];
        ?>
    </style>
    <?php


    include EB_THEME_PLUGIN_INDEX . 'data_id.php';



    // do xung đột với elementor nên chỉ nạp jquery riêng khi không đăng nhập
    //if ( mtv_id == 0 ) {
    // một số plugin nó load js qua hàm jquery luôn, nên cái này không bỏ được
    /*
if ( 1 == 2 ) {
?>
    <script type="text/javascript" src="<?php echo EB_URL_OUTSOURCE; ?>javascript/jquery/3.3.1.min.js"></script>
    <script type="text/javascript" src="<?php echo EB_URL_OUTSOURCE; ?>javascript/jquery/migrate-3.0.0.min.js" defer>
    </script>
    <?php
}
*/

    ?>
    <!-- HEAD by <?php echo $arr_private_info_setting['author']; ?> -->
    <?php echo $__cf_row['cf_js_head']; ?>
    <!-- // Global site format by <?php echo $arr_private_info_setting['author']; ?> -->
    <?php

    //echo 'aaaaaaaaaaaaaaaaaaaa';
    wp_head();
    //echo 'bbbbbbbbbbbbbbbbbbbb';


    //
    EBE_print_product_img_css_class($eb_background_for_post);

    // reset lại mục này, để còn insert CSS xuống footer nếu có
    $eb_background_for_post = array();

    //
    //print_r( $css_m_css );

    ?>
</head>
<!-- Thêm class tượng trưng cho mỗi trang lên BODY để tùy biến-->

<body class="<?php echo trim(implode(' ', $css_m_css) . $class_css_of_post); ?>">