<?php



/*
* Tạo các bảng riêng cho plugin của echbay
*/

$strCacheFilter = 'admin-create-echbay-table-v2';
$check_Cleanup_cache = _eb_get_static_html ( $strCacheFilter, '', '', 6 * 3600 );
if ($check_Cleanup_cache == false) {
	
	// cập nhật lại dữ liệu bảng
	EBE_tao_bang_hoa_don_cho_echbay_wp();
	
	// ép lưu cache
	_eb_get_static_html ( $strCacheFilter, date( 'r', date_time ), '', 60 );
	
}




