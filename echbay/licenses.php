<br>
<div><a href="javascript:;" class="medium blackcolor bold admin-set-reload-url">Bản quyền phần mềm</a></div>
<br>
<?php

global $arr_private_info_setting;


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
        if ( !is_dir( WP_CONTENT_DIR . '/plugins/elementor-pro' ) ) {
            $elementor_pro = '<p class="redcolor medium"><a href="' . admin_link . 'admin.php?page=eb-about&confirm_el_process=1&first_active=1" class="bluecolor">--= Bạn được sử dụng kho 150 Landing page miễn phí của chúng tôi, hãy <strong>bấm vào đây</strong> để gửi yêu cầu kích hoạt chức năng này =--</a></p>';
        }

    }

    ?>
<div class="medium18">Xin chúc mừng! Bạn đang sử dụng Phiên bản <i class="fa-pro upper small"></i></div>
<div>(<em>lần cập nhật cuối: <?php echo $last_update; ?></em>)</div>
<p>* Mặc định, khi bạn sử dụng Hosting của <a href="<?php echo $arr_private_info_setting['site_url']; ?>cart" target="_blank" rel="nofollow"><?php echo $arr_private_info_setting['site_upper']; ?></a> bạn sẽ được cung cấp các tính năng nâng cao, hỗ trợ việc quản trị và bảo mật website tốt hơn. Một số tính năng trong phiên bản <strong>PRO</strong>:</p>
<ol>
    <li>Tự động sao lưu dữ liệu đơn hàng lên <a href="<?php echo admin_link; ?>admin.php?page=eb-config&tab=cache&support_tab=cf_google_sheet_backup" target="_blank">Google Excel</a></li>
    <li>Tự động sao lưu toàn bộ dữ liệu sang server khác vào lúc 4 giờ sáng hàng ngày</li>
    <li>Tự động xác định vị trí tương đối (cấp độ quận/ huyện) của khách hàng bằng IP, sử dụng khi khách hàng nhập liệu để đặt hàng.</li>
    <li>Tự động cập nhật toàn bộ plugin, theme và các phần mềm liên quan lên phiên bản mới nhất theo định kỳ hàng tuần.</li>
    <li>Hỗ trợ cài đặt mặc định các tiêu chuẩn web mới nhất, hoặc thay đổi hàng loạt các tiêu chí nếu nó cần phải thay đổi, giúp website luôn luôn được sử dụng các kỹ thuật mới.</li>
</ol>
<?php

//
echo $elementor_pro;

}
else {
    ?>
<div class="medium18">Phiên bản <i class="fa-pro upper small"></i> là phiên bản cấp phát riêng cho khách hàng sử dụng dịch vụ Hosting cung cấp bởi <a href="<?php echo $arr_private_info_setting['site_url']; ?>" target="_blank" rel="nofollow"><?php echo $arr_private_info_setting['site_upper']; ?></a></div>
<?php
}
