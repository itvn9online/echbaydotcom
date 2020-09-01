<br>
<div><a href="javascript:;" class="medium blackcolor bold admin-set-reload-url">Bản quyền phần mềm</a></div>
<br>
<?php


//
include_once EB_THEME_PLUGIN_INDEX . 'GeoLite2Helper.php';

$path = $cGeoLite2->getPath();
if ( $path != NULL ) {
	
	//
	$last_update = date( 'r', filemtime( $path ) );
	$elementor_pro = '';
	
	// nếu không phải là sử dụng host của EB thì in thêm thông tin ra
	if ( strstr( $path, '/home/echbay_libary/' ) == false ) {
		echo '<p>' . $path . '</p>';
	}
	// khuyến mại thêm elementor pro
	else {
//		echo WP_CONTENT_DIR;
		
		//
		if ( ! is_dir( WP_CONTENT_DIR . '/plugins/elementor-pro' ) ) {
			$elementor_pro = '<p class="redcolor medium"><a href="' . admin_link . 'admin.php?page=eb-about&confirm_el_process=1&first_active=1" class="bluecolor">--= Bạn được sử dụng kho 150 Landing page miễn phí của chúng tôi, hãy <strong>bấm vào đây</strong> để gửi yêu cầu kích hoạt chức năng này =--</a></p>';
		}
		
	}
	
	?>
<div class="medium18">Xin chúc mừng! Bạn đang sử dụng Phiên bản <i class="fa-pro upper small"></i></div>
<div>(<em>lần cập nhật cuối: <?php echo $last_update; ?></em>)</div>
<p>* Mặc định, khi bạn sử dụng Hosting của <a href="https://echbay.com/cart" target="_blank" rel="nofollow">EchBay.com</a> bạn sẽ được cung cấp các tính năng nâng cao, hỗ trợ việc quản trị website tốt hơn.</p>
<?php
	
	//
	echo $elementor_pro;
	
}
else {
	?>
<div class="medium18">Phiên bản <i class="fa-pro upper small"></i> là phiên bản cấp phát riêng cho khách hàng sử dụng dịch vụ Hosting cung cấp bởi <a href="https://echbay.com/" target="_blank" rel="nofollow">EchBay.com</a></div>
<?php
}





