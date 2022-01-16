<?php

/*
 * Chức năng optimize code thủ công -> khi vào đây sẽ optimize liên tục
 */

//
$confirm_file = EB_THEME_PLUGIN_INDEX . 'optimizecode.txt';

// nếu chưa có file confirm thì tạo -> ép buộc optimize
if ( !file_exists( $confirm_file ) ) {
    _eb_create_file( $confirm_file, time() );
}

//
WGR_optimize_static_code();

// nếu không còn gì để optimize thì báo thành công
if ( !file_exists( $confirm_file ) ) {
    ?>
<p class="medium18 greencolor">Toàn bộ code đã được optimize!</p>
<?php
}
// còn thì tự động nạp lại trang cho đến khi hoàn tất
else {
    ?>
<p class="medium18 redcolor">Vui lòng chờ các file tĩnh đang được dọn dẹp bớt comment...</p>
<script>
setTimeout(function () {
    window.location=window.location.href;
}, 600);
</script>
<?php
}
