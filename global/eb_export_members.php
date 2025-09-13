<?php


// tạo URL cho phần cache
$rssCacheFilter = 'rss-members';


// test
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2000;
/*
if ( isset($_GET['test']) ) {
	$limit = 20;
}
*/
// cache theo số bản ghi
$rssCacheFilter .= '-limit' . $limit;


// kiểu export
$export_type = isset($_GET['members_export']) ? $_GET['members_export'] : '';
$rssCacheFilter .= '-type' . $export_type;


//
$trang = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
$rssCacheFilter .= '-trang' . $trang;
//echo $rssCacheFilter;


/*
$header_name = 'members';
header( "Content-Type: text/xml" );
header( 'Content-Disposition: inline; filename="' . $header_name . '-page' . $trang . '.xml"' );
*/


// export sản phẩm
function WGR_export_members_to_xml($op = array())
{
    global $wpdb;
    global $table_prefix;

    //
    $export_type = isset($_GET['members_export']) ? $_GET['members_export'] : '';

    // cấu hình mặc định
    if (!isset($op['limit']) || $op['limit'] == '' || $op['limit'] == 0) {
        $op['limit'] = 50;
    }
    if ($op['limit'] > 5000) {
        $op['limit'] = 5000;
    }
    if (!isset($op['trang']) || $op['trang'] == '' || $op['trang'] == 0) {
        $op['trang'] = 1;
    }
    $offset = ($op['trang'] - 1) * $op['limit'];
    //	echo $offset;
    //	print_r( $op ); exit();

    //
    //set_time_limit( 0 );


    // Lấy theo  phone
    if ($export_type == 'phone') {
        $tbl_usermeta = $table_prefix . 'usermeta';

        //
        $sql = "SELECT *
        FROM
            `" . $tbl_usermeta . "`
        WHERE
            `" . $tbl_usermeta . "`.user_id > 0
            AND `" . $tbl_usermeta . "`.meta_key = 'phone'
            AND `" . $tbl_usermeta . "`.meta_value != ''
        ORDER BY
            `" . $tbl_usermeta . "`.user_id DESC
        LIMIT " . $offset . ", " . $op['limit'];
    }
    // Lấy theo  email
    // mặc định thì phải đủ cả 2 yêu cầu
    else {
        $tbl_users = $table_prefix . 'users';

        //
        $key_gmail = '@gmail.com';
        $key_yahoo = '@yahoo.com';
        $key_yahoo_vn = '@yahoo.com.vn';
        $key_hotmail = '@hotmail.com';

        //
        $sql = "SELECT *
        FROM
            `" . $tbl_users . "`
        WHERE
            `" . $tbl_users . "`.ID > 0
            AND ( `" . $tbl_users . "`.user_email LIKE '%{$key_gmail}' OR `" . $tbl_users . "`.user_email LIKE '%{$key_yahoo}' OR `" . $tbl_users . "`.user_email LIKE '%{$key_yahoo_vn}' OR `" . $tbl_users . "`.user_email LIKE '%{$key_hotmail}' )
        ORDER BY
            `" . $tbl_users . "`.ID DESC
        LIMIT " . $offset . ", " . $op['limit'];
        /*
        GROUP BY
        	ID
        ORDER BY
        	ID
        LIMIT " . $offset . ", " . $op['limit'];
        */
    }
    //	echo $sql;

    //
    return _eb_q($sql);
}


//
$arr_for_slect_data = array(
    'trang' => $trang,
    'limit' => $limit
);


$export_tilte = 'Products Export';

include EB_THEME_PLUGIN_INDEX . 'echbay/export_top.php';


//
$before_price = '';
$after_price = '';
if ($__cf_row['cf_current_price_before'] == 1) {
    $before_price = $__cf_row['cf_current_sd_price'] . ' ';
} else {
    $after_price = ' ' . $__cf_row['cf_current_sd_price'];
}


?>
<table id="headerTable" border="0" cellpadding="0" cellspacing="0" width="100%" class="small">
    <tr class="text-center gray2bg">
        <td>ID</td>
        <td>Email</td>
        <td>Phone</td>
        <td>Address</td>
    </tr>
    <?php

    $sql = WGR_export_members_to_xml($arr_for_slect_data);
    //    print_r( $sql );
    if ($export_type == 'phone') {
        foreach ($sql as $v) {
            //        print_r( $v );

            $user_address = get_user_meta($v->user_id, 'address');
            //        print_r( $user_address );
            if (!empty($user_address)) {
                $user_address = $user_address[0];
            } else {
                $user_address = '&nbsp';
            }

            //
            echo '
<tr>
	<td>' . $v->user_id . '</td>
	<td>&nbsp</td>
	<td>' . $v->meta_value . '</td>
	<td>' . $user_address . '</td>
</tr>';
        }
    } else {
        foreach ($sql as $v) {
            //        print_r( $v );

            $user_phone = get_user_meta($v->ID, 'phone');
            //        print_r( $user_phone );
            if (!empty($user_phone)) {
                $user_phone = $user_phone[0];
            } else {
                $user_phone = '&nbsp';
            }

            $user_address = get_user_meta($v->ID, 'address');
            //        print_r( $user_address );
            if (!empty($user_address)) {
                $user_address = $user_address[0];
            } else {
                $user_address = '&nbsp';
            }

            //
            echo '
<tr>
	<td>' . $v->ID . '</td>
	<td>' . $v->user_email . '</td>
	<td>' . $user_phone . '</td>
	<td>' . $user_address . '</td>
</tr>';
        }
    }

    ?>
</table>
<?php


include EB_THEME_PLUGIN_INDEX . 'echbay/export_footer.php';


?>
</body>

</html>
<?php
/*
WGR_order_export_run ( <?php echo $main_content; ?>
,<?php echo json_encode( $arr_hd_trangthai ); ?>);
*/





exit();
