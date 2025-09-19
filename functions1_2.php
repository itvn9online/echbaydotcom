<?php

/**
 * Chức năng lấy dữ liệu trong cache
 */

// https://www.smashingmagazine.com/2012/06/diy-caching-methods-wordpress/
function _eb_get_static_html($f, $c = '', $file_type = '', $cache_time = 120, $dir_cache = EB_THEME_CACHE)
{
    global $__cf_row;

    if ($cache_time == 0) {
        $cache_time = $__cf_row['cf_reset_cache'];
    }
    //	echo $cache_time . '<br>';


    if ($cache_time == 0 || $f == '') {
        return false;
    }


    if (strlen($f) > 155) {
        $f = md5($f);
    }

    // cache file
    if ($file_type == '') {
        $file_type = '.txt';
    }
    $f = $dir_cache . $f . $file_type;
    // echo $f . '<br>';

    // nếu sử dụng redis cache thì trả về tham số này luôn
    if (defined('EB_REDIS_CACHE') && EB_REDIS_CACHE == true) {
        $rd = new Redis();
        $rd->connect(REDIS_MY_HOST, REDIS_MY_PORT);
        // echo "Connection to server sucessfully";
        $rd_key = WGR_redis_key($f);
        // echo $rd_key;
        if ($c != '') {
            try {
                // key will be deleted after 10 seconds
                $rd->setex($rd_key, $cache_time, $c);
            } catch (Exception $e) {
                // set the data in redis string
                $rd->set($rd_key, $c);
                // key will be deleted after 10 seconds
                $rd->expire($rd_key, $cache_time);
            }
            return true;
        }
        return $rd->get($rd_key);
    }

    // lưu nội dung file nếu có
    if ($c != '') {
        // _eb_create_file($f, time() . '¦' . $c);
        _eb_create_file($f, WGR_buffer($c));
    } else if (is_file($f)) {
        return WGR_cache_to_content(file_get_contents($f, 1), $cache_time);
    }
    return false;
}

function WGR_cache_to_content($data, $cache_time)
{
    // $content = explode('¦', $data, 2);
    $content = explode('|WGR_CACHE|', $data, 2);
    if (count($content) < 2 || !is_numeric($content[0])) {
        return false;
    }

    //
    if (($content[0] * 1) + $cache_time + mt_rand(1, 20) > time()) {
        return $content[1];
    }

    return false;
}

// xóa file cache đã được tạo ra
function _eb_remove_static_html($f, $file_type = '.txt')
{
    $f = EB_THEME_CACHE . $f . $file_type;

    if (is_file($f)) {
        unlink($f);
    }
}

function _eb_check_email_type($e_mail = '')
{
    if ($e_mail == '' || !is_email($e_mail)) {
        return 0;
    }
    return 1;

    //
    /*
 $r = 0;
 if ($e_mail != '') {
 $arr = explode ( '@', $e_mail );
 if (count ( $arr ) == 2) {
 $arr2 = explode ( '.', $arr [1] );
 if (count ( $arr2 ) > 1) {
 $r = 1;
 }
 }
 if ($r == 0) {
 if (preg_match ( '/^([a-z]|[0-9]|\.|-|_){2,32}@([a-z]|[0-9]|\.|-|_)+\.([a-z]|[0-9]){2,4}(\.+\w+)?$/i', $e_mail )) {
 $r = 1;
 }
 }
 }
 return $r;
 */
}

function _eb_mdnam($str)
{
    $str = md5($str);
    $str = substr($str, 2, 6);
    return md5($str);
}

// function tạo chuỗi vô định bất kỳ cho riêng mềnh
function _eb_code64($str, $code = 0)
{

    //
    if ($str == '') {
        return '';
    }

    // chuỗi để tọa ra một chuỗi vô định bất kỳ ^^! chả để làm gì
    $hoc_code = str_split('qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM');

    // bắt đầu tạo chuỗi -> mã hóa
    if ($code == 0) {
        // cắt chuỗi thành từng ký tự 1
        $arr = str_split($str);
        $str = '';
        // chạy vòng lặp để nhúng ký tự linh ta linh tinh vào chơi chơi
        foreach ($arr as $v) {
            $str .= $v . $hoc_code[mt_rand(0, count($hoc_code) - 1)];
        }
        // mã hóa chuỗi trc khi trả về
        return base64_encode($str);
    }

    // lật chuỗi -> giải mã chuỗi
    $str = base64_decode($str);
    // cắt chuỗi thành từng ký tự
    $arr = str_split($str);
    $str = '';
    // chạy vòng lặp và lấy chuỗi theo vị trí xác định
    foreach ($arr as $k => $v) {
        if ($k % 2 == 0) {
            $str .= $v;
        }
    }
    return $str;
}

function _eb_encode($str)
{
    return _eb_code64($str);
}

function _eb_decode($str)
{
    return _eb_code64($str, 1);
}

function _eb_postmeta($id, $key, $val)
{
    //	echo $id . "<br>\n";
    //	echo $key . "<br>\n";
    //	echo $val . "<br>\n";
    // kiểm tra trùng lặp
    //	WGR_update_meta_post( $id, $key, $val, true );
    // bỏ qua kiểm tra
    WGR_update_meta_post($id, $key, $val);
}

// cấu trúc để phân định option của EchBay với các mã khác (sợ trùng)
define('_eb_option_prefix', '_eb_');

//
function _eb_set_config($key, $val, $etro = 1)
{

    //	global $wpdb;
    //	_eb_postmeta( eb_config_id_postmeta, $key, $val );
    //	WGR_update_meta_post( eb_config_id_postmeta, $key, $val );
    // sử dụng option thay cho meta_post -> load nhanh hơn nhiều
    $key = _eb_option_prefix . $key;

    // xóa option cũ đi cho đỡ lằng nhằng
    //	if ( delete_option( $key ) ) {
    if (WGR_delete_option($key) == true && $etro == 1) {
        echo '<em>Remove</em>: ' . $key . '<br>' . "\n";
    }

    //
    //	$val = WGR_stripslashes( $val );
    // thêm option mới
    //	if ( get_option( $key ) == false ) {
    //	if ( $val == 0 || $val != '' ) {
    if ($val != '') {
        /*
         $sql = "INSERT INTO `" . $wpdb->options . "`
         ( option_name, option_name, option_name )
         VALUES
         ()";
         */
        //		add_option( $key, $val, '', 'no' );
        //		add_option( $key, $val );
        WGR_set_option($key, $val, 'no');

        //
        if ($etro == 1) {
            if (strlen($val) < 50) {
                echo 'Add: ' . $key . ' (' . $val . ')<br>' . "\n";
            } else {
                echo 'Add: ' . $key . '<br>' . "\n";
            }
        }
    } else if ($etro == 1) {
        echo 'Value: ' . $key . ' is NULL<br>' . "\n";
    }
    /*
 else {
 update_option( $key, $val, 'no' );
 //		update_option( $key, $val );
 }
 */
}

//function _eb_get_config( $real_time = false ) {
//}

function _eb_get_config_v3($real_time = false)
{

    global $wpdb;
    global $__cf_row;
    global $__cf_row_default;
    //	print_r( $__cf_row );
    //	print_r( $__cf_row_default );
    //	echo count( $__cf_row_default ) . '<br>' . "\n";
    //
    $row = _eb_q("SELECT option_value
	FROM
		`" . $wpdb->options . "`
	WHERE
		option_name = '" . eb_conf_obj_option . "'
	LIMIT 1");
    //	print_r( $row );
    // nếu có dữ liệu trả về -> database đã lên bản mới nhất
    if (isset($row[0]) && isset($row[0]->option_value)) {

        // chuyển từ dạng chuỗi sang dạng mảng
        $row = maybe_unserialize($row[0]->option_value);
        //		print_r( $row );
        //		$row = get_option( eb_conf_obj_option );
        //		print_r( $row );
        //		wp_parse_str( $row[0]->option_value, $row );
        //		print_r( $row );
        //
        foreach ($row as $k => $v) {
            if (isset($__cf_row_default[$k])) {
                if ($v == '') {
                    $v = $__cf_row_default[$k];
                }
                $__cf_row[$k] = WGR_stripslashes($v);
            }
        }
        //		print_r( $__cf_row );
    }
    // nếu chưa có -> load theo v2 và đồng bộ cho v3 luôn
    /*
 else {
 _eb_get_config_v2();
 }
 */
}

function _eb_get_config($real_time = false)
{

    global $wpdb;
    global $__cf_row;
    global $__cf_row_default;
    //	print_r( $__cf_row );
    //	print_r( $__cf_row_default );
    //	echo count( $__cf_row_default ) . '<br>' . "\n";
    //
    //	$__cf_row = $__cf_row_default;


    /*
     * đồng bộ phiên bản code cũ với code mới -> 1 thời gian sẽ bỏ chức năng này đi
     */
    /*
     if (get_option(_eb_option_prefix . 'cf_web_version') == false) {
     //		echo 1;
     $row = _eb_q("SELECT *
     FROM
     `" . wp_postmeta . "`
     WHERE
     post_id = " . eb_config_id_postmeta);
     //		print_r( $row );
     foreach ($row as $k => $a) {
     _eb_set_config($a->meta_key, $a->meta_value);
     }
     // xóa cấu hình cũ
     _eb_q("DELETE
     FROM
     `" . wp_postmeta . "`
     WHERE
     post_id = " . eb_config_id_postmeta, 0);
     }
     */
    //	exit();
    //	return false;

    //
    $option_conf_name = _eb_option_prefix . 'cf_';

    $row = _eb_q("SELECT option_name, option_value
	FROM
		`" . $wpdb->options . "`
	WHERE
		option_name LIKE '{$option_conf_name}%'");
    //	print_r( $row );
    //	exit();
    //
    if (count($row) == 0) {
        _eb_get_config_v3();

        foreach ($__cf_row as $k => $a) {
            _eb_set_config($k, $a);
        }

        return true;
    }


    // chuyển sang kiểu dữ liệu còn mới hơn nữa
    //	$arr_for_update_eb_config = array();
    //
    foreach ($row as $k => $a) {
        $a->option_name = str_replace(_eb_option_prefix, '', $a->option_name);
        //		echo $a->option_name . '<br>' . "\n";
        //		if ( isset( $__cf_row_default[ $a->option_name ] ) && $a->option_value == '' ) {
        if (isset($__cf_row_default[$a->option_name])) {
            if ($a->option_value == '') {
                //				$a->option_value = $__cf_row_default[ $a->option_name ];
                $a->option_value = $__cf_row_default[$a->option_name];
            }
            /*
             else if ( $a->option_value == 'off' ) {
             $__cf_row[ $a->option_name ] = 0;
             }
             */ else {
                $a->option_value = WGR_stripslashes($a->option_value);
                //				$__cf_row[ $a->option_name ] = $a->option_value;
            }

            //
            $__cf_row[$a->option_name] = $a->option_value;

            //
            //			$arr_for_update_eb_config[ $a->option_name ] = addslashes( $__cf_row[ $a->option_name ] );
        }
        //		$__cf_row[ $a->option_name ] = stripslashes( $a->option_value );
    }
    //	print_r( $__cf_row );
    // xóa config cũ đi -> tránh cache lưu lại
    //	delete_option( eb_conf_obj_option );
    // lưu cấu hình mới dưới dạng object
    //	add_option( eb_conf_obj_option, $arr_for_update_eb_config, '', 'no' );
    // mọi option đều phải dựa vào mảng cấu hình mặc định -> lệch phát bỏ qua luôn
    /*
 foreach ( $__cf_row_default as $k => $v ) {
 $a = get_option( _eb_option_prefix . $k );
 //
 if ( $a == false ) {
 $__cf_row[ $k ] = $__cf_row[ $k ];
 }
 else {
 $__cf_row[ $k ] = stripslashes( $a );
 }
 }
 */
}

function _eb_get_config_v1($real_time = false)
{

    global $__cf_row;
    global $__cf_row_default;
    //	print_r( $__cf_row );
    //	print_r( $__cf_row_default );
    //
    $row = _eb_q("SELECT *
	FROM
		`" . wp_postmeta . "`
	WHERE
		post_id = " . eb_config_id_postmeta);
    //	print_r( $row );
    // gán lại giá trị cho bảng config (nếu có, không thì sử dụng giá trị mặc định)
    //	if ( $real_time == false ) {
    foreach ($row as $k => $a) {
        if (isset($__cf_row_default[$a->meta_key]) && $a->meta_value == '') {
            $a->meta_value = $__cf_row_default[$a->meta_key];
        }
        $__cf_row[$a->meta_key] = stripslashes($a->meta_value);
    }
    /*
 }
 else {
 foreach ( $row as $k => $a ) {
 if ( isset( $__cf_row[ $a->meta_key ] ) ) {
 $__cf_row[ $a->meta_key ] = stripslashes( $a->meta_value );
 }
 }
 }
 */
    //	print_r( $__cf_row );
}

// Log mặc định
function _eb_log_default($m)
{
    return _eb_set_log(array(
        'l_noidung' => $m
    ));
}

// Log LỖI, cho vào log đồng thời báo lỗi luôn
function _eb_log_error($m)
{
    echo $m;

    return _eb_set_log(array(
        'l_noidung' => $m
    ), 6);
}

function _eb_log_click($m)
{
    //	return false;
    // v2
    return _eb_set_log(array(
        'l_noidung' => $m
    ), 3);

    // v1
    _eb_postmeta(eb_log_click_id_postmeta, '__eb_log_click', $m);
}

function _eb_get_log_click($limit = '')
{
    // v2
    return _eb_get_log(3);

    // v1
    return _eb_q("SELECT *
	FROM
		" . wp_postmeta . "
	WHERE
		post_id = " . eb_log_click_id_postmeta . "
		AND meta_key = '__eb_log_click'
	ORDER BY
		meta_id DESC
	" . $limit);
}

function _eb_log_user($m)
{
    //	return false;
    // v2
    return _eb_set_log(array(
        'l_noidung' => $m
    ), 4);

    // v1
    $m .= ' (at ' . date('r', date_time) . ')';

    _eb_postmeta(eb_log_user_id_postmeta, '__eb_log_user', $m);
}

function _eb_get_log_user($limit = '')
{
    // v2
    return _eb_get_log(4);

    // v1
    return _eb_q("SELECT *
	FROM
		" . wp_postmeta . "
	WHERE
		post_id = " . eb_log_user_id_postmeta . "
		AND meta_key = '__eb_log_user'
	ORDER BY
		meta_id DESC
	" . $limit);
}

function _eb_log_ga_event($event_category, $event_action = '', $event_label = '')
{

    //
    echo '<script>
        (function () {
            var arr = [
                "' . str_replace('"', '\"', $event_category) . '",
                "' . str_replace('"', '\"', $event_action) . '",
                "' . str_replace('"', '\"', $event_label) . '"
            ];

            //
            if (top != self) {
                if (typeof parent._global_js_eb != "undefined") {
                    parent._global_js_eb.ga_event_log(arr[0], arr[1], arr[1]);
                }
                else if (typeof _global_js_eb != "undefined") {
                    _global_js_eb.ga_event_log(arr[0], arr[1], arr[1]);
                }
                else {
                    console.log("ga_event_log not found!");
                }
            }
            else if (typeof _global_js_eb != "undefined") {
                _global_js_eb.ga_event_log(arr[0], arr[1], arr[1]);
            }
            else {
                console.log("ga_event_log nt found!");
            }
        })();
    </script>';
}

function _eb_log_admin($m, $post_id = 0)
{
    //	return false;
    // v2
    return _eb_set_log(array(
        'post_id' => $post_id,
        'l_noidung' => $m
    ), 1);

    // v1
    $m .= ' (by ' . mtv_email . ' at ' . date('r', date_time) . ')';
    //		echo $m . "\n";

    _eb_postmeta(eb_log_user_id_postmeta, '__eb_log_admin', $m);
}

function _eb_get_log_admin($limit = '')
{
    // v2
    return _eb_get_log(1);

    // v1
    return _eb_q("SELECT *
	FROM
		" . wp_postmeta . "
	WHERE
		post_id = " . eb_log_user_id_postmeta . "
		AND meta_key = '__eb_log_admin'
	ORDER BY
		meta_id DESC
	" . $limit);
}

function _eb_log_admin_order($m, $order_id)
{
    //	return false;
    // v2
    return _eb_set_log(array(
        'hd_id' => $order_id,
        'l_noidung' => $m
    ), 2);

    // v1
    _eb_postmeta(eb_log_user_id_postmeta, '__eb_log_invoice' . $order_id, $m);
}

function _eb_get_log_admin_order($order_id, $limit = 50)
{
    // v2
    return _eb_get_log(2, $limit, $order_id);

    // v1
    return _eb_q("SELECT *
	FROM
		" . wp_postmeta . "
	WHERE
		post_id = " . eb_log_user_id_postmeta . "
		AND meta_key = '__eb_log_invoice" . $order_id . "'
	ORDER BY
		meta_id DESC
	" . $limit);
}

function _eb_log_search($m)
{
    //	return false;
    // v2
    return _eb_set_log(array(
        'l_noidung' => $m
    ), 5);

    // v1
    _eb_postmeta(eb_log_search_id_postmeta, '__eb_log_search', $m);
}

function _eb_get_log_search($limit = '')
{
    // v2
    return _eb_get_log(5);

    // v1
    return _eb_q("SELECT *
	FROM
		" . wp_postmeta . "
	WHERE
		post_id = " . eb_log_search_id_postmeta . "
		AND meta_key = '__eb_log_search'
	ORDER BY
		meta_id DESC
	" . $limit);
}

function _eb_set_log($arr, $log_type = 0)
{
    // daidq (2022-09-14): do việc ghi log đợt gần đây dễ gây crash db nên bỏ nó đi vậy
    return false;

    //
    global $client_ip;

    $arr['l_agent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $arr['l_ip'] = $client_ip;
    $arr['l_ngay'] = date_time;
    $arr['l_type'] = $log_type;
    //	$arr['hd_id'] = $hd_id;
    //	$arr['post_id'] = $post_id;
    $arr['tv_id'] = mtv_id;

    return _eb_sd($arr, 'eb_wgr_log');
}

function _eb_get_log($log_type = 0, $limit = 100, $hd_id = 0)
{
    $filter = "";
    if ($hd_id > 0) {
        $filter = " AND hd_id = " . $hd_id;
    }

    //
    return _eb_q("SELECT *
	FROM
		`eb_wgr_log`
	WHERE
		l_type = " . $log_type . $filter . "
	ORDER BY
		l_id DESC
	LIMIT 0, " . $limit);
}

/*
 * Tính số lượng log theo khoảng thời gian
 * limit_clear_log: số lượng bản ghi tối đa cho mỗi log
 */

function _eb_count_log($log_type = 0, $limit_time = 3600, $limit_day = 0, $limit_clear_log = 35000)
{
    /*
     * limit_day < 182 -> lấy theo giây
     */
    /*
     if ( $limit_time < 182 ) {
     $limit_time = $limit_time * 24 * 3600;
     }
     */
    $limit_time += $limit_day * 24 * 3600;
    // mặc định thì tính theo số giây
    //	echo $log_type . '<br>' . "\n";
    //
    $sql = _eb_q("SELECT count(l_id) as c
	FROM
		`eb_wgr_log`
	WHERE
		l_type = " . $log_type . "
		AND l_ngay > " . (date_time - $limit_time) . "
	ORDER BY
		l_id DESC");
    //	print_r( $sql );

    if (!empty($sql)) {
        $a = $sql[0]->c;

        // nếu không phải log của đơn hàng -> xóa bớt log cho nhẹ db
        if ($log_type != 2 && $a > $limit_clear_log * 1.5) {
            $sql = _eb_q("SELECT l_id
			FROM
				`eb_wgr_log`
			WHERE
				l_type = " . $log_type . "
			ORDER BY
				l_id DESC
			LIMIT " . $limit_clear_log . ", 1");

            //
            if (!empty($sql)) {
                // lưu cái tổng kia lại đã
                $strsql = _eb_q("SELECT count(l_id) as c
				FROM
					`eb_wgr_log`
				WHERE
					l_type = " . $log_type . "
					AND l_id < " . $sql[0]->l_id);
                //				print_r( $strsql );
                _eb_set_option('WGR_history_for_log' . $log_type, $strsql[0]->c, 'no');

                // xóa
                _eb_q("DELETE
				FROM
					`eb_wgr_log`
				WHERE
					l_type = " . $log_type . "
					AND l_id < " . $sql[0]->l_id . "
					AND hd_id = 0", 0);
            }
        }

        //
        return $a;
    }
    return 0;
}

function _eb_clear_log($log_type = 0, $limit_day = 61)
{

    // tính toán lại log, đồng thời dọn dẹp bớt đi cho nó gọn
    _eb_count_log($log_type, 0, $limit_day);

    //
    /*
     $limit_day = date_time - $limit_day * 24 * 3600;
     //
     _eb_q("DELETE
     FROM
     `eb_wgr_log`
     WHERE
     l_type = " . $log_type . "
     AND l_ngay < " . $limit_day, 0);
     */

    return true;
}

// làm 1 vòng lặp, xóa toàn bộ cac loại log theo type
function _eb_clear_all_log()
{
    for ($i = 0; $i < 10; $i++) {
        _eb_clear_log($i);
    }

    // ép xóa các log trong 30 ngày qua
    $limit_day = 30;
    $limit_day = date_time - ($limit_day * 24 * 3600);

    // xóa thẳng cánh các log quá cũ
    _eb_q("DELETE
    FROM
        `eb_wgr_log`
    WHERE
        l_ngay < " . $limit_day . "
        AND hd_id = 0", 0);
}

function _eb_number_only($str = '', $re = '/[^0-9]+/')
{
    $str = trim($str);
    if ($str == '') {
        return 0;
    }
    //	echo $str . ' str number<br>';
    $a = preg_replace($re, '', $str);
    //	echo $a . ' a number<br>';
    if ($a == '') {
        $a = 0;
    } else if (substr($str, 0, 1) == '-') {
        $a = 0 - $a;
    } else {
        $a *= 1;
    }
    return $a;
}

function _eb_float_only($str = '', $lam_tron = 0)
{
    $str = trim($str);
    //	echo $str . ' str float<br>';
    $a = _eb_number_only($str, '/[^0-9|\.]+/');
    //	echo $a . ' a float<br>';

    // làm tròn hết sang số nguyên
    if ($lam_tron == 1) {
        $a = ceil($a);
    }
    // làm tròn phần số nguyên, số thập phân giữ nguyên
    else if ($lam_tron == 2) {
        $a = explode('.', $a);
        if (isset($a[1])) {
            $a = (int)$a[0] . '.' . $a[1];
        } else {
            $a = (int)$a[0];
        }
    }

    return $a;
}

function _eb_text_only($str = '')
{
    if ($str == '') {
        return '';
    }
    return preg_replace('/[^a-zA-Z0-9\-\.]+/', '', $str);
}

function _eb_un_money_format($str)
{
    return _eb_number_only($str);
}

function _eb_non_mark_seo_v2($str)
{
    // Chuyển đổi toàn bộ chuỗi sang chữ thường
    if (function_exists('mb_convert_case')) {
        $str = mb_convert_case(trim($str), MB_CASE_LOWER, "UTF-8");
    }

    //Tạo mảng chứa key và chuỗi regex cần so sánh
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        '-' => '\+|\*|\/|\&|\!| |\^|\%|\$|\#|\@'
    );

    foreach ($unicode as $key => $value) {
        //So sánh và thay thế bằng hàm preg_replace
        $str = preg_replace("/($value)/", $key, $str);
    }

    //Trả về kết quả
    return $str;
}

function _eb_non_mark_seo_v1($str)
{
    $str = _eb_non_mark(trim($str));

    //
    $unicode = array(
        /*
     'a' => array('á','à','ả','ã','ạ','ă','ắ','ặ','ằ','ẳ','ẵ','â','ấ','ầ','ẩ','ẫ','ậ','Á','À','Ả','Ã','Ạ','Ă','Ắ','Ặ','Ằ','Ẳ','Ẵ','Â','Ấ','Ầ','Ẩ','Ẫ','Ậ'),
     'd' => array('đ','Đ'),
     'e' => array('é','è','ẻ','ẽ','ẹ','ê','ế','ề','ể','ễ','ệ','É','È','Ẻ','Ẽ','Ẹ','Ê','Ế','Ề','Ể','Ễ','Ệ'),
     'i' => array('í','ì','ỉ','ĩ','ị', 'Í','Ì','Ỉ','Ĩ','Ị'),
     'o' => array('ó','ò','ỏ','õ','ọ','ô','ố','ồ','ổ','ỗ','ộ','ơ','ớ','ờ','ở','ỡ','ợ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ố','Ồ','Ổ','Ỗ','Ộ','Ơ','Ớ','Ờ','Ở','Ỡ','Ợ'),
     'u' => array('ú','ù','ủ','ũ','ụ','ư','ứ','ừ','ử','ữ','ự','Ú','Ù','Ủ','Ũ','Ụ','Ư','Ứ','Ừ','Ử','Ữ','Ự'),
     'y' => array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),
     */
        '-' => array(' ', '~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '=', '[', ']', '{', '}', '\\', '|', ';', ':', '\'', '"', ',', '<', '>', '/', '?')
    );
    foreach ($unicode as $nonUnicode => $uni) {
        foreach ($uni as $v) {
            $str = str_replace($v, $nonUnicode, $str);
        }
    }

    //
    return $str;
}

function _eb_non_mark_seo($str)
{
    // sử dụng hàm của wordpress
    // return sanitize_title($str);

    //	$str = _eb_non_mark_seo_v1( $str );
    $str = _eb_non_mark_seo_v2($str);


    //	$str = urlencode($str);
    // thay thế 2- thành 1-  
    // $str = preg_replace('/-+-/', "-", $str);
    $str = preg_replace('!\-+!', '-', $str);

    // cắt bỏ ký tự - ở đầu và cuối chuỗi
    // $str = preg_replace('/^\-+|\-+$/', "", $str);
    $str = rtrim(ltrim($str, '-'), '-');
    $str = rtrim(ltrim($str, '.'), '.');
    $str = trim($str);

    //
    $str = _eb_text_only($str);

    //
    return $str;
    //	return strtolower($str);
}

function _eb_non_mark($str)
{
    $unicode = array(
        'a' => array('á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ặ', 'ằ', 'ẳ', 'ẵ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ'),
        'A' => array('Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ặ', 'Ằ', 'Ẳ', 'Ẵ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ'),
        'd' => array('đ'),
        'D' => array('Đ'),
        'e' => array('é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ'),
        'E' => array('É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ'),
        'i' => array('í', 'ì', 'ỉ', 'ĩ', 'ị'),
        'I' => array('Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị'),
        'o' => array('ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ'),
        'O' => array('Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ'),
        'u' => array('ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự'),
        'U' => array('Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự'),
        'y' => array('ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ'),
        'Y' => array('Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ')
    );
    foreach ($unicode as $nonUnicode => $uni) {
        foreach ($uni as $v) {
            $str = str_replace($v, $nonUnicode, $str);
        }
    }
    return $str;
}
