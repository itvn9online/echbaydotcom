<?php


function EBE_get_list_file_update_echbay_core($dir, $arr_dir = array(), $arr_file = array())
{

    if (substr($dir, -1) == '/') {
        $dir = substr($dir, 0, -1);
    }

    //	$arr = glob ( $dir . '/*' );
    $arr = EBE_get_file_in_folder($dir . '/');
    //	print_r( $arr );

    //
    foreach ($arr as $v) {
        if (is_dir($v)) {
            $arr_dir[] = $v;

            $a = EBE_get_list_file_update_echbay_core($v, $arr_dir, $arr_file);
            $arr_dir += $a[0];
            $arr_file += $a[1];
        } else if (is_file($v)) {
            $arr_file[] = $v;
        }
    }

    return array(
        $arr_dir,
        $arr_file
    );
}

function EBE_update_file_via_php($dir_source, $arr_dir, $arr_file, $arr_old_dir, $arr_old_file, $dir_to)
{

    //
    foreach ($arr_dir as $v) {
        $v2 = str_replace($dir_source, $dir_to, $v);

        echo '<strong>from</strong>: ' . str_replace(EB_THEME_CONTENT, '', $v . ' - <strong>to</strong>: ' . $v2) . '<br>' . "\n";

        // tạo thư mục nếu chưa có
        if (EBE_create_dir($v2) == true) {
            echo '<strong>Create dir:</strong> ' . $v2 . '<br>' . "\n";
        }
    }


    // thử tạo 1 file mẫu
    //	_eb_alert( $dir_to );
    $file_test = $dir_to . 'test_local_attack.txt';

    //
    if (is_file($file_test)) {
        _eb_remove_file($file_test);
    }

    //
    _eb_create_file($file_test, date_time, '', 0);
    // nếu không có -> không up được qua php -> dùng ftp
    if (!is_file($file_test)) {
        return false;
    } else {
        echo '<strong class="redcolor">OK! update via PHP function...</strong><br>' . "\n";
    }



    // tìm và xóa các file không tồn tại trong bản mới
    foreach ($arr_old_file as $v) {

        //		echo $v . "\n";

        // chuyển sang file ở thư mục update
        $v2 = str_replace($dir_to, $dir_source, $v);
        //		echo $v2 . "\n";

        // kiểm tra xem có file ở thư mục update không -> không có -> xóa luôn file hiện tại
        if (!is_file($v2)) {
            if (unlink($v)) {
                echo $v . ' <strong>deleted old file successful</strong><br>' . "\n";
            } else {
                echo '<strong>could not delete old file</strong> ' . $v . '<br>' . "\n";
            }
            //			echo $v . "\n";
        }
    }
    //	exit();


    // tìm và xóa các thư mục không tồn tại trong bản mới (thực hiện sau khi xóa file)
    $arr_old_dir = array_reverse($arr_old_dir);
    foreach ($arr_old_dir as $v) {

        //		echo $v . "\n";

        // chuyển sang thư mục ở thư mục update
        $v2 = str_replace($dir_to, $dir_source, $v);
        //		echo $v2 . "\n";

        // kiểm tra xem có thư mục ở thư mục update không -> không có -> xóa luôn thư mục hiện tại
        if (!is_dir($v2)) {
            if (rmdir($v)) {
                echo $v . ' <strong>deleted old dir successful</strong><br>' . "\n";
            } else {
                echo '<strong>could not delete old dir</strong> ' . $v . '<br>' . "\n";
            }
            //			echo $v . "\n";
        }
    }
    //	exit();


    //
    foreach ($arr_file as $v) {

        // kiểm tra và conpiler các file js, css
        //		if ( $localhost != 1 ) {
        //WGR_compiler_update_echbay_css_js( $v );
        //		}

        //
        $v2 = str_replace($dir_source, $dir_to, $v);

        echo 'f: ' . str_replace(EB_THEME_CONTENT, '', $v . ' - <strong>to</strong>: ' . $v2) . '<br>' . "\n";

        // upload file
        copy($v, $v2);

        unlink($v);
    }

    //
    EBE_remove_dir_after_update($dir_source, $arr_dir, $dir_to);

    return true;
}

function EBE_update_file_via_ftp($dir_name_for_unzip_to, $dir_source_update = '')
{

    // Thư mục sau khi download và giải nén file zip
    if ($dir_source_update == '') {
        $dir_source_update = EB_THEME_CACHE . $dir_name_for_unzip_to . '/';
    } else {
        $dir_source_update = rtrim($dir_source_update, '/') . '/';
    }

    // thư mục mà các file sẽ được update tới
    $dir_to_update = EB_THEME_PLUGIN_INDEX;

    //
    $arr_name_for_unzip_to = [
        'echbaytwo-master',
        'echbaytwo-main',
    ];
    if (in_array($dir_name_for_unzip_to, $arr_name_for_unzip_to)) {
        $dir_to_update = EB_THEME_URL;
    }

    // mặc định là update plugin
    if (!is_dir($dir_source_update)) {
        echo 'dir not found: ' . $dir_source_update . '<br>' . "\n";
        echo '* <em>Kiểm tra module zip.so đã có trong thư mục <strong>/usr/lib64/php/modules/</strong> chưa! Nếu chưa có thì cần cài đặt bổ sung:<br>
			Với PHP 7.1++: <strong>sudo yum -y install php-pecl-zip php-zip ; service php-fpm restart</strong><br>
			Với các bản PHP 7.0 trở xuống: <strong>yes "" | pecl install zip</strong></em>';
        return false;

        // chỉ hỗ trợ update theme có tên chỉ định
        if ($dir_name_for_unzip_to != 'echbaydotcom-master' && !in_array($dir_name_for_unzip_to, $arr_name_for_unzip_to)) {
            echo 'theme it not support update via this panel: ' . $dir_to_update . '<br>' . "\n";
            echo '* <em>Chỉ hỗ trợ update theme có nền là <strong>echbaytwo</strong>!</em>';
            return false;
        }
    }
    echo 'Source udpate: <strong>' . basename($dir_source_update) . '</strong><br>' . "\n";
    echo 'To update: <strong>' . basename($dir_to_update) . '</strong><br>' . "\n";

    // lấy danh sách file và thư mục (thư mục mới)
    $a = EBE_get_list_file_update_echbay_core($dir_source_update);
    $list_dir_for_update_eb_core = $a[0];
    $list_file_for_update_eb_core = $a[1];
    print_r($list_dir_for_update_eb_core);
    print_r($list_file_for_update_eb_core);
    //die(__FILE__ . ':' . __LINE__);

    // lấy danh sách file và thư mục (thư mục cũ) -> để so sánh và xóa các file không còn tồn tại
    $a = EBE_get_list_file_update_echbay_core($dir_to_update);
    $list_dir_for_update_old_core = $a[0];
    $list_file_for_update_old_core = $a[1];
    print_r($list_dir_for_update_old_core);
    print_r($list_file_for_update_old_core);
    die(__FILE__ . ':' . __LINE__);



    // update thông qua hàm cơ bản của php --------> ƯU TIÊN
    if (EBE_update_file_via_php($dir_source_update, $list_dir_for_update_eb_core, $list_file_for_update_eb_core, $list_dir_for_update_old_core, $list_file_for_update_old_core, $dir_to_update) == true) {
        // nếu update thành công thì thôi
        return true;
    }
    // nếu không -> sẽ update qua FTP
    else {

        // update file thông qua ftp -> nếu không có dữ liệu -> hủy luôn
        $ftp_server = EBE_check_ftp_account();

        //		if ( ! defined('FTP_USER') || ! defined('FTP_PASS') ) {
        if ($ftp_server === false) {

            // update thông qua hàm cơ bản của php
            //		return EBE_update_file_via_php( $dir_source_update, $list_dir_for_update_eb_core, $list_file_for_update_eb_core, $list_dir_for_update_old_core, $list_file_for_update_old_core, $dir_to_update );

            // đến đây mà không up được thì bỏ qua luôn
            return false;
        }
    }


    // tạo kết nối tới FTP
    $ftp_user_name = FTP_USER;
    $ftp_user_pass = FTP_PASS;



    //
    $ftp_dir_root = EBE_get_ftp_root_dir();
    echo 'FTP root dir: <strong>' . $ftp_dir_root . '</strong><br><br>' . "\n";



    // tạo kết nối
    $conn_id = ftp_connect($ftp_server) or die('ERROR connect to server');

    // đăng nhập
    ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die('AutoBot login false');

    //
    //	echo getcwd() . "\n";


    //
    //	print_r( $list_dir_for_update_eb_core );
    foreach ($list_dir_for_update_eb_core as $v) {
        $v2 = str_replace($dir_source_update, $dir_to_update, $v);

        echo '<strong>from</strong>: ' . str_replace(EB_THEME_CONTENT, '', $v . ' - <strong>to</strong>: ' . $v2) . '<br>' . "\n";

        // tạo thư mục nếu chưa có
        if (!is_dir($v2)) {
            $create_dir = '.' . strstr($v2, '/' . $ftp_dir_root . '/');
            echo '<strong>Create dir:</strong> ' . $create_dir . '<br>' . "\n";
            ftp_mkdir($conn_id, $create_dir);
        }
    }
    //	exit();


    // tìm và xóa các file không tồn tại trong bản mới
    foreach ($list_file_for_update_old_core as $v) {

        //		echo $v . "\n";

        // chuyển sang file ở thư mục update
        $v2 = str_replace($dir_to_update, $dir_source_update, $v);
        //		echo $v2 . '<br>' . "\n";

        // kiểm tra xem có file ở thư mục update không -> không có -> xóa luôn file hiện tại
        if (!is_file($v2)) {
            $v = '.' . strstr($v, '/' . $ftp_dir_root . '/');

            if (ftp_delete($conn_id, $v)) {
                echo $v . ' <strong>deleted old file successful</strong><br>' . "\n";
            } else {
                echo '<strong>could not delete old file</strong> ' . $v . '<br>' . "\n";
            }
            //			echo $v . "\n";
        }
    }
    //	exit();


    // tìm và xóa các thư mục không tồn tại trong bản mới (thực hiện sau khi xóa file)
    $arr = array_reverse($list_dir_for_update_old_core);
    foreach ($arr as $v) {

        //		echo $v . "\n";

        // chuyển sang thư mục ở thư mục update
        $v2 = str_replace($dir_to_update, $dir_source_update, $v);
        //		echo $v2 . "\n";

        // kiểm tra xem có thư mục ở thư mục update không -> không có -> xóa luôn thư mục hiện tại
        if (!is_dir($v2)) {
            $v = '.' . strstr($v, '/' . $ftp_dir_root . '/');

            if (ftp_rmdir($conn_id, $v)) {
                echo $v . ' <strong>deleted old dir successful</strong><br>' . "\n";
            } else {
                echo '<strong>could not delete old dir</strong> ' . $v . '<br>' . "\n";
            }
            //			echo $v . "\n";
        }
    }
    //	exit();



    //
    //	print_r( $list_file_for_update_eb_core );
    foreach ($list_file_for_update_eb_core as $v) {

        // kiểm tra và conpiler các file js, css
        //		if ( $localhost != 1 ) {
        //WGR_compiler_update_echbay_css_js( $v );
        //		}

        //		_eb_create_file( $file_cache_update, file_get_contents( $v, 1 ) );

        $v2 = str_replace($dir_source_update, $dir_to_update, $v);
        $v2 = '.' . strstr($v2, '/' . $ftp_dir_root . '/');

        //		$v = '.' . strstr( $v, '/' . $ftp_dir_root . '/' );

        echo 'f: ' . str_replace(EB_THEME_CONTENT, '', $v . ' - <strong>to</strong>: ' . $v2) . '<br>' . "\n";
        //		echo $file_test . ' - file cache<br>' . "\n";

        // upload file FTP_BINARY or FTP_ASCII -> nên sử dụng FTP_BINARY
        //		ftp_put($conn_id, $v2, $v, FTP_ASCII) or die( 'ERROR upload file to server #' . $v );
        ftp_put($conn_id, $v2, $v, FTP_BINARY) or die('ERROR upload file to server #' . $v);
        //		ftp_put($conn_id, $v2, $v) or die( 'ERROR upload file to server #' . $v );
        //		ftp_put($conn_id, $v2, $file_cache_update, FTP_ASCII) or die( 'ERROR upload file to server #' . $v );

        unlink($v);
    }
    //	exit();



    // xóa thư mục sau khi update
    //	foreach ( $list_dir_for_update_eb_core as $v ) {
    //	}




    // close the connection
    ftp_close($conn_id);


    //
    EBE_remove_dir_after_update($dir_source_update, $list_dir_for_update_eb_core, $dir_to_update);


    //
    return true;
}

function EBE_remove_dir_after_update($dir, $arr, $dir_to = '')
{

    echo '<br><br>' . "\n\n";

    // lật ngược mảng để xóa thư mục trước
    $arr = array_reverse($arr);
    //	print_r( $arr );
    foreach ($arr as $v) {
        rmdir($v);
        echo '<strong>remove dir</strong>: ' . str_replace(EB_THEME_CONTENT, '', $v) . '<br>' . "\n";
        //		echo '<strong>remove dir</strong>: ' . $v . '<br>' . "\n";
    }

    // xóa thư mục gốc
    rmdir($dir);
    echo '<strong>remove dir</strong>: ' . str_replace(EB_THEME_CONTENT, '', $dir) . '<br>' . "\n";
    //	echo '<strong>remove dir</strong>: ' . $dir . '<br>' . "\n";

    // test
    //	exit();


    // cập nhật lại version trong file cache
    //	_eb_get_static_html ( 'github_version', EBE_get_text_version( file_get_contents( EB_THEME_PLUGIN_INDEX . 'readme.txt', 1 ) ), '', 3600 );
    _eb_get_static_html('github_version', file_get_contents(EB_THEME_PLUGIN_INDEX . 'VERSION', 1), '', 3600);
}

function EBE_remove_dir_and_file($dir)
{
    if (!is_file($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!EBE_remove_dir_and_file($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

function EBE_get_text_version($str)
{
    $str = explode('Stable tag:', $str);
    if (isset($str[1])) {
        $str = explode("\n", $str[1]);
        return trim($str[0]);
    }
    return 'null';
}

function WGR_remove_github_file($f_gitattributes)
{
    if (is_file($f_gitattributes)) {
        if (unlink($f_gitattributes)) {
            echo '<strong>remove file</strong>: ';
        } else {
            echo '<strong>NOT remove file</strong>: ';
        }
        //		echo str_replace( EB_THEME_CONTENT, '', $f_gitattributes ) . '.gitattributes<br>' . "\n";
        echo str_replace(EB_THEME_CONTENT, '', $f_gitattributes) . '<br>' . "\n";
    }
}

function EBE_eb_update_time_to_new_time($t)
{
    $t = date_time - $t;

    if ($t < 3600) {
        $t = 'Khoảng ' . ceil($t / 60) . ' phút trước';
    } else if ($t < 24 * 3600) {
        $t = 'Khoảng ' . ceil($t / 3600) . ' giờ trước';
    } else {
        $t = 'Khoảng ' . ceil($t / 3600 / 24) . ' ngày trước';
    }

    return $t;
}

function EBE_try_ftp_rename($from, $to)
{
    // ưu tiên sử dụng PHP thuần cho nó nhanh
    if (rename($from, $to)) {
        return true;
    }

    // update file thông qua ftp -> nếu không có dữ liệu -> hủy luôn
    $ftp_server = EBE_check_ftp_account();
    if ($ftp_server === false) {
        // đến đây mà không up được thì bỏ qua luôn
        return false;
    }

    // tạo kết nối tới FTP
    $ftp_user_name = FTP_USER;
    $ftp_user_pass = FTP_PASS;

    //
    $ftp_dir_root = EBE_get_ftp_root_dir();
    echo 'FTP root dir: <strong>' . $ftp_dir_root . '</strong><br><br>' . "\n";

    // tạo kết nối
    $conn_id = ftp_connect($ftp_server) or die('ERROR connect to server');

    // đăng nhập
    ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die('AutoBot login false');

    // đổi tên filr hoặc thư mục
    $from = '.' . strstr($from, '/' . $ftp_dir_root . '/');
    $to = '.' . strstr($to, '/' . $ftp_dir_root . '/');

    //
    return ftp_rename($conn_id, $from, $to);
}

function EBE_rename_dir_for_update_code($desc_dir)
{
    //die($desc_dir);
    if (!is_dir($desc_dir)) {
        return false;
    }

    // đổi tên thư mục cũ
    $myoldfolder = rtrim($desc_dir, '/');
    $myoldfolder = str_replace('-master', '', $myoldfolder);
    //die($myoldfolder);

    //
    $mynewfolder = '';
    if (is_dir($myoldfolder)) {
        $mynewfolder = $myoldfolder . '-' . date('Ymd-His');
        if (EBE_try_ftp_rename($myoldfolder, $mynewfolder) === false) {
            return false;
        }
    }

    // đổi tên thư mục mới
    if (EBE_try_ftp_rename($desc_dir, $myoldfolder) === false) {
        return false;
    }

    // xóa thư mục backup
    if ($mynewfolder != '') {
        //EBE_remove_dir_and_file($mynewfolder);
    }

    //
    //die(EB_THEME_PLUGIN_INDEX);
    //die($desc_dir);
    //die(__FILE__ . ':' . __LINE__);

    //
    return true;
}
