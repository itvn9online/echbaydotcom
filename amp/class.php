<?php


class EchAMPFunction
{
    function removes_attr($str)
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
            'decoding',
            'color',
            // video
            'autoplay',
            'loop',
            // img
            'fetchpriority',
            // iframe
            'frameborder',
            'scrolling',
            'allowfullscreen',
            //
            'longdesc'
        );


        // xóa từng attr đã được chỉ định
        foreach ($arr as $v) {
            // v2 -> thay thành 1 attr sau đó remove 1 thể
            $str = str_replace(' ' . $v . '=\'', ' for-remove-attr=\'', $str);
            $str = str_replace(' ' . $v . '="', ' for-remove-attr="', $str);

            // v1
            // $str = $this->remove_attr($str, ' ' . $v . '="', '"');
            // $str = $this->remove_attr($str, " " . $v . "='", "'");
        }

        // xóa riêng thẻ height cho table
        $str = $this->remove1_attr($str, 'table', 'height');


        // bắt đầu xóa attr đã được thay thế
        $str = $this->remove_attr($str, ' for-remove-attr="', '"');
        $str = $this->remove_attr($str, " for-remove-attr='", "'");


        // xóa các thẻ không còn được hỗ trợ
        foreach ([
            'style',
            'font'
        ] as $v) {
            $str = $this->remove_tag($str, $v);
        }

        //
        return $str;
    }

    // thay thế các tag không khả dụng trong AMP bằng span
    function remove_tag($str, $tag)
    {
        $str = str_replace('<' . $tag, '<span', $str);
        $str = str_replace('</' . $tag . '>', '</span>', $str);

        //
        return $str;
    }

    function remove_v1_tag($str, $tag)
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

    /**
     * Xóa riêng 1 attr cho 1 tag cụ thể
     **/
    function remove1_attr($str, $tag, $attr)
    {
        $search = array();
        $replace = array();
        $matches = array();
        preg_match_all('/<' . $tag . '[\s\r\n]+.*?>/is', $str, $matches);
        // print_r($matches);
        foreach ($matches[0] as $imgHTML) {
            if (strpos($imgHTML, $attr) === false) {
                continue;
            }

            // replace the attr and add the for-remove-attr attribute
            $replaceHTML = $imgHTML;
            $replaceHTML = str_replace(' ' . $attr . '=\'', ' for-remove-attr=\'', $replaceHTML);
            $replaceHTML = str_replace(' ' . $attr . '="', ' for-remove-attr="', $replaceHTML);

            // cho vào mảng để thay thế nội dung
            $search[] = $imgHTML;
            $replace[] = $replaceHTML;
        }
        return str_replace($search, $replace, $str);
    }


    function amp_change_tag($str)
    {
        global $other_amp_cdn;
        global $__cf_row;

        //
        $search = array();
        $replace = array();

        // amp-img
        $matches = array();
        preg_match_all('/<img[\s\r\n]+.*?>/is', $str, $matches);
        // print_r($matches);
        foreach ($matches[0] as $imgHTML) {
            // if (in_array($imgHTML, $search, true)) {
            //     // already has a replacement
            //     continue;
            // }

            // replace the src and add the data-src attribute
            $replaceHTML = $imgHTML;
            // $replaceHTML = preg_replace('/<img(.*?)srcset=/is', '<img$1srcset="" data-srcset=', $replaceHTML);

            // thêm class để resize ảnh (dựa theo AMP wp)
            $classes = 'amp-wp-enforced-sizes';
            if (preg_match('/class=["\']/i', $replaceHTML)) {
                $replaceHTML = preg_replace('/class=(["\'])(.*?)["\']/is', 'class=$1' . $classes . ' $2$1', $replaceHTML);
            } else {
                $replaceHTML = preg_replace('/<img/is', '<img class="' . $classes . '"', $replaceHTML);
            }

            // chưa có sizes thì bổ sung
            if (!preg_match('/sizes=["\']/i', $replaceHTML)) {
                $replaceHTML = preg_replace('/<img/is', '<img sizes="(max-width: 768px) 100vw, 768px"', $replaceHTML);
            }

            // thêm thẻ đóng amp-img
            // $replaceHTML = str_replace(' />', '>', $replaceHTML);
            $replaceHTML = str_replace('/>', '>', $replaceHTML);
            $replaceHTML .= '</amp-img>';

            // cho vào mảng để thay thế nội dung
            $search[] = $imgHTML;
            $replace[] = $replaceHTML;
        }


        // amp-video
        $matches = array();
        preg_match_all('/<video[\s\r\n]+.*?>/is', $str, $matches);
        // print_r($matches);
        foreach ($matches[0] as $imgHTML) {
            // if (in_array($imgHTML, $search, true)) {
            //     // already has a replacement
            //     continue;
            // }

            //
            $replaceHTML = $imgHTML;

            // thêm layout responsive
            $replaceHTML = str_replace('<video ', '<video layout="responsive" ', $replaceHTML);

            // bổ sung poster là logo (nếu chưa có)
            if (strpos($replaceHTML, 'poster=') === false) {
                $replaceHTML = str_replace('<video ', '<video poster="' . web_link . $__cf_row['cf_logo'] . '" ', $replaceHTML);
            }

            // cho vào mảng để thay thế nội dung
            $search[] = $imgHTML;
            $replace[] = $replaceHTML;

            //
            $other_amp_cdn['amp-video'] = '';
        }


        // amp-audio
        if (strpos($str, '<audio') !== false) {
            $other_amp_cdn['amp-audio'] = '';
        }


        // amp-iframe
        $matches = array();
        preg_match_all('/<iframe[\s\r\n]+.*?>/is', $str, $matches);
        // print_r($matches);
        foreach ($matches[0] as $imgHTML) {
            // if (in_array($imgHTML, $search, true)) {
            //     // already has a replacement
            //     continue;
            // }

            //
            $replaceHTML = $imgHTML;

            // xử lý riêng với video youtube
            if (strpos($replaceHTML, 'youtube.com/') !== false || strpos($replaceHTML, 'youtu.be/') !== false) {
                // tách lấy id video
                $replaceHTML = explode('src="', $replaceHTML);
                $replaceHTML = $replaceHTML[1];
                $replaceHTML = explode('"', $replaceHTML);
                $replaceHTML = $replaceHTML[0];

                // khởi tạo mã mới
                $replaceHTML = '<amp-youtube data-videoid="' . $this->get_youtube_id($replaceHTML) . '" layout="responsive" width="480" height="270"></amp-youtube>';

                //
                $other_amp_cdn['youtube'] = '';
            } else {
                $iframe_src = explode('src="', $replaceHTML);
                $iframe_src = $iframe_src[1];
                $iframe_src = explode('"', $iframe_src);
                $iframe_src = $iframe_src[0];
                // echo $iframe_src . "\n";

                $iframe_width = explode('width="', $replaceHTML);
                $iframe_width = $iframe_width[1];
                $iframe_width = explode('"', $iframe_width);
                $iframe_width = $iframe_width[0];
                // echo $iframe_width . "\n";

                $iframe_height = explode('height="', $replaceHTML);
                $iframe_height = $iframe_height[1];
                $iframe_height = explode('"', $iframe_height);
                $iframe_height = $iframe_height[0];
                // echo $iframe_height . "\n";

                // khởi tạo mã mới
                $replaceHTML = '<amp-iframe width="' . $iframe_width . '" height="' . $iframe_height . '" sandbox="allow-scripts allow-same-origin" layout="responsive" frameborder="0" src="' . $iframe_src . '"></amp-iframe>';

                //
                $other_amp_cdn['amp-iframe'] = '';
            }

            // cho vào mảng để thay thế nội dung
            $search[] = $imgHTML;
            $replace[] = $replaceHTML;
        }


        // bắt đầu thay nội dung
        // print_r($search);
        // print_r($replace);
        $str = str_replace($search, $replace, $str);


        // thay nốt các dữ liệu còn sót
        $str = str_replace('<img ', '<amp-img ', $str);
        //
        $str = str_replace('<video ', '<amp-video ', $str);
        $str = str_replace('</video>', '</amp-video>', $str);
        //
        $str = str_replace('<audio ', '<amp-audio ', $str);
        $str = str_replace('</audio>', '</amp-audio>', $str);
        //
        $str = str_replace('</iframe>', '', $str);
        // bỏ một số thuộc tính không được hỗ trợ trong AMP
        // $str = str_replace(' decoding="async"', '', $str);
        // $str = str_replace(" decoding='async'", '', $str);
        // $str = str_replace(' fetchpriority="high"', '', $str);

        //
        // print_r($other_amp_cdn);

        //
        return $str;
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
