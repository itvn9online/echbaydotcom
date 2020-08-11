<?php



/*
* Link tham khảo:
* https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce
* Gửi dữ liệu tới google sheet
* https://tuandc.com/lap-trinh/huong-dan-tao-from-gui-du-lieu-tu-website-len-google-sheet-khong-dung-google-forms.html
*/



$__cf_row ['cf_title'] = 'Đặt hàng thành công';
//$group_go_to[] = ' <li><a href="./hoan-tat" rel="nofollow">' . $__cf_row ['cf_title'] . '</a></li>';
$group_go_to[] = ' <li>' . $__cf_row ['cf_title'] . '</li>';





//
$__cf_row ['cf_title'] .= ', trân trọng cảm ơn bạn đã mua hàng tại ' . web_name;
$__cf_row ['cf_keywords'] = $__cf_row ['cf_title'];
$__cf_row ['cf_description'] = $__cf_row ['cf_title'];



//
$hd_id = _eb_getCucki( 'eb_cookie_order_id', 0 );
//echo $hd_id;

//$hd_mahoadon = _eb_getCucki( 'eb_cookie_order_sku' );
$hd_mahoadon = '';
//echo $hd_mahoadon;

//
$current_hd_object = '[]';
$current_tv_object = '[]';

$ga_ecom_update = 0;


// facebook purchase track
//$fb_purchase_order = '';



//
if ( ! isset( $_COOKIE['wgr_check_tracking_social' . $hd_id] ) ) {
	$import_ecommerce_ga = "
	if ( typeof ga != 'undefined' ) {
		ga('require', 'ec');
	}";
}





if ( $hd_id > 0 ) {
	$tongtien = 0;
	
	
	
	//
	$sql = _eb_load_order( 1, array(
		'p' => $hd_id,
	) );
//	print_r( $sql );
	
	//
	/*
	if ( !isset($sql->post) || !isset($sql->post->ID) ) {
	} else {
		*/
	if ( isset($sql[0]) || isset($sql[0]->ID) ) {
//		$sql = $sql->post;
		$sql = $sql[0];
//		print_r( $sql );
		
		
		//
		$hd_mahoadon = $sql->order_sku;
		$current_hd_object = $sql->order_products;
		$current_tv_object = $sql->order_customer;
		// tạm thời để lệnh kiểm tra đã, sau 1 tháng thì bỏ đi
//		if ( isset( $sql->order_total_price ) ) {
			$order_total_price = $sql->order_total_price;
			/*
		}
		else {
			EBE_tao_bang_hoa_don_cho_echbay_wp();
			$order_total_price = 0;
		}
		*/
		
		
		//
//		$fb_purchase_order = '
		$__cf_row ['cf_js_allpage'] .= '
		<script type="text/javascript">
function WGR_hoan_tat_load_tracking ( i ) {
	if ( typeof i != "number" ) {
		i = 50;
	}
	else if ( i < 0 ) {
		console.log("Max load WGR javascript!");
		return false;
	}
	
	if ( typeof WGR_hoan_tat_send_tracking != "function" ) {
		setTimeout(function () {
			WGR_hoan_tat_load_tracking( i - 1 );
		}, 200);
		return false;
	}
	
	//
	WGR_hoan_tat_send_tracking( ' . $hd_id . ', current_hd_object, current_tv_object );
	WGR_get_hoan_tat_total_price();
}
WGR_hoan_tat_load_tracking();
		</script>';
	}
	/*
}
else {
//	$sql = new stdClass();
	$sql = array();
	$sql = (object) $sql;
	$sql->order_time = 0;
	$sql->order_status = 0;
}
*/



// tính năng review sản phẩm sau khi mua hàng của google
/*
$__cf_row ['cf_js_hoan_tat'] .= '<!-- BEGIN GCR Opt-in Module Code -->
<script src="https://apis.google.com/js/platform.js?onload=renderOptIn"
  async defer>
</script>

<script>
  window.renderOptIn = function() { 
    window.gapi.load(\'surveyoptin\', function() {
      window.gapi.surveyoptin.render(
        {
          "merchant_id": 0,
          "order_id": "' . $hd_mahoadon . '",
          "email": WGR_get_hoan_tat_user_email(),
          "delivery_country": "VN",
          "estimated_delivery_date": "' . date( 'Y-m-d', date_time * ( 24 * 366 * 7 ) ) . '",
          "opt_in_style": "BOTTOM_LEFT_DIALOG"
        }); 
     });
  }
</script>
<!-- END GCR Opt-in Module Code -->';
*/





//
$__cf_row ['cf_js_allpage'] = $__cf_row ['cf_js_hoan_tat'] . "\r\n"
							. $__cf_row ['cf_js_allpage'];
//							. $__cf_row ['cf_js_allpage'] . "\r\n"
//							. $fb_purchase_order;



//
/*
$main_content = EBE_str_template ( 'hoan-tat.html', array (
	'tmp.hd_id' => $hd_id,
	'tmp.hd_mahoadon' => $hd_mahoadon,
	'tmp.cf_hotline' => $__cf_row['cf_hotline'],
), EB_THEME_PLUGIN_INDEX . 'html/' );
*/




//
//$main_content = EBE_html_template( EBE_get_page_template( $act ), array(
//$main_content = EBE_html_template( EBE_get_lang('booking_done'), array(
$main_content = EBE_html_template( WGR_get_html_template_lang( 'booking_done', 'hoan-tat' ), array(
//	'tmp.js' => '',
	
	'tmp.cf_bank' => nl2br( $__cf_row['cf_bank'] ),
	'tmp.hoantat_time' => EBE_get_lang( 'hoantat_time' ),
	'tmp.hoantat_banking' => EBE_get_lang( 'hoantat_banking' ),
	'tmp.cf_hotline' => $__cf_row['cf_hotline'],
//	'tmp.echbay_plugin_url' => EB_URL_OF_PLUGIN,
//	'tmp.echbay_plugin_version' => date_time,
	
	'tmp.hd_id' => $hd_id,
	'tmp.hd_mahoadon' => $hd_mahoadon
) );




// xác định mã giảm giá nếu có
$decode_order_discount = WGR_decode_for_discount_cart( $current_tv_object );
//print_r( $decode_order_discount );

$discount_code = '';
$discount_price = 0;
if ( $decode_order_discount != NULL ) {
	$decode_order_discount = $decode_order_discount[0];
	
	//
	$discount_code = $decode_order_discount->name;
//	$discount_price = 0;
	if ( isset( $decode_order_discount->coupon_giagiam ) ) {
		$discount_price = _eb_float_only( $decode_order_discount->coupon_giagiam );
	}
	
	if ( $discount_price > 0 ) {
	}
	else if ( $decode_order_discount->coupon_phantramgiam > 0 ) {
		$discount_price = $decode_order_discount->coupon_phantramgiam . '%';
	}
}




// thêm mã JS vào luôn trong phần PHP, để HTML làm bản dịch
$main_content .= '<script type="text/javascript">
var current_hd_id = "' . $hd_id . '",
	current_hd_code = "' . $hd_mahoadon . '",
	order_total_price = "' . $order_total_price . '" * 1,
	current_hd_date = "' . date( _eb_get_option('date_format') . ' H:i', $sql->order_time ) . '",
	current_hd_status = ' . $sql->order_status . ',
	arr_hd_trangthai = ' .json_encode( $arr_hd_trangthai ) . ',
	current_tv_object = "' . $current_tv_object . '",
	current_hd_object = "' . $current_hd_object . '",
	discount_price = "' . $discount_price . '",
	cf_google_sheet_backup = "' . $__cf_row['cf_google_sheet_backup'] . '",
	arr_lang_hoan_tat = {
		"cart_done_madon" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_madon') ) . '",
		"cart_done_khachhang" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_khachhang') ) . '",
		"cart_done_dienthoai" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_dienthoai') ) . '",
		"cart_done_diachi" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_diachi') ) . '",
		"cart_done_ghichu" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_ghichu') ) . '",
		"cart_done_trangthai" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_trangthai') ) . '",
		"cart_done_color" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_mausac') ) . '",
		"cart_done_size" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_size') ) . '",
		"cart_done_quan" : "' . _eb_str_block_fix_content( EBE_get_lang('post_soluong') ) . '",
		"cart_done_list" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_list') ) . '",
		"cart_done_tong" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_done_tong') ) . '",
		
		"customer_info" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_customer_info') ) . '",
		"payment_method" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_payment_method') ) . '",
		"cod" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_payment_cod') ) . '",
		"tructiep" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_payment_tt') ) . '",
		"bank" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_payment_bank') ) . '",
		"bk" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_payment_bk') ) . '",
		"nl" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_payment_nl') ) . '",
		"pp" : "' . _eb_str_block_fix_content( EBE_get_lang('cart_payment_pp') ) . '"
	};
</script>';


}
else {
	$main_content = '<h4 class="text-center" style="padding:90px 0;">Oh! your order not found!</h4>';
}

