<div class="hide-if-order-open-in-popup"><br>
	<div><a href="javascript:;" class="medium blackcolor bold admin-set-reload-url">Đơn hàng <i class="fa fa-refresh"></i></a></div>
	<ul class="cf eb-admin-tab eb-order-filter-tab">
		<li><a href="admin.php?page=eb-order">Tất cả</a></li>
		<?php
		global $arr_hd_trangthai;
		
		//
		foreach ( $arr_hd_trangthai as $k => $v ) {
			if ( $k >= 0 && $v != 'none' ) {
				echo '<li data-tab="' . $k . '" title="' . str_replace( '"', '&quot;', $v ) . '"><a href="admin.php?page=eb-order&tab=' . $k . '">' . $v . ' <sup id="show_count_order_by' . $k . '" data-value="0">0</sup></a></li>';
			}
		}
		?>
	</ul>
</div>
<script type="text/javascript" src="<?php echo EB_URL_OF_PLUGIN . 'echbay/js/order.js?v=' . date_time; ?>"></script>
<?php


//
include ECHBAY_PRI_CODE . 'order_function.php';





// đặt cookie riêng cho từng bộ lọc, nếu không có -> sẽ sử dụng cookie dùng chung
$str_for_order_cookie_name = 'get_order_by_time_line';

// lấy theo cookie nếu có
$order_by_time_line = _eb_getCucki( $str_for_order_cookie_name );
if( ! isset ( $_GET ['d'] ) && $order_by_time_line != '' ) {
	$_GET ['d'] = $order_by_time_line;
}



// tính tổng số đơn hàng theo từng tab
//print_r( $arr_hd_trangthai );
$strCountFilter = "";
if ( $order_by_time_line != '' ) {
	$strCountFilter .= WGR_cereate_order_filter ( $order_by_time_line );
}
//echo $strCountFilter . '<br>' . "\n";
foreach ( $arr_hd_trangthai as $k => $v ) {
	if ( $k >= 0 && $v != 'none' ) {
		$totalTabThread = _eb_c ( "SELECT COUNT(order_id) AS c
		FROM
			`eb_in_con_voi`
		WHERE
			tv_id > 0
			" . $strCountFilter . "
			AND order_status = " . $k );
		echo '<span data-id="' . $k . '" class="each-to-count-tab d-none">' . $totalTabThread . '</span>';
	}
}



//
if ( isset($_GET['id']) ) {
	$id = (int)$_GET['id'];
	if ( $id > 0 ) {
		include ECHBAY_PRI_CODE . 'order_details.php';
	}
} else {
	include ECHBAY_PRI_CODE . 'order_list.php';
}






