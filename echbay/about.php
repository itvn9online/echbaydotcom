<?php

global $arr_private_info_setting;


?>
<br>
<div style="padding-right:10%;text-align:justify;">
    <div><a href="javascript:;" class="medium blackcolor bold admin-set-reload-url">Về tác giả</a></div>
    <p>
        <?php
        if ( $arr_private_info_setting[ 'parent_theme_default' ] == 'echbaytwo' ) {
            ?>
        Giao diện được phát triển bởi <a href="http://facebook.com/ech.bay" target="_blank" rel="nofollow">Đào Quốc Đại</a>, trên nền tảng mã nguồn mở <a href="http://wordpress.org/" target="_blank" rel="nofollow">WordPress</a> và sử dụng ngôn ngữ lập trình <a href="http://php.net/" target="_blank" rel="nofollow">PHP</a>, và một số thư viện mã nguồn mở khác như <a href="http://jquery.com/" target="_blank" rel="nofollow">jQuery</a>, <a href="http://fontawesome.io/" target="_blank" rel="nofollow">Font Awesome</a>. Mọi thông tin quý vị có thể xem thêm tại website <a href="<?php echo $arr_private_info_setting['site_url']; ?>" target="_blank" rel="nofollow"><?php echo $arr_private_info_setting['site_upper']; ?></a>.<br>
        <br>
        <?php
        }
        ?>
        Để tối ưu hiệu suất của mã nguồn <a href="http://wordpress.org/" target="_blank" rel="nofollow">WordPress</a> nói chung, và giao diện được viết bởi <a href="<?php echo $arr_private_info_setting['site_url']; ?>" target="_blank" rel="nofollow"><?php echo $arr_private_info_setting['author']; ?></a> nói riêng, chúng tôi khuyến khích bạn sử dụng hosting trên hệ điều hành <a href="http://linux.com/" target="_blank" rel="nofollow"><i class="fab fa-linux"></i> Linux (CentOS)</a>, hệ thống quản trị cơ sở dữ liệu <a href="http://mariadb.com/" target="_blank" rel="nofollow">MariaDB</a> thay vì <a href="http://mysql.com/" target="_blank" rel="nofollow">MySQL</a>, webserver <a href="http://nginx.org/" target="_blank" rel="nofollow">Nginx</a> thay cho <a href="http://apache.org/" target="_blank" rel="nofollow">Apache</a>, ổ cứng chứa dữ liệu dạng SSD để cho tốc độ đọc ghi dữ liệu từ bộ nhớ đệm tốt hơn.<br>
        <br>
        Việc sử dụng code là hoàn toàn miễn phí, bạn chỉ mất phí trong trường hợp cần tác giả hỗ trợ trực tiếp để thay đổi website theo yêu cầu. Khi đó, chúng tôi sẽ tính phí theo số giờ làm việc, mức phí có thể sẽ thay đổi vào từng thời điểm, chúng tôi sẽ báo giá cụ thể trước khi làm.</p>
    <br>
    <?php
    include ECHBAY_PRI_CODE . 'role_user.php';


    if ( current_user_can( 'manage_options' ) ) {
        include ECHBAY_PRI_CODE . 'echbay_update_core.php';
        include ECHBAY_PRI_CODE . 'wordpress_update_core.php';
    }


    ?>
    <br>
    <hr>
    <p align="right">Cảm ơn vì đã chọn chúng tôi. Chúc thành công và trân trọng hợp tác.</p>
</div>
<br>
<br>
<?php


//
WGR_unzip_vendor_code( basename( __FILE__ ) . ':' . __LINE__ );

// tạo thương hiệu riêng cho partner
if ( $arr_private_info_setting[ 'parent_theme_default' ] != 'echbaytwo' ) {
    //echo $arr_private_info_setting[ 'parent_theme_default' ] . '<br>' . "\n";
    //echo EB_THEME_URL . '<br>' . "\n";

    // nếu file style vẫn tồn tại trong partner/hostingviet -> copy nội dung đó ra ngoài để thay thế
    $dir_partner_style = EB_THEME_URL . 'partner/' . $arr_private_info_setting[ 'parent_theme_default' ];
    //echo $dir_partner_style . '<br>' . "\n";

    if ( is_dir( $dir_partner_style ) && file_exists( $dir_partner_style . '/style.css' ) ) {
        foreach ( glob( $dir_partner_style . '/*' ) as $filename ) {
            echo $filename . '<br>' . "\n";

            $file_replace = EB_THEME_URL . basename( $filename );
            echo $file_replace . '<br>' . "\n";

            // thay thế file
            if ( WGR_copy( $filename, $file_replace ) ) {
                _eb_remove_file( $filename );
            } else {
                echo 'Không thay được file cho partner: ' . basename( $filename ) . '<br>' . "\n";
            }
        }
    }
}
