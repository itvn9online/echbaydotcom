function WGR_order_export_exist(a, k) {
	if (typeof a[k] != "undefined" && a[k] != "") {
		return a[k];
	}
	return "&nbsp;";
}

function WGR_order_export_null(s) {
	return s == "" ? "&nbsp;" : s;
}

function WGR_order_export___products(arr, arr2) {
	if (
		typeof arr2["hd_phivanchuyen"] == "undefined" ||
		arr2["hd_phivanchuyen"] == ""
	) {
		arr2["hd_phivanchuyen"] = 0;
	}

	//
	var tong = arr.quan * arr.price;
	if (
		typeof arr2["hd_chietkhau"] == "undefined" ||
		arr2["hd_chietkhau"] == ""
	) {
		arr2["hd_chietkhau"] = 0;
	} else {
		// tính theo %
		if (
			arr2["hd_chietkhau"] != "" &&
			arr2["hd_chietkhau"].toString().includes("%") == true
		) {
			arr2["hd_chietkhau"] =
				(tong / 100) * g_func.float_only(arr2["hd_chietkhau"]);
		}
	}

	//
	return (
		"<td>" +
		arr.name +
		"</td>" +
		'<td class="text-center">' +
		arr.quan +
		"</td>" +
		'<td class="text-center remove-size-color-tag">' +
		WGR_order_export_null(arr.color) +
		"</td>" +
		'<td class="text-center remove-size-color-tag">' +
		WGR_order_export_null(arr.size) +
		"</td>" +
		'<td class="text-center">' +
		WGR_order_export_null(arr.sku) +
		"</td>" +
		"<td>" +
		WGR_order_export_exist(arr, "weight") +
		"</td>" +
		'<td class="text-center">' +
		tong +
		"</td>" +
		'<td class="text-center">' +
		WGR_order_export_null(arr2["hd_chietkhau"]) +
		"</td>" +
		'<td class="text-center">' +
		WGR_order_export_null(arr2["hd_phivanchuyen"]) +
		"</td>" +
		'<td class="text-center">' +
		(tong - arr2["hd_chietkhau"] + arr2["hd_phivanchuyen"] * 1) +
		"</td>"
	);

	/*
	return '<div class="cf">' +
			'<div class="lf f80"><a href="' + web_link + '?p=' + arr.id + '" target="_blank" rel="nofollow">- ' + arr.name + '</a></div>' +
			'<div class="lf f5 text-center">' + arr.quan + '</div>' +
			'<div class="lf f10 text-center remove-size-color-tag">' + arr.color + '</div>' +
			'<div class="lf f5 text-center remove-size-color-tag">' + arr.size + '</div>' +
		'</div>';
		*/
}

function WGR_order_export__products(arr, arr2) {
	var str = "";

	// không tính cước vận chuyển lần 2
	arr2["hd_phivanchuyen"] = "";

	//
	for (var i = 0; i < arr.length; i++) {
		if (i > 0) {
			str +=
				'<tr><td colspan="5">&nbsp;</td>' +
				WGR_order_export___products(arr[i], arr2) +
				"</tr>";
		}
	}

	return str;
}

function WGR_order_export_products(arr, arr2) {
	return WGR_order_export___products(arr[0], arr2);
}

//
function WGR_order_export_run(arr, i) {
	//
	arr_status = arr_hd_trangthai;

	//
	if (arr.order_products == "" || arr.order_customer == "") {
		jQuery("#headerTable").append(
			"<tr>" +
				"<td>" +
				(i + 1) +
				"</td>" +
				"<td>" +
				arr.order_id +
				"</td>" +
				"<td>" +
				arr.order_sku +
				"</td>" +
				"<td>" +
				(typeof arr_status[arr.order_status] != "undefined"
					? arr_status[arr.order_status]
					: "Unknown") +
				"</td>" +
				"<td>ERROR load data in JSON!!!!!!!!!!!!!!!!!!!</td>" +
				"</tr>"
		);
		console.log(arr);
		return false;
	}

	//
	//	console.log( arr );

	//
	//	var order_lnk = admin_link + 'admin.php?page=eb-order&id=';

	//
	//	for ( var i = 0; i < arr.length; i++ ) {
	//
	var prod = jQuery.parseJSON(unescape(arr.order_products)),
		cus = jQuery.parseJSON(unescape(arr.order_customer));
	if (i == 0) {
		console.log(arr);
		console.log(prod);
		console.log(cus);
	}

	//
	jQuery("#headerTable").append(
		"<tr>" +
			"<td>" +
			(i + 1) +
			"</td>" +
			"<td>" +
			arr.order_id +
			"</td>" +
			"<td>" +
			arr.order_sku +
			"</td>" +
			"<td>" +
			arr.ngay_gui +
			"</td>" +
			"<td>" +
			(typeof arr_status[arr.order_status] != "undefined"
				? arr_status[arr.order_status]
				: "Unknown") +
			"</td>" +
			//			'<td>' + WGR_order_export_products( prod, cus ) + '</td>' +
			WGR_order_export_products(prod, cus) +
			'<td class="upper">' +
			(cus["hd_ten"] == "" ? "No-name" : cus["hd_ten"]) +
			"</td>" +
			"<td>" +
			cus["hd_dienthoai"] +
			"</td>" +
			"<td>" +
			cus["hd_diachi"] +
			"</td>" +
			"<td>" +
			WGR_order_export_exist(cus, "hd_quanhuyen") +
			"</td>" +
			"<td>" +
			WGR_order_export_exist(cus, "hd_tinhthanh") +
			"</td>" +
			"<td>" +
			WGR_order_export_exist(cus, "hd_utm_source") +
			"</td>" +
			"<td>" +
			WGR_order_export_exist(cus, "hd_utm_medium") +
			"</td>" +
			"<td>[" +
			WGR_order_export_exist(cus, "hd_utm_campaign") +
			"]</td>" +
			"</tr>" +
			WGR_order_export__products(prod, cus)
	);

	//	}

	//
	jQuery(".remove-size-color-tag strong").remove();
}

// xác định chiều rộng cho table hiển thị cho đẹp
jQuery(document).ready(function () {
	(function () {
		// tính tổng số thẻ tr
		var c = jQuery("#headerTable tr").length;
		if (c <= 1) {
			return false;
		}

		//		console.log( c );
		// -> chiều cao của body -> thêm 10 đơn vị để dự phòng
		c = c * 16 + 10;
		//		console.log( c );
		//		console.log( jQuery('#headerTable').height() );

		//
		for (var i = 0; i < 150; i++) {
			if (jQuery("#headerTable").height() > c) {
				//				console.log( jQuery('#headerTable').height() );
				jQuery("#headerTable").width(100 + i * 1 + "%");
			} else {
				// thêm lần dữ phòng nữa
				jQuery("#headerTable")
					.width(110 + i * 1 + "%")
					.addClass("pd");
				//				console.log( i );
				//				console.log( jQuery('#headerTable').height() );

				//
				jQuery("body").addClass("done");

				//
				/*
				setTimeout(function () {
					jQuery('#headerTable').addClass('moz-transition');
				}, 800);
				*/

				break;
			}
		}

		//
		jQuery("#headerTable tr").click(function () {
			jQuery("#headerTable tr").removeClass("selected");
			jQuery(this).addClass("selected");
		});
	})();
});

//
jQuery(".click-change-css-table").click(function () {
	jQuery("#headerTable").toggleClass("set-border");
});

// tạo bảng dùng để lấy tên cột cho vào google sheet
jQuery(".click-create-col-for-excel").click(function () {
	if (jQuery("#col_for_google_sheet td").length == 0) {
		// đoạn tạo TD này thì copy cho vào file google sheet
		jQuery("#headerTable tr:first td").each(function () {
			var a = g_func.non_mark_seo(jQuery(this).html());
			jQuery("#col_for_google_sheet tr").append("<td>" + a + "</td>");
			if (a == "san-pham") {
				jQuery("#col_for_google_sheet tr").append("<td>url</td>");
			}
		});
		//		jQuery('#col_for_google_sheet tr').append('<td>domain</td>');

		// xóa td đầu tiên -> không dùng stt
		jQuery("#col_for_google_sheet td:first").remove();

		// tạo form cho khung hoàn tất
		var str = "",
			str2 = "";
		jQuery("#col_for_google_sheet td").each(function () {
			str +=
				'\'<input type="text" name="' +
				jQuery(this).html() +
				'" value="" />\' +' +
				"\n";
			str2 +=
				"jQuery('form#backup-order-to-google-sheet input[name=\"" +
				jQuery(this).html() +
				"\"]').val();" +
				"\n";
		});

		// các đoạn mã này sau đó copy cho vào function WGR_backup_order_to_google_sheet
		console.log(str);
		console.log(str2);
	}
});
