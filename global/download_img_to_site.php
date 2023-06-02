<?php

//print_r( $_GET );
if (!isset($_GET['img'])) {
	die('img not found!');
}

if (!isset($_GET['file_name'])) {
	die('file_name not found!');
}

//
if (!function_exists('wp_handle_upload')) {
	include_once ABSPATH . 'wp-admin/includes/file.php';
}
// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
if (!function_exists('wp_generate_attachment_metadata')) {
	include_once ABSPATH . 'wp-admin/includes/image.php';
}

/*
* chuyển ảnh từ url thường sang chuẩn wordpress -> sau khi sử dụng echbaydotcom leech data từ bản cũ sang
*/
function EBE_sync_product_avatar($f, $parent_post_id = 0)
{
	// tạm thời thì dùng chức năng này khi có parent id
	if ($parent_post_id <= 0) {
		return false;
	}
	//$post_author = 3;

	// Get the path to the upload directory.
	$upload_dir = wp_upload_dir();
	//print_r($upload_dir);

	// $filename should be the path to a file in the upload directory.
	//$filename = str_replace('/ebarchive/', '/wp-content/uploads/', $f);
	$filename = str_replace(ABSPATH . 'ebarchive/', $wp_upload_dir['basedir'] . '/', $f);
	//echo $filename . PHP_EOL;
	if (file_exists($filename)) {
		return false;
	}
	//echo $f . PHP_EOL;
	//echo $filename . PHP_EOL;

	// Check the type of file. We'll use this as the 'post_mime_type'.
	$filetype = wp_check_filetype(basename($filename), null);
	if (empty($filetype)) {
		//print_r($filetype);
		//echo 'EMPTY filetype: ' . __FILE__ . ':' . __LINE__;
		//die(__FILE__ . ':' . __LINE__);
		return false;
	} else if (!isset($filetype['type'])) {
		//print_r($filetype);
		//echo '! isset filetype: ' . __FILE__ . ':' . __LINE__;
		//die(__FILE__ . ':' . __LINE__);
		return false;
	} else if (empty($filetype['type'])) {
		//echo 'EMPTY filetype: ' . __FILE__ . ':' . __LINE__;
		//print_r($filetype);
		//die(__FILE__ . ':' . __LINE__);
		return false;
	} else if (strpos($filetype['type'], 'image/') === false) {
		//echo '! strpos filetype: ' . __FILE__ . ':' . __LINE__;
		//print_r($filetype);
		//die(__FILE__ . ':' . __LINE__);
		return false;
	}
	if (!copy($f, $filename)) {
		//echo 'ERROR copy file: ' . __FILE__ . ':' . __LINE__;
		return false;
	}
	//die(__FILE__ . ':' . __LINE__);

	// tạo user upload ảnh riêng -> có gì sửa xóa nó tiện
	$user_name = 'leechmedia';
	$user_email = $user_name . '@' . $_SERVER['HTTP_HOST'];
	// kiểm tra theo email
	$user_id = email_exists($user_email);
	// nếu chưa có -> kiểm tra theo user
	if ($user_id * 1 < 1) {
		// kiểm tra theo user
		$user_id = username_exists($user_name);

		// chưa có -> tạo mới
		if ($user_id * 1 < 1) {
			$user_id = wp_create_user($user_name, md5(time()), $user_email);
			// đặt là tác giả
			$user_id_role = new WP_User($user_id);
			$user_id_role->set_role('author');
		}
	}

	// Prepare an array of post data for the attachment.
	$attachment = array(
		'guid' => $upload_dir['url'] . '/' . basename($filename),
		'post_mime_type' => $filetype['type'],
		'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
		'post_content' => '',
		//'post_author' => $post_author,
		//'post_author' => get_current_user_id(),
		'post_author' => $user_id,
		'post_status' => 'inherit'
	);
	//print_r($attachment);

	// Insert the attachment.
	$attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);

	// Generate the metadata for the attachment, and update the database record.
	$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
	wp_update_attachment_metadata($attach_id, $attach_data);

	//
	if ($parent_post_id > 0) {
		set_post_thumbnail(
			$parent_post_id,
			$attach_id
		);
	}

	//
	//die(__FILE__ . ':' . __LINE__);

	//
	return true;
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
	$post_ID = isset($_GET['post_ID']) ? (int) $_GET['post_ID'] : 0;

	//
	//	set_time_limit(0);

	if (copy($file_source, $save_to)) {
		chmod($save_to, 0777) or die('ERROR chmod file: ' . $save_to);

		//
		EBE_sync_product_avatar($save_to, $post_ID);
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

				//
				EBE_sync_product_avatar($save_to, $post_ID);
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

						//
						EBE_sync_product_avatar($save_to, $post_ID);
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
