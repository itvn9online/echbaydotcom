<?php


// tách riêng phần cache config ra -> phần này chỉ có thay đổi khi người dùng update
include_once EB_THEME_CORE . 'cache_config.php';
include_once EB_THEME_CORE . 'cache_lang.php';


//
$error_admin_log_cache = WGR_check_syntax($__eb_cache_conf, $file_last_update, true);
$last_update = 0;
if ($error_admin_log_cache == '') {
    @include_once $__eb_cache_conf;
} else {
    _eb_log_admin($error_admin_log_cache);
}
// chấp nhận lần đầu truy cập sẽ lỗi
//@include_once $__eb_cache_conf;

// lấy config theo thời gian thực nếu tài khoản đang đăng nhập
/*
if ( mtv_id > 0 ) {
	// không reset mảng này -> do 1 số config sẽ được tạo theo config của wp
//	$__cf_row = $__cf_row_default;
	
	// nạp lại config riêng
	_eb_get_config( true );
	EBE_get_lang_list();
}
*/


// kiểm tra thời gian tạo cache
$__eb_cache_time = date_time - $__eb_cache_time + rand(0, 20);
//$__eb_cache_time += rand ( 0, 60 );
//echo $__eb_cache_time . '<br>';

//$time_for_update_cache = $cf_reset_cache;
$time_for_update_cache = $__cf_row['cf_reset_cache'];
//echo $time_for_update_cache . '<br>';


// nếu thành viên đang đăng nhập hoặc thời gian cache đã hết -> nạp cache theo thời gian thực
if (mtv_id > 0 || $__eb_cache_time > $time_for_update_cache) {
    //if ( 1 == 2 ) {
    //
    if (is_file($file_last_update)) {
        $last_update = filemtime($file_last_update);
    }

    // nếu thời gian update cache nhỏ quá -> bỏ qua
    //	if ( is_file ( $file_last_update ) && is_file ( $__eb_cache_conf ) ) {
    if ($last_update > 0) {
        if (date_time - $last_update < $time_for_update_cache / 2) {
            $__eb_cache_time = 0;
            if (is_file($__eb_cache_conf)) {
                include_once $__eb_cache_conf;
            }
            //			echo '<!-- __eb_cache_time: ' . $__eb_cache_time . ' -->' . "\n";
        }
    }


    // kiểm tra lại lần nữa cho chắc ăn
    if (mtv_id > 0 || $__eb_cache_time > $time_for_update_cache) {

        // dọn cache định kỳ -> chỉ dọn khi không phải thao tác thủ công
        if (
            mtv_id > 0
            //		&& strpos( $_SERVER['REQUEST_URI'], '/' . WP_ADMIN_DIR . '/' ) !== false
            //		&& is_admin ()
            &&
            !isset($_GET['tab'])
        ) {
            $_GET['time_auto_cleanup_cache'] = 6 * 3600;

            include_once EB_THEME_PLUGIN_INDEX . 'cronjob/cleanup_cache.php';
        }


        //
        if (mtv_id == 0 || !is_file($file_last_update)) {
            //		if ( ! is_file( $file_last_update ) ) {
            // tạo file cache
            _eb_create_file($file_last_update, date_time);
        }


        // tham số để lưu cache
        $arr_new_config = array();
        $__eb_cache_content = '$__eb_cache_time=' . date_time . ';' . "\n";


        /*
         * Một số thông số khác
         */

        //
        if ($__cf_row['cf_web_name'] == '') {
            //			$web_name = get_bloginfo ( 'name' );
            $web_name = get_bloginfo('blogname');
            //			$web_name = get_bloginfo ( 'sitename' );
        } else {
            $web_name = $__cf_row['cf_web_name'];
        }

        //
        //		$__eb_cache_content .= '$web_name="' . str_replace( '"', '\"', $web_name ) . '";$web_link="' . str_replace( '"', '\"', $web_link ) . '";' . "\n";
        $__eb_cache_content .= '$web_name="' . str_replace('"', '\"', $web_name) . '";' . "\n";


        //
        $__cf_row['cf_reset_cache'] = (int)$__cf_row['cf_reset_cache'];

        // nếu thời gian update config lâu rồi, cache chưa set -> để cache mặc định
        // lần cập nhật config cuối là hơn 3 tiếng trước -> để mặc định
        if (
            $localhost != 1 &&
            $__cf_row["cf_reset_cache"] <= 0
        ) {
            // cho cache 120s mặc định
            if ($__cf_row['cf_ngay'] < date_time - 3 * 3600) {
                $arr_new_config["cf_reset_cache"] = 120;
            }
            // hoặc tối thiểu 10s để còn test cache
            else {
                $arr_new_config["cf_reset_cache"] = 10;
            }
        }
        //		print_r( $__cf_row );

        //
        /*
		$sql = _eb_q("SELECT option_value
		FROM
			" . $wpdb->options . "
		WHERE
			option_name = 'blog_public'
		ORDER BY
			option_id DESC
		LIMIT 0, 1");
//		print_r($sql);
//		$cf_blog_public = 1;
		if ( isset( $sql[0]->option_value ) ) {
//			$cf_blog_public = $sql[0]->option_value;
			$arr_new_config ["cf_blog_public"] = $sql[0]->option_value;
		}
		*/
        //		$arr_new_config ["cf_blog_public"] = get_option( 'blog_public' );
        $arr_new_config["cf_blog_public"] = _eb_get_option('blog_public');

        // định dạng ngày giờ
        //		$arr_new_config ["cf_date_format"] = get_option( 'date_format' );
        $arr_new_config["cf_date_format"] = _eb_get_option('date_format');
        //		$arr_new_config ["cf_time_format"] = get_option( 'time_format' );
        $arr_new_config["cf_time_format"] = _eb_get_option('time_format');

        // -> tạo chuỗi để lưu cache
        foreach ($arr_new_config as $k => $v) {
            $__eb_cache_content .= '$__cf_row[\'' . $k . '\']="' . str_replace('"', '\"', str_replace('$', '\$', $v)) . '";' . "\n";

            //
            $__cf_row[$k] = $v;
        }


        // tạo file timezone nếu chưa có
        // chỉ với các website có ngôn ngữ không phải tiếng Việt
        if (
            $__cf_row['cf_content_language'] != 'vi'
            // timezone phải tồn tại
            &&
            $__cf_row['cf_timezone'] != ''
            // file chưa được tạo
            &&
            !is_file(EB_THEME_CACHE . '___timezone.txt')
        ) {
            _eb_create_file(EB_THEME_CACHE . '___timezone.txt', $__cf_row['cf_timezone']);
        }


        // danh sách menu đã được đăng ký
        $menu_locations = get_nav_menu_locations();
        //		print_r($menu_locations);
        foreach ($menu_locations as $k => $v) {
            $__eb_cache_content .= '$menu_cache_locations[\'' . $k . '\']="' . $v . '";' . "\n";
        }


        /*
         * lưu cache -> chỉ lưu khi thành viên chưa đăng nhập
         */
        /*
        echo '<!-- aaaaaaaaaaaaa -->' . "\n";
        echo '<!-- ' . mtv_id . ' -->' . "\n";
        echo '<!-- ' . $__eb_cache_conf . ' (??????) -->' . "\n";
        if ( ! is_file( $__eb_cache_conf ) ) {
        	echo '<!-- file not exist -->' . "\n";
        }
        else {
        	echo '<!-- file exist -->' . "\n";
        }
        if ( ! function_exists('is_file') ) {
        	echo '<!-- function not exists -->' . "\n";
        }
        */
        // không cho tạo cache liên tục
        // chỉ tạo khi khách truy cập hoặc không có file
        if (mtv_id == 0 || !is_file($__eb_cache_conf)) {
            //		if ( ! is_file( $__eb_cache_conf ) ) {

            //			echo '<!-- ' . $__eb_cache_conf . ' (!!!!!) -->' . "\n";
            _eb_create_file($__eb_cache_conf, '<?php ' . str_replace('\\\"', '\"', $__eb_cache_content));

            //
            _eb_log_user('Update common_cache: ' . $_SERVER['REQUEST_URI']);
        }


        /*
         * tạo các page tự động nếu chưa có
         */
        /*
		$strCacheFilter = 'auto_create_page';
		$check_Cleanup_cache = _eb_get_static_html ( $strCacheFilter, '', '', 3600 );
		if ($check_Cleanup_cache == false) {
//			_eb_create_page( 'cart', 'Giỏ hàng' );
			
//			_eb_create_page( 'contact', 'Liên hệ' );
			
//			_eb_create_page( 'landing-page', 'Landing page', 'templates/full-width.php' );
			
//			_eb_create_page( 'process', 'Process...' );
			
//			_eb_create_page( 'hoan-tat', 'Đặt hàng thành công' );
			
//			_eb_create_page( 'profile', 'Trang cá nhân' );
			
//			_eb_create_page( 'sitemap', 'Sitemap' );
			
			// ép lưu cache
			_eb_get_static_html ( $strCacheFilter, date( 'r', date_time ), '', 60 );
		}
		*/


        /*
         * Tự động dọn dẹp log sau một khoảng thời gian
         */
        /*
		$strCacheFilter = 'auto_clean_up_log';
		$check_Cleanup_cache = _eb_get_static_html ( $strCacheFilter, '', '', 24 * 3600 );
		if ( $check_Cleanup_cache == false ) {
			// user log
			$a = _eb_get_log_user( " LIMIT 2000, 1 " );
//			print_r($a);
//			echo count($a);
			
			//
			if ( count($a) > 0 ) {
				$sql = "DELETE FROM
					`" . wp_postmeta . "`
				WHERE
					post_id = " . eb_log_user_id_postmeta . "
					AND meta_key = '__eb_log_user'
					AND meta_id < " . $a[0]->meta_id;
//				echo $sql . '<br>';
				
				//
				_eb_q($sql);
			}
			
			
			
			
			// admin log
			$a = _eb_get_log_admin( " LIMIT 2000, 1 " );
//			print_r($a);
//			echo count($a);
			
			//
			if ( count($a) > 0 ) {
				$sql = "DELETE FROM
					`" . wp_postmeta . "`
				WHERE
					post_id = " . eb_log_user_id_postmeta . "
					AND meta_key = '__eb_log_admin'
					AND meta_id < " . $a[0]->meta_id;
//				echo $sql . '<br>';
				
				//
				_eb_q($sql);
			}
			
			
			
			
			// Lưu thời gian dọn log
			_eb_get_static_html ( $strCacheFilter, date( 'r', date_time ), '', 60 );
		}
		*/


        // Xóa revision
        include_once EB_THEME_PLUGIN_INDEX . 'cronjob/revision_cleanup.php';

        // số bài viết tối đa trên web
        include_once EB_THEME_PLUGIN_INDEX . 'cronjob/max_post_cleanup.php';


        // giải nén các thư mục thuộc dạng outsource nếu chưa có
        //WGR_unzip_vendor_code();
    }
}


// giải nén các thư viện mã ngoài (outsource, vendor...)
WGR_unzip_vendor_code();

// optimize các file tĩnh (css, js...)
//WGR_optimize_static_code();

/*
echo EB_THEME_PLUGIN_INDEX . '<br>' . "\n";
var_dump( strstr( EB_THEME_PLUGIN_INDEX, 'echbaydotcom' ) );
if ( strstr( EB_THEME_PLUGIN_INDEX, 'echbaydotcom' ) == true ) {
    echo 'str str true <br>' . "\n";
} else {
    echo 'str str false <br>' . "\n";
}
var_dump( strpos( EB_THEME_PLUGIN_INDEX, 'echbaydotcom' ) );
if ( strpos( EB_THEME_PLUGIN_INDEX, 'echbaydotcom' ) !== false ) {
    echo 'str pos true <br>' . "\n";
} else {
    echo 'str pos false <br>' . "\n";
}
echo strpos( EB_THEME_PLUGIN_INDEX, 'ech2baydotcom' ) . '<br>' . "\n";
if ( strpos( EB_THEME_PLUGIN_INDEX, 'ech2baydotcom' ) > 0 ) {
    echo 'str pos true <br>' . "\n";
} else {
    echo 'str pos false <br>' . "\n";
}
var_dump( strpos( EB_THEME_PLUGIN_INDEX, 'echbaydotcom' ) );
if ( strpos( EB_THEME_PLUGIN_INDEX, 'echbaydotcom' ) === true ) {
    echo 'str pos true <br>' . "\n";
} else {
    echo 'str pos false <br>' . "\n";
}
*/

// cập nhật web version định kỳ
/*
$auto_update_web_version = EB_THEME_CACHE . 'web_version_auto.txt';

//
$last_update_web_version = 0;
if ( is_file( $auto_update_web_version ) ) {
	$last_update_web_version = filemtime( $auto_update_web_version );
}

//
if ( date_time - $last_update_web_version + rand( 0, 60 ) > 600 ) {
	_eb_set_config( 'cf_web_version', date( 'md.Hi', date_time ), 0 );
	
	_eb_create_file( $auto_update_web_version, date( 'r', date_time ) . ' - ' . date( 'r', $last_update_web_version ) );
}
*/


//
//print_r( $__cf_row );
//print_r( $___eb_lang );


// TEST
//echo _eb_get_full_category_v2 ( 0, 'category', 1 );
/*
if ( mtv_id == 1 && is_home() ) {
	include_once EB_THEME_PLUGIN_INDEX . 'cronjob/revision_cleanup.php';
}
*/