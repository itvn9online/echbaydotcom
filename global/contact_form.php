<?php


// kiểm tra nếu có file html riêng -> sử dụng html riêng
/*
$check_html_rieng = EB_THEME_HTML . 'contact.html';
$thu_muc_for_html = EB_THEME_HTML;
if ( ! file_exists($check_html_rieng) ) {
	$thu_muc_for_html = EB_THEME_PLUGIN_INDEX . 'html/';
}

//
$main_content = EBE_str_template ( 'contact.html', array (
	'tmp.cf_diachi' => nl2br( $__cf_row['cf_diachi'] ),
	'tmp.cf_email' => $__cf_row['cf_email'],
), $thu_muc_for_html );
*/

//
$custom_lang_html = EBE_get_lang( 'contact_html' );
// mặc định là lấy theo file HTML -> act
if ( trim( $custom_lang_html ) == $act ) {
    $custom_lang_html = EBE_get_page_template( $act );
}

//
if ( !isset( $main_content ) ) {
    $main_content = '';
}


//
function WGR_conatct_required_field( $s ) {
    if ( strpos( $s, '*' ) !== false ) {
        return ' aria-required="true" required';
    }
    return '';
}


//
$lh_hoten = EBE_get_lang( 'lh_hoten' );
$lh_email = EBE_get_lang( 'lh_email' );
$lh_diachi = EBE_get_lang( 'lh_diachi' );
$lh_dienthoai = EBE_get_lang( 'lh_dienthoai' );
$lh_noidung = EBE_get_lang( 'lh_noidung' );


//
//$main_content = EBE_html_template( EBE_get_page_template( $act ), array(
$main_content = EBE_html_template( $custom_lang_html, array(
    'tmp.content' => $main_content,

    // lang
    'tmp.lh_lienhe' => EBE_get_lang( 'lh_lienhe' ),
    'tmp.lh_luuy' => EBE_get_lang( 'lh_luuy' ),

    'tmp.lh_hoten' => $lh_hoten,
    'tmp.lh_required_hoten' => WGR_conatct_required_field( $lh_hoten ),

    'tmp.lh_email' => $lh_email,
    'tmp.lh_required_email' => WGR_conatct_required_field( $lh_email ),

    'tmp.lh_diachi' => $lh_diachi,
    'tmp.lh_required_diachi' => WGR_conatct_required_field( $lh_diachi ),

    'tmp.lh_dienthoai' => $lh_dienthoai,
    'tmp.lh_required_dienthoai' => WGR_conatct_required_field( $lh_dienthoai ),

    'tmp.lh_noidung' => $lh_noidung,
    'tmp.lh_required_noidung' => WGR_conatct_required_field( $lh_noidung ),

    'tmp.lh_submit' => EBE_get_lang( 'lh_submit' ),
    'tmp.lh_note' => EBE_get_lang( 'lh_note' ),
    'tmp.cart_hotline' => EBE_get_lang( 'cart_hotline' ),

    'tmp.cf_diachi' => nl2br( $__cf_row[ 'cf_diachi' ] ),
    'tmp.cf_email' => $__cf_row[ 'cf_email' ],
) );