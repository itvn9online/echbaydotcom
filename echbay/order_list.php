<?php


// tạo bảng hóa đơn nếu chưa có
/*
$strCacheFilter = 'update_order_table';
$check_Cleanup_cache = _eb_get_static_html ( $strCacheFilter, '', '', 6 * 3600 );
if ($check_Cleanup_cache == false) {
	
	EBE_tao_bang_hoa_don_cho_echbay_wp();
	
	// ép lưu cache
	_eb_get_static_html ( $strCacheFilter, date( 'r', date_time ), '', 6 * 3600 );
	
}
*/
//exit();


//
$threadInPage = 68;
$strFilter = "";
$totalThread = 0;
$totalPage = 0;
$strLinkPager = '';


//
$trang = isset($_GET['trang']) ? (int) $_GET['trang'] : 1;
//echo $trang . '<br>' . "\n";


//
include ECHBAY_PRI_CODE . 'order_filter.php';


// tổng số đơn hàng
$totalThread = _eb_c("SELECT COUNT(order_id) AS c
	FROM
		`eb_in_con_voi`
	WHERE
		tv_id > 0
		" . $strFilter);
//echo $strFilter . '<br>' . "\n";
//echo $totalThread . '<br>' . "\n";


// phân trang bình thường
$totalPage = ceil($totalThread / $threadInPage);
if ($totalPage < 1) {
    $totalPage = 1;
}
//echo $totalPage . '<br>' . "\n";
if ($trang > $totalPage) {
    $trang = $totalPage;
} else if ($trang < 1) {
    $trang = 1;
}
//echo $trang . '<br>' . "\n";
$offset = ($trang - 1) * $threadInPage;
//echo $offset . '<br>' . "\n";

//
$str_hom_nay = date('md', date_time);
$str_7_ngay = date_time - (24 * 3600 * 7);


// lấy 10 IP đặt hàng gần đây nhất và có số lượng đơn đặt từ 2 trở lên
$sql_order_ip = "SELECT *, COUNT(`order_ip`) AS c
    FROM
        `eb_in_con_voi`
    WHERE
        `order_time` > " . $str_7_ngay . "
    GROUP BY
        `order_ip`
    ORDER BY
        `c` DESC,
        `order_ip` DESC
    LIMIT 0, 20";
//print_r( $sql_order_ip );
$sql_order_ip = _eb_q($sql_order_ip);
//print_r( $sql_order_ip );


// lấy các IP trong danh sách đen để cho vào danh sách chặn IP trong 7 ngày
include_once EB_THEME_PLUGIN_INDEX . 'ebcache_global.php';
//print_r( $arr_current_blacklist_ip );

//echo 'OrderStatus BLACK_LIST: ' . OrderStatus::BLACK_LIST . '<br>' . "\n";
$sql_order_blacklist = "SELECT *
    FROM
        `eb_in_con_voi`
    WHERE
        `order_status` = " . OrderStatus::BLACK_LIST . "
        AND `order_time` > " . $str_7_ngay . "
    ORDER BY
        `order_ip` DESC
    LIMIT 0, 50";
$sql_order_blacklist = _eb_q($sql_order_blacklist);
//print_r( $sql_order_blacklist );

// nếu có -> tạo danh sách đen theo IP -> lưu vào file để sau còn block
if (!empty($sql_order_blacklist)) {
    // chạy vòng lặp kiểm tra xem có IP mới không
    $has_new_blacklist_ip = false;
    foreach ($sql_order_blacklist as $v) {
        if (!in_array($v->order_ip, $arr_current_blacklist_ip)) {
            $has_new_blacklist_ip = true;
            break;
        }
    }

    //
    if ($has_new_blacklist_ip === true) {
        $arr_new_blacklist_ip = [];
        foreach ($sql_order_blacklist as $v) {
            $arr_new_blacklist_ip[] = $v->order_ip;
        }
        //print_r( $arr_new_blacklist_ip );
        $str_new_blacklist_ip = '$arr_current_blacklist_ip=json_decode(\'' . json_encode($arr_new_blacklist_ip) . '\');';
        //echo $str_new_blacklist_ip . '<br>' . "\n";

        // lưu vào file để sau sử dụng lại
        _eb_create_file($inc_current_blacklist_ip, '<?php ' . $str_new_blacklist_ip);
    }
}


?>
<div class="cf">
    <?php

    //
    foreach ($sql_order_ip as $v) {
        // các IP có lượt đặt < 2 -> bỏ qua
        if ($v->c < 2) {
            continue;
        }
    ?>
        <div class="lf f20"><a href="admin.php?page=eb-order&by_ip=<?php echo $v->order_ip; ?>" target="_blank"><?php echo $v->order_ip; ?> (<?php echo $v->c; ?>)</a></div>
    <?php
    }

    ?>
</div>
<div class="wrap">
    <div class="cf">
        <div class="lf f60">
            <h1>Danh sách đơn hàng - <span><?php echo number_format($totalThread); ?></span> đơn (Trang
                <?php echo number_format($trang) . '/ ' . number_format($totalPage); ?>)</h1>
        </div>
        <div class="lf f40 cf">
            <div id="oi_quick_connect" class="cf"></div>
        </div>
    </div>
</div>
<div class="cf">
    <div class="lf f20">&nbsp;</div>
    <div class="lf f60 cf">
        <form name="frm_search_invoice" id="frm_search_invoice" method="get" action="<?php echo admin_link; ?>admin.php" onsubmit="return invoice_func_check_search();">
            <input type="hidden" name="page" value="eb-order">
            <!-- <input type="hidden" name="ost" value="search"> -->
            <input type="hidden" name="tab" value="<?php echo $status_by; ?>">
            <input type="hidden" name="type_search" value="<?php echo $type_search; ?>">
            <input type="text" name="invoice_key" id="oi_invoice_key" title="Tìm kiếm" value="<?php echo $invoice_key; ?>" placeholder="Mã đơn hàng, Số điện thoại, Email" maxlength="20" />
            <input type="submit" value="Tìm" class="cur oi_invoice_submit" />
        </form>
        <div class="click-search-by-type"><a data-type="dt" href="javascript:;">Số điện thoại</a> | <a data-type="sp" href="javascript:;">Tên sản phẩm</a> | <a data-type="id" href="javascript:;">Mã hóa đơn</a> <span class="redcolor small">* Lưu ý: từ khóa tìm kiếm có thể sẽ phân biệt chữ HOA, chữ thường, có dấu và
                không dấu.</span></div>
    </div>
</div>
<br>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table-list ip-invoice-alert">
    <tr class="table-list-title">
        <td width="12%">Mã HĐ<span>/ Ngày gửi</span></td>
        <td width="12%">Trạng thái</td>
        <td>S.Phẩm</td>
        <td>Thành viên<span>/ Điện thoại/ Địa chỉ</span></td>
        <td class="show-if-order-fullsize">Điện thoại</td>
        <td class="show-if-order-fullsize">Địa chỉ</td>
        <td class="show-if-order-fullsize">Ngày gửi</td>
    </tr>
    <?php

    //
    //	wp_delete_post( 16018, true );
    //	wp_delete_post( 16017, true );

    //
    $sql = _eb_load_order($threadInPage, array(
        //		'status_by' => $status_by,
        'filter_by' => $strFilter,
        'offset' => $offset
    ));
    //	print_r( $sql ); exit();

    //
    /*
    while ( $sql->have_posts() ) {
    
    $sql->the_post();
    
    $o = $sql->post;
    */
    //		print_r( $o );

    //
    foreach ($sql as $o) {

        //
        $hd_trangthai = $o->order_status;

        //
        /*
        $ngay_gui_don = date_time - $o->order_time;
        if ( $ngay_gui_don < 10 * 60 ) {
        $ngay_gui_don = 'Vài phút trước';
        }
        else if ( $ngay_gui_don < 60 * 60 ) {
        $ngay_gui_don = ceil( $ngay_gui_don/ 60 ) . ' phút trước';
        }
        else if ( $ngay_gui_don < 24 * 3600 ) {
        $ngay_gui_don = ceil( $ngay_gui_don/ 3600 ) . ' giờ trước';
        }
        else if ( $ngay_gui_don < 24 * 3600 * 2 ) {
        $ngay_gui_don = date( 'h A', $o->order_time ) . ' hôm qua';
        }
        else {
        $ngay_gui_don = date( 'd-m-Y H:i', $o->order_time );
        }
        */

        // nếu là ngày hoome nay -> chỉ hiển thị giờ
        $check_hom_nay = date('md', $o->order_time);
        if ($check_hom_nay == $str_hom_nay) {
            $ngay_gui_don = date('H:i', $o->order_time);
        } else {
            $ngay_gui_don = date('d-m-Y H:i', $o->order_time);
        }

        // Với các đơn hàng đang là tự động xác nhận
        // nếu trạng thái này nằm đây lâu quá rồi -> tự ghi nhận là chưa xác nhận
        //		if ( $hd_trangthai == 3 && isset( $o->order_update_time ) && date_time - $o->order_update_time > 600 ) {
        if ($hd_trangthai == 0 && isset($o->order_update_time) && $o->order_update_time > 0 && date_time - $o->order_update_time < 300) {
            $hd_trangthai = 3;
        }

        //
        echo '
		<tr data-id="' . $o->order_id . '" class="eb-set-order-list-info poup-change-status check_hom_nay' . $check_hom_nay . ' hd_status' . $hd_trangthai . '">
			<td class="text-center">
				<div><a href="' . admin_link . 'admin.php?page=eb-order&id=' . $o->order_id . '" data-id="' . $o->order_id . '" class="open-order-in-popup">
					' . $o->order_sku . ' <i class="fa fa-edit bluecolor"></i>
					<span class="time-for-send-bill d-block">(' . $ngay_gui_don . ')</span>
				</a></div>
			</td>
			<td><span class="order-list-icon">' . (isset($arr_hd_trangthai[$hd_trangthai]) ? $arr_hd_trangthai[$hd_trangthai] : '<em>NULL</em>') . '</span></td>
			<td><div class="eb-to-product small"></div></td>
			<td class="order-list-info">
				<div><a href="user-edit.php?user_id=' . $o->tv_id . '" target="_blank"><i class="fas fa-envelope"></i> ' . _eb_lay_email_tu_cache($o->tv_id) . '</a></div>
				<div><i class="fas fa-phone-alt"></i> <span class="eb-to-phone"></span></div>
				<div><i class="fas fa-home"></i> <span class="eb-to-adress small"></span></div>
			</td>
			<td class="eb-to-phone show-if-order-fullsize">.</td>
			<td class="show-if-order-fullsize"><div class="eb-to-adress">.</div></td>
			<td class="show-if-order-fullsize">' . $ngay_gui_don . '</td>
		</tr>
		<script type="text/javascript">post_excerpt_to_prodcut_list("' . $o->order_products . '", "' . $o->order_customer . '");</script>';
    }


    // với các đơn hàng cũ
    $sql = _eb_load_order_v1();
    foreach ($sql as $o) {

        //		print_r( $o );

        //
        //		$hd_trangthai = get_post_meta( $o->ID, '__eb_hd_trangthai', true );
        $hd_trangthai = _eb_get_post_object($o->ID, '__eb_hd_trangthai');

        //
        echo '
		<tr class="hd_status' . $hd_trangthai . '">
			<td>
				<div><a href="' . admin_link . 'admin.php?page=eb-order&id=' . $o->ID . '&order_old_type=1" class="order-a-of-v1">' . $o->post_title . '</a></div>
				<div class="small">' . $o->post_date . '</div>
			</td>
			<td>' . (isset($arr_hd_trangthai[$hd_trangthai]) ? $arr_hd_trangthai[$hd_trangthai] : '<em>NULL</em>') . '</td>
			<td><em>Chưa đồng bộ</em></td>
			<td><a href="user-edit.php?user_id=' . $o->post_author . '" target="_blank">' . _eb_lay_email_tu_cache($o->post_author) . '</a></td>
		</tr>';
    }


    ?>
</table>
<br>
<div class="admin-part-page">
    <?php
    if ($totalPage > 1) {
        echo EBE_part_page($trang, $totalPage, admin_link . 'admin.php?page=eb-order' . $strLinkPager . '&trang=');
    }


    ?>
</div>
<br>
<div class="text-center cf div-inline-block">
    <div><a href="<?php echo web_link; ?>order_export?token=<?php echo _eb_mdnam($_SERVER['HTTP_HOST']) . '&d=' . $filterDay . '&tab=' . $status_by; ?>&type_search=<?php echo $type_search; ?>&invoice_key=<?php echo $invoice_key; ?>" target="_blank" class="rf d-block blue-button whitecolor">Export to HTML</a></div>
</div>
<br>

<!-- mở cửa sổ chỉnh đơn hàng trong popup -->
<div id="oi_order_iframe" class="hide-if-press-esc2">
    <iframe id="target_order_iframe" name="target_order_iframe" src="about:blank" width="100%" height="600">AJAX
        form</iframe>
</div>
<script type="text/javascript">
    var str_hom_nay = "<?php echo $str_hom_nay; ?>";
    $('.check_hom_nay' + str_hom_nay + ':last').after('<tr><td colspan="' + $('.check_hom_nay' + str_hom_nay + ' td')
        .length + '">&nbsp;</td></tr>');

    // ẩn bớt menu khi người dùng xem danh sách đơn
    $('body').addClass('folded');


    WGR_view_by_time_line('<?php echo admin_link . 'admin.php?page=eb-order' . $jsLinkPager; ?>',
        '<?php echo $filterDay; ?>', '<?php echo $str_for_order_cookie_name; ?>');

    click_set_search_order_by_type();
</script>
<script type="text/javascript" src="<?php echo EB_URL_OF_PLUGIN . 'echbay/js/order_list.js?v=' . EBE_admin_get_realtime_for_file(EB_URL_OF_PLUGIN . 'echbay/js/order_list.js'); ?>">
</script>