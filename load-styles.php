<?php


$dir = dirname( __FILE__ );



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
foreach ( $load as $v ) {
//	echo $v . "\n";
	
	// nếu có trong thư mục mặc định của js echbaydotcom
	if ( substr( $v, 0, 1 ) == '/' ) {
		$out .= file_get_contents( $dir . $v, 1 );
	}
	else {
		$out .= file_get_contents( $dir2 . $v, 1 );
	}
}


// thay đổi lại URL ảnh cho chuẩn xác
$out = str_replace( '../images-global/', 'images-global/', $out );


echo $out;
exit();



