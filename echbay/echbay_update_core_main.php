<?php




//
//print_r( $_GET );
if (isset($_GET['remove_update_running_file'])) {
    _eb_remove_file(EB_THEME_CACHE . 'update_running.txt');
    exit();
}


global $localhost;


include_once ECHBAY_PRI_CODE . 'echbay_compiler_core.php';




//
//if ( mtv_id == 1 ) {
//if ( current_user_can('manage_options') )  {
if (isset($_GET['confirm_eb_process'])) {


    // không cập nhật trên localhost
    if ($localhost == 1) {
        //		if ( strpos( $_SERVER['HTTP_HOST'], 'localhost' ) !== false ) {
        //			echo $_SERVER['HTTP_HOST']; exit();
        //			echo $_SERVER['REQUEST_URI']; exit();

        // nếu thư mục là webgiare thì bỏ qua chế độ cập nhật
        if (strpos($_SERVER['REQUEST_URI'], '/wordpress.org') !== false) {
            echo '<h1>Chế độ cập nhật đã bị vô hiệu hóa bởi coder!</h1>';
            exit();
        }
    }



    // không giới hạn thời gian để download file được lâu hơn
    set_time_limit(0);

    //
    $connect_to_server = isset($_GET['connect_to']) ? $_GET['connect_to'] : '';

    //
    if ($connect_to_server == 'theme') {
        $file_cache_test = EB_THEME_CACHE . 'eb_update_theme.txt';
    } else {
        $file_cache_test = EB_THEME_CACHE . 'eb_update_core.txt';
    }
    //die($file_cache_test);

    //
    $lats_update_file_test = 0;
    if (file_exists($file_cache_test)) {
        $lats_update_file_test = file_get_contents($file_cache_test, 1);
    }

    //
    $time_limit_update = 60;

    //
    if (date_time - $lats_update_file_test > $time_limit_update) {

        // nơi lưu file zip
        $destination_path = EB_THEME_CACHE . 'echbaydotcom.zip';
        //die($destination_path);
        $dir_name_for_unzip_to = 'echbaydotcom-master';

        // thư mục mà code sẽ được giải nén tới
        $dir_unzip_update_to = WP_CONTENT_DIR . '/';
        //$dir_unzip_update_to = EB_THEME_CACHE; // daidq (2022-10-19): update thông qua cache có vẻ phức tạp và nặng nề hơn
        // nếu update theme thì phải thêm path cho themes
        if ($connect_to_server == 'theme') {
            $dir_name_for_unzip_to = 'echbaytwo-master';

            //
            $dir_unzip_update_to .= 'themes/';
            //die($dir_unzip_update_to . ':' . __LINE__);
        }

        // download từ github
        if (!file_exists($destination_path)) {

            // server dự phòng
            $url2_for_download_ebdotcom = '';

            // chọn server để update -> github là thời gian thực
            if ($connect_to_server == 'github') {
                $url_for_download_ebdotcom = 'https://github.com/itvn9online/echbaydotcom/archive/master.zip';
            }
            // cập nhật theme
            else if ($connect_to_server == 'theme') {
                //$url_for_download_ebdotcom = 'https://github.com/itvn9online/echbaytwo/archive/master.zip';
                $url_for_download_ebdotcom = $arr_private_info_setting['url_update_parent_theme'];

                //
                if (isset($arr_private_info_setting['dir_theme_unzip_to'])) {
                    $dir_name_for_unzip_to = $arr_private_info_setting['dir_theme_unzip_to'];
                }
                //die($arr_private_info_setting['parent_theme_default']);
                //die($dir_name_for_unzip_to);
                //die($url_for_download_ebdotcom);
            }
            // server của echbay thì update chậm hơn chút, nhưng tải nhanh hơn -> mặc định
            else {
                // daidq (2023-03-15): bỏ chế độ update tại server VN
                //$url_for_download_ebdotcom = $arr_private_info_setting['site_url'] . 'daoloat/echbaydotcom.zip';
                // -> chỉ dùng bản từ github
                $url_for_download_ebdotcom = 'https://github.com/itvn9online/echbaydotcom/archive/master.zip';

                // thiết lập chế độ download từ server dự phòng
                $url2_for_download_ebdotcom = 'http://api.echbay.com/daoloat/echbaydotcom.zip';
            }

            // TEST
            //$url_for_download_ebdotcom = 'http://api.echbay.com/css/bg/HD-Eagle-Wallpapers.jpg';
            //$destination_path = EB_THEME_CACHE . 'w.jpg';

            // thử download theo cách thông thường
            if (WGR_download($url_for_download_ebdotcom, $destination_path)) {
                chmod($destination_path, 0777);
            }
            // download từ server dự phòng
            else if ($url2_for_download_ebdotcom != '' && WGR_download($url2_for_download_ebdotcom, $destination_path)) {
                chmod($destination_path, 0777);
            }
            // đổi hàm khác xem ok không
            else if (file_put_contents($destination_path, fopen($url_for_download_ebdotcom, 'r'))) {
                chmod($destination_path, 0777);
            }
            // sử dụng cURL để download file qua https
            else if (WGR_copy_secure_file($url_for_download_ebdotcom, $destination_path)) {
                chmod($destination_path, 0777);
            }
            // vẫn không được thì báo lỗi
            else {
                die('<p>URL download: <strong>' . $url_for_download_ebdotcom . '</strong></p>
					<p>SAVE to: <strong>' . $destination_path . '</strong></p>
					<h1>Download file faild!</h1>');
            }
            //exit();

            //
            echo '<div>Download in: <a href="' . $url_for_download_ebdotcom . '" target="_blank">' . $url_for_download_ebdotcom . '</a></div>';
        }
        // nếu có file -> thử kiểm tra file size chưa đủ đô -> xóa luôn
        else if (filesize($destination_path) < 1000) {
            if (unlink($destination_path)) {
                echo '<div>Remove file because file size zero!</div>';
            }
            // xóa lại bằng ftp nếu không xóa được theo cách thông thường
            else if (EBE_ftp_remove_file($destination_path) == true) {
                echo '<div>Remove file via FTP because file size zero!</div>';
            } else {
                echo '<div>Canot remove file with filesize zero!</div>';
            }
        }


        // dir for content
        echo 'Dir content: <strong>' . EB_THEME_CONTENT . '</strong><br>' . "\n";
        //die(__FILE__ . ':' . __LINE__);

        //
        //die(WP_CONTENT_DIR);
        //die(EB_THEME_CACHE);
        //die($dir_name_for_unzip_to);
        //die($dir_unzip_update_to);


        // Giải nén file
        if (file_exists($destination_path)) {

            // kết quả giải nén
            $unzipfile = false;

            //
            if (class_exists('ZipArchive')) {
                echo '<div>Using: <strong>ZipArchive</strong></div>';

                $zip = new ZipArchive;
                if ($zip->open($destination_path) === TRUE) {
                    $zip->extractTo($dir_unzip_update_to);
                    $zip->close();

                    //
                    $unzipfile = true;
                }
            } else {
                echo '<div>Using: <strong>unzip_file (wordpress)</strong></div>';

                $unzipfile = unzip_file($destination_path, $dir_unzip_update_to);
            }

            //
            if ($unzipfile == true) {
                echo '<div>Unzip to: <strong>' . $dir_unzip_update_to . $dir_name_for_unzip_to . '</strong></div>';

                if (!is_dir($dir_unzip_update_to . $dir_name_for_unzip_to)) {
                    echo '<h3 class="redcolor">Unzip faild...</strong></h3>';
                }

                // xóa file của github luôn và ngay
                WGR_remove_github_file($dir_unzip_update_to . $dir_name_for_unzip_to . '/.gitattributes');
                WGR_remove_github_file($dir_unzip_update_to . $dir_name_for_unzip_to . '/.gitignore');

                // v1
                /*
                 if ( file_exists( $dir . '.gitattributes' ) ) {
                 if ( unlink( $dir . '.gitattributes' ) ) {
                 echo '<strong>remove file</strong>: ';
                 }
                 else {
                 echo '<strong>NOT remove file</strong>: ';
                 }
                 echo str_replace( EB_THEME_CONTENT, '', $dir ) . '.gitattributes<br>' . "\n";
                 }
                 if ( $dir_to != '' && file_exists( $dir_to . '.gitattributes' ) ) {
                 if ( _eb_remove_file( $dir_to . '.gitattributes' ) ) {
                 echo '<strong>remove file</strong>: ';
                 }
                 else {
                 echo '<strong>NOT remove file</strong>: ';
                 }
                 echo str_replace( EB_THEME_CONTENT, '', $dir_to ) . '.gitattributes<br>' . "\n";
                 }
                 */
            } else {
                echo '<div>Do not unzip file, update faild!</div>';

                // nếu không unzip được -> có thể do lỗi permission -> xóa đi để tải lại
                if (EBE_ftp_remove_file($destination_path) == true) {
                    echo '<div>Remove zip file via FTP!</div>';
                }
            }

            //
            echo '<br>' . "\n";
            //die(EB_THEME_PLUGIN_INDEX);
            //die(__FILE__ . ':' . __LINE__);
            //die($dir_unzip_update_to . ':' . __LINE__);



            // Bật chế độ bảo trì hệ thống
            $bat_che_do_bao_tri = EB_THEME_CACHE . 'update_running.txt';
            _eb_create_file($bat_che_do_bao_tri, date_time);
            echo '<h2>BẬT chế độ bảo trì website!</h2><br>';

            //
            //die($dir_name_for_unzip_to);
            //die($dir_unzip_update_to . $dir_name_for_unzip_to);

            // bắt đầu cập nhật
            // sử dụng chức năng đổi tên thư mục -> ưu tiên
            if (
                EBE_rename_dir_for_update_code($dir_unzip_update_to . $dir_name_for_unzip_to) === true ||
                // hoặc chuyển từng file
                EBE_update_file_via_ftp($dir_name_for_unzip_to, $dir_unzip_update_to . $dir_name_for_unzip_to) === true
            ) {

                // xóa file download để lần sau còn ghi đè lên
                unlink($destination_path);
                echo '<br><div>Remove zip file after unzip.</div><br>' . "\n";

                // tạo file cache để quá trình này không diễn ra liên tục
                _eb_create_file($file_cache_test, date_time);

                //
                echo '<div id="eb_core_update_all_done"></div>';

                // cho website vào chế độ chờ
                //sleep(15);
                //sleep(5);

                //
                include ECHBAY_PRI_CODE . 'func/config_reset_cache.php';

                // cập nhật lại dữ liệu bảng
                EBE_tao_bang_hoa_don_cho_echbay_wp();
                echo '<strong>Update</strong> WGR table database<br>' . "\n";
            }

            // tắt chế độ bảo trì
            _eb_create_file($bat_che_do_bao_tri, date_time);
            /*
             if ( _eb_remove_file( $bat_che_do_bao_tri ) == true ) {
             echo '<br><h2>TẮT chế độ bảo trì website!</h2>';
             }
             else {
             echo '<br><h2>Không TẮT được chế độ bảo trì! Hãy vào thư mục ebcache và xóa file update_running.txt thủ công.</h2>';
             }
             */
        } else {
            echo '<h3>Không tồn tại file zip để giải nén!</h3>';
        }
    } else {
        echo '<h3>Giãn cách mỗi lần update core tối thiểu là ' . ($time_limit_update / 60) . ' phút</h3>';
    }
} else {

    // Kiểm tra phiên bản trên github
    $strCacheFilter = 'github_version';
    $version_in_github = _eb_get_static_html($strCacheFilter, '', '', 24 * 3600);
    $url_check_version = $arr_private_info_setting['url_check_WGR_version'];
    /*
     $url_check_version = 'https://world.webgiare.org/wp-content/echbaydotcom/VERSION';
     */
    if ($version_in_github == false) {
        $version_in_github = _eb_getUrlContent($url_check_version);
        //			$version_in_github = _eb_getUrlContent( 'https://raw.githubusercontent.com/itvn9online/echbaydotcom/master/VERSION' );
        /*
         $version_in_github = _eb_getUrlContent( 'https://raw.githubusercontent.com/itvn9online/echbaydotcom/master/readme.txt' );
         
         $version_in_github = EBE_get_text_version( $version_in_github );
         */
        $version_in_github = _eb_del_line(strip_tags($version_in_github));

        _eb_get_static_html($strCacheFilter, $version_in_github, '', 60);
    }

    // Phiên bản hiện tại
    //		$version_current = EBE_get_text_version( file_get_contents( EB_THEME_PLUGIN_INDEX . 'readme.txt', 1 ) );
    $version_current = file_get_contents(EB_THEME_PLUGIN_INDEX . 'VERSION', 1);

    //
    if ($version_in_github != $version_current) {
        echo '<h3>* Phiên bản mới nhất <strong>' . $version_in_github . '</strong> đã được phát hành, phiên bản hiện tại của bạn là <strong>' . $version_current . '</strong>!</h3>';
    } else {
        echo '<h3>Xin chúc mừng! Phiên bản <strong>' . $version_current . '</strong> bạn đang sử dụng là phiên bản mới nhất.</h3>';
    }
    //		echo '<br>';

    // Link cập nhật core từ echbay.com
    if ($arr_private_info_setting['parent_theme_default'] == 'echbaytwo') {
        echo '<br><h2><center><a href="#" class="click-connect-to-echbay-update-eb-core">[ Bấm vào đây để cập nhật lại mã nguồn cho echbaydotcom! ]</a></center></h2>';
    }

    // Link cập nhật core từ github
    echo '<p><center><a href="#" class="click-connect-to-github-update-eb-core orgcolor">[ Cập nhật mã nguồn cho echbaydotcom theo thời gian thực! Server quốc tế (GitHub) ]</a></center></p>';

    //
    echo '<p class="bluecolor"><em>' . $url_check_version . '</em></p>';
}

/*	
 }
 else {
 echo 'Supper admin only access!';
 }
 */


//
$last_time_update_eb = filemtime(EB_THEME_PLUGIN_INDEX . 'readme.txt');
$last_time_update_theme_eb = filemtime(EB_THEME_URL . 'index.php');



// thư mục chứa theme hiện tại
$current_theme_dir_update = basename(EB_THEME_URL);
$current_theme_version_update = '';
if (file_exists(EB_THEME_URL . 'VERSION')) {
    $current_theme_version_update = ' (' . file_get_contents(EB_THEME_URL . 'VERSION', 1) . ')';
}

// hỗ trợ cập nhật theme khi sử dụng giao diện có thư mục tên như này
//$enable_theme_dir_update = 'echbaytwo';
$enable_theme_dir_update = $arr_private_info_setting['parent_theme_default'];
