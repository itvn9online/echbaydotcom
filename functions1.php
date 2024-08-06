<?php

// một số host không dùng được hàm end
function _eb_end($arr)
{
    return $arr[count($arr) - 1];
}

function _eb_last($arr)
{
    return _eb_end($arr);
}

function _eb_begin($arr)
{
    return $arr[0];
}

function _eb_first($arr)
{
    return _eb_begin($arr);
}

function _eb_add_css_js_file($arr, $file_type = '.css', $include_now = 0, $include_url = EB_URL_OF_THEME)
{
    global $localhost;
    global $__cf_row;

    //
    //		$include_now = 1;
    //
    //		echo basename( get_template_directory() ) . '<br>';
    //
    $check_time = 120;
    if (eb_code_tester == true && $include_now == 0) {
        $check_time = 5;
    } else if ($include_now == 1 || $localhost == 1) {
        //		else if ( eb_code_tester == true ) {
        //			print_r($arr);
        //			echo EB_THEME_THEME . "\n";
        // add trực tiếp file JS nếu làm việc trên lcoalhost -> để còn debug
        if ($file_type == '.js') {
            foreach ($arr as $v) {
                $f = EB_THEME_THEME . 'javascript/' . $v;
                //					echo $f . "\n";
                // nếu file không tồn tại -> kiểm tra trong plugin
                if (!is_file($f)) {
                    $v = EB_URL_OF_PLUGIN . $v;
                } else {
                    $v = EB_URL_OF_THEME . 'javascript/' . $v;
                }

                //
                echo '<script type="text/javascript" src="' . $v . '?v=' . web_version . '"></script>' . "\n";

                // v2
                //					echo _eb_import_js( $v . '?v=' . web_version );
            }
        } else {
            foreach ($arr as $v) {
                $f = EB_THEME_THEME . 'css/' . $v;
                //					echo $f . "\n";
                // nếu file không tồn tại -> kiểm tra trong plugin
                if (!is_file($f)) {
                    $v = EB_URL_OF_PLUGIN . $v;
                } else {
                    $v = EB_URL_OF_THEME . 'css/' . $v;
                }

                //
                echo '<link rel="stylesheet" href="' . $v . '?v=' . web_version . '" type="text/css" />' . "\n";
            }
        }

        //
        return false;
    }


    //		$strCacheFilter = implode( '-', $arr );
    $strCacheFilter = '';
    //		print_r($arr);
    foreach ($arr as $v) {
        $strCacheFilter .= _eb_end(explode('/', $v));
    }
    $strCacheFilter = preg_replace('/[^a-zA-Z0-9]+/', '-', $strCacheFilter);
    //		echo 'Cache: ' . $strCacheFilter . '<br>'; exit();
    //
    $f_content = _eb_get_static_html($strCacheFilter, '', '', $check_time);
    if ($f_content == false) {
        $f_content = '';

        //
        $f_dir = 'javascript/';
        if ($file_type == '.css') {
            $f_dir = 'css/';
        }

        //
        $f_filename = '';
        foreach ($arr as $v) {
            //				$f_filename .= $v;
            $f_filename .= _eb_end(explode('/', $v));

            //
            $v0 = $v;
            $v = EB_THEME_THEME . $f_dir . $v;

            // nếu file không tồn tại -> kiểm tra trong plugin
            if (!is_file($v)) {
                $v = EB_THEME_PLUGIN_INDEX . $v0;
            }
            //				echo $v . '<br>';
            // thêm cả thời gian chỉnh sửa file, nếu có thay đổi -> tên file sẽ thay đổi
            if (is_file($v)) {
                //					echo date( 'r', filemtime ( $v ) ) . '<br>';
                $f_filename .= filemtime($v);

                //
                //					$f_content .= file_get_contents( $v, 1 );
                $f_content .= '/* //' . $v0 . '// */' . "\n" . file_get_contents($v, 1) . "\n";
            }
        }
        $f_filename = str_replace('.js', '', $f_filename);
        $f_filename = str_replace('.css', '', $f_filename);
        $f_filename = preg_replace('/[^a-zA-Z0-9]+/', '-', $f_filename);
        $f_filename .= $file_type;
        //			echo 'Filename: ' . $f_filename . '<br>';
        //
        $f_save = EB_THEME_CACHE . $f_filename;
        //			echo 'Save: ' . $f_save . '<br>';
        //
        $new_content = '';

        // chỉ lưu file khi chưa tồn tại -> có chỉnh sửa mới cần phải lưu
        if (eb_code_tester == true || !is_file($f_save)) {

            // thu gọn dữ liệu file css (nếu có)
            //				if ( $compiler == 1 ) {
            //
            if ($file_type == '.js') {
                // lưu lại nội dung cũ trước khi xử lý
                $old_content = $f_content;

                // Xử lý nội dung
                $f_content = explode("\n", $f_content);

                // Nội dung đủ nhiều thì mới ghép dòng
                if (count($f_content) > 10) {
                    foreach ($f_content as $v) {
                        $v = trim($v);

                        //
                        if (substr($v, 0, 2) != '//') {
                            if (strpos($v, '//') !== false) {
                                $v .= "\n";
                            }

                            //
                            $new_content .= $v;
                        }
                    }
                }
                // Không thì cứ thế dùng thôi
                else {
                    $new_content = $old_content . "\n";
                }
            }
            // css
            else {
                $new_content = $f_content;

                // google khuyến khích không nên để inline cho CSS
                //$new_content = str_replace( '}', '}' . "\n", $new_content );
                //$new_content = str_replace( '}.', '}' . "\n" . '.', $new_content );
                //
                $split_content = explode("\n", $new_content);
                $new_content = '';
                $str_new = '';
                foreach ($split_content as $v) {
                    $v = trim($v);
                    $str_new .= $v;

                    if (strlen($str_new) > 1200) {
                        $new_content .= $str_new . "\n";

                        $str_new = '';
                    }
                }
                // kết thúc chuỗi, nếu vẫn còn dữ liệu thì phải add thêm vào
                $new_content .= $str_new;

                //
                /*
                  foreach ( $arr_css_new_content as $k => $v ) {
                  $new_content = str_replace( $k, $v, $new_content );
                  }
                 */
                $new_content = EBE_replace_link_in_cache_css($new_content);

                // nội dung in trực tiếp vào html
                $new_content = '<style type="text/css">/* ' . date('r', date_time) . ' */' . $new_content . '</style>';
            }
            /*
              }
              else {
              $new_content = $f_content . "\n";
              }
             */

            //
            //				echo gettype( $new_content ) . '<br>';
            // Tạo nội dung file css
            _eb_create_file($f_save, $new_content);

            //
            _eb_log_admin('Create static file: ' . $strCacheFilter);
        }
        //			exit();
        //
        //		$none_http_url = str_replace( 'http://', '//', content_url() );
        //$none_http_url = EB_DIR_CONTENT;
        //		$none_http_url = basename( EB_THEME_CONTENT );
        //
        if ($file_type == '.js') {

            // tạo nội dung nhúng file css
            $f_url = str_replace(ABSPATH, '', EB_THEME_CACHE) . $f_filename;
            //				echo 'URL: ' . $f_url . '<br>';

            /*
              $f_content = '<script type="text/javascript" src="' . $f_url . '"></script>';
             */

            // thử add js theo cách mới xem sao
            $f_content = _eb_import_js($f_url);
        } else {

            if ($new_content == '') {
                $f_content = file_get_contents($f_save, 1);
            } else {
                // in css ra cùng với html luôn
                $f_content = $new_content;

                // trỏ link tới css
                //				$c = '<link rel="stylesheet" href="' . $f_url . '?v=' . web_version . '" type="text/css" />';
            }
        }

        // lưu nội dung nhúng file
        _eb_get_static_html($strCacheFilter, $f_content, '', $check_time);
    }

    echo $f_content;
}

function _eb_import_js($js)
{
    /* async */
    return '<script type="text/javascript" src="' . $js . '"></script>' . "\n";
    /* */

    /*
      return '<script type="text/javascript">
      (function(d, a) {
      var s = d.createElement(a);
      s.type = "text/javascript";
      s.async = 1;
      s.src = "' . $js . '";
      var m = d.getElementsByTagName(a)[0];
      m.parentNode.insertBefore(s, m);
      }( document, "script" ));
      </script>';
      /* */
}

//
function _eb_replace_css_space($str, $new_array = array())
{
    $arr = array(
        ' { ' => '{',
        '; }.' => ';}.',
        '; }#' => ';}#',
        ';}.' => '}.',
        ';}#' => '}#',
        ': ' => ':',
    );

    //
    //	print_r( $arr );
    //	$arr = array_merge($arr, $new_array);
    //	print_r( $arr );

    foreach ($arr as $k => $v) {
        $str = str_replace($k, $v, $str);
    }

    foreach ($new_array as $k => $v) {
        $str = str_replace($k, $v, $str);
    }
    //	print_r( $new_array );

    return $str;
}

// khi file css nằm trong cache
function EBE_replace_link_in_cache_css($c)
{
    $a = array(
        // IMG của theme
        //		'../images/' => '../../../themes/' . basename( get_template_directory() ) . '/images/',
        '../images/' => '../../../themes/' . basename(EB_THEME_URL) . '/images/',

        // IMG của plugin tổng
        //		'../../images-global/' => EB_URL_OF_PLUGIN . 'images-global/',
        '../../images-global/' => '../../../echbaydotcom/images-global/',
        //		'../images-global/' => EB_URL_OF_PLUGIN . 'images-global/',
        '../images-global/' => '../../../echbaydotcom/images-global/',

        // các css ngoài -> trong outsource -> vd: font awesome
        '../outsource/' => '../../../themes/' . basename(EB_URL_TUONG_DOI) . '/outsource/',
        //'../outsource/' => '../../../themes/echbaytwo/outsource/',
        //'../fonts/' => '../../../themes/echbaytwo/outsource/fonts/'
    );

    // IMG của child theme
    if (using_child_wgr_theme == 1) {
        $a['../images-child/'] = '../../../themes/' . basename(EB_CHILD_THEME_URL) . '/images-child/';
    }

    //
    return _eb_replace_css_space($c, $a);
}

// khi css thuộc dạng inline (hiển thị trực tiếp trong HTML)
function EBE_replace_link_in_css($c)
{
    $a = array(
        // IMG của theme
        //		'../images/' => './' . EB_DIR_CONTENT . '/themes/' . basename( get_template_directory() ) . '/images/',
        '../images/' => './' . EB_DIR_CONTENT . '/themes/' . basename(EB_THEME_URL) . '/images/',

        // IMG của plugin tổng
        //		'../../images-global/' => EB_URL_OF_PLUGIN . 'images-global/',
        '../../images-global/' => './' . EB_DIR_CONTENT . '/echbaydotcom/images-global/',
        //		'../images-global/' => EB_URL_OF_PLUGIN . 'images-global/',
        '../images-global/' => './' . EB_DIR_CONTENT . '/echbaydotcom/images-global/',

        // các css ngoài -> trong outsource -> vd: font awesome
        '../outsource/' => './' . EB_URL_TUONG_DOI . 'outsource/',
        //'../outsource/' => './' . EB_DIR_CONTENT . '/themes/echbaytwo/outsource/',
        //'../fonts/' => './' . EB_DIR_CONTENT . '/themes/echbaytwo/outsource/fonts/'
    );

    // IMG của child theme
    if (using_child_wgr_theme == 1) {
        $a['../images-child/'] = './' . EB_DIR_CONTENT . '/themes/' . basename(EB_CHILD_THEME_URL) . '/images-child/';
    }

    //
    return _eb_replace_css_space($c, $a);
}

function WGR_remove_css_multi_comment($a)
{

    $a = explode('*/', $a);
    $str = '';
    foreach ($a as $v) {
        $v = explode('/*', $v);
        $str .= $v[0];
    }

    //
    $a = explode("\n", $str);
    $str = '';
    foreach ($a as $v) {
        $v = trim($v);
        if ($v != '') {
            $str .= $v;
        }
    }

    // bỏ các ký tự thừa nhiều nhất có thể
    $str = str_replace('; }', '}', $str);
    $str = str_replace(';}', '}', $str);
    $str = str_replace(' { ', '{', $str);
    $str = str_replace(' {', '{', $str);
    $str = str_replace(', .', ',.', $str);
    $str = str_replace(', #', ',#', $str);
    $str = str_replace(': ', ':', $str);
    $str = str_replace('} .', '}.', $str);

    // chuyển đổi tên màu sang mã màu
    $arr_colorname_to_code = [
        'transparent' => '00000000',
        'red' => 'ff0000',
        'darkred' => '8b0000',
        'black' => '000000',
        'white' => 'ffffff',
        'blue' => '0000ff',
        'darkblue' => '00008b',
        'green' => '008000',
        'darkgreen' => '006400',
        'orange' => 'ffa500',
        'darkorange' => 'ff8c00',
    ];
    foreach ($arr_colorname_to_code as $k => $v) {
        $str = str_replace(':' . $k . '}', ':#' . $v . '}', $str);
        $str = str_replace(':' . $k . ';', ':#' . $v . ';', $str);

        // !important
        $str = str_replace(':' . $k . ' !', ':#' . $v . ' !', $str);
        $str = str_replace(':' . $k . '!', ':#' . $v . '!', $str);
    }

    // thay url ảnh của child theme thành url tuyệt đối
    /*
    if (using_child_wgr_theme == 1) {
		return _eb_replace_css_space( $str, array(
			'../images-child/' => '../../../themes/' . basename(EB_CHILD_THEME_URL) . '/images-child/'
		) );
		
//		$str = str_replace('../../images-child/', str_replace('\\', '/', str_replace(ABSPATH, web_link, EB_CHILD_THEME_URL)) . 'images-child/', $str);
//		$str = str_replace('../../image-child/', str_replace('\\', '/', str_replace(ABSPATH, web_link, EB_CHILD_THEME_URL)) . 'image-child/', $str);
    }
	*/

    //
    return $str;
}

function WGR_remove_js_comment($a, $chim = false)
{
    $a = explode("\n", $a);

    $str = '';
    foreach ($a as $v) {
        $v = trim($v);

        if ($v == '' || substr($v, 0, 2) == '//') {
        } else {
            // thêm dấu xuống dòng với 1 số trường hợp
            if ($chim == true || strpos($v, '//') !== false || substr($v, -1) == '\\') {
                $v .= "\n";
            }
            $str .= $v;
        }
    }

    // loại bỏ khoảng trắng
    $arr = array(
        ' ( ' => '(',
        ' ) ' => ')',
        '( \'' => '(\'',
        '\' )' => '\')',

        '\' + ' => '\'+',
        ' + \'' => '+\'',

        ' == ' => '==',
        ' != ' => '!=',
        ' || ' => '||',
        ' === ' => '===',

        ' () ' => '()',
        ' && ' => '&&',
        '\' +\'' => '\'+\'',
        ' += ' => '+=',
        '+ \'' => '+\'',
        '; i < ' => ';i<',
        'var i = 0;' => 'var i=0;',
        '; i' => ';i',
        ' = \'' => '=\''
    );

    foreach ($arr as $k => $v) {
        $str = str_replace($k, $v, $str);
    }

    //
    return $str;
}

function WGR_remove_js_multi_comment($a, $begin = '/*', $end = '*/')
{

    $str = $a;

    $b = explode($begin, $a);
    $a = explode($end, $a);

    // nếu số thẻ đóng với thẻ mở khác nhau -> hủy luôn
    if (count($a) != count($b)) {
        return $str;
        //		return _eb_str_block_fix_content( $str );
    }

    //
    $str = '';

    //
    foreach ($a as $v) {
        $v = explode($begin, $v);
        $str .= $v[0];
    }

    return $str;
    //	return _eb_str_block_fix_content( $str );
}

// add css thẳng vào HTML
function _eb_add_compiler_css($arr)
{
    global $__cf_row;


    //	print_r( $arr );
    /*
      if ( $__cf_row['cf_css_optimize'] == 1 ) {
		  _eb_add_compiler_v2_css( $arr, 0 );
		  return true;
      }
     */


    /*
      // nếu là dạng tester -> chỉ có 1 kiểu add thôi
      if ( eb_code_tester == true ) {
		  _eb_add_compiler_v2_css( $arr );
      }
      // sử dụng thật thì có 2 kiểu add: inline và add link
      else {
     */
    // nếu là dạng tester -> chỉ có 1 kiểu add thôi -> nhúng link CSS
    //		if ( eb_code_tester == true ) {
    // Nếu không có lệnh opimize CSS -> nhúng thẳng link vào
    if ($__cf_row['cf_css_optimize'] != 1) {
        // v1
        _eb_add_compiler_v2_css($arr);
        return true;

        // v2
        echo '<!-- CSS node 0 -->' . "\n";
        _eb_add_compiler_v2_css($new_arr1);

        echo '<!-- CSS node 1 -->' . "\n";
        _eb_add_compiler_v2_css($new_arr2);
    }
    // sử dụng thật thì có 2 kiểu add: inline và add link
    else {

        // v1
        $new_arr1 = array();
        $new_arr2 = array();

        //
        //		print_r( $arr );
        foreach ($arr as $k => $v) {
            //echo $v . "\n";
            if ($v * 1 === 1) {
                if ($__cf_row['cf_css2_inline'] == 1) {
                    $new_arr2[$k] = 1;
                    $arr[$k] = 9;
                }
            } else {
                if ($__cf_row['cf_css_inline'] == 1) {
                    $new_arr1[$k] = 1;
                    $arr[$k] = 9;
                }
            }
        }
        //print_r( $new_arr1 );
        //print_r( $new_arr2 );
        //$arr = array_merge( $new_arr1, $new_arr2 );
        //print_r( $arr );
        //echo count( $arr ) . "\n";


        // chức năng load nội dung file trực tiếp giống wordpress
        $file_name = array();
        $file2_name = array();
        //print_r( $arr );
        foreach ($arr as $k => $v) {
            /*
            if ( is_file($k) ) {
            	echo $k . ' -> ' . $v . "\n";
            }
            */
            if ($v * 1 < 9 && is_file($k)) {
                //				echo $k . ' -> ' . $v . "\n";

                // nếu trong thư mục mặc định -> lấy tên file là đủ
                /*
                if ( strpos( $k, 'echbaydotcom/css' ) !== false
                || strpos( $k, 'echbaydotcom/html/details' ) !== false
                || strpos( $k, 'echbaydotcom/html/search' ) !== false
                || strpos( $k, 'echbaydotcom/themes/css' ) !== false ) {
                	$file_name[] = basename($k);
                }
                */
                if (strpos($k, 'echbaydotcom/') !== false) {
                    $file_name[] = urlencode(strstr(strstr($k, 'echbaydotcom/'), '/'));
                } else {
                    //					$file_name[] = urlencode( strstr( $k, '/' . EB_DIR_CONTENT ) );
                    $file_name[] = urlencode(strstr($k, 'themes/'));
                }
            } else {
                $file2_name[] = $k;
            }
        }
        //		echo WP_CONTENT_DIR . "\n";
        //		echo EB_DIR_CONTENT . "\n";
        //		echo EB_THEME_CONTENT . "\n";
        //		print_r( $file_name );
        //		echo count( $file_name ) . "\n";
        //		print_r( $file2_name );
        //		echo count( $file2_name ) . "\n";

        // nhúng phần CSS inline trực tiếp vào website
        _eb_add_compiler_v2_css($new_arr1);

        if (!empty($file_name)) {
            echo '<link rel="stylesheet" href="' . strstr(EB_THEME_PLUGIN_INDEX, EB_DIR_CONTENT) . 'load-styles.php?load=' . implode(',', $file_name) . '&ver=' . web_version . '" type="text/css" media="all" />' . "\n";
        }

        // nhúng phần CSS inline trực tiếp vào website
        _eb_add_compiler_v2_css($new_arr2);

        //
        return true;


        // cho vào 1 file để giảm request
        //_eb_add_compiler_v2_css( array_merge( $new_arr1, $new_arr2 ), 0 ); return true;
        // nhúng nội dung file css
        if ($__cf_row['cf_css_inline'] == 1) {
            _eb_add_compiler_v2_css($new_arr1);
        }
        // hoặc add link (tùy chọn ngâm cứu)
        else {
            _eb_add_compiler_v2_css($new_arr1, 0);
        }

        // nhúng link CSS (file 2)
        if ($__cf_row['cf_css2_inline'] == 1) {
            _eb_add_compiler_v2_css($new_arr2);
        } else {
            _eb_add_compiler_v2_css($new_arr2, 0);
        }
    }
    //}
}

function _eb_add_compiler_v2_css($arr, $css_inline = 1)
{
    if (empty($arr)) {
        return false;
    }

    global $__cf_row;

    // nhúng link trực tiếp
    //	if ( eb_code_tester == true ) {
    if ($__cf_row['cf_css_optimize'] != 1) {
        $content_dir = basename(WP_CONTENT_DIR);
        //		echo $content_dir . "\n";

        //		$ver = web_version;

        foreach ($arr as $v => $k) {
            // chỉ add file có trong host
            if (is_file($v)) {
                $ver = filemtime($v);

                //				echo ABSPATH . "\n";
                //				$v = str_replace( ABSPATH, '', $v );
                $v = str_replace('\\', '/', strstr($v, $content_dir));

                echo '<link rel="stylesheet" href="' . $v . '?ver=' . $ver . '" type="text/css" media="all" />' . "\n";
            }
        }

        //
        return true;
    }


    /*
      echo '<!-- ';
      print_r( $arr );
      echo $css_inline . '<br>' . "\n";
      echo ' -->';
     */


    // nhúng link đã qua cache
    if ($css_inline != 1) {
        $file_cache = '';
        $full_file_name = '';
        $new_arr = array();
        foreach ($arr as $v => $k) {
            // chỉ add file có trong host
            if (is_file($v)) {
                // lấy tên file
                $file_name = basename($v, '.css');
                //				echo $file_name . '<br>' . "\n";
                //
                $full_file_name .= '+' . $file_name;

                // thời gian cập nhật file
                /*
                  $file_time = filemtime ( $v );
                  //				$file_time = '-' . substr( filemtime ( $v ), 6 );
                  $file_time = $file_name . substr( $file_time, strlen($file_time) - 3 );

                  //				$file_cache .= $file_name . $file_time;
                  $file_cache .= $file_time;
                 */
                $file_cache .= $file_name;

                $new_arr[$v] = 1;
            }
        }

        //
        //		echo $file_cache . '<br>' . "\n";
        $file_cache = md5($file_cache);
        $file_show = $file_cache;

        // thêm khoảng thời gian lưu file
        $current_server_minute = (int)substr(date('i', date_time), 0, 1);
        //		$current_server_minute = ceil( date( 'i', date_time ) );
        $file_cache = 'zss-' . $file_cache . '-' . $current_server_minute . '.css';

        // file hiển thị sẽ hiển thị sớm hơn chút
        /*
          if ( $current_server_minute == 0 ) {
          $current_server_minute = 5;
          }
          else {
         */
        $current_server_minute = $current_server_minute - 1;
        //		}
        $file_show = 'zss-' . $file_show . '-' . $current_server_minute . '.css';


        $file_save = EB_THEME_CACHE . 'noclean/' . $file_cache;
        //		echo $file_save . "\n";
        // nếu chưa -> tạo file cache
        //		if ( ! is_file( $file_save ) ) {
        // tạo file cache định kỳ
        if (!is_file($file_save) || date_time - filemtime($file_save) + rand(0, 30) > 500) {
            $cache_content = '';
            foreach ($new_arr as $v => $k) {
                $file_content = explode("\n", file_get_contents($v, 1));

                foreach ($file_content as $v2) {
                    $v2 = trim($v2);
                    $cache_content .= $v2;
                }
            }

            //
            //			$cache_content = WGR_remove_css_multi_comment($cache_content);
            $cache_content = EBE_replace_link_in_cache_css($cache_content);

            //
            _eb_create_file($file_save, create_cache_infor_by($full_file_name) . $cache_content);

            // chưa có file phụ -> tạo luôn file phụ
            if (!is_file(EB_THEME_CACHE . 'noclean/' . $file_show)) {
                if (copy($file_save, EB_THEME_CACHE . 'noclean/' . $file_show)) {
                    chmod(EB_THEME_CACHE . 'noclean/' . $file_show, 0777);
                }
            }

            // cập nhật lại version để css mới nhận nhanh hơn
            //			_eb_set_config( 'cf_web_version', date( 'md.Hi', date_time ), 0 );
        }

        // -> done
        echo '<!-- ' . $file_cache . ' --><link rel="stylesheet" href="' . str_replace(ABSPATH, '', EB_THEME_CACHE) . 'noclean/' . $file_show . '?v=' . web_version . '" type="text/css" media="all" />';

        //
        return true;
    }


    // nhúng nội dung file
    echo '<style type="text/css">';

    //
    foreach ($arr as $v => $k) {
        // chỉ add file có trong host
        if (is_file($v)) {
            // lấy tên file
            $file_name = basename($v, '.css');
            //			echo $file_name . '<br>' . "\n";
            // thời gian cập nhật file
            $file_time = filemtime($v);
            //			$file_time = date( 'Ymd-Hi', filemtime ( $v ) );
            //			echo $file_time . '<br>' . "\n";
            // -> tên file trong cache
            $file_cache = $file_name . $file_time . '.css';
            //			echo $file_cache . '<br>' . "\n";
            // nơi lưu file cache
            $file_save = EB_THEME_CACHE . $file_cache;
            //			echo $file_save . '<br>' . "\n";
            // nếu chưa -> tạo file cache
            if (!is_file($file_save)) {
                $file_content = explode("\n", file_get_contents($v, 1));

                //
                //				$cache_content = '/* ' . $file_cache . ' - ' . date( 'r', date_time ) . ' */' . "\n";
                $cache_content = '';

                foreach ($file_content as $v2) {
                    $v2 = trim($v2);
                    $cache_content .= $v2;
                }

                //
                //				$cache_content = WGR_remove_css_multi_comment($cache_content);
                $cache_content = EBE_replace_link_in_css($cache_content);

                //
                _eb_create_file($file_save, $cache_content);
            }

            // 
            //			echo '/* ' . $file_cache . ' */' . "\n";
            // inline
            echo file_get_contents($file_save, 1) . "\n";
        }
    }

    //
    echo '</style>';
}

// add css dưới dạng <link>
function _eb_add_compiler_link_css($arr)
{
    global $__cf_row;

    //
    foreach ($arr as $v) {
        // nếu có file -> compiler lại và cho vào cache
        if (is_file($v)) {
            // lấy tên file
            $file_name = basename($v, '.css');
            //				echo $file_name . '<br>' . "\n";
            // thời gian cập nhật file
            $file_time = date('Ymd-Hi', filemtime($v));
            //				echo $file_time . '<br>' . "\n";
            // -> tên file trong cache
            $file_cache = $file_name . $file_time . '.css';
            //				echo $file_cache . '<br>' . "\n";
            // nơi lưu file cache
            $file_save = EB_THEME_CACHE . $file_cache;
            //				echo $file_save . '<br>' . "\n";
            // nếu chưa -> tạo file cache
            if (!is_file($file_save)) {
                $file_content = explode("\n", file_get_contents($v, 1));
                $cache_content = '/* ' . date('r', date_time) . ' */' . "\n";

                foreach ($file_content as $v2) {
                    $v2 = trim($v2);
                    $cache_content .= $v2;
                }

                //
                _eb_create_file($file_save, EBE_replace_link_in_cache_css($cache_content));
            }

            //
            //			$file_link = $__cf_row['cf_dns_prefetch'] . EB_DIR_CONTENT . '/uploads/ebcache/' . $file_cache;
            //			$file_link = web_link . EB_DIR_CONTENT . '/uploads/ebcache/' . $file_cache;
            $file_link = str_replace(ABSPATH, '', EB_THEME_CACHE) . $file_cache;

            // -> done
            echo '<link rel="stylesheet" href="' . $file_link . '" type="text/css" media="all" />' . "\n";
        }
        // nếu không -> include trực tiếp
        else {
            echo '<link rel="stylesheet" href="' . $v . '" type="text/css" media="all" />';
        }
    }
}

// Thiết lập hàm hiển thị logo
function _eb_echbay_logo()
{
    echo '<p><a href="' . web_link . '" title="' . get_bloginfo('description') . '">' . web_name . '</a></p>';
}

/*
 * Thiết lập hàm hiển thị menu
 * https://developer.wordpress.org/reference/functions/wp_nav_menu/
 * tag_menu_name: nếu muốn lấy cả tên menu thì gán thêm hàm này vào
 * tag_close_menu_name: thẻ đóng html của tên menu
 */

function _eb_echbay_menu($slug, $menu = array(), $in_cache = 1, $tag_menu_name = '', $tag_close_menu_name = '</div>')
{
    //	global $wpdb;
    global $menu_cache_locations;
    global $__cf_row;

    /*
      $strCacheFilter = 'menu-' . $slug;

      //
      if ( $in_cache == 0 ) {
      $a = false;
      } else {
      $a = _eb_get_static_html ( $strCacheFilter );
      }

      //
      if ( $a == false ) {
     */

    // mặc định mọi menu đều dùng UL và class cf
    $menu['container'] = 'ul';

    //
    $menu['theme_location'] = $slug;
    $menu['container_class'] = $slug;
    $menu['echo'] = false;
    //		$menu['show_home'] = true;
    // mặc định là thêm cf vào để có thể float cho menu
    if (!isset($menu['menu_class'])) {
        $menu['menu_class'] = 'cf';
    }
    $menu['menu_class'] .= ' eb-set-menu-selected ' . $slug;

    //
    // print_r($menu_cache_locations);
    if (!isset($menu_cache_locations[$slug])) {
        $menu_cache_locations[$slug] = 0;
    }
    // print_r($menu_cache_locations);

    // lấy tên menu nếu có yêu cầu
    $menu_name = '';
    if ($tag_menu_name != '') {
        // $menu_cache_locations = get_nav_menu_locations();

        if (isset($menu_cache_locations[$slug])) {
            $menu_obj = wp_get_nav_menu_object($menu_cache_locations[$slug]);
            //				print_r($menu_obj);

            if (isset($menu_obj->name)) {
                $menu_name = $tag_menu_name . $menu_obj->name . $tag_close_menu_name;
            }
        }
    }

    //
    $a = wp_nav_menu($menu);

    // nếu có chuỗi /auto.get_all_category/ -> đây là menu tự động -> lấy toàn bộ category
    if (strpos($a, '/auto.get_all_category/') !== false) {
        // lấy danh sách danh mục
        $all_cats = EBE_echbay_category_menu();

        // class cho menu
        $menu_slug_class = str_replace(' ', '-', $slug);

        // các mẫu danh mục khác nhau
        if (strpos($a, '/auto.get_all_category/bars/') !== false) {
            $a = '
<div class="all-category-hover ' . $menu_slug_class . '-hover">
	<div class="all-category-bars cur ' . $menu_slug_class . '-bars"><i class="fas fa-bars"></i> Danh mục</div>
	<div class="all-category-cats ' . $menu_slug_class . '-cats">' . $all_cats . '</div>
</div>';
        } else if (strpos($a, '/auto.get_all_category/caret/') !== false) {
            $a = '
<div class="all-category-hover ' . $menu_slug_class . '-hover">
	<div class="all-category-bars cur ' . $menu_slug_class . '-bars"><i class="fas fa-bars"></i> Danh mục <i class="fa fa-caret-down"></i></div>
	<div class="all-category-cats ' . $menu_slug_class . '-cats">' . $all_cats . '</div>
</div>';
        }
        // thêm nút trang chủ
        else if (strpos($a, '/auto.get_all_category/home/') !== false) {
            $a = str_replace('<!-- ul:before -->', '<li><div><a href="./"><i class="fas fa-home"></i> ' . EBE_get_lang('home') . '</a></div></li>', $all_cats);
        }
        // thêm icon trang chủ
        else if (strpos($a, '/auto.get_all_category/home_icon/') !== false) {
            $a = str_replace('<!-- ul:before -->', '<li><div><a href="./"><i class="fas fa-home"></i></a></div></li>', $all_cats);
        } else {
            $a = $all_cats;
        }
    }
    // mặc định với menu thông thường
    else {
        // xóa các ID và class trong menu
        $a = preg_replace('/ id=\"menu-item-(.*)\"/iU', '', $a);
        $a = preg_replace('/ class=\"menu-item (.*)\"/iU', '', $a);

        //
        $a = str_replace('http://./', './', $a);
        $a = str_replace('https://./', './', $a);

        // xóa ký tự đặc biệt khi rút link category
        $a = str_replace('/./', '/', $a);
        //			$a = str_replace( '/category/', '/', $a );
    }

    /*
      _eb_get_static_html ( $strCacheFilter, $a );
      }
     */

    // nhập một số dữ liệu từ config
    $arr = array(
        'cf_diachi' => $__cf_row['cf_diachi'],
        'cf_email' => $__cf_row['cf_email'],
        'cf_dienthoai' => $__cf_row['cf_dienthoai'],
        'cf_hotline' => $__cf_row['cf_hotline']
    );
    foreach ($arr as $k => $v) {
        // temp mặc định
        $a = str_replace('{tmp.' . $k . '}', $v, $a);
        // temp trong menu
        $a = str_replace('%tmp.' . $k . '%', $v, $a);
        $a = str_replace('%%' . $k . '%%', $v, $a);
        $a = str_replace('%' . $k . '%', $v, $a);
    }

    //
    /*
      if ( $__cf_row['cf_replace_content'] != '' ) {
      $a = WGR_replace_for_all_content( $__cf_row['cf_replace_content'], $a );
      }
     */
    if ($__cf_row['cf_old_domain'] != '') {
        $a = WGR_sync_old_url_in_content($__cf_row['cf_old_domain'], $a);
    }

    // trả về menu và URL tương đối
    // return '<!-- menu slug: ' . $slug . ' --><div data-id="' . $menu_cache_locations[$slug] . '" class="each-to-edit-menu"></div>' . $menu_name . str_replace(web_link, '', _eb_supper_del_line($a));
    return '<!-- menu slug: ' . $slug . ' --><div data-id="' . $menu_cache_locations[$slug] . '" class="each-to-edit-menu"></div>' . $menu_name . _eb_supper_del_line($a);
}

// load menu theo số thứ tự tăng dần
$i_echbay_top_menu = 0;

function EBE_echbay_top_menu($menu = array(), $in_cache = 1, $tag_menu_name = '', $tag_close_menu_name = '</div>')
{
    global $i_echbay_top_menu;

    $i_echbay_top_menu++;
    if ($i_echbay_top_menu > 6) {
        $i_echbay_top_menu = 6;
    }

    return _eb_echbay_menu(
        'top-menu-0' . $i_echbay_top_menu,
        $menu,
        $in_cache,
        $tag_menu_name,
        $tag_close_menu_name
    );
}

$i_echbay_footer_menu = 0;

function EBE_echbay_footer_menu($menu = array(), $in_cache = 1, $tag_menu_name = '', $tag_close_menu_name = '</div>')
{
    global $i_echbay_footer_menu;

    $i_echbay_footer_menu++;
    if ($i_echbay_footer_menu > 10) {
        $i_echbay_footer_menu = 10;
    }

    return _eb_echbay_menu(
        'footer-menu-0' . $i_echbay_footer_menu,
        $menu,
        $in_cache,
        $tag_menu_name,
        $tag_close_menu_name
    );
}

// Lấy toàn bộ danh sách category rồi hiển thị thành menu
function EBE_echbay_category_menu(
    // taxonomy mặc định
    $cat_type = 'category',
    // nhóm cha mặc định -> mặc định lấy nhóm cấp 1
    $cat_ids = 0,
    // class riêng (nếu có)
    $ul_class = 'eball-category-main',
    // có lấy nhóm con hay không -> mặc định là có
    $get_child = 1,
    //  thẻ theo yêu cầu (tùy vào seoer muốn thẻ gì thì truyền vào)
    $dynamic_tags = 'div'
) {

    //
    $arrs_cats = array(
        'taxonomy' => $cat_type,
        //		'hide_empty' => 0,
        'parent' => $cat_ids,
    );
    // lấy toàn bộ danh mục để làm design ở chế độ debug
    if (eb_code_tester == true) {
        $arrs_cats['hide_empty'] = 0;
    }

    //
    $arrs_cats = get_categories($arrs_cats);
    //	print_r($arrs_cats);
    if (count($arrs_cats) == 0) {
        // nếu đang là nhóm cấp 1 -> trả về thông báo
        if ($cat_ids == 0) {
            return '<!-- no ' . $cat_type . ' detected -->';
        }
        // nếu từ nhóm cấp 2 trở đi -> trả về NULL
        else {
            return '';
        }
    }


    // Nếu đang là lấy nhóm cấp 1
    if ($cat_ids == 0) {
        // Thử kiểm tra xem trong này có nhóm nào được set là nhóm chính không
        $post_primary_categories = array();
        //		print_r( $post_categories );
        foreach ($arrs_cats as $v) {
            if (_eb_get_cat_object($v->term_id, '_eb_category_primary', 0) > 0) {
                $post_primary_categories[] = $v;
            }
        }
        //		print_r( $post_primary_categories );
        // nếu có nhóm chính -> tiếp theo chỉ lấy các nhóm chính
        if (count($post_primary_categories) > 0) {
            $arrs_cats = $post_primary_categories;
        }
        //		print_r($arrs_cats);
    }


    // sắp xếp mảng theo chủ đích của người dùng
    $oders = WGR_order_and_hidden_taxonomy($arrs_cats, 1);
    /*
      $oders = array();
      $options = array();

      //
      foreach ( $arrs_cats as $v ) {
      $oders[ $v->term_id ] = (int) _eb_get_cat_object( $v->term_id, '_eb_category_order', 0 );
      $options[$v->term_id] = $v;
      }
      arsort( $oders );
     */


    //
    $str = '';
    //	foreach ( $arrs_cats as $v ) {
    foreach ($oders as $k => $v) {

        //
        //		$v = $options[$k];
        // không lấy nhóm có ID là 1
        if ($v->term_id != 1) {
            $str_child = '';
            if ($get_child == 1) {
                $str_child = EBE_echbay_category_menu(
                    $v->taxonomy,
                    $v->term_id,
                    'sub-menu',
                    'div'
                );
            }

            // lấy ảnh đại diện nhỏ đối với các nhóm cấp 1
            $cat_favicon = '';
            if ($cat_ids == 0) {
                $cat_favicon = _eb_get_cat_object($v->term_id, '_eb_category_favicon');
                if ($cat_favicon != '') {
                    $cat_favicon = '<span class="eball-category-icon" style="background-image:url(\'' . $cat_favicon . '\');"></span>';
                }
            }

            //
            $str .= '<li>' . $cat_favicon . '<' . $dynamic_tags . '><a href="' . _eb_cs_link($v) . '">' . $v->name . '<span class="eball-category-count"> (' . $v->count . ')</span></a></' . $dynamic_tags . '>' . $str_child . '</li>';
        }
    }

    // nếu là lấy nhóm cha -> thêm thuộc tính thêm chuỗi vào đầu và cuối menu
    if ($cat_ids == 0) {
        return '<ul class="cf ' . $ul_class . '"><!-- ul:before -->' . $str . '<!-- ul:after --></ul>';
    }
    // với sub-menu thì trả về menu không thôi
    else {
        return '<ul class="cf ' . $ul_class . '">' . $str . '</ul>';
    }
}

/*
 * tags: sidebar, widget
 * https://codex.wordpress.org/Function_Reference/dynamic_sidebar
 */

function _eb_echbay_get_sidebar($slug)
{
    global $arr_for_show_html_file_load;

    $arr_for_show_html_file_load[] = '<!-- sidebar: ' . $slug . ' -->';

    ob_start();

    dynamic_sidebar($slug);

    $a = ob_get_contents();

    /*
      ob_clean();
      ob_end_flush();
     */
    ob_end_clean();

    return trim($a);
}

function _eb_echbay_sidebar($slug, $css = '', $div = 'div', $in_cache = 1, $load_main_sidebar = 1, $return_null = 0)
{
    global $arr_for_show_html_file_load;

    /*
      $strCacheFilter = 'sidebar-' . $slug;

      //
      if ( $in_cache == 0 ) {
      $a = false;
      } else {
      $a = _eb_get_static_html ( $strCacheFilter );
      }

      //
      if ( $a == false ) {
     */

    //
    $a = _eb_echbay_get_sidebar($slug);

    // xóa ký tự đặc biệt khi rút link category
    if ($a == '') {
        $a = '<!-- Sidebar ' . $slug . ' is NULL -->';
        $arr_for_show_html_file_load[] = $a;

        // cho phép load từ main sidebar nếu không có kết quả
        if (
            $load_main_sidebar == 1
            // nếu không phải sidebar mặc định -> lấy sidebar mặc định luôn
            &&
            $slug != id_default_for_get_sidebar
        ) {
            $a = _eb_echbay_sidebar(id_default_for_get_sidebar, $css, $div, $in_cache);
        } else if ($return_null == 1) {
            $a = '';
        }
    } else {
        $a = str_replace('/./', '/', $a);
        //			$a = str_replace( 'div-for-replace', $div, $a );
        //			$a = str_replace( 'class-sidebar-replace', $css, $a );
        //
        //			$a = '<ul class="sidebar-' . $slug . ' eb-sidebar-global-css ' . $css . '">' . $a . '</ul>';
        $a = '<!-- ' . $slug . ' --><div class="sidebar-' . $slug . ' eb-sidebar-global-css ' . $css . '">' . $a . '</div>';
    }

    /*
      _eb_get_static_html ( $strCacheFilter, $a );
      }
     */

    return $a;
}

function _eb_q($str, $type = 1)
{
    global $wpdb;

    //	echo $str . '<br>' . "\n";
    // Không trả về gì cả -> delete, update, insert
    if ($type === 0) {
        //		$wpdb->query( $wpdb->prepare( $str ) );
        $wpdb->query(trim($str));
    }
    // có trả về dữ liệu -> select
    else {
        return $wpdb->get_results(trim($str), OBJECT);
    }

    //
    return false;
}

function _eb_c($str)
{
    $sql = _eb_q($str);

    // v1 -> chạy 1 vòng lặp rồi trả về kết quả
    //	if ( count( $sql ) > 0 ) {
    if (!empty($sql)) {
        //		echo 'aaaaaaaaa';
        //		print_r( $sql );
        $sql = $sql[0];
        //		print_r( $sql );
        foreach ($sql as $v) {
            $a = $v;
        }
        return $a;
    }

    // mặc định trả về 0
    return 0;
}

function _eb_full_url()
{
    return '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// Lưu log error vào file
function _eb_log_file($str)
{
    $_file = EB_THEME_CACHE . 'error_log';

    // Nếu file ko tồn tại -> thử cho ra thư mục gốc
    if (!is_file($_file)) {
        _eb_create_file($_file, 1);
        //			$_file = '../' .$_file;
    }

    // Nếu vẫn không tồn tại -> hủy
    if (!is_file($_file)) {
        die('error_log not found');
    }

    // Đưa nội dung log về 1 dòng
    $str = _eb_del_line($str);
    //		echo $str . '<br />';
    // lưu log
    error_log(date('[d-m-Y H:i:s]', date_time) . '; IP: ' . _eb_i() . '. MYSQL Warning: ' . $str . " --------------------> URL: " . _eb_full_url() . "\n", 3, $_file);

    echo '<p>#44 error.</p>';
}

//
function _eb_sd($arr, $tbl)
{
    $str0 = '';
    $str1 = '';

    foreach ($arr as $k => $v) {
        $str0 .= ',' . $k;
        $str1 .= ',';
        $str1 .= "'" . $v . "'";
    }

    $str0 = substr($str0, 1);
    $str1 = substr($str1, 1);

    _eb_q("INSERT INTO
	" . $tbl . "
	( " . $str0 . " )
	VALUES
	( " . $str1 . " )", 0);

    return true;
}

function _eb_set_data($arr, $tbl)
{
    return _eb_sd($arr, $tbl);
}
