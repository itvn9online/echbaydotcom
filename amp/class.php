<?php


class EchAMPFunction
{
    function amp_remove_attr($str)
    {

        //
        $arr = array(
            'id',
            'class',
            'style',
            'dir',
            'type',
            'border',
            'align',
            'loading',

            // iframe
            'frameborder',
            'scrolling',
            'allowfullscreen',

            //
            'longdesc'
        );

        // xóa từng attr đã được chỉ định
        foreach ($arr as $v) {
            $str = $this->remove_attr($str, ' ' . $v . '="', '"');
            $str = $this->remove_attr($str, " " . $v . "='", "'");
        }


        // xóa các thẻ không còn được hỗ trợ
        $arr = array(
            'style',
            'font'
        );

        //
        foreach ($arr as $v) {
            $str = $this->remove_tag($str, $v);
        }


        //
        return $str;
    }

    function remove_tag($str, $tag)
    {

        // tách mảng theo tag nhập vào
        $c = explode('<' . $tag, $str);
        //		print_r( $c );

        $new_str = '';
        foreach ($c as $k => $v) {

            // bỏ qua mảng số 0
            if ($k > 0) {
                //				echo $v . "\n";
                //				echo strstr( $v, '>' ) . "\n";
                //				echo substr( strstr( $v, '>' ), 1 ) . "\n";

                // lấy từ dấu > trở đi
                $v = strstr($v, '>');

                // bỏ qua dấu > ở đầu
                $v = substr($v, 1);
            }

            //
            $new_str .= $v;
        }

        // xóa thẻ đóng
        $new_str = str_replace('</' . $tag . '>', '', $new_str);

        //
        return $new_str;
    }

    function remove_attr($str, $attr, $end_attr = '"')
    {

        // cắt mảng theo attr nhập vào
        $c = explode($attr, $str);
        //		print_r( $c );

        $new_str = '';
        foreach ($c as $k => $v) {
            // chạy vòng lặp -> bỏ qua mảng đầu tiên
            if ($k > 0) {
                // dữ liệu mới bắt đầu từ đoạn kết thúc trước đó
                $v = strstr($v, $end_attr);

                // cắt bỏ đoạn thừa
                $v = substr($v, strlen($end_attr));
            }

            //
            $new_str .= $v;
        }

        // done
        return $new_str;
    }


    function amp_change_tag($str)
    {

        $arr = array(
            'img' => 'amp-img',
            'iframe' => 'amp-iframe'
        );

        foreach ($arr as $k => $v) {
            $str = $this->change_tag($str, $k, $v);
        }

        //
        $str = str_replace('</iframe>', '', $str);
        // bỏ một số thuộc tính không được hỗ trợ trong AMP
        $str = str_replace(' decoding="async"', '', $str);
        $str = str_replace(" decoding='async'", '', $str);

        //
        return $str;
    }

    function change_tag($str, $tag, $new_tag, $end_tag = '>')
    {
        global $other_amp_cdn;

        //
        $c = explode('<' . $tag . ' ', $str);
        //		print_r( $c );

        $new_str = '';
        foreach ($c as $k => $v) {

            // bỏ qua mảng số 0
            if ($k > 0) {
                $v2 = explode('>', $v);
                $v2 = $v2[0];
                //			echo $v2. "\n";
                //			echo substr( $v2, -1 ) . "\n";
                //			echo substr( $v2, 0, -1 ) . "\n";

                // xóa đoạn
                $v = str_replace($v2, '', $v);
                $v = substr($v, 1);

                //
                if (substr($v2, -1) == '/') {
                    $v2 = substr($v2, 0, -1);
                }
                $v2 = trim($v2);

                // riêng với video youtube
                if (strpos($v2, 'youtube.com/') !== false) {
                    //				echo $v2 . "\n";
                    $v2 = explode('src="', $v2);
                    $v2 = $v2[1];
                    $v2 = explode('"', $v2);
                    $v2 = $v2[0];
                    //				echo $v2 . "\n";
                    $v2 = _eb_get_youtube_id($v2);
                    //				echo $v2 . "\n";

                    // tạo nội dung mới từ ID youtube
                    $v2 = 'data-videoid="' . $v2 . '" layout="responsive" width="480" height="270"';
                    $new_tag = 'amp-youtube';

                    // tải cdn cho youtube
                    $other_amp_cdn['youtube'] = '';
                } else if ($new_tag == 'amp-iframe') {
                    //					echo $v2 . "\n";

                    $iframe_src = explode('src="', $v2);
                    $iframe_src = $iframe_src[1];
                    $iframe_src = explode('"', $iframe_src);
                    $iframe_src = $iframe_src[0];
                    //					echo $iframe_src . "\n";

                    $iframe_width = explode('width="', $v2);
                    $iframe_width = $iframe_width[1];
                    $iframe_width = explode('"', $iframe_width);
                    $iframe_width = $iframe_width[0];
                    //					echo $iframe_width . "\n";

                    $iframe_height = explode('height="', $v2);
                    $iframe_height = $iframe_height[1];
                    $iframe_height = explode('"', $iframe_height);
                    $iframe_height = $iframe_height[0];
                    //					echo $iframe_height . "\n";

                    $v2 = 'width="' . $iframe_width . '" height="' . $iframe_height . '" sandbox="allow-scripts allow-same-origin" layout="responsive" frameborder="0" src="' . $iframe_src . '"';

                    $other_amp_cdn['amp-iframe'] = '';
                }
                // với hình ảnh, nếu thiếu layout thì bổ sung
                else if ($new_tag == 'amp-img') {
                    //echo $k . '" ----------- <br>' . "\n\n";
                    //echo '"-----' . $v2 . '------" ----------- <br>' . "\n\n";
                    if ($v2 != '' && strpos($v2, 'src=') !== false) {
                        //echo '"-----' . $v2 . '------" ----------- <br>' . "\n\n";
                        $amp_avt_size = array();

                        // lấy chiều rộng thực của ảnh nếu chưa có
                        if (strpos($v2, ' width=') === false) {
                            $amp_avt_size = $this->get_src_img($v2);
                            //print_r($amp_avt_size);

                            //
                            if (!empty($amp_avt_size)) {
                                $v2 .= ' width="' . $amp_avt_size[0] . '"';
                            } else {
                                $v2 .= ' width="400"';
                            }
                        }

                        // chiều cao thì lấy luôn từ mục chiều rộng trước đó rồi
                        if (strpos($v2, ' height=') === false) {
                            if (empty($amp_avt_size)) {
                                $amp_avt_size = $this->get_src_img($v2);
                            }

                            //
                            if (!empty($amp_avt_size)) {
                                $v2 .= ' height="' . $amp_avt_size[1] . '"';
                            } else {
                                $v2 .= ' height="400"';
                            }
                        }

                        //
                        // thêm class để resize ảnh (dựa theo AMP wp)
                        $v2 .= ' class="amp-wp-enforced-sizes"';
                        //$v2 .= ' sizes="(min-width: 600px) 600px, 100vw"';
                    } else {
                        $v2 = '';
                    }
                }

                // tổng hợp nội dung lại
                if ($v2 != '') {
                    //echo $v2 . ' :::::::::::<br>' . "\n";
                    $v = '<' . $new_tag . ' ' . $v2 . '></' . $new_tag . '>' . $v;
                } else {
                    //echo $v . ' ================ <br>' . "\n";
                }
            }

            //
            $new_str .= $v;
        }

        return $new_str;
    }


    function add_css($arr)
    {

        $f_content = '';

        foreach ($arr as $v) {
            $v = EB_THEME_PLUGIN_INDEX . $v;

            if (is_file($v)) {
                $f_content .= trim(file_get_contents($v, 1)) . "\n";
            }
        }

        // sử dụng chức năng rút gọn nội dung sau
        //		$f_content = preg_replace( "/\r\n|\n\r|\n|\r|\t/", "", $f_content );
        //		$f_content = str_replace( '}.', '}' . "\n" . '.', $f_content );

        //
        return $f_content;
    }

    function del_line($str)
    {
        $c = explode("\n", $str);

        $new_str = '';
        foreach ($c as $v) {
            $v = trim($v);

            //
            if ($v != '') {
                if (strpos($v, '//') !== false) {
                    $v .= "\n";
                } else {
                    $v .= ' ';
                }

                //
                $new_str .= $v;
            }
        }

        //
        $new_str = str_replace(' } ', '}', $new_str);
        $new_str = str_replace(' { ', '{', $new_str);
        $new_str = str_replace('alt=""', '', $new_str);

        //
        return $new_str;
    }


    // tìm kích thước ảnh trên host
    function img_size($img, $default_width = 300, $default_height = 300)
    {
        //		echo $img . '<br>' . "\n";

        //
        $amp_avt_width = $default_width;
        $amp_avt_height = $default_height;

        // lấy domain hiện tại
        $domain = str_replace('www.', '', $_SERVER['HTTP_HOST']) . '/';

        //
        $check_img = strstr($img, $domain);
        $local_img = '';

        // nếu không -> thử tìm theo thư mục upload
        if ($check_img == '') {
            $check_img = strstr($img, '/' . EB_DIR_CONTENT . '/uploads/');
            if ($check_img != '') {
                $local_img = EB_THEME_CONTENT . substr($check_img, 1);
            }
        }
        // nếu có -> dùng luôn
        else {
            $local_img = ABSPATH . str_replace($domain, '', $check_img);
        }
        //		echo $local_img . '<br>' . "\n";

        //
        if ($local_img != '' && is_file($local_img)) {
            $local_img = getimagesize($local_img);
            //			print_r( $check_img );

            //
            $amp_avt_width = $local_img[0];
            $amp_avt_height = $local_img[1];
        }


        //
        return array(
            $amp_avt_width,
            $amp_avt_height,
        );
    }

    function get_src_img($v2)
    {
        $get_img_src = str_replace("'", '"', $v2);
        //		echo $get_img_src . '<br>' . "\n";

        $get_img_src = explode('src="', $get_img_src);
        //		print_r( $get_img_src );

        if (isset($get_img_src[1])) {
            //			echo $get_img_src . '<br>' . "\n";

            $get_img_src = explode('"', $get_img_src[1]);
            $get_img_src = $get_img_src[0];
            //			echo $get_img_src . '<br>' . "\n";

            //
            return $this->img_size($get_img_src, 400, 400);
        }

        //
        return array();
    }
}

//
$eb_amp = new EchAMPFunction();
