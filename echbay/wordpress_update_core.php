<?php

global $arr_private_info_setting;

if ( $arr_private_info_setting['parent_theme_default'] == 'echbaytwo' ) {
?>
<hr>
<div class="l19">* Trong trường hợp website của bạn đang sử dụng hosting cung cấp bởi <a href="<?php echo $arr_private_info_setting['site_url']; ?>cart" target="_blank" rel="next"><?php echo $arr_private_info_setting['site_upper']; ?></a>, bạn có thể sử dụng chức năng cập nhật mã nguồn cho WordPress tại đây. Chức năng cập nhật được tối ưu theo hệ thống server nên sẽ ổn định hơn so với cập nhật qua module sẵn có của WordPress. Ngoài ra, bạn sẽ được sử dụng miễn phí phiên bản Elemetor Pro</div>
<br>
<?php
}



//
$url_udpate_via_api = $arr_private_info_setting['site_url'] . 'actions/wordpress_core_update&domain=' . $_SERVER['HTTP_HOST'];


//
//if ( mtv_id == 1 ) {
//if ( current_user_can('manage_options') )  {
	if ( isset( $_GET['confirm_wp_process'] ) ) {
		//
		$url_udpate_via_api .= '&wgr_update_to=' . base64_encode( ABSPATH );
		
		//
		$file_cache_test = EB_THEME_CACHE . 'wp_update_core.txt';
		
		//
		$lats_update_file_test = 0;
		if ( file_exists( $file_cache_test ) ) {
			$lats_update_file_test = file_get_contents( $file_cache_test, 1 );
		}
//		echo $lats_update_file_test . '<br>';
		
		//
		if ( date_time - $lats_update_file_test > 6 * 3600 ) {
			
			// tạo file cache để quá trình này không diễn ra liên tục
//			_eb_create_file( $file_cache_test, $url_udpate_via_api );
//			_eb_create_file( $file_cache_test, date_time );
			echo $url_udpate_via_api . '<br>';
			
			//
			$m_for_request = trim( _eb_postUrlContent( $url_udpate_via_api ) );
			if ( substr( $m_for_request, 0, 6 ) == 'ERROR:' ) {
				echo '<script>a_lert("' . str_replace( '"', '&quot;', $m_for_request ) . '");</script>';
			}
			echo $m_for_request;
			
		}
		else {
			echo '<h3>Giãn cách mỗi lần update core tối thiểu là 6 tiếng</h3>';
		}
	}
	// cập nhật elementor
	else if ( isset( $_GET['confirm_el_process'] ) ) {
		//
		$url_udpate_via_api .= '&wgr_update_to=' . base64_encode( WP_CONTENT_DIR . '/plugins' );
		
		//
		if ( isset( $_GET['confirm_el_process'] ) ) {
//			$url_udpate_via_api .= '&first_active=1';
//			$url_udpate_via_api .= '&first_active=' . urlencode( web_link );
			$url_udpate_via_api .= '&first_active=' . base64_encode( web_link );
		}
		
		//
		$file_cache_test = EB_THEME_CACHE . 'el_update_core.txt';
		
		//
		$lats_update_file_test = 0;
		if ( file_exists( $file_cache_test ) ) {
			$lats_update_file_test = file_get_contents( $file_cache_test, 1 );
		}
		
		//
//		echo $url_udpate_via_api . '<br>';
		
		//
		if ( date_time - $lats_update_file_test > 6 * 3600 ) {
			
			// tạo file cache để quá trình này không diễn ra liên tục
//			_eb_create_file( $file_cache_test, $url_udpate_via_api );
//			_eb_create_file( $file_cache_test, date_time );
			
			//
			$url_udpate_via_api .= '&type_update=elementor-pro';
			echo $url_udpate_via_api . '<br>';
			
			//
			echo _eb_postUrlContent( $url_udpate_via_api );
			
		}
		else {
			echo '<h3>Giãn cách mỗi lần update core tối thiểu là 6 tiếng</h3>';
		}
	}
	// hiển thị link cập nhật
	else if ( $arr_private_info_setting['parent_theme_default'] == 'echbaytwo' ) {
		echo '<h2 class="l20"><center><a href="#" class="click-connect-to-echbay-update-wp-core">[ Bấm vào đây để cập nhật lại core cho WordPress! ]</a></center></h2>';
		echo '<h4 class="l20"><center><a href="#" class="click-connect-to-echbay-update-el-core">[ Bấm vào đây để cập nhật lại core cho Elementor Pro! ]</a></center></h4>';
	}
	
	//
//	echo $url_udpate_via_api . '<br>' . "\n";
	
	/*
}
else {
	echo 'Supper admin only access!';
}
*/



?>
<script type="text/javascript" src="<?php echo EB_URL_OF_PLUGIN . 'echbay/js/wordpress_update_core.js?v=' . EBE_admin_get_realtime_for_file( EB_URL_OF_PLUGIN . 'echbay/js/wordpress_update_core.js' ); ?>"></script>
