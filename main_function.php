<?php

// 
// echo EB_THEME_CACHE . '___timezone.txt';
if (is_file(EB_THEME_CACHE . '___timezone.txt')) {
    // echo file_get_contents(EB_THEME_CACHE . '___timezone.txt');
    date_default_timezone_set(file_get_contents(EB_THEME_CACHE . '___timezone.txt'));

    // 
    // define('EBE_HAS_SET_TIMEZONE', true);
}


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
$set_time_for_main_cache = $set_time_for_main_cache - rand(0, $set_time_for_main_cache / 2);

// thông điệp về ebcache
$why_ebcache_not_active = '';


//
function ___eb_cache_getUrl($cache_dir = 'all')
{
    if (defined('MY_FXIED_CACHE_FILENAME')) {
        $url = MY_FXIED_CACHE_FILENAME;
        // echo __FILE__ . ':' . __LINE__ . '<br>' . PHP_EOL;
    } else {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
        } else {
            $url = $_SERVER['SCRIPT_NAME'];
            $url .= (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        }
        if ($url == '/' || $url == '') {
            $url = '-';
        } else {
            $arr_cat_social_parameter = array(
                'fbclid=',
                'gclid=',
                'fb_comment_id=',
                'utm_'
            );
            foreach ($arr_cat_social_parameter as $v) {
                $url = explode('?' . $v, $url);
                $url = explode('&' . $v, $url[0]);
                $url = $url[0];
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
            if (strlen($url) > 200) {
                // $url = md5($url);
                $url = substr($url, 0, 200);
            }
            $url = preg_replace("/\/|\?|\&|\,|\=/", '-', $url);
        }
    }

    //
    $url = EB_THEME_CACHE . $cache_dir . '/' . $url . '.txt';

    //
    return $url;
}

// rút gọn HTML
function WGR_rut_gon_HTML_truoc_khi_tao_cache($data, $filename = '', $data_comment = '')
{

    //
    //	return $data;
    // echo $data . '<br>' . PHP_EOL;

    //
    $data = WGR_remove_js_multi_comment($data, '<!--', '-->');

    //
    $a = explode("\n", $data);
    // print_r($a);
    // die(__FILE__ . ':' . __LINE__);
    $data = '';
    // $i = 0;
    // $create_file = 1;

    foreach ($a as $v) {
        $v = trim($v);

        if ($v == '') {
            continue;
        }

        //echo substr( $v, -3 ) . PHP_EOL;

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
        //if (strpos($v, '//') !== false) {
        $v .= PHP_EOL;
        /*
         }
         else {
         $v .= ' ';
         }
         */

        // v1
        $data .= $v;


        // v2 -> vài vòng lặp sẽ add nội dung 1 lần để tránh biến to quá hoặc hàm file put contents bị gọi nhiều quá
        /*
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
     */

        //			}
    }
    // v2 -> nhúng nội dung còn thiếu ở những vòng lặp cuối
    if ($data != '') {
        WGR_cache($filename, $data . $data_comment);
        //die(basename(__FILE__) . ':' . __LINE__);
        //file_put_contents( $filename, $data, FILE_APPEND )or die( 'ERROR: append last main cache file' );
        //file_put_contents($filename, $data . $data_comment, LOCK_EX) or die('ERROR: append last main cache file');
        //die(basename(__FILE__) . ':' . __LINE__);
        //touch($filename);
    }

    //
    return true;
}

// page's content is $buffer ($data)
function ___eb_cache_cache($filename, $data, $data_comment = '')
{
    //echo $filename . '<br>' . PHP_EOL;

    // v2
    // nhúng comment vào trước
    //	file_put_contents( $filename, $data_comment ) or die('ERROR: write comment main cache file');
    //file_put_contents( $filename, '<!-- -->' )or die( 'ERROR: create file' );

    // rồi nhúng các nội dung khác vào sau
    return WGR_rut_gon_HTML_truoc_khi_tao_cache($data, $filename, $data_comment);
}

// kiểm tra và nạp ebsuppercache nếu chưa có -> chỉ áp dụng khi người dùng đang đăng nhập -> thường thì admin mới đăng nhập
function WGR_add_ebcache_php_to_index($__cf_row)
{
    // nếu chưa có tham số WP_ACTIVE_WGR_SUPPER_CACHE
    if (
        !defined('WP_ACTIVE_WGR_SUPPER_CACHE') &&
        // và phải là user đã đăng nhập
        mtv_id > 0 &&
        // supper cache đang bật
        $__cf_row['cf_enable_ebsuppercache'] == 1 &&
        // ebcache chưa được nạp
        strpos(file_get_contents(ABSPATH . 'index.php', 1), '/echbaydotcom/ebcache.php') === false
    ) {
        // copy file mẫu
        if (copy(__DIR__ . '/index-tmp.php', ABSPATH . 'index.php')) {
            echo 'active WP ACTIVE WGR SUPPER CACHE <br>' . PHP_EOL;
        } else {
            // không copy được thì dùng chức năng tạo file -> có hỗ trợ sử dụng phương thức FTP
            _eb_create_file(ABSPATH . 'index.php', file_get_contents(__DIR__ . '/index-tmp.php', 1));

            //
            echo 'add ebsuppercache to index.php (1)<br>' . PHP_EOL;
        }
    }
}

function WGR_v1_add_ebcache_php_to_index($__cf_row)
{
    if (mtv_id > 0 && $__cf_row['cf_enable_ebsuppercache'] == 1) {
        //echo ABSPATH . '<br>' . PHP_EOL;
        $content_of_wp_index = trim(file_get_contents(ABSPATH . 'index.php', 1));

        // nếu chưa có file cache thì thêm vào thôi
        if (strpos($content_of_wp_index, 'echbaydotcom/ebcache.php') === false) {
            //echo __DIR__ . '<br>' . PHP_EOL;

            // tách theo dấu xuống dòng \n
            $content_of_wp_index = explode(PHP_EOL, $content_of_wp_index);
            if (trim($content_of_wp_index[0]) == '<?php') {
                $content_of_wp_index[0] .= ' ' . PHP_EOL . 'include __DIR__ . \'/wp-content/echbaydotcom/ebcache.php\';';
                //print_r($content_of_wp_index);
                //die('sdg sgds');

                //
                _eb_create_file(ABSPATH . 'index.php', implode(PHP_EOL, $content_of_wp_index));
                echo 'add ebsuppercache to index.php (1)<br>' . PHP_EOL;
            } else {
                // tách theo dấu cách
                $content_of_wp_index = implode(PHP_EOL, $content_of_wp_index);
                $content_of_wp_index = explode(' ', $content_of_wp_index);

                //
                if (trim($content_of_wp_index[0]) == '<?php') {
                    $content_of_wp_index[0] .= ' ' . 'include __DIR__ . \'/wp-content/echbaydotcom/ebcache.php\';';
                    _eb_create_file(ABSPATH . 'index.php', implode(' ', $content_of_wp_index));
                    echo 'add ebsuppercache to index.php (2)<br>' . PHP_EOL;
                } else {
                    echo 'ERROR add ebsuppercache<br>' . PHP_EOL;
                }
            }
        } else {
            //echo ABSPATH . 'index.php' . '<br>' . PHP_EOL;
            //echo 'index.php has been add ebsuppercache!<br>' . PHP_EOL;
        }
    }
}

// thêm câu báo rằng đang lấy nội dung trong cache
function WGR_create_eb_cache_note()
{
    global $arr_private_info_setting;
    global $set_time_for_main_cache;

    //
    return '<!-- Plugin by ' . $arr_private_info_setting['site_upper'] . ' - Theme by ' . $arr_private_info_setting['theme_site_upper'] . '
Cached page generated by ' . $arr_private_info_setting['author'] . ' Cache (ebcache), an product of ' . $arr_private_info_setting['site_url'] . ' with ' . $arr_private_info_setting['theme_site_url'] . '
Served from: ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . ' on ' . date('r', time()) . ' ' . $_SERVER['REMOTE_ADDR'] . '
Served to: ebcache all URI
Cache auto clean after ' . $set_time_for_main_cache . ' secondes
Caching using hard disk drive. Recommendations using SSD for your website.
Compression = gzip -->';
}

function WGR_cat_js_cache()
{
    // kiểm tra file cache category có tồn tại không -> file này nạp dạng js nên cũng cần nó tồn tại để đỡ lỗi web
    $cat_js_file_name = (int)substr(date('i', time()), 0, 1);

    // nếu phút hiện tại là 0
    if ($cat_js_file_name == 0) {
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

function WGR_content_cat_js_cache($cat_js_file_name, $using_js_file_name)
{
    _eb_create_file(EB_THEME_CACHE . $cat_js_file_name, 'var eb_site_group=[' . _eb_get_full_category_v2(0, 'category', 1) . '],eb_post_options_group=[' . _eb_get_full_category_v2(0, 'post_options', 1) . '],eb_blog_group=[' . _eb_get_full_category_v2(0, EB_BLOG_POST_LINK, 1) . '];');

    //
    if (!is_file(EB_THEME_CACHE . $using_js_file_name)) {
        copy(EB_THEME_CACHE . $cat_js_file_name, EB_THEME_CACHE . $using_js_file_name);
        chmod(EB_THEME_CACHE . $using_js_file_name, 0777);
    }
}

//getUrl gets the queried page with query string
function WGR_cache($f, $buffer)
{
    // page's content is $buffer
    return file_put_contents($f, time() . '¦' . $buffer, LOCK_EX) or die('ERROR: append last main cache file');
}

function WGR_display($f, $reset_time = 120)
{
    // echo $f . '<br>' . PHP_EOL;
    // die(__FILE__ . ':' . __LINE__);
    $data = file_get_contents($f, 1);
    // echo $data . '<br>' . PHP_EOL;
    $content = explode('¦', $data, 2);
    // echo count($content) . '<br>' . PHP_EOL;
    if (count($content) != 2 || !is_numeric($content[0])) {
        return false;
    }
    $active_reset = time() - ($content[0] * 1);
    // echo $active_reset . '<br>' . PHP_EOL;
    //die(__FILE__ . ':' . __LINE__);
    if ($active_reset > $reset_time) {
        return false;
    }

    // kiểm tra file js liên quan phải có
    $arr_cat_js_cache = WGR_cat_js_cache();

    //
    $cat_js_file_name = $arr_cat_js_cache['cat_js_file_name'];
    $using_js_file_name = $arr_cat_js_cache['using_js_file_name'];

    // -> done
    if (is_file(EB_THEME_CACHE . $cat_js_file_name) && is_file(EB_THEME_CACHE . $using_js_file_name)) {
        // echo __FILE__ . ':' . __LINE__ . '<br>' . PHP_EOL;
        echo $content[1];
        echo '<!-- generated by ebsuppercache (' . $active_reset . ' | ' . $reset_time . ' | ' . date('r', $content[0]) . ') -->';
        exit();
    }
    // echo __FILE__ . ':' . __LINE__ . '<br>' . PHP_EOL;

    //
    return false;
}
