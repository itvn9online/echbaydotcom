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

function WGR_recycle_bin_print( $v ) {
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
    //echo $v->bpx_content;

    //
    $post_title = WGR_recycle_bin_get( $v->bpx_content, 'post_title' );
    echo $post_type . ' ---> ' . $post_status . ' ---> ' . $post_title . '<br>' . "\n";
    //echo $v->bpx_content;
    //exit();

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
echo '<h3>eb_post_xml</h3>';
$sql = _eb_q( "SELECT *
	FROM
		`eb_post_xml`
    ORDER BY
        bpx_id DESC
    LIMIT 0, 1000" );
//print_r( $sql );
foreach ( $sql as $v ) {
    WGR_recycle_bin_print( $v );
    //break;
}

//
echo '<h3>eb_backup_post_xml</h3>';
$sql = _eb_q( "SELECT *
	FROM
		`eb_backup_post_xml`
    ORDER BY
        bpx_id DESC
    LIMIT 0, 1000" );
//print_r( $sql );
foreach ( $sql as $v ) {
    WGR_recycle_bin_print( $v );
    //break;
}

?>
<br>
<div><a href="<?php echo admin_link; ?>admin.php?page=eb-products" target="_blank" class="blue-button">Download list XML backup</a></div>
<p class="redcolor">* Chọn [<strong>XML backup</strong>] để tải về danh sách bài viết dưới dạng XML và sử dụng chức năng <a href="<?php echo admin_link; ?>import.php" target="_blank"><strong>Import Wordpress</strong></a> để nhập liệu lại bài viết</p>
<br>
