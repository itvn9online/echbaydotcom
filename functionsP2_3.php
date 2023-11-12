<?php


//
function _eb_get_full_category_v2($this_id = 0, $taxx = 'category', $get_full_link = 0, $op = array())
{
    //	global $web_link;

    //
    $op['taxonomy'] = $taxx;
    //	$op['hide_empty'] = 0;
    $op['parent'] = $this_id;

    if (!isset($op['hide_empty'])) {
        $op['hide_empty'] = eb_code_tester == true ? 0 : 1;
    }

    //
    $arr = get_categories($op);
    //	print_r($arr);

    //
    /*
    $link_for_taxonomy = '';
    if ( $taxx != 'category' ) {
    	$link_for_taxonomy = 'taxonomy=' . $taxx . '&';
    }
    */

    //
    $str = '';
    foreach ($arr as $v) {
        //		print_r($v);

        //
        //		$c_link = _eb_cs_link( $v );
        if ($get_full_link == 1) {
            $c_link = _eb_cs_link($v);
            //			echo $c_link . '<br>' . "\n";
        } else {
            //			$c_link = web_link . '?' . $link_for_taxonomy . 'cat=' . $v->term_id;
            $c_link = _eb_c_short_link($v->term_id, $taxx);
        }

        //
        //		$cat_order = 0;
        //		if ( $this_id == 0 ) {
        $cat_order = _eb_number_only(_eb_get_cat_object($v->term_id, '_eb_category_order', 0));
        //		}

        //
        $str .= ',{id:' . $v->term_id . ',ten:"' . _eb_str_block_fix_content($v->name) . '",slug:"' . $v->slug . '",lnk:"' . str_replace('/', '\/', $c_link) . '",order:' . $cat_order . ',hidden:' . _eb_get_cat_object($v->term_id, '_eb_category_hidden', 0) . ',avt:"' . _eb_get_cat_object($v->term_id, '_eb_category_avt') . '",icon:"' . _eb_get_cat_object($v->term_id, '_eb_category_favicon') . '",arr:[' . _eb_get_full_category_v2($v->term_id, $taxx, $get_full_link) . ']}';
    }
    $str = substr($str, 1);

    //
    return $str;
}


/*
function WGR_get_arr_taxonomy ( $tax = 'category' ) {
	$arrs = get_categories( array(
		'taxonomy' => $tax,
		'parent' => 0,
	) );
//	print_r( $arrs );
	
	//
	$oders = array();
	$options = array();
	
	//
	foreach ( $arrs as $v ) {
		$oders[ $v->term_id ] = (int) _eb_get_cat_object( $v->term_id, '_eb_category_order', 0 );
		$options[$v->term_id] = $v;
	}
	arsort( $oders );
//	print_r( $oders );
	
	//
	foreach ( $oders as $k => $v ) {
		$v = $options[$k];
		print_r($v);
	}
}
*/


function _eb_get_tax_post_options($arr_option = array(), $taxo = 'post_options')
{
    //	global $func;

    /*
     * arr_option -> bao gồm các giá trị sau:
     * ul_before: nội dung khi bắt đầu UL -> trước LI đầu
     * ul_after: nội dung khi kết thúc UL -> sau LI cuối
     * ul_class: class CSS cho thẻ UL
     *
     * select_before: nội dung khi bắt đầu SELECT -> trước OPTION đầu
     * select_after: nội dung khi kết thúc SELECT -> sau OPTION cuối
     * select_class: class CSS cho thẻ SELECT
     */


    $arrs = get_categories(array(
        'taxonomy' => $taxo,
        //		'hide_empty' => 0,
        'parent' => 0,
    ));

    //
    $oders = WGR_order_and_hidden_taxonomy($arrs, 1);
    /*
    $oders = array();
    $options = array();
	
    //
    foreach ( $arrs as $v ) {
    	$oders[ $v->term_id ] = (int) _eb_get_cat_object( $v->term_id, '_eb_category_order', 0 );
    	$options[$v->term_id] = $v;
    }
    arsort( $oders );
    */

    //
    $javascripts = '';
    $strs = '';
    $selects = '';

    //
    foreach ($oders as $k => $v) {
        //		$v = $options[$k];

        //
        $arr = get_categories(array(
            'taxonomy' => 'post_options',
            //			'hide_empty' => 0,
            'parent' => $v->term_id,
        ));

        //
        $oder = WGR_order_and_hidden_taxonomy($arr, 1);
        /*
        $oder = array();
        $option = array();
		
        //
        foreach ( $arr as $v2 ) {
        	$oder[ $v2->term_id ] = (int) _eb_get_cat_object( $v2->term_id, '_eb_category_order', 0 );
        	$option[$v2->term_id] = $v2;
        }
        arsort( $oder );
        */

        //
        $javascript = '';
        $str = '';
        $select = '';
        foreach ($oder as $k2 => $v2) {
            //			$v2 = $option[$k2];

            $op_link = _eb_cs_link($v2);

            $str .= '<li><a data-parent="' . $v->term_id . '" data-id="' . $v2->term_id . '" href="' . $op_link . '">' . $v2->name . '</a></li>';

            $select .= '<option value="' . $v2->term_id . '">' . $v2->name . '</option>';

            $javascript .= ',{id:"' . $v2->term_id . '",ten:"' . $v2->name . '",url:"' . $op_link . '"}';
        }

        //
        if ($str != '') {

            //
            $strs .= '
			<li>
				<div class="search-advanced-padding click-add-id-to-sa">
					<div class="search-advanced-name"><a data-parent="0" data-id="' . $v->term_id . '" href="' . _eb_cs_link($v) . '" title="' . $v->name . '">' . $v->name . ' <i class="fa fa-caret-down"></i></a></div>
					<ul class="sub-menu">
						' . $str . '
					</ul>
				</div>
			</li>';

            //
            $selects .= '
			<select class="change-add-id-to-sa">
				<option value="0">' . $v->name . '</option>
				' . $select . '
			</select>';

            //
            $javascripts .= ',{id:"' . $v->term_id . '",ten:"' . $v->name . '",arr:[' . substr($javascript, 1) . ']}';
        }
    }

    // tổng hợp dữ liệu trả về
    if (!isset($arr_option['ul_before'])) {
        $arr_option['ul_before'] = '';
    }
    if (!isset($arr_option['ul_after'])) {
        $arr_option['ul_after'] = '';
    }
    if (!isset($arr_option['ul_class'])) {
        $arr_option['ul_class'] = '';
    }

    if (!isset($arr_option['select_before'])) {
        $arr_option['select_before'] = '';
    }
    if (!isset($arr_option['select_after'])) {
        $arr_option['select_after'] = '';
    }
    if (!isset($arr_option['select_class'])) {
        $arr_option['select_class'] = '';
    }


    // js
    //	if ( $type == 'js' ) {
    //	}
    // html
    //	else {
    return '
		<ul class="widget-search-advanced ul-eb-postoptions ' . $arr_option['ul_class'] . '">' . $arr_option['ul_before'] . $strs . $arr_option['ul_after'] . '</ul>
		<div class="select-eb-postoptions ' . $arr_option['select_class'] . '2 d-none">' . $arr_option['select_before'] . $selects . $arr_option['select_after'] . '</div>
		<script type="text/javascript">var js_eb_postoptions=[' . substr($javascripts, 1) . '];</script>';
    //	}
}


/*
 * chức năng thay thế cho hàm thread-remove-endbegin trên javascript
 */
function _eb_thread_remove_endbegin($arr, $begin = 0, $end = 0, $tag = '</li>')
{
    $arr = explode($tag, $arr);
    $str = '';
    //	$str = array();
    foreach ($arr as $k => $v) {
        if ($k >= $begin && $k <= $end) {
            $v = trim($v);
            if ($v != '') {
                $str .= '<!-- ' . $k . ' -->' . $v . $tag;
            }
            //			$str[] =  '<!-- ' . $k . ' -->' . $v;
        }
    }

    return $str;
    //	return implode( $tag, $str );
}


function _eb_selected($k, $v)
{
    return $k == $v ? ' selected="selected"' : '';
}


function _eb_parse_args($arr, $default)
{
    // sử dụng hàm của wp
    return wp_parse_args($arr, $default);
}

function _eb_widget_parse_args($arr, $default)
{
    // tìm ở mảng default -> nếu mảng chính không có thì gán thêm vào
    /*
    foreach ( $default as $k => $v ) {
    	if ( ! isset( $arr[$k] ) ) {
    		$arr[$k] = $v;
    	}
    }
    */

    // bỏ HTML cho mảng chính
    foreach ($arr as $k => $v) {
        $arr[$k] = strip_tags($v);
    }

    return $arr;
}


function _eb_get_option($name)
{
    global $wpdb;

    $sql = _eb_q("SELECT option_value
	FROM
		`" . $wpdb->options . "`
	WHERE
		option_name = '" . $name . "'
	ORDER BY
		option_id DESC
	LIMIT 0, 1");

    //
    //	print_r( $sql );
    if (!empty($sql)) {
        return WGR_stripslashes($sql[0]->option_value);
    }

    return '';
}

function WGR_get_option($name)
{
    return _eb_get_option($name);
}

function _eb_update_option($name, $value, $load = 'yes')
{
    if (trim($name) == '') {
        return WGR_delete_option($name);
    }

    //
    global $wpdb;

    // tạo mới nếu chưa có
    $sql = _eb_q("SELECT option_id
	FROM
		`" . $wpdb->options . "`
	WHERE
		option_name = '" . $name . "'");
    //	print_r( $sql );

    // xử lý an toàn cho chuỗi trước khi update
    $value = WGR_stripslashes(trim($value));
    //	if ( ! function_exists('get_magic_quotes_gpc') || ! get_magic_quotes_gpc () ) {
    $value = addslashes($value);
    //	}

    // create
    if (empty($sql)) {
        _eb_q("INSERT INTO
		`" . $wpdb->options . "`
		( option_name, option_value, autoload )
		VALUES
		( '" . $name . "', '" . $value . "', '" . $load . "' )", 0);
    }
    // update
    else {
        _eb_q("UPDATE `" . $wpdb->options . "`
		SET
			option_value = '" . $value . "',
			autoload = '" . $load . "'
		WHERE
			option_name = '" . $name . "'", 0);
    }

    return true;
}

function _eb_set_option($name, $value, $load = 'yes')
{
    return _eb_update_option($name, $value, $load);
}

function WGR_set_option($name, $value, $load = 'yes')
{
    return _eb_update_option($name, $value, $load);
}

function WGR_add_option($name, $value, $load = 'yes')
{
    return _eb_update_option($name, $value, $load);
}

function WGR_delete_option($name)
{
    global $wpdb;

    _eb_q("DELETE
	FROM
		`" . $wpdb->options . "`
	WHERE
		option_name = '" . $name . "'", 0);

    return true;
}

function WGR_del_option($name)
{
    return WGR_delete_option($name);
}


function EBE_create_in_con_voi_table($table, $pri_key, $arr)
{

    // mảng các cột mẫu
    //	print_r($arr);
    //	$arr = array_reverse( $arr );
    //	print_r($arr);

    // các cột hiện tại trong database
    $arr_check = _eb_q("SHOW TABLES LIKE '" . $table . "'");

    // nếu chưa có bảng hóa đơn
    if (count($arr_check) == 0) {

        //  -> thêm bảng -> thêm cột khóa chính
        $sql = trim('
		CREATE TABLE `' . $table . '` (
			`' . $pri_key . '` ' . strtoupper($arr[$pri_key]['type']) . ' NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		');
        _eb_q($sql, 0);

        // tạo khóa chính
        $sql = trim('ALTER TABLE `' . $table . '` ADD PRIMARY KEY(`' . $pri_key . '`)');
        _eb_q($sql, 0);

        // sửa lại cột
        $sql = trim('ALTER TABLE `' . $table . '` CHANGE `' . $pri_key . '` `' . $pri_key . '` ' . strtoupper($arr[$pri_key]['type']) . ' NOT NULL AUTO_INCREMENT');
        _eb_q($sql, 0);

        // lấy lại danh sách cột sau khi tạo mới
        $arr_check = _eb_q("SHOW TABLES LIKE '" . $table . "'");
    }
    //print_r( $arr_check );

    // cấu trúc bảng
    $strsql = _eb_q("DESCRIBE `" . $table . "`");
    //	print_r( $strsql );

    // chạy lệnh để kiểm tra cột có hay chưa
    $arr_current = array();
    foreach ($strsql as $v2) {
        //		print_r( $v2 );
        $v2 = (array)$v2;
        //		print_r( $v2 );

        $arr_current[$v2['Field']] = 1;
    }
    //	print_r( $arr_current );

    //
    $first_cloumn = $pri_key;
    foreach ($arr as $k => $v) {
        if (!isset($arr_current[$k])) {
            $v['field'] = $k;

            //
            $sql = 'ALTER TABLE `' . $table . '` ADD `' . $k . '` ' . strtoupper($v['type']) . ' ' . ($v['null'] == 'no' ? 'NOT NULL' : 'NULL') . ' AFTER `' . $first_cloumn . '`;';
            //			echo $sql . "\n";
            _eb_q($sql, 0);

            // UNIQUE
            if ($v['key'] == 'uni') {
                $sql = 'ALTER TABLE `' . $table . '` ADD UNIQUE(`' . $k . '`)';
                //				echo $sql . "\n";
                _eb_q($sql, 0);
            }
            // INDEX
            else if ($v['key'] == 'mul') {
                $sql = 'ALTER TABLE `' . $table . '` ADD INDEX(`' . $k . '`);';
                //				echo $sql . "\n";
                _eb_q($sql, 0);
            }
            //			echo $sql . "\n";
            //			_eb_q( $sql );
        }

        // thay đổi cột tiếp theo
        $first_cloumn = $k;
    }
}


function EBE_tao_bang_hoa_don_cho_echbay_wp()
{

    //
    EBE_create_in_con_voi_table('eb_in_con_voi', 'order_id', array(
        'order_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'pri',
            'default' => '',
            'extra' => 'auto_increment',
        ),
        'order_sku' => array(
            'type' => 'varchar(191)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'order_products' => array(
            'type' => 'longtext',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'order_total_price' => array(
            'type' => 'varchar(55)',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'order_customer' => array(
            'type' => 'longtext',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'order_agent' => array(
            'type' => 'longtext',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'order_ip' => array(
            'type' => 'varchar(191)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'order_time' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'order_update_time' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'order_status' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'tv_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        )
    ));


    EBE_create_in_con_voi_table('eb_details_in_con_voi', 'dorder_id', array(
        'dorder_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'pri',
            'default' => '',
            'extra' => 'auto_increment',
        ),
        'dorder_key' => array(
            'type' => 'varchar(191)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'dorder_name' => array(
            'type' => 'longtext',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'order_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        )
    ));


    // tạo bảng lưu trữ các bài viết sẽ xóa vĩnh viễn
    $arr_post_xml = array(
        'bpx_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'pri',
            'default' => '',
            'extra' => 'auto_increment',
        ),
        // Nội dung file xml
        'bpx_content' => array(
            'type' => 'longtext',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'bpx_agent' => array(
            'type' => 'varchar(255)',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'bpx_ip' => array(
            'type' => 'varchar(191)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'bpx_time' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'bpx_date' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'post_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'post_parent' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'post_type' => array(
            'type' => 'varchar(20)',
            'null' => 'yes',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'tv_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        )
    );

    // bảng lưu trữ post trước khi xóa
    EBE_create_in_con_voi_table('eb_backup_post_xml', 'bpx_id', $arr_post_xml);

    // bảng post dưới dạng XML (max post -> không xóa)
    EBE_create_in_con_voi_table('eb_post_xml', 'bpx_id', $arr_post_xml);


    // Bảng lưu tất cả các thể loại log
    EBE_create_in_con_voi_table('eb_wgr_log', 'l_id', array(
        'l_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'pri',
            'default' => '',
            'extra' => 'auto_increment',
        ),
        // Nội dung log
        'l_noidung' => array(
            'type' => 'text',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'l_agent' => array(
            'type' => 'varchar(255)',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'l_ip' => array(
            'type' => 'varchar(191)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'l_ngay' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => '',
            'default' => '',
            'extra' => '',
        ),
        'l_type' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'hd_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'post_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        ),
        'tv_id' => array(
            'type' => 'bigint(20)',
            'null' => 'no',
            'key' => 'mul',
            'default' => '',
            'extra' => '',
        )
    ));
}


function EBE_part_page($Page, $TotalPage, $strLinkPager)
{
    $show_page = 8;
    $str_page = '';
    if ($Page <= $show_page) {
        if ($TotalPage <= $show_page) {
            for ($i = 1; $i <= $TotalPage; $i++) {
                if ($i == $Page) {
                    $str_page .= '<strong>' . $i . '</strong>';
                } else {
                    $str_page .= '<a rel="nofollow" href="' . $strLinkPager . $i . '">' . $i . '</a>';
                }
            }
        } else {
            for ($i = 1; $i <= $show_page; $i++) {
                if ($i == $Page) {
                    $str_page .= '<strong>' . $i . '</strong>';
                } else {
                    $str_page .= '<a rel="nofollow" href="' . $strLinkPager . $i . '">' . $i . '</a>';
                }
            }
            $str_page .= ' ... <a rel="nofollow" href="' . $strLinkPager . $i . '">&gt;</a>';
        }
    } else {
        $chiadoi = $show_page / 2;
        $i = $Page - ($chiadoi + 1);
        $str_page = '<a rel="nofollow" href="' . $strLinkPager . $i . '">&lt;&lt;</a> <a rel="nofollow" href="' . $strLinkPager . '1">1</a> ... ';
        $i++;
        for ($i; $i < $Page; $i++) {
            $str_page .= '<a rel="nofollow" href="' . $strLinkPager . $i . '">' . $i . '</a>';
        }
        $str_page .= '<strong>' . $i . '</strong>';
        $i++;
        $_Page = $Page + $chiadoi;
        if ($_Page > $TotalPage) {
            $_Page = $TotalPage;
        }
        for ($i; $i < $_Page; $i++) {
            $str_page .= '<a rel="nofollow" href="' . $strLinkPager . $i . '">' . $i . '</a>';
        }
        $str_page .= ' ... <a rel="nofollow" href="' . $strLinkPager . $TotalPage . '">' . $TotalPage . '</a> <a href="' . $strLinkPager . $i . '" rel="nofollow">&gt;&gt;</a>';
    }

    return $str_page;
}


function EBE_part_page_ajax($Page, $TotalPage, $strLinkPager, $return)
{
    $show_page = 8;
    $str_page = '';
    if ($Page <= $show_page) {
        if ($TotalPage <= $show_page) {
            for ($i = 1; $i <= $TotalPage; $i++) {
                if ($i == $Page) {
                    $str_page .= ' <a title="Trang ' . $i . '" href="javascript:;"><span class="bold">[ ' . $i . ' ]</span></a>';
                } else {
                    $str_page .= ' <a title="Trang ' . $i . '" onclick="ajaxl(\'' . $strLinkPager . $i . '\',\'' . $return . '\',1)" href="javascript:;">' . $i . '</a>';
                }
            }
        } else {
            for ($i = 1; $i <= $show_page; $i++) {
                if ($i == $Page) {
                    $str_page .= ' <a title="Trang ' . $i . '" href="javascript:;"><span class="bold">[ ' . $i . ' ]</span></a>';
                } else {
                    $str_page .= ' <a title="Trang ' . $i . '" onclick="ajaxl(\'' . $strLinkPager . $i . '\',\'' . $return . '\',1)" href="javascript:;">' . $i . '</a>';
                }
            }
            $str_page .= ' ... <a title="Tiếp" onclick="ajaxl(\'' . $strLinkPager . $i . '\',\'' . $return . '\',1)" href="javascript:;">&gt;&gt;</a>';
        }
    } else {
        $chiadoi = $show_page / 2;
        $i = $Page - ($chiadoi + 1);
        $str_page = '<a title="Trước" onclick="ajaxl(\'' . $strLinkPager . $i . '\',\'' . $return . '\',1)" href="javascript:;">&lt;&lt;</a> <a title="Trang 1" onclick="ajaxl(\'' . $strLinkPager . '1\',\'' . $return . '\',1)" href="javascript:;">1</a> ... ';
        $i++;
        for ($i; $i < $Page; $i++) {
            $str_page .= ' <a title="Trang ' . $i . '" onclick="ajaxl(\'' . $strLinkPager . $i . '\',\'' . $return . '\',1)" href="javascript:;">' . $i . '</a>';
        }
        $str_page .= ' <a title="Trang ' . $i . '" href="javascript:;"><span class="bold">[ ' . $i . ' ]</span></a>';
        $i++;
        $_Page = $Page + $chiadoi;
        if ($_Page > $TotalPage) {
            $_Page = $TotalPage;
        }
        for ($i; $i <= $_Page; $i++) {
            $str_page .= ' <a title="Trang ' . $i . '" onclick="ajaxl(\'' . $strLinkPager . $i . '\',\'' . $return . '\',1)" href="javascript:;">' . $i . '</a>';
        }
        $str_page .= ' ... <a title="Tiếp" onclick="ajaxl(\'' . $strLinkPager . $i . '\',\'' . $return . '\',1)" href="javascript:;">&gt;&gt;</a>';
    }
    return '<div class="public-part-page"><span class="bold">Trang: </span> ' . $str_page . '</div>';
}


function EBE_check_list_post_null($str = '')
{
    if ($str == '') {
        global $__cf_row;

        $__cf_row["cf_blog_public"] = 0;

        $str = '<li class="no-set-width-this-li"><div>Chưa có dữ liệu</div></li>';
    }

    return $str;
}


// ebp -> ech bay post
function EBE_print_product_img_css_class($arr, $in = 'Header')
{
    echo '<!-- EchBay Product Image in ' . $in . ' -->
<style type="text/css">' . str_replace('http://' . $_SERVER['HTTP_HOST'] . '/', './', str_replace('https://' . $_SERVER['HTTP_HOST'] . '/', './', implode("\n", $arr))) . '</style>';
}


// https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
function EBE_set_header($status = 200)
{

    // sử dụng hàm set header của wp
    return status_header($status);


    // hoặc tự mình làm
    $pcol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
    //	echo $pcol;

    // Chuyển header sang 200
    if ($status == 200) {
        header($pcol . ' 200 OK');
    } else if ($status == 404) {
        header($pcol . ' 404 Not Found');
    } else if ($status == 401) {
        header($pcol . ' 401 Unauthorized');
    }
}


// chuyển tên file php, html... thành css
function WGR_convert_fiename_to_css($f)
{
    $f = explode('.', $f);
    return $f[0] . '.css';
}

// lấy css theo plugin
function EBE_get_css_for_config_design($f, $type = '.php')
{
    //	return EB_THEME_PLUGIN_INDEX . 'themes/css/' . str_replace( $type, '.css', $f );
    return EB_THEME_PLUGIN_INDEX . 'themes/css/' . WGR_convert_fiename_to_css($f);
}

// lấy css theo theme
function EBE_get_css_for_theme_design($f, $dir = EB_THEME_URL, $type = '.php')
{
    //	return $dir . 'css/' . str_replace( $type, '.css', $f );
    //	return $dir . 'ui/' . str_replace( $type, '.css', $f );
    return $dir . 'ui/' . WGR_convert_fiename_to_css($f);
}

// kiểm tra file template xem nằm ở đâu thì nhúng css tương ứng ở đó
function WGR_check_add_add_css_themes_or_plugin($f)
{
    //	echo EB_CHILD_THEME_URL . 'ui/' . WGR_convert_fiename_to_css( $f ) . '<br>' . "\n";
    //	echo EB_THEME_URL . 'ui/' . WGR_convert_fiename_to_css( $f ) . '<br>' . "\n";

    // ưu tiên hàng của theme trước
    if (using_child_wgr_theme == 1 && is_file(EB_CHILD_THEME_URL . 'ui/' . WGR_convert_fiename_to_css($f))) {
        return EBE_get_css_for_theme_design($f, EB_CHILD_THEME_URL);
    } else if (is_file(EB_THEME_URL . 'ui/' . WGR_convert_fiename_to_css($f))) {
        return EBE_get_css_for_theme_design($f);
    }

    // còn lại sẽ là của plugin
    return EBE_get_css_for_config_design($f);
}


// load các module của web theo phương thức chung
function WGR_load_module_name_css(
    // phương thức lấy
    $module_name,
    // add css vào body hoặc head (0)
    $css_body = 1
) {
    global $__cf_row_default;
    global $__cf_row;
    global $arr_for_add_css;

    $arr = array();

    for ($i = 1; $i < 20; $i++) {
        $j = 'cf_' . $module_name . $i . '_include_file';

        if (!isset($__cf_row_default[$j])) {
            break;
        }
        //		echo $j . ' -> ' . $__cf_row[ $j ] . '<br>' . "\n";

        //
        if ($__cf_row[$j] != '') {
            // nếu là widget -> chỉ nhúng, và nhúng theo kiểu khác
            if ($__cf_row[$j] == $module_name . '_widget.php') {
                $arr[] = EB_THEME_PLUGIN_INDEX . $__cf_row[$j];
            } else {
                // ưu tiên hàng của theme trước
                if (
                    using_child_wgr_theme == 1 &&
                    is_file(EB_CHILD_THEME_URL . 'ui/' . $__cf_row[$j])
                ) {
                    $arr[] = EB_CHILD_THEME_URL . 'ui/' . $__cf_row[$j];

                    $arr_for_add_css[EBE_get_css_for_theme_design($__cf_row[$j], EB_CHILD_THEME_URL)] = $css_body;
                } else if (is_file(EB_THEME_URL . 'ui/' . $__cf_row[$j])) {
                    $arr[] = EB_THEME_URL . 'ui/' . $__cf_row[$j];

                    $arr_for_add_css[EBE_get_css_for_theme_design($__cf_row[$j])] = $css_body;
                }
                // còn lại sẽ là của plugin
                else {
                    $arr[] = EB_THEME_PLUGIN_INDEX . 'themes/' . $module_name . '/' . $__cf_row[$j];

                    $arr_for_add_css[EBE_get_css_for_config_design($__cf_row[$j])] = $css_body;
                }
            }
        }
    }
    //	print_r($arr_for_add_css);
    //	print_r($arr);

    return $arr;
}


// Tạo comment theo chuẩn chung
function EBE_insert_comment($data = array())
{
    global $client_ip;

    // dữ liệu mặc định
    $arr = array(
        // mặc định thì cho vào thành contact
        'comment_post_ID' => eb_contact_id_comments,
        'comment_author' => '',
        'comment_author_email' => mtv_email,
        'comment_author_url' => '',
        'comment_content' => '',
        'comment_type' => '',
        'comment_parent' => 0,
        'user_id' => mtv_id,
        'comment_author_IP' => $client_ip,
        'comment_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        'comment_date' => date('Y-m-d H:i:s', date_time),
        'comment_approved' => 1
    );

    // dữ liệu phủ định
    foreach ($data as $k => $v) {
        if (isset($arr[$k])) {
            $arr[$k] = $v;
        }
    }
    //	print_r($arr);

    wp_insert_comment($arr);
}


function WGR_stripslashes($v)
{
    return stripslashes(stripslashes(stripslashes($v)));
}
