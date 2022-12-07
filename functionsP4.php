<?php


// bóc tách dữ liệu trong phần đơn hàng để xử lý
function WGR_decode_for_products_cart($a)
{
    if ($a == '') {
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
    foreach ($arr as $k => $v) {
        $a = str_replace($k, $v, $a);
    }

    //$a = urldecode( $a );
    //echo $a . '<br>' . "\n\n";
    //echo urldecode($a);

    // cắt bỏ phần name đi, vì nó hay bị lỗi chữ tiếng Việt
    $a = explode('"name":"', $a);
    //print_r( $a );
    foreach ($a as $k => $v) {
        if ($k > 0) {
            $v = strstr($v, '"slug":"');
            $a[$k] = $v;
        }
    }
    //print_r( $a );
    $a = implode('', $a);
    //echo $a . '<br>' . "\n\n";

    // chỉnh lại cái tên màu, do trước đó nó bị decode, nên trong php không hiển thị được
    try {
        $a = json_decode($a);
        foreach ($a as $k => $v) {
            if (isset($v->color) && $v->color != '') {
                //$v->color = urldecode($v->color);
                $v->color = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", $v->color);
                $v->color = html_entity_decode($v->color, ENT_QUOTES, 'UTF-8');

                //
                $arr = _eb_arr_escape_fix_content();
                foreach ($arr as $k2 => $v2) {
                    if ($v2 != '') {
                        $v->color = str_replace($v2, $k2, $v->color);
                    }
                }
            }
        }
    } catch (Exception $e) {
        $a = NULL;
    }

    return $a;
}

function WGR_decode_for_discount_cart($a)
{
    if ($a == '') {
        return NULL;
    }
    $re = NULL;

    //echo $str . '<br>' . "\n\n";

    //echo html_entity_decode($a) . '<br>' . "\n\n";
    $a = html_entity_decode($a, ENT_QUOTES, 'UTF-8');
    //echo $a . '<br>' . "\n\n";
    $a = urldecode($a);
    //echo $a . '<br>' . "\n\n";

    // bóc tách lấy phần discount
    $a = explode('","hd_discount_code":"', $a);
    //print_r( $a );
    if (count($a) > 1) {
        $a = explode('"', $a[1]);

        //
        $a = $a[0];
        if ($a != '') {
            $arr_discount_code = get_categories(
                array(
                    'name' => $a,
                    'orderby' => 'id',
                    'hide_empty' => 0,
                    'taxonomy' => 'discount_code'
                )
            );
            //print_r( $arr_discount_code );

            //
            if (!empty($arr_discount_code)) {
                //echo $arr_discount_code[0]->term_id . '<br>' . "\n";

                $arr_discount_code[0]->coupon_giagiam = _eb_get_cat_object($arr_discount_code[0]->term_id, '_eb_category_coupon_giagiam');
                $arr_discount_code[0]->coupon_phantramgiam = _eb_get_cat_object($arr_discount_code[0]->term_id, '_eb_category_coupon_phantramgiam');
                $arr_discount_code[0]->coupon_donggia = _eb_get_cat_object($arr_discount_code[0]->term_id, '_eb_category_coupon_donggia');

                //print_r( $arr_discount_code );

                $re = $arr_discount_code;
            }
        }

        //return $a;
    }

    return $re;
}

function WGR_download($source, $dest)
{
    if (function_exists('copy')) {
        if (copy($source, $dest)) {
            return true;
        }
    }

    //
    return false;
}

// hiển thị shortcode thông qua widget shortcode
function WGR_shortcode($key)
{
    $value = EBE_get_lang($key);

    // không hiển thị nếu đặt lệnh là ẩn hoặc vẫn là dạng tmp
    if ($value == 'hide' || $value == 'null' || strpos($value, 'tmp_shortcode_____') !== false) {
        return '';
    }

    // còn lại sẽ gọi đến hàm shortcode đẻ hiển thị ra
    return do_shortcode($value);
}

function WGR_echo_shortcode($key)
{
    echo WGR_shortcode($key);
    return '';
}

function WGR_before_optimize_code($confirm_file)
{
    // không tồn tại file cần thiết -> hủy
    if (!file_exists($confirm_file)) {
        return false;
    }

    // kiểm tra nội dung file
    $content_file = file_get_contents($confirm_file, 1);
    //echo $content_file . '<br>' . "\n";
    // nội dung file này phải là 1 số
    if (is_numeric($content_file)) {
        // chỉ giải nén trong khoảng thời gian cho phép
        if ($content_file > date_time) {
            echo '<!-- ' . __FUNCTION__ . ' run in ' . date('Y-m-d H:i:s', $content_file) . ' -->' . "\n";
            return false;
        }
    } else {
        // nếu là chữ -> hẹn thời gian để xử lý code -> xử lý ngay dễ bị trường hợp thu viện cần xử lý chưa được nạp xong -> lỗi luôn
        _eb_create_file($confirm_file, date_time + 60);
        return false;
    }

    //
    return true;
}

// unzip code -> chạy 1 lần duy nhất
function WGR_unzip1_vendor_code($check_confirm_file = true)
{
    // nếu có file này -> thì bỏ qua luôn -> tránh chạy quá nhiều lần
    $f = EB_THEME_PLUGIN_INDEX . '_done_unzipcode.txt';
    if (file_exists($f)) {
        return false;
    }
    // tạo file để tránh chạy nhiều lần
    _eb_create_file($f, __FILE__);
    // bắt đầu unzip code
    return WGR_unzip_vendor_code($check_confirm_file);
}

function WGR_unzip_vendor_code($check_confirm_file = true)
{
    $confirm_file = EB_THEME_PLUGIN_INDEX . 'unzipcode.txt';
    if ($check_confirm_file === true) {
        if (WGR_before_optimize_code($confirm_file) === false) {
            return false;
        }
    }
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
        return false;
    }
    echo '<!-- ' . __FUNCTION__ . ' running... -->';
    $arr_debug = debug_backtrace();
    echo '<!-- ' . basename($arr_debug[1]['file']) . ':' . $arr_debug[1]['line'] . ' -->' . "\n";
    //echo $arr_debug[ 1 ][ 'function' ] . '<br>' . "\n";
    //echo basename( str_replace( '\\', '/', $arr_debug[ 1 ][ 'class' ] ) ) . '<br>' . "\n";

    //
    $arr_vendor_list = [
    EB_THEME_PLUGIN_INDEX . 'outsource',
    EB_THEME_URL . 'outsource',
    ];
    //print_r( $arr_vendor_list );

    //
    $zip = new ZipArchive;
    foreach ($arr_vendor_list as $base_for_unzip) {
        if (!is_dir($base_for_unzip)) {
            //echo $base_for_unzip . '<br>' . "\n";
            continue;
        }

        //
        foreach (glob($base_for_unzip . '/*.zip') as $filename) {
            //echo $filename . '<br>' . "\n";

            //
            $dir_unzip = dirname($filename) . '/' . basename($filename, '.zip');
            //echo $dir_unzip . '<br>' . "\n";

            //
            if (is_dir($dir_unzip)) {
                continue;
            }
            echo '<!-- ' . __FUNCTION__ . ' ' . $filename . ':' . __LINE__ . ' -->' . "\n";

            //
            if ($zip->open($filename) === TRUE) {
                $zip->extractTo($base_for_unzip);
                $zip->close();
            }

            // giải nén thành công -> có thưu mục mới
            if (is_dir($dir_unzip)) {
                echo '<!-- DONE! sync code ' . basename($dir_unzip) . ' -->' . "\n";
                continue;
            }
            echo '<!-- ERROR! sync code ' . basename($dir_unzip) . ' -->' . "\n";

            // thử xử lý qua ftp
            if (!defined('FTP_USER') || !defined('FTP_PASS')) {
                continue;
            }
            $ftp_server = EBE_check_ftp_account();
            if ($ftp_server === false) {
                echo '<!-- FTP account not found -->' . "\n";
                continue;
            }
            $ftp_user_name = FTP_USER;
            $ftp_user_pass = FTP_PASS;

            // tạo kết nối
            echo '<!-- connect to ftp -->' . "\n";

            $conn_id = ftp_connect($ftp_server);
            if (!$conn_id) {
                echo '<!-- ERROR FTP connect to server -->' . "\n";
                ftp_close($conn_id);
                continue;
            }
            // đăng nhập
            echo 'login to ftp <br>' . "\n";
            if (!ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)) {
                echo '<!-- ERROR FTP login to server -->' . "\n";
                ftp_close($conn_id);
                continue;
            }
            $ftp_dir_root = EBE_get_config_ftp_root_dir(date_time);

            //
            $file_for_ftp = $base_for_unzip;
            if ($ftp_dir_root != '') {
                $file_for_ftp = strstr($file_for_ftp, '/' . $ftp_dir_root . '/');
            }
            echo '<!-- ' . $file_for_ftp . ' -->' . "\n";

            //
            if (!ftp_chmod($conn_id, 0777, $file_for_ftp)) {
                echo '<!-- ERROR FTP: ftp_chmod error -->' . "\n";

                //
                $file_for_ftp = $filename;
                if ($ftp_dir_root != '') {
                    $file_for_ftp = strstr($file_for_ftp, '/' . $ftp_dir_root . '/');
                }
                echo '<!-- ' . $file_for_ftp . ' -->' . "\n";

                //
                if (!ftp_chmod($conn_id, 0777, $file_for_ftp)) {
                    echo '<!-- ERROR FTP: ftp_chmod error -->' . "\n";
                } else {
                    echo '<!-- chmod via ftp -->' . "\n";
                }
            } else {
                echo '<!-- chmod via ftp -->' . "\n";
            }
            ftp_close($conn_id);
        }
    }

    //
    _eb_remove_file($confirm_file);
    // không xóa được file -> bỏ luôn
    if (file_exists($confirm_file)) {
        echo '<!-- Can not remove file ' . basename($confirm_file) . ' -->';
        return false;
    }
    return true;
}

function WGR_optimize_backup_code($source_file, $save_dir, $min_line = 10)
{
    // số lượng code trong file cần optimize quá ít thì cũng thôi
    if (count(explode("\n", file_get_contents($source_file))) < $min_line) {
        //echo '<!-- ' . str_replace( ABSPATH, '', $source_file ) . ' -->' . "\n";
        return false;
    }

    // nếu tồn tại file done -> lượng dòng của file này nhiều sẵn rồi -> bỏ qua
    $done_file = $save_dir . '/' . str_replace('.', '-', basename($source_file)) . '-after-optimize.txt';
    if (file_exists($done_file)) {
        //echo $done_file . '<br>' . "\n";
        return false;
    }

    //echo $save_dir . '<br>' . "\n";
    $bak_file = $save_dir . '/' . str_replace('.', '-', basename($source_file)) . '-before-optimize.txt';
    //echo $bak_file . '<br>' . "\n";

    // chưa tại file backup rồi thì thực hiện copy
    if (!file_exists($bak_file)) {
        error_reporting(0);
        //_eb_create_file( $bak_file, date_time );
        WGR_copy($source_file, $bak_file);
        //return false;
    }

    // thực hiện optimize
    if (file_exists($bak_file)) {
        WGR_compiler_update_echbay_css_js($source_file);

        // với 1 số file, sau khi optimize xong lượng dòng vẫn quá lớn -> tạo file confirm để bỏ qua
        if (count(explode("\n", file_get_contents($source_file))) >= $min_line) {
            _eb_create_file($done_file, count(explode("\n", file_get_contents($source_file))));
        }

        //
        echo '<!-- ' . str_replace(ABSPATH, '', $bak_file) . ' -->' . "\n";
        echo '<!-- ' . str_replace(ABSPATH, '', $source_file) . ' -->' . "\n";

        //
        return true;
    }
    return false;
}

function WGR_optimize_static_code()
{
    // không thực thi chức năng này ở phần load ajax
    //var_dump( strpos( $_SERVER[ 'REQUEST_URI' ], '/admin-ajax.php' ) );
    //var_dump( strpos( $_SERVER[ 'REQUEST_URI' ], '/post.php' ) );
    /*
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/admin-ajax.php' ) !== false ) {
    //echo '1';
    return false;
    }
    */
    //echo '0';

    //
    $confirm_file = EB_THEME_PLUGIN_INDEX . 'optimizecode.txt';
    //echo $confirm_file . '<br>' . "\n";
    if (WGR_before_optimize_code($confirm_file) === false) {
        return false;
    }
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
        return false;
    }
    //echo __FUNCTION__ . ' running... <br>' . "\n";

    // Nếu không có function cần thiết -> nạp vào thôi
    if (!function_exists('WGR_compiler_update_echbay_css_js')) {
        include_once ECHBAY_PRI_CODE . 'echbay_compiler_core.php';
    }

    // tham số này là để optimize từ từ, không cần optimize liên tục
    $has_optimize = false;

    // thư mục chứa file cần xử lý
    $arr_optimize_dir = [
        'css',
        'css/default',
        'css/template',
        'class/widget',
        'themes/css',
        'html/details',
        'html/details/mobilemua',
        'html/details/pcmua',
        'html/search',
        'javascript',
    ];

    foreach ($arr_optimize_dir as $v) {
        if ($has_optimize === true) {
            break;
        }

        //
        $v = rtrim(EB_THEME_PLUGIN_INDEX . $v, '/');
        echo '<!-- ' . __FUNCTION__ . ' ' . str_replace(ABSPATH, '', $v) . ' -->' . "\n";
        if (!is_dir($v)) {
            continue;
        }

        //
        foreach (glob($v . '/*.css') as $css_filename) {
            //echo $css_filename . '<br>' . "\n";
            if (WGR_optimize_backup_code($css_filename, $v) === true) {
                $has_optimize = true;
                break;
            }
        }
        if ($has_optimize === true) {
            break;
        }

        //
        foreach (glob($v . '/*.js') as $js_filename) {
            //echo $js_filename . '<br>' . "\n";
            if (WGR_optimize_backup_code($js_filename, $v) === true) {
                $has_optimize = true;
                break;
            }
        }
        if ($has_optimize === true) {
            break;
        }
    }
    //var_dump( $has_optimize );

    // for child theme
    if ($has_optimize === false)
        $has_optimize = WGR_optimize_child_theme_static_code();

    // for php
    //if ( $has_optimize === false )$has_optimize = WGR_optimize_php_code();

    // đến cuối cùng mà không còn file nào để optimize -> xóa file xác thực optimize thôi
    if ($has_optimize === false) {
        _eb_remove_file($confirm_file);
        // không xóa được file -> bỏ luôn
        if (file_exists($confirm_file)) {
            echo '<!-- Can not remove file ' . basename($confirm_file) . ' -->';
            return false;
        }
    }
}

function WGR_optimize_child_theme_static_code()
{
    $has_optimize = false;

    //
    if (defined('EB_CHILD_THEME_URL')) {
        //echo EB_CHILD_THEME_URL . '<br>' . "\n";

        $arr_optimize_dir = [
            '',
            'ui',
            'templates',
            'shortcode',
        ];

        foreach ($arr_optimize_dir as $v) {
            if ($has_optimize === true) {
                break;
            }

            //
            $v = rtrim(EB_CHILD_THEME_URL . $v, '/');
            echo '<!-- ' . str_replace(ABSPATH, '', $v) . ' -->' . "\n";
            if (!is_dir($v)) {
                continue;
            }

            //
            foreach (glob($v . '/*.css') as $css_filename) {
                //echo $css_filename . '<br>' . "\n";
                if (basename($css_filename) != 'style.css') {
                    if (WGR_optimize_backup_code($css_filename, $v) === true) {
                        $has_optimize = true;
                        break;
                    }
                }
            }
            if ($has_optimize === true) {
                break;
            }

            //
            foreach (glob($v . '/*.js') as $js_filename) {
                //echo $js_filename . '<br>' . "\n";
                if (WGR_optimize_backup_code($js_filename, $v) === true) {
                    $has_optimize = true;
                    break;
                }
            }
            if ($has_optimize === true) {
                break;
            }
        }
    }

    //
    return $has_optimize;
}

function WGR_optimize_php_code()
{
    $arr_optimize_dir = [
        'global',
        'global/temp',
        'class/widget',
    ];

    //
    $has_optimize = false;
    foreach ($arr_optimize_dir as $v) {
        if ($has_optimize === true) {
            break;
        }

        //
        $v = rtrim(EB_THEME_PLUGIN_INDEX . $v, '/');
        echo '<!-- ' . str_replace(ABSPATH, '', $v) . ' -->' . "\n";
        if (!is_dir($v)) {
            continue;
        }

        //
        foreach (glob($v . '/*.php') as $php_filename) {
            if (WGR_optimize_backup_code($php_filename, $v) === true) {
                $has_optimize = true;
                break;
            }
        }
        if ($has_optimize === true) {
            break;
        }
    }

    //
    return $has_optimize;
}