<?php


$dir = dirname( __FILE__ ) . '/';
//echo $dir . "\n";



header( "Etag: " . file_get_contents( $dir . 'VERSION', 1 ) );
header( 'Content-Type: application/javascript; charset=UTF-8' );
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

$by_dir = 'javascript/';
if ( isset( $_GET['dir'] ) ) {
	$by_dir = $_GET['dir'];
}

foreach ( $load as $v ) {
//	echo $v . "\n";
	
	// nếu có trong thư mục mặc định của js echbaydotcom
	if ( file_exists( $dir . $by_dir . $v ) ) {
		echo file_get_contents( $dir . $by_dir . $v, 1 );
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
			echo file_get_contents( $dir2 . $v, 1 );
//		}
	}
}


exit();



