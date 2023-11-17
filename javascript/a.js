//
//console.log( typeof jQuery );
if (typeof jQuery != "function") {
	console.log("jQuery not found");
}

//
//console.log( typeof $ );
if (typeof $ != "function") {
	$ = jQuery;
}

//
if (cf_chu_de_chinh != "") {
	jQuery("#menu-posts .wp-menu-name").html(cf_chu_de_chinh);
}

// nạp iframe để submit cho tiện
_global_js_eb.add_primari_iframe();

// chức năng xử lý cho product size
var gallery_has_been_load = false,
	current_ls_url = window.location.href;

function WGR_get_parameter_in_url(s) {
	// tìm theo dấu ?
	let a = current_ls_url.split("&" + s + "=");
	if (a.length > 1) {
		return a[1].split("&")[0];
	}

	//
	a = current_ls_url.split("?" + s + "=");
	if (a.length > 1) {
		return a[1].split("&")[0];
	}

	return "";
}

function WGR_set_class_parameter_in_url(s) {
	s = WGR_get_parameter_in_url(s);
	console.log(s);

	if (s != "") {
		jQuery('ul.echbay-subsubsub a[data-slug="' + s + '"]').addClass("redcolor");
	}
}

function WGR_set_html_loc_to_admin_post_list(op) {
	if (typeof op["inner"] == "undefined" || op["inner"] == "") {
		op["inner"] = "#posts-filter";
	}

	//
	jQuery("#posts-filter").before(
		'<div class="div-subsubsub"><ul class="echbay-subsubsub cf l19">' +
			op["str"] +
			"</ul></div>"
	);

	//
	if (typeof op["get"] != "undefined" && op["get"] != "") {
		WGR_set_class_parameter_in_url(op["get"]);
	}
}

function WGR_create_html_loc_to_admin_post_list(arr, op) {
	if (arr.length == 0) {
		return false;
	}

	//
	//	console.log(arr);
	console.log(op);

	//
	let str = '<li class="bold">' + op["name"] + ":</li>";
	for (let i = 0; i < arr.length; i++) {
		str +=
			'<li><a href="' +
			admin_link +
			op["url"] +
			op["get"] +
			"=" +
			arr[i].slug +
			'" data-slug="' +
			arr[i].slug +
			'">' +
			arr[i].ten +
			"</a></li>";
	}

	//
	WGR_set_html_loc_to_admin_post_list({
		get: op["get"],
		str: str,
	});
}

// tạo url chung cho các module
(function (admin_body_class) {
	//
	//	jQuery('body').addClass('folded');
	//	jQuery('#adminmenu').addClass('cf');

	//
	//	console.log( typeof jQuery );
	//	console.log( typeof $ );

	//
	let win_href = window.location.href,
		admin_act = EBE_get_current_wp_module(win_href);
	console.log(admin_act);

	//
	jQuery(".admin-set-reload-url").attr({
		href: win_href,
	});

	// đánh dấu các tab đang được xem
	jQuery(".eb-admin-tab a").each(function () {
		let a = jQuery(this).attr("href") || "";
		//		console.log(a);

		if (a != "") {
			a = a.split("&tab=");

			if (a.length > 1) {
				a = a[1].split("&")[0];

				jQuery(this).attr({
					"data-tab": a,
				});
			}
		}
	});

	// đánh dấu tab
	let a = win_href.split("&tab=");
	if (a.length > 1) {
		a = a[1].split("&")[0].split("#")[0];
		//		console.log(a);

		jQuery('.eb-admin-tab a[data-tab="' + a + '"]').addClass("selected");
	} else {
		jQuery(".eb-admin-tab li:first a").addClass("selected");
	}

	// post size (product size)
	// nếu đang trong phần sửa bài viết
	if (admin_act == "post") {
		WGR_run_for_admin_edit_post();

		// ẩn phần mã giảm gí đi, không sử dụng ở đây
		if (typeof pagenow != "undefined" && pagenow == "post") {
			jQuery(
				'#tagsdiv-discount_code, #tagsdiv-discount_code-hide, label[for="tagsdiv-discount_code-hide"]'
			)
				.hide()
				.addClass("hide-if-js")
				.addClass("d-none");
			console.log("Hide tagsdiv-discount_code");
		}

		//
		WGR_find_all_taxonomy_for_edit();
	}
	// thêm bài viết mới
	else if (admin_act == "post-new") {
		// thêm STT mới nhất để bài viết này luôn được lên đầu khi thêm mới
		if (order_max_post_new > 0) {
			jQuery("#menu_order").val(order_max_post_new * 1 * 1);
			console.log("Add menu order to maximun");
		}

		// ẩn bớt các công cụ chưa dùng để cho nó tiện
		//		$('#postbox-container-2').hide();
		//		console.log('hide postbox-container-2 in post new');

		//
		WGR_find_all_taxonomy_for_edit();
	}
	// danh sách post, page, custom post type
	else if (admin_act == "list") {
		// nếu là post
		//		if ( win_href.split('post_type=').length == 1
		//		|| win_href.split('post_type=post').length > 1 ) {
		if (pagenow == "edit-post" && typenow == "post") {
			jQuery("table.wp-list-table").addClass(
				"admin-list-product-avt"
			) /* .width( '150%' ) */;

			//
			/*
             console.log(arr_eb_product_status);
             
             let ads_loc = '<li class="bold">Trạng thái:</li>';
             for ( let i = 0; i < arr_eb_product_status.length; i++ ) {
             ads_loc += '<li><a href="' + admin_link + 'edit.php?post_type=post&post_filter_status=' + arr_eb_product_status[i].id + '">' + arr_eb_product_status[i].ten + '</a></li>';
             }
             console.log(ads_loc);
             
             jQuery('#posts-filter').before('<ul class="echbay-subsubsub cf">' + ads_loc + '</ul>');
             jQuery('ul.subsubsub').addClass('cf').css({
             float: 'none'
             });
             */

			//
			let ads_loc = "";

			//
			//			console.log(arr_eb_product_status);
			ads_loc = '<li class="bold">Trạng thái:</li>';
			for (let i = 0; i < arr_eb_product_status.length; i++) {
				ads_loc +=
					'<li><a href="' +
					admin_link +
					"edit.php?post_type=post&post_filter_status=" +
					arr_eb_product_status[i].id +
					'" data-slug="' +
					arr_eb_product_status[i].id +
					'">' +
					arr_eb_product_status[i].ten +
					"</a></li>";
			}

			//
			WGR_set_html_loc_to_admin_post_list({
				get: "post_filter_status",
				str: ads_loc,
			});

			//
			WGR_create_html_loc_to_admin_post_list(eb_options_group, {
				name: "Thông số khác",
				get: "post_options",
				url: "edit.php?post_type=post&",
			});

			//
			WGR_create_html_loc_to_admin_post_list(eb_site_group, {
				name: "Chuyên mục",
				get: "category_name",
				url: "edit.php?post_type=post&",
			});

			//
			jQuery("ul.subsubsub").addClass("cf").css({
				float: "none",
			});
		}
		// nếu là ads -> thêm bộ lọc tìm kiếm theo trạng thái
		else if (win_href.split("post_type=ads").length > 1) {
			let ads_loc = "";

			//
			//			console.log(arr_eb_ads_status);
			ads_loc = '<li class="bold">Trạng thái:</li>';
			for (let i = 0; i < arr_eb_ads_status.length; i++) {
				ads_loc +=
					'<li><a href="' +
					admin_link +
					"edit.php?post_type=ads&ads_filter_status=" +
					arr_eb_ads_status[i].id +
					'" data-slug="' +
					arr_eb_ads_status[i].id +
					'">' +
					arr_eb_ads_status[i].ten +
					"</a></li>";
			}

			//
			WGR_set_html_loc_to_admin_post_list({
				get: "ads_filter_status",
				str: ads_loc,
			});

			//
			WGR_create_html_loc_to_admin_post_list(eb_options_group, {
				name: "Thông số khác",
				get: "post_options",
				url: "edit.php?post_type=ads&",
			});

			//
			WGR_create_html_loc_to_admin_post_list(eb_site_group, {
				name: "Chuyên mục",
				get: "category_name",
				url: "edit.php?post_type=ads&",
			});

			//
			WGR_create_html_loc_to_admin_post_list(eb_blog_group, {
				name: "Danh mục tin",
				get: "blogs",
				url: "edit.php?post_type=ads&",
			});

			//
			jQuery("ul.subsubsub").addClass("cf").css({
				float: "none",
			});
		}
		// nếu là ads -> thêm bộ lọc tìm kiếm theo trạng thái
		else if (win_href.split("post_type=blog").length > 1) {
			WGR_create_html_loc_to_admin_post_list(eb_blog_group, {
				name: "Danh mục tin",
				get: "blogs",
				url: "edit.php?post_type=blog&",
			});

			//
			jQuery("ul.subsubsub").addClass("cf").css({
				float: "none",
			});
		}
	}
	// danh sách đơn hàng
	/*
     else if ( win_href.split('?page=eb-order').length > 1 ) {
     // thu gọn menu của wp
     //		jQuery('body').addClass('folded');
     }
     */
	// danh sách category
	else if (admin_act == "cat_list") {
		/*
         // fix chiều cao cho cột mô tả -> vì nó dài quá
         jQuery('#the-list').addClass('eb-hide-description');
         
         jQuery('#the-list .column-description').each(function(index, element) {
         let a = jQuery(this).html() || '';
         if ( a != '' ) {
         jQuery(this).html( '<div class="eb-fixed-content-height">' + a + '</div>' );
         }
         }).addClass('show-column-description');
         
         // mặc định sẽ ẩn cột description đi cho nó gọn
         if ( dog('description-hide') != null && dog('description-hide').checked == true ) {
         jQuery('#description-hide').click();
         if ( dog('description-hide').checked == true ) {
         dog('description-hide').checked = false;
         }
         }
         */
	}
	// chỉnh sửa category
	else if (admin_act == "cat_details") {
		WGR_check_if_value_this_is_one("_eb_category_in_list");
		WGR_check_if_value_this_is_one("_eb_category_primary");
		WGR_check_if_value_this_is_one("_eb_category_noindex");
		WGR_check_if_value_this_is_one("_eb_category_hidden");

		// riêng từng phần
		if (typeof pagenow != "undefined") {
			if (pagenow == "edit-discount_code") {
				jQuery("#_eb_category_coupon_giagiam").removeClass("m").addClass("n");
				jQuery("#_eb_category_coupon_phantramgiam")
					.removeClass("m")
					.addClass("s");
				jQuery("#_eb_category_coupon_donggia").removeClass("m").addClass("n");

				jQuery("#_eb_category_coupon_ngayhethan")
					.removeClass("m")
					.addClass("n");

				jQuery("#_eb_category_coupon_toithieu").removeClass("m").addClass("n");
				jQuery("#_eb_category_coupon_toida").removeClass("m").addClass("n");

				jQuery("#_eb_category_coupon_product").removeClass("m").addClass("n");
				jQuery("#_eb_category_coupon__product").removeClass("m").addClass("n");

				jQuery("#_eb_category_coupon_category").removeClass("m").addClass("n");
				jQuery("#_eb_category_coupon__category").removeClass("m").addClass("n");

				jQuery("#_eb_category_coupon_max").removeClass("m").addClass("s");
			}
		}

		//
		if ($("#taxonomy_image").length > 0) {
			$("#_eb_category_favicon").val($("#taxonomy_image").val() || "");

			$("#taxonomy_image").change(function () {
				$("#_eb_category_favicon").val($("#taxonomy_image").val() || "");
			});

			$(".edit-tag-actions .button-primary").click(function () {
				$("#taxonomy_image").change();
			});
		}
	}
	// thêm tài khoản thành viên
	else if (admin_act == "user-new") {
		jQuery("#createuser .form-table tr:last").after(
			"" +
				'<tr class="form-field">' +
				"<th>&nbsp;</th>" +
				"<td>" +
				(jQuery("#echbay_role_user_note").html() ||
					"DIV #echbay_role_user_note not found") +
				"</td>" +
				"</tr>"
		);
	}
	// sửa tài khoản thành viên
	else if (admin_act == "user-edit") {
		jQuery(".user-role-wrap").after(
			"" +
				'<tr class="form-field">' +
				"<th>&nbsp;</th>" +
				"<td>" +
				(jQuery("#echbay_role_user_note").html() ||
					"DIV #echbay_role_user_note not found") +
				"</td>" +
				"</tr>"
		);
	}
	// không cho người dùng chỉnh sửa kích thước ảnh thumb -> để các câu lệnh dùng thumb sẽ chính xác hơn
	else if (admin_act == "media") {
		jQuery("#wpbody-content .form-table tr:first td:last")
			.addClass("disable-edit-thumb-small")
			.append('<div class="div-edit-thumb-small">&nbsp;</div>');
	}
	// chuyển rule wordpress sang nginx cho nó mượt
	else if (admin_act == "permalink") {
		//		console.log( arr_wordpress_rules.length );
		console.log(arr_wordpress_rules);

		let str = "";
		for (let x in arr_wordpress_rules) {
			let rule = x,
				rewrite = arr_wordpress_rules[x];

			if (rule.substr(rule.length - 1) != "$") {
				rule += "$";
			}
			if (rule.substr(0, 1) != "^") {
				rule = "^" + rule;
			}

			if (rewrite.substr(0, 1) != "/") {
				rewrite = "/" + rewrite;
			}

			str += "rewrite " + rule + " " + rewrite + ";" + "\n";
		}

		// Thay tham số của wordpress bằng tham số nginx
		str = str.replace(/\$matches\[1\]/gi, "$1");
		str = str.replace(/\$matches\[2\]/gi, "$2");
		str = str.replace(/\$matches\[3\]/gi, "$3");
		str = str.replace(/\$matches\[4\]/gi, "$4");
		str = str.replace(/\$matches\[5\]/gi, "$5");
		str = str.replace(/\$matches\[6\]/gi, "$6");
		str = str.replace(/\$matches\[7\]/gi, "$7");
		str = str.replace(/\$matches\[8\]/gi, "$8");
		str = str.replace(/\$matches\[9\]/gi, "$9");
		str = str.replace(/\$matches\[10\]/gi, "$10");

		//		str = str.replace( /\{1\,\}/gi, '{1,10}' );
		str = str.replace(/\{1\,\}/gi, "?");
		str = str.replace(/\{4\}/gi, "(4)");
		str = str.replace(/\{1,2\}/gi, "(1,2)");
		str = str.replace(/\{4\}/gi, "(4)");
		str = str.replace(/\{4\}/gi, "(4)");
		str = str.replace(/\{4\}/gi, "(4)");

		//		console.log(str);

		jQuery('form[name="form"]').after(
			'<textarea style="width:99%;height:600px;">' + str + "</textarea>"
		);
	}
	// ở phần menu thì thêm 1 số menu tĩnh vào để add cho nhanh
	else if (admin_act == "menu") {
		WGR_main_edit_menu();
	}
	// hỗ trợ cho cái chuyển đổi taxonomy
	else if (pagenow == "tools_page_taxonomy-switcher") {
		jQuery("select#from_tax option, select#to_tax option").each(function () {
			jQuery(this).html(
				jQuery(this).html() +
					" (" +
					(jQuery(this).val() || jQuery(this).attr("value") || "") +
					")"
			);
		});
	}

	// hiển thị khung post dưới localhost để test
	if (win_href.split("localhost:").length > 1) {
		jQuery("#target_eb_iframe").height(600).css({
			position: "relative",
			top: 0,
			left: 0,
			height: "600px",
		});
	}

	//
	jQuery('input[id="_eb_category_order"]').width(90);

	//
	fix_textarea_height();
	//	fix_all_textarea_height();

	// thêm CSS hiển thị nút add IMG cho category
	if (
		jQuery("#_eb_category_avt").length > 0 ||
		jQuery("#_eb_category_favicon").length
	) {
		jQuery("head").append(
			"<style>" +
				"div.gallery-add-to-category_avt," +
				"div.gallery-add-to-category_favicon { display: block; }" +
				"</style>"
		);
	}

	// mở gallery tự viết
	jQuery(
		"#_eb_category_avt, #_eb_category_favicon, #_eb_product_avatar, #cf_favicon, #cf_logo, #cf_og_image"
	).each(function () {
		let show_only = jQuery(this).attr("data-show") || "",
			update_for = jQuery(this).attr("id") || "";

		jQuery(this).after(
			'<button type="button" data-show="' +
				show_only +
				'" data-update="' +
				update_for +
				'" class="button click-open-new-gallery">Add image</button>'
		);
	});

	//
	jQuery(".click-open-new-gallery").click(function () {
		jQuery("#oi_admin_popup").show();

		// cuộn đến cuối của ô thêm ảnh
		window.scroll(0, jQuery(this).offset().top - jQuery(window).height() + 90);

		//
		let show_only = jQuery(this).attr("data-show") || "";

		//
		if (gallery_has_been_load == false) {
			gallery_has_been_load = true;
			setTimeout(function () {
				gallery_has_been_load = false;
			}, 15 * 1000);

			//
			dog("oi_admin_popup").innerHTML = "waiting...";

			//
			let post_ID = jQuery("#post_ID").val() || "";
			if (post_ID != "") {
				post_ID = "&post_ID=" + post_ID;
			}

			//
			ajaxl("gallery" + post_ID, "oi_admin_popup", 9, function () {
				// Nếu có thuộc tính hiển thị option
				if (show_only != "") {
					// chỉ hiển thị option theo chỉ định
					jQuery("#oi_admin_popup .eb-newgallery-option ." + show_only).show();
				}
			});
		}
		// Hiển thị option theo chỉ định
		else if (
			show_only != "" &&
			jQuery("#oi_admin_popup .eb-newgallery-option").length > 0
		) {
			//			jQuery('#oi_admin_popup .eb-newgallery-option div').hide();
			jQuery("#oi_admin_popup .eb-newgallery-option ." + show_only).show();
		}
	});
	//	jQuery('.click-open-new-gallery').click();

	//
	jQuery("#wp-admin-bar-top-secondary").addClass("cf eb-admin-bar-support");
	jQuery("#wp-admin-bar-top-secondary li:first").before("<li>TEST</li>");

	//});
})(jQuery("body").attr("class") || "");

//
function process_for_press_esc() {
	jQuery(".click-to-exit-design").click();
	jQuery("#oi_admin_popup, .hide-if-press-esc").hide();

	// xóa nội dung trong 1 số phần
	jQuery(".remove-if-press-esc").html("");

	//
	jQuery("body")
		.removeClass("ebdesign-no-scroll")
		.removeClass("body-no-scroll")
		.removeClass("fixed-tool-admin-menu");

	window.history.pushState("", "", current_ls_url);

	// nếu admin đang mở trong popup -> xử lý cả ở cửa số cha
	if (top != self) {
		try {
			top.process_for_press_esc();
		} catch (e) {
			parent.process_for_press_esc();
		}
		//		window.opener.process_for_press_esc();
	}
}

// Tất cả các hiệu ứng khi bấm ESC sẽ bị đóng lại
jQuery(document)
	.keydown(function (e) {
		if (e.keyCode == 27) {
			console.log("ESC to close");

			process_for_press_esc();
		}
	})
	.ready(function () {
		// điều chỉnh dữ liệu phần discount code
		if (dog("_eb_category_coupon_ngayhethan") != null) {
			//_global_js_eb.select_date('#_eb_category_coupon_ngayhethan');
			$("#_eb_category_coupon_ngayhethan").datepicker();

			// chuyển định dạng số cho phần giá
			jQuery(
				"#_eb_category_coupon_giagiam, #_eb_category_coupon_donggia, #_eb_category_coupon_toithieu, #_eb_category_coupon_toida"
			)
				.change(function () {
					let a = jQuery(this).val() || 0;

					//				a = g_func.number_only( a );
					a = g_func.money_format(a);

					if (a == "") {
						a = 0;
					}

					//			console.log(a);
					jQuery(this).val(a);
				})
				.change();
		}
	});

// hiển thị iframe submit của EchBay
jQuery(".click-show-eb-target").click(function () {
	jQuery("#target_eb_iframe").addClass("show-target-echbay");
});

// xóa CSS chặn các menu khác của admin
var current_cookie_show_hide_admin_menu = g_func.getc(
	"ebe_click_show_hidden_menu"
);
//console.log( current_cookie_show_hide_admin_menu );

jQuery(".click-show-no-customize").click(function (e) {
	// đang bật -> tắt
	if (current_cookie_show_hide_admin_menu == null) {
		jQuery("#admin-hide-menu").remove();

		//
		current_cookie_show_hide_admin_menu = encodeURIComponent(
			window.location.href
		);
		g_func.setc(
			"ebe_click_show_hidden_menu",
			current_cookie_show_hide_admin_menu,
			4 * 3600,
			7
		);
	}
	// đang tắt -> bật
	else {
		g_func.delck("ebe_click_show_hidden_menu");

		current_cookie_show_hide_admin_menu = null;

		console.log("Please re-load for disable function hide-menu");
		setTimeout(function () {
			//			if (confirm('Re-load for disable function hide-menu?') == true) {
			window.location = window.location.href;
			//			}
		}, 600);
	}
});

//
if (cf_hide_supper_admin_menu == 1) {
	// thông báo cho người dùng
	if (current_cookie_show_hide_admin_menu == null) {
		console.log("Hide menu for admin");
	} else {
		console.log("Hide menu admin is active! but cookies disable is ON");
	}
}

// clone menu EchBay.com to top
(function () {
	let id_echbay_menu = "toplevel_page_eb-order",
		a = {};
	jQuery("#" + id_echbay_menu + " a").each(function (index, element) {
		let h = jQuery(this).attr("href") || "",
			t = jQuery(this).html() || "";
		a[h] = t;
	});
	//	console.log(a);

	//
	jQuery("#wp-admin-bar-root-default").append(
		'<li id="' +
			id_echbay_menu +
			'-top" class="menupop"><a href="javascript://" class="ab-item"><i class="fa fa-leaf"></i> ' +
			private_info_setting_site_upper +
			"</a></li>"
	);

	jQuery("#" + id_echbay_menu + "-top").append(
		'<div class="ab-sub-wrapper"><ul id="' +
			id_echbay_menu +
			'-submenu" class="ab-submenu"></ul></div>'
	);

	// thêm menu update nếu có update mới
	let check_update =
		jQuery("#menu-dashboard ul a .update-plugins .update-count").html() || 0;
	if (parseInt(check_update, 10) > 0) {
		//		console.log( jQuery('#menu-dashboard ul a .update-plugins .update-count').length );

		// Hiển thị menu update trên top
		jQuery("#wp-admin-bar-root-default").append(
			'<li class="menupop"><a href="update-core.php" class="ab-item orgcolor bold reddot wgr-eb-show-menu-update"><i class="fa fa-download"></i> ' +
				(jQuery('#menu-dashboard ul a[href="update-core.php"]').html() ||
					"Update core") +
				"</a></li>"
		);

		// Hiển thị menu chỗ mục update
		jQuery("#menu-dashboard ul, #menu-plugins").show();

		// xóa thẻ span trong menu update
		jQuery(".wgr-eb-show-menu-update span").remove();
	}

	// kiểm tra phiên bản plugin
	//	if ( wgr_plugin_current_version != wgr_plugin_github_version ) {
	if (
		parseInt(wgr_plugin_current_version.replace(/\./g, ""), 10) <
		parseInt(wgr_plugin_github_version.replace(/\./g, ""), 10)
	) {
		console.log("UPDATE NEW PLUGIN VERSION");

		jQuery("#toplevel_page_eb-order ul, ul#wp-admin-bar-root-default").append(
			'<li><a href="' +
				admin_link +
				'admin.php?page=eb-about&click_to_update_core=1&connect_to=github" class="orgcolor reddot no-set-cookies">Update WGR Plugin</a></li>'
		);
	}

	// kiểm tra phiên bản theme -> theme thì chỉ có sử dụng child-theme thì mới có version
	//	if ( wgr_theme_current_version != '' && wgr_theme_current_version != wgr_theme_github_version ) {
	if (
		wgr_theme_current_version != "" &&
		private_info_setting_parent_theme_default == "echbaytwo" &&
		parseInt(wgr_theme_current_version.replace(/\./g, ""), 10) <
			parseInt(wgr_theme_github_version.replace(/\./g, ""), 10)
	) {
		console.log("UPDATE NEW THEME VERSION");

		jQuery("#toplevel_page_eb-order ul, ul#wp-admin-bar-root-default").append(
			'<li><a href="' +
				admin_link +
				'admin.php?page=eb-about&click_to_update_core=1&connect_to=theme" class="orgcolor reddot no-set-cookies">Update WGR Theme</a></li>'
		);
	}

	//
	for (let x in a) {
		jQuery("#" + id_echbay_menu + "-submenu").append(
			'<li><a class="ab-item" href="' + x + '">' + a[x] + "</a></li>"
		);
	}
})();

//
function WGR_admin_add_img_lazzy_load(img) {
	if (img != "") {
		jQuery(".each-to-bgimg:first").css({
			"background-image": "url('" + img + "')",
		});
	}

	//
	jQuery(".each-to-bgimg:first")
		.removeClass("each-to-bgimg")
		.removeClass("eb-lazzy-effect");
}

// load 10 cái đầu tiên trước
jQuery(".each-to-bgimg")
	.slice(0, 10)
	.each(function () {
		WGR_admin_add_img_lazzy_load(jQuery(this).attr("data-img") || "");
	});

//
if (jQuery(".each-to-bgimg").length > 0) {
	//
	jQuery(".each-to-bgimg").addClass("eb-lazzy-effect");

	//
	jQuery(window).scroll(function () {
		let new_scroll_top = window.scrollY || jQuery(window).scrollTop();
		//		console.log(new_scroll_top);

		jQuery(".eb-lazzy-effect").each(function () {
			a = jQuery(this).offset().top || 0;

			if (a < new_scroll_top + 600) {
				WGR_admin_add_img_lazzy_load(jQuery(this).attr("data-img") || "");
			}
		});
	});
}

// Hủy lưu URL mỗi khi người dùng bấm vào link
//jQuery('#adminmenu a').click(function () {
/*
 jQuery('#toplevel_page_eb-order a').click(function () {
 });
 */

// tự động lưu phiên định kỳ
setInterval(function () {
	g_func.setc("wgr_check_last_user_visit", "webgiare.org", 2 * 3600);
}, 1800 * 1000);

// chỉ lưu cookie khi người dùng bấm vào link của EchBay
jQuery("#toplevel_page_eb-order a").click(function () {
	// chỉ lưu các URL nằm trong menu chính của EchBay
	let a = jQuery(this).attr("href") || "";
	if (a != "") {
		g_func.setc("wgr_last_url_user_visit", escape(a), 4 * 3600);
	}

	// Nếu phiên lưu URL đã hết hạn
	if (g_func.getc("wgr_check_last_user_visit") == null) {
		// -> lưu phiên mới luôn
		g_func.setc("wgr_check_last_user_visit", "webgiare.org", 2 * 3600);
		//		g_func.setc( 'wgr_last_url_user_visit', '', 60 );
	}
});

jQuery("#toplevel_page_eb-order a.no-set-cookies").off("click");

// hiển thị các video youtube trong các phần hướng dẫn ra ngoài
jQuery(".admin-add-youtube-video").each(function () {
	// chỉ lưu các URL nằm trong menu chính của EchBay
	let a = jQuery(this).attr("data-src") || "";
	a = _global_js_eb.youtube_id(a);

	//
	if (a != "") {
		// tính chiều rộng của video
		let w = jQuery(window).width(),
			h = w;
		w = (w / 100) * 90;
		if (w > 800) {
			w = 800;
		}
		h = w * youtube_video_default_size;

		//
		jQuery(this).html(
			'<iframe width="' +
				w +
				'" height="' +
				h +
				'" src="https://www.youtube.com/embed/' +
				a +
				'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'
		);
	}
});

// chỉnh sửa nhanh ảnh đại diện
jQuery(".admin-list-post-avt").dblclick(function () {
	let a = jQuery(this).attr("data-img") || "",
		jd = jQuery(this).attr("data-id") || "",
		lnk = prompt("URL ảnh:", a);

	//
	if (jd == "" || jd * 1 <= 0) {
		return false;
	}

	if (lnk == "" || lnk == null || lnk == a) {
		return false;
	}

	//
	ajaxl(
		"quick-update-avt&new_avt=" + encodeURIComponent(lnk),
		"div-for-ajax-load",
		1,
		function () {
			jQuery('.admin-list-post-avt[data-id="' + jd + '"]').css({
				"background-image": "url('" + lnk + "')",
			});
		}
	);
});

// TEST
/*
 if ( window.location.href.split('?page=eb-products').length > 1 ) {
 jQuery('#rAdminME').append('<div data-src="https://www.youtube.com/watch?v=M4xVAyNEBUg" class="admin-open-youtube-video cur">Test xem video youtube</div>');
 }
 */

// hiển thị video youtube khi người dùng bấm
jQuery(window).on("load", function () {
	jQuery(".admin-open-youtube-video")
		.off("click")
		.click(function () {
			// chỉ lưu các URL nằm trong menu chính của EchBay
			let a = jQuery(this).attr("data-src") || "";
			a = _global_js_eb.youtube_id(a);

			//
			if (a != "") {
				// tính chiều rộng của video
				let w = jQuery(window).width(),
					h = w;
				w = (w / 100) * 90;
				if (w > 800) {
					w = 800;
				}
				h = w * youtube_video_default_size;

				//
				if (dog("quick_youtube_support") == null) {
					jQuery("body").append(
						'<div id="quick_youtube_support" class="hide-if-press-esc remove-if-press-esc"></div>'
					);
				}

				//
				jQuery("#quick_youtube_support")
					.show()
					.html(
						'<div onclick="process_for_press_esc();" class="cur">Bấm vào đây hoặc phím <em><strong>ESC</strong></em> để thoát</div> <iframe width="' +
							w +
							'" height="' +
							h +
							'" src="https://www.youtube.com/embed/' +
							a +
							'?rel=0&autoplay=1&html5=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'
					);
			}
		});
});

// Thêm icon cho link bản PRO -> gạ gẫm người dùng nâng cấp thì mới có tiền chứ
jQuery(
	'#toplevel_page_eb-order li a[href="admin.php?page=eb-licenses"]'
).append(' <i class="fa-pro small upper"></i>');

// thêm neu chỉnh sửa nhanh cho các hạng mục
jQuery("#menu-posts ul.wp-submenu li.wp-first-item").after(
	'<li><a href="admin.php?page=eb-products">Chỉnh sửa nhanh</a></li>'
);
jQuery("#menu-posts-ads ul.wp-submenu li.wp-first-item").after(
	'<li><a href="admin.php?page=eb-products&by_post_type=ads">Chỉnh sửa nhanh</a></li>'
);
jQuery("#menu-posts-blog ul.wp-submenu li.wp-first-item").after(
	'<li><a href="admin.php?page=eb-products&by_post_type=blog">Chỉnh sửa nhanh</a></li>'
);

//
/*
 jQuery('.ebe-currency-format').each(function() {
 let a = jQuery(this).attr('data-num') || jQuery(this).html() || '';
 
 if ( a != '' && a != '0' ) {
 jQuery(this).html( g_func.money_format( a ) );
 }
 });
 */
_global_js_eb.ebe_currency_format();

function WGR_set_a_target_top() {
	jQuery("a").each(function () {
		let a = jQuery(this).attr("target") || "";
		if (a == "") {
			jQuery(this).attr({
				target: "_top",
			});
		}
	});
}
setTimeout(function () {
	WGR_set_a_target_top();
	setTimeout(function () {
		WGR_set_a_target_top();
		setTimeout(function () {
			WGR_set_a_target_top();
		}, 2000);
	}, 1000);
}, 200);

//
g_func.setc("wgr_ebsuppercache_timeout", 1, 1800);

//
(function () {
	/*
	 * Chức năng dành riêng cho trang widget
	 */
	if (jQuery("body.widgets-php").length == 0) {
		return false;
	}
	console.log("%c Chức năng chạy riêng trên trang widget", "color: green;");

	//
	if (jQuery("#widgets-right").length == 0) {
		console.log("%c Không xác định được thẻ #widgets-right", "color: red;");
		return false;
	}

	//
	jQuery("#widgets-right").before(
		'<div class="redcolor"><input type="checkbox" onClick="jQuery(\'#widgets-right\').toggleClass(\'active\');" /> Đánh dấu vào ô này để kích hoạt chức năng chỉnh sửa widget. Việc chỉnh sửa widget yêu cầu nhân viên kỹ thuật có độ am hiểu nhất định để tránh việc làm hỏng giao diện website. Nếu không phải là bạn, vui lòng bỏ qua chức năng này. Xin cảm ơn.<div>'
	);
})();
