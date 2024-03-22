/*
 * d function
 */
// tham số để xác nhận file df đã load xong
var confirm_df_loaded = true;

function ___eb_set_thumb_to_fullsize(s) {
	if (typeof s == "undefined" || s == "") {
		return "";
	}
	//	console.log(s);

	//
	if (s.includes(wp_content + "/uploads/") == true) {
		let t = s.split("-");
		t = t[t.length - 1];
		//		console.log( t );

		if (t.split(".").length == 2) {
			t = t.split(".")[0].split("x");

			if (t.length == 2) {
				let re = /^\d+$/;

				// nếu đang là thumbnail hoặc ảnh thu nhỏ -> thì mới cần chuyển sang ảnh to
				if (re.test(t[0]) == true && re.test(t[1]) == true) {
					s = s.replace("-" + t[0] + "x" + t[1] + ".", ".");
				}
			}
		}
	}
	//	console.log(s);

	// bỏ thumb đi
	return s;
}

function WGR_get_thumb_in_srcset(srcset) {
	// tìm thumbnail trong srcset
	srcset = srcset.split(" 150w,");

	// có thì trả về luôn
	if (srcset.length > 1) {
		srcset = srcset[0].split(",");
		return jQuery.trim(srcset[srcset.length - 1]);
	}

	return "";
}

function ___eb_set_img_to_thumbnail(sr, srcset) {
	if (typeof sr == "undefined" || sr == "") {
		return "";
	}
	//	console.log( sr );

	// nếu có tham số này -> site không sử dụng thumb hoặc không có thumb
	// cf_disable_auto_get_thumb
	if (
		typeof eb_disable_auto_get_thumb == "number" &&
		WGR_check_option_on(eb_disable_auto_get_thumb)
	) {
		// ưu tiên lấy theo srcset -> có thumb thì vẫn dùng thumb như thường
		if (typeof srcset != "undefined" && srcset != "") {
			srcset = WGR_get_thumb_in_srcset(srcset);
			if (srcset != "") {
				return srcset;
			}
		}

		//
		if (WGR_check_option_on(cf_tester_mode))
			console.log("Auto get thumbnail disable");
	}
	// lấy thumb để làm ảnh slider -> load cho nhanh
	else if (sr.includes(wp_content + "/uploads/") == true) {
		// ưu tiên lấy theo srcset
		if (typeof srcset != "undefined" && srcset != "") {
			srcset = WGR_get_thumb_in_srcset(srcset);
			if (srcset != "") {
				return srcset;
			}
		}

		// cắt lấy chuỗi cuối cùng của ảnh để kiểm tra xem có phải thumb hay không
		let file_name = sr.split("/");
		file_name = file_name[file_name.length - 1];
		//		console.log( file_name );

		//		let is_thumb = sr.split('/').pop().split('-').pop().split('.')[0];
		let is_thumb = file_name.split("-");
		is_thumb = is_thumb[is_thumb.length - 1];
		//		console.log( is_thumb );

		//
		if (is_thumb.split(".").length == 2) {
			let file_type = file_name.split(".");
			file_type = file_type[file_type.length - 1];
			//			console.log( file_type );

			let thumbnail = "-150x150." + file_type;
			//			console.log( thumbnail );

			is_thumb = is_thumb.split(".")[0];
			//			console.log( is_thumb );

			// có chữ x -> có thể là thumb -> xóa cái đoạn đó đi -> có thể gây lỗi nếu đó là tên file ảnh =))
			if (is_thumb.includes("x") == true) {
				let re = /^\d+$/;
				is_thumb = is_thumb.split("x");

				// nếu đang là thumbnail hoặc ảnh thu nhỏ
				if (
					re.test(is_thumb[is_thumb.length - 2]) == true &&
					re.test(is_thumb[is_thumb.length - 1]) == true
				) {
					//					console.log( is_thumb[ is_thumb.length - 2 ] );
					//					console.log( is_thumb[ is_thumb.length - 1 ] );

					sr = sr.replace(
						"-" +
							is_thumb[is_thumb.length - 2] +
							"x" +
							is_thumb[is_thumb.length - 1] +
							"." +
							file_type,
						thumbnail
					);
				}
				// nếu không phải thumbnail -> tạo thumbnail luôn
				else {
					sr = sr.replace("." + file_type, thumbnail);
				}
			}
			// nếu không có chữ x -> không phải thumb
			else {
				//			if ( is_thumb.split('x').length != 2 ) {
				// -> thêm thumb
				//				let img_type = sr.split('.').pop();

				sr = sr.replace("." + file_type, thumbnail);
			}
		}
	}
	//	console.log( sr );

	return sr;
}

function WGR_fixed_tab_height_in_line(jd, for_tag, max_height) {
	// tab sẽ chịu ảnh hưởng, thường là thẻ A hoặc LI
	if (typeof for_tag == "undefined" || for_tag == "") {
		for_tag = "a";
	}

	// chiều cao dùng để so sánh, chiều cao menu phổ thông là 30px, gấp đôi lên sẽ hơn 40px
	//	if ( typeof max_height == 'undefined' ) {
	//		max_height = 40;
	//	}

	// Chiều cao định vị cho tab
	let min_tab_height = jQuery(jd).attr("data-max-height") || "",
		tag = jQuery(jd).attr("data-tag") || for_tag;

	//
	if (min_tab_height == "") {
		min_tab_height = jQuery(jd + " li:first").height() || "";

		//
		if (min_tab_height == "") {
			console.log("Not get height of LI in " + jd);
			return false;
		}
		// gấp rưỡi chiều cao để xử lý dễ hơn chút
		else {
			min_tab_height *= 1.5;
			min_tab_height = Math.ceil(min_tab_height);
		}
	}

	//
	if (WGR_check_option_on(cf_tester_mode))
		console.log("Fixed data height (max " + min_tab_height + "px) for " + jd);

	//	console.log( jQuery('.thread-details-tab').height() );
	if (jQuery(jd).height() > min_tab_height) {
		let j = 30;
		for (let i = 0; i < 28; i++) {
			jQuery(jd + " " + tag).css({
				//				padding: '0 ' +j+ 'px',
				"padding-left": j + "px",
				"padding-right": j + "px",
			});

			//
			//			console.log( jQuery( jd ).height() );
			if (jQuery(jd).height() < min_tab_height) {
				break;
			}

			//
			j--;
		}
	}

	// nếu vẫn chưa được -> màn hình có thể còn nhỏ hơn nữa -> tiếp tục thu font-size
	if (jQuery(jd).height() > min_tab_height) {
		let j = 17;
		for (let i = 0; i < 7; i++) {
			jQuery(jd + " " + tag).css({
				"font-size": j + "px",
			});

			//
			//			console.log( jQuery( jd ).height() );
			if (jQuery(jd).height() < min_tab_height) {
				break;
			}

			//
			j--;
		}
	}

	//
	//	jQuery( jd ).addClass('fixed-height-auto-height');

	return true;
}

function ___eb_big_banner() {
	console.log(
		"WARNING! Function ___eb_big_banner bas been remove, please clear in your code!"
	);
}

function ___eb_logo_doitac_chantrang(so_the_li_mong_muon, li_fo_scroll) {
	console.log(
		"WARNING! Function ___eb_logo_doitac_chantrang bas been remove, please clear in your code!"
	);
}

// tạo hiệu ứng với phần danh sách sản phẩm
function ___eb_thread_list_li() {
	jQuery(".thread-list li").each(function () {
		let ngay = jQuery(this).attr("data-ngay") || 0,
			//		giacu = jQuery(this).attr('data-gia') || '',
			//		giamoi = jQuery(this).attr('data-giamoi') || '',
			//		a = jQuery(this).attr('data-giovang') || '',
			//		b = jQuery(this).attr('data-soluong') || 0,
			//		gia = jQuery(this).attr('data-gia') || 0,
			per = jQuery(this).attr("data-per") || 0;

		//	gia = parseInt(gia, 10);

		/*
        b = parseInt(b, 10);
        if (b <= 0 || isNaN(b)) {
        	jQuery('.thread-list-chayhang', this).css({
        		'background-image': 'url(images/bg-sold-out.png)'
        	})
        }
        */

		//
		if (per > 0) {
			//			jQuery(this).addClass('thread-list-giamgiashow');

			//
			if (ngay > date_time) {
				jQuery(this).addClass("thread-list-giovangshow");
			}
		}
	});

	//
	//	jQuery('.hide-if-gia-zero[data-per="0"]').addClass('aaaaaaaaa');
}

// thêm dòng phân cách cho thẻ breadcrumb
function ___eb_add_space_for_breadcrumb(con) {
	console.log("___eb_add_space_for_breadcrumb has been remove");

	/*
    if ( typeof con == 'undefined' ) {
    	con = '/';
    }
	
	jQuery('.thread-details-tohome li').before('<li>' + con + '</li>');
	jQuery('.thread-details-tohome li:first').remove();
	*/
}

function WGR_active_popup(op) {
	if (WGR_check_option_on(cf_tester_mode)) console.log(op);

	// kiểm tra và tạo các giá trị mặc định
	if (typeof op["id_event"] == "undefined" || op["id_event"] == "") {
		console.log("id_event not found!");
		return false;
	}
	if (
		op["id_event"].substr(0, 1) != "#" &&
		op["id_event"].substr(0, 1) != "."
	) {
		op["id_event"] = "#" + op["id_event"];
	}
	if (jQuery(op["id_event"]).length == 0) {
		console.log(op["id_event"] + " not found!");
		return false;
	}

	if (typeof op["cookie_name"] == "undefined" || op["cookie_name"] == "") {
		op["cookie_name"] =
			"WGR_popup_ckname_" + op["id_event"].replace(/\#|\./g, "_");
	}

	//
	if (typeof op["cookie_time"] != "number" || op["cookie_time"] * 1 < 0) {
		op["cookie_time"] = 0;
	}
	if (typeof op["cookie_time1"] != "number" || op["cookie_time1"] * 1 < 0) {
		op["cookie_time1"] = 0;
	}
	if (typeof op["cookie_time2"] != "number" || op["cookie_time2"] * 1 < 0) {
		op["cookie_time2"] = 0;
	}
	let rtime =
		op["cookie_time"] * 60 +
		op["cookie_time1"] * 60 * 60 +
		op["cookie_time2"] * 24 * 3600;

	// nếu không có thời gian xác định cho cookie -> hủy bỏ luôn
	//	if ( op['cookie_time'] == 0 && op['cookie_time1'] == 0 && op['cookie_time2'] == 0 ) {
	if (rtime == 0) {
		console.log("time not set!");
		return false;
	}

	if (typeof op["time_start"] != "number" || op["time_start"] * 1 < 0) {
		op["time_start"] = 5;
	}
	if (typeof op["time_end"] != "number" || op["time_end"] * 1 < 0) {
		op["time_end"] = 30;
	}
	if (WGR_check_option_on(cf_tester_mode)) console.log(op);

	//
	let a = g_func.getc(op["cookie_name"]);
	if (a != null) {
		console.log("Popup hide by WGR_active_popup");
		return false;
	}

	// bật popup lên
	if (op["time_start"] > 0) {
		console.log("Popup show after " + op["time_start"] + "s");
		setTimeout(function () {
			jQuery(op["id_event"]).fadeIn();

			// lưu cookie phiên hiển thị popup này, để sau đỡ hiển thị lại
			g_func.setc(op["cookie_name"], rtime, rtime);

			//
			_global_js_eb.auto_margin();
		}, op["time_start"] * 1000);
	} else {
		jQuery(op["id_event"]).fadeIn();
	}
	jQuery(op["id_event"]).addClass("hide-if-esc");

	// tắt popup
	if (op["time_end"] > 0) {
		op["time_end"] = op["time_start"] * 1 + op["time_end"];
		console.log("Popup hide after " + op["time_end"] + "s");
		setTimeout(function () {
			jQuery(op["id_event"]).fadeOut();
		}, op["time_end"] * 1000);
	}

	if (typeof op["close_icon"] != "undefined" && op["close_icon"] != "") {
		jQuery(op["id_event"]).prepend(
			'<div class="wgr-popup-close" onclick="jQuery(\'' +
				op["id_event"] +
				'\').fadeOut();"><span><i class="fa ' +
				op["close_icon"] +
				'"></i></span></div>'
		);
	}
}

function WGR_close_popup() {
	close_img_quick_video_details();
}

function close_img_quick_video_details() {
	// ẩn video
	jQuery(".quick-video").hide().height("auto");

	// xóa nội dung -> tránh video vẫn đang bật
	dog("quick-video-content", "&nbsp;");

	//
	jQuery("#click_show_cpa, .hide-if-esc").hide();

	//
	jQuery("body").removeClass("body-no-scroll");

	window.history.pushState("", "", current_ls_url);
}

function ___eb_click_open_video_popup() {
	jQuery(".click-quick-view-video")
		.each(function () {
			let a = jQuery(this).attr("data-video") || "",
				lnk = jQuery(this).attr("href") || "",
				module = jQuery(this).attr("data-module") || "";
			if (module == "") {
				jQuery(this).attr({
					"data-module": "video_no_group",
				});
			}

			// lấy URL để tạo ID cho youtube nếu không có ID
			if (a == "" && lnk != "") {
				a = _global_js_eb.youtube_id(lnk);
				//			if ( WGR_check_option_on ( cf_tester_mode ) ) console.log( lnk );
				if (a != "") {
					//				if ( WGR_check_option_on ( cf_tester_mode ) ) console.log( a );
					jQuery(this).attr({
						"data-video": "//www.youtube.com/embed/" + a,
					});
				}
			}
		})
		.off("click")
		.click(function () {
			//		alert(1);
			let a = jQuery(this).attr("data-video") || "",
				tit = jQuery(this).attr("title") || "",
				lnk = jQuery(this).attr("href") || "",
				// nhóm các video liên quan theo module
				module = jQuery(this).attr("data-module") || "",
				str = "",
				arr_list_video = {};

			//
			if (a != "") {
				//
				jQuery(".quick-video").show().height(jQuery(document).height());
				//			jQuery('body').addClass('body-scroll-disable');

				//
				let wit = jQuery("#quick-video-content").width(),
					// wit_hai = 0,
					hai = jQuery(window).height();

				// xem đang là màn ngang hay dọc
				/*
            if ( hai > wit ) {
            	wit_hai = wit;
            	wit = hai;
            	hai = wit_hai;
            }
            */
				//			console.log( wit );
				//			console.log( hai );

				// chiều cao video -> không để hết màn hình là đẹp
				//			hai = Math.ceil( hai/ 100 * 90 );

				//
				//			wit = Math.ceil( hai/ youtube_video_default_size );

				if (wit > 1024) {
					wit = 1024;
				}
				console.log("ytb width: " + wit);

				hai = Math.ceil(wit * youtube_video_default_size);
				console.log("ytb height: " + hai);

				/*
            if ( hai > 600 ) {
            	hai = 400;
            } else {
            	hai = hai / 3 * 2;
            }
            */

				//
				a = _global_js_eb.youtube_id(a);
				//			alert(a);

				//
				if (lnk == "" || lnk == "javascript:;") {
				} else {
					// chỉ link nội bộ mới sử dụng chức năng này
					if (lnk.includes("//") == false || lnk.includes(web_link) == true) {
						window.history.pushState("", "", lnk);
					}
				}

				//
				let ytb_ifr =
					"https://www.youtube.com/embed/" +
					a +
					"?feature=oembed&autoplay=1&start&end&wmode=opaque&loop=0&controls=1&mute=0&rel=0&modestbranding=0";
				//			let ytb_ifr = 'https://www.youtube.com/embed/' +a+ '?autoplay=1';

				//
				str +=
					"" +
					'<div class="quick-video-node">' +
					'<div class="quick-video-width" style="width:' +
					wit +
					'px">' +
					'<div class="quick-video-title bold">' +
					tit +
					"</div>" +
					'<iframe width="' +
					wit +
					'" height="' +
					hai +
					'" src="' +
					ytb_ifr +
					'" frameborder="0" allowfullscreen></iframe>' +
					"</div>" +
					"</div>";

				// Tạo list video -> Lấy các video khác trên cùng trang
				let get_other_video = ".click-quick-view-video";
				if (module != "") {
					if (WGR_check_option_on(cf_tester_mode)) console.log(module);
					get_other_video =
						'.click-quick-view-video[data-module="' + module + '"]';
				}
				if (WGR_check_option_on(cf_tester_mode)) {
					console.log(get_other_video);
					console.log(jQuery(get_other_video).length);
				}

				//
				jQuery(get_other_video).each(function () {
					let a2 = jQuery(this).attr("data-video") || "",
						tit2 = jQuery(this).attr("title") || "";

					//
					if (a2 != "") {
						a2 = _global_js_eb.youtube_id(a2);
						//					console.log( a2 );

						//
						if (a2 != a) {
							arr_list_video[a2] = tit2;
						}
					}
				});
				//			console.log( arr_list_video );

				//
				for (let x in arr_list_video) {
					//
					str +=
						"" +
						'<div class="quick-video-node">' +
						'<div class="quick-video-width" style="width:' +
						wit +
						'px">' +
						'<div class="quick-video-title bold">' +
						arr_list_video[x] +
						"</div>" +
						'<iframe width="' +
						wit +
						'" height="' +
						hai +
						'" src="//www.youtube.com/embed/' +
						x +
						'" frameborder="0" allowfullscreen></iframe>' +
						"</div>" +
						"</div>";
				}

				//
				let new_scroll_top = window.scrollY || jQuery(window).scrollTop();

				//
				jQuery("#quick-video-content").css({
					"padding-top": new_scroll_top + "px",
				});

				//
				dog("quick-video-content", str);
				//dog( 'quick-video-content', str + str + str + str + str + str + str );

				// chỉnh lại chiều cao dữ liệu một lần nữa
				jQuery(".quick-video").show().height(jQuery(document).height());

				//
				return false;
			}
		});
	//	jQuery('.click-quick-view-video:first').click();

	//
	/*
    jQuery('.quick-video-close i.fa-remove').click(function () {
        close_img_quick_video_details();
    });
    */
}

//
function ___eb_func_fix_right_menu() {
	// chiều cao của menu phải
	//	let a = jQuery('.fix-right-menu').height();
	fix_right_window_height = jQuery(window).height();

	// chiều cao của main -> lớn hơn right thì mới dùng chức năng này
	privary_main_height = jQuery(id_for_fix_main_content).height() || 0;
	//console.log( 'main: ' + privary_main_height );

	// điểm bắt đầu fix menu
	right_main_height = jQuery(id_for_fix_menu_content).offset().top || 0;
	right_main_height += jQuery(".fix-right-menu").height();
	//console.log( 'main_right: ' + right_main_height );

	// xác định có auto scroll hay không
	fix_details_right_menu = false;
	if (right_main_height < privary_main_height) {
		fix_details_right_menu = true;
	}

	// thêm vào chiều cao của window để điểm cuối của scroll được ok
	if (fix_menu_top_or_bottom == "bottom") {
		right_main_height -= fix_right_window_height;
	}

	// điểm kết thúc fix menu
	end_right_top_menu =
		jQuery("#fix_end_right_menu").offset().top - fix_right_window_height;
	//console.log( 'end right: ' + end_right_top_menu );

	// fix style cho menu bên này, tránh bị vỡ khung
	/*
    jQuery('.fix-right-menu').width(jQuery('#fix_right_menu').width()).css({
        left: jQuery('#fix_right_menu').offset().left + 'px'
    });
    */

	//
	jQuery(window).scroll();
}

function ___eb_fix_left_right_menu() {
	return ___WGR_fixed_left_right_menu();
}

//
function ___WGR__fixed_left_right_menu(add_padding_top) {
	let a = window.scrollY || jQuery(window).scrollTop();

	// từ top tới right menu
	let top_to_right = $("#main_right").offset().top;
	//console.log(top_to_right);

	// nhỏ hơn thì fixed cứng top 0
	if (a < top_to_right) {
		$(".fix-right-menu").css({
			top: 0,
		});
	}
	// lớn hơn thì tính toán tiếp
	else {
		// chiều cao phần nội dung
		let right_hight = $(".fix-right-menu").height();
		//console.log('right_hight:' + right_hight);
		// điểm cuối của right menu
		//let end_right = $('.col-sidebar-content').height() - top_to_right;
		let end_right =
			$("#fix_end_right_menu").offset().top -
			top_to_right -
			right_hight -
			add_padding_top;
		//console.log('end_right:' + end_right);

		// phần top add thêm
		let add_top = a - top_to_right;
		if (add_top > end_right) {
			add_top = end_right;
		}
		add_top += add_padding_top;

		//
		$(".fix-right-menu").css({
			top: add_top,
		});
	}

	//
	setTimeout(function () {
		___WGR__fixed_left_right_menu(add_padding_top);
	}, 200);
}

//
function ___WGR_fixed_left_right_menu(add_padding_top) {
	if (g_func.mb_v2() == true) {
		console.log("___eb_fix_left_right_menu disable in mobile");
		return false;
	}

	/*
	 * add_padding_top: dùng với các giao diện có sử dụng stick menu top -> sẽ có thêm giãn cách để không bị stick menu che
	 */
	if (typeof add_padding_top != "number") {
		add_padding_top = 0;
	}

	//
	$("body").addClass("fixed-right-menu");

	//
	___WGR__fixed_left_right_menu(add_padding_top);
}

function ___eb_show_cart_count() {
	let c = "eb_cookie_cart_list_id",
		cart_id_in_cookie = g_func.getc(c);
	//	console.log( cart_id_in_cookie );
	if (cart_id_in_cookie == null) {
		return false;
	}
	//	console.log( cart_id_in_cookie );

	//
	cart_id_in_cookie = jQuery.trim(cart_id_in_cookie);
	if (cart_id_in_cookie == "") {
		return false;
	}

	// bỏ dấu , ở đầu chuỗi
	if (cart_id_in_cookie.substr(0, 1) == ",") {
		cart_id_in_cookie = cart_id_in_cookie.substr(1);
	}

	// tính tổng số SP
	jQuery(".show_count_cart").html(cart_id_in_cookie.split(",").length);
}

// danh sách sản phẩm đã xem, lưu dưới dạng cookies
function ___wgr_set_product_id_cookie(
	cookie_name,
	add_id,
	limit_history,
	limit_save
) {
	// tên của cookie lưu trữ
	if (typeof cookie_name == "undefined" || cookie_name == "") {
		cookie_name = "wgr_product_id_view_history";
	}

	// giới hạn lưu trữ
	if (typeof limit_history == "undefined" || limit_history < 0) {
		limit_history = 25;
	} else {
		limit_history = parseInt(limit_history, 10);
	}

	// thời hạn lưu trữ
	if (typeof limit_save == "undefined" || limit_save < 0) {
		limit_save = 7;
	} else {
		limit_save = parseInt(limit_save, 10);
	}

	// ID lưu trữ
	//	console.log(typeof add_id);
	//	console.log(add_id);
	if (typeof add_id == "undefined" || add_id == "" || add_id < 0) {
		add_id = pid;
	} else {
		add_id = parseInt(add_id, 10);
	}
	//	console.log(typeof add_id);
	//	console.log(add_id);
	if (add_id <= 0) {
		if (WGR_check_option_on(cf_tester_mode))
			console.log("new ID for add not found: " + add_id);
		return false;
	}

	// lấy danh sách trong cookie trước đó
	let str_history = g_func.getc(cookie_name),
		new_id = "[" + add_id + "]";
	if (WGR_check_option_on(cf_tester_mode)) {
		console.log(str_history);
		limit_history = 5;
	}

	// nếu chưa có -> null
	if (str_history == null || str_history == "") {
		str_history = "";
	}
	// nếu có rồi -> kiểm tra có trùng với ID hiện tại không
	else if (str_history.includes(new_id) == true) {
		if (WGR_check_option_on(cf_tester_mode))
			console.log("product ID exist in history cookie");
		return false;
	}

	//
	//	str_history = str_history.replace( '[' + pid + ']', '' );
	//	console.log(str_history);

	// kiểm tra đọ dài của log
	let check_history = str_history.split("][");
	//	console.log(check_history.length);
	//	console.log(check_history);

	// nếu nhiều quá -> thay mảng cuối bằng ID hiện tại
	if (check_history.length >= limit_history) {
		// thêm vào cuối
		//		check_history[ check_history.length - 1 ] = pid + ']';

		// sau đó ghép chuỗi lại -> trong giới hạn cho phép thì ghép luôn là được
		if (check_history.length < limit_history * 2) {
			// thêm vào đầu
			check_history[0] = "[" + add_id;

			str_history = check_history.join("][");
		} else {
			str_history = "";
			for (let i = 0; i < limit_history + 1; i++) {
				str_history += check_history[i] + "][";
			}
			// thêm vào cuối
			str_history += add_id + "]";
		}
		if (WGR_check_option_on(cf_tester_mode))
			console.log("Split history: " + str_history);
	}
	// thêm mới
	else {
		// thêm vào cuối
		//		str_history += new_id;

		// thêm vào đầu
		str_history = new_id + str_history;
	}
	//	console.log(str_history);
	//	return false;

	// lưu cookie mới
	g_func.setc(cookie_name, str_history, 0, limit_save);

	//
	return str_history;
}

function WGR_goto_a_tag(a) {
	setTimeout(function () {
		action_WGR_goto_a_tag(a);
	}, 200);
}

function action_WGR_goto_a_tag(a) {
	try {
		let goto = 0;

		// chuyển tới 1 ID, class hoặc input nào đó
		if (jQuery("#" + a).length > 0) {
			goto = jQuery("#" + a).offset().top;
		} else if (jQuery('a[name="' + a + '"]').length > 0) {
			goto = jQuery('a[name="' + a + '"]').offset().top;
		} else if (jQuery("." + a).length > 0) {
			goto = jQuery("." + a).offset().top;
		}
		// mở popup đăng nhập/ đăng ký
		else if (a == "login" || a == "register") {
			g_func.opopup(a);
			return false;
		}
		console.log(goto);

		if (goto > 90) {
			//window.scroll( 0, goto - 110 );
			jQuery("body,html").animate(
				{
					//scrollTop: goto - 110
					scrollTop: goto - Math.ceil(($(window).height() / 100) * 33),
				},
				600
			);

			window.location.hash = a;

			return false;
		}
	} catch (e) {
		console.log(WGR_show_try_catch_err(e));

		//
		jQuery("body,html").animate(
			{
				//scrollTop: goto - 110
				scrollTop:
					jQuery(this).offset().top -
					Math.ceil(($(window).height() / 100) * 33),
			},
			600
		);
		return false;
	}
}
