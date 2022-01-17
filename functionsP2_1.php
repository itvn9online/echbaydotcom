<?php


function _eb_remove_file( $file_, $ftp = 1 ) {
    if ( file_exists( $file_ ) ) {
        if ( !unlink( $file_ ) ) {
            // thử xóa bằng ftp
            if ( $ftp == 1 ) {
                return EBE_ftp_remove_file( $file_ );
            }
        } else {
            return true;
        }
    }

    return false;
}

function _eb_create_file(
    $file_,
    $content_,
    $add_line = '',
    $ftp = 1,
    $set_permission = 0777
) {

    //
    if ( $content_ == '' ) {
        echo 'ERROR put file: content is NULL<br>' . "\n";
        return false;
    }

    //
    if ( !file_exists( $file_ ) ) {
        $filew = fopen( $file_, 'x+' );

        // nếu không tạo được file
        if ( !$filew ) {
            /*
            foreach ( debug_backtrace() as $backtrace ) {
                echo $backtrace[ 'file' ] . ':' . $backtrace[ 'line' ] . ':' . $backtrace[ 'function' ] . '<br>' . PHP_EOL;
            }
            */

            // thử tạo bằng ftp
            if ( $ftp == 1 ) {
                return EBE_ftp_create_file( $file_, $content_, $add_line );
            }

            //
            echo 'ERROR create file: ' . $file_ . '<br>' . "\n";
            return false;
        } else {
            // nhớ set 777 cho file
            chmod( $file_, $set_permission );
        }
        fclose( $filew );
    }

    //
    if ( $add_line != '' ) {
        $aa = file_put_contents( $file_, $content_, FILE_APPEND );
        //chmod($file_, 0777);
    }
    //
    else {
        //		file_put_contents( $file_, $content_, LOCK_EX ) or die('ERROR: write to file');
        $aa = file_put_contents( $file_, $content_ );
        //chmod($file_, 0777);
    }

    //
    if ( !$aa && $ftp == 1 ) {
        //		echo $file_ . '<br>' . "\n";
        if ( EBE_ftp_create_file( $file_, $content_, $add_line ) != true ) {
            echo 'ERROR write to file: ' . $file_ . '<br>' . "\n";
            return false;
        }
    }


    /*
     * add_line: thêm dòng mới
     */
    //	$content_ = str_replace('\"', '"', $content_);
    //	$content_ = str_replace("\'", "'", $content_);

    /*
	// nếu tồn tại file rồi -> sửa
	if (file_exists($file_)) {
//			if( flock( $file_, LOCK_EX ) ) {
			// open
	//		$fh = fopen($file_, 'r+') or die('ERROR: open 1');
	//		$str_data = fread($fh, filesize($file_));
			if ($add_line != '') {
				$fh = fopen($file_, 'a+') or die('ERROR: add to file');
			} else {
				$fh = fopen($file_, 'w+') or die('ERROR: write to file');
			}
//			}
	}
	// chưa tồn tại file -> tạo
	else {
		// open
		$fh = fopen($file_, 'x+') or die('ERROR: create file');
		chmod($file_, 0777);
	}
	
	// write
	fwrite($fh, $content_) or die('ERROR: write');
	// close
	fclose($fh) or die('ERROR: close');
	*/

    //
    //	echo '<!-- ' . $file_ . ' -->' . "\n";

    //
    return true;
}

function WGR_copy( $source, $path, $ftp = 1, $ch_mod = 0644 ) {
    if ( copy( $source, $path ) ) {
        chmod( $path, $ch_mod )or die( 'ERROR chmod WGR_copy: ' . $path );
        return true;
    }

    // Không thì tạo thông qua FTP
    if ( $ftp == 1 ) {
        return WGR_ftp_copy( $source, $path );
    }

    return false;
}

function WGR_ftp_copy( $source, $path ) {

    //
    $ftp_server = EBE_check_ftp_account();
    if ( $ftp_server == false ) {
        echo 'FTP account not found';
        return false;
    }
    $ftp_user_name = FTP_USER;
    $ftp_user_pass = FTP_PASS;


    // tạo kết nối
    $conn_id = ftp_connect( $ftp_server );
    if ( !$conn_id ) {
        echo 'ERROR FTP connect to server<br>' . "\n";
        return false;
    }


    // đăng nhập
    if ( !ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass ) ) {
        echo 'ERROR FTP login false<br>' . "\n";
        return false;
    }


    //
    $ftp_dir_root = EBE_get_config_ftp_root_dir( date_time );

    //
    $file_for_ftp = $path;
    //	echo $file_for_ftp . '<br>';
    if ( $ftp_dir_root != '' ) {
        //		echo $ftp_dir_root . '<br>';

        // nếu trong chuỗi file không có root dir -> báo lỗi
        if ( strpos( $file_for_ftp, '/' . $ftp_dir_root . '/' ) === false ) {
            echo 'ERROR FTP root dir not found #' . $ftp_dir_root . '<br>' . "\n";
            return false;
        }

        $file_for_ftp = strstr( $file_for_ftp, '/' . $ftp_dir_root . '/' );
        //		echo $file_for_ftp . '<br>';
    }


    // copy qua FTP_BINARY thì mới copy ảnh chuẩn được
    if ( ftp_put( $conn_id, $file_for_ftp, $source, FTP_BINARY )or die( 'ERROR copy file via FTP #' . $path ) ) {
        return true;
    }


    //
    return false;

}

function EBE_create_dir( $path, $ftp = 1, $mod = 0755 ) {
    if ( is_dir( $path ) ) {
        return true;
    }

    //
    if ( mkdir( $path, $mod ) ) {
        // server window ko cần chmod
        chmod( $path, $mod )or die( 'ERROR chmod dir: ' . $path );

        return true;
    }

    // Không thì tạo thông qua FTP
    if ( $ftp == 1 ) {
        return WGR_ftp_create_dir( $path, $mod );
    }

    return false;
}

function WGR_ftp_create_dir( $path, $mod = 0755 ) {
    if ( is_dir( $path ) ) {
        return true;
    }

    $ftp_dir_root = EBE_get_config_ftp_root_dir( date_time );

    $ftp_server = EBE_check_ftp_account();
    if ( $ftp_server == false ) {
        echo 'FTP account not found';
        return false;
    }
    $ftp_user_name = FTP_USER;
    $ftp_user_pass = FTP_PASS;


    // tạo kết nối
    $conn_id = ftp_connect( $ftp_server );
    if ( !$conn_id ) {
        echo 'ERROR FTP connect to server<br>' . "\n";
        return false;
    }


    // đăng nhập
    if ( !ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass ) ) {
        echo 'ERROR FTP login false<br>' . "\n";
        return false;
    }


    //
    $file_for_ftp = $path;
    if ( $ftp_dir_root != '' ) {
        $file_for_ftp = strstr( $file_for_ftp, '/' . $ftp_dir_root . '/' );
    }
    //	echo $file_for_ftp . '<br>';
    //	echo EBE_create_cache_for_ftp() . '<br>';

    // upload file
    $result = true;
    if ( !ftp_mkdir( $conn_id, $file_for_ftp ) ) {
        echo 'ERROR FTP: ftp_mkdir error<br>' . "\n";
        $result = false;
    } else if ( !ftp_chmod( $conn_id, $mod, $file_for_ftp ) ) {
        echo 'ERROR FTP: ftp_chmod error<br>' . "\n";
    }


    // close the connection
    ftp_close( $conn_id );


    //
    return $result;

}

function EBE_create_cache_for_ftp() {
    return EB_THEME_CACHE . 'cache_for_ftp.txt';
}

function EBE_check_ftp_account() {

    if ( !defined( 'FTP_USER' ) || !defined( 'FTP_PASS' ) ) {
        echo 'ERROR FTP: FTP_USER or FTP_PASS not found<br>' . "\n";
        return false;
    }

    if ( defined( 'FTP_HOST' ) ) {
        $ftp_server = FTP_HOST;
    } else {
        //		$ftp_server = $_SERVER['HTTP_HOST'];
        $ftp_server = $_SERVER[ 'SERVER_ADDR' ];
    }
    //	echo $ftp_server . '<br>' . "\n";

    return $ftp_server;
}

function EBE_get_config_ftp_root_dir( $content_ = '1' ) {
    global $__cf_row;

    // Nếu chưa có thư mục root cho FTP -> bắt đầu dò tìm
    if ( $__cf_row[ 'cf_ftp_root_dir' ] == '' ) {
        $__cf_row[ 'cf_ftp_root_dir' ] = EBE_get_ftp_root_dir( $content_ );
    }
    // Tạo file cache để truyền dữ liệu
    else {
        _eb_create_file( EBE_create_cache_for_ftp(), $content_, '', 0 );
    }

    return $__cf_row[ 'cf_ftp_root_dir' ];
}

function EBE_get_ftp_root_dir( $content_ = 'test' ) {

    $ftp_server = EBE_check_ftp_account();
    if ( $ftp_server == false ) {
        echo 'FTP account not found';
        return '';
    }
    $ftp_user_name = FTP_USER;
    $ftp_user_pass = FTP_PASS;
    //	echo $ftp_user_name . '<br>';
    //	echo $ftp_user_pass . '<br>';


    // tạo kết nối
    $conn_id = ftp_connect( $ftp_server );
    if ( !$conn_id ) {
        echo 'ERROR FTP connect to server<br>' . "\n";
        return '';
    }


    // đăng nhập
    if ( !ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass ) ) {
        echo 'ERROR FTP login false<br>' . "\n";
        return '';
    }


    // tạo file trong cache
    $cache_for_ftp = EBE_create_cache_for_ftp();

    // Tạo một file bằng hàm của PHP thường -> không dùng FTP
    if ( _eb_create_file( $cache_for_ftp, $content_, '', 0 ) != true ) {
        return '';
    }


    // lấy thư mục gốc của tài khoản FTP
    $a = explode( '/', $cache_for_ftp );
    $ftp_dir_root = '';
    //	print_r( $a );
    foreach ( $a as $v ) {
        //		echo $v . "\n";
        if ( $ftp_dir_root == '' && $v != '' ) {
            $file_test = strstr( $cache_for_ftp, '/' . $v . '/' );
            //			echo $file_test . " - \n";

            //
            if ( $file_test != '' ) {
                if ( ftp_nlist( $conn_id, '.' . $file_test ) != false ) {
                    $ftp_dir_root = $v;
                    break;
                }
            }
        }
    }
    //	echo $ftp_dir_root . '<br>' . "\n";

    //
    ftp_close( $conn_id );

    //
    if ( $ftp_dir_root == '' ) {
        echo 'ERROR FTP: ftp_dir_root not found<br>' . "\n";
    } else {
        //		echo ABSPATH . '<br>';
        //		echo basename( ABSPATH ) . '<br>';

        // kiểm tra xem thư mục -> nếu khác root -> lấy theme
        if ( basename( ABSPATH ) != $ftp_dir_root ) {
            // lấy từ vị trì root trở đi
            //			$ftp_dir_root .= '/' . basename( ABSPATH );
            $ftp_dir_root = strstr( ABSPATH, $ftp_dir_root );

            // bỏ dấu chéo ở cuối
            //			echo substr( $ftp_dir_root, -1 ) . '<br>';
            if ( substr( $ftp_dir_root, -1 ) ) {
                $ftp_dir_root = substr( $ftp_dir_root, 0, -1 );
            }
        }
    }

    return $ftp_dir_root;
}

// Tạo file thông qua tài khoản FTP
function EBE_ftp_create_file( $file_, $content_, $add_line = '', $mod = 0777 ) {

    //
    if ( $content_ == '' ) {
        echo 'ERROR FTP: content is NULL<br>' . "\n";
        return false;
    }

    //
    $ftp_dir_root = EBE_get_config_ftp_root_dir( $content_ );


    if ( !file_exists( $file_ ) && !is_dir( dirname( $file_ ) ) ) {
        echo 'ERROR FTP: dir not found<br>' . "\n";
        return false;
    }

    $ftp_server = EBE_check_ftp_account();
    if ( $ftp_server == false ) {
        echo 'FTP account not found';
        return false;
    }
    $ftp_user_name = FTP_USER;
    $ftp_user_pass = FTP_PASS;


    // tạo kết nối
    $conn_id = ftp_connect( $ftp_server );
    if ( !$conn_id ) {
        echo 'ERROR FTP connect to server<br>' . "\n";
        return false;
    }


    // đăng nhập
    if ( !ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass ) ) {
        echo 'ERROR FTP login false<br>' . "\n";
        return false;
    }


    //
    $file_for_ftp = $file_;
    //	echo $file_for_ftp . '<br>';
    if ( $ftp_dir_root != '' ) {
        //		echo $ftp_dir_root . '<br>';

        // nếu trong chuỗi file không có root dir -> báo lỗi
        if ( strpos( $file_, '/' . $ftp_dir_root . '/' ) === false ) {
            echo 'ERROR FTP root dir not found #' . $ftp_dir_root . '<br>' . "\n";
            return false;
        }

        $file_for_ftp = strstr( $file_, '/' . $ftp_dir_root . '/' );
        //		echo $file_for_ftp . '<br>';
    }
    //	echo EBE_create_cache_for_ftp() . '<br>';

    // upload file
    $result = true;
    if ( !ftp_put( $conn_id, '.' . $file_for_ftp, EBE_create_cache_for_ftp(), FTP_BINARY ) ) {
        echo 'ERROR FTP: ftp_put error<br>' . "\n";
        $result = false;
    }
    // chmod file sau khi tạo
    //	else if ( ! ftp_chmod($conn_id, 0644, $file_for_ftp) ) {
    //	}


    // close the connection
    ftp_close( $conn_id );

    //
    //	echo '<!-- ' . $file_ . ' (FTP) -->' . "\n";

    //
    return $result;

}

// Xóa file thông qua tài khoản FTP
function EBE_ftp_remove_file( $file_ ) {

    $ftp_dir_root = EBE_get_config_ftp_root_dir();


    $ftp_server = EBE_check_ftp_account();
    if ( $ftp_server == false ) {
        echo 'FTP account not found';
        return false;
    }
    $ftp_user_name = FTP_USER;
    $ftp_user_pass = FTP_PASS;


    // tạo kết nối
    $conn_id = ftp_connect( $ftp_server );
    if ( !$conn_id ) {
        echo 'ERROR FTP connect to server<br>' . "\n";
        return false;
    }


    // đăng nhập
    if ( !ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass ) ) {
        echo 'ERROR FTP login false<br>' . "\n";
        return false;
    }


    //
    $file_for_ftp = $file_;
    if ( $ftp_dir_root != '' ) {
        $file_for_ftp = strstr( $file_, '/' . $ftp_dir_root . '/' );
    }

    // upload file
    $result = true;
    if ( !ftp_delete( $conn_id, $file_for_ftp ) ) {
        echo 'ERROR FTP: ftp_delete error<br>' . "\n";
        $result = false;
    }


    // close the connection
    ftp_close( $conn_id );


    //
    return $result;

}


/*
function _eb_setCucki ( $c_name, $c_value = 0, $c_time = 0, $c_path = '/' ) {
}
*/

function _eb_getCucki( $c_name, $default_value = '' ) {
    if ( isset( $_COOKIE[ $c_name ] ) ) {
        //	if ( isset($_COOKIE[ $c_name ]) && $_COOKIE[ $c_name ] != '' ) {
        return $_COOKIE[ $c_name ];
    }
    return $default_value;
}


function _eb_alert( $m, $redirect = '' ) {
    return _eb_html_alert( $m, $redirect );

    //
    die( '<script type="text/javascript">alert("' . str_replace( '"', '\'', $m ) . '");</script>' );
}

function _eb_html_alert( $m, $redirect = '' ) {
    die( '<script type="text/javascript">
(function () {
	try {
		if ( top != self && typeof parent.WGR_html_alert == "function" ) {
			parent.WGR_html_alert("' . $m . '", "' . $redirect . '");
		}
		else if ( typeof WGR_html_alert == "function" ) {
			WGR_html_alert("' . $m . '", "' . $redirect . '");
		}
		else {
			alert("' . str_replace( '"', '\'', $m ) . '");
		}
	} catch ( e ) {
		console.log( e );
		alert("' . str_replace( '"', '\'', $m ) . '");
	}
})();
</script>' );

    //
    return false;
}


function EBE_get_file_in_folder( $dir, $file_type = '', $type = '', $get_basename = false ) {
    /*
     * chuẩn hóa đầu vào
     */
    // bỏ dấu * nếu có
    /*
	if ( substr( $dir, -1 ) == '*' ) {
		$dir = substr( $dir, 0, -1 );
	}
    */
    $dir = rtrim( $dir, '*' );
    /*
	if ( substr( $file_type, 0, 1 ) == '*' ) {
		$file_type = substr( $file_type, 1 );
	}
    */
    $file_type = ltrim( $file_type, '*' );
    // thêm dấu / nếu chưa có
    $dir = rtrim( $dir, '/' ) . '/';
    /*
	if ( substr( $dir, -1 ) != '/' ) {
		$dir .= '/';
	}
    */

    // lấy danh sách file
    if ( $file_type != '' ) {
        $arr = glob( $dir . '*' . $file_type, GLOB_BRACE );
    } else {
        $arr = glob( $dir . '*' );
    }

    // chỉ lấy file
    if ( $type == 'file' ) {
        $arr = array_filter( $arr, 'is_file' );
    }
    // chỉ lấy thư mục
    else if ( $type == 'dir' ) {
        $arr = array_filter( $arr, 'is_dir' );
    }

    //	print_r($arr);
    //	exit();

    // chỉ lấy mỗi tên file hoặc thư mục
    if ( $get_basename == true ) {
        foreach ( $arr as $k => $v ) {
            $arr[ $k ] = basename( $v );
        }
    }

    return $arr;
}

function _eb_remove_ebcache_content( $dir = EB_THEME_CACHE, $remove_dir = 0, $remove_noclean = false ) {
    //	echo $dir . '<br>'; exit();

    // nếu ký tự cuối là dấu / -> bỏ đi
    if ( substr( $dir, -1 ) == '/' ) {
        $dir = substr( $dir, 0, -1 );
    }
    //	echo $dir . '<br>';

    //	exit();

    // không xóa cache trong 1 số thư mục
    if ( $remove_noclean == false && basename( $dir ) == 'noclean' ) {
        return false;
    }

    // lấy d.sách file và thư mục trong thư mục cần xóa
    //	$arr = glob ( $dir . '/*' );
    $arr = EBE_get_file_in_folder( $dir . '/' );
    //	print_r( $arr ); exit();


    /*
     * v2
     */
    foreach ( $arr as $v ) {
        //		echo $v . '<br>' . "\n";

        // nếu là thư mục -> xóa nội dung trong thư mục
        if ( is_dir( $v ) ) {
            // gọi lệnh xóa tiếp các file trong thư mục -> đến hết mới thôi
            _eb_remove_ebcache_content( $v, $remove_dir, $remove_noclean );
        } else if ( is_file( $v ) ) {
            @unlink( $v );
        }
    }

    //
    return true;


    /*
     * v1
     */
    // lọc lấy file
    $_file = array_filter( $arr, 'is_file' );
    // và xóa
    array_map( 'unlink', $_file );

    // lọc lấy thư mục
    $_dir = array_filter( $arr, 'is_dir' );
    foreach ( $_dir as $v ) {
        // gọi lệnh xóa tiếp đến hết mới thôi
        _eb_remove_ebcache_content( $v );

        //
        //		if ($remove_dir == 1) {
        //			rmdir ( $v );
        //			echo $v . "\n";
        //		}
    }
}


function _eb_create_account_auto( $arr = array() ) {
    if ( count( $arr ) == 0 ) {
        return 0;
    }
    global $arr_private_info_setting;


    //
    $user_email = _eb_non_mark( strtolower( $arr[ 'tv_email' ] ) );

    // tìm theo email
    $user_id = email_exists( $user_email );

    // có thì trả về luôn
    if ( $user_id > 0 ) {
        return $user_id;
    }


    // tạo username từ email
    if ( !isset( $arr[ 'user_name' ] ) || trim( $arr[ 'user_name' ] ) == '' ) {
        $user_name = str_replace( '.', '_', str_replace( '@', '', $user_email ) );
    } else {
        $user_name = strtolower( $arr[ 'user_name' ] );
    }
    $user_name = str_replace( '-', '', str_replace( '.', '', _eb_text_only( trim( $user_name ) ) ) );

    // Kiểm tra user có chưa
    $user_id = username_exists( $user_name );
    //		echo $user_id; exit();

    // có thì trả về luôn
    if ( $user_id > 0 ) {
        return $user_id;
    }


    // chưa có -> tạo mới ->  mật khẩu mặc định ;))
    return wp_create_user( $user_name, $arr_private_info_setting[ 'site_upper' ], $user_email );
}

/*
 * Tự động tạo trang nếu chưa có
 */
function WGR_create_page( $page_url, $page_name = '', $other_option = array() ) {
    global $wpdb;

    //
    if ( $page_url == '' ) {
        die( 'Please set value for page_url' );
    }
    if ( $page_name == '' ) {
        // thử lấy trong bảng lang xem có không
        $page_name = EBE_get_lang( $page_url );

        // không có thì lấy luôn page_url làm tên
        if ( $page_name == '' ) {
            $page_name = $page_url;
            //			die('Please set value for page_name');
        }
    }

    $sql = _eb_q( "SELECT *
	FROM
		`" . wp_posts . "`
	WHERE
		post_name = '" . $page_url . "'" );
    //	print_r( $sql );

    // nếu chưa có thì tạo mới
    if ( empty( $sql ) ) {
        $page = array(
            'post_title' => $page_name,
            'post_type' => 'page',
            //			'post_content' => 'Vui lòng không xóa hoặc thay đổi bất kỳ điều gì trong trang này.',
            //			'post_content' => 'Đây là trang dùng để chủ động nhập nội dung cho các bộ thẻ META ở phía dưới. Hãy điều chỉnh nó cho phù hợp!',
            'post_content' => '',
            'post_excerpt' => 'Đây là trang tĩnh, dùng để chủ động thêm nội dung vào các trang đã được fixed cứng bởi code. Bạn có thể thêm nội dung tùy chỉnh vào ô Nội dung ở trên và bổ sung các bộ thẻ META nếu thấy cần thiết.',
            'post_parent' => 0,
            'post_author' => mtv_id,
            'post_status' => 'publish',
            'post_name' => $page_url,
        );
        $pageid = WGR_insert_post( $page );
        //		echo $pageid; exit();

        //
        if ( !is_wp_error( $pageid ) ) {
            // thêm page template luôn
            if ( isset( $other_option[ 'page_template' ] ) && $other_option[ 'page_template' ] != '' ) {
                update_post_meta( $pageid, '_wp_page_template', $other_option[ 'page_template' ] );
            }

            // nạp lại trang
            if ( isset( $other_option[ 'reload' ] ) && $other_option[ 'page_template' ] == 1 ) {
                return wp_redirect( _eb_p_link( $pageid ) );
            }

            // hoặc trả về page vừa được insert
            return WGR_create_page( $page_url, $page_name );
        }
        //there was an error in the post insertion, 
        else {
            echo $pageid->get_error_message();
            exit();
        }
    }

    //
    return $sql[ 0 ];
}

function _eb_create_page( $page_url, $page_name, $page_template = '' ) {
    global $wpdb;

    $name = $wpdb->get_var( "SELECT ID
	FROM
		`" . wp_posts . "`
	WHERE
		post_name = '" . $page_url . "'" );

    if ( $name == '' ) {
        $page = array(
            'post_title' => $page_name,
            'post_type' => 'page',
            //			'post_content' => 'Vui lòng không xóa hoặc thay đổi bất kỳ điều gì trong trang này.',
            'post_content' => 'Đây là trang dùng để chủ động nhập nội dung cho các bộ thẻ META ở phía dưới. Hãy điều chỉnh nó cho phù hợp!',
            'post_parent' => 0,
            'post_author' => mtv_id,
            'post_status' => 'publish',
            'post_name' => $page_url,
        );

        // tạo page mới
        //		$page = apply_filters('yourplugin_add_new_page', $page, 'teams');
        $pageid = WGR_insert_post( $page );


        /*
         * add template tương ứng
         */
        /*
		if ( $page_template == '' ) {
//			$page_template = 'templates/' . $page_url . '.php';
			$page_template = 'templates/index.php';
		}
		
		WGR_update_meta_post( $pageid, '_wp_page_template', $page_template, true );
		*/
    }
}


function _eb_create_breadcrumb( $url, $tit, $id = 0, $rel = '', $to_first = false ) {
    global $breadcrumb_position;
    global $group_go_to;

    //
    if ( $rel != '' ) {
        $rel = ' rel="' . $rel . '"';
    }

    // thêm bổ sung vào đầu mảng
    if ( $to_first == true ) {
        $p = 2;

        //
        $group_go_to = array_merge( array(
            $url => ' <li><a data-id="' . $id . '" href="' . $url . '"' . $rel . '>' . $tit . '</a></li>'
        ), $group_go_to );
    }
    // mặc định là cuối mảng
    else {
        $group_go_to[ $url ] = ' <li><a data-id="' . $id . '" href="' . $url . '"' . $rel . '>' . $tit . '</a></li>';

        //
        //	echo $breadcrumb_position . "\n";

        $breadcrumb_position++;
        $p = $breadcrumb_position;
    }

    //
    return '
	, {
		"@type": "ListItem",
		"position": ' . $p . ',
		"item": {
			"@id": "' . str_replace( '/', '\/', $url ) . '",
			"name": "' . str_replace( '"', '&quot;', $tit ) . '"
		}
	}';
}

function _eb_create_html_breadcrumb( $c ) {
    //	global $group_go_to;
    global $schema_BreadcrumbList;
    global $css_m_css;

    //
    //	print_r( $c );

    //
    $return_id = $c->term_id;

    //
    $cat_custom_css = _eb_get_cat_object( $c->term_id, '_eb_category_custom_css' );
    //	echo $cat_custom_css . '<br>' . "\n";
    if ( $cat_custom_css != '' ) {
        $css_m_css[] = $cat_custom_css;
    }
    $css_m_css[] = 'ebcat-' . $c->slug;

    //
    if ( $c->parent > 0 ) {

        //
        $return_id = $c->parent;

        //
        $cat_custom_css = _eb_get_cat_object( $c->parent, '_eb_category_custom_css' );
        //		echo $cat_custom_css . '<br>' . "\n";
        if ( $cat_custom_css != '' ) {
            $css_m_css[] = $cat_custom_css;
        }

        //
        //		$parent_cat = get_term_by( 'id', $c->parent, $c->taxonomy );
        $parent_cat = get_term( $c->parent, $c->taxonomy );
        //		print_r( $parent_cat );
        $css_m_css[] = 'ebcat-' . $parent_cat->slug;

        //
        if ( _eb_get_cat_object( $parent_cat->term_id, '_eb_category_hidden', 0 ) != 1 ) {
            $lnk = _eb_cs_link( $parent_cat );
            //			$group_go_to[$lnk] = ' <li><a data-id="' . $parent_cat->term_id . '" href="' . $lnk . '">' . $parent_cat->name . '</a></li>';
            $schema_BreadcrumbList[ $lnk ] = _eb_create_breadcrumb( $lnk, $parent_cat->name, $parent_cat->term_id );
        }

        // tìm tiếp nhóm cha khác nếu có
        if ( $parent_cat->parent > 0 ) {
            $return_id = _eb_create_html_breadcrumb( $parent_cat );
        }
    }

    return $return_id;
}

function _eb_echbay_category_menu( $id, $tax = 'category' ) {
    $str = '';

    $strCacheFilter = 'eb_cat_menu' . $id;
    //	echo $strCacheFilter;

    $str = _eb_get_static_html( $strCacheFilter );

    if ( $str == false ) {

        // parent
        //		$parent_cat = get_term_by( 'id', $id, $tax );
        $parent_cat = get_term( $id, $tax );
        //		print_r( $parent_cat );

        // sub
        $sub_cat = get_categories( array(
            //			'hide_empty' => 0,
            'parent' => $parent_cat->term_id
            //			'child_of' => $parent_cat->term_id
        ) );
        //		print_r( $sub_cat );

        foreach ( $sub_cat as $k => $v ) {
            $str .= '<li><a href="' . _eb_cs_link( $v ) . '">' . $v->name . '</a></li>';
        }

        if ( $str != '' ) {
            $str = '<ul class="sub-menu">' . $str . '</ul>';
        }

        // tổng hợp
        $str = '<ul><li><a href="' . _eb_cs_link( $parent_cat ) . '">' . $parent_cat->name . '</a>' . $str . '</li></ul>';

        //
        _eb_get_static_html( $strCacheFilter, $str );

    }

    //
    return $str;
}

function _eb_get_youtube_img( $id, $size = 'hqdefault' ) {
    /*
     * size: maxresdefault
     */
    return 'http://i3.ytimg.com/vi/' . $id . '/' . $size . '.jpg';
}

function _eb_get_youtube_id( $url ) {
    if ( $url == '' ) {
        return '';
    }

    //
    parse_str( parse_url( $url, PHP_URL_QUERY ), $a );

    if ( isset( $a[ 'v' ] ) ) {
        return $a[ 'v' ];
    } else {
        $a = explode( '/embed/', $url );
        if ( isset( $a[ 1 ] ) ) {
            $a = explode( '?', $a[ 1 ] );
            $a = explode( '&', $a[ 0 ] );

            return $a[ 0 ];
        }

        $a = explode( '/youtu.be/', $url );
        if ( isset( $a[ 1 ] ) ) {
            $a = explode( '?', $a[ 1 ] );
            $a = explode( '&', $a[ 0 ] );

            return $a[ 0 ];
        }
    }

    return '';
}

// tiêu đề tiêu chuẩn của google < 70 ký tự
function _eb_tieu_de_chuan_seo( $str ) {
    global $__cf_row;
    global $arr_active_for_404_page;
    global $arr_private_info_setting;
    global $act;

    // nếu sử dụng module SEO của EchBay
    if ( cf_on_off_echbay_seo == 1 ) {
        $str = trim( $str );

        // hoặc tự bổ sung nếu có dữ liệu đầu vào
        /*
        if ( $__cf_row[ 'cf_abstract' ] == '' ) {
            $__cf_row[ 'cf_abstract' ] = $__cf_row[ 'cf_title' ];
        }
        */
        if ( strlen( $str ) < 35 && $__cf_row[ 'cf_abstract' ] != '' ) {
            $str .= ' - ' . $__cf_row[ 'cf_abstract' ];

            //
            if ( strlen( $str ) > 70 ) {
                $str = _eb_short_string( $str, 70 );
            }
        }
        echo '<!-- title by ' . $arr_private_info_setting[ 'author' ] . ' -->' . "\n";
    }
    // page 404 của WGR
    else if ( isset( $arr_active_for_404_page[ $act ] ) ) {
        $str = $__cf_row[ 'cf_title' ];
    }
    //
    else if ( is_404() ) {
        $str = '404 not found!';
    }
    // mặc định thì lấy theo mẫu của wordpress
    else {
        // chỉ lấy mỗi title cho phần trang chủ
        if ( is_home() || is_front_page() ) {
            $str = web_name;
        }
        // còn lại thì không can thiệp
        else {
            //$str = wp_title( '|', false, 'right' );
            $str = wp_title( '', false, 'right' );
        }
        //$str = wp_title( '', false );
        echo '<!-- title by other plugin -->' . "\n";
    }

    //
    echo '<title>' . str_replace( '"', '&quot;', $str ) . '</title>' . "\n";
    //	return $str;
}

function _eb_short_string( $str, $len, $more = 1 ) {
    $str = trim( $str );

    if ( $len > 0 && strlen( $str ) > $len ) {
        $str = substr( $str, 0, $len );
        if ( !substr_count( $str, " " ) ) {
            if ( $more == 1 ) {
                $str .= "...";
            }
            return $str;
        }
        while ( strlen( $str ) && ( $str[ strlen( $str ) - 1 ] != " " ) ) {
            $str = substr( $str, 0, -1 );
        }
        $str = substr( $str, 0, -1 );
        if ( $more == 1 ) {
            $str .= "...";
        }
    }

    return $str;
}

function _eb_del_line( $str, $re = "", $pe = "/\r\n|\n\r|\n|\t/i" ) {
    // v3
    /*
    $str = explode( "\n", $str );
    foreach ( $str as $k => $v ) {
    	$v = trim( $v );
    	
    	if ( $v == '' ) {
    		unset( $str[$k] );
    	}
    }
    return implode( $re, $str );
    */

    // v2
    $str = explode( "\n", trim( $str ) );
    $s = '';

    // nếu có dữ liệu thay thế
    if ( $re != '' ) {
        foreach ( $str as $k => $v ) {
            $v = trim( $v );

            if ( $v != '' ) {
                $s .= $v . "\n";
            }
        }
        $s = explode( "\n", trim( $s ) );

        //
        return trim( implode( $re, $s ) );
    }

    // nếu không có thì chỉ nối chuỗi thôi
    foreach ( $str as $k => $v ) {
        $s .= trim( $v );
    }

    //
    return $s;


    // v1
    return preg_replace( $pe, $re, trim( $str ) );
}

function _eb_supper_del_line( $str, $add_line = '' ) {
    global $__cf_row;

    //
    $a = explode( "\n", $str );
    $str = '';
    foreach ( $a as $v ) {
        $v = trim( $v );
        if ( $v != '' ) {
            $str .= $v . $add_line;
        }
    }

    // chuyển URL sang dạng tương đối
    $str = str_replace( web_link . EB_DIR_CONTENT . '/', EB_DIR_CONTENT . '/', $str );

    //
    if ( $__cf_row[ 'cf_replace_content' ] != '' ) {
        $str = WGR_replace_for_all_content( $__cf_row[ 'cf_replace_content' ], $str );
    }

    //
    return $str;
}