<?php


/*
 * Cập nhật toàn bộ key cho các sản phẩm chưa có dữ liệu -> khác biệt phiên bản
 *
 * Trường hợp chức năng mới kích hoạt, nhưng dữ liệu tìm kiếm không có sẵn -> thêm tham số update_key_version để cập nhật lại toàn bộ key
 */
if ( isset( $_GET[ 'update_key_version' ] ) && mtv_id > 0 ) {
	$sql = _eb_q( "SELECT ID, post_title
	FROM
		`" . wp_posts . "`
	WHERE
		post_title != ''
		AND post_type = 'post'
		AND post_status = 'publish'
	ORDER BY
		ID DESC
	LIMIT 0, 5000" );
	//	print_r( $sql );

	// nếu có -> tạo nội dung mới để cho vào post meta
	if ( !empty( $sql ) ) {
		foreach ( $sql as $v ) {
			$v->post_title = _eb_non_mark_seo( $v->post_title );
			$v->post_title = str_replace( '-', '', $v->post_title );

			//
			WGR_update_meta_post( $v->ID, '_eb_product_searchkey', $v->post_title );
		}
		//		print_r( $sql );
	}
}


//
$arrFilter = array();
include_once __DIR__ . '/ebsearch_get_id.php';

//
if ( !empty( $arrFilter ) ) {
	$arrFilter = array_unique( $arrFilter );
	$show_html_template = 'search';

	include EB_THEME_PLUGIN_INDEX . 'global/search_show.php';
} else {
	// xem có dùng mã tìm kiếm khác không
	$search_not_found = EBE_get_lang( 'search_addon' );
	if ( $search_not_found == '' ) {
		$search_not_found = '<h4 class="text-center" style="padding:90px 0;">' . EBE_get_lang( 'search_not_found' ) . '</h4>';
	}
}