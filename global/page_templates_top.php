<?php



// thiết lập tiêu đề theo mẫu chung
if ( $__cf_row['cf_set_link_for_h1'] == 1 ) {
	$trv_h1_tieude = '<a href="' . _eb_p_link( $post->ID ) . '" rel="nofollow">' . $post->post_title . '</a>';
}
else {
	$trv_h1_tieude = $post->post_title;
}




//
// thêm CSS theo slug để tiện sử dụng cho việc custom CSS
$_eb_product_css = _eb_get_post_object( $post->ID, '_eb_product_css' );
if ( $_eb_product_css != '' ) {
//	$class_css_of_post = ' ' . $_eb_product_css;
	$css_m_css[] = $_eb_product_css;
}
//echo $class_css_of_post;




ob_start();
