<?php



//
//print_r( $_GET );



//
$order_id = isset ( $_GET ['order_id'] ) ? (int) $_GET['order_id'] : 0;
if ( $order_id == 0 ) {
	die ( '<script>
	arr_user_blacklist.push({
		"error" : "order_id not found!"
	});
	</script>' );
}


//
$strFilter = " AND order_id != " . $order_id;

//
$dt = isset ( $_GET ['dt'] ) ? trim( $_GET['dt'] ) : '';
$e = isset ( $_GET ['e'] ) ? trim( $_GET['e'] ) : '';


// tìm theo 2 yếu tố
if ( $dt != '' && $e != '' ) {
	$strFilter .= " AND ( order_customer LIKE '%{$dt}%' OR order_customer LIKE '%{$e}%' ) ";
}
else if ( $dt != '' ) {
	$strFilter .= " AND order_customer LIKE '%{$dt}%' ";
}
else if ( $e != '' ) {
	$strFilter .= " AND order_customer LIKE '%{$e}%' ";
}
//echo $strFilter;


//
if ( $strFilter != '' ) {
	echo '<script>';
	
	//
//	echo $strFilter;
	
	// lấy danh sách đơn hàng
	$sql = _eb_load_order( 25, array(
		// chỉ lấy các đơn bị liệt vào danh sách đen
//		'status_by' => 12,
		'filter_by' => $strFilter
	) );
//	print_r( $sql );
	
	//
	foreach ( $sql as $v ) {
//		print_r( $v );
		
		// đối với các đơn khác
		$str_js = 'arr_list_orther_order';
		
		// đối với đơn bị báo xấu
		if ( $v->order_status == 12 ) {
			$str_js = 'arr_user_blacklist';
		}
		
		//
		echo $str_js . '.push({
			"order_id" : ' . $v->order_id . ',
			"order_status" : ' . $v->order_status . ',
			"order_customer" : "' . $v->order_customer . '"
		});';
	}
	
	//
	echo '</script>';
}



//exit();


