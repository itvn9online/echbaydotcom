<?php


// tự động tạo page nếu chưa có
if ( $act != '' ) {
	WGR_create_page( $act, '', array(
		'page_template' => 'templates/products_all.php',
		'reload' => 1
	) );
}


// gọi tới file dùng chung của post
include EB_THEME_PLUGIN_INDEX . 'global/post.php';




