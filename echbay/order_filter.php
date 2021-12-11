<?php




$type_search = '';
$invoice_key = '';
if ( isset ( $_GET ['invoice_key'] ) ) {
	$invoice_key = $_GET['invoice_key'];
	
	if ( $invoice_key != '' ) {
		if( isset ( $_GET ['type_search'] ) ) {
			$type_search = $_GET['type_search'];
		}
		else {
			$type_search = _eb_getCucki('eb_admin_order_type_search');
		}
//		$invoice_key = urlencode( str_replace( '+', ' ', $invoice_key ) );
		$strLinkPager .= '&invoice_key=' . $invoice_key;
		
		// tìm kiếm theo key
		$invoice_slug_key = _eb_non_mark_seo( urldecode( $invoice_key ) );
//		echo $invoice_slug_key . '<br>';
		$invoice_slug_key = str_replace( '.', '', str_replace( '-', '', $invoice_slug_key ) );
//		echo $invoice_slug_key . '<br>';
		
		// cấu trúc thẻ tìm kiếm theo từng hạng mục
		if ( $type_search == 'sp' ) {
			$strFilter .= " AND ( order_products LIKE '%{$invoice_slug_key}%' OR order_products LIKE '%{$invoice_key}%' ) ";
		}
		else if ( $type_search == 'id' ) {
			$strFilter .= " AND ( order_sku LIKE '%{$invoice_key}%' OR order_id LIKE '%{$invoice_key}%' ) ";
		}
		else {
			$strFilter .= " AND ( order_customer LIKE '%{$invoice_slug_key}%' OR order_customer LIKE '%{$invoice_key}%' ) ";
		}
		$strLinkPager .= '&type_search=' . $type_search;
	}
}



// lọc theo trạng thái đơn
$status_by = '';
if ( isset( $_GET['tab'] ) &&  $_GET['tab'] != '' ) {
	$status_by = $_GET['tab'];
	
	// với đơn hàng đã ẩn, chỉ có supper admin mới được xem
	if ( (int) $status_by == 13 && ! current_user_can('create_users') ) {
		$status_by = '';
	}
	
	//
	if ( $status_by != '' ) {
		$status_by = (int) $status_by;
		
		$strFilter .= " AND order_status = " . $status_by;
	}
}
// lọc theo IP
else if ( isset( $_GET['by_ip'] ) &&  $_GET['by_ip'] != '' ) {
    $strFilter .= " AND order_ip = '" . $_GET['by_ip'] . "' ";
}
// khi người dùng tìm kiếm
else if ( isset ( $_GET ['invoice_key'] ) ) {
	// nếu tài khoản không phải là supper admin -> ẩn các đơn đã hủy
	if ( ! current_user_can('create_users') ) {
		$strFilter .= " AND order_status != 13 ";
	}
}
// mặc định thì bỏ qua cái đơn đã hủy
else {
	$strFilter .= " AND order_status != 4 AND order_status != 13 ";
}
//echo $strFilter . '<br>' . "\n";
$strLinkPager .= '&tab=' . $status_by;
$jsLinkPager = $strLinkPager;



// lọc theo ngày tháng
$filterDay = isset( $_GET['d'] ) ? $_GET['d'] : '';

if ( $filterDay != '' ) {
	$strFilter .= WGR_create_order_filter ( $filterDay );
	
	//
	if ( isset ( $_GET ['d1'] ) ) {
		$filterDay .= '&d1=' . $_GET ['d1'];
		
		//
		if ( isset ( $_GET ['d2'] ) ) {
			$filterDay .= '&d2=' . $_GET ['d2'];
		}
	}
	
	//
	$strLinkPager .= '&d=' . $filterDay;
}
//echo $strFilter . '<br>' . "\n";
//exit();




