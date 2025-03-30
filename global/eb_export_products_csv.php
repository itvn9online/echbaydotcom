<?php




$export_tilte = 'Products Export';

include EB_THEME_PLUGIN_INDEX . 'echbay/export_top.php';


//
$before_price = '';
$after_price = '';
if ($__cf_row['cf_current_price_before'] == 1) {
	$before_price = $__cf_row['cf_current_sd_price'] . ' ';
} else {
	$after_price = ' ' . $__cf_row['cf_current_sd_price'];
}



?>
<table id="headerTable" border="0" cellpadding="0" cellspacing="0" width="100%" class="small">
	<tr class="text-center gray2bg">
		<td>ID</td>
		<td>Tên</td>
		<td>Hình ảnh</td>
		<td>Giá bán thường</td>
		<td>Giá khuyến mãi</td>
		<td>Danh mục</td>
		<td>Mô tả ngắn</td>
		<td>Mô tả</td>
	</tr>
	<?php

	foreach ($sql as $v) {

		//
		// print_r($v);

		//
		// $p_link = web_link . _eb_p_link($v->ID);
		$p_link = _eb_p_link($v->ID);

		//
		$trv_img = _eb_get_post_img($v->ID);
		if (strpos($trv_img, '//') === false) {
			$trv_img = web_link . $trv_img;
		}


		//
		$price = _eb_float_only(_eb_get_post_object($v->ID, '_eb_product_oldprice', 0));
		$new_price = _eb_float_only(_eb_get_post_object($v->ID, '_eb_product_price', 0));
		if ($new_price > 1 && $price < 1) {
			$price = $new_price;
			$new_price = 0;
		}

		// 
		$cats = get_the_category($v->ID);
		// print_r($cats);


		//
		echo '
<tr>
	<td>' . $v->ID . '</td>
	<td>' . $v->post_title . '</td>
	<td>' . $trv_img . '</td>
	<td>' . $price . '</td>
	<td>' . $new_price . '</td>
	<td>' . $cats[0]->name . '</td>
	<td>' . htmlspecialchars(nl2br($v->post_excerpt)) . '</td>
	<td>' . htmlspecialchars($v->post_content) . '</td>
</tr>';
	}

	?>
</table>
<?php



include EB_THEME_PLUGIN_INDEX . 'echbay/export_footer.php';




?>
</body>

</html>
<?php
/*
WGR_order_export_run ( <?php echo $main_content; ?>
,<?php echo json_encode( $arr_hd_trangthai ); ?>);
*/





exit();
