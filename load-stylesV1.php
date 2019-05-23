<?php


$dir = dirname( __FILE__ ) . '/';
//echo $dir . "\n";



header( "Etag: " . file_get_contents( $dir . 'VERSION', 1 ) );
header( 'Content-Type: text/css; charset=UTF-8' );
$expires_offset = 31536000; // 1 year
header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires_offset ) . ' GMT' );
header( "Cache-Control: public, max-age=$expires_offset" );


//print_r( $_GET );

if ( ! isset( $_GET['load'] ) || $_GET['load'] == '' ) {
	die('/* no money no load! */');
}


$load = $_GET['load'];


$load = explode( ',', $load );

$dir2 = '';

$out = '';
foreach ( $load as $v ) {
//	echo $v . "\n";
	
	// nếu có trong thư mục mặc định của js echbaydotcom
	if ( file_exists( $dir . 'css/' . $v ) ) {
		$out .= file_get_contents( $dir . 'css/' . $v, 1 );
	}
	else if ( file_exists( $dir . 'css/default/' . $v ) ) {
		$out .= file_get_contents( $dir . 'css/default/' . $v, 1 );
	}
	else if ( file_exists( $dir . 'css/template/' . $v ) ) {
		$out .= file_get_contents( $dir . 'css/template/' . $v, 1 );
	}
	// details
	else if ( file_exists( $dir . 'html/details/' . $v ) ) {
		$out .= file_get_contents( $dir . 'html/details/' . $v, 1 );
	}
	else if ( file_exists( $dir . 'html/details/mobilemua/' . $v ) ) {
		$out .= file_get_contents( $dir . 'html/details/mobilemua/' . $v, 1 );
	}
	else if ( file_exists( $dir . 'html/details/pcmua/' . $v ) ) {
		$out .= file_get_contents( $dir . 'html/details/pcmua/' . $v, 1 );
	}
	// search
	else if ( file_exists( $dir . 'html/search/' . $v ) ) {
		$out .= file_get_contents( $dir . 'html/search/' . $v, 1 );
	}
	// theme
	else if ( file_exists( $dir . 'themes/css/' . $v ) ) {
		$out .= file_get_contents( $dir . 'themes/css/' . $v, 1 );
	}
	else {
		// nếu chưa có dir2 -> tìm dir2
		if ( $dir2 == '' ) {
			$get_dir = $dir;
			for ( $i = 0; $i < 10; $i++ ) {
				$get_dir = dirname( $get_dir );
//				echo $get_dir . "\n";
				
				if ( file_exists( $get_dir . '/' . $v ) ) {
					$dir2 = $get_dir . '/';
					break;
				}
			}
		}
		
		// kiểm tra lại lần nữa
//		if ( $dir2 != '' ) {
			$out .= file_get_contents( $dir2 . $v, 1 );
//		}
	}
}


// thay đổi lại URL ảnh cho chuẩn xác
$out = str_replace( '../images-global/', 'images-global/', $out );


echo $out;
exit();



