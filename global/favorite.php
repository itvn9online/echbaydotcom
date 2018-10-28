<?php


//
$str_favorite = _eb_getCucki('wgr_product_id_user_favorite');
//echo $str_favorite;

if ( $str_favorite == '' ) {
	$favorite_list = '<!-- IDs for favorite not found! -->';
}
else {
	
	// chuyển đổi sang dấu khác để còn tạo mảng giá trị
	$str_favorite = str_replace('][', ',', $str_favorite);
	$str_favorite = str_replace(']', '', $str_favorite);
	$str_favorite = str_replace('[', '', $str_favorite);
//	echo $str_favorite;
	
	// -> tạo mảng
	$str_favorite = explode(',', $str_favorite);
//	print_r($str_favorite);
//	exit();
	
	$products_list = _eb_load_post( EBE_get_lang('limit_products_list'), array(
		'post__in' => $str_favorite
	) );
}




