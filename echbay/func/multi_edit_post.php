<?php



//print_r($_POST);


$list_id = trim($_POST['t_list_id']);
$list_id = substr($list_id, 1);

if ($list_id == '') {
	_eb_alert('Không xác định được ID cần xử lý');
}
$list_id = explode(",", $list_id);



// Múc đích của việc cập nhật
$actions_for = trim($_POST['actions_for']);

// Đối với 1 số chức năng (Thông số khác) -> cần xác định người dùng đang muốn cập nhật cho thông số nào
$actions_id_for = (int) $_POST['actions_id_for'];
//$func->alert($actions_id_for);


//
$strFilter = "";



//
echo '<script>
function WHR_show_console_in_multi ( m ) {
	console.log(m);
}
</script>';



//
function WGR_multi_edit_search_old_price($v, $k = '_eb_product_price')
{
	$sql = _eb_q("SELECT *
	FROM
		`" . wp_postmeta . "`
	WHERE
		meta_key = '" . $k . "'
		AND post_id = " . $v . "
	ORDER BY
		meta_id DESC
	LIMIT 0, 1");
	//	print_r( $sql );
	if (!empty($sql)) {
		return $sql[0]->meta_value;
	} else {
		WGR_multi_edit_price_script_log('Product price not found! #' . $v . ' (' . $k . ')');
	}

	//
	return 0;
}

function WGR_multi_edit_price_script_log($v)
{
	echo '<script>WHR_show_console_in_multi("' . $v . '");</script>';
}



//
if ($actions_for == 'status') {
	if (isset($_POST['t_trangthai'])) {
		$trv_trangthai = (int) $_POST['t_trangthai'];

		//
		foreach ($list_id as $v) {
			$v = (int) trim($v);
			if ($v > 0) {
				WGR_update_meta_post($v, '_eb_product_status', $gia);
			}
		}
	} else {
		_eb_alert('Chưa chọn [Trạng thái] mới');
	}
} else if ($actions_for == 'enddate') {
	$trv_ngayhethan = trim($_POST['t_ngayhethan']);

	if (strlen($trv_ngayhethan) == 10) {
		foreach ($list_id as $v) {
			$v = (int) trim($v);
			if ($v > 0) {
				WGR_update_meta_post($v, '_eb_product_ngayhethan', $trv_ngayhethan);
			}
		}
	} else {
		foreach ($list_id as $v) {
			$v = (int) trim($v);
			if ($v > 0) {
				delete_post_meta($v, '_eb_product_ngayhethan');
			}
		}
	}
}
// khi cần tăng giá gốc của sản phẩm lên thao % nào đó
else if ($actions_for == 'tanggia') {
	$phantram_tanggia = (int) $_POST['t_tanggia'];
	if ($phantram_tanggia < 0) {
		$phantram_tanggia = 0;
	} else if ($phantram_tanggia >= 100) {
		$phantram_tanggia = 99;
	}
	WGR_multi_edit_price_script_log($phantram_tanggia);

	//
	if (cf_set_raovat_version != 1) {
		$sql = "UPDATE `wp_postmeta`
		SET
			`meta_value` = meta_value + (meta_value * $phantram_tanggia.0 / 100.0)
		WHERE
			`meta_key` = '_eb_product_oldprice'
			AND `meta_value` > 0";

		//
		if (!empty($list_id)) {
			$list_id .= " AND post_id IN (" . implode(',', $list_id) . ")";
		}

		//
		_eb_q($sql, 0);
	} else {
		WGR_multi_edit_price_script_log('Not running in raovat site!');
	}
}
// khi cần giảm giá theo giá gốc
else if ($actions_for == 'giamgia') {
	$phantram_giamgia = (int) $_POST['t_giamgia'];
	if ($phantram_giamgia < 0) {
		$phantram_giamgia = 0;
	} else if ($phantram_giamgia >= 100) {
		$phantram_giamgia = 99;
	}
	WGR_multi_edit_price_script_log($phantram_giamgia);

	//
	if (cf_set_raovat_version != 1) {
		//	if ( $phantram_giamgia > 0 ) {
		foreach ($list_id as $v) {
			$v = (int) trim($v);
			if ($v > 0) {
				// tìm theo giá cũ
				$giacu = WGR_multi_edit_search_old_price($v, '_eb_product_oldprice');
				$gia = WGR_multi_edit_search_old_price($v);

				// nếu giá cũ < giá mới -> cập nhật lại luôn
				if ($giacu <= $gia) {
					$giacu = $gia;

					// chuyển giá cũ sang giá mới luôn
					if ($giacu > 0) {
						WGR_update_meta_post($v, '_eb_product_oldprice', $giacu);
					}
				}

				// tìm được giá thì mới xử lý
				if ($giacu == 0) {
					WGR_multi_edit_price_script_log('Product price is zero #' . $v);
				}
				// tính toán giá mới
				else {
					if ($phantram_giamgia > 0) {
						$gia = $giacu - ceil($giacu / 100 * $phantram_giamgia);

						// cập nhật giá mới
						WGR_update_meta_post($v, '_eb_product_price', $gia);
					}
					// cập nhật theo giá cũ
					else {
						WGR_update_meta_post($v, '_eb_product_price', $giacu);
					}
					//					echo $gia . '<br>';
				}
			}
		}
	} else {
		//		WGR_multi_edit_price_script_log('Percent is zero!');
		WGR_multi_edit_price_script_log('Not running in raovat site!');
	}
} else if ($actions_for == 'stt') {
	$trv_stt = _eb_number_only($_POST['t_stt']);

	if ($trv_stt < 0) {
		$trv_stt = 0;
	}

	//
	$arr_for_update = array();
	$arr_for_update['menu_order'] = $trv_stt;

	//
	foreach ($list_id as $v) {
		$v = (int) trim($v);
		if ($v > 0) {
			$arr_for_update['ID'] = $v;

			$post_id = WGR_update_post($arr_for_update, 'ERROR!');
		}
	}
} else {
	_eb_alert('Không xác định được chức năng cần thiết!');
}


//
die('<script>parent.WGR_after_update_multi_post();</script>');




exit();