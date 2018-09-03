<?php



//
//print_r( $_GET );



if ( ! isset( $_GET['code'] ) ) {
	die( EBE_get_lang('dc_is_null') );
}
$code = trim( $_GET['code'] );

//
if ( $code == '' || strlen( $code ) < 3 ) {
	die( EBE_get_lang('dc_too_short') );
}



//
$arr_discount_code = get_categories( array(
    'name' => $code,
    'orderby' => 'id',
	'hide_empty' => 0,
	'taxonomy' => 'discount_code'
) );
//print_r( $arr_discount_code );


//
if ( empty( $arr_discount_code ) ) {
	die( EBE_get_lang('dc_not_found') );
}


//
$ngay_hom_nay = date( 'Ymd', date_time );

//
foreach ( $arr_discount_code as $v ) {
	$check_discount_ex = _eb_get_cat_object( $v->term_id, '_eb_category_coupon_ngayhethan' );
	
	// nếu chưa tìm thấy mã giảm giá nào
	if ( $check_discount_ex != ''
	// độ dài ngày hết hạn chuẩn
	&& strlen( $check_discount_ex ) == 10
	&& str_replace( '/', '', $check_discount_ex ) >= $ngay_hom_nay ) {
		echo $v->category_description;
		break;
	}
}



