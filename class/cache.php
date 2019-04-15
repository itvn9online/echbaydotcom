<?php


function WGR_check_syntax ( $__eb_cache_conf, $file_last_update, $auto_clean = false ) {
	
	//
	if ( ! file_exists ( $__eb_cache_conf )) {
		return '';
	}
	
	// nếu có file kiểm tra lần update cuối
	if ( ! file_exists ( $file_last_update ) ) {
		unlink ( $__eb_cache_conf );
		return '';
	}
	
	//
	$error_admin_log_cache = '';
	
	//
	/*
	if ( function_exists('php_check_syntax') ) {
		echo 'php_check_syntax<br>';
	}
	*/
	
	/*
	if ( file_exists($__eb_cache_conf) && is_readable($__eb_cache_conf) ) {
		include_once $__eb_cache_conf;
	} else {
		throw new Exception('Include file does not exists or is not readable.');
	}
	*/
	
	$last_update = filemtime ( $file_last_update );
//	$last_update = file_get_contents ( $file_last_update );
	
	// tối đa 35 phút cache, quá thì tự dọn dẹp, hạn chế để web lỗi cache
	if ( $auto_clean == true && $last_update > 0 && date_time - $last_update > 2100 ) {
		unlink ( $file_last_update );
		unlink ( $__eb_cache_conf );
		$error_admin_log_cache = 'Auto reset cache after 2100s';
	}
	// nạp conf
	else {
		// cứ 120s, kiểm tra lỗi code 1 lần
		if ( $last_update > 0 && date_time - $last_update > 120 ) {
			// kiểm tra lỗi cấu trúc file nếu có
			// kiểm tra ký tự cuối cùng của file
			$return_check_syntax = trim( file_get_contents( $__eb_cache_conf, 1 ) );
			
			// nếu không phải dấu ; -> lỗi
			if ( substr( $return_check_syntax, -1 ) != ';' ) {
				// thử kiểm tra lại bằng exec
				$return_check_syntax = 0;
				if ( function_exists('exec') ) {
					exec("php -l {$__eb_cache_conf}", $output, $return_check_syntax);
//					echo $return_check_syntax . '<br>';
					
					// khác 0 -> có lỗi
					if ( $return_check_syntax != 0 ) {
						//
//						echo 'ERROR code!'; exit();
						
						// xóa file cache đi để thử lại
						unlink ( $file_last_update );
						unlink ( $__eb_cache_conf );
//						sleep( rand( 2, 10 ) );
						$error_admin_log_cache = 'Cache syntax ERROR by exec ' . basename( $__eb_cache_conf );
						
						//
//						echo eb_web_protocol . ':' . _eb_full_url();
//						wp_redirect( eb_web_protocol . ':' . _eb_full_url(), 302 ); exit();
					}
					// không lỗi thì dùng bình thường
					/*
					else {
						include_once $__eb_cache_conf;
					}
					*/
				}
				// không thì cứ xóa thẳng tay
				else {
					// xóa file cache đi để thử lại
					unlink ( $file_last_update );
					unlink ( $__eb_cache_conf );
//					sleep( rand( 2, 10 ) );
					$error_admin_log_cache = 'Cache syntax ERROR by substr ' . basename( $__eb_cache_conf );
				}
			}
		}
//		else {
//			try {
//				include_once $__eb_cache_conf;
				/*
			} catch ( Exception $e ) {
				echo 'Error include site config';
			}
			*/
//		}
	}
	
	//
	return $error_admin_log_cache;
}




function convert_arr_cache_to_parameter($arr) {
	$str = '';
	foreach ( $arr as $k => $v ) {
		$_get_type = gettype ( $v );
		if ($_get_type == 'array') {
			$_content = '';
			foreach ( $v as $k2 => $v2 ) {
				$_content .= ',"' . $k2 . '"=>"' . str_replace( '"', '\"', $v2 ) . '"';
			}
			$str .= '$' . $k . '=array(' . substr ( $_content, 1 ) . ');';
		} else if ($_get_type == 'integer' || $_get_type == 'double') {
			$str .= '$' . $k . '=' . $v . ';';
		} else {
			$v = str_replace ( '$', '\$', $v );
			$v = str_replace ( '"', '\"', $v );
			$str .= '$' . $k . '="' . $v . '";';
		}
		$str .= "\n";
	}
	
	return $str;
}



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



// chỉ nạp mới cache trong một số trường hợp
if ( strstr( $_SERVER['REQUEST_URI'], '/admin-ajax.php' ) == false ) {
	include_once EB_THEME_CORE . 'cache2.php';
}
// còn lại sẽ sử dụng cache cũ (nếu có)
else if (file_exists ( $__eb_cache_conf )) {
	include_once $__eb_cache_conf;
}
else {
	// v3 -> nạp lại cache -> nếu có lỗi sẽ lỗi 1 lần rồi thôi
	include_once EB_THEME_CORE . 'cache2.php';
	
	/*
	// v2 -> tạm thời cứ dừng ở đây đã
	die('config not select in ajax');
	
	// v1 -> nạp config mặc định
	$__cf_row = $__cf_row_default;
	*/
}



