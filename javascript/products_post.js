//
WGR_admin_quick_edit_select_menu();
change_update_new_stt();

// thay đổi số lượng bài viết sẽ hiển thị
WGR_change_set_thread_show_in_page();

// bấm vào để chỉnh sửa giá nhanh
jQuery(".click-quick-edit-price")
	.off("click")
	.click(function () {
		var id = jQuery(this).attr("data-id") || "",
			old_price = jQuery(this).attr("data-old-price") || "",
			new_price = jQuery(this).attr("data-new-price") || "";

		if (old_price == "") {
			old_price = 0;
		}

		if (new_price == "") {
			new_price = 0;
		}

		if (old_price == 0 && new_price > 0) {
			old_price = new_price;
		}

		//
		//	console.log( id );
		//	console.log( old_price );
		//	console.log( new_price );

		//
		var f = document.frm_quick_edit_price;
		f.t_product_id.value = id;
		f.t_old_price.value = g_func.money_format(old_price);
		f.t_new_price.value = g_func.money_format(new_price);
		f.data_ajax.value = $(this).attr("data-ajax") || "";

		//
		jQuery("#frm_quick_edit_price").show();

		//
		f.t_new_price.focus();
	});

//
jQuery("#quick_edit_new_price")
	.off("change")
	.change(function () {
		// v2
		WGR_check_quick_edit_price(true);
		return false;
	});

//
jQuery("#quick_edit_old_price")
	.off("change")
	.change(function () {
		// v2
		WGR_check_quick_edit_price(true);
		return false;
	});

//
function WGR_fixed_quick_edit_price(a, b) {
	a = jQuery.trim(a);
	//	console.log( a );

	// thiết lập giá trị mặc định nếu chưa có
	if (a == "") {
		return 0;
	} else {
		// nhân thêm 1000
		if (a.toString().toLowerCase().includes("k") == true) {
			a = g_func.only_number(a);
			a = a * 1000;
		}
		// tính theo %
		else if (a.toString().includes("%") == true) {
			if (typeof b == "undefined") {
				b = a;
			}
			b = g_func.only_number(b);

			// giảm theo số %
			if (a.toString().includes("-") == true) {
				a = (b / 100) * g_func.only_number(a);
				a = b + a;
			}
			// bằng số %
			else {
				a = (b / 100) * g_func.only_number(a);
			}
		}
	}
	//	console.log( a );
	//	console.log( g_func.only_number( a ) );

	//
	return g_func.only_number(a) * 1;
}

function WGR_check_quick_edit_price(no_auto_submit) {
	var f = document.frm_quick_edit_price;
	var a = WGR_fixed_quick_edit_price(f.t_old_price.value),
		b = WGR_fixed_quick_edit_price(f.t_new_price.value, a),
		aj = f.data_ajax.value;

	// nếu giá cũ nhỏ hơn giá mới -> set về 0 luôn
	if (b <= 0 && a > 0) {
		b = a;
		a = 0;
	} else if (a <= b) {
		//	else if ( a == b ) {
		a = 0;
	}
	//	console.log( a ); console.log( b ); return false;

	//
	if (typeof no_auto_submit == "undefined") {
		var trang = jQuery(".admin-part-page strong").html() || 1,
			//			uri = '&post_id=' + f.t_product_id.value + '&by_post_type=post&trang=' + trang + '&t=update_price&old_price=' + a + '&new_price=' + b;
			uri = aj + "&t=update_price&old_price=" + a + "&new_price=" + b;
		//		console.log( uri );

		//
		WGR_admin_quick_edit_products("products", uri);
	} else {
		f.t_old_price.value = g_func.money_format(a);
		f.t_new_price.value = g_func.money_format(b);
	}

	//
	return false;
}

// tìm kiếm sản phẩm
function auto_submit_products_post_search() {
	var f = document.frm_products_post_search;

	WGR_admin_quick_edit_products(
		"products",
		"&by_post_type=" +
			f.by_post_type.value +
			"&q=" +
			encodeURIComponent(f.q.value)
	);

	return false;
}

// Chỉnh sửa SEO nhanh
WGR_click_open_quick_edit_seo();

// quick multi edit
function load_list_thread_check() {
	var str = "";
	jQuery(".eb-uix-thread-checkbox:checkbox:checked").each(function () {
		//		console.log( jQuery(this).val() );
		str += "," + jQuery(this).val();
	});

	//
	document.frm_multi_edit_post.t_list_id.value = str;
}

function WGR_after_update_multi_post() {
	f = document.frm_multi_edit_post;

	//
	ajaxl(
		"products&by_post_type=" +
			f.by_post_type.value +
			"&by_cat_id=" +
			f.by_cat_id.value +
			"&trang=" +
			f.current_page.value,
		"rAdminME",
		9,
		function () {
			jQuery("#rAdminME").css({
				opacity: 1,
			});
		}
	);
}

jQuery(".click-show-tools")
	.off("click")
	.click(function () {
		jQuery(".show-if-click-tools, .show-if-quick-edit").toggle("slow");

		//
		var a = g_func.getc("eb_admin_quick_edit_post_taxonomy");

		// nếu -> đang mở -> đóng lại
		if (a != null || a == 1) {
			g_func.delck("eb_admin_quick_edit_post_taxonomy");
		}
		// chưa có -> gán lệnh đang mở
		else {
			g_func.setc("eb_admin_quick_edit_post_taxonomy", 1, 6 * 3600);
		}
	});

//
jQuery("#thread-all-checkbox")
	.off("click")
	.click(function () {
		jQuery("input[name='thread-checkbox']").prop(
			"checked",
			dog("thread-all-checkbox").checked
		);

		//
		load_list_thread_check();
	});

//
jQuery(".eb-uix-thread-checkbox")
	.off("click")
	.click(function () {
		load_list_thread_check();
	});

//
jQuery(".focus-set-actions-for")
	.off("change")
	.change(function () {
		var a = jQuery(this).attr("data-for") || "",
			b = jQuery(this).attr("data-id-for") || 0,
			f = document.frm_multi_edit_post;

		//
		if (a == "") {
			a_lert("Không xác định được thao tác");
		}

		//
		f.actions_for.value = a;
		f.actions_id_for.value = b;
	});

jQuery(".click-set-actions-for")
	.off("click")
	.click(function () {
		var a = jQuery(this).attr("data-for") || "",
			b = jQuery(this).attr("data-id-for") || 0,
			f = document.frm_multi_edit_post;

		if (f.t_list_id.value == "") {
			a_lert("Không xác định được sản phẩm cần sửa");
		} else if (a == "") {
			a_lert("Không xác định được thao tác");
		} else {
			jQuery("#rAdminME").css({
				opacity: 0.2,
			});

			//
			f.actions_for.value = a;
			f.actions_id_for.value = b;
			f.submit();

			//
			jQuery("#target_eb_iframe").on("load", function () {
				jQuery("#rAdminME").css({
					opacity: 1,
				});
			});
		}
	});

//
$(".click-convert-date-to-stt").click(function () {
	var a = $(this).attr("data-ajax") || "";

	if (a != "") {
		var f = document.frm_products_post_search;

		WGR_admin_quick_edit_products(
			"products",
			"&by_post_type=" + f.by_post_type.value + a
		);
	} else {
		console.log("%c data ajax not found!", "color: red;");
	}
});

//_global_js_eb.select_date('.thread-list-ngayhethan');
jQuery(document).ready(function ($) {
	$(".thread-list-ngayhethan").datepicker();
});
_global_js_eb.ebe_currency_format();
