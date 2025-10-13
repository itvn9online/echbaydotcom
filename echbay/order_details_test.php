<?php

// TEST
echo '<!--' . "\n";

$print_sql = $sql;

echo 'print_sql:' . "\n";
print_r($print_sql);


// xác định size, màu sắc để email cho khách
$decode_order_product = WGR_decode_for_products_cart($print_sql->order_products);
echo 'order_products:' . "\n";
print_r($print_sql->order_products);
print_r($decode_order_product);

// xác định mã giảm giá nếu có
$decode_order_discount = WGR_decode_for_discount_cart($print_sql->order_customer);
echo 'order_customer:' . "\n";
print_r($print_sql->order_customer);
print_r($decode_order_discount);


/*
$coupon_sql = _eb_q("SELECT *
FROM
	`" . wp_posts . "`
WHERE
	ID = 988098
	AND post_status = 'publish'
	AND post_type = 'post'");
print_r($coupon_sql);
if ( count( $coupon_sql ) > 0 ) {
	$coupon_post = $coupon_sql[0];
	print_r($coupon_post);
	$coupon_product_name = $coupon_post->post_title;
}
*/


/*
$strsql = _eb_q("SELECT *
	FROM
		`" . wp_posts . "`
	WHERE
		post_author = " . $sql->post_author . "
	ORDER BY
		ID DESC" );
if ( count($strsql) > 0 ) {
	$strsql = $strsql[0];
	print_r( $strsql );
	
	// lấy thời gian gửi đơn hàng trước đó, mỗi đơn cách nhau tầm 5 phút
	$lan_gui_don_truoc = strtotime( $strsql->post_date );
	echo date( 'r', $lan_gui_don_truoc ) . "\n";
	echo date_time - $lan_gui_don_truoc . "\n";
}
*/

echo "\n" . '-->';
// END TEST
