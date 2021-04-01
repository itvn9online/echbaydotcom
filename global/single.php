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
        // sử dụng child theme
        if ( using_child_wgr_theme == 1 && file_exists( EB_CHILD_THEME_URL . 'php/404.php' ) ) {
            include EB_CHILD_THEME_URL . 'php/404.php';
        }
        // sử dụng theme mặc định
        else {
            include EB_THEME_PHP . '404.php';
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