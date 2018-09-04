<br>
<div><a href="javascript:;" class="medium blackcolor bold admin-set-reload-url">Bản quyền phần mềm</a></div>
<br>
<?php


//
include_once EB_THEME_PLUGIN_INDEX . 'GeoLite2Helper.php';

$path = $cGeoLite2->getPath();
if ( $path != NULL ) {
	
	if ( strstr( $path, '/home/echbay_libary/' ) == false ) {
		echo '<p>' . $path . '</p>';
	}
	
	?>
<div class="medium18">Xin chúc mừng! Bạn đang sử dụng Phiên bản <i class="fa-pro upper small"></i></div>
<p>* Mặc định, khi bạn sử dụng Hosting của <a href="https://www.echbay.com/cart" target="_blank" rel="nofollow">EchBay.com</a> bạn sẽ được cung cấp các tính năng nâng cao, hỗ trợ việc quản trị website tốt hơn.</p>
<?php

}




