<?php

//
/*
$strCacheFilter = 'footer-ajax';
$main_content = _eb_get_static_html ( $strCacheFilter );
if ($main_content == false) {
	$main_content = '<h2>ERROR! get footer ajax content...</h2>';
}
else {
	include EB_THEME_PLUGIN_INDEX . 'common_content.php';
}
*/
$strCacheFilter = EB_THEME_CACHE . 'footer-ajax.txt';

// nếu không có file -> dừng lại chút, có thể file cache đang được tạo
if ( !file_exists( $strCacheFilter ) ) {
    sleep( 1 );
}

// thử kiểm tra lại
if ( file_exists( $strCacheFilter ) ) {
    $main_content = file_get_contents( $strCacheFilter, 1 );
    include EB_THEME_PLUGIN_INDEX . 'common_content.php';
} else {
    $main_content = '<h2>ERROR! get footer ajax content...</h2>';
}

//
$tmp = file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/footer-ajax.html', 1 );
echo str_replace( '{tmp.footer_content}', $main_content, $main_content );


exit();