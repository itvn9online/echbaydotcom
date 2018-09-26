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
	
	if ( strstr( $path, '/home/echbay_libary/' ) == false ) {
		echo '<p>' . $path . '</p>';
	}
	
	?>
<div class="medium18">Xin chúc mừng! Bạn đang sử dụng Phiên bản <i class="fa-pro upper small"></i></div>
<div>(<em>lần cập nhật cuối: <?php echo $last_update; ?></em>)</div>
<p>* Mặc định, khi bạn sử dụng Hosting của <a href="https://www.echbay.com/cart" target="_blank" rel="nofollow">EchBay.com</a> bạn sẽ được cung cấp các tính năng nâng cao, hỗ trợ việc quản trị website tốt hơn.</p>
<?php

}




