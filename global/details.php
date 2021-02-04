<?php


/*
 * Mọi code dùng chung cho trang chi tiết sản phẩm/ bài viết
 */


//
//print_r($post);
$__post = $post;


// nếu đây là một page, và page này có URL thuốc nhóm ưu tiên -> không hiển thị page theo kiểu thông thường
if ( $__post->post_type == 'page' && isset( $arr_active_for_404_page[ $__post->post_name ] ) ) {
    echo '<!-- Custom page by ' . $arr_private_info_setting[ 'author' ] . ': ' . $__post->post_name . ' -->' . "\n";

    $act = $__post->post_name;

    // không index các trang module riêng của EB
    $__cf_row[ "cf_blog_public" ] = 0;

    include EB_THEME_PLUGIN_INDEX . 'global/' . $__post->post_name . '.php';
}
// mặc định thì hiển thị trang chi tiết luôn
else {
    include EB_THEME_PLUGIN_INDEX . 'global/details_default.php';
}


// TEST
/*
$post_categories = wp_get_post_categories( $pid );
print_r( $post_categories );
foreach ( $post_categories as $c ) {
    $cat = get_term( $c );
    print_r( $cat );

    //$str .= '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
}
*/

//
/*
WGR_get_taxonomy_xml_list( $pid );
WGR_get_taxonomy_xml_list( $pid, 'post_tag' );
WGR_get_taxonomy_xml_list( $pid, 'post_options' );
WGR_get_taxonomy_xml_list( $pid, 'blogs' );
WGR_get_taxonomy_xml_list( $pid, 'blog_tag' );
*/
