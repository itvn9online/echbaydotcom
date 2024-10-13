// nút thêm sản phẩm vào giỏ hàng
var add_to_cart_running = false;
setTimeout(function () {
	jQuery(".click-jquery-add-to-cart").click(function () {
		// nếu trong chi tiết sản phẩm -> sẽ kiểm tra sản phẩm có đang bị đánh dấu hết hàng không
		if (typeof pid != "undefined" && pid > 0) {
			if (product_post_status != "publish") {
				a_lert("Xin lỗi quý khách! Sản phẩm hiện đang ngừng bán");
				return false;
			} else if (product_trv_trangthai * 1 === 7) {
				a_lert("Xin lỗi quý khách! Sản phẩm hiện đang hết hàng");
				return false;
			}
		}

		//
		//		if ( pid == 0 ) {
		if (add_to_cart_running == true) {
			console.log("add to cart running");
			return false;
		}
		add_to_cart_running = true;

		//
		let product_price =
				jQuery(this).attr("data-gia") || jQuery(this).attr("data-price") || "",
			//			tr = jQuery(this).attr('data-open-iframe') || 0,
			product_object = {};

		//
		if (product_price == "") {
			product_price = 0;

			if (pid > 0) {
				// nếu chưa chọn màu hoặc size -> yêu cầu chọn
				// sau sư dụng php để tạo list chọn size, color
				/*
                if ( _global_js_eb.check_size_color_cart() == false ) {
                    return false;
                }
                */

				if (typeof product_js.gm == "number" && product_js.gm > 0) {
					product_price = product_js.gm;
				}

				// lưu thông tin color, size để show trong giỏ hàng
				let a = g_func.getc("eb_cookie_cart_lists");
				if (a == null) {
					a = {};
				} else {
					try {
						a = jQuery.parseJSON(unescape(a));
					} catch (e) {
						a = {};
						console.log(WGR_show_try_catch_err(e));
					}
				}
				a["_" + pid] = {
					size:
						jQuery(".oi_product_size li.selected").attr("data-name") ||
						jQuery(".oi_product_size li:first").attr("data-name") ||
						"",
					color:
						jQuery(".oi_product_color li.selected").attr("title") ||
						jQuery(".oi_product_color li:first").attr("title") ||
						"",
					quan:
						jQuery("#oi_change_soluong input").val() ||
						jQuery("#oi_change_soluong select").val() ||
						1,
				};
				//				console.log( a );
				g_func.setc(
					"eb_cookie_cart_lists",
					escape(JSON.stringify(a)),
					6 * 3600
				);
			}
		}

		//
		//		if ( tr == 1 || tr == '1' ) {
		//		if ( tr * 1 == 1 ) {
		if (WGR_check_option_on(cf_add_to_cart_iframe)) {
			product_object.open_iframe = 1;
		}

		//
		product_object.price = product_price;

		_global_js_eb.cart_add_item(
			jQuery(this).attr("data-id") || pid || 0,
			product_object
		);
	});

	//
	jQuery(".click-jquery-show-quick-cart").click(function () {
		if (pid == 0) {
			if (WGR_check_option_on(cf_tester_mode)) console.log("for post only");
			return false;
		}

		// nếu có URL một website nào đó -> nhảy sang link web đó -> bán hàng thông qua lazada, shopee để cắt giảm chi phí
		if (_eb_product_supper_shop != "") {
			// thêm các thông số track
			let a = [];
			if (_eb_product_supper_shop.includes("utm_source") == false) {
				a.push("utm_source=" + document.domain.replace(/\./g, "_"));
			}
			if (_eb_product_supper_shop.includes("utm_medium") == false) {
				a.push("utm_medium=echbay_com");
			}
			if (_eb_product_supper_shop.includes("utm_campaign") == false) {
				a.push("utm_medium=webgiare_org");
			}
			a = a.join("&");

			// nếu url không có dấu ? -> đổi lại dấu hỏi luôn
			if (_eb_product_supper_shop.includes("?") == false) {
				a = "?" + a.substr(1);
			}
			_eb_product_supper_shop += a;

			//
			_global_js_eb.ga_event_track(
				"Go to supper shop (" + document.domain + ")",
				"",
				{
					//				'category' : '',
					//				'label' : '',
					action: "go_to_shop",
				}
			);
			window.open(_eb_product_supper_shop, "_blank");
			return false;
		}

		// Hiển thị quick cart dạng popup nếu quick cart không hiển thị sẵn
		if (WGR_show_or_scroll_to_quick_cart() == false) {
			// nếu đang xem trong iframe -> mở ra giỏ hàng luôn
			if (top != self) {
				parent.window.location = web_link + "cart/?id=" + pid;
				return false;
			}

			// Hiển thị bình thường
			if (jQuery("#click_show_cpa").length > 0) {
				jQuery("#click_show_cpa").show();
				jQuery("body").addClass("body-no-scroll");
				jQuery('#click_show_cpa input[name="t_dienthoai"]').focus();

				//
				/*
                let a = jQuery(window).height() - jQuery('.cart-quick').height();
                if (a > 0) {
                    a = a / 3;
                } else {
                    a = 25;
                }
                jQuery('.cart-quick').css({
                    'margin-top': a + 'px'
                });
                */
			} else {
				console.log(
					"%c Không xác định được form mua hàng nhanh",
					"color: red;"
				);
			}
		}
	});

	// Nút kép -> nhảy sang giỏ hàng hoặc mở quick cart
	jQuery(".click-jquery-quickcart-or-cart").click(function (e) {
		// Chuyển sang giỏ hàng nếu không có quick cart
		if (WGR_show_or_scroll_to_quick_cart() == false) {
			jQuery(".click-jquery-add-to-cart:first").click();
		}
	});

	//
	/*
    jQuery('.click-jquery-add-to-cart a, .click-jquery-quickcart-or-cart a').click(function() {
        return false;
    });
    */

	// do trên fb có chút lỗi khi redrect trình duyệt 2 lần -> chỉnh lại phương thức hiển thị nút mua
	jQuery(".show-if-javascript").removeClass("show-if-javascript");
	jQuery(".hide-if-javascript").hide();

	//
	//	console.log('TEST');
	//	jQuery('.click-jquery-show-quick-cart:first').click();
}, 600);

// chuyển các URL video thành dạng list video
(function () {
	jQuery(".echbay-widget-youtube-padding .img-max-width").each(function () {
		let a = jQuery(this).html() || "";

		if (a != "") {
			jQuery(".echbay-widget-youtube-padding .vhidden").removeClass("vhidden");
			jQuery(".echbay-widget-youtube-remove").hide().remove();

			//
			let str = "",
				wit = jQuery(this).width(),
				hai = Math.ceil(wit * youtube_video_default_size);
			wit = Math.ceil(wit);

			//
			//			console.log(wit);
			//			console.log(hai);
			//			console.log(a);
			a = a.split("\n");
			//			console.log(a);

			for (let i = 0; i < a.length; i++) {
				a[i] = g_func.trim(a[i]);

				if (a[i] != "") {
					a[i] = _global_js_eb.youtube_id(a[i]);
				}

				if (a[i] != "") {
					if (WGR_check_option_on(cf_tester_mode))
						console.log("Widget YouTube video: " + a[i]);

					str +=
						'<div class="widget_echbay_youtube-node"><iframe src="//www.youtube.com/embed/' +
						a[i] +
						'" allowfullscreen="" frameborder="0" height="' +
						hai +
						'" width="' +
						wit +
						'"></iframe></div>';
				}
			}

			//
			jQuery(this).html(str);
		}
	});
})();

// chuyển các URL video thành dạng list video
(function () {
	//	jQuery('.widget_echbay_gg_map .url-to-google-map').each(function() {
	jQuery(".url-to-google-map").each(function () {
		let a = jQuery(this).html() || "",
			str = "",
			wit = 4 / 5,
			scrolling = jQuery(this).attr("data-scrolling") || "",
			localtion = jQuery(this).attr("data-localtion") || "",
			zoom = jQuery(this).attr("data-zoom") || 10,
			w = jQuery(this).attr("data-width") || "",
			h = jQuery(this).attr("data-height") || "",
			other_attr = "";
		//		console.log( localtion );

		// tự tạo localtion theo địa chỉ
		if (localtion != "") {
			localtion = g_func.non_mark(localtion);
			//			console.log( localtion );

			// danh sách địa chỉ
			other_attr += ' aria-label="' + localtion.replace(/\"/g, "&quot;") + '"';

			//
			if (zoom == "") {
				zoom = 14;
			}

			// thay thế url
			a =
				"https://maps.google.com/maps?q=" +
				encodeURIComponent(localtion) +
				"&t=m&z=" +
				zoom.toString() +
				"&output=embed&iwloc=near".replace(/\&/g, "&amp;");
			//			console.log( a );
		}

		if (a != "") {
			a = a.split("\n")[0];
			a = g_func.trim(a);

			if (a != "") {
				jQuery(this).show().html("");

				// tính toán kích thước
				if (w == "") {
					w = "100%";
				}
				if (h == "" || global_window_width < 755) {
					h = jQuery(this).width() * wit;
					if (h > 450) {
						h = 450;
					}
				}
				if (scrolling == "on") {
					other_attr += ' scrolling="no"';
				}

				str +=
					'<div class="widget_echbay_gg_map-node"><iframe src="' +
					a +
					'" width="' +
					w +
					'" height="' +
					h +
					'px" frameborder="0" marginheight="0" marginwidth="0"' +
					other_attr +
					' style="border:0" allowfullscreen=""></iframe></div>';

				jQuery(this).attr({
					"data-iframe": escape(str),
				});
			}
		}
	});
})();

//
//console.log(act);

//
if (act == "search") {
	jQuery('.thread-search-avt[data-img=""]').hide();

	//
	_global_js_eb.ga_event_track("", "", "", {
		//		'category' : '',
		//		'label' : '',
		action: "view_search_results",
	});
}

// google analytics event tracking
setTimeout(function () {
	if (pid > 0) {
		// đối với sản phẩm
		if (eb_wp_post_type == "post") {
			_global_js_eb.ga_event_track("View product", document.title, "", {
				//'category' : '',
				//'label' : '',
				items: [
					{
						id: "P" + pid,
						name: product_js.tieude,
						price: product_js.gm > 0 ? product_js.gm : product_js.gia,
					},
				],
				action: "view_item",
			});

			//
			_global_js_eb.tt_track("ViewContent", {
				description: product_js.tieude,
				content_id: "" + pid,
			});
		}
		// mặc định là cho blog
		else {
			_global_js_eb.ga_event_track("View blog", document.title, "", {
				//'category' : '',
				//'label' : '',
				action: "view_blog",
			});
		}
	} else if (act == "archive") {
		if (
			switch_taxonomy == "category" ||
			switch_taxonomy == "post_tag" ||
			switch_taxonomy == "post_options"
		) {
			if (WGR_check_option_on(cf_tester_mode)) console.log("test track for fb");

			//
			let track_arr = {
				content_name:
					jQuery("h1:first a").html() ||
					jQuery("h1:first").html() ||
					document.title,
			};

			//
			let ids = "",
				args = [],
				dr_items = [],
				dr_value = 0;
			jQuery(".thread-list li").each(function () {
				if (args.length > 25) {
					return false;
				}

				//
				let a = jQuery(this).attr("data-id") || "";

				if (a != "") {
					let price = jQuery(this).attr("data-price") || 0;
					ids += "," + a;

					//
					args.push({
						id: "P" + a,
						name: jQuery("a:first", this).attr("title") || "",
						price: price,
					});

					//
					dr_items.push({
						id: a,
						google_business_vertical: "retail",
					});
					dr_value += price * 1;
				}
			});
			if (ids != "") {
				track_arr["content_ids"] = ids.substr(1).split(",");

				//
				_global_js_eb.fb_track("ViewContent", track_arr);

				// tiktok
				_global_js_eb.tt_track("ViewContent", {
					content_id: "" + track_arr["content_ids"][0],
				});
			} else {
				console.log("ids for facebook track not found");
			}
			console.log("args:", args);
			console.log("dr_items:", dr_items);
			console.log("dr_value:", dr_value);

			//
			if (args.length > 0) {
				_global_js_eb.ga_event_track(
					"View list",
					"Xem danh sach san pham",
					"",
					{
						//'category' : '',
						//'label' : '',
						items: args,
						action: "view_item_list",
					}
				);

				//
				if (typeof dataLayer != "undefined") {
					console.log("dataLayer push");
					dataLayer.push({
						dr_event_type: "view_item_list",
						dr_items: dr_items,
						dr_value: dr_value,
						event: "dynamic_remarketing",
					});
				}
			}
		}
	} else if (act == "cart") {
		_global_js_eb.ga_event_track("View cart", "Xem gio hang", "", {
			//			'category' : '',
			//			'label' : '',
			action: "view_cart",
		});

		_global_js_eb.cart_size_color();
	}
	//	else if ( act == 'hoan-tat' ) {
	//	}
}, 3000);

// ID của pid -> để tạo giỏ hàng cho chuẩn
var current_pid_quicview = pid,
	set_new_height_for_quick_view = true,
	time_for_new_height_quick_view = 800,
	// lưu title hiện tại của trang
	cache_for_quick_view_title = document.title,
	// lưu lại ID hiện tại của quick view
	cache_for_quick_view_id = 0,
	cache_for_quick_view_url = "";

WGR_fixed_a_tag();
WGR_thread_list_quickview();

//
var press_esc_to_quickvideo_close = false;
if (press_esc_to_quickvideo_close == false) {
	if (WGR_check_option_on(cf_tester_mode))
		console.log("create event close by ESC");

	//
	press_esc_to_quickvideo_close = true;

	jQuery(document).keydown(function (e) {
		//		console.log( e.keyCode );

		//
		if (e.keyCode == 27) {
			// trong cùng một cửa sổ
			if (top == self) {
				close_img_quick_video_details();
				g_func.opopup();
				close_ebe_quick_view();
			}
			// khác cửa sổ (ví dụ như khi dùng quick view)
			else {
				try {
					if (WGR_check_option_on(cf_tester_mode))
						console.log("Close popup window");

					// hàm tự viết
					top.close_img_quick_video_details();
					top.g_func.opopup();
					top.close_ebe_quick_view();

					// hoặc hàm mặc định của trình duyệt
					window.close();
					self.close();
				} catch (e) {
					console.log(WGR_show_try_catch_err(e));
				}
			}
		}
	});
}

//
(function () {
	let i = 0,
		fn = "frm_search",
		fn_rand = "";
	jQuery(".div-search form").each(function () {
		let a = jQuery(this).attr("name") || "";

		if (a == "") {
			if (i > 0) {
				fn_rand = i;
			}

			jQuery(this).attr({
				name: fn + fn_rand,
			});

			i++;
		}
	});
})();

// menu for mobile
if (typeof document.frm_search != "undefined") {
	if (jQuery("#click_add_to_search").length > 0) {
		jQuery("#value_add_to_search")
			.off()
			.keyup(function (e) {
				//	console.log(e.keyCode);
				if (e.keyCode == 13) {
					jQuery("#click_add_to_search").click();
					return false;
				}
				//	}).val( jQuery('input[type="search"]').val() || '' );
				//		}).val( document.frm_search.s.value );
			})
			.val(jQuery('form[name="frm_search"] input[type="search"]').val() || "");

		//
		jQuery("#click_add_to_search")
			.off("click")
			.click(function () {
				//document.frm_search.s.value = jQuery('#value_add_to_search').val() || '';
				jQuery('form[name="frm_search"] input[type="search"]').val(
					jQuery("#value_add_to_search").val() || ""
				);

				//
				//if ( document.frm_search.s.value.length > 2 ) {
				if (
					jQuery('form[name="frm_search"] input[type="search"]').val().length >
					2
				) {
					document.frm_search.submit();
				} else {
					jQuery("#value_add_to_search").focus();
				}
			});
	}
}

// daidq (2024-10-13): tạm bỏ chức năng lưu log click
_global_js_eb._log_click_ref();

// báo lỗi nếu có thẻ dynamic_title_tag chưa được chuyển đổi
if (jQuery("dynamic_title_tag").length > 0) {
	a_lert("dynamic_title_tag cần được thay đổi sang DIV hoặc H*");
	console.log("================= dynamic_title_tag =================");
} else if (jQuery("dynamic_widget_tag").length > 0) {
	a_lert("dynamic_widget_tag cần được thay đổi sang DIV hoặc H*");
	console.log("================= dynamic_widget_tag =================");
}

// chuyển các đoạn CSS sử dụng font-awesome sang js -> SVG
jQuery(".widget-category-selectbox .echbay-widget-title").append(
	'<i class="fas fa-sort-down"></i>'
);

// mở trang đăng ký, đăng nhập cho người dùng khi cần hỗ trợ
(function (hr) {
	//console.log(hr);
	if (typeof isLogin != "undefined" && isLogin * 1 > 0) {
		console.log("%c Login exist!", "color: red;");
		return false;
	}

	//
	if (hr.includes("?register=1") == true) {
		g_func.opopup("register");
	} else if (hr.includes("?login=1") == true) {
		g_func.opopup("login");
	}
})(window.location.href);
