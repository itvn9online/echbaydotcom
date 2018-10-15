<?php



// lọc theo trạng thái đơn
$status_by = '';
if ( isset( $_GET['tab'] ) ) {
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
// mặc định thì bỏ qua cái đơn đã hủy
else {
	$strFilter .= " AND order_status != 4 AND order_status != 13 ";
}
$strLinkPager .= '&tab=' . $status_by;
$jsLinkPager = $strLinkPager;



// lọc theo ngày tháng
$filterDay = isset( $_GET['d'] ) ? $_GET['d'] : '';

if ( $filterDay != '' ) {
	$strFilter .= WGR_cereate_order_filter ( $filterDay );
	
	$strLinkPager .= '&d=' . $filterDay;
}
//echo $strFilter . '<br>' . "\n";
//exit();




