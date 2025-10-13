<?php


// sử dụng file này để load chi tiết đơn hàng -> dùng chung
include ECHBAY_PRI_CODE . 'order_details_load.php';


?>
<p class="orgcolor d-none show-if-order-open-in-popup">* Bấm <strong>ESC</strong> hoặc <span onClick="process_for_press_esc();" class="cur bluecolor">nhấn vào đây</span> để đóng cửa sổ này!</p>
<div class="wrap">
    <h1>Chi tiết đơn hàng</h1>
</div>
<?php

// tự chuyển lại trạng thái là chưa xác nhận nếu bấm vào xem mà không làm gì cả
$auto_reset_status_after_view = 0;


//
$list_log_for_order = _eb_get_log_admin_order($post->order_id);
//	print_r( $list_log_for_order );

// xem có update log xem cho đơn này hay không
$show_dang_xac_nhan = '';
$update_log_view_order = 1;
$str_log_view_order = '';
$i = 0;

// chỉ hiện thị log với tài khoản admin
$show_log_for_supper_admin = 0;
if (current_user_can('manage_options')) {
    $show_log_for_supper_admin = 1;
}

//
/*
if ( empty( $list_log_for_order ) ) {
	$update_log_view_order = 1;
}
else {
	*/
foreach ($list_log_for_order as $v) {
    //
    //		if ( $update_log_view_order == 0 && $v->tv_id == mtv_id ) {
    if ($update_log_view_order == 1 && $v->tv_id == mtv_id) {
        //			echo date_time - $v->l_ngay . '<br>' . "\n";
        //			if ( $i == 0 && date_time - $v->l_ngay > 600 ) {
        //				$update_log_view_order = 1;
        if ($i == 0 && date_time - $v->l_ngay < 600) {
            $update_log_view_order = 0;
        }
        $i++;
    }

    // với đơn hàng mới hoặc đơn hàng đang xác nhận
    if ($post->order_status == 0 || $post->order_status == 3) {
        // kiểm tra thời gian xác nhận lần cuối để thông báo tới người dùng
        if ($show_dang_xac_nhan == '' && date_time - $v->l_ngay < 300) {
            $show_dang_xac_nhan = '<a data-id="' . $v->tv_id . '" href="' . admin_link . 'user-edit.php?user_id=' . $v->tv_id . '" target="_blank">' . WGR_get_user_email($v->tv_id) . '</a>';
        }
    }

    //
    if ($show_log_for_supper_admin == 1) {
        $str_log_view_order .= '
<tr>
<td><a href="' . admin_link . 'user-edit.php?user_id=' . $v->tv_id . '" target="_blank">' . WGR_get_user_email($v->tv_id) . '</a></td>
<td>' . date('d/m/Y (H:i)', $v->l_ngay) . '</td>
<td>' . $v->l_ip . '</td>
<td>' . $v->l_noidung . '</td>
</tr>';
    }
}
//}


// tự động cập nhật trạng thái đơn mới để người sau nắm được
if ($update_log_view_order == 1) {
    // lưu log mỗi khi có người xem đơn hàng
    _eb_log_admin_order('Xem đơn hàng', $post->order_id);
}


//
//if ( $post->order_status == 3 ) {
if ($show_dang_xac_nhan != '') {
    // nếu mới cập nhật -> hiển thị thông báo cho người sau được biết
    //	if ( date_time - $post->l_ngay < 300 ) {
    echo '<div class="dang-xac-nhan">Hóa đơn đang được kiểm duyệt bởi ' . $show_dang_xac_nhan . '</div>';
    /*
    }
    // Nếu không -> reset trạng thái mới
    else {
    }
    */
}


// xem người gửi đơn là admin hay khách
$order_user_can = user_can($post->tv_id, 'delete_posts') ? 1 : 0;


?>
<form name="frm_invoice_details" method="post" action="<?php echo web_link; ?>process?set_module=order_details" target="target_eb_iframe" onSubmit="return ___eb_admin_update_order_details();">
    <div class="d-none">
        <input type="number" name="order_id" value="<?php echo $post->order_id; ?>">
        <input type="number" name="order_old_type" id="order_old_type" value="<?php echo $order_old_type; ?>">
        <textarea name="order_products" id="order_products" style="width:99%;height:110px;"><?php echo $post->order_products; ?></textarea>
        <textarea name="order_customer" id="order_customer" style="width:99%;height:110px;"><?php echo $post->order_customer; ?></textarea>
        <input type="number" name="t_trangthai" value="<?php echo $post->order_status; ?>">
        <input type="text" name="t_total_price" id="hd_total_price" value="<?php echo $post->order_total_price; ?>" />
    </div>
    <div class="medium18 redcolor l30">Thông tin khách hàng</div>
    <table cellpadding="6" cellspacing="0" width="100%" border="0" class="eb-public-table">
        <tr>
            <td class="t">Họ và tên</td>
            <td class="i"><input type="text" name="t_ten" id="oi_hd_ten" value="" class="m" /></td>
        </tr>
        <tr>
            <td class="t">Điện thoại</td>
            <td class="i"><input type="text" name="t_dienthoai" id="oi_hd_dienthoai" value="" class="n" /></td>
        </tr>
        <tr>
            <td class="t">Địa chỉ</td>
            <td class="i"><input type="text" name="t_diachi" id="oi_hd_diachi" value="" class="l" /></td>
        </tr>
        <tr>
            <td class="t">Quận/ Huyện</td>
            <td class="i"><input type="text" name="t_quanhuyen" id="oi_hd_quanhuyen" value="" autocomplete="off" class="m" />
                <input type="hidden" name="t_id_quanhuyen" id="oi_hd_id_quanhuyen" value="" />
            </td>
        </tr>
        <tr>
            <td class="t">Tỉnh/ Thành phố</td>
            <td class="i"><input type="text" name="t_tinhthanh" id="oi_hd_tinhthanh" value="" class="m" />
                <input type="hidden" name="t_id_tinhthanh" id="oi_hd_id_tinhthanh" value="" />
            </td>
        </tr>
        <tr>
            <td class="t">Địa chỉ (đầy đủ)</td>
            <td class="i"><input type="text" id="oi_full_diachi" value="" onClick="this.select();" class="l" /></td>
        </tr>
        <tr>
            <td class="t">Ghi chú của khách hàng</td>
            <td id="oi_ghi_chu_cua_khach" class="i">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2"><textarea id="hd_admin_ghichu" placeholder="Ghi chú của nhân viên Chăm sóc khách hàng. Ví dụ: lý do hủy đơn hàng..."></textarea></td>
        </tr>
    </table>
    <br>
    <div class="medium18 redcolor l30">Thông tin hóa đơn <strong>#<?php echo $post->order_sku; ?></strong></div>
    <table cellpadding="6" cellspacing="0" width="100%" border="0" class="order-danhsach-sanpham">
        <tr class="order-title-sanpham">
            <td width="10%">ID</td>
            <td width="">Sản phẩm</td>
            <td width="15%">Đơn giá</td>
            <td width="10%">Số lượng</td>
            <td width="15%">Cộng</td>
        </tr>
        <tr class="medium">
            <td colspan="2" class="tborder text-right">Tổng</td>
            <td colspan="3" class="tborder show-total-order"><strong class="ebe-currency">&nbsp;</strong></td>
        </tr>
        <tr class="medium">
            <td colspan="2" class="tborder text-right">Mã giảm giá</td>
            <td colspan="3" id="oi_ma_giam_gia" class="tborder"><em>Không áp dụng</em></td>
        </tr>
        <tr class="medium">
            <td colspan="2" class="tborder text-right">Chiết khấu</td>
            <td colspan="3" class="tborder"><input type="text" id="hd_chietkhau" placeholder="VD: 50000 hoặc 20%" maxlength="10" class="n" />
                <span class="ebe-currency"></span> hoặc %
            </td>
        </tr>
        <tr class="medium">
            <td colspan="2" class="tborder text-right">Phí vận chuyển</td>
            <td colspan="3" class="tborder"><input type="text" id="hd_phivanchuyen" placeholder="Ví dụ: 50000" maxlength="10" class="n" />
                <span class="ebe-currency"></span>
            </td>
        </tr>
        <tr class="medium">
            <td colspan="2" class="tborder text-right">Tổng cộng</td>
            <td colspan="3" class="tborder show-totals-order"><strong class="ebe-currency redcolor">&nbsp;</strong></td>
        </tr>
    </table>
    <p class="description">* <strong>Chiết khấu</strong>: Số tiền giảm trực tiếp cho khách hàng hoặc khách được giảm khi nhập mã giảm giá.</p>
    <p class="description">* <strong>Phí vận chuyển</strong>: Cước vận chuyển khách sẽ phải trả thêm khi nhận hàng trong trường hợp có phí vận chuyển.</p>
    <br>
    <table cellpadding="6" cellspacing="0" width="100%" border="0" class="eb-public-table">
        <?php

        $hd_trangthai = $post->order_status;

        //
        if ($hd_trangthai == 0 && isset($post->order_update_time)) {
            $sql = "UPDATE eb_in_con_voi
			SET
				order_update_time = " . date_time . "
			WHERE
				order_id = " . $post->order_id;
            //						echo $sql . "\n";
            _eb_q($sql, 0);

            //
            //						$auto_reset_status_after_view = 0;
        }

        //
        $str_select_trangthai = '';
        $str_button_trangthai = '';
        foreach ($arr_hd_trangthai as $k => $v) {
            if ($k >= 0 && $v != 'none') {
                $sl = '';
                $cl = '';
                if ($k == $hd_trangthai) {
                    $sl = ' selected="selected"';
                    $cl = 'selected';
                }

                //
                $str_select_trangthai .= '<option value="' . $k . '"' . $sl . '>' . $v . '</option>';

                $str_button_trangthai .= '<li data-tab="' . $k . '" title="' . str_replace('"', '&quot;', $v) . '"><button type="button" data-tab="' . $k . '" class="' . $cl . '"><span>' . $v . '</span></button></li>';
            }
        }

        ?>
        <tr>
            <td class="t">Phân loại đơn hàng:</td>
        </tr>
        <tr>
            <td colspan="2">
                <ul class="cf bill-detail-status">
                    <?php echo $str_button_trangthai; ?>
                </ul>
            </td>
        </tr>
        <!--
		<tr>
			<td class="t">Trạng thái</td>
			<td class="i"><select name="t_trangthai">
					<?php echo $str_select_trangthai; ?>
				</select></td>
		</tr>
		-->
        <tr>
            <td valign="top" class="t">Vị trí địa lý</td>
            <td class="i">
                <?php

                //
                /*
            echo EB_THEME_PLUGIN_INDEX . '<br>';
            echo EB_URL_OF_PLUGIN . '<br>';
            echo WP_CONTENT_DIR . '<br>';
            */

                // GeoLite2 -> xác định vị trí người dùng qua IP -> chỉ áp dụng đối với khách hàng
                if ($order_user_can == 1) {
                    echo 'Gửi bởi Biên tập viên';
                } else {
                    /*
                    include_once EB_THEME_PLUGIN_INDEX . 'GeoLite2Helper.php';

                    //
                    if ($cGeoLite2->getPath() != NULL) {
                        //		echo $cGeoLite2->getUserAddressByIp( $post->order_ip );
                        echo $cGeoLite2->getUserOptionByIp($post->order_ip);
                    } else {
                        echo 'Phiên bản <i class="fa-pro upper small"></i>';
                    }
                    */
                ?>
                    <div data-ip="<?php echo $post->order_ip; ?>" class="order_ip-to-location"></div>
                <?php
                }

                ?>
                <div class="redcolor">* Hệ thống sẽ định vị khu vực mà khách hàng này đã truy cập vào website và gửi đơn hàng. Dữ liệu chỉ có độ chính xác tương đối (80-90%), dùng cho mục đích kiểm soát và đối chiếu với địa chỉ người dùng đã nhập trong đơn hàng. Ví dụ người dùng ở HCM mà địa chỉ đơn hàng ở HN thì có thể đây sẽ là đơn hàng ảo.</div>
            </td>
        </tr>
        <tr>
            <td class="t">Email</td>
            <td class="i"><a href="<?php echo admin_link; ?>user-edit.php?user_id=<?php echo $post->tv_id; ?>" target="_blank" id="get-order-email"><?php echo _eb_lay_email_tu_cache($post->tv_id); ?></a></td>
        </tr>
        <tr>
            <td class="t">Ngày gửi</td>
            <td data-time="<?php echo $post->order_time; ?>" class="i order-time-server"><?php echo date('d-m-Y H:i', $post->order_time); ?></td>
        </tr>
        <tr>
            <td class="t">Cập nhật cuối</td>
            <td class="i"><?php echo date('d-m-Y H:i', $post->order_update_time); ?></td>
        </tr>
        <tr>
            <td class="t">Giá trị đơn hàng</td>
            <td class="i"><?php
                            //			if ( isset( $post->order_total_price ) ) {
                            echo $post->order_total_price;
                            /*
			}
			else {
				EBE_tao_bang_hoa_don_cho_echbay_wp();
			}
			*/
                            ?></td>
        </tr>
    </table>
    <br>
    <div class="show-if-js-enable d-none bill-detail-submit">
        <button type="submit" id="eb_cart_submit" class="blue-button cur"><i class="fa fa-save"></i> Lưu thay đổi</button>
        <button type="button" id="eb_cart_print" class="red-button cur"><i class="fa fa-print"></i> In Phiếu thu</button>
        <button type="button" id="eb_vandon_print" class="org-button cur"><i class="fa fa-truck"></i> In Vận đơn</button>
        <button type="button" id="eb_vandon_close" onClick="process_for_press_esc();" class="org-button black-button cur d-none show-if-order-open-in-popup"><i class="fa fa-sign-out"></i> Đóng</button>
    </div>
</form>
<div id="order_show_too_order" class="d-none">
    <div class="medium18 redcolor l30">Đơn hàng cùng người gửi</div>
    <table border="0" cellpadding="6" cellspacing="0" width="100%" class="table-list">
        <tr class="table-list-title">
            <td>ID</td>
            <td>Khách hàng</td>
            <td>Điện thoại</td>
            <td>Địa chỉ</td>
            <td>IP</td>
            <td>Ngày gửi</td>
            <td>Ghi chú đơn hàng</td>
        </tr>
    </table>
    <br>
    <br>
</div>
<div class="medium18 redcolor l30">Lịch sử xem và thay đổi dữ liệu</div>
<table border="0" cellpadding="6" cellspacing="0" width="100%" class="table-list">
    <tr class="table-list-title">
        <td>Thành viên</td>
        <td>Thời gian</td>
        <td>IP</td>
        <td>Nội dung</td>
    </tr>
    <?php echo $str_log_view_order; ?>
</table>
<br />
<div class="medium18 redcolor l30">Dữ liệu tham khảo cho kiểm soát viên</div>
<table cellpadding="6" cellspacing="0" width="100%" border="0" class="eb-public-table dulieu-thamkhao">
    <tr>
        <td class="t">IP</td>
        <td class="i"><a href="admin.php?page=eb-order&by_ip=<?php echo $post->order_ip; ?>" target="_blank"><?php echo $post->order_ip; ?></a></td>
    </tr>
    <tr>
        <td class="t">Vị trí</td>
        <td class="i"><a href="javascript:;" target="_blank" rel="nofollow" class="set-map-url">Xem vị trí trên bản đồ</a></td>
    </tr>
</table>
<br>
<br>
<div id="open_list_bao_xau">Khách hàng này đã bị báo xấu <strong>0</strong> lần</div>
<div id="order_show_bao_xau">
    <div id="close_list_bao_xau"><i class="fa fa-remove"></i></div>
    <ul>
    </ul>
</div>
<script type="text/javascript">
    // v1
    var order_details_arr_cart_product_list_v1 = (function(arr) {
        if (typeof arr == 'undefined') {
            arr = '';
        }
        return arr;
    })(<?php echo $post->post_excerpt; ?>);

    // v2
    var order_details_arr_cart_product_list = "<?php echo $post->order_products; ?>",
        order_details_arr_cart_customer_info = "<?php echo $post->order_customer; ?>",
        order_id = "<?php echo $id; ?>",
        order_user_can = "<?php echo $order_user_can; ?>";
</script>
<script type="text/javascript" src="<?php echo EB_URL_OF_PLUGIN . 'javascript/districts_vn.js?v=' . EBE_admin_get_realtime_for_file(EB_URL_OF_PLUGIN . 'echbay/js/order_details.js'); ?>"></script>
<script type="text/javascript" src="<?php echo EB_URL_OF_PLUGIN . 'echbay/js/order_details.js?v=' . EBE_admin_get_realtime_for_file(EB_URL_OF_PLUGIN . 'echbay/js/order_details.js'); ?>"></script>
<script type="text/javascript" src="<?php echo EB_URL_OF_PLUGIN . 'echbay/js/order_api.js?v=' . EBE_admin_get_realtime_for_file(EB_URL_OF_PLUGIN . 'echbay/js/order_api.js'); ?>"></script>