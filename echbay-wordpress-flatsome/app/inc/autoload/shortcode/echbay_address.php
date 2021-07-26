<?php

function action_echbay_address( $ops ) {
    echo EBE_get_html_address();
}

// cách sử dụng -> vào phần nội dung bài viết rồi nhập: [wonder_gotadi]
add_shortcode( 'echbay_address', 'action_echbay_address' );