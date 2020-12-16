<?php


$dir = __DIR__;



header( "Etag: " . file_get_contents( $dir . '/VERSION', 1 ) );
header( 'Content-Type: text/css; charset=UTF-8' );
$expires_offset = 31536000; // 1 year
header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires_offset ) . ' GMT' );
header( "Cache-Control: public, max-age=$expires_offset" );


if ( ! isset( $_GET['load'] ) || $_GET['load'] == '' ) {
	die('/* no money no load! */');
}


$load = $_GET['load'];


$load = explode( ',', $load );

$dir2 = dirname( $dir ) . '/';

//
//echo $dir . "\n"; print_r( $_GET ); echo $dir2 . "\n";

$out = '';
$arr_replace_url = array();
foreach ( $load as $v ) {
//	echo $v . "\n";
	
	// nếu có trong thư mục mặc định của js echbaydotcom
	if ( substr( $v, 0, 1 ) == '/' ) {
		$out .= file_get_contents( $dir . $v, 1 );
	}
	// đây là các thư mục khác
	else {
		$out .= file_get_contents( $dir2 . $v, 1 );
		$arr_replace_url[ dirname( $v ) ] = 1;
	}
}


// thay đổi lại URL ảnh cho chuẩn xác
$out = str_replace( '../images-global/', 'images-global/', $out );

//
//print_r( $arr_replace_url );
foreach ( $arr_replace_url as $k => $v ) {
	// nếu có dấu .. -> lùi lại 1 thư mục nữa
	$out = str_replace( '../images-child/', '../' . dirname( $k ) . '/images-child/', $out );
}

echo $out;

exit();



