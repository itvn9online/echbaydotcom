<?php



/*
* Do quá trình cache file config dễ bị lỗi file -> gây lỗi syntax -> nên phần config này sẽ update khi người dùng cập nhật web
*/
$__eb_cache_only_conf = EB_THEME_CACHE . '___conf.php';
$__eb_txt_only_conf = EB_THEME_CACHE . '___conf.txt';
WGR_check_syntax( $__eb_cache_only_conf, $__eb_txt_only_conf );


// chỉ tạo khi không có file cache config, hoặc người dùng đang đăng nhập thì lấy config theo thời gian thực
if ( mtv_id > 0 || ! file_exists ( $__eb_txt_only_conf ) ) {
	
	/*
	* Tự tạo các thư mục phục vụ cho cache nếu chưa có
	*/
	
	// Kiểm tra và tạo thư mục cache nếu chưa có
	if( ! is_dir( EB_THEME_CACHE ) ) {
//		echo EB_THEME_CACHE . '<br>' . "\n";
		
		// tự động tạo thư mục uploads nếu chưa có
		$dir_wp_uploads = dirname(EB_THEME_CACHE);
//		echo $dir_wp_uploads . '<br>' . "\n";
		if( !is_dir( $dir_wp_uploads ) ) {
			mkdir( $dir_wp_uploads, 0777 ) or die("ERROR create uploads directory: " . $dir_wp_uploads);
			// server window ko cần chmod
			chmod( $dir_wp_uploads, 0777 ) or die('chmod ERROR');
		}
		
		mkdir( EB_THEME_CACHE, 0777 ) or die("ERROR create cache directory");
		// server window ko cần chmod
		chmod( EB_THEME_CACHE, 0777 ) or die('chmod ERROR');
	}
	
	
	// tạo file này càng sớm càng tốt -> để hạn chế nhiều người cùng tạo file 1 lúc
	if ( ! file_exists( $__eb_txt_only_conf ) ) {
		_eb_create_file ( $__eb_txt_only_conf, 1 );
	}
	
	
	// các thư mục con của cache
	$arr_create_dir_cache = array(
		// lưu cache của các file css, js
		'static',
		'all',
		// thư mục mà các file trong này, chỉ bị reset khi hết hạn
		'noclean',
		// mail khi người dùng đặt hàng thành công sẽ gửi ở trang hoàn tất
		'booking_mail',
		'booking_mail_cache',
		'admin_invoice_product',
		'tv_mail',
		'post_meta',
		'post_img',
		'details'
	);
	foreach ( $arr_create_dir_cache as $v ) {
		$v = EB_THEME_CACHE . $v;
//		echo $v . '<br>';
		
		//
		if ( ! is_dir( $v ) ) {
			mkdir($v, 0777) or die('mkdir error');
			// server window ko cần chmod
			chmod($v, 0777) or die('chmod ERROR');
		}
	}
	
	
	
	/*
	* lấy các dữ liệu được tạo riêng cho config -> $post_id = -1;
	*/
	// reset lại cache
	$__cf_row = $__cf_row_default;
	
	//
	_eb_get_config();
	
	
	
	
	// chỉnh lại số điện thoại sang dạng html -> do safari lỗi hiển thị
	if ( $__cf_row['cf_call_dienthoai'] == '' && $__cf_row['cf_dienthoai'] != '' ) {
		$__cf_row['cf_call_dienthoai'] = '<a href="tel:' . $__cf_row['cf_dienthoai'] . '" rel="nofollow">' . $__cf_row['cf_dienthoai'] . '</a>';
	}
	
	if ( $__cf_row['cf_call_hotline'] == '' && $__cf_row['cf_hotline'] != '' ) {
		$__cf_row['cf_call_hotline'] = '<a href="tel:' . $__cf_row['cf_hotline'] . '" rel="nofollow">' . $__cf_row['cf_hotline'] . '</a>';
	}
	
	
	
	
	// tên thư mục chứa theme theo tiêu chuẩn của echbay
	$__cf_row ["cf_theme_dir"] = basename( dirname( dirname( EB_THEME_HTML ) ) );
	
	
	
	
	
	// chuyển đơn vị tiền tệ từ sau ra trước
	$custom_css_for_price = '';
	if ( $__cf_row['cf_current_price_before'] == 1 ) {
		
		//
		$custom_css_for_price .= '.ebe-currency:after{display:none}.ebe-currency:before{display:inline-block}';
		
		
		// đổi đơn vị tiền tệ
		if ( $__cf_row['cf_current_price'] != '' ) {
			$custom_css_for_price .= '.ebe-currency:before{content:"' . str_replace( '/', '\\', $__cf_row['cf_current_price'] ) . '"}';
		}
	}
	// đổi đơn vị tiền tệ
	else if ( $__cf_row['cf_current_price'] != '' ) {
		$custom_css_for_price .= '.ebe-currency:after{content:"' . str_replace( '/', '\\', $__cf_row['cf_current_price'] ) . '"}';
	}
	$__cf_row['cf_default_css'] .= $custom_css_for_price;
	
	
	
	// tạo mặt nạ cho nội dung
	/*
	if ( $__cf_row['cf_set_mask_for_details'] == 1 ) {
		$__cf_row['cf_default_css'] .= '.thread-content-mask{right:0;bottom:0;display:block}';
	}
	*/
	
	
	
	// chỉnh lại CSS cho phần thread-home-c2
	if ( $__cf_row['cf_home_sub_cat_tag'] != '' ) {
		//
	//	$__cf_row['cf_default_css'] .= '.thread-home-c2 a{color:' . $__cf_row['cf_default_color'] . '}.thread-home-c2 ' . $__cf_row['cf_home_sub_cat_tag'] . ':first-child{background-color:' . $__cf_row['cf_default_bg'] . '}.thread-home-c2 a:first-child {background:none !important}';
		
		//
		$__cf_row['cf_default_css'] = str_replace( '.thread-home-c2 a:first-child', '.thread-home-c2 ' . $__cf_row['cf_home_sub_cat_tag'] . ':first-child', $__cf_row['cf_default_css'] );
	}
	
	
	
	
	// thiết lập chiều rộng cho các module riêng lẻ
	if ( $__cf_row['cf_blog_class_style'] != '' ) {
		if ( $__cf_row['cf_top_class_style'] == '' ) {
			$__cf_row['cf_top_class_style'] = $__cf_row['cf_blog_class_style'];
		}
		
		if ( $__cf_row['cf_footer_class_style'] == '' ) {
			$__cf_row['cf_footer_class_style'] = $__cf_row['cf_blog_class_style'];
		}
		
		if ( $__cf_row['cf_cats_class_style'] == '' ) {
			$__cf_row['cf_cats_class_style'] = $__cf_row['cf_blog_class_style'];
		}
		
		if ( $__cf_row['cf_post_class_style'] == '' ) {
			$__cf_row['cf_post_class_style'] = $__cf_row['cf_blog_class_style'];
		}
		
		if ( $__cf_row['cf_blogs_class_style'] == '' ) {
			$__cf_row['cf_blogs_class_style'] = $__cf_row['cf_blog_class_style'];
		}
		
		if ( $__cf_row['cf_blogd_class_style'] == '' ) {
			$__cf_row['cf_blogd_class_style'] = $__cf_row['cf_blog_class_style'];
		}
	}
	
	
	
	// -> tạo chuỗi để lưu cache
	$__eb_cache_content = '';
	foreach ( $__cf_row as $k => $v ) {
		$__eb_cache_content .= '$__cf_row[\'' . $k . '\']="' . str_replace ( '"', '\"', str_replace ( '$', '\$', $v ) ) . '";' . "\n";
	}
	
	
	
	
	
	/*
	* Tối ưu thẻ META với mạng xã hội
	*/
	$arr_meta = array();
	
	// social
	if ( $__cf_row ['cf_google_plus'] != '' ) {
		$arr_meta[] = '<meta itemprop="author" content="' .$__cf_row ['cf_google_plus']. '?rel=author" />';
		$arr_meta[] = '<link rel="author" href="' .$__cf_row ['cf_google_plus']. '" />';
		$arr_meta[] = '<link rel="publisher" href="' .$__cf_row ['cf_google_plus']. '" />';
	}
	
	// https://developers.facebook.com/docs/plugins/comments/#settings
	if ( $__cf_row ['cf_facebook_id'] != '' ) {
		$arr_meta[] = '<meta property="fb:app_id" content="' . $__cf_row ['cf_facebook_id'] . '" />';
	}
	else if ( $__cf_row ['cf_facebook_admin_id'] != '' ) {
		// v2 -> fb có ghi chú, chỉ sử dụng 1 trong 2 (fb:app_id hoặc fb:admins)
		$fb_admins = explode( ',', $__cf_row ['cf_facebook_admin_id'] );
		foreach ( $fb_admins as $v ) {
			$v = trim( $v );
			if ( $v != '' ) {
				$arr_meta[] = '<meta property="fb:admins" content="' . $v . '" />';
			}
		}
		
		// v1
//		$arr_meta[] = '<meta property="fb:admins" content="' .$__cf_row ['cf_facebook_admin_id']. '" />';
	}
	
	/* https://developers.facebook.com/tools/debug/og/object/
	if ( $__cf_row ['cf_facebook_page'] != '' ) {
		$arr_meta[] = '<meta property="article:publisher" content="' . $__cf_row ['cf_facebook_page'] . '" />';
		$arr_meta[] = '<meta property="article:author" content="' . $__cf_row ['cf_facebook_page'] . '" />';
	}
	*/
	
	// seo local
	if ( $__cf_row ['cf_region'] != '' ) {
		$arr_meta[] = '<meta name="geo.region" content="' . $__cf_row ['cf_region'] . '" />';
	}
	
	if ( $__cf_row ['cf_placename'] != '' ) {
		$arr_meta[] = '<meta name="geo.placename" content="' . $__cf_row ['cf_placename'] . '" />';
	}
	
	if ( $__cf_row ['cf_position'] != '' ) {
		$arr_meta[] = '<meta name="geo.position" content="' . $__cf_row ['cf_position'] . '" />';
		$arr_meta[] = '<meta name="ICBM" content="' . $__cf_row ['cf_position'] . '" />';
	}
	
	//
//	print_r( $arr_meta );
	$dynamic_meta = '';
	/*
	foreach ( $arr_meta as $v ) {
		$dynamic_meta .= $v . "\n";
	}
	*/
	$dynamic_meta .= implode( "\n", $arr_meta );
	
	// save
	$__eb_cache_content .= '$dynamic_meta="' . str_replace( '"', '\"', $dynamic_meta ) . '";' . "\n";
	
	
	
	
	/*
	* ID và tài khoản MXH
	*/
	$add_data_id = array (
//		'web_name' => '\'' . $__cf_row ['web_name'] . '\'',
//		'service_name' => '\'' . $service_name . '\'',
		
		'eb_disable_auto_get_thumb' => (int) $__cf_row ['cf_disable_auto_get_thumb'],
		
		'cf_facebook_page' => '\'' . str_replace( '/', '\/', $__cf_row ['cf_facebook_page'] ) . '\'',
		'__global_facebook_id' => '\'' . $__cf_row ['cf_facebook_id'] . '\'',
		'cf_instagram_page' => '\'' . str_replace( '/', '\/', $__cf_row ['cf_instagram_page'] ) . '\'',
		'cf_google_plus' => '\'' . str_replace( '/', '\/', $__cf_row ['cf_google_plus'] ) . '\'',
		'cf_youtube_chanel' => '\'' . str_replace( '/', '\/', $__cf_row ['cf_youtube_chanel'] ) . '\'',
		'cf_twitter_page' => '\'' . str_replace( '/', '\/', $__cf_row ['cf_twitter_page'] ) . '\'' 
	);
	$cache_data_id = '';
	foreach ( $add_data_id as $k => $v ) {
		$cache_data_id .= ',' . $k . '=' . $v;
	}
	$cache_data_id = substr ( $cache_data_id, 1 );
	$__eb_cache_content .= '$cache_data_id="' . $cache_data_id . '";' . "\n";
	
	
	
	//
	if ( ! file_exists( $__eb_cache_only_conf ) ) {
//		echo '<!-- ' . $__eb_cache_only_conf . ' (!!!!!) -->' . "\n";
		_eb_create_file ( $__eb_cache_only_conf, '<?php ' . str_replace( '\\\"', '\"', $__eb_cache_content ) );
		
		//
		_eb_log_user ( 'Update cache_config: ' . $_SERVER ['REQUEST_URI'] );
		
		// kiểm tra lại sau khi tạo file
		$error_admin_log_cache = WGR_check_syntax( $__eb_cache_only_conf, $__eb_txt_only_conf, false, true );
		if ( $error_admin_log_cache != '' ) {
			_eb_log_admin( $error_admin_log_cache );
		}
	}
	
}
else {
	include_once $__eb_cache_only_conf;
}


