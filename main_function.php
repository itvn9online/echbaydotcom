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
// $set_time_for_main_cache = 600;
//$set_time_for_main_cache = 60;

// thời gian cache
//$set_time_for_main_cache = $__cf_row['cf_reset_cache'];

// thử xem cache x2 đối với đoạn này xem có ok không
//$set_time_for_main_cache = $__cf_row['cf_reset_cache'] * 2;

// set tĩnh thời gian cache
$set_time_for_main_cache = mt_rand(300, 600);

// thông điệp về ebcache
$why_ebcache_not_active = '';


//
function ___eb_cache_getUrl($cache_dir = 'all')
{
    // echo __FUNCTION__ . ':' . __LINE__ . '<br>' . PHP_EOL;
    // echo debug_backtrace()[1]['function'] . ':' . __LINE__ . '<br>' . PHP_EOL;

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
                'add_to_wishlist=',
                '_wpnonce=',
                'utm_',
                'v',
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
            // $url = preg_replace("/\/|\?|\&|\,|\=/", '-', $url);
            $url = str_replace([
                '&amp%3B',
                '&amp;',
                '/',
                '?',
                '&',
                ',',
                '=',
            ], '-', $url);
            // echo $url . '<br>' . PHP_EOL;

            // thay thế 2- thành 1-  
            $url = preg_replace('!\-+!', '-', $url);
            // echo $url . '<br>' . PHP_EOL;

            // cắt bỏ ký tự - ở đầu và cuối chuỗi
            $url = rtrim(ltrim($url, '-'), '-');
            $url = rtrim(ltrim($url, '.'), '.');
            $url = trim($url);
            // echo $url . '<br>' . PHP_EOL;
            if ($url == '') {
                $url = '-';
                // echo $url . '<br>' . PHP_EOL;
            }
        }
    }

    //
    // if (isset($_GET['sdgs34fgjdfdf34dhdhdf'])) {
    //     die($url);
    // }

    // cache file thì dùng tham số này
    return EB_THEME_CACHE . $cache_dir . '/' . $url . '.txt';
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
    $cache_using = 'hard disk';
    if (defined('EB_REDIS_CACHE') && EB_REDIS_CACHE == true) {
        $cache_using = 'redis';
    }

    //
    return '<!-- Plugin by ' . $arr_private_info_setting['site_upper'] . ' - Theme by ' . $arr_private_info_setting['theme_site_upper'] . '
Cached page generated by ' . $arr_private_info_setting['author'] . ' Cache (ebcache), an product of ' . $arr_private_info_setting['site_url'] . ' with ' . $arr_private_info_setting['theme_site_url'] . '
Served from: ' . (defined('EB_PREFIX_CACHE') ? EB_PREFIX_CACHE : '') . ':' . $_SERVER['REQUEST_URI'] . ' on ' . date('r', time()) . ' ' . $_SERVER['REMOTE_ADDR'] . '
Served to: ebcache all URI
Cache auto clean after ' . $set_time_for_main_cache . ' secondes
Caching using ' . $cache_using . ' drive. Recommendations using SSD for your website.
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
    // tạo file cache tĩnh nếu chưa có
    if (defined('EB_MY_CACHE_CONFIG') && !is_file(EB_MY_CACHE_CONFIG)) {
        // lấy nội dung file config mẫu
        $config_content = file_get_contents(__DIR__ . '/my-config.php');

        // xem có đang yêu cầu bật redis cache hay không
        $enable_redis = 'false';
        if (defined('WGR_REDIS_CACHE') && WGR_REDIS_CACHE == true) {
            $enable_redis = 'true';
        }
        $config_content = str_replace('enable_redis', $enable_redis, $config_content);
        // gán prefix cho cache luôn
        defined('WGR_CACHE_PREFIX') || define('WGR_CACHE_PREFIX', strtolower(str_replace([
            'www.',
            '.'
        ], '', str_replace('-', '_', explode(':', $_SERVER['HTTP_HOST'])[0]))));
        $config_content = str_replace('str_cache_prefix', WGR_CACHE_PREFIX, $config_content);

        // tạo file
        file_put_contents(EB_MY_CACHE_CONFIG, $config_content, LOCK_EX) or die('ERROR: create my-config');

        // tìm và xóa các file cache cũ hơn
        $dir_config = dirname(EB_MY_CACHE_CONFIG);
        for ($i = 5; $i < 15; $i++) {
            $file_config = $dir_config . '/my-config-' . date('Ymd', time() - 24 * 3600 * $i) . '.php';
            // echo $file_config . '<br>' . PHP_EOL;
            if (is_file($file_config)) {
                unlink($file_config);
            }
        }
    }

    // echo 'aaaaaaaaaaa';
    // page's content is $buffer

    // sử dụng redis cache
    if (defined('EB_REDIS_CACHE') && EB_REDIS_CACHE == true) {
        // die(__FUNCTION__ . ':' . __LINE__);
        $rd = new Redis();
        $rd->connect(REDIS_MY_HOST, REDIS_MY_PORT);
        //echo "Connection to server sucessfully";
        //set the data in redis string 
        $rd_key = WGR_redis_key($f);
        // echo $rd_key;
        try {
            // key will be deleted after 10 seconds
            $rd->setex($rd_key, 3600, WGR_buffer($buffer));
        } catch (Exception $e) {
            // set the data in redis string
            $rd->set($rd_key, WGR_buffer($buffer));
            // key will be deleted after 10 seconds
            $rd->expire($rd_key, 3600);
        }
        return true;
    }

    // return file_put_contents($f, time() . '¦' . $buffer, LOCK_EX) or die('ERROR: append last main cache file');
    return file_put_contents($f, WGR_buffer($buffer), LOCK_EX) or die('ERROR: append last main cache file');
}

function WGR_buffer($buffer)
{
    return time() . '|WGR_CACHE|' . $buffer;
}

function WGR_display($f, $reset_time = 120)
{
    // echo $f . '<br>' . PHP_EOL;
    // die(__FILE__ . ':' . __LINE__);
    // nếu sử dụng redis cache thì trả về tham số này luôn
    if (defined('EB_REDIS_CACHE') && EB_REDIS_CACHE == true) {
        // echo WGR_redis_key($f);
        // die(__FUNCTION__ . ':' . __LINE__);
        $rd = new Redis();
        $rd->connect(REDIS_MY_HOST, REDIS_MY_PORT);
        //echo "Connection to server sucessfully";
        // Get the stored data and print it 
        $data = $rd->get(WGR_redis_key($f));
        //var_dump($data);
        //echo "Stored string in redis: " . $data;
        if ($data === false) {
            return false;
        }
    } else {
        $data = file_get_contents($f, 1);
    }
    // echo $data . '<br>' . PHP_EOL;
    // $content = explode('¦', $data, 2);
    $content = explode('|WGR_CACHE|', $data, 2);
    // echo count($content) . '<br>' . PHP_EOL;
    // echo PHP_ZTS . PHP_EOL;
    // echo PHP_SAPI . PHP_EOL;
    if (count($content) < 2 || !is_numeric($content[0])) {
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

// tạo key cho redis cache từ file name
function WGR_redis_key($f)
{
    // dùng redis thì cắt bỏ đuôi txt cho nhẹ
    // echo $f . '<br>' . PHP_EOL;
    $f = str_replace('.', '-', explode('.txt', basename($f))[0]);
    // echo $f . '<br>' . PHP_EOL;
    if (defined('EB_PREFIX_CACHE')) {
        return EB_PREFIX_CACHE . $f;
    }
    return $f;
}
