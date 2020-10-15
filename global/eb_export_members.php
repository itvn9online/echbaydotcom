<?php


// tạo URL cho phần cache
$rssCacheFilter = 'rss-members';


// test
$limit = isset( $_GET[ 'limit' ] ) ? ( int )$_GET[ 'limit' ] : 2000;
/*
if ( isset($_GET['test']) ) {
	$limit = 20;
}
*/
// cache theo số bản ghi
$rssCacheFilter .= '-limit' . $limit;


// kiểu export
$export_type = isset( $_GET[ 'members_export' ] ) ? $_GET[ 'members_export' ] : '';
$rssCacheFilter .= '-type' . $export_type;


//
$trang = isset( $_GET[ 'trang' ] ) ? ( int )$_GET[ 'trang' ] : 1;
$rssCacheFilter .= '-trang' . $trang;
//echo $rssCacheFilter;


/*
$header_name = 'members';
header( "Content-Type: text/xml" );
header( 'Content-Disposition: inline; filename="' . $header_name . '-page' . $trang . '.xml"' );
*/


// export sản phẩm
function WGR_export_members_to_xml( $op = array() ) {
    global $wpdb;
    global $table_prefix;

    // cấu hình mặc định
    if ( !isset( $op[ 'limit' ] ) || $op[ 'limit' ] == '' || $op[ 'limit' ] == 0 ) {
        $op[ 'limit' ] = 50;
    }
    if ( !isset( $op[ 'trang' ] ) || $op[ 'trang' ] == '' || $op[ 'trang' ] == 0 ) {
        $op[ 'trang' ] = 1;
    }
    $offset = ( $op[ 'trang' ] - 1 ) * $op[ 'limit' ];
    //	echo $offset;
    //	print_r( $op ); exit();

    //
    $sql = "SELECT *
	FROM
		`" . $table_prefix . "users`
	ORDER BY
		`" . $table_prefix . "users`.ID DESC
	LIMIT " . $offset . ", " . $op[ 'limit' ];
    /*
    GROUP BY
    	ID
    ORDER BY
    	ID
    LIMIT " . $offset . ", " . $op['limit'];
    */
    //	echo $sql;

    //
    return _eb_q( $sql );
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
if ( $__cf_row[ 'cf_current_price_before' ] == 1 ) {
    $before_price = $__cf_row[ 'cf_current_sd_price' ] . ' ';
} else {
    $after_price = ' ' . $__cf_row[ 'cf_current_sd_price' ];
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

    $sql = WGR_export_members_to_xml( $arr_for_slect_data );

    foreach ( $sql as $v ) {
        //        print_r( $v );

        $user_phone = get_user_meta( $v->ID, 'phone' );
//        print_r( $user_phone );
        if ( !empty( $user_phone ) ) {
            $user_phone = $user_phone[ 0 ];
        } else {
            $user_phone = '&nbsp';
        }

        $user_address = get_user_meta( $v->ID, 'address' );
//        print_r( $user_address );
        if ( !empty( $user_address ) ) {
            $user_address = $user_address[ 0 ];
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