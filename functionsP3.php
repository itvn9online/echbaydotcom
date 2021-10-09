<?php

// chức năng cho hoặc không cho truy cập vào 1 file nào đó
function WGR_deny_or_accept_vist_php_file( $progress_file, $deny_or_accept, $warning_content ) {
    global $arr_private_info_setting;

    if ( file_exists( $progress_file ) ) {
        $progress_content = file_get_contents( $progress_file, 1 );
        $progress_content = explode( "\n", trim( $progress_content ) );

        // Kiểm tra dòng đầu tiên xem đã được add câu lệnh die vào chưa
        $progress_content[ 0 ] = trim( $progress_content[ 0 ] );

        // nếu chế độ xem qua xmlrpc đang tắt
        if ( $deny_or_accept != 1 ) {
            // kiểm tra có lệnh die chưa -> như này là chưa add -> add thêm thôi
            if ( $progress_content[ 0 ] == '<?php' || $progress_content[ 0 ] == '<?' ) {
                $progress_content[ 0 ] = '<?php die("' . $warning_content . ' method has been disable by ' . $arr_private_info_setting[ 'site_upper' ] . '");';

                _eb_create_file( $progress_file, implode( "\n", $progress_content ) );
            }
        }
        // cho phép xem qua xmlrpc
        else {
            // cho xem
            if ( $progress_content[ 0 ] == '<?php' || $progress_content[ 0 ] == '<?' ) {}
            // không cho xem
            else {
                $progress_content[ 0 ] = '<?php';

                _eb_create_file( $progress_file, implode( "\n", $progress_content ) );
            }
        }
    }
}


function WGR_remove_html_comments( $a ) {
    // v2
    return WGR_remove_js_multi_comment( $a, '<!--', '-->' );

    // v1
    $str = '';

    $a = explode( '-->', $a );
    foreach ( $a as $v ) {
        $v = explode( '<!--', $v );
        $str .= $v[ 0 ];
    }

    return trim( $str );

}


function WGR_copy_secure_file( $FromLocation, $ToLocation, $VerifyPeer = false, $VerifyHost = true ) {
    // Initialize CURL with providing full https URL of the file location
    $Channel = curl_init( $FromLocation );

    // Open file handle at the location you want to copy the file: destination path at local drive
    $File = fopen( $ToLocation, "w" );

    // Set CURL options
    curl_setopt( $Channel, CURLOPT_FILE, $File );

    // We are not sending any headers
    curl_setopt( $Channel, CURLOPT_HEADER, 0 );

    // Disable PEER SSL Verification: If you are not running with SSL or if you don't have valid SSL
    curl_setopt( $Channel, CURLOPT_SSL_VERIFYPEER, $VerifyPeer );

    // Disable HOST (the site you are sending request to) SSL Verification,
    // if Host can have certificate which is nvalid / expired / not signed by authorized CA.
    curl_setopt( $Channel, CURLOPT_SSL_VERIFYHOST, $VerifyHost );

    // Execute CURL command
    curl_exec( $Channel );

    // Close the CURL channel
    curl_close( $Channel );

    // Close file handle
    fclose( $File );

    // return true if file download is successfull
    return file_exists( $ToLocation );
}


/*
 * Tải file theo thời gian thực
 */
function EBE_admin_get_realtime_for_file( $v ) {
    return filemtime( str_replace( EB_URL_OF_PLUGIN, EB_THEME_PLUGIN_INDEX, $v ) );
}

function EBE_admin_set_realtime_for_file( $arr ) {
    foreach ( $arr as $k => $v ) {
        $arr[ $k ] = $v . '?v=' . EBE_admin_get_realtime_for_file( $v );
    }
    return $arr;
}


// kiểm tra và trả về đường dẫn của file theme tương ứng
function WGR_check_and_load_tmp_theme( $load_config_temp, $dir_all_theme ) {
    global $arr_for_show_html_file_load;
    global $arr_for_add_css;
    //global $arr_for_add_theme_css;

    //
    $tmp_child_theme = '';
    if ( using_child_wgr_theme == 1 ) {
        $tmp_child_theme = EB_CHILD_THEME_URL . 'ui/' . $load_config_temp;
        //echo $tmp_child_theme . '<br>' . "\n";
    }

    $tmp_theme = EB_THEME_URL . 'ui/' . $load_config_temp;
    //echo $tmp_theme . '<br>' . "\n";

    $tmp_plugin = EB_THEME_PLUGIN_INDEX . 'themes/' . $dir_all_theme . '/' . $load_config_temp;
    //echo $tmp_plugin . '<br>' . "\n";


    // ưu tiên hàng của child theme trước
    if ( $tmp_child_theme != '' && file_exists( $tmp_child_theme ) ) {
        $arr_for_show_html_file_load[] = '<!-- config HTML (child theme): ' . $load_config_temp . ' -->';

        $main_content = file_get_contents( $tmp_child_theme, 1 );

        $arr_for_add_css[ EBE_get_css_for_theme_design( $load_config_temp, EB_CHILD_THEME_URL ) ] = 1;
        //$arr_for_add_theme_css[ EBE_get_css_for_theme_design ( $load_config_temp, EB_CHILD_THEME_URL ) ] = 1;
    }
    // sau đó đến theme
    else if ( file_exists( $tmp_theme ) ) {
        $arr_for_show_html_file_load[] = '<!-- config HTML (theme): ' . $load_config_temp . ' -->';

        $main_content = file_get_contents( $tmp_theme, 1 );

        $arr_for_add_css[ EBE_get_css_for_theme_design( $load_config_temp ) ] = 1;
        //$arr_for_add_theme_css[ EBE_get_css_for_theme_design ( $load_config_temp ) ] = 1;
    }
    // rồi đến plugin
    else if ( file_exists( $tmp_plugin ) ) {
        $arr_for_show_html_file_load[] = '<!-- config HTML (plugin): ' . $load_config_temp . ' -->';

        $main_content = file_get_contents( $tmp_plugin, 1 );

        $arr_for_add_css[ EBE_get_css_for_config_design( $load_config_temp, '.html' ) ] = 1;
        //$arr_for_add_theme_css[ EBE_get_css_for_config_design ( $load_config_temp, '.html' ) ] = 1;
    } else {
        return 'File not exist! ' . $load_config_temp;
    }

    return $main_content;
}


// chuyển các mảng dữ liệu động về một định dạng chuẩn hơn
function WGR_convert_default_theme_to_confog( $arr ) {
    if ( isset( $arr[ 'top' ] ) ) {
        foreach ( $arr[ 'top' ] as $k => $v ) {
            $arr[ 'cf_top' . ( $k + 1 ) . '_include_file' ] = $v;
        }
        unset( $arr[ 'top' ] );
    }

    if ( isset( $arr[ 'footer' ] ) ) {
        foreach ( $arr[ 'footer' ] as $k => $v ) {
            $arr[ 'cf_footer' . ( $k + 1 ) . '_include_file' ] = $v;
        }
        unset( $arr[ 'footer' ] );
    }

    if ( isset( $arr[ 'home' ] ) ) {
        foreach ( $arr[ 'home' ] as $k => $v ) {
            $arr[ 'cf_home' . ( $k + 1 ) . '_include_file' ] = $v;
        }
        unset( $arr[ 'home' ] );
    }

    return $arr;
}


function WGR_parameter_not_found( $f ) {
    die( 'Parameter not found (' . basename( $f, '.php' ) . ')' );
}


// host không phải là bản demo -> cập nhật lại url mới luôn và ngay
function WGR_auto_update_link_for_demo( $current_homeurl, $current_siteurl ) {
    //if ( $_SERVER['HTTP_HOST'] == 'demo.webgiare.org' ) {
    if ( $_SERVER[ 'HTTP_HOST' ] == 'webgiare.org' || $_SERVER[ 'HTTP_HOST' ] == 'themes.webgiare.org' ) {
        return false;
    }

    // riêng đối với domain demo của webgiare
    if (
        strstr( $current_homeurl, 'webgiare.org/demo' ) == true
        //|| strstr( $current_homeurl, 'www.webgiare.org/demo' ) == true
        //|| strstr( $current_siteurl, 'www.webgiare.org/demo' ) == true
        ||
        strstr( $current_siteurl, 'webgiare.org/demo' ) == true
    ) {
        _eb_update_option( 'home', eb_web_protocol . '://' . $_SERVER[ 'HTTP_HOST' ] );
        _eb_update_option( 'siteurl', eb_web_protocol . '://' . $_SERVER[ 'HTTP_HOST' ] );

        wp_redirect( _eb_full_url(), 301 );
        exit();
    }

    return true;
}


// Lấy DOM content của file xml
function WGR_get_dom_xml( $a, $tag ) {
    $a = explode( '</' . $tag . '>', $a );
    $a = explode( '<' . $tag . '>', $a[ 0 ] );
    if ( count( $a ) > 1 ) {
        return $a[ 1 ];
    }

    return '';
}


// đồng bộ URL cũ với mới cho nội dung
function WGR_sync_old_url_in_content( $a, $c ) {
    /*
    if ( $a == '' ) {
    	return $c;
    }
    */

    //
    $a = explode( ',', $a );
    foreach ( $a as $v ) {
        $v = trim( $v );
        if ( $v != '' ) {
            // nếu tên miền hiện tại là tên miền cũ -> redirect luôn
            if ( $v == $_SERVER[ 'HTTP_HOST' ] ) {
                //echo _eb_get_option('home') . $_SERVER['REQUEST_URI'];
                wp_redirect( _eb_get_option( 'home' ) . $_SERVER[ 'REQUEST_URI' ], 301 );
                exit();
            }

            //
            $c = str_replace( '/' . $v . '/', '/' . $_SERVER[ 'HTTP_HOST' ] . '/', $c );
        }
    }

    return $c;
}

function WGR_replace_for_all_content( $list_replace, $content_replace ) {
    //echo $content_replace . '<br>' . "\n";
    //print_r( $list_replace );
    //global $__cf_row;
    //$list_replace = $__cf_row['cf_replace_content'];

    //
    //if ( mtv_id == 1 ) echo $list_replace . '<br>' . "\n";

    //$list_replace = explode( "\n", trim( $list_replace ) );
    $list_replace = explode( "\n", trim( $list_replace ) );
    //print_r( $list_replace );

    //
    //if ( mtv_id == 1 ) print_r( $list_replace );

    foreach ( $list_replace as $v ) {
        $v = trim( $v );
        if ( $v != '' ) {
            $v = explode( '|', $v );
            //print_r( $v );
            //if ( mtv_id == 1 ) print_r( $v );
            $content_replace = str_replace( trim( $v[ 0 ] ), trim( $v[ 1 ] ), $content_replace );
        }
    }

    return $content_replace;
}


// thêm thẻ LI theo tiêu chuẩn chung cho thread node
function WGR_add_li_to_thread_node( $str ) {
    if ( strstr( $str, '</li>' ) == false ) {
        $str = '<li data-id="{tmp.trv_id}" data-ngay="{tmp.trv_ngayhethan}" data-price="{tmp.trv_num_giamoi}" data-per="{tmp.pt}" data-link="{tmp.p_link}" data-status="{tmp.product_status}" class="hide-if-gia-zero">' . $str . '</li>';
    }

    //
    return EBE_arr_tmp( array(
        'thread_list_mua' => EBE_get_lang( 'thread_list_mua' ),
        'thread_list_more' => EBE_get_lang( 'thread_list_more' )
    ), $str );
}


// sắp xếp lại mảng của taxonomy sau khi select, ẩn các nhóm đang bị khóa
function WGR_order_and_hidden_taxonomy( $arr, $order_only = 0, $no_cache = 0 ) {
    /*
     * no_cache: khi muốn lấy dữ liệu trực tiếp từ CSDL thì đặt lệnh này
     */
    //
    //print_r( $arr );

    //
    $a = array();
    $a2 = array();

    // sắp xếp lại thứ tự của cat
    if ( $order_only == 1 ) {
        foreach ( $arr as $v ) {
            $stt = _eb_get_cat_object( $v->term_id, '_eb_category_order', 0, $no_cache );

            $a[ $v->term_id ] = $stt;

            $v->stt = $stt;
            $a2[ $v->term_id ] = $v;
        }
    }
    // thêm cả chức năng bỏ qua các nhóm đã khóa
    else {
        foreach ( $arr as $v ) {
            // không lấy các nhóm đã bị khóa
            if ( isset( $v->term_id ) && _eb_get_cat_object( $v->term_id, '_eb_category_hidden', 0, $no_cache ) != 1 ) {
                $stt = _eb_get_cat_object( $v->term_id, '_eb_category_order', 0, $no_cache );

                $a[ $v->term_id ] = $stt;

                $v->stt = $stt;
                $a2[ $v->term_id ] = $v;
            }
        }
    }

    // Sắp xếp mảng từ lớn đến bé
    arsort( $a );

    // gán lại giá trị cho mảng sa khi order
    foreach ( $a as $k => $v ) {
        $a[ $k ] = $a2[ $k ];
    }

    //
    return $a;

}


function create_cache_infor_by( $s ) {
    return trim( '
/*
Served from: ' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] . ' on ' . date( 'r', date_time ) . '
List file: ' . substr( $s, 1 ) . '
*/
	' );
}


function WGR_default_config( $k ) {
    if ( !isset( $_POST[ $k ] ) || ( int )$_POST[ $k ] != 1 ) {
        //return 0;
        return 'off';
    }
    return 1;
}


// xóa bài viết theo cách riêng
function WGR_remove_post_by_type( $post_type = 'revision', $ID = 0, $strFilter = '' ) {
    // Nếu có ID -> xóa luôn theo ID
    if ( $ID > 0 ) {
        wp_delete_post( $ID, true );
    }
    // nếu không -> xóa theo post type truyền vào
    else {
        global $wpdb;


        // xóa postmeta trước
        _eb_q( "DELETE FROM
			`" . wp_postmeta . "`
		WHERE
			post_id IN ( select
							ID
						from
							`" . wp_posts . "`
						where
							post_type = '" . $post_type . "' " . $strFilter . " )", 0 );

        // tiếp theo là term_relationships
        //echo $wpdb->term_relationships . '<br>' . "\n"; exit();
        _eb_q( "DELETE FROM
			`" . $wpdb->term_relationships . "`
		WHERE
			object_id IN ( select
							ID
						from
							`" . wp_posts . "`
						where
							post_type = '" . $post_type . "' " . $strFilter . " )", 0 );

        // sau đó xóa posts
        _eb_q( "DELETE FROM
			`" . wp_posts . "`
		WHERE
			post_type = '" . $post_type . "' " . $strFilter . " ", 0 );
    }

    //
    _eb_log_user( 'Delete post ID #' . $ID . ' --- Post type: ' . $post_type );

    return true;
}


// lệnh sử dụng dữ liệu chung với bảng post thay vì meta post của wp, phù hợp với các trang sử dụng wordpress để làm trang rao vặt
function WGR_update_meta_post( $id, $k, $v ) {

    // sử dụng phương thức mặc định của wp (sử dụng song song)
    // nếu không phải key của echbay hoặc tính năng không bật -> bỏ qua luôn
    if ( cf_set_raovat_version != 1 ||
        strstr( $k, '_eb_' ) == false ) {
        update_post_meta( $id, $k, $v );
        return true;
    }

    // một số key sẽ dùng post meta -> để tìm theo khoảng giá, lọc quảng cáo...
    $remove_in_postmeta = true;
    if ( $k == '_eb_product_price' ||
        $k == '_eb_ads_status' ) {
        update_post_meta( $id, $k, $v );
        $remove_in_postmeta = false;
    }


    // xử dụng phương thức riêng để lấy dữ liệu
    global $arr_posts_structure;

    // lấy cấu trúc của bảng post (nếu chưa có) -> bắt buộc phải có ít nhất một dữ liệu trong post
    if ( $arr_posts_structure == NULL || !array_key_exists( $k, $arr_posts_structure ) ) {
        $sql = _eb_q( "SELECT *
		FROM
			`" . wp_posts . "`
		LIMIT 0, 1" );
        if ( empty( $sql ) ) {
            return false;
        }
        //echo '<!-- SELECT new posts structure -->' . "\n";
        //echo '<!-- ' . $k . ' -->' . "\n";

        //
        $sql = ( array )$sql[ 0 ];
        //print_r( $sql );
        //echo $k . '<br>' . "\n";
        //echo $sql[ $k ] . '<br>' . "\n";

        // nếu vẫn chưa có -> thêm cột luôn
        if ( !array_key_exists( $k, $sql ) ) {
            //$add_type = 'LONGTEXT';
            $add_type = 'TEXT';

            //
            //$add_after = 'ID';
            $add_after = 'post_type';

            //
            $strsql = 'ALTER TABLE `' . wp_posts . '` ADD `' . $k . '` ' . $add_type . ' NOT NULL AFTER `' . $add_after . '`';
            //echo $strsql . '<br>' . "\n";

            // chạy lệnh thêm cột
            _eb_q( $strsql, 0 );

            // gán giá trị cho bảng, để lần tới sẽ không bị lặp lại
            $sql[ $k ] = '';

            //
            echo '<!-- ADD new posts structure (' . $k . ') -->' . "\n";
        }

        // gán cấu trúc bảng để lần sau không bị select lại
        $arr_posts_structure = $sql;
    }
    //print_r( $arr_posts_structure );

    // update dữ liệu vào bảng posts
    _eb_q( "UPDATE `" . wp_posts . "`
	SET
		`" . $k . "` = '" . $v . "'
	WHERE
		ID = " . $id, 0 );

    //
    if ( cf_remove_raovat_meta == 1 && $remove_in_postmeta == true ) {
        _eb_q( "DELETE FROM
			`" . wp_postmeta . "`
		WHERE
			post_id = " . $id . "
			AND meta_key = '" . $k . "'", 0 );

        //
        //delete_post_meta( $id, $k );
    }

    //
    return true;

}


function WGR_insert_post( $arr, $_alert = '', $wp_error = true, $using_default = 0 ) {

    // phiên bản mặc định
    if ( cf_set_raovat_version != 1 || $using_default == 1 ) {
        $post_id = wp_insert_post( $arr, $wp_error );
        //echo $post_id . '<br>';
        if ( is_wp_error( $post_id ) ) {
            //print_r( $post_id ) . '<br>';

            $errors = $post_id->get_error_messages();
            $m = '';
            foreach ( $errors as $error ) {
                echo $error . '<br>' . "\n";
                $m .= trim( $error ) . '; ';
            }

            //
            if ( $_alert != '' ) {
                _eb_alert( $_alert . ' (' . $m . ')' );
            }

            //
            return false;
        }

        //
        return $post_id;
    }


    // phiên bản nâng cao -> update post meta riêng
    $arr_meta_box = array();
    //if ( array_key_exists( 'meta_input', $arr ) ) {
    if ( isset( $arr[ 'meta_input' ] ) ) {
        $arr_meta_box = $arr[ 'meta_input' ];

        // xong xóa cái mảng kia đi
        $arr[ 'meta_input' ] = array();
        unset( $arr[ 'meta_input' ] );
    }

    // chạy lại lệnh update -> ép buộc dùng phiên bản cũ -> update luôn
    $post_id = WGR_insert_post( $arr, $_alert, $wp_error, 1 );
    if ( $post_id == false ) {
        return false;
    }

    // sau đó mới cập nhật meta
    //if ( array_key_exists( 'ID', $arr ) ) {
    if ( isset( $arr[ 'ID' ] ) ) {
        WGR_after_update_post( $arr[ 'ID' ], $arr_meta_box );
    }
    //else if ( array_key_exists( 'import_id', $arr ) ) {
    else if ( isset( $arr[ 'import_id' ] ) ) {
        WGR_after_update_post( $arr[ 'import_id' ], $arr_meta_box );
    }

    //
    return $post_id;

}


function WGR_update_post( $arr, $_alert = '', $wp_error = true, $using_default = 0 ) {

    // phiên bản mặc định
    if ( cf_set_raovat_version != 1 || $using_default == 1 ) {
        $post_id = wp_update_post( $arr, $wp_error );
        //echo $post_id . '<br>';
        if ( is_wp_error( $post_id ) ) {
            //print_r( $post_id ) . '<br>';

            $errors = $post_id->get_error_messages();
            $m = '';
            foreach ( $errors as $error ) {
                echo $error . '<br>' . "\n";
                $m .= trim( $error ) . '; ';
            }

            //
            if ( $_alert != '' ) {
                _eb_alert( $_alert . ' (' . $m . ')' );
            }

            //
            return false;
        }

        //
        return $post_id;
    }


    // phiên bản nâng cao -> update post meta riêng
    $arr_meta_box = array();
    //if ( array_key_exists( 'meta_input', $arr ) ) {
    if ( isset( $arr[ 'meta_input' ] ) ) {
        $arr_meta_box = $arr[ 'meta_input' ];

        // xong xóa cái mảng kia đi
        $arr[ 'meta_input' ] = array();
        unset( $arr[ 'meta_input' ] );
    }

    // chạy lại lệnh update -> ép buộc dùng phiên bản cũ -> update luôn
    $post_id = WGR_update_post( $arr, $_alert, $wp_error, 1 );
    if ( $post_id == false ) {
        return false;
    }

    // sau đó mới cập nhật meta
    //if ( array_key_exists( 'ID', $arr ) ) {
    if ( isset( $arr[ 'ID' ] ) ) {
        WGR_after_update_post( $arr[ 'ID' ], $arr_meta_box );
    }
    //else if ( array_key_exists( 'import_id', $arr ) ) {
    else if ( isset( $arr[ 'import_id' ] ) ) {
        WGR_after_update_post( $arr[ 'import_id' ], $arr_meta_box );
    }

    //
    return $post_id;

}


function WGR_after_update_post( $id, $arr ) {
    if ( empty( $arr ) ) {
        return false;
    }

    //
    foreach ( $arr as $k => $v ) {
        WGR_update_meta_post( $id, $k, $v );
    }

    return true;
}


/*
 * Tạo header theo tiêu chuẩn chung cho cả mobile
 * https://developers.google.com/web/updates/2014/11/Support-for-theme-color-in-Chrome-39-for-Android
 */
function WGR_show_header_favicon( $icon = '' ) {
    global $__cf_row;

    //
    if ( $icon == '' ) {
        $icon = $__cf_row[ 'cf_favicon' ];
        if ( strstr( $icon, '//' ) == false ) {
            if ( substr( $icon, 0, 1 ) == '/' ) {
                $icon = substr( $icon, 1 );
            }
            $icon = web_link . $icon;
        }
    }

    //
    return '<meta http-equiv="Cache-control" content="public">
<!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<meta name="theme-color" content="' . $__cf_row[ 'cf_default_bg' ] . '">
<meta name="msapplication-navbutton-color" content="' . $__cf_row[ 'cf_default_bg' ] . '">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="' . $__cf_row[ 'cf_default_bg' ] . '">
<!-- <link rel="icon" sizes="192x192" href="' . $icon . '"> -->
<link href="' . $icon . '" rel="shortcut icon" type="image/png" />';

}


function WGR_save_post_xml( $postid, $save_table = 'eb_backup_post_xml' ) {
    //return true;

    global $wpdb;
    global $client_ip;

    //
    $str = '';

    // Lấy toàn bộ dữ liệu của post
    $sql = _eb_q( "SELECT *
	FROM
		`" . wp_posts . "`
	WHERE
		ID = " . $postid );
    //print_r( $sql );
    if ( !empty( $sql ) ) {
        foreach ( $sql[ 0 ] as $k => $v ) {
            $str .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>' . "\n";
        }
    }


    //
    /*
	$a = get_post_taxonomies( $postid );
//print_r($a);
	foreach ( $a as $v ) {
		$post_taxonomy = get_the_terms( $postid, $v );
//print_r($post_taxonomy);
		
		if ( ! empty( $post_taxonomy ) ) {
			foreach ( $post_taxonomy as $v2 ) {
				$str .= '<category domain="' . $v . '" nicename="' . $v2->slug . '"><![CDATA[' . $v2->name . ']]></category>' . "\n";
			}
		}
	}
	*/


    //
    $str .= WGR_get_taxonomy_xml_list( $postid );
    /*
    $post_categories = wp_get_post_categories( $postid );
    //print_r( $post_categories );
    foreach ( $post_categories as $c ) {
        $cat = get_term( $c );
        //print_r( $cat );

        $str .= '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
    }
    */


    //
    $str .= WGR_get_taxonomy_xml_list( $postid, 'post_tag' );
    /*
    $arr_post_options = wp_get_object_terms( $postid, 'post_tag' );
    //print_r( $arr_post_options );

    foreach ( $arr_post_options as $c ) {
        $cat = get_term( $c, 'post_tag' );
        //print_r( $cat );

        $str .= '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
    }
    */


    //
    $str .= WGR_get_taxonomy_xml_list( $postid, 'post_options' );
    /*
    $arr_post_options = wp_get_object_terms( $postid, 'post_options' );
    //print_r( $arr_post_options );

    foreach ( $arr_post_options as $c ) {
        $cat = get_term( $c, 'post_options' );
        //print_r( $cat );

        $str .= '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
    }
    */


    //
    $str .= WGR_get_taxonomy_xml_list( $postid, 'blogs' );
    /*
    $arr_post_options = wp_get_object_terms( $postid, 'blogs' );
    //print_r( $arr_post_options );

    foreach ( $arr_post_options as $c ) {
        $cat = get_term( $c, 'blogs' );
        //print_r( $cat );

        $str .= '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
    }
    */


    //
    $str .= WGR_get_taxonomy_xml_list( $postid, 'blog_tag' );
    /*
    $arr_post_options = wp_get_object_terms( $postid, 'blog_tag' );
    //print_r( $arr_post_options );

    foreach ( $arr_post_options as $c ) {
        $cat = get_term( $c, 'blog_tag' );
        //print_r( $cat );

        $str .= '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
    }
    */


    // Lấy toàn bộ post meta của post
    $sql = _eb_q( "SELECT *
	FROM
		`" . $wpdb->postmeta . "`
	WHERE
		post_id = " . $postid );
    //print_r( $sql );
    if ( !empty( $sql ) ) {
        foreach ( $sql as $v ) {
            $str .= trim( '
<wp:postmeta>
	<wp:meta_key><![CDATA[' . $v->meta_key . ']]></wp:meta_key>
	<wp:meta_value><![CDATA[' . $v->meta_value . ']]></wp:meta_value>
</wp:postmeta>
			' ) . "\n";
        }
    }

    //
    //echo $str;

    //
    //if ( ! function_exists('get_magic_quotes_gpc') || ! get_magic_quotes_gpc () ) {
    $str = addslashes( $str );
    //}

    //
    _eb_q( "DELETE
	FROM
		`" . $save_table . "`
	WHERE
		post_id = " . $postid, 0 );

    // lưu dữ liệu
    _eb_sd( array(
        'bpx_content' => $str,
        'bpx_agent' => isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) ? $_SERVER[ 'HTTP_USER_AGENT' ] : '',
        'bpx_ip' => $client_ip,
        'bpx_time' => date_time,
        'bpx_date' => date( 'YmdH', date_time ),
        'post_id' => $postid,
        'tv_id' => mtv_id,
    ), $save_table );

    //
    //exit();

    //
    _eb_log_user( 'WGR_save_post_xml ID #' . $save_table . ' to table ' . $save_table );

    //
    return true;
}


function WGR_get_taxonomy_xml_list( $id, $taxonomy = 'category' ) {
    $arr_post_options = wp_get_object_terms( $id, $taxonomy );
    //print_r( $arr_post_options );

    //
    $str = '';
    foreach ( $arr_post_options as $c ) {
        // nếu không tồn tại dữ liệu cần thiết -> lấy mới
        if ( isset( $c->taxonomy, $c->slug, $c->name ) ) {
            $cat = $c;
        } else {
            $cat = get_term( $c, $taxonomy );
        }
        //print_r( $cat );

        if ( isset( $cat->taxonomy, $cat->slug, $cat->name ) ) {
            $str .= '<category domain="' . $cat->taxonomy . '" nicename="' . $cat->slug . '"><![CDATA[' . $cat->name . ']]></category>';
        }
    }

    //
    //echo $str . "\n";
    return $str;
}


function WGR_register_sidebar( $k, $v ) {
    register_sidebar( array(
        'name' => str_replace( '_', ' ', $k ),
        'id' => $k,
        'class' => $k,
        'description' => $v,

        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',

        //'before_title' => '<div-for-replace class="' . $k . '-title">',
        //'after_title' => '</div-for-replace>'
        'before_title' => $k . '-title',
    ) );
}


function WGR_check_discount_code_exist() {

    //
    $co_ma_giam_gia = 0;

    //
    $arr_discount_code = get_categories( array(
        'hide_empty' => 0,
        'taxonomy' => 'discount_code'
    ) );

    /*
    echo '<!-- ';
    print_r($arr_discount_code);
    echo ' -->';
    */

    //
    if ( !empty( $arr_discount_code ) ) {
        $ngay_hom_nay = date( 'Ymd', date_time );

        //
        foreach ( $arr_discount_code as $v ) {
            $check_discount_ex = _eb_get_cat_object( $v->term_id, '_eb_category_coupon_ngayhethan' );

            // nếu chưa tìm thấy mã giảm giá nào
            if ( $co_ma_giam_gia == 0
                // có ngày hết hạn
                &&
                $check_discount_ex != ''
                // độ dài ngày hết hạn chuẩn
                &&
                strlen( $check_discount_ex ) == 10 &&
                str_replace( '/', '', $check_discount_ex ) >= $ngay_hom_nay ) {
                $co_ma_giam_gia = 1;
                break;
            }
            //echo $check_discount_ex . '<br>' . "\n";
        }
    }

    //
    return $co_ma_giam_gia;

}


function WGR_check_token( $check_with = '' ) {
    if ( $check_with == '' ) {
        $check_with = $_SERVER[ 'HTTP_HOST' ];
    }

    //
    if ( !isset( $_GET[ 'token' ] ) || $_GET[ 'token' ] != _eb_mdnam( $check_with ) ) {
        die( '<h1>Permission deny!</h1>' );
    }

    return true;
}


// chuyển đường dẫn file sang link web
function WGR_path_to_lnk( $v ) {
    // test
    //return $v;

    // v2
    $v = explode( EB_DIR_CONTENT, $v );
    $v[ 0 ] = web_link;
    return implode( EB_DIR_CONTENT, $v );

    // v1
    // for window
    $a = substr( ABSPATH, 0, -1 ) . '\\';
    //echo $a . "\n";

    //
    return str_replace( ABSPATH, web_link, str_replace( $a, web_link, $v ) );
}


// bóc tách dữ liệu trong phần đơn hàng để xử lý
function WGR_decode_for_products_cart( $a ) {
    if ( $a == '' ) {
        return NULL;
    }

    //echo $a . '<br>' . "\n\n";

    //$a = json_decode( $a, JSON_UNESCAPED_SLASHES );
    //print_r( $a );

    //$a = stripcslashes($a);
    //echo $a . '<br>' . "\n\n";
    //$a = html_entity_decode($a, ENT_QUOTES, 'UTF-8');
    //echo $a . '<br>' . "\n\n";

    // cái urldecode nó lỗi tiếng Việt nên phải xử lý kiểu này
    $arr = array(
        '%28' => '(',
        '%29' => ')',
        '%3C' => '<',
        '%3E' => '>',
        '%20' => ' ',
        '%5B' => '[',
        '%5D' => ']',
        '%7B' => '{',
        '%7D' => '}',
        '%2C' => ',',
        '%22' => '"',
        '%3A' => ':'
    );
    foreach ( $arr as $k => $v ) {
        $a = str_replace( $k, $v, $a );
    }

    //$a = urldecode( $a );
    //echo $a . '<br>' . "\n\n";
    //echo urldecode($a);

    // cắt bỏ phần name đi, vì nó hay bị lỗi chữ tiếng Việt
    $a = explode( '"name":"', $a );
    //print_r( $a );
    foreach ( $a as $k => $v ) {
        if ( $k > 0 ) {
            $v = strstr( $v, '"slug":"' );
            $a[ $k ] = $v;
        }
    }
    //print_r( $a );
    $a = implode( '', $a );
    //echo $a . '<br>' . "\n\n";

    // chỉnh lại cái tên màu, do trước đó nó bị decode, nên trong php không hiển thị được
    try {
        $a = json_decode( $a );
        foreach ( $a as $k => $v ) {
            if ( isset( $v->color ) && $v->color != '' ) {
                //$v->color = urldecode($v->color);
                $v->color = preg_replace( "/%u([0-9a-f]{3,4})/i", "&#x\\1;", $v->color );
                $v->color = html_entity_decode( $v->color, ENT_QUOTES, 'UTF-8' );

                //
                $arr = _eb_arr_escape_fix_content();
                foreach ( $arr as $k2 => $v2 ) {
                    if ( $v2 != '' ) {
                        $v->color = str_replace( $v2, $k2, $v->color );
                    }
                }
            }
        }
    } catch ( Exception $e ) {
        $a = NULL;
    }

    return $a;
}

function WGR_decode_for_discount_cart( $a ) {
    if ( $a == '' ) {
        return NULL;
    }
    $re = NULL;

    //echo $str . '<br>' . "\n\n";

    //echo html_entity_decode($a) . '<br>' . "\n\n";
    $a = html_entity_decode( $a, ENT_QUOTES, 'UTF-8' );
    //echo $a . '<br>' . "\n\n";
    $a = urldecode( $a );
    //echo $a . '<br>' . "\n\n";

    // bóc tách lấy phần discount
    $a = explode( '","hd_discount_code":"', $a );
    //print_r( $a );
    if ( count( $a ) > 1 ) {
        $a = explode( '"', $a[ 1 ] );

        //
        $a = $a[ 0 ];
        if ( $a != '' ) {
            $arr_discount_code = get_categories( array(
                'name' => $a,
                'orderby' => 'id',
                'hide_empty' => 0,
                'taxonomy' => 'discount_code'
            ) );
            //print_r( $arr_discount_code );

            //
            if ( !empty( $arr_discount_code ) ) {
                //echo $arr_discount_code[0]->term_id . '<br>' . "\n";

                $arr_discount_code[ 0 ]->coupon_giagiam = _eb_get_cat_object( $arr_discount_code[ 0 ]->term_id, '_eb_category_coupon_giagiam' );
                $arr_discount_code[ 0 ]->coupon_phantramgiam = _eb_get_cat_object( $arr_discount_code[ 0 ]->term_id, '_eb_category_coupon_phantramgiam' );
                $arr_discount_code[ 0 ]->coupon_donggia = _eb_get_cat_object( $arr_discount_code[ 0 ]->term_id, '_eb_category_coupon_donggia' );

                //print_r( $arr_discount_code );

                $re = $arr_discount_code;
            }
        }

        //return $a;
    }

    return $re;
}

function WGR_download( $source, $dest ) {
    if ( function_exists( 'copy' ) ) {
        if ( copy( $source, $dest ) ) {
            return true;
        }
    }

    //
    return false;
}

// hiển thị shortcode thông qua widget shortcode
function WGR_shortcode( $key ) {
    $value = EBE_get_lang( $key );

    // không hiển thị nếu đặt lệnh là ẩn hoặc vẫn là dạng tmp
    if ( $value == 'hide' || $value == 'null' || strstr( $value, 'tmp_shortcode_____' ) == true ) {
        return '';
    }

    // còn lại sẽ gọi đến hàm shortcode đẻ hiển thị ra
    return do_shortcode( $value );
}

function WGR_echo_shortcode( $key ) {
    echo WGR_shortcode( $key );
    return '';
}

function WGR_before_optimize_code( $confirm_file ) {
    // không tồn tại file cần thiết -> hủy
    if ( !file_exists( $confirm_file ) ) {
        return false;
    }

    // kiểm tra nội dung file
    $content_file = file_get_contents( $confirm_file, 1 );
    //echo $content_file . '<br>' . "\n";
    // nội dung file này phải là 1 số
    if ( is_numeric( $content_file ) ) {
        // chỉ giải nén trong khoảng thời gian cho phép
        if ( $content_file > time() ) {
            //echo __FUNCTION__ . ' run in ' . date( 'r', $content_file ) . '<br>' . "\n";
            return false;
        }
    } else {
        // nếu là chữ -> hẹn thời gian để xử lý code
        _eb_create_file( $confirm_file, time() + 60 );
        return false;
    }
    return true;
}

function WGR_unzip_vendor_code() {
    $confirm_file = EB_THEME_PLUGIN_INDEX . 'unzipcode.txt';
    if ( WGR_before_optimize_code( $confirm_file ) === false ) {
        return false;
    }
    echo __FUNCTION__ . ' running... <br>' . "\n";

    //
    _eb_remove_file( $confirm_file );
    // không xóa được file -> bỏ luôn
    if ( file_exists( $confirm_file ) ) {
        echo '<!-- Can not remove file ' . basename( $confirm_file ) . ' -->';
        return false;
    }

    //
    $arr_vendor_list = [
        EB_THEME_URL . 'outsource',
    ];
    //print_r( $arr_vendor_list );

    //
    $zip = new ZipArchive;
    foreach ( $arr_vendor_list as $base_for_unzip ) {
        foreach ( glob( $base_for_unzip . '/*.zip' ) as $filename ) {
            //echo $filename . '<br>' . "\n";

            //
            $dir_unzip = dirname( $filename ) . '/' . basename( $filename, '.zip' );
            //echo $dir_unzip . '<br>' . "\n";

            //
            if ( !is_dir( $dir_unzip ) ) {
                echo $filename . '<br>' . "\n";

                //
                if ( $zip->open( $filename ) === TRUE ) {
                    $zip->extractTo( $base_for_unzip );
                    $zip->close();
                }

                //
                if ( is_dir( $dir_unzip ) ) {
                    echo 'DONE! sync code ' . basename( $dir_unzip ) . ' <br>' . "\n";
                } else {
                    echo 'ERROR! sync code ' . basename( $dir_unzip ) . ' <br>' . "\n";

                    // thử xử lý qua ftp
                    if ( defined( 'FTP_USER' ) && defined( 'FTP_PASS' ) ) {
                        $ftp_server = EBE_check_ftp_account();
                        if ( $ftp_server === false ) {
                            echo 'FTP account not found <br>' . "\n";
                        } else {
                            $ftp_user_name = FTP_USER;
                            $ftp_user_pass = FTP_PASS;

                            // tạo kết nối
                            echo 'connect to ftp <br>' . "\n";
                            $conn_id = ftp_connect( $ftp_server );
                            if ( !$conn_id ) {
                                echo 'ERROR FTP connect to server <br>' . "\n";
                            } else {
                                // đăng nhập
                                echo 'login to ftp <br>' . "\n";
                                if ( !ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass ) ) {
                                    echo 'ERROR FTP login to server <br>' . "\n";
                                } else {
                                    $ftp_dir_root = EBE_get_config_ftp_root_dir( date_time );

                                    //
                                    $file_for_ftp = $base_for_unzip;
                                    if ( $ftp_dir_root != '' ) {
                                        $file_for_ftp = strstr( $file_for_ftp, '/' . $ftp_dir_root . '/' );
                                    }
                                    echo $file_for_ftp . '<br>' . "\n";

                                    //
                                    if ( !ftp_chmod( $conn_id, 0777, $file_for_ftp ) ) {
                                        echo 'ERROR FTP: ftp_chmod error <br>' . "\n";

                                        //
                                        $file_for_ftp = $filename;
                                        if ( $ftp_dir_root != '' ) {
                                            $file_for_ftp = strstr( $file_for_ftp, '/' . $ftp_dir_root . '/' );
                                        }
                                        echo $file_for_ftp . '<br>' . "\n";

                                        //
                                        if ( !ftp_chmod( $conn_id, 0777, $file_for_ftp ) ) {
                                            echo 'ERROR FTP: ftp_chmod error <br>' . "\n";
                                        } else {
                                            echo 'chmod vid ftp <br>' . "\n";
                                        }
                                    } else {
                                        echo 'chmod vid ftp <br>' . "\n";
                                    }
                                }
                            }
                            ftp_close( $conn_id );
                        }
                    }
                }
            }
        }
    }
}

function WGR_optimize_static_code() {
    $confirm_file = EB_THEME_PLUGIN_INDEX . 'optimizecode.txt';
    if ( WGR_before_optimize_code( $confirm_file ) === false ) {
        return false;
    }
    echo __FUNCTION__ . ' running... <br>' . "\n";

    //
    _eb_remove_file( $confirm_file );
    // không xóa được file -> bỏ luôn
    if ( file_exists( $confirm_file ) ) {
        echo '<!-- Can not remove file ' . basename( $confirm_file ) . ' -->';
        return false;
    }

    // Nếu không có function cần thiết -> nạp vào thôi
    if ( !function_exists( 'WGR_compiler_update_echbay_css_js' ) ) {
        include_once ECHBAY_PRI_CODE . 'echbay_compiler_core.php';
    }

    // thư mục chứa file cần xử lý
    $arr_optimize_dir = [
        'css',
        'css/default',
        'css/template',

        'class/widget',

        'html/details',
        'html/details/mobilemua',
        'html/details/pcmua',
        'html/search',

        'javascript',
    ];

    foreach ( $arr_optimize_dir as $v ) {
        $v = rtrim( EB_THEME_PLUGIN_INDEX . $v, '/' );
        echo $v . '<br>' . "\n";

        //
        if ( is_dir( $v ) ) {
            foreach ( glob( $v . '/*.css' ) as $filename ) {
                //echo $filename . '<br>' . "\n";
                WGR_compiler_update_echbay_css_js( $filename );
            }

            //
            foreach ( glob( $v . '/*.js' ) as $filename ) {
                //echo $filename . '<br>' . "\n";
                WGR_compiler_update_echbay_css_js( $filename );
            }
        }
    }

    //
    $arr_optimize_dir = [
        'global',
        'global/temp',

        'class/widget',
    ];

    foreach ( $arr_optimize_dir as $v ) {
        $v = rtrim( EB_THEME_PLUGIN_INDEX . $v, '/' );
        echo $v . '<br>' . "\n";

        //
        if ( is_dir( $v ) ) {
            foreach ( glob( $v . '/*.php' ) as $filename ) {
                //echo $filename . '<br>' . "\n";
                WGR_compiler_update_echbay_css_js( $filename );
            }
        }
    }

    /*
     * for child theme
     */
    if ( defined( 'EB_CHILD_THEME_URL' ) ) {
        //echo EB_CHILD_THEME_URL . '<br>' . "\n";

        $arr_optimize_dir = [
            '',
            'ui',
            'templates',
            'shortcode',
        ];

        foreach ( $arr_optimize_dir as $v ) {
            $v = rtrim( EB_CHILD_THEME_URL . $v, '/' );
            echo $v . '<br>' . "\n";

            //
            if ( is_dir( $v ) ) {
                foreach ( glob( $v . '/*.css' ) as $filename ) {
                    //echo $filename . '<br>' . "\n";
                    WGR_compiler_update_echbay_css_js( $filename );
                }

                //
                foreach ( glob( $v . '/*.js' ) as $filename ) {
                    //echo $filename . '<br>' . "\n";
                    WGR_compiler_update_echbay_css_js( $filename );
                }
            }
        }
    }
}