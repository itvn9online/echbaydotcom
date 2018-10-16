<?php



//
//if ( ! current_user_can('manage_options') ) {
if ( ! current_user_can('publish_pages') ) {
	die('Permission ERROR!');
}

//
WGR_check_token();

//
_eb_log_admin( 'Export order in ' . _eb_full_url() );



$threadInPage = 1000;
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
body { opacity: .1; }
body.done {
	opacity: 1;
	-moz-transition: all 0.8s ease;
	-o-transition: all 0.8s ease;
	-webkit-transition: all 0.8s ease;
	transition: all 0.8s ease;
}
body,
a { color: #000; }
a:hover { text-decoration: underline; }
#headerTable { line-height: 16px; }
/*
#headerTable { border-left: 1px #ccc solid; }
#headerTable td {
	border-right: 1px #ccc solid;
	padding: 0 3px 0 6px;
}
#headerTable.pd { border-top: 1px #ccc solid; }
#headerTable.pd td {
	padding: 5px 3px 5px 6px;
	border-bottom: 1px #ccc solid;
}
*/
#headerTable.pd tr:first-child { font-weight: bold; }
/*
#headerTable.pd tr:first-child td,
#headerTable td.text-center {
	padding-left: 3px;
	padding-right: 3px;
}
*/
#headerTable tr.selected,
#headerTable tr:hover { background: #f2f2f2; }
</style>
</head>

<body>
<table id="headerTable" border="0" cellpadding="0" cellspacing="0" width="100%" class="small">
	<tr class="text-center gray2bg">
		<td>ID</td>
		<td>Mã hóa đơn</td>
		<td>Trạng thái</td>
		<td>Sản phẩm</td>
		<td>SL</td>
		<td>M.sắc</td>
		<td>K.Thước</td>
		<!--
		<td>Sản phẩm (S.Lượng/ Màu sắc/ Kích thước)</td>
			--> 
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
		<td>Phương tiện</td>
		<td>Chiến dịch</td>
	</tr>
</table>
<?php




$a = array(
	EB_THEME_PLUGIN_INDEX . 'outsource/javascript/jquery/3.2.1.min.js',
	EB_THEME_PLUGIN_INDEX . 'outsource/javascript/jquery/migrate-3.0.0.min.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/functions.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/eb.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/all.js',
//	EB_THEME_PLUGIN_INDEX . 'javascript/edit_post.js'
	EB_THEME_PLUGIN_INDEX . 'echbay/js/order_export.js'
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
WGR_order_export_run ( <?php echo $main_content; ?>, <?php echo json_encode( $arr_hd_trangthai ); ?> );
</script>
</html>
<?php





exit();




