<?php


global $wpdb;

//
$url_for_home_clean_up = admin_link . 'admin.php?page=eb-coder&tab=recycle_bin';


function WGR_recycle_bin_get( $content, $node ) {
    $content = explode( ']]></' . $node . '>', $content );

    //
    //echo count( $content ) . ' aaaaaaaaaaaaaaaaa';
    //print_r( $content );
    //echo 'bbbbbbbbbbbbbbbbbb';

    //
    if ( count( $content ) == 1 ) {
        return '';
    }
    $content = $content[ 0 ];
    $content = explode( '<' . $node . '><![CDATA[', $content );

    //
    return $content[ 1 ];
}

function WGR_recycle_bin_print( $v, $by_bpx_id = 0 ) {
    //print_r( $v );
    //die( 'df dhd hd' );

    //
    $post_type = WGR_recycle_bin_get( $v->bpx_content, 'post_type' );
    // không lấy menu
    if ( $post_type == 'nav_menu_item' ) {
        return false;
    }

    //
    $post_status = WGR_recycle_bin_get( $v->bpx_content, 'post_status' );
    // không lấy bản nháp tự động
    if ( $post_status == 'auto-draft' ) {
        return false;
    }

    //
    $post_id = WGR_recycle_bin_get( $v->bpx_content, 'ID' );
    //echo $v->bpx_content;

    //
    $post_title = WGR_recycle_bin_get( $v->bpx_content, 'post_title' );
    echo '<div>' . $post_type . ' ---> ' . $post_status . ' ---> <a href="' . admin_link . 'admin.php?page=eb-coder&tab=recycle_bin&by_bpx_id=' . $v->bpx_id . '">' . $post_title . '</a> (<em>' . date( 'r', $v->bpx_time ) . '</em>)</div>' . "\n";
    //echo $v->bpx_content;
    //exit();

    //
    if ( $by_bpx_id > 0 ) {
        // chỉ hiển thị phần nội dung
        ?>
<div>
    <div class="redcolor medium18 l19">Nội dung bài viết:</div>
    <div class="orgcolor">* Nội dung bài viết đã được lưu trữ, có thể copy phần này để restore lại bài viết trong trường hợp bị xóa nhầm.</div>
    <textarea onClick="this.select();" readonly style="width: 99%;height: 350px;"><?php echo htmlentities( WGR_recycle_bin_get( $v->bpx_content, 'post_content' ), ENT_QUOTES, 'UTF-8' ); ?></textarea>
</div>
<br>
<?php

// phần nội dung đầy đủ
?>
<div>
    <div class="medium18 l19">Mã XML đầy đủ:</div>
    <div class="redcolor">* Phần mã này là mã XML dành cho kỹ thuật viên đối chiếu dữ liệu, vui lòng không sử dụng trực tiếp!</div>
    <textarea readonly style="width: 99%;height: 250px;"><?php echo htmlentities( $v->bpx_content, ENT_QUOTES, 'UTF-8' ); ?></textarea>
</div>
<br>
<?php
}

//
/*
    $xml = simplexml_load_string( '<?xml version="1.0" encoding="UTF-8"?>
' . $v->bpx_content, "SimpleXMLElement", LIBXML_NOCDATA );
    $json = json_encode( $xml );
    $array = json_decode( $json, TRUE );
    print_r( $array );
    */

//
//exit();
}


//
$by_bpx_id = isset( $_GET[ 'by_bpx_id' ] ) ? $_GET[ 'by_bpx_id' ] : 0;
$strFilter = "";
if ( $by_bpx_id > 0 ) {
    $strFilter = " AND bpx_id = " . $by_bpx_id;
}


//
$sql = _eb_q( "SELECT *
	FROM
		`eb_post_xml`
    WHERE
        1=1 " . $strFilter . "
    GROUP BY
        post_id
    ORDER BY
        bpx_id DESC
    LIMIT 0, 1000" );
//print_r( $sql );
echo '<h3>eb_post_xml (' . count( $sql ) . ')</h3>';
foreach ( $sql as $v ) {
    WGR_recycle_bin_print( $v, $by_bpx_id );
    //break;
}

//
$sql = _eb_q( "SELECT *
	FROM
		`eb_backup_post_xml`
    WHERE
        1=1 " . $strFilter . "
    GROUP BY
        post_id
    ORDER BY
        bpx_id DESC
    LIMIT 0, 1000" );
//print_r( $sql );
echo '<h3>eb_backup_post_xml (' . count( $sql ) . ')</h3>';
foreach ( $sql as $v ) {
    WGR_recycle_bin_print( $v, $by_bpx_id );
    //break;
}

?>
<br>
<div><a href="<?php echo admin_link; ?>admin.php?page=eb-products" target="_blank" class="blue-button">Download list XML backup</a></div>
<p class="redcolor">* Chọn [<strong>XML backup</strong>] để tải về danh sách bài viết dưới dạng XML và sử dụng chức năng <a href="<?php echo admin_link; ?>import.php" target="_blank"><strong>Import Wordpress</strong></a> để nhập liệu lại bài viết</p>
<br>
