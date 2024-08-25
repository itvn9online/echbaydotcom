/*
 * d post
 * Các function dùng chung cho phần chi tiết bài viết sẽ được cho vào đây
 * Sau đó các file js riêng của từng theme sẽ kế thừa và sử dụng các chức năng bằng cách gọi function
 */
var time_next_details_slider = null;

// slider show (nếu có)
function ___eb_details_slider(auto_next_details_slider) {
	console.log(
		"WARNING! Function ___eb_details_slider bas been remove, please clear in your code!"
	);
}

// hiển thị giá liên hệ cho phần quick cart
function WGR_zero_price_quick_cart(quan, price) {
	if (
		typeof price == "undefined" ||
		price == "" ||
		price == 0 ||
		price == "0"
	) {
		jQuery("#oi_change_tongtien").hide();
		jQuery("#oi_details_zero_price").show();
		return 0;
	}
	//	if ( typeof quan != 'number' || quan == '' ) {
	if (typeof quan == "undefined" || quan == "") {
		quan = 1;
	} else {
		quan = quan * 1;
	}

	//
	let total_price = quan * price;
	jQuery("#oi_change_tongtien").show().html(g_func.money_format(total_price));
	jQuery("#oi_details_zero_price").hide();

	return total_price;
}

// số lượng để khách hàng mua hàng nhanh
function ___eb_details_cart_quan() {
	// using select
	if (1 == 1) {
		dog(
			"oi_change_soluong",
			'<input type="number" name="t_soluong[' + pid + ']" value="1" />'
		);
	} else {
		let str = '<option value="1">[ Chọn số lượng ]</option>',
			sl = "";
		for (let i = 1; i < 11; i++) {
			sl = "";
			if (i == 1) {
				sl = ' selected="selected"';
			}

			str += '<option value="' + i + '"' + sl + ">" + i + "</option>";
		}

		dog(
			"oi_change_soluong",
			'<select name="t_soluong[' + pid + ']">' + str + "</select>"
		);
	}

	jQuery("#oi_change_soluong input, #oi_change_soluong select").change(
		function () {
			let a = jQuery(this).val() || 0;

			/* TEST
        a = 3;
        console.log( a );
        console.log( product_js.gm );
        console.log( a * product_js.gm );
        console.log( g_func.money_format( a * product_js.gm ) );
        */
			let total_new_price = WGR_zero_price_quick_cart(a, product_js.gm);

			_global_js_eb.cart_create_arr_poruduct(total_new_price);
			WGR_check_discount_code_return("oi_check_discount_code");
		}
	);
	jQuery("#oi_change_soluong input, #oi_change_soluong select").change();
}

function WGR_after_load_details_lisder() {
	jQuery(".thread-details-mobileAvt li").click(function () {
		let a = jQuery(this).attr("data-src") || "";
		if (a != "") {
			a = ___eb_set_thumb_to_fullsize(a);
			if (WGR_check_option_on(cf_tester_mode)) console.log(a);

			jQuery(this).css({
				"background-image": 'url("' + a + '")',
			});
		}
	});

	// click vào cái đầu tiên luôn
	//	if ( cf_slider_details_play <= 0 ) {
	jQuery(".thread-details-mobileAvt li:first").click();
	if (WGR_check_option_on(cf_tester_mode))
		console.log("Auto click to first details slider");
	//	}

	// thay đổi kiểu tải thumb nếu thumb được chuyển sang chiều dọc
	// trên mobile thì để 1 chế độ xem thôi
	if (
		WGR_check_option_on(cf_details_right_thumbnail) &&
		global_window_width >= 775
	) {
		//
		let effect_for_post_slider =
			".child-thread-details-mobileAvt .jEBE_slider-thumbnail ul";

		//
		jQuery(".child-thread-details-mobileAvt .jEBE_slider-right-thumbnail")
			.off("click")
			.click(function () {
				//let a = jQuery('.child-thread-details-mobileAvt .jEBE_slider-thumbnail li.selected').attr('data-i') || 0;
				let a = jQuery(effect_for_post_slider).attr("data-scroll") || 1,
					len =
						jQuery(".child-thread-details-mobileAvt .jEBE_slider-thumbnail li")
							.length - 1;
				//			console.log(a);
				a = a - (0 - 1);
				if (a >= len) {
					a = len - 1;
				}
				//			console.log(a);

				//
				jQuery(effect_for_post_slider)
					.css({
						top:
							0 -
							a *
								jQuery(
									".child-thread-details-mobileAvt .jEBE_slider-thumbnail li:first"
								).height() -
							a * 5 +
							"px",
					})
					.attr({
						"data-scroll": a,
					});

				//
				jQuery(
					".child-thread-details-mobileAvt .jEBE_slider-left-thumbnail"
				).show();
			});

		jQuery(".child-thread-details-mobileAvt .jEBE_slider-left-thumbnail")
			.off("click")
			.click(function () {
				//let a = jQuery('.child-thread-details-mobileAvt .jEBE_slider-thumbnail li.selected').attr('data-i') || 0;
				let a = jQuery(effect_for_post_slider).attr("data-scroll") || 1;
				//			console.log(a);
				a = a - 1;
				if (a < 0) {
					a = 0;
				}
				//			console.log(a);

				//
				jQuery(effect_for_post_slider)
					.css({
						top:
							0 -
							a *
								jQuery(
									".child-thread-details-mobileAvt .jEBE_slider-thumbnail li:first"
								).height() -
							a * 5 +
							"px",
					})
					.attr({
						"data-scroll": a,
					});
			});
	}
}

function ___eb_details_slider_v2() {
	// trên mobile thì để 1 chế độ xem thôi -> xóa CSS điều khiển định dạng slider trước khi lệnh JS chạy để không bị vỡ khung
	if (
		WGR_check_option_on(cf_details_right_thumbnail) &&
		global_window_width < 775
	) {
		//		jQuery('link#thumb-col, link#thumb-col-left, link#thumb-col-mobile').remove();
		jQuery("link#thumb-col, link#thumb-col-left").remove();
	}

	// thời gian chuyển slider, nếu có thì phải hợp lệ (kiểu số nguyên, tối thiểu 1 giây)
	//	if ( typeof auto_next_details_slider != 'number' || auto_next_details_slider < 1000 ) {
	//		auto_next_details_slider = 0;
	//	}

	let str = "",
		str_swiper = "",
		str_thumb = "",
		i = 0,
		sr = "",
		slider_btn = "",
		slider_len = jQuery("#export_img_product img").length,
		html_for_get = "#export_img_product img",
		data_get = "data-src";

	//
	//	console.log( slider_len );

	// nếu slider chính không có ảnh -> lấy ảnh từ nội dung -> nếu option cho phép lấy ảnh từ nội dung
	if (slider_len == 0 && WGR_check_option_on(cf_details_content_slider)) {
		slider_len = jQuery("#content_img_product img").length;
		html_for_get = "#content_img_product img";
		data_get = "src";
	}
	//	console.log( slider_len );
	//	console.log( html_for_get );

	// tạo lại hiệu ứng cho phần gallery của (nếu có)
	jQuery("#content_img_product .gallery dl.gallery-item .gallery-icon a").click(
		function () {
			return false;
		}
	);
	// https://www.jqueryscript.net/lightbox/Responsive-Touch-enabled-jQuery-Image-Lightbox-Plugin.html
	//	let gallery = jQuery('#content_img_product .gallery dl.gallery-item .gallery-icon a').simpleLightbox();

	// -> nếu vẫn không có -> hủy slider
	if (slider_len <= 1) {
		jQuery(".hide-if-slider-null").hide();

		//
		let a = "";
		// let wit = jQuery(".thread-details-mobileAvt").width();
		// nếu chỉ có 1 ảnh -> in luôn cái ảnh đấy ra -> ảnh slider có thể là ảnh chất lượng hơn
		if (slider_len == 1) {
			a =
				jQuery(html_for_get).attr("data-src") ||
				jQuery(html_for_get).attr("src") ||
				"";
		}
		// xử lý chính ảnh đại diện
		else {
			a = jQuery(".thread-details-mobileAvt").attr("data-img") || "";
		}

		//
		if (a != "") {
			/*
            jQuery('.thread-details-mobileAvt').removeClass('ti-le-global').height('auto').css({
                'background-image' : 'none',
                'line-height' : 'normal'
            }).html( '<img src="' + ___eb_set_thumb_to_fullsize(a) + '" data-width="' + wit + '" style="max-width:' + wit + 'px;" />' );
            */
			jQuery(".thread-details-mobileAvt").css({
				"background-image": "url(" + ___eb_set_thumb_to_fullsize(a) + ")",
			});
		}

		//
		return false;
	}

	//
	jQuery(html_for_get).each(function () {
		//		sr = jQuery(this).attr(data_get) || '';
		(sr = jQuery(this).attr("data-src") || jQuery(this).attr("src") || ""),
			(srcset = jQuery(this).attr("srcset") || "");
		//		console.log( sr );

		//
		if (WGR_check_option_on(cf_on_details_slider)) {
			sr = ___eb_set_img_to_thumbnail(sr, srcset);
		}
		if (WGR_check_option_on(cf_tester_mode)) console.log(sr);

		//
		str +=
			'<li data-node="' +
			i +
			'" data-src="' +
			sr +
			'" style="background-image:url(\'' +
			sr +
			"')\">&nbsp;</li>";

		str_swiper +=
			'<div data-node="' +
			i +
			'" data-src="' +
			sr +
			'" class="swiper-slide" style="background-image:url(\'' +
			sr +
			"')\">&nbsp;</div>";

		str_thumb +=
			'<li data-node="' +
			i +
			'" data-src="' +
			sr +
			'"><div style="background-image:url(\'' +
			sr +
			"')\">&nbsp;</div></li>";

		slider_btn += '<li data-node="' + i + '"><i class="fa fa-circle"></i></li>';

		//
		i++;
	});

	//
	//	dog('export_img_product', '<ul class="cf">' + str_thumb + '</ul>');

	//
	//	if ( slider_len <= 1 ) {
	//		return false;
	//	}
	//	jQuery('.thread-details-mobileLeft, .thread-details-mobileRight').show();

	// tạo thumb nếu đủ ảnh
	jQuery(".thread-details-mobileAvt")
		.html('<ul class="cf">' + str + "</ul>")
		.css({
			"background-image": "",
		});

	// tải slider theo code mới
	if (WGR_check_option_on(cf_on_details_slider)) {
		/*
        if ( typeof eb_disable_auto_get_thumb == 'number' && WGR_check_option_on ( eb_disable_auto_get_thumb ) ) {
        }
        */

		//
		jEBE_slider(
			".thread-details-mobileAvt",
			{
				buttonListNext: WGR_check_option_on(cf_details_show_list_next)
					? true
					: false,

				// tự động chạy slider theo thời gian mà người dùng chỉ định
				autoplay: cf_slider_details_play > 0 ? true : false,
				//			swipemobile : true,
				swipemobile: WGR_check_option_on(cf_swipe_details_banner)
					? true
					: false,
				// nếu số giây tự chuyển slider nhỏ quá -> chuyển sang tính theo giây
				speedNext:
					cf_slider_details_play < 60
						? cf_slider_details_play * 1000
						: cf_slider_details_play,

				sliderArrow: true,
				//			sliderArrowWidthLeft : '40%',
				sliderArrowWidthLeft: "",
				//			sliderArrowWidthRight : '60%',
				sliderArrowWidthRight: "",
				sliderArrowLeft: "fa-chevron-circle-left",
				sliderArrowRight: "fa-chevron-circle-right",

				//			thumbnail : 'ul li',
				thumbnail: WGR_check_option_on(cf_details_show_list_thumb)
					? "ul li"
					: false,
				size: jQuery(".thread-details-mobileAvt").attr("data-size") || "",
			},
			function () {
				WGR_after_load_details_lisder();
			}
		);
	} else {
		console.log("thread-details-mobileAvt run by wiget: zEchBay run slider");
	}

	/*
    jQuery(".jEBE_slider-toRight").on("swipeleft", function() {
        console.log('to left');
    });
    */
}

// hẹn giờ cho các deal
function ___eb_details_countdown() {
	let a = jQuery(".thread-details-countdown").attr("data-timeend") || 0;
	if (a == 0 || a == "0") {
	} else {
		jQuery(".thread-details-countdown").show();
	}
}

// tạo style cho phần tóm tắt, từ dạng không html sang có html
function ___eb_details_ul_options() {
	// tắt chế độ tạo style cho phần excerpt nếu option này đang được tắt
	if (!WGR_check_option_on(cf_details_ul_options)) {
		if (WGR_check_option_on(cf_tester_mode))
			console.log("___eb_details_ul_options disable");
		return false;
	}
	if (WGR_check_option_on(cf_tester_mode))
		console.log("___eb_details_ul_options is running...");

	// chặn -> không cho chạy lại lần nữa
	cf_details_ul_options = "off";

	//
	let a =
		jQuery(".thread-details-options tbody").html() ||
		jQuery(".thread-details-options").html() ||
		"";
	//console.log(a);
	if (a != "") {
		// thay TR -> LI
		a = a
			.replace(/\<tr\s/gi, "<li ")
			.replace(/\<tr\>/gi, "<li>")
			.replace(/\<\/tr\>/gi, "</li>");

		// TD -> DIV
		//	a = a.replace( /\<td\s/gi, '<div ' ).replace( /\<td\>/gi, '<div>' ).replace( /\<\/td\>/gi, '</div>' );

		//
		//		console.log(a);
		jQuery(".thread-details-options")
			.hide()
			.after('<ul class="thread-details2-options">' + a + "</ul>");

		// xóa bỏ TD -> chỉ giữ lại DIV
		jQuery(".thread-details2-options td")
			.each(function () {
				jQuery(this).after(jQuery(this).html());
			})
			.remove();
	}
}

function ___eb_details_excerpt_html(a_before, a_after) {
	// tắt chế độ tạo style cho phần excerpt nếu option này đang được tắt
	if (!WGR_check_option_on(cf_details_excerpt)) {
		if (WGR_check_option_on(cf_tester_mode))
			console.log("___eb_details_excerpt_html disable");
		return false;
	}
	// chặn -> không cho chạy lại lần nữa
	cf_details_excerpt = "off";

	//
	let a =
			jQuery(".thread-details-comment .blog-details-excerpt").text() ||
			jQuery(".thread-details-comment").text() ||
			"",
		str = "";
	a = $.trim(a);

	// Bỏ qua nếu không tìm thấy CSS hoặc dữ liệu bị trống
	if (a == "") {
		if (WGR_check_option_on(cf_tester_mode))
			console.log("thread-details-comment is NULL");
		return false;
	}

	// với mobile thì bỏ qua -> do lỗi mất HTML chưa rõ nguyên nhân
	if ($(window).width() < 768) {
		if (WGR_check_option_on(cf_tester_mode))
			console.log("___eb_details_excerpt_html not run in mobile");
		//$('.thread-details-comment').removeClass('graycolor').html(a.replace(/\n/gi, '<br>'));
		//return false;
	}
	if (WGR_check_option_on(cf_tester_mode))
		console.log("___eb_details_excerpt_html is running...");

	// Tách lấy từng dòng -> để tạo style cho thống nhất
	a = a.split("\n");

	// tạo dưới dạng bảng -> cho vào bảng post options luôn
	if (WGR_check_option_on(cf_options_excerpt)) {
		let str_table = "";
		for (let i = 0; i < a.length; i++) {
			a[i] = g_func.trim(a[i]);

			if (a[i] != "") {
				let a_bold = a[i].split(":"),
					str_tr = "";

				// in đậm đề mục
				if (WGR_check_option_on(cf_details_bold_excerpt)) {
					a_bold[0] = "<strong>" + $.trim(a_bold[0]) + "</strong>";
				}

				for (let j = 0; j < a_bold.length; j++) {
					if (jQuery.trim(a_bold[j]) != "") {
						a_bold[j] = "<td><div>" + a_bold[j] + "</div></td>";
						str_tr += a_bold[j];
					}
				}

				//
				//jQuery('.thread-details-options').append('<tr>' + a_bold.join(' ') + '</tr>');
				str_table += "<tr>" + str_tr + "</tr>";
			}
		}
		jQuery(".thread-details-options").append(str_table);

		//
		jQuery(".thread-details-comment").hide();

		//
		return false;
	}

	// 1 dòng thì cũng bỏ qua luôn
	if (a.length <= 1) {
		if (WGR_check_option_on(cf_tester_mode))
			console.log("thread-details-comment is one line");
		return false;
	}

	// dữ liệu phụ họa
	if (typeof a_before == "undefined") {
		a_before = "";
	}

	if (typeof a_after == "undefined") {
		a_after = "";
	}
	//	console.log( WGR_check_option_on( cf_options_excerpt ) );

	// bắt đầu tạo style
	for (let i = 0; i < a.length; i++) {
		a[i] = g_func.trim(a[i]);

		if (a[i] != "") {
			// Tạo LI thông thường
			// in đậm đề mục
			if (WGR_check_option_on(cf_details_bold_excerpt)) {
				let a_bold = a[i].split(":");
				a_bold[0] = "<strong>" + a_bold[0] + "</strong>";

				a[i] = a_bold.join(":");
			}

			str += "<li>" + a_before + a[i] + a_after + "</li>";
		}
	}

	// In ra dữ liệu mới
	if (str != "") {
		jQuery(".thread-details-comment")
			.show()
			.html("<ul>" + str + "</ul>");
	}
}

// tạo số liệu rating ảo
function ___eb_details_product_rating() {
	let a = jQuery(".each-to-rating").attr("data-rating") || 0;
	//	console.log(a);
	a = (a * 10) / 10;
	//	console.log(a);

	let str = "";
	for (let i = 0; i < 5; i++) {
		//		console.log( i );

		if (i < a) {
			if (i + 0.5 == a) {
				str +=
					'<i data-start="' +
					(i + 1) +
					'" class="fa fa-star-half-o orgcolor cur"></i> ';
			} else {
				str +=
					'<i data-start="' +
					(i + 1) +
					'" class="fa fa-star orgcolor cur"></i> ';
			}
		} else {
			str += '<i data-start="' + (i + 1) + '" class="fa fa-star-o cur"></i> ';
		}
	}
	jQuery(".each-to-rating").html(str);
	jQuery(".each-to-rating i").click(function () {
		console.log("Thank for rating...");
	});
}

// hiệu ứng với các tab nội dung
function ___eb_details_product_tab() {
	//
	jQuery(".thread-details-tab li").click(function () {
		jQuery(".thread-details-tab li").removeClass("selected");
		jQuery(this).addClass("selected");

		let a = jQuery(this).attr("data-show") || "";
		//		console.log(a);

		if (a != "") {
			jQuery(".thread-details-contenttab").hide();

			jQuery("." + a).show();

			if (a == "thread-details-tab-comment") {
				jQuery(".hide-if-show-comment").hide();
			} else {
				jQuery(".hide-if-show-comment").show();
				_global_js_eb.auto_margin();
			}
		}

		//
		//		_global_js_eb.auto_margin();
	});

	//
	//	jQuery(document).ready(function(e) {
	jQuery(".thread-details-tab li:first").click();
	//	});

	//
	setTimeout(function () {
		// định vị lại style cho bản PC
		if (g_func.mb_v2() == false) {
			WGR_fixed_tab_height_in_line(".thread-details-tab", "li");
		}
	}, 600);
}

// giá theo từng màu hoặc size (ưu tiên size)
function WGR_show_price_for_size_color(gia) {
	// nếu không xác định được giá thì bỏ luôn -> chức năng này để xác định lại giá
	if (typeof gia == "undefined" || gia == "" || gia <= 0) {
		return false;
	}

	// add giá theo bản mới -> web nào update mới có tính năng này
	if (jQuery(".show-size-color-price").length > 0) {
		jQuery(".show-size-color-price .ebe-currency").html(
			g_func.money_format(gia)
		);

		// tính bước giá đã thay đổi của giá mới
		let buoc_gia =
				jQuery(".show-size-color-price .ebe-currency").attr("data-num") || "",
			gia_cu =
				jQuery(".set-size-color-price .ebe-currency").attr("data-num") || "",
			//			giam_gia = jQuery('.thread-details-data-gia').attr('data-gia') || '',
			giam_gia = "",
			set_gia = 0,
			// tỉ lệ thay đổi giá của giá mới -> giá cũ cũng giảm tương tự
			ti_le = 0;
		//		console.log(buoc_gia);
		//		console.log(gia_cu);
		//		console.log(giam_gia);

		// nếu tìm và xác định được giá cũ thì mới tiếp tục
		if (gia_cu != "" && gia_cu > 0 && buoc_gia != "" && buoc_gia > 0) {
			// nếu tỉ lệ không đổi -> không cần tính toán nữa
			if (buoc_gia == gia) {
				jQuery(".set-size-color-price .ebe-currency").html(
					g_func.money_format(gia_cu)
				);
			}
			// khi giá có thay đổi -> tính toán lại
			else {
				// tính theo % giảm giá đang bị sai -> tính theo bước giá
				//				giam_gia = 100 - (buoc_gia * 100 / gia_cu);
				//				console.log(giam_gia);

				// tính theo % có sẵn -> giá cũ luôn luôn hơn giá mới theo % nhất định
				if (giam_gia != "" && giam_gia > 0) {
					// ép về kiểu số mới thực hiện pháp cộng được
					set_gia = 0 - (0 - gia) + (gia / 100) * giam_gia;
					// với giá $ thì chưa test -> nên vẫn dùng phương thức trên
					//					set_gia = Number( gia ) + ( gia/ 100 * giam_gia );
				}
				// không có % thì tính theo bước giá đã thay đổi
				else {
					ti_le = Math.ceil((gia * 100) / buoc_gia);
					//					console.log(ti_le);

					// tính lại giá cũ theo tỉ lệ vừa tính được
					set_gia = Math.ceil((gia_cu / 100) * ti_le);
				}

				// hiển thị lại giá cũ theo tỉ lệ đã tìm được
				jQuery(".set-size-color-price .ebe-currency").html(
					g_func.money_format(set_gia)
				);
			}
		}
	} else {
		console.log(
			"Update new version or add class show-size-color-price for show child product price"
		);
	}
}

// kiểm tra số lượng sản phẩm trong kho -> chuẩn bị cho việc kết nối với kho woo
function WGR_check_product_color_with_inventory() {
	let str = "";

	jQuery(".oi_product_color li").each(function () {
		let a = jQuery(this).attr("data-sku") || "";

		if (a != "") {
			str += "," + a;
		}
	});

	if (str != "") {
		//
		str = "https://test.com/wgr_inventory/?skus=" + str;
		console.log(str);
	}
}

// màu sắc sản phẩm
// hiển thị tên màu trực tiếp nếu không có màu nào được chỉ định
function WGR_show_product_color_name() {
	//	console.log(product_color_name);

	// nếu có tên màu sắc -> hiển thị tên màu ra ngoài cho dễ nhìn
	if (product_color_name != "") {
		jQuery(".show-if-color-exist").show();

		// lấy hình ảnh nếu có
		let product_img =
			jQuery('meta[itemprop="image"]').attr("content") ||
			jQuery('meta[itemprop="og:image"]').attr("content") ||
			"";

		//
		let str = "";

		// nếu có hình ảnh -> thêm hình ảnh vào phần size
		if (product_img != "") {
			str +=
				'<li title="' +
				product_color_name +
				'" data-img="' +
				product_img +
				'" data-node="0" data-sku="' +
				product_js.sku +
				'" class="selected" style="background-image:url(' +
				product_img +
				');">&nbsp;<div>' +
				product_color_name +
				"</div></li>";

			jQuery(".oi_product_color ul").after(
				'<div class="show-products-color-text l19 small">&nbsp;</div>'
			);
		}
		// nếu không, chỉ hiển thị mỗi tên
		else {
			str =
				'<li data-sku="' +
				product_js.sku +
				'" class="text-center text-color-center">' +
				product_color_name +
				"</li>";
		}
		jQuery(".oi_product_color ul").html(str);

		//
		WGR_check_product_color_with_inventory();
	}

	return false;
}

function ___eb_details_product_color() {
	//
	if (jQuery("#export_img_list_color img").length == 0) {
		return WGR_show_product_color_name();
	}

	//
	let str = "",
		i = 0,
		select_default_color = null;
	jQuery("#export_img_list_color img").each(function () {
		let s = jQuery(this).attr("data-src") || jQuery(this).attr("src") || "";

		if (s != "") {
			// trạng thái
			let status = jQuery(this).attr("data-status") || 1,
				img_fullsize = ___eb_set_thumb_to_fullsize(s);
			//			console.log(status);

			if (status > 0) {
				let color_name =
						jQuery(this).attr("alt") ||
						jQuery(this).attr("title") ||
						jQuery(this).attr("data-color") ||
						"",
					color_quan = jQuery(this).attr("data-quan") || "",
					color_price = jQuery(this).attr("data-price") || "",
					color_size = jQuery(this).attr("data-size") || "",
					color_color = jQuery(this).attr("data-color") || "",
					color_sku = jQuery(this).attr("data-sku") || "",
					in_sctock = "",
					bg = "",
					cl = "";
				//				console.log(color_quan);

				// Để trống -> coi như còn hàng
				if (color_quan == "") {
					color_quan = 1;
				} else {
					color_quan *= 1;
				}
				//				console.log(color_quan);
				if (WGR_check_option_on(cf_tester_mode))
					console.log("color_quan: " + color_quan);

				// còn hàng thì mới hiển thị
				if (color_quan >= 0) {
					if (color_quan == 0) {
						in_sctock = " (Hết hàng)";
					} else if (select_default_color == null) {
						select_default_color = i;
					}

					//
					if (color_color != "") {
						if (color_color.substr(0, 1) == "#") {
							color_color = color_color.substr(1);
						}
						if (color_color.length == 3 || color_color.length == 6) {
							bg = "background-color:#" + color_color;
							cl = "color-color";
						}
					}
					if (bg == "") {
						bg = "background-image:url(" + ___eb_set_img_to_thumbnail(s) + ")";
						cl = "";
					}

					//
					str +=
						'<li title="' +
						color_name +
						in_sctock +
						'" data-img="' +
						img_fullsize +
						'" data-node="' +
						i +
						'" data-quan="' +
						color_quan +
						'" data-price="' +
						color_price +
						'" data-size="' +
						color_size +
						'" data-color="' +
						color_color +
						'" data-sku="' +
						color_sku +
						'" class="' +
						cl +
						'" style="' +
						bg +
						';">&nbsp;<div>' +
						color_name +
						in_sctock +
						"</div></li>";

					arr_product_color.push(img_fullsize);

					i++;
				}
			}
		}
	});

	// nếu ít hơn 1 màu -> hủy bỏ
	//	console.log(i);
	if (i < 2) {
		WGR_show_product_color_name();

		arr_product_color = [];

		return false;
	}
	//	console.log(arr_product_color);

	//
	jQuery(".show-if-color-exist").show();
	jQuery(".oi_product_color ul")
		.html(str)
		.after('<div class="show-products-color-text l19 small"></div>');

	//
	WGR_check_product_color_with_inventory();

	//
	jQuery(".oi_product_color li")
		.off("click")
		.click(function () {
			// ẩn câu thông báo mỗi lần ấn
			jQuery("#WGR_html_alert").hide();

			//
			jQuery(".oi_product_color li").removeClass("selected");
			//		jQuery(this).addClass('selected');

			// Lấy tên màu
			let color_name = jQuery(this).attr("title") || "",
				color_img = jQuery(this).attr("data-img") || "",
				color_color = jQuery(this).attr("data-color") || "",
				color_price = jQuery(this).attr("data-price") || "",
				color_size = jQuery(this).attr("data-size") || "",
				color_node = jQuery(this).attr("data-node") || 0;

			// đổi ảnh đại diện khi click đổi màu
			if (
				color_color != "" &&
				(color_color.length == 3 || color_color.length == 6)
			) {
			} else if (WGR_check_option_on(cf_show_img_color)) {
				jQuery(".thread-details-mobileAvt li").css({
					"background-image": "url(" + color_img + ")",
				});
			}

			//
			jQuery('.oi_product_color li[data-node="' + color_node + '"]').addClass(
				"selected"
			);

			// Hiển thị ra cho người dùng xem
			//		jQuery('.show-products-color-text').html(color_name);

			// đổi tên sản phẩm theo màu sắc
			WGR_show_product_name_and_color(color_name);

			// gán size riêng theo mỗi màu nếu có
			size_rieng_cua_tung_mau = color_size;
			price_by_color = color_price;

			//
			if (typeof document.frm_cart != "undefined") {
				if (color_name == "") {
					color_name = "Unknown";
				}

				/*
            if ( color_img != '' ) {
                color_img = ' <img src="' + color_img + '" height="50" />';
            }
            */

				//			jQuery('.eb-global-frm-cart input[name^=t_color]').val( color_name + color_img );
				jQuery(".eb-global-frm-cart input[name^=t_color]").val(color_name);

				//
				let select_quan =
						jQuery("#oi_change_soluong input").val() ||
						jQuery("#oi_change_soluong select").val() ||
						0,
					show_price = 0;

				// hiển thị giá riêng của từng mầu nếu có
				if (color_price != "" && color_price > 0) {
					show_price = color_price;
					price_for_quick_cart = color_price;
					if (WGR_check_option_on(cf_tester_mode)) console.log("price_color");
				}
				// lấy giá mặc định
				else {
					show_price = product_js.gm;
					price_for_quick_cart = 0;
					if (WGR_check_option_on(cf_tester_mode)) console.log("price_default");
				}
				//			console.log('COLOR');
				let total_new_price = WGR_zero_price_quick_cart(
					select_quan,
					show_price
				);

				// hiển thị lại giá của chi tiết sản phẩm
				WGR_show_price_for_size_color(show_price);

				//
				_global_js_eb.cart_create_arr_poruduct(total_new_price);
			} else {
				console.log("frm_cart not found");
			}

			// load lại size để điều chỉnh giá theo size -> phủ định cái giá riêng của màu ở trên
			size_has_been_load = false;
			___eb_details_product_size();
		});

	//
	jQuery('.oi_product_color li[data-quan="0"]')
		.off("click")
		.click(function () {
			a_lert("Xin lỗi quý khách! Sản phẩm này tạm thời đang cháy hàng...");
			return false;
		});

	//
	//	console.log('select_default_color: ' + select_default_color);
	if (select_default_color != null) {
		//jQuery('.oi_product_color:first li:first').click();
		jQuery(
			'.oi_product_color:first li[data-node="' + select_default_color + '"]'
		).click();

		// nếu không có size theo màu và không có size của sản phẩm chính -> hẹn giờ lấy giá của màu đầu tiên (nếu có)
		// nếu có thì đã có 1 lệnh sau đó tìm và lấy giá theo size rồi -> lệnh đó sẽ phủ nhận cái lệnh này đi nếu nó vẫn diễn ra
		if (size_rieng_cua_tung_mau == "" && arr_product_size.length == 0) {
			setTimeout(function () {
				console.log("load price in color");
				//jQuery('.oi_product_color:first li:first').click();
				jQuery(
					'.oi_product_color:first li[data-node="' + select_default_color + '"]'
				).click();
			}, 300);
		}
	}

	//
	/*
    if ( jQuery('.oi_product_color').length > 1 ) {
        jQuery('.oi_product_color').each(function() {
            jQuery('li:first', this).addClass('selected');
        });
    }
    */
}

function WGR_show_product_name_and_color(color_name) {
	// tạo thêm một dòng phụ bên dưới phần màu sắc để tạo độ dãn dòng
	jQuery(".show-products-color-text").html("&nbsp;");

	// bắt đầu hiển thị thêm tên màu vào tiêu đề
	if (typeof color_name != "string") {
		color_name = "";
	}

	let product_name = product_js.tieude;

	if (color_name != "") {
		product_name += " - (" + color_name + ")";
	}

	// hiển thị tên theo từng vị trí cụ thể
	if (jQuery(".thread-details-title a").length > 0) {
		jQuery(".thread-details-title a").html(product_name);
	} else if (jQuery(".thread-details-title").length > 0) {
		jQuery(".thread-details-title").html(product_name);
	} else if (jQuery("h1 a").length > 0) {
		jQuery("h1 a").html(product_name);
	} else if (jQuery("h1").length > 0) {
		jQuery("h1").html(product_name);
	}
}

// size sản phẩm
function ___eb_details_convert_product_size() {
	// convert mảng size sang kiểu dữ liệu mới
	if (typeof arr_product_size != "object") {
		if (arr_product_size == "") {
			arr_product_size = [];
		} else {
			let a = arr_product_size.slice();
			a = unescape(a);
			//			console.log(a);

			//
			if (a.substr(0, 1) == ",") {
				a = a.substr(1);
			}
			if (a.substr(0, 1) != "[") {
				a = "[" + a + "]";
			}

			// convert to array
			//			a = JSON.parse( a );
			//			a = jQuery.parseJSON( a );
			try {
				a = eval(a);
			} catch (e) {
				a = [];
				console.log(WGR_show_try_catch_err(e));
			}
			//			console.log( JSON.stringify( a ) );

			// gán lại mảng size từ mảng a0 nếu chưa đúng
			if (typeof a[0] != "undefined" && typeof a[0].name == "undefined") {
				a = a[0];
			}
			arr_product_size = a;
		}
	}
	//	console.log( JSON.stringify( arr_product_size ) );
	//	console.log( arr_product_size );
}

function ___eb_details_product_size() {
	//
	if (size_has_been_load == true) {
		console.log("size_has_been_load");
		return false;
	}
	size_has_been_load = true;

	//
	___eb_details_convert_product_size();

	// mặc định sẽ dùng mảng size chính
	let sa = arr_product_size.slice();
	// nếu có mảng giá trị truyền vào từ màu -> sử dụng màu này
	if (size_rieng_cua_tung_mau != "") {
		try {
			sa = eval(unescape(size_rieng_cua_tung_mau));
		} catch (e) {
			sa = [];
			console.log(WGR_show_try_catch_err(e));
		}
	}

	// có 1 size thì bỏ qua, mặc định rồi
	//	if ( sa.length <= 1 || jQuery('.oi_product_size').length == 0 ) {
	// có 1 size cũng hiển thị, mặc định select cái size đấy cho khách là được
	if (sa.length < 1 || jQuery(".oi_product_size").length == 0) {
		return false;
	}
	if (WGR_check_option_on(cf_tester_mode)) console.log(sa);

	// có nhiều size thì tạo list
	let str = "",
		select_default_size = null;

	for (let i = 0; i < sa.length; i++) {
		// conver từ bản code cũ sang
		if (typeof sa[i].name == "undefined") {
			if (typeof sa[i].ten != "undefined") {
				sa[i].name = sa[i].ten;
			} else {
				sa[i].name = "";
			}
		}

		if (typeof sa[i].val == "undefined") {
			if (typeof sa[i].soluong != "undefined") {
				sa[i].val = sa[i].soluong;
			} else {
				sa[i].val = 0;
			}
		} else if (sa[i].val == "") {
			sa[i].val = 0;
		}

		if (typeof sa[i].price == "undefined" || sa[i].price == "") {
			sa[i].price = 0;
		}

		// Giá trị mảng phải khác null -> null = xóa
		if (sa[i].val != null && sa[i].val >= 0) {
			// chọn size nếu còn hàng
			if (select_default_size == null && sa[i].val > 0) {
				select_default_size = i;
			}

			// Tên và Số lượng phải tồn tại
			//			if ( sa[i].val != '' && sa[i].name != '' ) {
			if (sa[i].name != "") {
				let str_alert = "",
					str_title = "";
				if (sa[i].val > 0) {
					if (sa[i].val < 5) {
						str_title = "C\u00f2n " + sa[i].val + " s\u1ea3n ph\u1ea9m";
						str_alert = '<span class="bluecolor">' + str_title + "</span>";
					} else {
						str_title = "S\u1eb5n h\u00e0ng";
						str_alert = '<span class="greencolor">' + str_title + "</span>";
					}
				} else {
					str_title = "H\u1ebft h\u00e0ng";
					str_alert = '<span class="redcolor">' + str_title + "</span>";
				}

				//
				str +=
					'<li title="' +
					str_title +
					'" data-size-node="' +
					i +
					'" data-name="' +
					sa[i].name +
					'" data-quan="' +
					sa[i].val +
					'" data-price="' +
					sa[i].price +
					'"><div>' +
					sa[i].name +
					"</div>" +
					str_alert +
					"</li>";
			}
		}
	}
	if (str == "") {
		arr_product_size = [];
		return false;
	}

	jQuery(".oi_product_size, .show-if-size-exist").show();
	jQuery(".oi_product_size ul")
		.html(str)
		.after('<div class="show-products-size-text l19 small"></div>');

	jQuery(".oi_product_size li")
		.off("click")
		.click(function () {
			// ẩn câu thông báo mỗi lần ấn
			jQuery("#WGR_html_alert").hide();

			//
			let size_node = jQuery(this).attr("data-size-node") || "";

			if (size_node == "") {
				return false;
			}
			//		console.log(size_node);

			jQuery(".oi_product_size li.selected").removeClass("selected");
			//		jQuery(this).addClass('selected');
			jQuery(
				'.oi_product_size li[data-size-node="' + size_node + '"]'
			).addClass("selected");

			let curent_soluong = jQuery(this).attr("data-quan") || 0,
				size_price = jQuery(this).attr("data-price") || "";
			curent_soluong = 0 - curent_soluong;
			curent_soluong = 0 - curent_soluong;

			let str_alert = "";
			if (curent_soluong > 0) {
				if (curent_soluong < 5) {
					str_alert =
						'<span class="bluecolor">C\u00f2n ' +
						curent_soluong +
						" s\u1ea3n ph\u1ea9m</span>";
				} else {
					str_alert = '<span class="greencolor">S\u1eb5n h\u00e0ng</span>';
				}
			} else {
				str_alert = '<span class="redcolor">H\u1ebft h\u00e0ng</span>';
				jQuery(".show-if-user-size").show();
			}
			jQuery(".oi_product_size .product-size-soluong > span").html(str_alert);
			//		jQuery('.oi_product_size .product-size-soluong').show();

			if (typeof document.frm_cart != "undefined") {
				//			jQuery('#click_show_cpa input[name^="t_size"]').val( jQuery(this).attr('data-name') || '' );
				//			jQuery('#click_show_cpa input[name="t_size[]"]').val( jQuery(this).attr('data-name') || '' );
				jQuery(".eb-global-frm-cart input[name^=t_size]").val(
					jQuery(this).attr("data-name") || ""
				);
				//			document.frm_cart.t_size.value = jQuery(this).attr('data-id') || '';

				//
				let select_quan =
						jQuery("#oi_change_soluong input").val() ||
						jQuery("#oi_change_soluong select").val() ||
						0,
					show_price = 0;

				// nếu có giá theo size -> lấy giá theo size
				if (size_price != "" && size_price > 0) {
					show_price = size_price;
					price_for_quick_cart = size_price;
					if (WGR_check_option_on(cf_tester_mode)) console.log("price_by_size");
				}
				// nếu có giá theo mầu vừa click -> lấy giá theo mầu
				else if (price_by_color != "" && price_by_color > 0) {
					show_price = price_by_color;
					price_for_quick_cart = price_by_color;
					if (WGR_check_option_on(cf_tester_mode))
						console.log("price_by_color");
				}
				// mặc định thì lấy giá của size chính
				else {
					show_price = product_js.gm;
					price_for_quick_cart = 0;
					if (WGR_check_option_on(cf_tester_mode)) console.log("price_default");
				}
				//			console.log('SIZE');
				let total_new_price = WGR_zero_price_quick_cart(
					select_quan,
					show_price
				);

				// hiển thị lại giá của chi tiết sản phẩm
				WGR_show_price_for_size_color(show_price);

				//
				_global_js_eb.cart_create_arr_poruduct(total_new_price);
			} else {
				console.log("frm_cart not found");
			}
		});

	// với các size hết hàng -> hủy chọn
	jQuery('.oi_product_size li[data-quan="0"]')
		.off("click")
		.click(function () {
			a_lert("Xin lỗi quý khách! Sản phẩm này tạm thời đang cháy hàng...");
			return false;
		});

	//
	//	jQuery('.oi_product_size li:first').click();
	// nếu còn 1 size nào đó còn hàng -> chọn sẵn cho khách
	if (select_default_size != null) {
		jQuery(
			'.oi_product_size:first li[data-size-node="' + select_default_size + '"]'
		).click();
		setTimeout(function () {
			if (WGR_check_option_on(cf_tester_mode))
				console.log("select_default_size: " + select_default_size);
			jQuery(
				'.oi_product_size:first li[data-size-node="' +
					select_default_size +
					'"]'
			).click();
		}, 500);
	}
}

// tạo html cho khung đếm số phiếu mua hàng
function ___eb_details_product_quan() {
	//	if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('___eb_details_product_quan');

	//
	if (jQuery("#oi_mua_max").length == 0) {
		return false;
	}

	//
	let a = jQuery("#oi_mua_max").attr("data-min") || "",
		b = jQuery("#oi_mua_max").attr("data-max") || "",
		c = 0;
	if (a != "" && b != "") {
		c = (a * 100) / b;
		if (c > 100) {
			c = 100;
		} else if (c < 0) {
			c = 0;
		}
		jQuery("#oi_mua_max").width(c + "%");
		jQuery("#oi_con_phieu").html(b - a);
	}
}

//
function ___eb_details_post_run(r) {
	if (khong_chay_function_o_theme_nua == 1) {
		console.log(
			"WARNING!!!!!!!!!!!!!!!!!!! function ___eb_details_post_run has been runing..."
		);
		return false;
	}
	khong_chay_function_o_theme_nua = 1;

	// với bản pc -> chỉnh lại kích thước ảnh thành fullsize (mặc định trước đó trong admind dể mobile hết)
	/*
    if ( global_window_width > 768 ) {
        jQuery('#content_img_product img, .max-width-img-content img, .echbay-tintuc-noidung img').removeAttr('sizes');
        console.log('Set img fullsize for mobile');
    }
    */

	// chạy function riêng (nếu có)
	/*
    if ( typeof r == 'function' ) {
        r();
    }
    */
	if (typeof Child_eb_details_post_run == "function") {
		Child_eb_details_post_run();
	}

	/*
	 * và function chung mà phần lớn theme đều cần đến
	 */

	// slider cho trang chi tiết
	___eb_details_slider_v2();

	// tạo style cho phần tóm tắt
	___eb_details_excerpt_html();
	___eb_details_ul_options();

	//
	___eb_details_product_tab();

	// tạo bộ đếm lượt mua
	___eb_details_product_quan();

	// mặc định form quick cart nằm cuối trang
	//	jQuery('form[name^=frm_cart]').addClass('eb-global-frm-cart');

	// nếu người dùng không kích hoạt quick cart -> kiểm tra nếu trên bản mobile thì hiển thị cái này ra
	//	console.log(arr_detect_browser);
	//	alert(arr_detect_browser);
	if (
		jQuery(".clone-show-mobile-quick-cart").length > 0 &&
		arr_detect_browser == "fb" &&
		// site mevutu ko muốn hiển thị quick cart trên cả fb
		typeof disable_fb_quick_cart == "undefined"
	) {
		console.log("Show quick cart for Facebook browser");
		jQuery(".clone-show-mobile-quick-cart").addClass("clone-show-quick-cart");
	}

	// nếu có lệnh chuyển quick cart xuống cuối trang
	if (WGR_check_option_on(cf_details_bottom_quick_cart)) {
		// xóa class này ở những mục khác
		jQuery(".clone-show-quick-cart").remove();
		// sau đó thêm mới ở dưới cùng bài viết
		jQuery(".thread-details-contenttab").after(
			'<div class="clone-show-quick-cart"></div>'
		);
	}

	// -> một số theme nào cần hiển thị thì tạo kiểm tra class và đưa lên
	if (jQuery(".clone-show-quick-cart").length > 0) {
		jQuery(".clone-show-quick-cart").html(
			jQuery("#click_show_cpa .cart-quick-padding").html()
		);

		// xong thì xóa cái quick cart, size, color mặc định đi
		if (WGR_check_option_on(cf_donot_remove_quick_cart)) {
			jQuery(".donot-remove-if-quickcart").removeClass(
				"remove-if-clone-quickcart"
			);
		}
		jQuery("#click_show_cpa, .remove-if-clone-quickcart").remove();

		//		jQuery('.clone-show-quick-cart input[name^="t_muangay"]').val( pid );
		//		jQuery('.clone-show-quick-cart input[name="t_muangay[]"]').val( pid );
	}
	// nạp ID cho phần quick cart
	//	else {
	// nạp ID cho phần quick cart
	//		jQuery('#click_show_cpa input[name^="t_muangay"]').val( pid );
	//		jQuery('#click_show_cpa input[name="t_muangay[]"]').val( pid );
	//	}
	jQuery(".eb-global-frm-cart input[name^=t_muangay]").val(pid);

	// hiển thị hộp nhập mã giảm giá nếu có
	_global_js_eb.cart_discount_code(co_ma_giam_gia, ".eb-quickcart-coupon");

	if (cart_shipping_content != "") {
		jQuery(".eb-quickcart-shipping_fee").show();
	}

	// color
	___eb_details_product_color();

	// size
	___eb_details_product_size();

	// selext box số lượng sản phẩm khi mua hàng
	___eb_details_cart_quan();

	// nạp thông tin khách hàng (nếu có)
	_global_js_eb.cart_customer_cache();

	// facebook track
	let track_arr = {
		content_ids: [pid],
		content_name: product_js.tieude,
	};
	if (typeof product_js.gm == "number" && product_js.gm > 0) {
		track_arr.value = product_js.gm;
		//		track_arr.currency = 'VND';
		track_arr.currency = cf_current_sd_price;
	}
	_global_js_eb.fb_track("ViewContent", track_arr);

	//
	/*
    let arr_attr_img_content = [],
        i = 0;
    jQuery('.thread-details-tab-content img').each(function(index, element) {
        let arr = {};
    	
        jQuery(this).each(function() {
            jQuery.each(this.attributes, function() {
                // this.attributes is not a plain object, but an array
                // of attribute nodes, which contain both the name and value
                if(this.specified) {
//					console.log(this.name, this.value);
                	
                    arr[this.name] = this.value;
                }
            });
        });
    	
        //
        arr_attr_img_content[i] = arr;
        i++;
    });
    console.log( arr_attr_img_content );
    */

	// đếm thời gian hiển thị
	//	console.log(trv_ngayhethan);
	if (trv_ngayhethan > 0) {
		WGR_check_and_begin_load_count_down();
	}

	// hiển thị con dấu hàng chính hãng
	if (WGR_check_option_on(_eb_product_chinhhang)) {
		if (WGR_check_option_on(cf_tester_mode)) console.log("Hàng chính hãng");

		//
		if (jQuery(".show-tem-chinh-hang").length > 0) {
			jQuery(".show-tem-chinh-hang").addClass("tem-chinh-hang").html("&nbsp;");
		} else {
			//			jQuery('.pdetail-slider-btn').after('<div class="tem-chinh-hang">&nbsp;</div>');
			jQuery(".thread-details-mobileCenter").after(
				'<div class="tem-chinh-hang">&nbsp;</div>'
			);
		}
	}

	// tạo hiệu ứng thu gọn nội dung -> bấm xem thêm để hiển thị đầy đủ
	(function () {
		// thiết lập bằng 0 -> tắt chức năng
		//		if ( cf_product_details_viewmore == 0 ) {
		if (cf_product_details_viewmore < 90) {
			return false;
		}

		// nếu mở trong iframe -> không dùng chức năng thu gọn nội dung
		if (top != self) {
			if (WGR_check_option_on(cf_tester_mode))
				console.log("Short post content is active! but post open in iframe!");
			return false;
		}

		let a = null;

		// chỉnh theo phần mặt nạ của nội dung
		if (jQuery(".thread-content-bmask").length > 0) {
			a = jQuery(".thread-content-bmask");
		}
		// mặc định là can thiệp vào nội dung luôn
		else if (jQuery("#content_img_product").length > 0) {
			a = jQuery("#content_img_product");
		} else {
			console.log("thread-content-bmask or content_img_product not found!");
		}

		if (a != null) {
			// nếu tính năng được kích hoạt, nhưng chiều cao không đủ
			if (a.height() < cf_product_details_viewmore * 1.5) {
				if (WGR_check_option_on(cf_tester_mode))
					console.log(
						"cf_product_details_viewmore it active! but height of content not enough"
					);
				return false;
			}

			//
			a.addClass("thread-content-viewmore")
				.height(cf_product_details_viewmore)
				.after(
					'<br /><div class="text-center"><a href="javascript:;" class="click-viewmore-thread-details">Xem thêm</a></div>'
				);

			//
			jQuery(".click-viewmore-thread-details").click(function () {
				jQuery(".thread-content-bmask, #content_img_product")
					.height("auto")
					.removeClass("thread-content-viewmore");

				jQuery(".click-viewmore-thread-details").hide();

				let new_scroll =
					jQuery("#content_img_product").offset().top ||
					jQuery(".thread-content-bmask").offset().top ||
					0;

				if (new_scroll > 0) {
					window.scroll(0, new_scroll - 110);
				}
			});
		}
	})();

	//
	___wgr_set_product_id_cookie();

	// thêm chiều rộng tuyệt đối cho khung nội dung
	let a = jQuery("#content_img_product").attr("data-max-width") || "";
	if (a == "" || a == "0" || a == 0) {
		if (cf_img_details_maxwidth > 0) {
			jQuery("#content_img_product").attr({
				"data-max-width": cf_img_details_maxwidth,
			});
		}
	}
}

function WGR_check_and_begin_load_count_down() {
	let id_for_show = "oi_time_line";

	// nếu ko có ID để hiển thị thời gian -> hủy bỏ luôn
	if (dog(id_for_show) == null) {
		if (WGR_check_option_on(cf_tester_mode))
			console.log(
				'Thời gian hết hạn được kích hoạt, nhưng không tìm thấy DIV id="' +
					id_for_show +
					'"'
			);
		return false;
	}

	// nếu có -> hiển thị thời gian
	if (jQuery(".thread-details-countdown-left").length == 0) {
		jQuery("#" + id_for_show).before(
			'<div class="medium l35 thread-details-countdown-left">' +
				lang_details_time_discount +
				"</div>"
		);
	}

	// Nếu trả về false -> khả năng cao là hết hạn hiển thị -> hiển thị thông báo hết hạn
	if (___wgr_dem_thoi_gian_san_pham(trv_ngayhethan - date_time) == false) {
		dog("oi_time_line").innerHTML = lang_details_time_soldout;
		jQuery("#" + id_for_show)
			.removeClass("bold")
			.addClass("product-soldout");
		jQuery(".details-ankhi-hethang").hide();
	}
	// điều chỉnh class theo style riêng
	else {
		jQuery("#" + id_for_show).addClass("global-details-countdown");
	}
}

function ___wgr_dem_thoi_gian_san_pham(thoi_gian_con_lai) {
	// hết hạn hiển thị
	if (thoi_gian_con_lai < 0) {
		console.log("Hết hạn hiển thị");
		return false;
	}

	// hẹn giờ load lại chức năng
	setTimeout(function () {
		___wgr_dem_thoi_gian_san_pham(thoi_gian_con_lai - 1);
	}, 1000);

	// còn hạn hiển thị
	//	console.log(thoi_gian_con_lai);
	//	return false;

	//
	let str = "";

	//
	let so_du = thoi_gian_con_lai % 3600;
	let gio = (thoi_gian_con_lai - so_du) / 3600;
	if (gio < 10) gio = "0" + gio;
	let giay = so_du % 60;
	if (giay < 10) giay = "0" + giay;
	let phut = (so_du - giay) / 60;
	if (phut < 10) phut = "0" + phut;

	let ngay = 0;
	if (gio > 24) {
		ngay = gio;
		gio = gio % 24;
		ngay = (ngay - gio) / 24;

		str += "<span>" + ngay + "<em>ngày</em></span>";
	}

	//
	//	console.log(gio + ':' + phut + ':' + giay);
	dog("oi_time_line").innerHTML =
		str +
		"<span>" +
		gio +
		"<em>giờ</em></span><span>" +
		phut +
		"<em>phút</em></span><span>" +
		giay +
		"<em>giây</em></span>";

	return true;
}
