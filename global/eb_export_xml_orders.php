<?php

/**
 * lấy danh sách đơn hàng từ bảng `eb_in_con_voi`
 * Yêu cầu:
 * `order_time` từ 0 giờ đến 23h59 phút của ngày này từ năm ngoái đến năm 10 năm trước (định dạng timestamp)
 * `order_customer` có chứa @gmail.com
 */

// Lấy ngày tháng hiện tại
$current_date = date('m-d'); // Ví dụ: 09-08
$nam_nay = date('Y') * 1;
$muoi_nam_truoc = $nam_nay - 10;

// Tạo mảng các timestamp cho ngày này trong các năm 2011-2018
$timestamp_conditions = [];
for ($year = $muoi_nam_truoc; $year < $nam_nay; $year++) {
    $date_string = $year . '-' . $current_date;
    $start_of_day = strtotime($date_string . ' 00:00:00');
    $end_of_day = $start_of_day + 86400; // 86400 giây trong một ngày
    $timestamp_conditions[] = "(`order_time` BETWEEN $start_of_day AND $end_of_day)";
}
// print_r($timestamp_conditions);

// Tạo SQL query với các điều kiện OR cho từng năm
$date_conditions = implode(' OR ', $timestamp_conditions);
$sql = "SELECT order_customer, order_time FROM `eb_in_con_voi` WHERE (order_customer LIKE '%gmail.com%' OR order_customer LIKE '%yahoo.com%'  OR order_customer LIKE '%hotmail.com%') AND ($date_conditions) ORDER BY order_id DESC LIMIT 1000";
// echo $sql . '<br><br>' . PHP_EOL;
$results = $wpdb->get_results($sql, ARRAY_A);
// print_r($results[0]);

/**
 * Function để xử lý tiếng Việt - decode Unicode và URL encoding
 */
function fix_vietnamese_text($str)
{
    if (empty($str)) return $str;

    // Bước 1: Decode Unicode escape sequences (%uXXXX)
    $str = preg_replace_callback('/%u([0-9A-Fa-f]{4})/', function ($matches) {
        return json_decode('"\u' . $matches[1] . '"');
    }, $str);

    // Bước 2: Replace các ký tự UTF-8 encoding phổ biến của tiếng Việt
    $utf8_map = array(
        // Ký tự a
        '%E0' => 'à',
        '%E1' => 'á',
        '%E2' => 'â',
        '%E3' => 'ã',
        '%C3%A0' => 'à',
        '%C3%A1' => 'á',
        '%C3%A2' => 'â',
        '%C3%A3' => 'ã',

        // Ký tự e  
        '%E8' => 'è',
        '%E9' => 'é',
        '%EA' => 'ê',
        '%C3%A8' => 'è',
        '%C3%A9' => 'é',
        '%C3%AA' => 'ê',

        // Ký tự i
        '%EC' => 'ì',
        '%ED' => 'í',
        '%EE' => 'î',
        '%EF' => 'ï',
        '%C3%AC' => 'ì',
        '%C3%AD' => 'í',
        '%C3%AE' => 'î',
        '%C3%AF' => 'ï',

        // Ký tự o
        '%F2' => 'ò',
        '%F3' => 'ó',
        '%F4' => 'ô',
        '%F5' => 'õ',
        '%C3%B2' => 'ò',
        '%C3%B3' => 'ó',
        '%C3%B4' => 'ô',
        '%C3%B5' => 'õ',

        // Ký tự u
        '%F9' => 'ù',
        '%FA' => 'ú',
        '%FB' => 'û',
        '%FC' => 'ü',
        '%C3%B9' => 'ù',
        '%C3%BA' => 'ú',
        '%C3%BB' => 'û',
        '%C3%BC' => 'ü',

        // Ký tự đ
        '%C4%91' => 'đ',
        '%C4%90' => 'Đ',

        // Một số ký tự khác
        '%C6%A1' => 'ơ',
        '%C6%B0' => 'ư',
        '%C4%83' => 'ă',
        '%C3%A6' => 'æ',
    );

    foreach ($utf8_map as $encoded => $decoded) {
        $str = str_replace($encoded, $decoded, $str);
    }

    // Bước 3: Nếu vẫn còn ký tự %XX, thử decode hoặc chuyển về không dấu
    if (preg_match('/%[0-9A-Fa-f]{2}/', $str)) {
        // Thử decode URL encoding thông thường
        $decoded_str = urldecode($str);
        if (mb_check_encoding($decoded_str, 'UTF-8')) {
            $str = $decoded_str;
        } else {
            // Nếu vẫn lỗi, chuyển về không dấu bằng function WordPress
            $str = remove_accents($str);
        }
    }

    return $str;
}

/**
 * Function để giải mã dữ liệu order_customer
 */
function decode_order_customer($encoded_data)
{
    // echo "=== Dữ liệu gốc ===" . PHP_EOL;
    // echo htmlspecialchars($encoded_data) . '<br><br>' . PHP_EOL;

    // 
    $a = $encoded_data;

    // cái urldecode nó lỗi tiếng Việt nên phải xử lý kiểu này
    $arr = array(
        '%28' => '(',
        '%29' => ')',
        '%3C' => '<',
        '%3E' => '>',
        '%20' => ' ',
        '%5B' => '[',
        '%5D' => ']',
        '%7B' => '{',
        '%7D' => '}',
        '%2C' => ',',
        '%22' => '"',
        '%3A' => ':'
    );
    foreach ($arr as $k => $v) {
        $a = str_replace($k, $v, $a);
    }
    return json_decode($a, true);
}

// Chạy vòng lặp để decode dữ liệu khách hàng
$customers = [];
foreach ($results as $k => $v) {
    // Giải mã dữ liệu
    $a = decode_order_customer($v['order_customer']);

    if ($a !== null) {
        $customers[] = [
            'hd_ten' => isset($a['hd_ten']) ? fix_vietnamese_text($a['hd_ten']) : '',
            'hd_dienthoai' => isset($a['hd_dienthoai']) ? $a['hd_dienthoai'] : '',
            'hd_email' => isset($a['hd_email']) ? $a['hd_email'] : '',
            'hd_diachi' => isset($a['hd_diachi']) ? fix_vietnamese_text($a['hd_diachi']) : '',
            'order_time' => date('Y-m-d H:i:s', $v['order_time']),
        ];
    }

    // Tạm thời chỉ chạy 5 vòng để test
    // if ($k >= 4) break;

    // echo PHP_EOL . "=== Kết thúc bản ghi " . ($k + 1) . " ===" . PHP_EOL . PHP_EOL;
}
// print_r($customers);

// Tạo cấu trúc để hiển thị dạng JSON (chỉ khi cần thiết)
// Uncomment dòng dưới nếu muốn xuất ra JSON
header('Content-Type: application/json');
echo json_encode($customers);

// Hiển thị tổng số bản ghi
// echo "<h3>Tổng số đơn hàng tìm thấy: " . count($results) . "</h3>";

exit();
