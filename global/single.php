<?php

//
//echo 'aaaaaaaaaa';

// có nội dung
if ( have_posts() ) {
    //	echo '<!-- TEST -->' . "\n";
    //	print_r( $post );

    // các post type được hỗ trợ
    if ( $post->post_type == 'post' ||
        // blog của WGR
        $post->post_type == EB_BLOG_POST_TYPE ||
        // product của woocommerce
        $post->post_type == 'product' ) {
        // sử dụng child theme
        if ( using_child_wgr_theme == 1 && file_exists( EB_CHILD_THEME_URL . 'php/content.php' ) ) {
            //echo '<!-- ' . EB_CHILD_THEME_URL . ' -->' . "\n";
            include EB_CHILD_THEME_URL . 'php/content.php';
        }
        // sử dụng theme mặc định
        else {
            include EB_THEME_PHP . 'content.php';
        }
    }
    // định dạng ads của WGR sẽ bị ẩn
    else if ( $post->post_type == 'ads' ) {
        //print_r( $post );
        //echo $post->ID . '<br>' . "\n";

        $ads_url_redirect = get_post_meta( $post->ID, '_eb_ads_url' );
        //print_r( $ads_url_redirect );
        if ( !empty( $ads_url_redirect ) ) {
            $ads_url_redirect = $ads_url_redirect[ 0 ];
        }
        if ( $ads_url_redirect == '' ) {
            $ads_url_redirect = _eb_get_post_object( $post->ID, '_eb_ads_url' );
        }
        //echo $ads_url_redirect . '<br>' . "\n";
        //echo web_link . '<br>' . "\n";
        //die( 'gh ssf' );

        // trỏ thẳng tới URL mà người dùng hướng tới
        if ( $ads_url_redirect != '' ) {
            wp_redirect( $ads_url_redirect );
        } else {
            //wp_redirect( web_link, 301 );

            // sử dụng child theme
            if ( using_child_wgr_theme == 1 && file_exists( EB_CHILD_THEME_URL . 'php/404.php' ) ) {
                include EB_CHILD_THEME_URL . 'php/404.php';
            }
            // sử dụng theme mặc định
            else {
                include EB_THEME_PHP . '404.php';
            }
        }
    }
    // các định dạng của bên khác sẽ vẫn hỗ trợ
    else {
        // hiển thị mặc định nội dung bài viết dưới dạng blog
        echo '<!-- post_type second support: ' . $post->post_type . ' -->' . "\n";
        //$check_post_type_supports = post_type_supports( $post->post_type, 'post-formats' );
        //print_r( $check_post_type_supports );
        include EB_THEME_PHP . 'content.php';
    }
}
// không có nội dung
else {
    include EB_THEME_PLUGIN_INDEX . 'global/content-none.php';
}