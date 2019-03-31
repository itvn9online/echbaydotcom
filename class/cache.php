<?php



/*
* Các tham số mặc định, khai báo trước khi cche được gọi
*/


//print_r( $___eb_lang );




// lấy URL trong config wp
if ( defined('WP_SITEURL') ) {
	$web_link = WP_SITEURL;
	
	if ( ! defined('WP_HOME') ) {
		define( 'WP_HOME', WP_SITEURL );
	}
//	echo $web_link;
}
else if ( defined('WP_HOME') ) {
	$web_link = WP_HOME;
	
	define( 'WP_SITEURL', WP_HOME );
}
else {
//	$web_link = get_bloginfo ( 'url' );
	
//	if ( is_admin() ) {
	// lấy URL động với site có dùng SSL
	/*
	if ( eb_web_protocol == 'https' ) {
		$web_link = eb_web_protocol . '://' . $_SERVER['HTTP_HOST'];
	}
	// mặc định thì lấy theo config
	else {
		*/
//		$web_link = get_option ( 'siteurl' );
		$web_link = _eb_get_option ( 'siteurl' );
		
		define( 'WP_SITEURL', $web_link );
		define( 'WP_HOME', WP_SITEURL );
		
		// do cơ chế update config của WGR sẽ khai báo WP_SITEURL, nên khi chưa có thì kiểm tra siteurl luôn
		WGR_auto_update_link_for_demo ( $web_link, $web_link );
//	}
}

// hỗ trợ link HTTP nếu truy cập vào cổng 443 -> cloudflare
//if ( eb_web_protocol == 'https' && strstr( $web_link, 'https://' ) == false ) {
	/*
if ( eb_web_protocol == 'https' ) {
	$web_link = str_replace( 'http://', 'https://', $web_link );
}
*/


// thêm dấu chéo vào cuối nếu chưa có
//if ( substr( $web_link, -1 ) != '/' ) {
	$web_link .= '/';
//}
//echo $web_link;

//
/*
if ( $localhost == 1 ) {
	$web_link = get_bloginfo ( 'url' ) . '/';
} else {
	$web_link = eb_web_protocol . '://' . $_SERVER['HTTP_HOST'] . '/';
}
*/




/*
* chỉnh lại đường dẫn tĩnh nếu sai thông số
/////////////////////////// tạm thời tắt chức năng hỗ trợ nhiều tên miền -> tốt cho google
*/
/*
if ( strstr( $web_link, $_SERVER['HTTP_HOST'] ) == false ) {
	// tách mảng để tạo URL cố định
	$web_link = explode('/', $web_link);
//	print_r($web_link);
	
	// thay bằng giá trị mới
	$web_link[2] = $_SERVER['HTTP_HOST'];
	
	// gán lại
	$web_link = implode( '/', $web_link );
//	echo $web_link;
}
*/

define( 'web_link', $web_link );
//echo web_link;




$__eb_cache_time = 0;

$__eb_cache_conf = EB_THEME_CACHE . '___all.php';
//echo $__eb_cache_conf . '<br>';

//$file_last_update = str_replace ( '.php', '.txt', $__eb_cache_conf );
$file_last_update = EB_THEME_CACHE . '___all.txt';
//echo $file_last_update . '<br>';



// không nạp cache trong một số trường hợp
if ( strstr( $_SERVER['REQUEST_URI'], '/admin-ajax.php' ) == false ) {
	if (file_exists ( $__eb_cache_conf )) {
		include_once $__eb_cache_conf;
	}
	else {
		die('config not select in ajax');
		$__cf_row = $__cf_row_default;
	}
}
else {
	include EB_THEME_CORE . 'cache2.php';
}



