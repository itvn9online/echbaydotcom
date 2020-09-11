<?php
/*
* Mọi code dùng chung cho trang chủ sản phẩm, lấy hay không sẽ dựa vào config của khách
*/

// cache
/*
$strCacheFilter = 'home-ajax';
$main_content = _eb_get_static_html ( $strCacheFilter );
if ($main_content == false) {
	$main_content = '<h2>ERROR! get home ajax content...</h2>';
}
else {
	include EB_THEME_PLUGIN_INDEX . 'common_content.php';
}
*/
$strCacheFilter = EB_THEME_CACHE . 'home-ajax.txt';

// nếu không có file -> dừng lại chút, có thể file cache đang được tạo
if ( ! file_exists( $strCacheFilter ) ) {
	sleep(1);
}

// thử kiểm tra lại
if ( file_exists( $strCacheFilter ) ) {
	$main_content = file_get_contents( $strCacheFilter, 1 );
	include EB_THEME_PLUGIN_INDEX . 'common_content.php';
}
else {
	$main_content = '<h2>ERROR! get home ajax content...</h2>';
}

//
echo $main_content;



exit();


