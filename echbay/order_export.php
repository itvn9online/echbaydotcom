<?php



//
if ( ! current_user_can('manage_options') ) {
	die('Permission ERROR!');
}

//
_eb_log_admin( 'Export order in ' . _eb_full_url() );



$threadInPage = 68;
$strFilter = "";
$strLinkPager = '';
$offset = 0;



include ECHBAY_PRI_CODE . 'order_function.php';

include ECHBAY_PRI_CODE . 'order_filter.php';



$sql = _eb_load_order( $threadInPage, array(
//	'status_by' => $status_by,
	'filter_by' => $strFilter,
	'offset' => $offset
) );

//
//print_r( $sql );
$main_content = json_encode( $sql );



?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Order Export</title>
<?php



$a = array(
//	EB_THEME_PLUGIN_INDEX . 'outsource/fa-4.7.0/i.css',
//	EB_THEME_PLUGIN_INDEX . 'outsource/fontawesome-free-5.0.6/css/fontawesome.css',
	EB_THEME_PLUGIN_INDEX . 'outsource/fa-5.3.0/css/i.css',
	EB_THEME_PLUGIN_INDEX . 'outsource/fa-5.3.0/css/v4-shims.min.css',
	EB_THEME_PLUGIN_INDEX . 'css/d.css',
	EB_THEME_PLUGIN_INDEX . 'css/d2.css',
	EB_THEME_PLUGIN_INDEX . 'css/admin.css',
	EB_THEME_PLUGIN_INDEX . 'css/admin-blog-widget.css'
);
foreach ( $a as $v ) {
//	$k = EB_THEME_PLUGIN_INDEX . $v;
//	echo $k . '<br>' . "\n";
//	if ( file_exists( $v ) ) {
		echo '<link rel="stylesheet" href="' . str_replace( ABSPATH, web_link, $v ) . '?v=' . filemtime( $v ) . '" type="text/css" media="all" />' . "\n";
//	}
}





$order_max_post_new = 0;
$str_ads_status = '';
$str_product_status = '';
$web_ad_link = web_link;

include EB_THEME_PLUGIN_INDEX . 'class/custom/admin-js.php';


?>
<style type="text/css">
a { color: #000; }
a:hover { text-decoration: underline; }
table {
	border-left: 1px #ccc solid;
	border-top: 1px #ccc solid;
}
table td {
	padding: 5px 6px;
	border-right: 1px #ccc solid;
	border-bottom: 1px #ccc solid;
}
</style>
</head>

<body>
<table id="headerTable" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr class="bold text-center">
		<td>id</td>
		<td>Mã hóa đơn</td>
		<td>Trạng thái</td>
		<td>Sản phẩm (S.Lượng/ Màu sắc/ Kích thước)</td>
		<!--
		<td><div class="cf">
				<div class="lf f80">Sản phẩm</div>
				<div class="lf f5">S.Lượng</div>
				<div class="lf f10">Màu sắc</div>
				<div class="lf f5">Kích thước</div>
			</div></td>
			-->
		<td>Khách hàng</td>
		<td>Điện thoại</td>
		<td>Địa chỉ</td>
		<td>Nguồn</td>
		<td>Chiến dịch</td>
		<td>Cộng tác viên</td>
	</tr>
</table>
<?php




$a = array(
	EB_THEME_PLUGIN_INDEX . 'outsource/javascript/jquery/3.2.1.min.js',
	EB_THEME_PLUGIN_INDEX . 'outsource/javascript/jquery/migrate-3.0.0.min.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/functions.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/eb.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/all.js'
//	EB_THEME_PLUGIN_INDEX . 'javascript/edit_post.js'
);
foreach ( $a as $v ) {
//	if ( file_exists( $v ) ) {
		echo '<script type="text/javascript" src="' . str_replace( ABSPATH, web_link, $v ) . '?v=' . filemtime( $v ) . '"></script>' . "\n";
//	}
}




//
//print_r( $arr_hd_trangthai );
//print_r( json_encode( $arr_hd_trangthai ) );



?>
<script>
function WGR_order_export_products ( arr ) {
//	arr = jQuery.parseJSON( unescape( arr ) );
//	console.log( arr );
	
	//
	var str = '';
	
	for ( var i = 0; i < arr.length; i++ ) {
		str += '<div class="cf">' +
			'<div class="lf f80"><a href="' + web_link + '?p=' + arr[i].id + '" target="_blank" rel="nofollow">- ' + arr[i].name + '</a></div>' +
			'<div class="lf f5 text-center">' + arr[i].quan + '</div>' +
			'<div class="lf f10 text-center remove-size-color-tag">' + arr[i].color + '</div>' +
			'<div class="lf f5 text-center remove-size-color-tag">' + arr[i].size + '</div>' +
		'</div>';
	}
	
	return str;
}


//
(function ( arr, arr_status ) {
	
	console.log( arr );
	
	//
	var order_lnk = admin_link + 'admin.php?page=eb-order&id=';
	
	//
	for ( var i = 0; i < arr.length; i++ ) {
		//
		var prod = jQuery.parseJSON( unescape( arr[i].order_products ) );
		console.log( prod );
		//
		var cus = jQuery.parseJSON( unescape( arr[i].order_customer ) );
		console.log( cus );
		
		//
		$('#headerTable').append('<tr>' +
			'<td><a href="' + order_lnk + arr[i].order_id + '" target="_blank" rel="nofollow">' + arr[i].order_id + '</a></td>' +
			'<td><a href="' + order_lnk + arr[i].order_id + '" target="_blank" rel="nofollow">' + arr[i].order_sku + '</a></td>' +
			'<td>' + ( typeof arr_status[ arr[i].order_status ] != 'undefined' ? arr_status[ arr[i].order_status ] : 'Unknown' ) + '</td>' +
			'<td>' + WGR_order_export_products( prod ) + '</td>' +
			'<td class="upper">' + ( cus['hd_ten'] == '' ? 'No-name' : cus['hd_ten'] ) + '</td>' +
			'<td>' + cus['hd_dienthoai'] + '</td>' +
			'<td>' + cus['hd_diachi'] + '</td>' +
			'<td>' + arr[i].order_id + '</td>' +
			'<td>' + arr[i].order_id + '</td>' +
			'<td>' + arr[i].order_id + '</td>' +
		'</tr>');
		
	}
	
	//
	$('.remove-size-color-tag strong').remove();
	
})( <?php echo $main_content; ?>, <?php echo json_encode( $arr_hd_trangthai ); ?> );


// xác định chiều rộng cho table hiển thị cho đẹp



</script>
</html>
<?php





exit();




