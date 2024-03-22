// cấu hình chung cho phần chỉnh BG
var arr_bg_background_position = {
		"": "Mặc định (theo thiết kế của tác giả)",
		"top left": "Góc trên bên trái",
		"top center": "Góc trên ở giữa",
		"top right": "Góc trên bên phải",
		"center left": "Giữa bên trái",
		"center center": "Chính giữa",
		"center right": "Giữa bên phải",
		"bottom left": "Phía dưới Bên trái",
		"bottom center": "Phía dưới ở giữa",
		"bottom right": "Góc phải phía dưới",
	},
	arr_bg_background_size = {
		"": "Mặc định (theo thiết kế của tác giả)",
		cover: "Bao phủ",
		contain: "Contain",
		auto: "Tự động",
		"150% auto": "Rộng (150%) x Cao (tự động)",
		"140% auto": "Rộng (140%) x Cao (tự động)",
		"130% auto": "Rộng (130%) x Cao (tự động)",
		"120% auto": "Rộng (120%) x Cao (tự động)",
		"110% auto": "Rộng (110%) x Cao (tự động)",
		"auto 150%": "Rộng (tự động) x Cao (150%)",
		"auto 140%": "Rộng (tự động) x Cao (140%)",
		"auto 130%": "Rộng (tự động) x Cao (130%)",
		"auto 120%": "Rộng (tự động) x Cao (120%)",
		"auto 110%": "Rộng (tự động) x Cao (110%)",
		//	'auto 95%' : 'Rộng (tự động) x Cao (95%)',
		"auto 90%": "Rộng (tự động) x Cao (90%)",
		//	'auto 85%' : 'Rộng (tự động) x Cao (85%)',
		"auto 80%": "Rộng (tự động) x Cao (80%)",
		//	'auto 75%' : 'Rộng (tự động) x Cao (75%)',
		"auto 70%": "Rộng (tự động) x Cao (70%)",
		//	'auto 65%' : 'Rộng (tự động) x Cao (65%)',
		"auto 60%": "Rộng (tự động) x Cao (60%)",
		//	'auto 55%' : 'Rộng (tự động) x Cao (55%)',
		"auto 50%": "Rộng (tự động) x Cao (50%)",
		//	'95% auto' : 'Rộng (95%) x Cao (tự động)',
		"90% auto": "Rộng (90%) x Cao (tự động)",
		//	'85% auto' : 'Rộng (85%) x Cao (tự động)',
		"80% auto": "Rộng (80%) x Cao (tự động)",
		//	'75% auto' : 'Rộng (75%) x Cao (tự động)',
		"70% auto": "Rộng (70%) x Cao (tự động)",
		//	'65% auto' : 'Rộng (65%) x Cao (tự động)',
		"60% auto": "Rộng (60%) x Cao (tự động)",
		//	'55% auto' : 'Rộng (55%) x Cao (tự động)',
		"50% auto": "Rộng (50%) x Cao (tự động)",
		//	'50% auto' : 'Rộng x Cao (aaaaaa%)'
	},
	arr_cf_css_body = {},
	khong_cho_update_config_lien_tuc = false;

function load_config_for_custom_logo(
	arr,
	arr_name,
	arr_alt,
	arr_input_type,
	a,
	op,
	ops
) {
	var str = "",
		str_input = "",
		sl = "",
		// ký tự tối đa của input text
		maxlength = "",
		donvi = "",
		// class định hình độ dài của input text
		with_class = "",
		name_input = "",
		str_placeholder = "";

	//
	if (typeof a != "object") {
		if (a != "") {
			//			console.log(a);
			a = unescape(a);
			//			console.log(a);
			a = eval("[" + a + "]");
			a = a[0];
			//			a = eval( unescape( a ) );
		} else {
			a = {};
		}
	}
	console.log(arr);
	//	console.log(a);
	//	console.log('---------------');
	//	console.log(arr_name);
	//	console.log(arr_alt);

	//
	if (typeof ops != "object") {
		ops = {};
	}

	//
	if (typeof op["input_css"] == "undefined" || typeof op["input_css"] == "") {
		op["input_css"] = "each-to-css-for-" + op["input_name"];
	}
	if (typeof op["after_html"] == "undefined" || typeof op["after_html"] == "") {
		op["after_html"] = "custom_css_for_" + op["input_name"];
	}
	//	console.log( op );

	//
	for (var x in arr) {
		//		console.log(x);

		// kiểm tra và bổ sung những mảng chưa có, hoặc xóa những mảng dư thừa
		/*
		if ( a != '' && typeof a[x] != 'undefined' ) {
			arr[x] = a[x];
		}
		console.log(arr[x]);
		*/
		if (typeof a[x] == "undefined") {
			a[x] = "";
		}

		// xong mới tạo tên cho input
		name_input = op["input_name"] + "__custom_css_in_js__" + x;

		//
		if (typeof arr_input_type[x] == "undefined") {
			arr_input_type[x] = "text";
		}

		//
		donvi = "";
		if (
			typeof ops["donvi"] != "undefined" &&
			typeof ops["donvi"][x] != "undefined"
		) {
			donvi = ops["donvi"][x];
		}

		//
		str_placeholder = "";
		if (
			typeof ops["placeholder"] != "undefined" &&
			typeof ops["placeholder"][x] != "undefined"
		) {
			str_placeholder = ops["placeholder"][x];
			//			console.log( str_placeholder );
			if (str_placeholder != "") {
				str_placeholder = str_placeholder.toString().replace(/\"/g, "&quot;");
			}
		}

		// tạo input
		str_input = "";
		if (typeof arr[x] == "object") {
			for (var x2 in arr[x]) {
				sl = "";
				//				if ( typeof a[x] != 'undefined' && x2 == a[x] ) {
				if (x2 == a[x]) {
					sl = ' selected="selected"';
				}

				//
				str_input +=
					'<option value="' + x2 + '"' + sl + ">" + arr[x][x2] + "</option>";
			}
			str_input =
				'<select name="' +
				name_input +
				'" data-donvi="' +
				donvi +
				'" class="' +
				op["input_css"] +
				'">' +
				str_input +
				"</select>";
		} else if (arr_input_type[x] == "number") {
			str_input =
				'<input type="' +
				arr_input_type[x] +
				'" name="' +
				name_input +
				'" value="' +
				a[x] +
				'" data-donvi="' +
				donvi +
				'" placeholder="' +
				str_placeholder +
				'" class="s ' +
				op["input_css"] +
				'" />';
		} else if (arr_input_type[x] == "color") {
			str_input =
				'<input type="' +
				arr_input_type[x] +
				'" name="' +
				name_input +
				'" value="' +
				a[x] +
				'" placeholder="#notset" class="ebe-color-picker ' +
				op["input_css"] +
				'" />';

			str_input +=
				' - <a href="javascript:;" data-set="' +
				name_input +
				'" class="click-to-set-site-color">Nhập mã màu</a> - <a href="javascript:;" data-set="' +
				name_input +
				'" class="click-to-reset-site-color">Mặc định</a> ';
		} else {
			maxlength = 155;
			if (
				typeof ops["maxlength"] != "undefined" &&
				typeof ops["maxlength"][x] != "undefined"
			) {
				maxlength = ops["maxlength"][x];
			}

			//
			with_class = "s";
			if (maxlength > 155) {
				with_class = "l";
			} else if (maxlength > 75) {
				with_class = "m";
			} else if (maxlength > 25) {
				with_class = "n";
			}

			//
			str_input =
				'<input type="' +
				arr_input_type[x] +
				'" name="' +
				name_input +
				'" value="' +
				a[x].toString().replace(/\"/g, "&quot;") +
				'" data-donvi="' +
				donvi +
				'" placeholder="' +
				str_placeholder +
				'" class="' +
				with_class +
				" " +
				op["input_css"] +
				'" maxlength="' +
				maxlength +
				'" />';
		}

		// ghi chú
		if (typeof arr_alt[x] != "undefined") {
			str_input += '<p class="description">' + arr_alt[x] + "</p>";
		}

		//
		str +=
			"" +
			"<tr>" +
			'<td valign="top" class="t">' +
			arr_name[x] +
			"</td>" +
			'<td class="i">' +
			str_input +
			"</td>" +
			"</tr>";
	}

	//
	console.log("After HTML " + op["after_html"]);
	$("#" + op["after_html"]).after(str);
}

function create_css_for_custom_in_js(clat, jd, cs) {
	//	str += (function ( clat, jd, cs ) {
	//	$('.each-to-css-for-logo').off('change').change(function () {

	// tạo class để gọi tới chức năng lấy nội dung
	if (typeof cs["for"] == "undefined" || cs["for"] == "") {
		cs["for"] = cs["pc"];
	}

	if (clat == "") {
		clat = ".each-to-css-for-" + cs["for"];
	}

	if (jd == "") {
		jd = "cf_css_" + cs["for"];
	}
	console.log("Each to CSS in: " + clat + "/ " + jd);

	//
	var str = "",
		str_mobile = "",
		arr = {};

	// class cho bản mobile
	if (typeof cs["mobile"] == "undefined" || cs["mobile"] == "") {
		cs["mobile"] = ".style-for-mobile " + cs["pc"];
	}

	//
	$(clat).each(function () {
		var a = $(this).val() || "",
			n = $(this).attr("name") || "",
			// đơn vị tính với các số đo: px, pt
			donvi = $(this).attr("data-donvi") || "";

		if (a != "" && n != "") {
			//				n = n.replace('custom_css_in_js_', '');
			n = n.split("__custom_css_in_js__")[1];

			if (n.includes("background_image") == true) {
				// bỏ URL đi nếu có
				if (a.includes("(") == true) {
					a = jQuery.trim(a.split("(")[1].split(")")[0]);
				}
				a = "url(" + a + ") !important";
			}

			//
			arr[n] = a;
			//				console.log(donvi);

			//
			if (n.includes("_for_mobile") == true) {
				str_mobile +=
					n.replace("_for_mobile", "").replace(/\_/g, "-") +
					":" +
					a +
					donvi +
					";";
			} else {
				/*
				else if ( n.includes('_for_footer') == true ) {
					str += n.replace('_for_footer', '').replace(/\_/g, '-') + ':' + a + donvi + ';';
				}
				*/
				str += n.replace(/\_/g, "-") + ":" + a + donvi + ";";
			}
		}
	});
	//		console.log(arr);

	//
	if (str == "" && str_mobile == "") {
		//			arr = '';
		arr = {};
	} else {
		if (str != "") {
			str = cs["pc"] + "{" + str + "}";
			console.log(str);
		}
		//			console.log(str);

		if (str_mobile != "" && cs["mobile"] != null) {
			str_mobile = cs["mobile"] + "{" + str_mobile + "}";
			console.log(str_mobile);
		}
		//			console.log(str_mobile);

		//
		//			arr = escape( JSON.stringify( arr ) );
		//			console.log(arr);
	}

	//
	//		$( jd ).val( arr );
	arr_cf_css_body[jd] = arr;

	// trả về kết quả với css for pc trước, mobile sau
	return str + str_mobile;
	//	})( '.each-to-css-for-logo', '#cf_css_logo', {
	//		'mobile' : '',
	//		'pc' : 'web-logo'
	//	} );
	//	});
}

// phần config khi bấm update sẽ chạy rất lâu do phải update rất nhiều thứ, làm cái này để nó chỉ update những cái đã được thay đổi
var arr_list_filed_config_update = {},
	load_add_filed_for_config = false;
function WGR_add_filed_for_config_update() {
	if (load_add_filed_for_config === true) {
		return false;
	}
	load_add_filed_for_config = true;

	//
	if ($("#list_filed_for_config_update").length == 0) {
		console.log("%c list_filed_for_config_update not found!", "color: red;");
		return false;
	}

	//	var str = [];

	// các filed ẩn -> mặc định sẽ lấy tất cả các text area luôn
	//	$('.config-update-filed-change-only textarea, .load-config-hidden-filed input').each(function() {
	$(
		'.load-config-hidden-filed textarea, .load-config-hidden-filed input, .config-update-filed-change-only input[type="hidden"]'
	).each(function () {
		var a = $(this).attr("name") || "";

		if (
			a != "" &&
			a.includes("cf_") == true &&
			typeof arr_list_filed_config_update[a] == "undefined"
		) {
			//			str.push(a);
			arr_list_filed_config_update[a] = 1;
		}
	});

	//
	var arr_list_input = [
		'.config-update-filed-change-only input[type="number"]',
		'.config-update-filed-change-only input[type="email"]',
		'.config-update-filed-change-only input[type="checkbox"]',
		'.config-update-filed-change-only input[type="radio"]',
		'.config-update-filed-change-only input[type="color"]',
		".config-update-filed-change-only select",
		//		'.config-update-filed-change-only textarea',
		'.config-update-filed-change-only input[type="text"]',
	];

	$(arr_list_input.join(",")).change(function () {
		var a = $(this).attr("name") || "";

		if (
			a != "" &&
			a.includes("cf_") == true &&
			typeof arr_list_filed_config_update[a] == "undefined"
		) {
			arr_list_filed_config_update[a] = 1;

			WGR_add_json_to_filed_config_update();
		}
	});

	// với textarea thì viết riêng ra, do hàm change đang dùng để resize textarea
	$(".config-update-filed-change-only textarea").focus(function () {
		var a = $(this).attr("name") || "";

		if (
			a != "" &&
			a.includes("cf_") == true &&
			typeof arr_list_filed_config_update[a] == "undefined"
		) {
			arr_list_filed_config_update[a] = 1;

			WGR_add_json_to_filed_config_update();
		}
	});

	// nạp dữ liệu từ phần chỉnh sửa màu sắc
	$(".click-to-set-site-color, .click-to-reset-site-color").focus(function () {
		var a = $(this).attr("data-set") || "";

		if (
			a != "" &&
			a.includes("cf_") == true &&
			typeof arr_list_filed_config_update[a] == "undefined"
		) {
			arr_list_filed_config_update[a] = 1;

			WGR_add_json_to_filed_config_update();
		}
	});

	// với các input được update thông các chức năng khác như chèn ảnh
	$(".click-open-new-gallery").each(function () {
		var a = $(this).attr("data-update") || "";

		if (
			a != "" &&
			a.includes("cf_") == true &&
			typeof arr_list_filed_config_update[a] == "undefined"
		) {
			arr_list_filed_config_update[a] = 1;

			WGR_add_json_to_filed_config_update();
		}
	});

	//
	//	console.log(str);
	WGR_add_json_to_filed_config_update();
}

function WGR_add_json_to_filed_config_update() {
	console.log(arr_list_filed_config_update);
	document.frm_config.list_filed_for_config_update.value = JSON.stringify(
		arr_list_filed_config_update
	);
}
