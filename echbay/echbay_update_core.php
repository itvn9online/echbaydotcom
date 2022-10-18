<?php

global $arr_private_info_setting;

?>
<hr>
<p>Mọi hướng dẫn cũng như các bản cập nhật mới nhất sẽ được giới thiệu tại liên kết này: <a
        href="https://github.com/itvn9online/echbaydotcom" target="_blank"
        rel="nofollow">https://github.com/itvn9online/echbaydotcom</a></p>
<p>Lịch sử chi tiết các cập nhật sẽ được liệt kê tại đây: <a
        href="https://github.com/itvn9online/echbaydotcom/commits/master" target="_blank"
        rel="nofollow">https://github.com/itvn9online/echbaydotcom/commits/master</a></p>
<!-- <h1>Cập nhật bộ plugin tổng của <?php echo $arr_private_info_setting['site_upper']; ?> (webgiare.org)</h1> -->
<div id="get_list_wgr_process_update">
    <?php

//
include_once __DIR__ . '/echbay_update_core_functions.php';
include_once __DIR__ . '/echbay_update_core_main.php';

?>
</div>
<div id="show_list_wgr_plugin_update" class="content-process-update-complete"></div>
<p><em>* Xin lưu ý! các tính năng được cập nhật là xây dựng và phát triển cho phiên bản trả phí, nên với phiên bản miễn
        phí, một số tính năng sẽ không tương thích hoặc phải chỉnh lại giao diện sau khi cập nhật. Lần cập nhật trước:
        <strong>
            <?php echo date('r', $last_time_update_eb); ?>
            (
            <?php echo EBE_eb_update_time_to_new_time($last_time_update_eb); ?>)
        </strong></em></p>
<br>
<?php

// hiển thị nút update theme
if ($current_theme_dir_update == $enable_theme_dir_update && $arr_private_info_setting['url_update_parent_theme'] != '') {
?>
<p>Giao diện bạn đang sử dụng là <strong>
        <?php echo $__cf_row['cf_current_theme_using']; ?>
    </strong>, thư mục nền của
    website là <strong>
        <?php echo $current_theme_dir_update . $current_theme_version_update; ?>
    </strong>. Nền này đang
    được hỗ trợ cập nhật miễn phí từ hệ thống, nếu bạn muốn cập nhật hoặc cài đặt lại, vui lòng bấm nút bên dưới để thực
    hiện:</p>
<h2 class="text-center"><a href="#" class="click-connect-to-echbay-update-eb-theme">[ Bấm vào đây để cập nhật lại giao
        diện nền cho website! ]</a></h2>
<p class="text-center"><em>Lần cập nhật trước: <strong>
            <?php echo date('r', $last_time_update_theme_eb); ?>
            (
            <?php echo EBE_eb_update_time_to_new_time($last_time_update_theme_eb); ?>)
        </strong></em></p>
<br>
<?php
} else {
?>
<p>Giao diện bạn đang sử dụng là <strong>
        <?php echo $__cf_row['cf_current_theme_using']; ?>
    </strong>, thư mục nền của
    website là <strong>
        <?php echo $current_theme_dir_update . $current_theme_version_update; ?>
    </strong>. Nền này hiện
    chưa hỗ trợ cập nhật miễn phí từ hệ thống của chúng tôi.</p>
<?php
}
?>
<div id="show_list_wgr_theme_update" class="content-process-update-complete"></div>
<br>
<div class="content-waiting-update-complete">
    <div class="bg-waiting-update-complete whitecolor medium18 l25 bold">Tiến trình cập nhật đang chạy, vui lòng không
        đóng cửa sổ này!</div>
</div>
<script type="text/javascript"
    src="<?php echo EB_URL_OF_PLUGIN . 'echbay/js/echbay_update_core.js?v=' . EBE_admin_get_realtime_for_file(EB_URL_OF_PLUGIN . 'echbay/js/echbay_update_core.js'); ?>">
</script>