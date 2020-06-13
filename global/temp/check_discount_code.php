<?php


function WGR_check_discount_code_error( $er ) {
	die( '{"error":"' . $er . '"}' );
}


//
//print_r( $_GET );
header('Content-Type: application/json');



//
if ( ! isset( $_GET['code'] ) ) {
	WGR_check_discount_code_error( EBE_get_lang('dc_is_null') );
}
$code = trim( $_GET['code'] );

//
if ( $code == '' || strlen( $code ) < 3 ) {
	WGR_check_discount_code_error( EBE_get_lang('dc_too_short') );
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
	WGR_check_discount_code_error( EBE_get_lang('dc_not_found') );
}


//
$ngay_hom_nay = date( 'Ymd', date_time );
$khong_tim_thay_ma = true;

//
foreach ( $arr_discount_code as $v ) {
	$check_discount_ex = _eb_get_cat_object( $v->term_id, '_eb_category_coupon_ngayhethan' );
	
	// nếu chưa tìm thấy mã giảm giá nào
	if (
		$check_discount_ex != ''
		// độ dài ngày hết hạn chuẩn
		&& strlen( $check_discount_ex ) == 10
		&& str_replace( '/', '', $check_discount_ex ) * 1 >= $ngay_hom_nay
	) {
		
//		echo $v->category_description;
//		echo json_encode($v);
		
		
		//
		$coupon_product = _eb_get_cat_object( $v->term_id, '_eb_category_coupon_product' );
		$coupon_product_name = '';
		$coupon_product_link = '';
		if ( $coupon_product > 0 ) {
			$coupon_sql = _eb_q("SELECT *
			FROM
				`" . wp_posts . "`
			WHERE
				ID = " . $coupon_product . "
				AND post_status = 'publish'
				AND post_type = 'post'");
//			print_r($coupon_sql);
			if ( count( $coupon_sql ) > 0 ) {
				$coupon_post = $coupon_sql[0];
//				print_r($coupon_post);
				$coupon_product_name = $coupon_post->post_title;
				$coupon_product_link = _eb_p_link($coupon_product);
			}
		}
		
		$coupon__product = _eb_get_cat_object( $v->term_id, '_eb_category_coupon__product' );
		$coupon__product_name = '';
		$coupon__product_link = '';
		if ( $coupon__product > 0 ) {
			$coupon_sql = _eb_q("SELECT *
			FROM
				`" . wp_posts . "`
			WHERE
				ID = " . $coupon__product . "
				AND post_status = 'publish'
				AND post_type = 'post'");
//			print_r($coupon_sql);
			if ( count( $coupon_sql ) > 0 ) {
				$coupon_post = $coupon_sql[0];
//				print_r($coupon_post);
				$coupon__product_name = $coupon_post->post_title;
				$coupon__product_link = _eb_p_link($coupon__product);
			}
		}
		
		$coupon_category = _eb_get_cat_object( $v->term_id, '_eb_category_coupon_category' );
		$coupon_category_name = '';
		$coupon_category_link = '';
		if ( $coupon_category > 0 ) {
			$a = WGR_get_all_term($coupon_category);
			$coupon_category_name = $a->name;
			$coupon_category_link = _eb_c_link($coupon_category);
		}
		
		$coupon__category = _eb_get_cat_object( $v->term_id, '_eb_category_coupon__category' );
		$coupon__category_name = '';
		$coupon__category_link = '';
		if ( $coupon__category > 0 ) {
			$a = WGR_get_all_term($coupon__category);
			$coupon__category_name = $a->name;
			$coupon__category_link = _eb_c_link($coupon__category);
		}
		
		
		//
		$arr = array(
			'cat_name' => $v->cat_name,
			'category_description' => EBE_get_lang('dc_ok') . ' ' . $v->category_description,
			'coupon_giagiam' => _eb_get_cat_object( $v->term_id, '_eb_category_coupon_giagiam' ),
			'coupon_phantramgiam' => _eb_get_cat_object( $v->term_id, '_eb_category_coupon_phantramgiam' ),
			'coupon_donggia' => _eb_get_cat_object( $v->term_id, '_eb_category_coupon_donggia' ),
			'coupon_ngayhethan' => $check_discount_ex,
			'coupon_toithieu' => _eb_get_cat_object( $v->term_id, '_eb_category_coupon_toithieu' ),
			'coupon_toida' => _eb_get_cat_object( $v->term_id, '_eb_category_coupon_toida' ),
			
			'coupon_product' => $coupon_product,
			'coupon_product_name' => $coupon_product_name,
			'coupon_product_link' => $coupon_product_link,
			
			'coupon__product' => $coupon__product,
			'coupon__product_name' => $coupon__product_name,
			'coupon__product_link' => $coupon__product_link,
			
			'coupon_category' => $coupon_category,
			'coupon_category_name' => $coupon_category_name,
			'coupon_category_link' => $coupon_category_link,
			
			'coupon__category' => $coupon__category,
			'coupon__category_name' => $coupon__category_name,
			'coupon__category_link' => $coupon__category_link,
			
			'coupon_min' => _eb_get_cat_object( $v->term_id, '_eb_category_coupon_min', 0 ),
			'coupon_max' => _eb_get_cat_object( $v->term_id, '_eb_category_coupon_max', 0 )
		);
		echo json_encode($arr);
		
		$khong_tim_thay_ma = false;
		
		break;
	}
	else {
		echo json_encode([
			'code' => -404,
			'msg' => 'Discount code not found or expired!'
		]);
	}
}



//
if ( $khong_tim_thay_ma == true ) {
	$er = EBE_get_lang('dc_expires');
}



exit();


