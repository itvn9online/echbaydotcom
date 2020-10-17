<?php


global $table_prefix;
global $wpdb;


$threadInPage = _eb_getCucki( 'quick_edit_per_page' );
if ( $threadInPage == '' ) {
    $threadInPage = 68;
}
$totalThread = 0;
$totalPage = 0;
$strLinkPager = admin_link . 'admin.php?page=eb-members';


//
$trang = isset( $_GET[ 'trang' ] ) ? ( int )$_GET[ 'trang' ] : 1;
//echo $trang . '<br>' . "\n";

$strLinkAjaxl = '';
$strAjaxLink = '';

//
$key_gmail = '@gmail.com';
$key_yahoo = '@yahoo.com';
$key_yahoo_vn = '@yahoo.com.vn';
$key_hotmail = '@hotmail.com';
$tbl_users = $table_prefix . 'users';


// tổng số đơn hàng
$totalThread = _eb_c( "SELECT COUNT(ID) AS c
	FROM
		`" . $tbl_users . "`
    WHERE
        `" . $tbl_users . "`.ID > 0
        AND ( `" . $tbl_users . "`.user_email LIKE '%{$key_gmail}' OR `" . $tbl_users . "`.user_email LIKE '%{$key_yahoo}' OR `" . $tbl_users . "`.user_email LIKE '%{$key_yahoo_vn}' OR `" . $tbl_users . "`.user_email LIKE '%{$key_hotmail}' )" );
//echo $strFilter . '<br>' . "\n";
//echo $totalThread . '<br>' . "\n";


// phân trang bình thường
$totalPage = ceil( $totalThread / $threadInPage );
if ( $totalPage < 1 ) {
    $totalPage = 1;
}
//echo $totalPage . '<br>' . "\n";
if ( $trang > $totalPage ) {
    $trang = $totalPage;
} else if ( $trang < 1 ) {
    $trang = 1;
}
//echo $trang . '<br>' . "\n";
$offset = ( $trang - 1 ) * $threadInPage;
//echo $offset . '<br>' . "\n";

//
$strAjaxLink .= '&trang=' . $trang;

?>
<br>
<div><a href="javascript:;" class="medium blackcolor bold admin-set-reload-url"><i class="fa fa-users"></i> Danh sách thành viên</a></div>
<p>* Ngoài dữ liệu thành viên mặc định của wordpress, chúng tôi bổ sung thêm một số chức năng liên quan tới thành viên để tiện bóc tách dữ liệu phục vụ cho marketing.</p>
<div class="text-right cf div-inline-block">
    <?php

    $arr_button_export = array(
        'all' => '<i class="fa fa-download"></i> Download Email and Phone',
        'email' => '<i class="fa fa-envelope-o"></i> Download Email',
        'phone' => '<i class="fa fa-phone"></i> Download Phone'
    );

    $export_token = _eb_mdnam( $_SERVER[ 'HTTP_HOST' ] );

    foreach ( $arr_button_export as $k => $v ) {
        echo '<div><a href="' . web_link . 'eb_export_products?members_export=' . $k . '&token=' . $export_token . '&trang=' . $trang . '&limit=' . $threadInPage . '" target="_blank" class="rf d-block blue-button whitecolor">' . $v . '</a></div> ';
    }

    ?>
</div>
<br>
<div align="right" > Số bản ghi trên mỗi trang
    <select id="change_set_thread_show_in_page" style="padding:3px;">
    </select>
</div>
<br>
<?php

//


?>
<br>
<div class="admin-part-page">
    <?php
    if ( $totalPage > 1 ) {
        echo EBE_part_page( $trang, $totalPage, $strLinkPager . '&trang=' );
    }
    ?>
</div>
<br>
<script type="text/jscript">
var threadInPage = '<?php echo $threadInPage; ?>',
	strLinkPager = '<?php echo $strLinkPager; ?>';

WGR_change_set_thread_show_in_page();
</script>