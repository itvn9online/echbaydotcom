<?php



//print_r( $_GET );
if (!isset($_GET['img'])) {
	die('img not found!');
}

if (!isset($_GET['file_name'])) {
	die('file_name not found!');
}

//
$file_source = urldecode($_GET['img']);
$file_source = str_replace(' ', '%20', $file_source);


// thư mục download
$save_to = ABSPATH . 'ebarchive/';
//echo $save_to . '<br>' . "\n";

// tạo thư mục để download ảnh về
EBE_create_dir($save_to, 1, 0777);


// thêm năm vào khu vực lưu trữ
$set_year = $year_curent;
if (isset($_GET['set_year'])) {
	$set_year = (int) $_GET['set_year'];
	if ($set_year < 1970) {
		$set_year = $year_curent;
	}
}
$save_to .= $set_year . '/';
EBE_create_dir($save_to, 1, 0777);


// thêm tháng luôn
$save_to .= $month_curent . '/';
EBE_create_dir($save_to, 1, 0777);


// file download
$file_name = $_GET['file_name'];
$file_name = explode('?', $file_name);
$file_name = ltrim($file_name[0], '-');
$save_to .= $file_name;
//echo $save_to . '<br>' . "\n";
if (!file_exists($save_to)) {

	$post_ID = 0;
	if (isset($_GET['post_ID'])) {
		$post_ID = (int) $_GET['post_ID'];
	}

	$file_basename = basename($save_to);
	$file_type = wp_check_filetype($file_basename, null);
	$attachment_title = sanitize_file_name(pathinfo($file_basename, PATHINFO_FILENAME));
	$post_date_gmt = date('Y-m-d H:i:s', date_time);

	$post_excerpt = '';
	if (isset($_GET['img_title'])) {
		$post_excerpt = urldecode(trim($_GET['img_title']));
	}

	$arr_set_attachment = array(
		//		'ID' => 994351
		//		'post_author' => 245
		'post_date' => $post_date_gmt,
		'post_date_gmt' => $post_date_gmt,
		'post_content' => '',
		'post_title' => $attachment_title,
		'post_excerpt' => $post_excerpt,
		'post_status' => 'inherit',
		'comment_status' => 'closed',
		'ping_status' => 'closed',
		//		'post_password' => '',
		//		'post_name' => 1n9a3734-2
		//		'to_ping' => 
		//		'pinged' => 
		'post_modified' => $post_date_gmt,
		'post_modified_gmt' => $post_date_gmt,
		//		'post_content_filtered' => 
		'post_parent' => $post_ID,
		'guid' => str_replace(ABSPATH, web_link, $save_to),
		'menu_order' => 0,
		//		'post_type' => 'attachment',
		//		'post_type' => 'ebattachment',
		'post_type' => 'ebarchive',
		'post_mime_type' => $file_type['type'],
		'comment_count' => 0
	);

	//
	//	set_time_limit(0);

	if (copy($file_source, $save_to)) {
		chmod($save_to, 0777) or die('ERROR chmod file: ' . $save_to);

		if ($post_ID > 0) {
			//			$arr_set_attachment['post_mime_type'] = wp_check_filetype( $save_to, null );
			_eb_sd($arr_set_attachment, wp_posts);
		}
	} else {
		// xử lý lỗi copy ảnh qua SSL
		// https://www.youtube.com/watch?v=5vjbOqdxtEM
		$re_copy_file = 0;
		if (function_exists('stream_context_set_default')) {
			stream_context_set_default([
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false
				)
			]);

			$re_copy_file = 1;
		}

		if ($re_copy_file === 1) {
			if (copy($file_source, $save_to)) {
				chmod($save_to, 0777) or die('ERROR chmod file: ' . $save_to);

				if ($post_ID > 0) {
					//					$arr_set_attachment['post_mime_type'] = wp_check_filetype( $save_to, null );
					_eb_sd($arr_set_attachment, wp_posts);
				}
			} else {
				// https://stackoverflow.com/questions/4545790/file-get-contents-returns-403-forbidden
				// https://www.php.net/manual/en/function.copy.php
				if (function_exists('stream_context_create')) {
					$re_copy_file = stream_context_create([
						'http' => array(
							'method' => 'GET',
							'protocol_version' => 1.1,
							'follow_location' => 1,
							'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'
						)
					]);

					if (copy($file_source, $save_to, $re_copy_file)) {
						chmod($save_to, 0777) or die('ERROR chmod file: ' . $save_to);

						if ($post_ID > 0) {
							//							$arr_set_attachment['post_mime_type'] = wp_check_filetype( $save_to, null );
							_eb_sd($arr_set_attachment, wp_posts);
						}
					} else {
						die('ERROR copy file');
					}
				} else {
					die('stream_context_create not found!');
				}
			}
		} else {
			die('stream_context_set_default not found!');
		}
	}
} else if (!isset($_GET['show_url_img'])) {
	echo 'file exist!';
}

//
$save_to = str_replace(ABSPATH, web_link, $save_to);
//echo $save_to . '<br>' . "\n";


//
if (isset($_GET['show_url_img'])) {
	die($save_to);
}


//
echo '<script>
if ( top != self ) {
	parent.finish_download_img_other_domain_of_content("' . $save_to . '");
}
</script>';




exit();
