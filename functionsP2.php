<?php


function EBE_get_big_banner($limit = 5, $option = array(), $op = array())
{
    // lấy mẫu q.cáo
    $html = EBE_get_page_template('ads_node');

    // tạo sẵn bg -> ưu tiên load ảnh mobile trước cho nó nhẹ, tạo hình cho nhanh
    //	$html = str_replace( '{tmp.other_attr}', ' style="background-image:url({tmp.trv_table_img});"', $html );
    $html = str_replace('{tmp.other_attr}', ' style="background-image:url({tmp.trv_mobile_img});"', $html);

    //	print_r($option);

    //
    if (!isset($op['set_size']) || $op['set_size'] == '') {
        global $__cf_row;

        $op['set_size'] = $__cf_row['cf_top_banner_size'];
    }
    if (!isset($op['class_big_banner']) || $op['class_big_banner'] == '') {
        $op['class_big_banner'] = 'oi_big_banner';
    }
    if (!isset($op['by_status']) || $op['by_status'] === '') {
        $op['by_status'] = 1;
    }

    //
    $a = _eb_load_ads(
        $op['by_status'],
        (int)$limit,
        $op['set_size'],
        $option,
        0,
        $html,
        /*
		'
<li class="global-a-posi"><a href="{tmp.p_link}" title="{tmp.post_title}"{tmp.target_blank}>&nbsp;</a>
	<div data-size="{tmp.data_size}" data-img="{tmp.trv_img}" data-table-img="{tmp.trv_table_img}" data-mobile-img="{tmp.trv_mobile_img}" data-video="{tmp.youtube_url}" class="ti-le-global banner-ads-media text-center" style="background-image:url({tmp.trv_table_img});">&nbsp;</div>
</li>',
		*/
        array(
            'default_value' => ''
        )
    );

    //
    if ($a != '') {
        $a = '<div class="' . $op['class_big_banner'] . '">' . $a . '</div>';
    }

    //
    return $a;
}


function _eb_load_post_obj($posts_per_page, $_eb_query = array())
{

    //
    $arr['post_type'] = 'post';
    $arr['posts_per_page'] = $posts_per_page;
    //	$arr['orderby'] = 'menu_order';
    $arr['orderby'] = 'menu_order ID';
    $arr['order'] = 'DESC';
    $arr['post_status'] = 'publish';

    //
    foreach ($_eb_query as $k => $v) {
        $arr[$k] = $v;
    }
    /*
	echo '<!-- _eb_load_post_obj ' . "\n";
//	print_r( $_eb_query );
	print_r( $arr );
	echo ' -->' . "\n";
	*/

    // https://codex.wordpress.org/Class_Reference/WP_Query
    $results = new WP_Query($arr);
    wp_reset_postdata();

    //
    return $results;
}

/*
 * Load danh sách đơn hàng
 */
// tạo đơn hàng
function EBE_set_order($arr)
{
    _eb_sd($arr, 'eb_in_con_voi');

    // lấy ID trả về
    $strsql = _eb_q("SELECT *
	FROM
		`eb_in_con_voi`
	WHERE
		tv_id = " . $arr['tv_id'] . "
	ORDER BY
		order_id DESC
	LIMIT 0, 1");
    //	print_r( $strsql );
    //	echo count($strsql);
    if (count($strsql) > 0) {
        return $strsql[0]->order_id;
    }

    return 0;
}

// xóa chi tiết đơn hàng
function EBE_update_details_order($k, $id, $v = '')
{
    _eb_q("DELETE
	FROM
		`eb_details_in_con_voi`
	WHERE
		order_id = " . $id . "
		AND dorder_key = '" . $k . "'", 0);

    // nếu có value mới -> add mới luôn
    if ($v != '') {
        EBE_set_details_order($k, $v, $id);
    }
}

// tạo chi tiết đơn hàng
function EBE_set_details_order($k, $v, $id)
{
    _eb_q("INSERT INTO
	`eb_details_in_con_voi`
	( dorder_key, dorder_name, order_id )
	VALUES
	( '" . $k . "', '" . $v . "', " . $id . " )", 0);
}

// danh sách đơn hàng
function _eb_load_order($posts_per_page = 68, $_eb_query = array())
{

    //
    //	print_r( $_eb_query );

    //
    $strFilter = "";
    $offset = 0;

    // lấy theo ID hóa đơn
    if (isset($_eb_query['offset'])) {
        $offset = $_eb_query['offset'];
    }

    // lấy theo ID hóa đơn
    if (isset($_eb_query['p']) && $_eb_query['p'] > 0) {
        $strFilter .= " AND order_id = " . $_eb_query['p'];
    }

    // lấy theo trạng thái hóa đơn
    //	if ( isset( $_eb_query['status_by'] ) && (int) $_eb_query['status_by'] != '' ) {
    if (isset($_eb_query['status_by'])) {
        $strFilter .= " AND order_status = " . (int)$_eb_query['status_by'];
    }

    // lấy theo filter có sẵn
    if (isset($_eb_query['filter_by'])) {
        $strFilter .= " " . $_eb_query['filter_by'];
    }

    //
    //	echo $strFilter . '<br>' . "\n";

    //
    $sql = _eb_q("SELECT *
	FROM
		`eb_in_con_voi`
	WHERE
		tv_id > 0
		" . $strFilter . "
	ORDER BY
		order_id DESC
	LIMIT " . $offset . ", " . $posts_per_page);
    //	print_r( $sql );

    return $sql;
}

function _eb_load_order_v1($posts_per_page = 68, $_eb_query = array())
{
    global $wpdb;

    //
    //	print_r( $_eb_query );

    //
    $strFilter = "";
    if (isset($_eb_query['p']) && $_eb_query['p'] > 0) {
        $strFilter .= " AND ID = " . $_eb_query['p'];
    }

    //
    $sql = _eb_q("SELECT *
	FROM
		" . wp_posts . "
	WHERE
		post_type = 'shop_order'
		AND post_status = 'private'
		" . $strFilter . "
	ORDER BY
		ID DESC
	LIMIT 0, " . $posts_per_page);
    //	print_r( $sql );

    return $sql;


    //
    /*
    $_eb_query['post_type'] = 'shop_order';
    $_eb_query['post_status'] = 'private';
    $_eb_query['orderby'] = 'ID';
    $_eb_query['order'] = 'DESC';
	
    return _eb_load_post_obj( $posts_per_page, $_eb_query );
    */
}

/*
 * https://codex.wordpress.org/Class_Reference/WP_Query
 * posts_per_page: số lượng bài viết cần lấy
 * _eb_query: gán giá trị để thực thi wordpres query
 * html: mặc định là sử dụng HTML của theme, file thread_node.html, nếu muốn sử dụng HTML riêng thì truyền giá trị HTML mới vào
 * not_set_not_in: mặc định là lọc các sản phẩm trùng lặp trên mỗi trang, nếu để bằng 1, sẽ bỏ qua chế độ lọc -> chấp nhận lấy trùng
 */
function _eb_load_post(
    $posts_per_page = 20,
    $_eb_query = array(),
    $html = __eb_thread_template,
    $not_set_not_in = 0,
    $other_options = array()
) {
    global $___eb_post__not_in;
    //	echo '<!-- POST NOT IN: ' . $___eb_post__not_in . ' -->' . "\n";

    // lọc các sản phẩm trùng nhau
    if ($___eb_post__not_in != '' && $not_set_not_in === 0) {
        $_eb_query['post__not_in'] = explode(',', substr($___eb_post__not_in, 1));
    }
    /*
    echo '<!-- ';
    print_r( $_eb_query );
    echo ' -->';
    */

    // ignore_sticky_posts -> wp mặc định nó lấy các bài viết được ghim -> bỏ qua nó để lấy cho chuẩn
    if (isset($_eb_query['post__in']) && !isset($_eb_query['ignore_sticky_posts'])) {
        $_eb_query['ignore_sticky_posts'] = 1;
    }

    //
    $show_sql_query = 0;
    if (isset($other_options['show_sql_query'])) {
        $show_sql_query = 1;
        unset($other_options['show_sql_query']);
    }

    //
    $sql = _eb_load_post_obj($posts_per_page, $_eb_query);
    if ($show_sql_query == 1) {
        //print_r($_eb_query);
        echo $sql->request . '<br>' . "\n";
    }
    if (isset($_eb_query['no_found_rows']) && $_eb_query['no_found_rows'] == true && isset($_eb_query['fields']) && $_eb_query['fields'] == 'ids') {
        //print_r($sql);
        return $sql->post_count;
    }

    // TEST
    /*
	global $act;
	if ( $act == 'ebsearch' ) {
//	if ( $_eb_query['post_type'] == 'blog' ) {
		print_r( $sql );
//		print_r( $_eb_query );
		exit();
	}
	*/

    //
    if (!isset($other_options['pot_tai'])) {
        $other_options['pot_tai'] = 'category';
    }

    //
    $str = '';

    //
    while ($sql->have_posts()) {

        $sql->the_post();
        //		the_content();

        //
        if ($not_set_not_in == 0) {
            $___eb_post__not_in .= ',' . $sql->post->ID;
        }

        //
        $str .= EBE_select_thread_list_all($sql->post, $html, $other_options['pot_tai'], $other_options);
    }

    //
    wp_reset_postdata();

    // xác định có xóa dòng hay không
    if (!isset($other_options['auto_del_line'])) {
        $other_options['auto_del_line'] = 'yes';
    }

    // ưu tiên sử dụng URL tương đối -> có thể gây lỗi trên 1 số phiên bản -> bỏ
    //	return str_replace( web_link, '', _eb_supper_del_line( $str ) );
    if ($other_options['auto_del_line'] != 'no') {
        return _eb_supper_del_line($str);
    }
    //	return str_replace( '{tmp.post_zero}', EBE_get_lang('post_zero'), _eb_supper_del_line( $str ) );
    return $str;
}


function _eb_checkPostServerClient()
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        die('<h1>POST DIE</h1>');
        exit();
    }


    $checkPostServer = $_SERVER['HTTP_HOST'];
    $checkPostServer = str_replace('www.', '', $checkPostServer);
    //	$checkPostServer = explode ( '/', $checkPostServer );
    //	$checkPostServer = $checkPostServer [0];

    $checkPostClient = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    if ($checkPostClient != '') {
        $checkPostClient = explode('//', $checkPostClient);
        $checkPostClient = $checkPostClient[1];
        $checkPostClient = str_replace('www.', '', $checkPostClient);
        $checkPostClient = explode('/', $checkPostClient);
        $checkPostClient = $checkPostClient[0];
    }

    //
    if ($checkPostClient == '' || strtolower($checkPostServer) != strtolower($checkPostClient)) {
        die('<h1>REFERER DIE</h1>');
        exit();
    }


    /*
     * xử lý an toàn cho chuỗi
     */

    // kiểm tra get_magic_quotes_gpc đang bật hay tắt
    // Magic_quotes_gpc là 1 giá trị tùy chọn bật chế độ tự động thêm ký tự escape vào trước các ký tự đặc biệt như: nháy đơn ('), nháy kép ("), dấu backslash (\) khi nó đc POST hoặc GET từ client lên
    $magic_quotes = 0;
    //	if ( function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc () ) {
    $magic_quotes = 1;
    //	}
    //	echo $magic_quotes . '<br>' . "\n";

    //
    foreach ($_POST as $k => $v) {
        //		if ( $v != '' && gettype( $v ) == 'string' ) {
        if (gettype($v) == 'string') {
            // nếu Magic_quotes_gpc đang tắt -> loại bỏ ký tự đặc biệt
            //				if ( $magic_quotes == 1 ) {
            //					$v = stripslashes ( $v );
            //				}

            // nếu Magic_quotes_gpc đang tắt -> add dữ liệu an toàn thủ công vào
            if ($magic_quotes == 0) {
                // xử lý an toàn cho chuỗi
                $v = addslashes($v);

                // mysqli_real_escape_string tương tự như addslashes, nhưng công việc sẽ do mysql xử lý
                //				$str = mysqli_real_escape_string ( $str );

                $_POST[$k] = $v;
            }
        }
    }
    //	print_r( $_POST );


    //
    return $_POST;
}

//
function EBE_stripPostServerClient()
{
    //	global $_POST;

    //	print_r( $_POST );
    foreach ($_POST as $k => $v) {
        if (gettype($v) == 'string') {
            $v = trim($v);
            $v = strip_tags($v);
            $_POST[$k] = $v;
        }
    }
    return $_POST;
}

function WGR_check_ebnonce($key = '_ebnonce')
{

    //
    //echo $_SERVER['HTTP_REFERER'] . '<br>';

    //
    if (!isset($_POST[$key])) {
        _eb_alert('nonce?');
        return false;
    }

    if ($_SERVER['HTTP_REFERER'] != $_POST[$key]) {
        _eb_alert('nonce token?');
        return false;
    }

    //
    return true;
}


function _eb_checkDevice()
{
    if (wp_is_mobile()) {
        return 'mobile';
    }
    // mặc định cho rằng đây là PC
    return 'pc';
}

function _eb_checkDevice_v1()
{
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        // lấy thông tin hệ điều hành của người dùng
        $_ebArrUAAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        // mảng các thiết bị mobile chuyên dụng
        $_ebArrMobi = array('midp', 'j2me', 'avantg', 'ipad', 'iphone', 'docomo', 'novarra', 'palmos', 'palmsource', '240x320', 'opwv', 'chtml', 'pda', 'windows ce', 'mmp/', 'mib/', 'symbian', 'wireless', 'nokia', 'hand', 'mobi', 'phone', 'cdm', 'up.b', 'audio', 'sie-', 'sec-', 'samsung', 'htc', 'mot-', 'mitsu', 'sagem', 'sony', 'alcatel', 'lg', 'erics', 'vx', 'nec', 'philips', 'mmm', 'xx', 'panasonic', 'sharp', 'wap', 'sch', 'rover', 'pocket', 'benq', 'java', 'pt', 'pg', 'vox', 'amoi', 'bird', 'compal', 'kg', 'voda', 'sany', 'kdd', 'dbt', 'sendo', 'sgh', 'gradi', 'jb', 'dddi', 'moto', 'opera mobi', 'opera mini', 'android');
        foreach ($_ebArrMobi as $k => $v) {
            // nếu không xác định được chuỗi
            if (strpos($_ebArrUAAgent, $v) === false) {
                // ~> bỏ qua ko làm gì cả
            }
            // nếu tìm được -> trả về thông tin rằng đây là thiết bị mobile
            else {
                return 'mobile';
                break;
            }
        }
    }
    // mặc định cho rằng đây là PC
    return 'pc';
}


// Chuyển ký tự UTF-8 -> ra bảng mã mới
// bảng mã dịch ngược lại mã escape
function _eb_arr_escape_fix_content()
{
    return array(
        'á' => '%E1',
        'à' => '%E0',
        'ả' => '%u1EA3',
        'ã' => '%E3',
        'ạ' => '%u1EA1',
        'ă' => '%u0103',
        'ắ' => '%u1EAF',
        'ặ' => '%u1EB7',
        'ằ' => '%u1EB1',
        'ẳ' => '%u1EB3',
        'ẵ' => '%u1EB5',
        'â' => '%E2',
        'ấ' => '%u1EA5',
        'ầ' => '%u1EA7',
        'ẩ' => '%u1EA9',
        'ẫ' => '%u1EAB',
        'ậ' => '%u1EAD',
        'Á' => '%C1',
        'À' => '%C0',
        'Ả' => '%u1EA2',
        'Ã' => '%C3',
        'Ạ' => '%u1EA0',
        'Ă' => '%u0102',
        'Ắ' => '%u1EAE',
        'Ặ' => '%u1EB6',
        'Ằ' => '%u1EB0',
        'Ẳ' => '%u1EB2',
        'Ẵ' => '%u1EB4',
        'Â' => '%C2',
        'Ấ' => '%u1EA4',
        'Ầ' => '%u1EA6',
        'Ẩ' => '%u1EA8',
        'Ẫ' => '%u1EAA',
        'Ậ' => '%u1EAC',
        'đ' => '%u0111',
        'Đ' => '%u0110',
        'é' => '%E9',
        'è' => '%E8',
        'ẻ' => '%u1EBB',
        'ẽ' => '%u1EBD',
        'ẹ' => '%u1EB9',
        'ê' => '%EA',
        'ế' => '%u1EBF',
        'ề' => '%u1EC1',
        'ể' => '%u1EC3',
        'ễ' => '%u1EC5',
        'ệ' => '%u1EC7',
        'É' => '%C9',
        'È' => '%C8',
        'Ẻ' => '%u1EBA',
        'Ẽ' => '%u1EBC',
        'Ẹ' => '%u1EB8',
        'Ê' => '%CA',
        'Ế' => '%u1EBE',
        'Ề' => '%u1EC0',
        'Ể' => '%u1EC2',
        'Ễ' => '%u1EC4',
        'Ệ' => '%u1EC6',
        'í' => '%ED',
        'ì' => '%EC',
        'ỉ' => '%u1EC9',
        'ĩ' => '%u0129',
        'ị' => '%u1ECB',
        'Í' => '%CD',
        'Ì' => '%CC',
        'Ỉ' => '%u1EC8',
        'Ĩ' => '%u0128',
        'Ị' => '%u1ECA',
        'ó' => '%F3',
        'ò' => '%F2',
        'ỏ' => '%u1ECF',
        'õ' => '%F5',
        'ọ' => '%u1ECD',
        'ô' => '%F4',
        'ố' => '%u1ED1',
        'ồ' => '%u1ED3',
        'ổ' => '%u1ED5',
        'ỗ' => '%u1ED7',
        'ộ' => '%u1ED9',
        'ơ' => '%u01A1',
        'ớ' => '%u1EDB',
        'ờ' => '%u1EDD',
        'ở' => '%u1EDF',
        'ỡ' => '%u1EE1',
        'ợ' => '%u1EE3',
        'Ó' => '%D3',
        'Ò' => '%D2',
        'Ỏ' => '%u1ECE',
        'Õ' => '%D5',
        'Ọ' => '%u1ECC',
        'Ô' => '%D4',
        'Ố' => '%u1ED0',
        'Ồ' => '%u1ED2',
        'Ổ' => '%u1ED4',
        'Ỗ' => '%u1ED6',
        'Ộ' => '%u1ED8',
        'Ơ' => '%u01A0',
        'Ớ' => '%u1EDA',
        'Ờ' => '%u1EDC',
        'Ở' => '%u1EDE',
        'Ỡ' => '%u1EE0',
        'Ợ' => '%u1EE2',
        'ú' => '%FA',
        'ù' => '%F9',
        'ủ' => '%u1EE7',
        'ũ' => '%u0169',
        'ụ' => '%u1EE5',
        'ư' => '%u01B0',
        'ứ' => '%u1EE9',
        'ừ' => '%u1EEB',
        'ử' => '%u1EED',
        'ữ' => '%u1EEF',
        'ự' => '%u1EF1',
        'Ú' => '%DA',
        'Ù' => '%D9',
        'Ủ' => '%u1EE6',
        'Ũ' => '%u0168',
        'Ụ' => '%u1EE4',
        'Ư' => '%u01AF',
        'Ứ' => '%u1EE8',
        'Ừ' => '%u1EEA',
        'Ử' => '%u1EEC',
        'Ữ' => '%u1EEE',
        'Ự' => '%u1EF0',
        'ý' => '%FD',
        'ỳ' => '%u1EF3',
        'ỷ' => '%u1EF7',
        'ỹ' => '%u1EF9',
        'ỵ' => '%u1EF5',
        'Ý' => '%DD',
        'Ỳ' => '%u1EF2',
        'Ỷ' => '%u1EF6',
        'Ỹ' => '%u1EF8',
        'Ỵ' => '%u1EF4'
    );
}

function _eb_arr_block_fix_content()
{
    // https://www.google.com/search?q=site:charbase.com+%E1%BB%9D#q=site:charbase.com+%E1%BA%A3
    return array(
        'á' => '\u00e1',
        'à' => '\u00e0',
        'ả' => '\u1ea3',
        'ã' => '\u00e3',
        'ạ' => '\u1ea1',
        'ă' => '\u0103',
        'ắ' => '\u1eaf',
        'ặ' => '\u1eb7',
        'ằ' => '\u1eb1',
        'ẳ' => '\u1eb3',
        'ẵ' => '\u1eb5',
        'â' => '\u00e2',
        'ấ' => '\u1ea5',
        'ầ' => '\u1ea7',
        'ẩ' => '\u1ea9',
        'ẫ' => '\u1eab',
        'ậ' => '\u1ead',
        'Á' => '\u00c1',
        'À' => '\u00c0',
        'Ả' => '\u1ea2',
        'Ã' => '\u00c3',
        'Ạ' => '\u1ea0',
        'Ă' => '\u0102',
        'Ắ' => '\u1eae',
        'Ặ' => '\u1eb6',
        'Ằ' => '\u1eb0',
        'Ẳ' => '\u1eb2',
        'Ẵ' => '\u1eb4',
        'Â' => '\u00c2',
        'Ấ' => '\u1ea4',
        'Ầ' => '\u1ea6',
        'Ẩ' => '\u1ea8',
        'Ẫ' => '\u1eaa',
        'Ậ' => '\u1eac',
        'đ' => '\u0111',
        'Đ' => '\u0110',
        'é' => '\u00e9',
        'è' => '\u00e8',
        'ẻ' => '\u1ebb',
        'ẽ' => '\u1ebd',
        'ẹ' => '\u1eb9',
        'ê' => '\u00ea',
        'ế' => '\u1ebf',
        'ề' => '\u1ec1',
        'ể' => '\u1ec3',
        'ễ' => '\u1ec5',
        'ệ' => '\u1ec7',
        'É' => '\u00c9',
        'È' => '\u00c8',
        'Ẻ' => '\u1eba',
        'Ẽ' => '\u1ebc',
        'Ẹ' => '\u1eb8',
        'Ê' => '\u00ca',
        'Ế' => '\u1ebe',
        'Ề' => '\u1ec0',
        'Ể' => '\u1ec2',
        'Ễ' => '\u1ec4',
        'Ệ' => '\u1ec6',
        'í' => '\u00ed',
        'ì' => '\u00ec',
        'ỉ' => '\u1ec9',
        'ĩ' => '\u0129',
        'ị' => '\u1ecb',
        'Í' => '\u00cd',
        'Ì' => '\u00cc',
        'Ỉ' => '\u1ec8',
        'Ĩ' => '\u0128',
        'Ị' => '\u1eca',
        'ó' => '\u00f3',
        'ò' => '\u00f2',
        'ỏ' => '\u1ecf',
        'õ' => '\u00f5',
        'ọ' => '\u1ecd',
        'ô' => '\u00f4',
        'ố' => '\u1ed1',
        'ồ' => '\u1ed3',
        'ổ' => '\u1ed5',
        'ỗ' => '\u1ed7',
        'ộ' => '\u1ed9',
        'ơ' => '\u01a1',
        'ớ' => '\u1edb',
        'ờ' => '\u1edd',
        'ở' => '\u1edf',
        'ỡ' => '\u1ee1',
        'ợ' => '\u1ee3',
        'Ó' => '\u00d3',
        'Ò' => '\u00d2',
        'Ỏ' => '\u1ece',
        'Õ' => '\u00d5',
        'Ọ' => '\u1ecc',
        'Ô' => '\u00d4',
        'Ố' => '\u1ed0',
        'Ồ' => '\u1ed2',
        'Ổ' => '\u1ed4',
        'Ỗ' => '\u1ed6',
        'Ộ' => '\u1ed8',
        'Ơ' => '\u01a0',
        'Ớ' => '\u1eda',
        'Ờ' => '\u1edc',
        'Ở' => '\u1ede',
        'Ỡ' => '\u1ee0',
        'Ợ' => '\u1ee2',
        'ú' => '\u00fa',
        'ù' => '\u00f9',
        'ủ' => '\u1ee7',
        'ũ' => '\u0169',
        'ụ' => '\u1ee5',
        'ư' => '\u01b0',
        'ứ' => '\u1ee9',
        'ừ' => '\u1eeb',
        'ử' => '\u1eed',
        'ữ' => '\u1eef',
        'ự' => '\u1ef1',
        'Ú' => '\u00da',
        'Ù' => '\u00d9',
        'Ủ' => '\u1ee6',
        'Ũ' => '\u0168',
        'Ụ' => '\u1ee4',
        'Ư' => '\u01af',
        'Ứ' => '\u1ee8',
        'Ừ' => '\u1eea',
        'Ử' => '\u1eec',
        'Ữ' => '\u1eee',
        'Ự' => '\u1ef0',
        'ý' => '\u00fd',
        'ỳ' => '\u1ef3',
        'ỷ' => '\u1ef7',
        'ỹ' => '\u1ef9',
        'ỵ' => '\u1ef5',
        'Ý' => '\u00dd',
        'Ỳ' => '\u1ef2',
        'Ỷ' => '\u1ef6',
        'Ỹ' => '\u1ef8',
        'Ỵ' => '\u1ef4'
    );
}

function _eb_str_text_fix_js_content($str)
{
    if ($str == '') {
        return '';
    }

    //	$str = iconv('UTF-16', 'UTF-8', $str);
    //	$str = mb_convert_encoding($str, 'UTF-8', 'UTF-16');
    //	$str = mysqli_escape_string($str);
    //	$str = htmlentities($str, ENT_COMPAT, 'UTF-16');
    $arr = _eb_arr_block_fix_content();

    //
    foreach ($arr as $k => $v) {
        if ($v != '') {
            $str = str_replace($k, $v, $str);
        }
    }
    return $str;
}

function _eb_str_block_fix_content($str)
{
    if ($str == '') {
        return '';
    }

    //	$str = iconv('UTF-16', 'UTF-8', $str);
    //	$str = mb_convert_encoding($str, 'UTF-8', 'UTF-16');
    //	$str = mysqli_escape_string($str);
    //	$str = htmlentities($str, ENT_COMPAT, 'UTF-16');
    // https://www.google.com/search?q=site:charbase.com+%E1%BB%9D#q=site:charbase.com+%E1%BA%A3
    $arr = array(
        // Loại bỏ dòng trắng
        //			';if (' => ';if(',
        //			'{if (' => '{if(',
        //			'}if (' => '}if(',
        //			'} else if (' => '}else if(',
        //			'} else {' => '}else{',
        //			'}else {' => '}else{',
        //			';for (' => ';for(',
        //			'}for (' => '}for(',
        //			'function (' => 'function(',
        //			//
        //			' != ' => '!=',
        //			' == ' => '==',
        //			' || ' => '||',
        //			' -= ' => '-=',
        //			' += ' => '+=',
        //			' && ' => '&&',
        //			//
        //			') {' => '){',
        //			';}' => '}',
        //			' = \'' => '=\'',
        //			'\' +' => '\'+',
        //			'+ \'' => '+\'',
        //			' = ' => '=',
        //			'}, {' => '},{',
        //			'}, ' => '},',
        '\'' => '\\\'',
        '"' => '&quot;',
        //		'' => ''
    );

    //
    $str = _eb_str_text_fix_js_content(str_replace('\\', '\\\\', $str));

    //
    foreach ($arr as $k => $v) {
        if ($v != '') {
            $str = str_replace($k, $v, $str);
        }
    }
    //	$str = str_replace('\\', '/', str_replace("'", "\'", $str) );
    return $str;
}


function _eb_postUrlContent($url, $data = '', $head = 0)
{
    global $post_get_cc;

    return $post_get_cc->post($url, $data, $head);
}

function _eb_post_content($url, $data = '', $head = 0)
{
    return _eb_postUrlContent($url, $data, $head);
}

function _eb_getUrlContent($url, $agent = '', $options = array(), $head = 0)
{
    global $post_get_cc;

    return $post_get_cc->get($url, $agent, $options, $head);
}

function _eb_get_content($url, $agent = '', $options = array(), $head = 0)
{
    return _eb_getUrlContent($url, $agent, $options, $head);
}


// fix URL theo 1 chuẩn nhất định
function _eb_fix_url($url)
{
    //echo strstr( _eb_full_url(), '//' ) . '<br>' . "\n";
    //echo strstr( $url, '//' ) . '<br>' . "\n";
    //var_dump( strpos( _eb_full_url(), '?' ) );
    //var_dump( strpos( strstr( _eb_full_url(), '//' ), strstr( $url, '//' ) ) );
    //echo $url . '<br>' . "\n";
    //echo _eb_full_url() . '<br>' . "\n";
    //die(__FILE__ . ':' . __LINE__);
    return false;

    //
    //	if ( strstr( $url, '//' ) != strstr( _eb_full_url (), '//' ) ) {
    // nếu không có dấu ? -> không có tham số nào được truyền trên URL
    if (
        strpos(_eb_full_url(), '?') === false
        // nếu URL khác nhau
        &&
        strpos(strstr(_eb_full_url(), '//'), strstr($url, '//')) === false
    ) {
        //	if ( count( explode( strstr( $url, '//' ), strstr( _eb_full_url (), '//' ) ) ) == 1 ) {

        //		header ( 'Location:' . $url, true, 301 );

        wp_redirect($url, 301);

        exit();
    }

    return true;
}

// short link
function _eb_s_link($id, $seo = 'p')
{
    return web_link . '?' . $seo . '=' . $id;
}

// link cho sản phẩm
function _eb_p_link($id, $short_link = true)
{
    $strCacheFilter = 'prod_link' . $id;
    $a = _eb_get_static_html($strCacheFilter, '', '', eb_default_cache_time);
    if ($a == false) {
        $a = get_the_permalink($id);
        if ($a == '') {
            if ($short_link == true) {
                $a = _eb_s_link($id);
            } else {
                $a = web_link . '404?redirect_from=' . urlencode(_eb_full_url());
            }
        }

        //
        _eb_get_static_html($strCacheFilter, $a, '', 300);
    }
    //	echo $a . '<br>' . "\n";

    return $a;
}


// link cho phân nhóm
$arr_cache_for_get_cat_url = array();

// https://codex.wordpress.org/Function_Reference/get_category_link
// lấy link nhóm theo object
function _eb_cs_link($v)
{
    return _eb_c_link($v->term_id, $v->taxonomy);
}

// lấy link nhóm 1 cách chi tiết
function _eb_c_link($id, $taxx = 'category')
{
    global $arr_cache_for_get_cat_url;

    //
    if (isset($arr_cache_for_get_cat_url[$id])) {
        return $arr_cache_for_get_cat_url[$id];
    }

    $strCacheFilter = 'cat_link' . $id;
    //	$a = _eb_get_static_html ( $strCacheFilter, '', '', eb_default_cache_time );
    $a = _eb_get_static_html($strCacheFilter, '', '', 600);
    //	$a = false;
    //	$a = _eb_get_static_html ( $strCacheFilter, '', '', 5 );
    if ($a == false) {
        $a = '';

        //
        //		echo $taxx . '<br>' . "\n";
        if ($taxx == '') {
            $taxx = 'category';
        }
        //		echo $taxx . '<br>' . "\n";

        //
        $term = get_term($id, $taxx);
        //		echo $id . '<br>' . "\n";
        //		echo $taxx . '<br>' . "\n";
        //		print_r( $term );
        if (gettype($term) == 'object' && !isset($term->errors)) {
            $a = get_term_link($term, $taxx);

            /*
            echo '<!-- ';
            echo $id . '<br>' . "\n";
            echo $taxx . '<br>' . "\n";
            print_r( $term );
            echo ' -->';
            */
        }
        /*
        else {
        	$term = WGR_get_all_term( $id );
        }
        echo 'aaaaaaaaa';
        */

        /*
        if ( $taxx == '' || $taxx == 'category' ) {
        	$a = get_category_link( $id );
        }
        else {
        	*/
        //			$a = get_term_link( get_term_by( 'id', $id, $taxx ), $taxx );
        //			$a = get_term_link( $term, $taxx );
        //		}
        //		echo $a . '<br>' . "\n";

        //
        if (isset($a->errors) || $a == '') {
            //			print_r($a);

            // thử chức năng tìm tất cả các term
            $a = WGR_get_all_term($id);
            //			print_r($a);
            //			echo 'aaaaaaaaaa<br>';

            // nếu tìm được -> tạo link luôn
            if (!isset($a->errors)) {
                $a = get_term_link($a, $a->taxonomy);
            }
            /*
            else {
            	$a = '';
            }
            */

            // lấy theo blog
            /*
//			if ( $taxx != '' ) {
//				$a = get_term_link( get_term_by( 'id', $id, $taxx ), $taxx );
//			}
//			else {
//				$a = get_term_link( get_term_by( 'id', $id, EB_BLOG_POST_LINK ), EB_BLOG_POST_LINK );
				$a = get_term_link( get_term( $id, EB_BLOG_POST_LINK ), EB_BLOG_POST_LINK );
				if ( isset($a->errors) || $a == '' ) {
//					$a = get_term_link( get_term_by( 'id', $id, 'post_tag' ), 'post_tag' );
					$a = get_term_link( get_term( $id, 'post_tag' ), 'post_tag' );
					
					// lấy theo post_tag
					if ( isset($a->errors) || $a == '' ) {
//						$a = get_term_link( get_term_by( 'id', $id, 'post_options' ), 'post_options' );
						$a = get_term_link( get_term( $id, 'post_options' ), 'post_options' );
					}
				}
//			}
			*/

            //
            if (isset($a->errors) || $a == '') {
                //				$a = '#';

                // trả về link lỗi luôn, không lưu cache
                return _eb_c_short_link($id, $taxx);
            }
        }
        // xóa ký tự đặc biệt khi rút link category
        $a = str_replace('/./', '/', $a);

        // nếu tên file là dạng short link -> thử tạo thủ công
        if (strpos($a, '?cat=') !== false || strpos($a, '&cat=') !== false) {
            // lấy URL trực tiếp luôn
            if ($taxx == 'category' || $taxx == 'post_tag') {
                if ($taxx == 'post_tag') {
                    $category_base = 'tag_base';
                } else {
                    $category_base = 'category_base';
                }
                $category_base = get_option($category_base);
                //				$category_base = _eb_get_option($category_base);

                if ($category_base == '.') {
                    $category_base = '';
                } else {
                    if ($category_base == '') {
                        if ($taxx == 'post_tag') {
                            $category_base = 'tag';
                        } else {
                            $category_base = $taxx;
                        }
                    }
                    $category_base .= '/';
                }
                $a = web_link . $category_base . $term->slug;
            }
            // custom taxonomy
            else {
                $a = web_link . $taxx . '/' . $term->slug;
            }
            //			echo $a . '<br>' . "\n";

            //			return $a;
        }

        // kiểm tra lại lần nữa
        if (strpos($a, '?cat=') !== false || strpos($a, '&cat=') !== false) {
        }
        // lưu tên file vào cache nếu không phải short link
        else {
            _eb_get_static_html($strCacheFilter, $a, '', 300);
        }
    }
    //	echo $a . '<br>' . "\n";

    //
    $arr_cache_for_get_cat_url[$id] = $a;

    return $a;
}

function _eb_c_short_link($id, $taxx = '')
{
    if ($taxx != 'category') {
        return web_link . '?taxonomy=' . $taxx . '&cat=' . $id;
    }
    return web_link . '?cat=' . $id;
}

function _eb_c_link_v1($id, $taxx = 'category')
{
    global $arr_cache_for_get_cat_url;

    //
    if (isset($arr_cache_for_get_cat_url[$id])) {
        return $arr_cache_for_get_cat_url[$id];
    }

    $strCacheFilter = 'cat_link' . $id;
    $a = _eb_get_static_html($strCacheFilter, '', '', eb_default_cache_time);
    //		$a = _eb_get_static_html ( $strCacheFilter, '', '', 5 );
    if ($a == false) {

        //
        $a = get_category_link($id);
        //		$a = get_term_link( get_term( $id, $taxx ), $taxx );

        // nếu trùng với short link -> không ghi cache nữa
        /*
        if ( $a == $default_return ) {
        	return $a;
        }
        */

        //
        if (isset($a->errors) || $a == '') {
            //			print_r($a);

            //			$tem = get_term_by( 'id', $id, EB_BLOG_POST_LINK );
            $tem = get_term($id, EB_BLOG_POST_LINK);

            // lấy theo blog
            $a = get_term_link($tem, EB_BLOG_POST_LINK);
            //				$a = get_term_link( get_term( $id, EB_BLOG_POST_LINK ), EB_BLOG_POST_LINK );

            // lấy theo post_options
            if (isset($a->errors) || $a == '') {
                $a = get_term_link($tem, 'post_options');

                // lấy theo post_tag
                if (isset($a->errors) || $a == '') {
                    $a = get_term_link($tem, 'post_tag');
                }
            }
        }

        //
        if (isset($a->errors) || $a == '') {
            $a = '#';
        }
        // xóa ký tự đặc biệt khi rút link category
        else {
            $a = str_replace('/./', '/', $a);
        }
        //			echo $id . ' -> ' . $a . '<br>' . "\n";

        //
        _eb_get_static_html($strCacheFilter, $a, '', 300);
    }
    //		echo $a . '<br>' . "\n";

    //
    $arr_cache_for_get_cat_url[$id] = $a;

    return $a;
}

// blog
function _eb_b_link($id, $seo = '')
{
    return _eb_p_link($id);
}

// blog group
function _eb_bs_link($id, $seo = '')
{
    return _eb_c_link($id, EB_BLOG_POST_LINK);
}

//
include EB_THEME_PLUGIN_INDEX . 'functionsP2_1.php';
include EB_THEME_PLUGIN_INDEX . 'functionsP2_2.php';
include EB_THEME_PLUGIN_INDEX . 'functionsP2_3.php';
