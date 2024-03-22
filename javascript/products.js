function WGR_admin_quick_edit_select_menu() {
	//
	jQuery(".set-url-post-post-type").each(function () {
		var a = jQuery(this).attr("data-type") || "post";

		jQuery(this).attr({
			href:
				window.location.href
					.split("&by_post_type=")[0]
					.split("&by_taxonomy=")[0] +
				"&by_post_type=" +
				a,
		});
	});

	//
	jQuery(".set-url-taxonomy-category").each(function () {
		var a = jQuery(this).attr("data-type") || "post";

		jQuery(this).attr({
			href:
				window.location.href
					.split("&by_post_type=")[0]
					.split("&by_taxonomy=")[0] +
				"&by_taxonomy=" +
				a,
		});
	});

	//
	if (window.location.href.includes("&by_taxonomy=") == true) {
		jQuery(
			'.set-url-taxonomy-category[data-type="' + by_taxonomy + '"]'
		).addClass("bold");
	} else {
		/*
	else if ( window.location.href.includes('&check_post_name=') == true ) {
		jQuery('.check-post-name').addClass('bold');
	}
	*/
		jQuery(
			'.set-url-post-post-type[data-type="' + by_post_type + '"]'
		).addClass("bold redcolor");
	}
}

//
function WGR_click_open_quick_edit_seo() {
	//
	if (dog("oi_eb_products") == null) {
		jQuery("#rAdminME").after(
			'<div id="oi_eb_products" class="hide-if-press-esc"></div>'
		);
	}

	//
	jQuery(".click-open-quick-edit-seo")
		.off("click")
		.click(function () {
			// không cho bấm liên tiếp
			if (waiting_for_ajax_running == true) {
				console.log("waiting_for_ajax_running");
				return false;
			}
			waiting_for_ajax_running = true;

			//
			var a = jQuery(this).attr("data-id") || "";

			if (a == "") {
				return false;
			}
			//		console.log(a);

			//
			jQuery("#rAdminME").css({
				opacity: 0.2,
			});
			jQuery("#oi_eb_products").show();
			jQuery("body").addClass("ebdesign-no-scroll");

			//
			ajaxl(
				"products_seo&id=" + a + "&type=" + js_for_tax_or_post,
				"oi_eb_products",
				1,
				function () {
					jQuery("#rAdminME").css({
						opacity: 1,
					});

					waiting_for_ajax_running = false;
				}
			);
		});
}

//
function WGR_admin_quick_edit_products(
	connect_to,
	url_request,
	parameter,
	func_done
) {
	// kiểm tra dữ liệu đầu vào
	if (typeof connect_to == "undefined" || connect_to == "") {
		console.log("not set connect to");
		return false;
	}
	if (typeof url_request == "undefined" || url_request == "") {
		console.log("URL for request is NULL");
		return false;
	}

	// các tham số khác
	if (typeof parameter == "undefined") {
		parameter = "";
	}

	//
	if (typeof func_done != "function") {
		func_done = function () {};
	}

	// không cho bấm liên tiếp
	if (waiting_for_ajax_running == true) {
		console.log("waiting_for_ajax_running");
		return false;
	}
	waiting_for_ajax_running = true;

	//
	jQuery("#rAdminME").css({
		opacity: 0.2,
	});

	var uri = connect_to + url_request + parameter;
	//console.log(uri);
	ajaxl(uri, "rAdminME", 9, function () {
		jQuery("#rAdminME").css({
			opacity: 1,
		});

		waiting_for_ajax_running = false;

		//
		func_done();
	});
}

function change_update_new_stt() {
	//
	jQuery(".click-order-thread")
		.off("click")
		.click(function () {
			WGR_admin_quick_edit_products(
				"products",
				jQuery(this).attr("data-ajax") || ""
			);
		});

	//
	jQuery(".change-update-new-stt")
		.off("change")
		.change(function () {
			//	jQuery('.change-update-new-stt').off('keydown').keydown(function(e) {
			//		console.log(e.keyCode);
			//		if ( e.keyCode == 13 ) {
			var a = jQuery(this).val() || 0,
				j = jQuery(this).attr("id") || "";
			a = g_func.number_only(a);
			if (a * 1 < 0) {
				a = 0;
			}
			//			console.log( a );

			// giảm đi 1 đơn vị -> vì sử dụng lệnh của chức năng UP
			a--;
			//			console.log( a );

			//
			WGR_admin_quick_edit_products(
				"products",
				jQuery(this).attr("data-ajax") || "",
				a,
				function () {
					if (j != "") {
						jQuery("#" + j).focus();

						//
						setTimeout(function () {
							//						window.scroll( 0, jQuery('#' + j).offset().top - 90 );
							window.scroll(
								0,
								window.scrollY ||
									jQuery(window).scrollTop() ||
									jQuery("#" + j).offset().top - 90
							);
						}, 600);
					}
				}
			);
			//		}
			/*
	}).blur(function () {
		$(this).select();
		*/
		});

	//
	//	jQuery('input.change-update-custom-meta').off('keydown').keydown(function(e) {
	jQuery("input.change-update-custom-meta")
		.off("change")
		.change(function (e) {
			//		console.log(e.keyCode);
			//		if ( e.keyCode == 13 ) {
			var a = jQuery(this).val() || "",
				j = jQuery(this).attr("id") || "";

			WGR_admin_quick_edit_products(
				"products",
				jQuery(this).attr("data-ajax") || "",
				"&t=change&new_value=" + a,
				function () {
					if (j != "") {
						jQuery("#" + j).focus();

						//
						setTimeout(function () {
							//						window.scroll( 0, jQuery('#' + j).offset().top - 90 );
							window.scroll(
								0,
								window.scrollY ||
									jQuery(window).scrollTop() ||
									jQuery("#" + j).offset().top - 90
							);
						}, 600);
					}
				}
			);

			return false;
			//		}
		});

	//
	jQuery("select.change-update-custom-meta")
		.off("change")
		.change(function () {
			var a = jQuery(this).val() || "",
				j = jQuery(this).attr("id") || "";

			WGR_admin_quick_edit_products(
				"products",
				jQuery(this).attr("data-ajax") || "",
				"&t=change&new_value=" + a,
				function () {
					if (j != "") {
						setTimeout(function () {
							//					window.scroll( 0, jQuery('#' + j).offset().top - 90 );
							window.scroll(
								0,
								window.scrollY ||
									jQuery(window).scrollTop() ||
									jQuery("#" + j).offset().top - 90
							);
						}, 600);
					}
				}
			);
		})
		.each(function () {
			var a = jQuery(this).attr("data-value") || 0;

			if (a * 1 > 0) {
				jQuery('option[value="' + a + '"]', this).prop("selected", true);
			}
		});

	// đánh dấu phiên tìm kiếm trước
	var a = g_func.getc("eb_admin_quick_edit_post_taxonomy");
	if (a != null || a == 1) {
		jQuery(".show-if-click-tools, .show-if-quick-edit").show();
	}
}
