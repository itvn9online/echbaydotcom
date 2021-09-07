<?php
/*
 * Nạp các file tĩnh cần thiết cho hệ thống
 */
// head
function EB_flatsome_load_header_static() {
    /*
     * nạp base -> mình quen dùng kiểu này
     */
    echo '<base href="' . Wgr::$eb->BaseModelWgr->base_url . '" />';


    // nạp phần font awesome trước -> include trực tiếp xem có bị lỗi của google page speed không
    $load_font_awesome = '';
    $load_font_awesome .= file_get_contents( EB_THEME_URL . 'outsource/fontawesome-free-5.15.1-web/css/brands.min.css', 1 );
    $load_font_awesome .= file_get_contents( EB_THEME_URL . 'outsource/fontawesome-free-5.15.1-web/css/solid.min.css', 1 );
    $load_font_awesome = str_replace( '../webfonts/', EB_THEME_URL . 'outsource/fontawesome-free-5.15.1-web/webfonts/', $load_font_awesome );
    //echo EB_THEME_URL . 'outsource/' . '<br>' . "\n";
    //echo ABSPATH . '<br>' . "\n";
    $load_font_awesome = str_replace( ABSPATH, '', $load_font_awesome );
    echo '<style>' . $load_font_awesome . '</style>';


    /*
     * nạp các file css, js dùng chung từ core
     */
    Wgr::$eb->BaseModelWgr->adds_css( [
        /*
         * for theme
         */
        EB_THEME_URL . 'outsource/fontawesome-free-5.15.1-web/css/fontawesome.min.css',
        EB_THEME_URL . 'outsource/fontawesome-free-5.15.1-web/css/v4-shims.min.css',
        /*
         * for plugin
         */
        EB_THEME_PLUGIN_INDEX . 'css/d.css',
        EB_THEME_PLUGIN_INDEX . 'css/d2.css',
        EB_THEME_PLUGIN_INDEX . 'css/m_flatsome.css',
        //EB_THEME_PLUGIN_INDEX . 'css/g.css',
        EB_THEME_PLUGIN_INDEX . 'css/thread_list.css',
        //EB_THEME_PLUGIN_INDEX . 'css/default/home_hot.css',
        //EB_THEME_PLUGIN_INDEX . 'css/default/home_node.css',
    ] );


    /*
     * lấy các màu sắc được được thiết lập trong theme
     */
    $theme_mod = get_theme_mods();
    //print_r( $theme_mod );

    $arr_root_color = [];
    $arr_theme_mod = [];
    if ( !isset( $theme_mod[ 'color_primary' ] ) ) {
        $theme_mod[ 'color_primary' ] = '#446084';
    }
    //$arr_root_color[] = '--root-color: ' . $theme_mod[ 'color_primary' ];
    //$arr_root_color[] = '--main-color: ' . $theme_mod[ 'color_primary' ];
    //$arr_root_color[] = '--default-bg: ' . $theme_mod[ 'color_primary' ];

    if ( !isset( $theme_mod[ 'color_secondary' ] ) ) {
        $theme_mod[ 'color_secondary' ] = '#d26e4b';
    }
    $arr_root_color[] = '--sub-color: ' . $theme_mod[ 'color_secondary' ];
    //$arr_root_color[] = '--default2-bg: ' . $theme_mod[ 'color_secondary' ];

    if ( !isset( $theme_mod[ 'color_success' ] ) ) {
        $theme_mod[ 'color_success' ] = '#7a9c59';
    }
    $arr_root_color[] = '--success-color: ' . $theme_mod[ 'color_success' ];

    if ( !isset( $theme_mod[ 'color_alert' ] ) ) {
        $theme_mod[ 'color_alert' ] = '#b20000';
    }
    $arr_root_color[] = '--alert-color: ' . $theme_mod[ 'color_alert' ];

    if ( !isset( $theme_mod[ 'color_texts' ] ) ) {
        $theme_mod[ 'color_texts' ] = '#777';
    }
    $arr_root_color[] = '--text-color: ' . $theme_mod[ 'color_texts' ];
    $arr_root_color[] = '--texts-color: ' . $theme_mod[ 'color_texts' ];
    //$arr_root_color[] = '--default-color: ' . $theme_mod[ 'color_texts' ];

    if ( !isset( $theme_mod[ 'type_headings_color' ] ) ) {
        $theme_mod[ 'type_headings_color' ] = '#555';
    }
    $arr_root_color[] = '--h-color: ' . $theme_mod[ 'type_headings_color' ];

    if ( !isset( $theme_mod[ 'color_links' ] ) ) {
        $theme_mod[ 'color_links' ] = '#4e657b';
    }
    $arr_root_color[] = '--a-color: ' . $theme_mod[ 'color_links' ];

    if ( !isset( $theme_mod[ 'color_links_hover' ] ) ) {
        $theme_mod[ 'color_links_hover' ] = '#111';
    }
    $arr_root_color[] = '--a-hover-color: ' . $theme_mod[ 'color_links_hover' ];

    // các màu có thì mới in ra
    if ( isset( $theme_mod[ 'color_divider' ] ) ) {
        $arr_root_color[] = '--divider-color: ' . $theme_mod[ 'color_divider' ];
    }
    if ( isset( $theme_mod[ 'color_widget_links' ] ) ) {
        $arr_root_color[] = '--widget-color: ' . $theme_mod[ 'color_widget_links' ];
    }
    if ( isset( $theme_mod[ 'color_widget_links_hover' ] ) ) {
        $arr_root_color[] = '--widget-hover-color: ' . $theme_mod[ 'color_widget_links_hover' ];
    }
    if ( isset( $theme_mod[ 'site_width' ] ) ) {
        // độ rọng của flatsome nó luôn trừ đi 30px, sau đó column trong nó lại padding tổng 2 bên là 30px -> trừ đi 60px để cân bằng
        $arr_theme_mod[] = '/*.w99,*/.w90,.row{/* max-width:' . $theme_mod[ 'site_width' ] . 'px; */ max-width:' . ( $theme_mod[ 'site_width' ] - 60 ) . 'px !important;}';
    }
    echo '<style>:root {' . implode( ';', $arr_root_color ) . '}/* theme_mod */' . implode( '', $arr_theme_mod ) . '</style>';

    //
    global $eb_wp_post_type;
    //global $parent_cid;
    $parent_cid = 0;
    //global $cid;
    $cid = 0;
    //global $pid;
    $pid = 0;
    //global $eb_product_price;
    $eb_product_price = 0;
    global $__cf_row;
    global $arr_private_info_setting;
    global $act;
    global $cache_data_id;
    global $eb_background_for_post;

    include WGR_APP_PATH . 'inc/header.php';
}

// footer
function EB_flatsome_load_footer_static() {
    //
    //echo 'mtv_id: ' . mtv_id;
    //echo ( mtv_id > 0 && current_user_can( 'delete_posts' ) ) ? 1 : 2;

    /*
     * nạp các file css, js dùng chung từ core
     */
    Wgr::$eb->BaseModelWgr->adds_js( [
        /*
         * for plugin
         */
        EB_THEME_PLUGIN_INDEX . 'javascript/slider.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/functions.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/eb.js',
        ( mtv_id > 0 && current_user_can( 'delete_posts' ) ) ? EB_THEME_PLUGIN_INDEX . 'javascript/show-edit-btn.js' : '',
        EB_THEME_PLUGIN_INDEX . 'javascript/df.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/dp.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/d.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/footer.js',
        EB_THEME_PLUGIN_INDEX . 'javascript/fomo_order.js',
        /*
         * for child theme
         */
        EB_CHILD_THEME_URL . 'ui/d.js',
    ] );
}

//
// chỉ nạp nếu theme không phải dạng basic
if ( !defined( 'FLATSOME_BASIC_THEME' ) ) {
    add_action( 'wp_head', 'EB_flatsome_load_header_static', 9 );
    add_action( 'wp_footer', 'EB_flatsome_load_footer_static', 0 );
}

// xóa thẻ <title> để sử dụng title tùy chỉnh
remove_action( 'wp_head', '_wp_render_title_tag', 1 );