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
//echo $threadInPage . '<br>' . "\n";
//echo $strFilter . '<br>' . "\n";
//echo count( $sql ) . '<br>' . "\n";
//$main_content = json_encode( $sql );


$export_tilte = 'Order Export';

include EB_THEME_PLUGIN_INDEX . 'echbay/export_top.php';


?>
<table id="headerTable" border="0" cellpadding="0" cellspacing="0" width="100%" class="small">
	<tr class="text-center gray2bg">
		<td>STT</td>
		<td>ID</td>
		<td>Mã hóa đơn</td>
		<td>Ngày gửi</td>
		<td>Trạng thái</td>
		<td>Sản phẩm</td>
		<td>SL</td>
		<td>M.sắc</td>
		<td>K.Thước</td>
		<td>SKU</td>
		<td>C.Nặng</td>
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
		<td>Cộng</td>
		<td>Giảm</td>
		<td>Cước</td>
		<td>Tổng</td>
		<td>Khách hàng</td>
		<td>Điện thoại</td>
		<td>Địa chỉ</td>
		<td>Quận/ Huyện</td>
		<td>Tỉnh/ Thành phố</td>
		<td>Nguồn</td>
		<td>Phương tiện</td>
		<td>Chiến dịch</td>
	</tr>
</table>
<?php



include EB_THEME_PLUGIN_INDEX . 'echbay/export_footer.php';




//
//print_r( $arr_hd_trangthai );
//print_r( json_encode( $arr_hd_trangthai ) );



?>
<script>
var arr_hd_trangthai = <?php echo json_encode( $arr_hd_trangthai ); ?>;
<?php

$lang_date_format = _eb_get_option('date_format');
//echo $lang_date_format . 'aaaaaaaaaaaaaaaaaaaa';

foreach ( $sql as $k => $v ) {
	$v->ngay_gui = date( $lang_date_format, $v->order_time );
//	$v->ngay_gui = 'aaaaa';
	
	//
	echo 'WGR_order_export_run( ' . json_encode( $v ) . ', ' . $k . ' );' . "\n";
}

?>
</script>
</body>
</html>
<?php
/*
WGR_order_export_run ( <?php echo $main_content; ?>
,<?php echo json_encode( $arr_hd_trangthai ); ?>);
*/





exit();