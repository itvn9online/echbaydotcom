<?php

/*
[echbay_address title="Liên hệ"]
*/

function action_echbay_address( $ops = [] ) {
    EBE_html_address( $ops );
}

// cách sử dụng -> vào phần nội dung bài viết rồi nhập: [wonder_gotadi]
add_shortcode( 'echbay_address', 'action_echbay_address' );