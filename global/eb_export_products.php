<?php



//
//if ( ! current_user_can('delete_posts') || ! isset( $_GET['token'] ) || $_GET['token'] != _eb_mdnam( $_SERVER['HTTP_HOST'] ) ) {
WGR_check_token();



function WGR_export_product_to_xml ( $op = array(), $post_type = 'post' ) {
	global $wpdb;
	
	// cấu hình mặc định
	if ( ! isset( $op['limit'] ) || $op['limit'] == '' || $op['limit'] == 0 ) {
		$op['limit'] = 50;
	}
	if ( ! isset( $op['trang'] ) || $op['trang'] == '' || $op['trang'] == 0 ) {
		$op['trang'] = 1;
	}
	$offset = ($op['trang'] - 1) * $op['limit'];
//	echo $offset;
	
	if ( ! isset( $op['join'] ) || $op['join'] == '' ) {
		$op['join'] = "";
	}
	if ( ! isset( $op['filter'] ) || $op['filter'] == '' ) {
		$op['filter'] = " AND ( `" . wp_posts . "`.post_status = 'publish' OR `" . wp_posts . "`.post_status = 'pending' OR `" . wp_posts . "`.post_status = 'draft' ) ";
	}
	if ( ! isset( $op['filter2'] ) || $op['filter2'] == '' ) {
		$op['filter2'] = "";
	}
//	print_r( $op ); exit();
	
	//
	$sql = "SELECT *
	FROM
		`" . wp_posts . "`
		" . $op['join'] . "
	WHERE
		`" . wp_posts . "`.post_type = '" . $post_type . "'
		" . $op['filter'] . $op['filter2'] . "
	GROUP BY
		ID
	ORDER BY
		ID
	LIMIT " . $offset . ", " . $op['limit'];
//	echo $sql;
	
	//
	return _eb_q( $sql );
}




// tạo URL cho phần cache
$rssCacheFilter = 'rss';



// test
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2000;
/*
if ( isset($_GET['test']) ) {
	$limit = 20;
}
*/
// cache theo số bản ghi
$rssCacheFilter .= '-limit' . $limit;



// kiểu export
$export_type = isset( $_GET['export_type'] ) ? $_GET['export_type'] : '';
$rssCacheFilter .= '-type' . $export_type;



// theo ID người dùng
$user_export = isset( $_GET['user_export'] ) ? $_GET['user_export'] : '';
$rssCacheFilter .= '-user' . $user_export;



//
$trang = isset( $_GET['trang'] ) ? (int)$_GET['trang'] : 1;
$rssCacheFilter .= '-trang' . $trang;



//
$arr_for_slect_data = array(
	'trang' => $trang,
	'limit' => $limit
);


//
$header_name = 'products';


// lọc theo cat id
$by_cat_id = isset( $_GET['by_cat_id'] ) ? (int) $_GET['by_cat_id'] : 0;
if ( $by_cat_id > 0 ) {
	$rssCacheFilter .= '-cat' . $by_cat_id;
	
	//
	$cats_type = isset( $_GET['cats_type'] ) ? trim( $_GET['cats_type'] ) : 'category';
//	$rssCacheFilter .= '-cat_type' . $cats_type;
	
	
	//
	$get_cat_name = get_term( $by_cat_id, $cats_type );
	if ( ! empty( $get_cat_name ) ) {
		$header_name = $get_cat_name->slug;
	}
	
	
	//
	$arrs_cats = array(
		'taxonomy' => $cats_type,
//		'hide_empty' => 0,
		'parent' => $by_cat_id
	);
	
	$arrs_cats = get_categories( $arrs_cats );
//	print_r( $arrs_cats );
	
	$by_child_cat_id = '';
	if ( ! empty( $arrs_cats ) ) {
		foreach ( $arrs_cats as $v ) {
			$by_child_cat_id .= ',' . $v->term_id;
		}
//		echo $by_child_cat_id . '<br>';
	}
	
	
	// câu lệnh lọc theo taxonomy
	$arr_for_slect_data['filter2'] = " AND `" . $wpdb->term_taxonomy . "`.taxonomy = '" . $cats_type . "'
		AND `" . $wpdb->term_taxonomy . "`.term_id IN (" . $by_cat_id . $by_child_cat_id . ") ";
	
	// câu lệnh jion các bảng lại với nhau
	$arr_for_slect_data['join'] = " LEFT JOIN `" . $wpdb->term_relationships . "` ON ( `" . wp_posts . "`.ID = `" . $wpdb->term_relationships . "`.object_id)
		LEFT JOIN `" . $wpdb->term_taxonomy . "` ON ( `" . $wpdb->term_relationships . "`.term_taxonomy_id = `" . $wpdb->term_taxonomy . "`.term_taxonomy_id ) ";
		
}

//
if ( $export_type != 'csv' ) {
	header("Content-Type: text/xml");
	header('Content-Disposition: inline; filename="' . $header_name . '-page' . $trang . '.xml"');
//	header('Content-Disposition: attachment; filename="' . $header_name . '-page' . $trang . '.xml"');
}


//
if ( $export_type == 'facebook'
|| $export_type == 'google' ) {
	$arr_for_slect_data['filter'] = " AND `" . wp_posts . "`.post_status = 'publish' ";
	
	$sql = WGR_export_product_to_xml( $arr_for_slect_data );
//	print_r( $sql );
	
	include EB_THEME_PLUGIN_INDEX . 'global/eb_export_products_facebook.php';
}
else if ( $export_type == 'csv' ) {
//	if ( isset( $_GET['post_status'] ) ) {
		$arr_for_slect_data['filter'] = " AND `" . wp_posts . "`.post_status = 'publish' ";
//	}
	
	$sql = WGR_export_product_to_xml( $arr_for_slect_data );
//	print_r( $sql );
	
	include EB_THEME_PLUGIN_INDEX . 'global/eb_export_products_csv.php';
}
else if ( $export_type == 'woo' ) {
	$sql = WGR_export_product_to_xml( $arr_for_slect_data, 'product' );
	
	include EB_THEME_PLUGIN_INDEX . 'global/eb_export_products_woo.php';
}
else {
	$sql = WGR_export_product_to_xml( $arr_for_slect_data );
	
	include EB_THEME_PLUGIN_INDEX . 'global/eb_export_products_default.php';
}





exit();




