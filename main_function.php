<?php


/*
 * CONFIG
 */
// thời gian lưu cache
$set_time_for_main_cache = 600;
//$set_time_for_main_cache = 60;

// thời gian cache
//$set_time_for_main_cache = $__cf_row['cf_reset_cache'];

// thử xem cache x2 đối với đoạn này xem có ok không
//$set_time_for_main_cache = $__cf_row['cf_reset_cache'] * 2;

// set tĩnh thời gian cache
$set_time_for_main_cache = $set_time_for_main_cache - rand( 0, $set_time_for_main_cache / 2 );

// thông điệp về ebcache
$why_ebcache_not_active = '';


//
function ___eb_cache_getUrl( $cache_dir = 'all' ) {
    if ( isset( $_SERVER[ 'REQUEST_URI' ] ) ) {
        $url = $_SERVER[ 'REQUEST_URI' ];
    } else {
        $url = $_SERVER[ 'SCRIPT_NAME' ];
        $url .= ( !empty( $_SERVER[ 'QUERY_STRING' ] ) ) ? '?' . $_SERVER[ 'QUERY_STRING' ] : '';
    }
    if ( $url == '/' || $url == '' ) {
        $url = '-';
    } else {
        $arr_cat_social_parameter = array(
            'fbclid=',
            'gclid=',
            'fb_comment_id=',
            'utm_'
        );
        foreach ( $arr_cat_social_parameter as $v ) {
            $url = explode( '?' . $v, $url );
            $url = explode( '&' . $v, $url[ 0 ] );
            $url = $url[ 0 ];
        }
        /*
        $url = explode( '?gclid=', $url );
        $url = explode( '&gclid', $url[0] );
        $url = explode( '?utm_', $url[0] );
        $url = explode( '&utm_', $url[0] );
        $url = explode( '?fb_comment_id=', $url[0] );
        $url = explode( '&fb_comment_id', $url[0] );
        $url = $url[0];
        */

        //
        if ( strlen( $url ) > 200 ) {
            $url = md5( $url );
        } else {
            $url = preg_replace( "/\/|\?|\&|\,|\=/", '-', $url );
        }
    }

    //
    $url = EB_THEME_CACHE . $cache_dir . '/' . $url . '.txt';

    //
    return $url;
}

// rút gọn HTML
function WGR_rut_gon_HTML_truoc_khi_tao_cache( $data, $filename = '' ) {

    //
    //	return $data;

    //
    $data = WGR_remove_js_multi_comment( $data, '<!--', '-->' );

    //
    $a = explode( "\n", $data );
    $data = '';
    $i = 0;
    $create_file = 1;

    foreach ( $a as $v ) {
        $v = trim( $v );

        if ( $v != '' ) {

            //			echo substr( $v, -3 ) . "\n";

            // xóa HTML comment
            // https://stackoverflow.com/questions/1084741/regexp-to-strip-html-comments
            /*
			if ( substr( $v, 0, 4 ) == '<!--' && substr( $v, -3 ) == '-->' ) {
//				$v = trim( preg_replace('/<!--(.*)-->/Uis', '', $v) );
//				$v = trim( preg_replace('s/<!--[^>]*-->//g', '', $v) );
				$v = WGR_remove_html_comments ( $v );
			}
			*/

            // nội dung hợp lệ
            //			if ( $v != '' ) {

            //				if ( strstr( $v, '//' ) == true ) {
            $v .= "\n";
            /*
				}
				else {
					$v .= ' ';
				}
				*/

            // v1
            $data .= $v;


            // v2 -> vài vòng lặp sẽ add nội dung 1 lần để tránh biến to quá hoặc hàm file_put_contents bị gọi nhiều quá
            if ( $i % 55 == 0 ) {
                // lần đầu tiên thì add nội dung, để nó reset lại file từ đầu
                if ( $create_file == 1 ) {
                    file_put_contents( $filename, $data )or die( 'ERROR: add main cache file' );
                    $create_file = 0;
                }
                // sau đó là append
                else {
                    file_put_contents( $filename, $data, FILE_APPEND )or die( 'ERROR: append main cache file' );
                }
                $data = '';
                $i = 0;
            }
            $i++;

            //			}
        }
    }
    // v2 -> nhúng nội dung còn thiếu ở những vòng lặp cuối
    if ( $data != '' ) {
        file_put_contents( $filename, $data, FILE_APPEND )or die( 'ERROR: append last main cache file' );
    }

    return true;


    // v1
    // xóa một số khoảng trắng không cần thiết -> tiết kiệm từng kb =))
    for ( $i = 0; $i < 10; $i++ ) {
        $data = str_replace( '</div> <div', '</div><div', $data );
        $data = str_replace( '</div> </div>', '</div></div>', $data );

        $data = str_replace( '/> </div>', '/></div>', $data );
        $data = str_replace( '/> <div', '/><div', $data );
    }

    return $data;

}

// page's content is $buffer ($data)
function ___eb_cache_cache( $filename, $data, $data_comment = '' ) {
    //echo $filename . '<br>' . "\n";

    // v2
    // nhúng comment vào trước
    //	file_put_contents( $filename, $data_comment ) or die('ERROR: write comment main cache file');
    file_put_contents( $filename, '<!-- -->' )or die( 'ERROR: create file' );

    // rồi nhúng các nội dung khác vào sau
    WGR_rut_gon_HTML_truoc_khi_tao_cache( $data, $filename );

    // v3
    // nhúng comment vào sau
    if ( $data_comment != '' ) {
        file_put_contents( $filename, $data_comment, FILE_APPEND )or die( 'ERROR: write comment main cache file' );
    }
    //echo $filename . '<br>' . "\n";

    return true;


    // v1
    // sử dụng hàm này cho gọn
    file_put_contents( $filename, WGR_rut_gon_HTML_truoc_khi_tao_cache( $data ) . $data_comment )or die( 'ERROR: write main cache file' );

    // TEST
    //	unlink ( ABSPATH . EB_DIR_CONTENT . '/uploads/ebcache/all/-wordpress.org-.txt' ); echo 'TEST';

    //
    //	chmod($filename, 0777);

    /*
    // mở file để ghi
    $filew = fopen( $filename, 'w+' );
    // ghi nội dung cho file
    fwrite($filew, $data);
    fclose($filew);
    */

    return true;
}

// kiểm tra và nạp ebsuppercache nếu chưa có -> chỉ áp dụng khi người dùng đang đăng nhập -> thường thì admin mới đăng nhập
function WGR_add_ebcache_php_to_index( $__cf_row ) {
    // nếu chưa có tham số WP_ACTIVE_WGR_SUPPER_CACHE
    if ( !defined( 'WP_ACTIVE_WGR_SUPPER_CACHE' ) &&
        // và phải là user đã đăng nhập
        mtv_id > 0 &&
        // supper cache đang bật
        $__cf_row[ 'cf_enable_ebsuppercache' ] == 1 &&
        // ebcache chưa được nạp
        strstr( file_get_contents( ABSPATH . 'index.php', 1 ), '/echbaydotcom/ebcache.php' ) == false ) {
        // copy file mẫu
        if ( copy( __DIR__ . '/index-tmp.php', ABSPATH . 'index.php' ) ) {
            echo 'active WP_ACTIVE_WGR_SUPPER_CACHE <br>' . "\n";
        } else {
            // không copy được thì dùng chức năng tạo file -> có hỗ trợ sử dụng phương thức FTP
            _eb_create_file( ABSPATH . 'index.php', file_get_contents( __DIR__ . '/index-tmp.php', 1 ) );

            //
            echo 'add ebsuppercache to index.php (1)<br>' . "\n";
        }
    }
}

function WGR_add_ebcache_php_to_index_v1( $__cf_row ) {
    if ( mtv_id > 0 && $__cf_row[ 'cf_enable_ebsuppercache' ] == 1 ) {
        //echo ABSPATH . '<br>' . "\n";
        $content_of_wp_index = trim( file_get_contents( ABSPATH . 'index.php', 1 ) );

        // nếu chưa có file cache thì thêm vào thôi
        if ( strstr( $content_of_wp_index, 'echbaydotcom/ebcache.php' ) == false ) {
            //echo __DIR__ . '<br>' . "\n";

            // tách theo dấu xuống dòng \n
            $content_of_wp_index = explode( "\n", $content_of_wp_index );
            if ( trim( $content_of_wp_index[ 0 ] ) == '<?php' ) {
                $content_of_wp_index[ 0 ] .= ' ' . "\n" . 'include __DIR__ . \'/wp-content/echbaydotcom/ebcache.php\';';
                //print_r($content_of_wp_index);
                //die('sdg sgds');

                //
                _eb_create_file( ABSPATH . 'index.php', implode( "\n", $content_of_wp_index ) );
                echo 'add ebsuppercache to index.php (1)<br>' . "\n";
            } else {
                // tách theo dấu cách
                $content_of_wp_index = implode( "\n", $content_of_wp_index );
                $content_of_wp_index = explode( ' ', $content_of_wp_index );

                //
                if ( trim( $content_of_wp_index[ 0 ] ) == '<?php' ) {
                    $content_of_wp_index[ 0 ] .= ' ' . 'include __DIR__ . \'/wp-content/echbaydotcom/ebcache.php\';';
                    _eb_create_file( ABSPATH . 'index.php', implode( ' ', $content_of_wp_index ) );
                    echo 'add ebsuppercache to index.php (2)<br>' . "\n";
                } else {
                    echo 'ERROR add ebsuppercache<br>' . "\n";
                }
            }
        } else {
            //echo ABSPATH . 'index.php' . '<br>' . "\n";
            //echo 'index.php has been add ebsuppercache!<br>' . "\n";
        }
    }
}

// thêm câu báo rằng đang lấy nội dung trong cache
function WGR_create_eb_cache_note() {
    global $arr_private_info_setting;
    global $set_time_for_main_cache;

    //
    return '<!-- Plugin by ' . $arr_private_info_setting[ 'site_upper' ] . ' - Theme by ' . $arr_private_info_setting[ 'theme_site_upper' ] . '
Cached page generated by ' . $arr_private_info_setting[ 'author' ] . ' Cache (ebcache), an product of ' . $arr_private_info_setting[ 'site_url' ] . ' with ' . $arr_private_info_setting[ 'theme_site_url' ] . '
Served from: ' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] . ' on ' . date( 'Y-m-d H:i:s', date_time ) . '
Served to: ebcache all URI
Cache auto clean after ' . $set_time_for_main_cache . ' secondes
Caching using hard disk drive. Recommendations using SSD for your website.
Compression = gzip -->';
}

function WGR_cat_js_cache() {
    // kiểm tra file cache category có tồn tại không -> file này nạp dạng js nên cũng cần nó tồn tại để đỡ lỗi web
    $cat_js_file_name = ( int )substr( date( 'i', time() ), 0, 1 );

    // nếu phút hiện tại là 0
    if ( $cat_js_file_name == 0 ) {
        $using_js_file_name = 5;
    } else {
        $using_js_file_name = $cat_js_file_name - 1;
    }

    // file name
    $cat_js_file_name = 'cat-' . $cat_js_file_name . '.js';
    //die( EB_THEME_CACHE . $cat_js_file_name );
    $using_js_file_name = 'cat-' . $using_js_file_name . '.js';

    return [
        'cat_js_file_name' => $cat_js_file_name,
        'using_js_file_name' => $using_js_file_name,
    ];
}

function WGR_content_cat_js_cache( $cat_js_file_name, $using_js_file_name ) {
    _eb_create_file( EB_THEME_CACHE . $cat_js_file_name, 'var eb_site_group=[' . _eb_get_full_category_v2( 0, 'category', 1 ) . '],eb_post_options_group=[' . _eb_get_full_category_v2( 0, 'post_options', 1 ) . '],eb_blog_group=[' . _eb_get_full_category_v2( 0, EB_BLOG_POST_LINK, 1 ) . '];' );

    //
    if ( !file_exists( EB_THEME_CACHE . $using_js_file_name ) ) {
        copy( EB_THEME_CACHE . $cat_js_file_name, EB_THEME_CACHE . $using_js_file_name );
        chmod( EB_THEME_CACHE . $using_js_file_name, 0777 );
    }
}