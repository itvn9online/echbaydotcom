<?php

//
include_once EB_THEME_PLUGIN_INDEX . 'main_function.php';

//echo date('r' ); exit();


//
//print_r( $_GET );



// chế độ bảo trì đang được bật -> tạm dừng mọi truy cập
if ( file_exists( EB_THEME_CACHE . 'update_running.txt' ) ) {
	
	//
	$time_for_bao_tri = file_get_contents( EB_THEME_CACHE . 'update_running.txt', 1 );
	
	// Hiển thị chế độ bảo trì trong vòng 2 phút thôi
//	if ( date_time - $time_for_bao_tri < 30 ) {
	if ( date_time - $time_for_bao_tri < 60 ) {
//	if ( date_time - $time_for_bao_tri < 120 ) {
//	if ( date_time - $time_for_bao_tri < 300 ) {
		
		// Set trạng thái cho trang 404
		EBE_set_header(404);
//		$protocol = ( isset($_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
		
		//echo $protocol;
//		header( $protocol . ' 404 Not Found' );
		
		$m = 'He thong dang duoc bao tri</title><h1>He thong dang duoc bao tri! Vui long quay lai sau ' . ( 60 - ( date_time - $time_for_bao_tri ) ) . ' giay';
		
		die('<title>' . $m . '</h1><script>if ( top != self ) {alert("' . $m . '")}</script>');
		
	}
	else {
		// xóa file
		_eb_remove_file( EB_THEME_CACHE . 'update_running.txt' );
		
		echo '<!-- Remove update_running.txt in ebcache dir -->';
	}
}





//
//echo $__cf_row['cf_reset_cache'] . '<br>' . "\n";
//echo mtv_id . '<br>' . "\n";
//echo $act . '<br>' . "\n";
//print_r($post);

//echo $_SERVER['REQUEST_URI'] . '<br>' . "\n";
//echo $_SERVER['SCRIPT_NAME'] . '<br>' . "\n";
//echo $_SERVER['QUERY_STRING'] . '<br>' . "\n";
//echo ___eb_cache_getUrl () . '<br>' . "\n";
//exit();

// getUrl gets the queried page with query string




function ___eb_cache_display ( $cache_time = 60 ) {
	$filename = ___eb_cache_getUrl();
	
	// nếu không tồn tại file/
	if ( ! file_exists( $filename ) ) {
		
		//
		/*
		file_put_contents( $filename, '.', LOCK_EX ) or die('ERROR: create cache file');
		chmod($filename, 0777);
		*/
//		exit();
		
		// -> tạo file và trả về tên file
		$filew = fopen( $filename, 'x+' );
		// nhớ set 777 cho file
		chmod($filename, 0777);
		fclose($filew);
		
		//
//		exit();
		
		// trả về tên file
		return $filename;
	}
	
	// nếu tồn tại -> tiếp tục kiểm tra thời gian tạo cache
	/*
	$filer = fopen( $filename, 'r' );
	$data = fread( $filer, filesize( $filename) );
	fclose( $filer );
	*/
	
	//
	$time_file = filemtime ( $filename );
//	echo '<!-- ';
//	echo date_time - $time_file . '<br>' . "\n";
//	echo $cache_time . '<br>' . "\n";
//	echo ' -->';
	// 100 is the cache time here!!!
	if ( date_time - $time_file > $cache_time ) {
		return $filename;
	}
	
	//
	$data = file_get_contents ( $filename, 1 );
	if ( $data == '' || $data == '.' ) {
		return $filename;
	}
	
	//
	die( ___eb_cache_mobile_class ( $data ));
}


// set class cho bản mobile nếu sử dụng cache
function ___eb_cache_mobile_class ( $data ) {
//	return false;
	
//	global $func;
	
	if ( _eb_checkDevice() == 'mobile' ) {
		$data = str_replace( 'eb-set-css-pc-mobile', 'style-for-mobile', $data );
	}
	
	echo _eb_ssl_template( $data );
	
}




//
function ___eb_cache_end_ob_cache ( $strEBPageDynamicCache ) {
	global $set_time_for_main_cache;
	global $arr_private_info_setting;
	
//	exit();
	
	//
	$main_content = ob_get_contents();
	
	
	//
//	ob_end_flush();
	ob_end_clean();
	
	
	
	// xóa các class của WP đi, lằng nhằng vãi -> chiếm dụng nhiều HTML quá
	/*
	$arr_remove_wp_class = array(
		'menu-item-type-custom',
		'menu-item-type-taxonomy',
		'menu-item-object-blogs',
		'menu-item-object-category',
		'menu-item-has-children',
	);
	foreach ( $arr_remove_wp_class as $v ) {
		$main_content = str_replace( $v, '', $main_content );
	}
	*/
	
	// xóa các thẻ TAB đi -> rút gọn lại HTML 1 chút
//	$main_content = preg_replace( "/\t/", "", $main_content );
	
	// bỏ phần comment HTML
//	$main_content = WGR_remove_js_multi_comment( $main_content, '<!--', '-->' );
	
	// optimize javascript
	
	// bỏ các dấu xuống dòng thừa
//	$main_content = preg_replace( "/\n\n/", "\n", $main_content );
	
	
	
	// -> echo nội dung ra -> bị ob nên nội dung không được in ra
//	echo $main_content;
	___eb_cache_mobile_class ( $main_content );
	
	
	
	
	// thêm câu báo rằng đang lấy nội dung trong cache
	$eb_cache_note = WGR_create_eb_cache_note();
	
	//
//	echo $eb_cache_note;
	
	
	
	// lưu file tĩnh
//	_eb_get_static_html ( $strEBPageDynamicCache, $main_content );
	___eb_cache_cache ( $strEBPageDynamicCache, $main_content, $eb_cache_note );
}



// if no cache, callback cache
//ob_start ('___eb_cache_cache');







// mặc định là không cache
$enable_echbay_super_cache = 0;

// kiểm tra các chế độ cache
if ( $__cf_row['cf_reset_cache'] > 0 ) {
	/*
	if ( is_home() ) {
		echo 'home';
	}
	else {
		echo 'not home';
	}
	*/
//	echo $act . '<br>' . "\n";
	
	// nếu thành viên đang đăng nhập -> không cache
	if ( mtv_id > 0 ) {
		$enable_echbay_super_cache = 3;
	}
	// sử dụng cache echbay cho một số trang thôi, dùng nhiều chưa kiểm chứng lỗi
	else if (
		$act == ''
		|| $act == 'archive'
		|| $act == 'single'
		|| $act == 'page'
		|| isset( $is_page_templates )
	) {
//	else if ( $act == 'archive' || $act == 'single' || $act == 'page' ) {
		$enable_echbay_super_cache = 1;
		
		//
		if ( $__cf_row['cf_reset_cache'] < 30 ) {
			$enable_echbay_super_cache = 8;
		}
		// nếu chế độ cache wp đang được bật -> không dùng cache EchBay
		else if ( defined('WP_CACHE') && WP_CACHE == true ) {
			$enable_echbay_super_cache = 2;
		}
		// tắt ép cache -> dùng với các custom page mà cần kiểu submit -> tìm kiếm, đặt hàng
		else if ( defined('WGR_NO_CACHE') && WGR_NO_CACHE == true ) {
			$enable_echbay_super_cache = 7;
		}
		// tắt khi người dùng đang tìm kiếm
		else if ( isset( $_GET['search_advanced'] ) ) {
			$enable_echbay_super_cache = 4;
		}
	}
	else {
		$enable_echbay_super_cache = 9;
	}
}
//echo WP_CACHE . '<br>' . "\n";


//
if ( isset( $css_m_css ) ) {
//	print_r( $css_m_css );
	$css_m_css = array_merge( $css_m_css, get_body_class() );
}
else {
	$css_m_css = get_body_class();
}

// css riêng của post, page...
$class_css_of_post = '';


// load file index theo theme
$file_index_theo_theme = EB_THEME_URL . 'i.php';
if ( using_child_wgr_theme == 1 && file_exists( EB_CHILD_THEME_URL . 'i.php' ) ) {
	$file_index_theo_theme = EB_CHILD_THEME_URL . 'i.php';
}
//echo $file_index_theo_theme;


// cache chỉ được kích hoạt khi tham số này = 1
if ( $enable_echbay_super_cache == 1 ) {
	
	
	// nếu có tham số DNS prefetch -> kiểm tra domain hiện tại có trùng với DNS prefetch không
	if ( $__cf_row['cf_dns_prefetch'] != '' ) {
		$arr_dns_prefetch = explode( "\n", trim( $__cf_row['cf_dns_prefetch'] ) );
		
		// trùng thì hủy bỏ truy cập này luôn
		foreach ( $arr_dns_prefetch as $v ) {
			if ( trim( $v ) == $_SERVER['HTTP_HOST'] ) {
				EBE_set_header(403);
				
				die( file_get_contents( EB_THEME_PLUGIN_INDEX . 'html/dns_prefetch.html', 1 ) );
			}
		}
	}
	

	// check device (có cache) -> mặc định là 1 class css để lát replace nếu là mobile
	$css_m_css[] = 'eb-set-css-pc-mobile';
	
	
	
	
	// kiểm tra và hiển thị nội dung cũ nếu có -> có thì nó sẽ exist luôn rồi
	$strEBPageDynamicCache = ___eb_cache_display( $set_time_for_main_cache );
	
	
	
	
	
	// tạo file trước để giữ chỗ luôn và ngay đã
//	_eb_create_file ( EB_THEME_CACHE . $strEBPageDynamicCache . '.txt', '&nbsp;' );
	
	
	
	
	
	
	// chưa có -> gọi lệnh cache luôn
	// bắt đầu cho bộ cache toàn trang -> ở footer gọi hàm ob_end là được
	ob_start();
	
	
	
	
	// gọi đến file index của từng theme
	include_once $file_index_theo_theme;
	
	
	
	//
	___eb_cache_end_ob_cache ( $strEBPageDynamicCache );
	
	
	
}
// nếu không có cache -> include bình thường thôi
else {
//	echo 1;
	// check device (không cache)
//	$css_m_css = implode( ' ', get_body_class() );
//	$css_m_css = '';
	// dùng tính năng cache toàn trang thì tạm thời tắt chức năng phát hiện mobile bằng php
	if ( _eb_checkDevice() == 'mobile' ) {
		$css_m_css[] = 'style-for-mobile';
	}
	
	
	
	// gọi đến file index của từng theme
	//include_once EB_THEME_URL . 'index.php';
	include_once $file_index_theo_theme;
	
	
	
	// nếu cache đang được bật, nhưng lại dùng cache của đơn vị khác -> cũng hủy cache luôn
	if ( $enable_echbay_super_cache == 2 ) {
		echo '<!-- EchBay Cache (ebcache) is enable, but an another plugin cache is enable too
If you want to using EchBay Cache, please set WP_CACHE = false or comment WP_CACHE in file wp-config.php -->';
	}
	// nếu cache đang được bật, nhưng người dùng đăng nhập -> thông báo tới người dùng
	else if ( $enable_echbay_super_cache == 3 ) {
		echo '<!-- EchBay Cache (ebcache) is enable, but not caching requests by known users -->';
	}
	//
	else if ( $enable_echbay_super_cache == 4 ) {
		echo '<!-- EchBay Cache (ebcache) not running in SEARCH method -->';
	}
	else if ( $enable_echbay_super_cache == 7 ) {
		echo '<!-- EchBay Cache (ebcache) not running because WGR_NO_CACHE enable -->';
	}
	// chỉ cache với 1 số trang cụ thể thôi
	else if ( $enable_echbay_super_cache == 9 ) {
		echo '<!-- EchBay Cache cache only home page, category page, post details page -->';
	}
	// thời gian tối thiểu để cache là 30s
	else if ( $enable_echbay_super_cache == 8 ) {
		echo '<!-- EchBay Cache (ebcache) is enable, but not time for reset cache too many short (' . $__cf_row['cf_reset_cache'] . ' secondes). Min 30 secondes -->';
	}
	else {
		echo '<!-- EchBay Cache (ebcache) is disable -->';
	}
}






