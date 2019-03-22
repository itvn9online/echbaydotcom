<?php




$export_tilte = 'Products Export';

include EB_THEME_PLUGIN_INDEX . 'echbay/export_top.php';


//
$before_price = '';
$after_price = '';
if ( $__cf_row['cf_current_price_before'] == 1 ) {
	$before_price = $__cf_row['cf_current_sd_price'] . ' ';
}
else {
	$after_price = ' ' . $__cf_row['cf_current_sd_price'];
}



?>
<table id="headerTable" border="0" cellpadding="0" cellspacing="0" width="100%" class="small">
	<tr class="text-center gray2bg">
		<td>ID</td>
		<td>Title</td>
		<td>Post name</td>
		<td>URL</td>
		<td>Images</td>
		<td>Price</td>
		<td>Sale Price</td>
		<td>Post date</td>
		<td>Post date gmt</td>
		<td>Comment status</td>
		<td>Ping status</td>
		<td>Post status</td>
		<td>Post parent</td>
		<td>Menu order</td>
		<td>Post type</td>
	</tr>
	<?php
	
foreach ( $sql as $v ) {
	
	//
//	print_r( $v );
	
	//
//	$p_link = web_link . _eb_p_link( $v->ID );
	$p_link = _eb_p_link( $v->ID );
	
	//
	$trv_img = _eb_get_post_img( $v->ID );
	if ( strstr( $trv_img, '//' ) == false ) {
		$trv_img = web_link . $trv_img;
	}
	
	
	//
	$price = _eb_float_only( _eb_get_post_object( $v->ID, '_eb_product_oldprice', 0 ) );
	$new_price = _eb_float_only( _eb_get_post_object( $v->ID, '_eb_product_price', 0 ) );
	
	
	//
	echo '
<tr>
	<td>' . $v->ID . '</td>
	<td>' . $v->post_title . '</td>
	<td>' . $v->post_name . '</td>
	<td>' . $p_link . '</td>
	<td>' . $trv_img . '</td>
	<td>' . $before_price . $price . $after_price . '</td>
	<td>' . $before_price . $new_price . $after_price . '</td>
	<td>' . $v->post_date . '</td>
	<td>' . $v->post_date_gmt . '</td>
	<td>' . $v->comment_status . '</td>
	<td>' . $v->ping_status . '</td>
	<td>' . $v->post_status . '</td>
	<td>' . $v->post_parent . '</td>
	<td>' . $v->menu_order . '</td>
	<td>' . $v->post_type . '</td>
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




//
foreach ( $sql as $v ) {
	
	//
//	print_r( $v );
	
	//
//	$p_link = web_link . _eb_p_link( $v->ID );
	$p_link = _eb_p_link( $v->ID );
	
	//
	$trv_img = _eb_get_post_img( $v->ID );
	if ( strstr( $trv_img, '//' ) == false ) {
		$trv_img = web_link . $trv_img;
	}
	
	
	//
	echo '
<item>
	';
	
	
	//
	$post_categories = wp_get_post_categories( $v->ID );
//	print_r( $post_categories );
	foreach($post_categories as $c){
		$cat = get_term( $c );
//		print_r( $cat );
		
		echo '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '">' . $cat->name . '</category>';
	}
	
	
	//
	$arr_post_options = wp_get_object_terms( $v->ID, 'post_tag' );
//	print_r( $arr_post_options );
	
	foreach($arr_post_options as $c){
		$cat = get_term( $c, 'post_tag' );
//		print_r( $cat );
		
		echo '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '">' . $cat->name . '</category>';
	}
	
	
	//
	$arr_post_options = wp_get_object_terms( $v->ID, 'post_options' );
//	print_r( $arr_post_options );
	
	foreach($arr_post_options as $c){
		$cat = get_term( $c, 'post_options' );
//		print_r( $cat );
		
		echo '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '">' . $cat->name . '</category>';
	}
	
	
	
	// nạp lại các post meta của post này
	_eb_get_post_object( $v->ID, '_eb_product_price' );
//	print_r( $arr_object_post_meta );
	
	//
	$arr_meta = $arr_object_post_meta[ 'id' . $v->ID ];
//	print_r( $arr_meta );
	foreach ( $arr_meta as $k2 => $v2 ) {
		if ( strstr( $k2, '_eb_product_' ) == true ) {
			echo '
			<wp:postmeta>
				<wp:meta_key>' . $k2 . '</wp:meta_key>
				<wp:meta_value>' . $v2 . '</wp:meta_value>
			</wp:postmeta>';
		}
	}
	
	
	
	//
	echo '
</item>';
		
	//
}


//
echo '</channel>
</rss>';


