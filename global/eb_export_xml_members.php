<?php

/**
 * Export members to XML
 * lấy danh sách thành viên từ bảng wp_users
 * Chỉ lấy email có đuôi @gmail.com
 * Có ngày đăng ký là ngày này năm ngoái
 * Lấy 10 thành viên mới đăng ký nhất
 */

// 
// $members = $wpdb->get_results("SELECT * FROM `" . $wpdb->users . "` WHERE `user_status` = 0 AND `user_email` LIKE '%@gmail.com' AND YEAR(`user_registered`) = YEAR(CURDATE()) - 1 ORDER BY `user_registered` DESC LIMIT 0, 10", OBJECT);
// print_r($members);


exit();
