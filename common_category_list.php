<?php




// lấy toàn bộ các nhóm sản phẩm cấp 1 được đánh dấu là _eb_category_in_list
// mảng gán dữ liệu để sau đỡ phải select lại cho nhanh
// cho vào config_cache mà nó không nhận -> đành phải cho vào common
$arr_replace_cat_in_ids_list = array();
function WGR_get_category_in_list ( $tax = 'category' ) {
	global $arr_replace_cat_in_ids_list;
	
//	echo $tax . '<br>' . "\n";
	
	$a = get_categories( array(
		'taxonomy' => $tax,
		'hide_empty' => 0,
//		'orderby' => 'slug',
		'parent' => 0
	) );
//	print_r( $a );
	
	//
	$arr = array();
	foreach ( $a as $v ) {
//		echo _eb_get_cat_object( $v->term_id, '_eb_category_in_list', 0 ) . '<br>' . "\n";
		
		// nếu có thuộc tính hiển thị trên danh mục
		if ( (int) _eb_get_cat_object( $v->term_id, '_eb_category_in_list', 0 ) == 1 ) {
//			$arr[ $v->slug ] = $v->name;
			$arr[] = '{tmp.' . $v->slug . '}|';
//			echo $v->slug . ' (' . $v->term_id . ')<br>' . "\n";
			
			//
			$arr_replace_cat_in_ids_list[] = $v;
			
			// -> lấy tất cả các nhóm con của nó luôn
			$a2 = get_categories( array(
				'taxonomy' => $tax,
				'hide_empty' => 0,
//				'orderby' => 'slug',
				'parent' => $v->term_id
			) );
//			print_r( $a2 );
			
			// nhóm con thì ko cần kiểm tra nữa -> cứ thế cho vào thôi
			foreach ( $a2 as $v2 ) {
//				$arr[ $v2->slug ] = $v2->name;
				// -> nhóm con không cần lấy, vì chỉ dùng theo key của nhóm cha
//				$arr[] = '{tmp.' . $v2->slug . '}|';
				
				//
				$arr_replace_cat_in_ids_list[] = $v2;
			}
		}
	}
	return $arr;
}

// xác định xem tài khoản này có hiển thị danh sách sản phẩm trên phần list không -> code khá nặng nên khuyến khích khách không sử dụng
//echo 'aaaaaaaaaaaaaaaaaaaaaa';
$__cf_row['cf_category_in_list'] = 0;
$arr_replace_cat_in_list = array_merge(
	WGR_get_category_in_list(),
	WGR_get_category_in_list('post_options'),
	WGR_get_category_in_list('blogs')
);
//print_r( $arr_replace_cat_in_list );
if ( ! empty( $arr_replace_cat_in_list ) ) {
//	echo implode( "\n", $arr_replace_cat_in_list );
	
	//
	$__cf_row['cf_replace_content'] = trim( $__cf_row['cf_replace_content'] ) . "\n" . implode( "\n", $arr_replace_cat_in_list );
	$__cf_row['cf_category_in_list'] = 1;
	//print_r($arr_replace_cat_in_ids_list);
}
//echo $__cf_row['cf_category_in_list'];


