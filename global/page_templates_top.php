<?php


// định nghĩa chức năng dò tìm thuộc tính từ post option cho phần node sản phẩm
define( 'eb_find_category_in_list', true );



// khai báo biến này để khẳng định đây là page templates -> lưu cache được
$is_page_templates = true;



// thiết lập tiêu đề theo mẫu chung
if ( $__cf_row['cf_set_link_for_h1'] == 1 ) {
	$h1_rel_nofollow = ' rel="nofollow"';
	if ( $__cf_row['cf_set_nofollow_for_h1'] != 1 ) {
		$h1_rel_nofollow = '';
	}
	$trv_h1_tieude = '<a href="' . _eb_p_link( $post->ID ) . '"' . $h1_rel_nofollow . '>' . $post->post_title . '</a>';
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
//echo 'aaaaaaaaaa';



//
ob_start();



//
$private_post_link_stylesheet = _eb_get_post_object( $post->ID, '_eb_product_link_stylesheet' );
if ( $private_post_link_stylesheet != '' ) {
	echo '<link rel="stylesheet" href="' . $private_post_link_stylesheet . '" type="text/css" />';
}




//
if ( ! isset( $admin_edit ) ) {
	$admin_edit = '';
	if ( current_user_can('delete_posts') ) {
		$admin_edit = '<a title="Edit" href="' . admin_link . 'post.php?post=' . $post->ID . '&action=edit" class="fa fa-edit breadcrumb-clone-edit-post"></a>';
	}
}
echo '<div class="thread-details-relative">' . $admin_edit . '</div>';


