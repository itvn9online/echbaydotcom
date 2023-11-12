<?php


/*
 * function này dùng để nạp nội dung từ file html template, nếu có thay đổi thì cập nhật nội dung cho file này luôn
 * ---> đỡ phải cập nhật file liên tục mỗi lần
 * ---> Cơ bản thì có cách dùng shortcode hoặc cách tạo page template, ưng cách nào thì dùng cách đó (shortcode dùng gọn hơn)
 */
/*
add_shortcode( 'get_child_html_template',
    function ( $args, $content ) {
        print_r( $args );
        //print_r( $content );

        //
        global $post;
        print_r( $post );
    } );
    */
add_shortcode(
    'get_child_php_template',
    function ($args) {
        /*
         * Truyền tham số path vào mảng $args (theo cấu trúc của wordpress) rồi gọi trong page, post...
         * Ví dụ: [get_child_php_template path="home.php"]
         */
        //print_r( $args );

        //
        if (!isset($args['path'])) {
            echo 'No parameter: path ---> [get_child_php_template path="home.php"]' . '<br>' . "\n";
            return false;
        }

        // không có thì trả về lỗi
        $f = EB_CHILD_THEME_URL . 'shortcode/' . $args['path'];
        if (!is_file($f)) {
            echo 'File not exist: ' . $f . '<br>' . "\n";
            return false;
        }

        // có thì include vào thôi
        include_once $f;
    }
);
