<?php


//print_r( $_POST );
//print_r( $_GET );
//die( __FILE__ . ':' . __LINE__ );
$_POST = EBE_stripPostServerClient();
//print_r( $_POST );

// kiểm tra dữ liệu đầu vào xem có chuẩn không, nếu có thì chỉ trong vòng 30 phút trở lại
//WGR_check_ebnonce('__wgr_request_from');
/*
if ( ! isset($_POST['__wgr_nonce']) || $_POST['__wgr_nonce'] * 1 +  1800 < date_time ) {
	_eb_alert( 'Data submit ERROR!' );
}
*/


//
function ket_thuc_qua_trinh_dat_hang($hd_id, $hd_mahoadon, $m)
{
?>
	<!-- INFO BOOKING -->
	<script type="text/javascript">
		var my_hd_id = '<?php echo $hd_id; ?>',
			my_hd_mahoadon = '<?php echo $hd_mahoadon; ?>',
			my_message = '<?php echo $m; ?>';

		//
		parent._global_js_eb.cpl_cart(my_hd_id, my_hd_mahoadon, my_message);
	</script>
<?php


	//
	exit();
}


//
if (!isset($_POST['t_muangay'])) {
	_eb_alert('Đầu vào không hợp lệ');
}


//
// $strFilter = "";
$arr_ids = [];
$arr = $_POST['t_muangay'];
$arr_shop_cart = array();
$arr_shop_cart_size = array();
$arr_shop_cart_price = array();
foreach ($arr as $k => $v) {
	// nếu có số lượng
	if (isset($_POST['t_soluong'][$v]) > 0) {
		// $strFilter .= "," . $v;
		$arr_ids[] = $v;

		$arr_shop_cart[$v] = (int)$_POST['t_soluong'][$v];
		$arr_shop_cart_size[$v] = isset($_POST['t_size'][$v]) ? $_POST['t_size'][$v] : '';
		$arr_shop_cart_price[$v] = isset($_POST['t_new_price'][$v]) ? $_POST['t_new_price'][$v] : 0;
	}
}
/*
echo $strFilter . "\n";
print_r( $arr_shop_cart );
print_r( $arr_shop_cart_size );
//die( __FILE__ . ':' . __LINE__ );
*/


//
// if ($strFilter == "") {
if (empty($arr_ids)) {
	_eb_alert('Không tồn tại giỏ hàng cần thiết');
}


//
// $strFilter = substr($strFilter, 1);
//echo $strFilter . "\n";
//die( __FILE__ . ':' . __LINE__ );


//
$t_email = strtolower(trim($_POST['t_email']));

if (_eb_check_email_type($t_email) != 1) {
	_eb_alert('Email không đúng định dạng');
}


//
$t_ten = trim($_POST['t_ten']);
$t_dienthoai = trim($_POST['t_dienthoai']);
$t_diachi = trim($_POST['t_diachi']);
$t_ghichu = trim($_POST['t_ghichu']);


//echo $tv_id . "\n";
//die( __FILE__ . ':' . __LINE__ );


//
//echo date( 'r', date_time ) . "\n";


// nếu đang là tài khoản admin -> luôn luôn tạo tài khoản mới
if (current_user_can('delete_posts')) {
	$tv_id = _eb_create_account_auto(array(
		'tv_matkhau' => '',
		'tv_hoten' => $t_ten,
		'tv_dienthoai' => $t_dienthoai,
		'user_name' => $t_dienthoai,
		'tv_diachi' => $t_diachi,
		'tv_email' => $t_email
	));

	// kiểm tra lại việc tạo tài khoản
	if ($tv_id <= 0) {
		_eb_alert('Không xác định được tài khoản khách hàng');
	}
}
// nếu tài khoản thông thường -> sẽ kiểm tra nhiều thứ hơn
else {
	//
	if (mtv_id > 0) {
		$tv_id = mtv_id;
	} else {
		$tv_id = _eb_create_account_auto(array(
			'tv_matkhau' => '',
			'tv_hoten' => $t_ten,
			'tv_dienthoai' => $t_dienthoai,
			'user_name' => $t_dienthoai,
			'tv_diachi' => $t_diachi,
			'tv_email' => $t_email
		));

		// kiểm tra lại việc tạo tài khoản
		if ($tv_id <= 0) {
			_eb_alert('Không xác định được tài khoản khách hàng');
		}
	}


	// kiểm tra lần gừi đơn trước đó, nếu mới gửi thì bỏ qua
	// daidq: cho phép gửi trùng lặp nhiều đơn 1 lúc
	if (1 > 2) {
		$strsql = _eb_q("SELECT *
		FROM
			`eb_in_con_voi`
		WHERE
			tv_id = " . $tv_id . "
		ORDER BY
			order_id DESC
		LIMIT 0, 1");
		//	print_r( $strsql );
		//	echo count($strsql);
		if (count($strsql) > 0) {
			$strsql = $strsql[0];
			//		print_r( $strsql );
			//die( __FILE__ . ':' . __LINE__ );

			// lấy thời gian gửi đơn hàng trước đó, mỗi đơn cách nhau tầm 5 phút
			$lan_gui_don_truoc = $strsql->order_time;
			//		echo date( 'r', $lan_gui_don_truoc ) . "\n";
			//		echo date_time - $lan_gui_don_truoc . "\n";

			// giãn cách gửi đơn
			if (date_time - $lan_gui_don_truoc < 30) {
				ket_thuc_qua_trinh_dat_hang($strsql->order_id, $strsql->order_sku, 'Cảm ơn bạn, chúng tôi đang tiếp nhận và xử lý đơn hàng của bạn.');
			}
		}
	}
}
//die( __FILE__ . ':' . __LINE__ );


// Hủy đơn hàng nếu thiếu dữ liệu đầu vào
$hd_trangthai = 0;
if (!isset($_POST['hd_products_info'])) {
	$_POST['hd_products_info'] = '';

	$hd_trangthai = -1;
}

if (!isset($_POST['hd_customer_info'])) {
	$_POST['hd_customer_info'] = '';

	$hd_trangthai = -1;
}
//die( __FILE__ . ':' . __LINE__ );


//
$product_js_list = trim($_POST['hd_products_info']);
$hd_mahoadon = date('mdhis', $date_time) . 'E' . $tv_id;


// thông tin đơn hàng
$arr = array(
	'order_sku' => $hd_mahoadon,
	'order_products' => $product_js_list,
	'order_total_price' => trim($_POST['t_total_price']),
	'order_customer' => trim($_POST['hd_customer_info']),
	//	'order_agent' => trim( $_POST ['hd_customer_info'] ),
	'order_ip' => $client_ip,
	'order_time' => date_time,
	'order_status' => $hd_trangthai,
	'tv_id' => $tv_id,
);
//print_r( $arr );
//die( __FILE__ . ':' . __LINE__ );


// xác định size, màu sắc để email cho khách
$decode_order_product = WGR_decode_for_products_cart($product_js_list);
//print_r( $decode_order_product );

// xác định mã giảm giá nếu có
$decode_order_discount = WGR_decode_for_discount_cart($arr['order_customer']);

$discount_code = '';
$discount_price = 0;
if ($decode_order_discount != NULL) {
	$decode_order_discount = $decode_order_discount[0];

	//
	$discount_code = $decode_order_discount->name;
	//	$discount_price = 0;
	if (isset($decode_order_discount->coupon_giagiam)) {
		$discount_price = _eb_float_only($decode_order_discount->coupon_giagiam);
	}

	if ($discount_price > 0) {
	} else if ($decode_order_discount->coupon_phantramgiam > 0) {
		$discount_price = $decode_order_discount->coupon_phantramgiam . '%';
	}
}
/*
if ( mtv_id == 1 ) {
	echo $discount_price;
	print_r( $decode_order_discount );
//die( __FILE__ . ':' . __LINE__ );
}
*/


$hd_id = EBE_set_order($arr);
if ($hd_id == 0) {
	//	EBE_tao_bang_hoa_don_cho_echbay_wp();

	_eb_alert('Lỗi gửi chi tiết đơn hàng');
}
//echo $hd_id . "\n";


//die( __FILE__ . ':' . __LINE__ );


// TEST
//$strFilter = '249,99';

// lấy danh sách sản phẩm để tạo email
$sql = _eb_load_post_obj(100, array(
	// 'post__in' => explode(',', $strFilter),
	'post__in' => $arr_ids,
));
// hỗ trợ đặt hàng tư page nếu không tìm thấy trong post
if (empty($sql->posts)) {
	$sql = _eb_load_post_obj(100, array(
		'post_type' => 'page',
		// 'post__in' => explode(',', $strFilter),
		'post__in' => $arr_ids,
	));

	//
	if (empty($sql->posts)) {
		_eb_alert('Không xác định được sản phẩm/ dịch vụ liên quan');
	}
}
//print_r($sql);
//die('fgj s fs');

//
$product_list = '';

//$product_js_list = '';

$tong_tien = 0;
// v2: daidq (2021-09-07)
$sql_post = $sql->posts;
foreach ($sql_post as $chitiet) {
	/*
	// v1
	while ( $sql->have_posts() ) {
		$sql->the_post();
		$chitiet = $sql->post;
	    */
	//print_r( $chitiet );
	//die('fhjhd ddfhfh');

	// kiểm tra nếu tồn tại số lượng giỏ hàng thì mới tiếp tục
	if (isset($arr_shop_cart[$chitiet->ID]) && $arr_shop_cart[$chitiet->ID] * 1 > 0) {

		// tạo chi tiết đơn
		EBE_set_details_order('product_id', $chitiet->ID, $hd_id);

		//
		//	$trv_giaban = _eb_float_only( _eb_get_post_meta( $chitiet->ID, '_eb_product_oldprice', true ) );
		$trv_giaban = _eb_float_only(_eb_get_post_object($chitiet->ID, '_eb_product_oldprice'));
		//	$trv_giamoi = _eb_float_only( _eb_get_post_meta( $chitiet->ID, '_eb_product_price', true ) );
		$trv_giamoi = _eb_float_only(_eb_get_post_object($chitiet->ID, '_eb_product_price'));
		$cthd_soluong = $arr_shop_cart[$chitiet->ID];

		// nếu có giá riêng theo từng size hoặc màu
		$gia_rieng_theo_size = '';
		if ($arr_shop_cart_price[$chitiet->ID] > 0) {
			$gia_rieng_theo_size = EBE_get_lang('booking_done_mail_size_price') . ': <strong>' . number_format($arr_shop_cart_price[$chitiet->ID]) . '</strong><br>';
		}

		//
		$total_line = $trv_giamoi * $cthd_soluong;
		$tong_tien += $total_line;

		//
		$trv_tietkiem = $trv_giaban - $trv_giamoi;
		$trv_khuyenmai = 0;
		if ($trv_giamoi > 0 && $trv_giaban > $trv_giamoi) {
			$trv_khuyenmai = 100 - intval($trv_giamoi * 100 / $trv_giaban);
		}

		//
		//	$masanpham = _eb_get_post_meta( $chitiet->ID, '_eb_product_sku', true );
		$masanpham = _eb_get_post_object($chitiet->ID, '_eb_product_sku');
		if ($masanpham == '') {
			$masanpham = $chitiet->ID;
		}

		//
		$trv_color = '';
		$trv_size = $arr_shop_cart_size[$chitiet->ID];
		if ($decode_order_product != NULL) {
			foreach ($decode_order_product as $v) {
				if ($v->id == $chitiet->ID) {
					$trv_color = $v->color;

					//
					if ($trv_size == '') {
						$trv_size = $v->size;
					}

					break;
				}
			}
		} else {
			//	$trv_color = _eb_get_post_meta( $chitiet->ID, '_eb_product_color', true );
			$trv_color = _eb_get_post_object($chitiet->ID, '_eb_product_color');
		}

		//
		$product_list .= EBE_get_lang('post_sku') . ': <strong>' . $masanpham . '</strong><br>
' . EBE_get_lang('booking_done_mail_post_name') . ': <a href="' . web_link . '?p=' . $chitiet->ID . '" target="_blank">' . $chitiet->post_title . '</a><br>
' . EBE_get_lang('cart_kichco') . ': ' . $trv_size . '<br>
' . EBE_get_lang('cart_mausac') . ': ' . $trv_color . '<br>
' . EBE_get_lang('post_giacu') . ': ' . number_format($trv_giaban) . '<br>
' . EBE_get_lang('post_giamoi') . ': <strong>' . number_format($trv_giamoi) . '</strong><br>
' . $gia_rieng_theo_size . '
' . EBE_get_lang('booking_done_mail_giam') . ': ' . $trv_khuyenmai . '% (' . number_format($trv_tietkiem) . ')<br>
' . EBE_get_lang('post_soluong') . ': ' . $cthd_soluong . '<br>
' . EBE_get_lang('cart_str_total') . ': ' . number_format($total_line) . '<br>
--------------------------------------------<br>';

		//
		/*
		$product_js_list .= ',{
			"id" : "' . $chitiet->ID . '",
			"name" : "' . str_replace( '"', '&quot;', $chitiet->post_title ) . '",
			"size" : "' . str_replace( '"', '&quot;', $arr_shop_cart_size [$chitiet->ID] ) . '",
			"color" : "' . str_replace( '"', '&quot;', $trv_color ) . '",
			"old_price" : "' . $trv_giaban . '",
			"price" : "' . $trv_giamoi . '",
			"quan" : "' . $cthd_soluong . '",
			"sku" : "' . $masanpham . '"
		}';
		*/
	}
}
//echo $product_list . "\n";
//die( __FILE__ . ':' . __LINE__ );
$product_list = _eb_del_line($product_list);
EBE_set_details_order('product_list', $product_list, $hd_id);
//echo $product_list . "\n";
//die( __FILE__ . ':' . __LINE__ );

//echo $product_js_list . "\n";
//$product_js_list = substr( _eb_del_line( $product_js_list ), 1 );


//
$current_domain = str_replace('/', '-', web_link);
$current_domain = str_replace(':', '-', $current_domain);

$backup_order_to_echbay = $arr_private_info_setting['site_url'] . 'actions/backup_order&domain=' . $_SERVER['HTTP_HOST'] . '&current_domain=' . $current_domain . '&data=';


$arr = array(
	'tv_email' => $t_email,
	'tv_hoten' => $t_ten,
	'tv_dienthoai' => $t_dienthoai,
	'tv_diachi' => $t_diachi
);
//print_r( $arr );
foreach ($arr as $k => $v) {
	$v = trim($v);

	if ($v != '') {
		EBE_set_details_order($k, $v, $hd_id);
	}
}
//_eb_alert($str_for_custom_order);
//die( __FILE__ . ':' . __LINE__ );


//
if (strpos($discount_price, '%') !== false) {
	//	if ( mtv_id == 1 ) {
	$discount_price = str_replace('%', '', $discount_price);
	//	echo $discount_price . '<br>' . "\n";
	$discount_price = $tong_tien / 100 * $discount_price;
	//	echo $discount_price . '<br>' . "\n";
	//die( __FILE__ . ':' . __LINE__ );
	//	}
}


// Gửi email thông báo
//$message = EBE_str_template ( 'html/mail/booking.html', array (
$message = EBE_html_template(WGR_get_html_template_lang('booking_mail', 'booking', EB_THEME_PLUGIN_INDEX . 'html/mail/'), array(
	'tmp.web_link' => web_link,
	'tmp.t_ten' => $t_ten == '' ? $t_email : $t_ten,

	'tmp.date_oder' => date('d-m-Y', $date_time),
	'tmp.hd_id' => $hd_id,
	'tmp.hd_mahoadon' => $hd_mahoadon,

	'tmp.discount_code' => $discount_code,
	'tmp.discount_price' => number_format($discount_price),
	'tmp.after_discount' => number_format($tong_tien - $discount_price),

	'tmp.t_diachi' => $t_diachi,
	'tmp.t_ghichu' => $t_ghichu,
	'tmp.t_dienthoai' => $t_dienthoai,
	'tmp.t_email' => $t_email,
	'tmp.t_amount' => number_format($tong_tien),
	'tmp.web_name' => $web_name,
	'tmp.product_list' => $product_list
	//), EB_THEME_PLUGIN_INDEX );
));
//echo $message . '<br>';
//die( __FILE__ . ':' . __LINE__ );


//
//$mail_title = 'Gui ban thong tin don hang: ' . $hd_mahoadon;
$mail_title = EBE_get_lang('booking_done_mail_title') . ': ' . $hd_mahoadon;


// gửi email cho admin
$mail_to_admin = $__cf_row['cf_email'];
if ($__cf_row['cf_email_note'] != '') {
	$mail_to_admin = $__cf_row['cf_email_note'];
} else if ($mail_to_admin == $__cf_row_default['cf_email']) {
	$mail_to_admin = '';
}


$bcc_email = '';
if (
	strpos($t_email, '@gmail.com') !== false ||
	strpos($t_email, '@yahoo.') !== false ||
	strpos($t_email, '@hotmail.com') !== false
) {
	//	if ( _eb_check_email_type( $t_email ) == 1 ) {
	$bcc_email = $t_email;
	//		_eb_send_email( $t_email, $mail_title, $message, '', $mail_to_admin );
	//	}
}


// -> ép buộc sử dụng hàm mail mặc định
//$__cf_row ['cf_sys_email'] = 0;

//_eb_send_email ( $mail_to_admin, $mail_title, $message, '', $bcc_email );

// lưu nội dung vào cache rồi thực hiện chức năng gửi mail sau
$content_for_cache_mail = '<mail_to_admin>' . $mail_to_admin . '</mail_to_admin>
<mail_title>' . $mail_title . '</mail_title>
<message>' . $message . '</message>
<bcc_email>' . $bcc_email . '</bcc_email>';

_eb_create_file(EB_THEME_CACHE . 'booking_mail/' . $hd_id . '.txt', $content_for_cache_mail);

if (is_dir(EB_THEME_CACHE . 'booking_mail_cache')) {
	_eb_create_file(EB_THEME_CACHE . 'booking_mail_cache/' . $hd_id . '.txt', $content_for_cache_mail);
}


//
$m = 'Cảm ơn bạn, thông tin đơn hàng của bạn đã được gửi đi.';


//
ket_thuc_qua_trinh_dat_hang($hd_id, $hd_mahoadon, $m);
