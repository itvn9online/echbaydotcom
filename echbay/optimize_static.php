<?php

/*
 * Chức năng optimize code thủ công -> khi vào đây sẽ optimize liên tục
 */

//
$confirm_file = EB_THEME_PLUGIN_INDEX . 'optimizecode.txt';

// nếu chưa có file confirm thì tạo -> ép buộc optimize
if (!is_file($confirm_file)) {
?>
    <p class="medium18 orgcolor">Tạo file <?php echo basename($confirm_file); ?> để ép buộc tiến trình optimize code...</p>
<?php
    _eb_create_file($confirm_file, date_time);
}

//
WGR_optimize_static_code();

// nếu không còn gì để optimize thì báo thành công
if (!is_file($confirm_file)) {
?>
    <p class="medium18 greencolor">Toàn bộ code đã được optimize!</p>
<?php
}
// còn thì tự động nạp lại trang cho đến khi hoàn tất
else {
?>
    <p class="medium18 redcolor">Vui lòng chờ các file tĩnh đang được dọn dẹp bớt comment...</p>
    <script>
        setTimeout(function() {
            window.location = window.location.href;
        }, 600);
    </script>
<?php
}
