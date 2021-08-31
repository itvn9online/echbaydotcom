<p class="redcolor medium">* Chức năng này sẽ tiến hành backup các bài viết dạng page, blocks để đề phòng trường hợp bị xóa dữ liệu thì vẫn còn bản backup mà restore.</p>
<?php

//
if ( defined( 'EB_CHILD_THEME_URL' ) ) {
    //echo EB_CHILD_THEME_URL . '<br>' . "\n";
    $ux_builder_dir = EB_CHILD_THEME_URL . 'ux-builder';
    if ( !is_dir( $ux_builder_dir ) ) {
        EBE_create_dir( $ux_builder_dir );
    }

    //
    if ( is_dir( $ux_builder_dir ) ) {
        //$day_backup = date( 'Ymd-H' );

        //
        $sql = _eb_q( "SELECT *
        FROM
            `" . wp_posts . "`
        WHERE
            post_status = 'publish'
            AND ( post_type = 'page' OR post_type = 'blocks' )" );
        //print_r( $sql );

        foreach ( $sql as $v ) {
            //echo $v->post_date . '<br>' . "\n";
            //echo $v->post_date_gmt . '<br>' . "\n";
            //echo $v->post_modified . '<br>' . "\n";
            //echo $v->post_modified_gmt . '<br>' . "\n";

            // lấy ngày thay đổi cuối để tạo backup -> nếu không có thay đổi thì không tạo backup
            if ( !isset( $v->post_modified_gmt ) || empty( $v->post_modified_gmt ) ) {
                echo '$post_modified_gmt not found!: ' . $v->post_type . '#' . $v->ID . '<br>' . "\n";
                continue;
            }
            $post_modified_gmt = date( 'Ymd-H', strtotime( $v->post_modified_gmt ) );
            //echo 'post_modified_gmt: ' . $post_modified_gmt . '<br>' . "\n";

            //
            if ( trim( $v->post_content ) == '' ) {
                continue;
            }

            // file tồn tại rồi thì thôi, backup tầm 1 tiếng 1 lần
            $file_backup = $ux_builder_dir . '/' . $v->post_name . '-' . $post_modified_gmt . '-' . $v->post_type . '-' . $v->ID . '.txt';
            if ( file_exists( $file_backup ) ) {
                echo 'Backup exist: <em>' . basename( $file_backup ) . '</em><br>' . "\n";
                continue;
            }

            //
            _eb_create_file( $file_backup, $v->post_content );
            echo 'Create new: <strong>' . basename( $file_backup ) . '</strong><br>' . "\n";
        }
    } else {
        echo 'dir backup ux-builder not found! <br>' . "\n";
    }

}
