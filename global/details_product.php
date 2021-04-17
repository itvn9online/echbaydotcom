<?php


echo '<!-- ';

print_r( $post );

print_r( $arr_object_post_meta );

echo ' -->';


// set trạng thái trang là sản phẩm
//$web_og_type = 'product';
$web_og_type = EBE_get_lang('schema_product_type');




// Tạo menu cho post option
/*
$arr_post_options = wp_get_object_terms( $pid, 'product_attributes' );
if ( mtv_id == 1 ) print_r($arr_post_options);

// sắp xếp theo STT
//$sort_post_options = WGR_order_and_hidden_taxonomy( $arr_post_options );
$sort_post_options = array();
$new_post_options = array();
foreach ( $arr_post_options as $v ) {
//	echo $v->term_id . '<br>' . "\n";
	
	// chỉ lấy các nhóm được xác minh là hiển thị
	if ( _eb_get_cat_object( $v->term_id, '_eb_category_hidden', 0 ) != 1 ) {
		// đoạn này sẽ order theo nhóm cha của taxonomy
		$sort_post_options[ $v->term_id ] = (int) _eb_get_cat_object( $v->parent, '_eb_category_order', 0 );
	}
	$new_post_options[ $v->term_id ] = $v;
}
arsort( $sort_post_options );
//if ( mtv_id == 1 ) print_r( $sort_post_options );
//print_r( $new_post_options );

//
//foreach ( $arr_post_options as $v ) {
foreach ( $sort_post_options as $k=> $v ) {
	$v = $new_post_options[ $k ];
	
	//
	if ( $v->parent > 0 ) {
//		$parent_name = get_term_by( 'id', $v->parent, $v->taxonomy );
		$parent_name = WGR_get_taxonomy_parent( $v );
//		if ( mtv_id == 1 ) print_r( $parent_name );
		
		//
		$other_option_list .= '
<tr>
	<td><div>' . $parent_name->name . '</div></td>
	<td><div><a href="' . _eb_c_link( $v->term_id, $v->taxonomy ) . '" target="_blank">' . $v->name . '</a></div></td>
</tr>';
	}
}
*/




// tag of blog
$arr_list_tag = wp_get_object_terms( $pid, 'product_tag' );




