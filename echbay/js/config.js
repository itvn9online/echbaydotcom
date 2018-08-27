



//
function xu_ly_du_lieu_js(fun) {
	/*
	fun = fun.replace(/<!--\s-->/g, "");
	fun = fun.replace(/<!--\s\s-->/g, "");
	fun = fun.replace(/<!--\s\s\s-->/g, "");
	*/
	
	// optimize code
	fun = fun.replace( / type=\"text\/javascript\"/gi, '' );
	fun = fun.replace( / type=\'text\/javascript\'/gi, '' );
	
	//
	var arr = fun.split("\n"),
		str = '',
		re = /^\w+$/,
		ky_tu_cuoi_cung = '';
	for (var i = 0; i < arr.length; i++) {
		arr[i] = $.trim(arr[i]);
		
		//
		if ( arr[i] != '' ) {
			// remove comment
			if ( arr[i].substr( 0, 4 ) == '<!--' && arr[i].substr( arr[i].length - 3 ) == '-->' ) {
			}
			else if ( arr[i].substr( 0, 2 ) == '/*' && arr[i].substr( arr[i].length - 2 ) == '*/' ) {
			}
			// accept code
			else if ( arr[i].substr(0, 2) != '//' ) {
				
				/*
				if (arr[i].substr(0, 4) == '<!--') {
					if (arr[i].substr(arr[i].length - 3) == '-->') {
						arr[i] = '';
					}
				}
				*/
//				if (arr[i] != '') {
					str += arr[i];
//					str += "\n";
//				}
				
				//
				ky_tu_cuoi_cung = arr[i].substr( arr[i].length - 1 );
				if ( ky_tu_cuoi_cung != ';' ) {
//					console.log( ky_tu_cuoi_cung );
//				} else {
//					console.log( ky_tu_cuoi_cung+ ' ------------------> OK' );
					str += "\n";
				}
			}
		}
	}
	return str;
}

//
var khong_cho_update_config_lien_tuc = false,
	arr_cf_css_body = {};

function khong_update_lien_tuc () {
	
	khong_cho_update_config_lien_tuc = true;
	$('#rAdminME').css({
		opacity: .2
	});
	
	$('#target_eb_iframe').on('load', function () {
		khong_cho_update_config_lien_tuc = false;
		
		$('#rAdminME').css({
			opacity: 1
		});
	});
	
}

function check_update_config_theme () {
	if ( khong_cho_update_config_lien_tuc == true ) {
		console.log('Vui lòng chờ...');
		return false;
	}
	
	khong_update_lien_tuc();
	
	return true;
}

function check_update_config() {
	if ( khong_cho_update_config_lien_tuc == true ) {
		console.log('Vui lòng chờ...');
		return false;
	}
	
	//
	var f = document.frm_config;
	
	var fun = f.cf_js_allpage_full.value,
		js_gadsense = f.cf_js_gadsense_full.value,
		js_hoantat = f.cf_js_hoan_tat_full.value,
		js_head = f.cf_js_head_full.value;
	
	var str = xu_ly_du_lieu_js(fun),
		str_gadsense = xu_ly_du_lieu_js(js_gadsense),
		str_hoantat = xu_ly_du_lieu_js(js_hoantat),
		str_head = xu_ly_du_lieu_js(js_head);
	
	f.cf_js_allpage.value = str;
	f.cf_js_gadsense.value = str_gadsense;
	f.cf_js_hoan_tat.value = str_hoantat;
	f.cf_js_head.value = str_head;
	
	//
	console.log(str);
	console.log(str_hoantat);
	console.log(str_head);
	
	//
	/*
	if ( $.trim( f.cf_dns_prefetch.value ) != '' ) {
		var a = f.cf_dns_prefetch.value.split('//');
//		console.log( a.length );
		
		if ( a.length > 1 ) {
			a = a[1];
		} else {
			a = a[0];
		}
		
		f.cf_dns_prefetch.value = a.split('/')[0];
	}
	*/
	
	
	//
	create_phone_click_to_call();
	create_deault_css();
	
	
	//
	var a = $.trim( f.cf_current_price.value );
	if ( a != '' ) {
		f.cf_current_price.value = a.replace( /\\/g, '/' );
	}
	
	
	
	// tạo HTML cho địa chỉ
	var a = $.trim( f.cf_diachi.value );
	f.cf_p_diachi.value = '';
	if ( a != '' ) {
		a = a.split( "\n" );
		for ( var i = 0; i < a.length; i++ ) {
			a[i] = $.trim( a[i] );
			
			// thêm chữ địa chỉ cho dòng đầu tiên
			if ( i == 0 ) {
				a[i] = '%tmp.fd_diachi% ' + a[i];
			}
			// nếu trống -> tạo cách dòng
			else if ( a[i] == '' ) {
				a[i] = '&nbsp;';
			}
			
			// tổng hợp lại
			f.cf_p_diachi.value += '<p>' + a[i] + '</p>';
		}
	}
	
	
	
	//
	khong_update_lien_tuc();
	
	
	
	//
//	return false;
	return true;
}


function create_deault_css () {
	var str = '',
		f = document.frm_config,
		a = '';
	
	
	// màu nền của AMP chính là màu nền cơ bản
	$('#cf_default_amp_bg').val( $('#cf_default_bg').val() || '' );
	
	
	// chiều cao của big_banner -> chỉ áp dụng cho bản PC
	a = f.cf_top_banner_size.value;
	
	// nếu size full thì không định dạng chiều cao cho banner, để
//	console.log(a);
	if ( a == 'full' ) {
		str += '.oi_big_banner {height: auto;line-height: normal;}';
		str += '@media screen and (max-width:775px) { .oi_big_banner {height: auto;line-height: normal;} }';
		str += '@media screen and (max-width:350px) { .oi_big_banner {height: auto;line-height: normal;} }';
	}
	// những cái khác định dạng bình thường
	else {
		if ( a == 'auto' ) {
			a = 400;
		}
		else {
			a = a.split('/')[0];
		}
		
		//
		a -= Math.ceil(a/ 8);
		str += '.oi_big_banner {height: ' + a + 'px;line-height: ' + a + 'px;}';
		
		// table
		a = Math.ceil(a/ 2);
		str += '@media screen and (max-width:775px) { .oi_big_banner {height: ' + a + 'px;line-height: ' + a + 'px;} }';
		
		// mobile
		a = Math.ceil(a/ 2);
		str += '@media screen and (max-width:350px) { .oi_big_banner {height: ' + a + 'px;line-height: ' + a + 'px;} }';
	}
//	console.log(str);
	
	
	// body
	a = f.cf_default_body_bg.value;
	var new_css = '';
	
	if ( a == '#ffffff' || a == '#fff' ) { }
	else {
		new_css += 'background-color: ' + a + ';';
	}
	/*
	a = f.cf_default_size.value;
	if ( a != '' && a > 0 && a != 10 ) {
		new_css += 'font-size: ' + a + 'pt;';
	}
	*/
	if ( new_css != '' ) {
		str += 'body {' + new_css + '}';
	}
	
	
	// DIV bg default
	a = f.cf_default_div_bg.value;
	str += '.default-div-bg { background-color: ' + a + '; }';
	
	// text color
	a = f.cf_default_color.value;
	str += '.default-color, .default-color a, .mcb, .mcb a { color: ' + a + '; }';
	
	// link color
	a = f.cf_default_link_color.value;
	str += 'a { color: ' + a + '; }';
	
	// button, menu, default bg
	a = f.cf_default_bg.value;
	str += '.default-bg, .thread-home-c2 a:first-child, #oi_scroll_top { background-color: ' + a + '; }';
	str += '.div-search, .thread-home-c2, .echbay-widget-title:before { border-color: ' + a + '; }';
//	str += '.thread-home-c2 { border-bottom-color: ' + a + '; }';
	
	// button, menu 2
	a = f.cf_default_2bg.value;
	str += '.default-2bg, .default2-bg, #oi_scroll_top:hover { background-color: ' + a + '; }';
	
	// text color (button, menu)
	a = f.cf_default_bg_color.value;
	str += '.default-bg, .default-bg a { color: ' + a + '; }';
	
	// text color (button, menu)
	if ( dog('cf_on_off_echbay_logo').checked == false ) {
		str += '.powered-by-echbay { display: none; }';
	}
	
	
	// lấy các thuộc tính để CSS cho body
	str += create_css_for_custom_in_js( '.each-to-css-for-body', 'cf_css_body', {
		'mobile' : 'body.style-for-mobile',
		'pc' : 'body'
	} );
	
	// logo
	str += create_css_for_custom_in_js( '.each-to-css-for-logo', 'cf_css_logo', {
		'mobile' : '.style-for-mobile #webgiare__top .web-logo, .style-for-mobile .menu-for-mobile .web-mobile-logo',
		'pc' : '.web-logo'
	} );
	
	// các mã màu được sinh ra sau khi bộ JS kia chạy
	try {
		a = f.mobile_logo_custom_css_in_js_background_image.value;
		if ( a != '' ) {
			str += '.style-for-mobile .web-logo, .style-for-mobile .web-mobile-logo{background-image: url(' + a + ') !important;}';
		}
	} catch ( e ) {
		console.log( WGR_show_try_catch_err( e ) );
	}
	
	// w90
	str += create_css_for_custom_in_js( '.each-to-css-for-w90', 'cf_css_w90', {
//		'mobile' : '',
		'pc' : '.w90'
	} );
	
	//
	str += create_css_for_custom_in_js( '.each-to-css-for-details', 'cf_css_details', {
//		'mobile' : '',
		'pc' : '.thread-details-tab li.selected'
	} );
	
	//
	console.log( arr_cf_css_body );
//	console.log( JSON.stringify( arr_cf_css_body ) );
//	console.log( escape( JSON.stringify( arr_cf_css_body ) ) );
	$( '#cf_css_body' ).val( escape( JSON.stringify( arr_cf_css_body ) ) );
	
	
	// các mã màu được sinh ra sau khi bộ JS kia chạy
	try {
		a = f.details_custom_css_in_js_background_color.value;
		if ( a != '' && a.substr( 0, 1 ) == '#' && ( a.length == 4 || a.length == 7 ) ) {
			str += '.thread-details-tab,.thread-list-filter{border-bottom-color:' + a + '}.thread-list-filter div{background-color:' + a + '}';
		}
	} catch ( e ) {
		console.log( WGR_show_try_catch_err( e ) );
	}
	
	
	//
	$('#cf_default_css').val(str);
}

// tạo số điện thoại dưới dạng HTML
function create_phone_click_to_call () {
	
	var f = document.frm_config;
	
	// điện thoại
	var a = $.trim( f.cf_dienthoai.value ),
		str = '';
	if ( a != '' ) {
		a = a.split("\n");
		
		for ( var i = 0; i < a.length; i++ ) {
			a[i] = $.trim( a[i] );
			
			if ( a[i] != '' ) {
				str += '<a href="tel:' + g_func.number_only( a[i] ) + '" rel="nofollow">' + a[i] + '</a>' + "\n";
			}
		}
	}
	f.cf_call_dienthoai.value = $.trim( str );
	
	// hotline
	var a = $.trim( f.cf_hotline.value ),
		str = '';
	if ( a != '' ) {
		a = a.split("\n");
		
		for ( var i = 0; i < a.length; i++ ) {
			a[i] = $.trim( a[i] );
			
			if ( a[i] != '' ) {
				str += '<a href="tel:' + g_func.number_only( a[i] ) + '" rel="nofollow">' + a[i] + '</a>' + "\n";
			}
		}
	}
	f.cf_call_hotline.value = $.trim( str );
	
}

//
if ( $('#cf_call_dienthoai').val() == '' || $('#cf_call_hotline').val() == '' ) {
	create_phone_click_to_call();
}




function config_test_send_mail() {
	
	var f = document.frm_config,
		from = f.cf_smtp_email.value,
		pass = f.cf_smtp_pass.value,
		host = f.cf_smtp_host.value,
		to = f.cf_email_note.value;
	
	if ( to == '' ) {
		to = f.cf_email.value;
	}
	
	//
	dog('test_smtp_email', 'Đang tải...');
	$('#test_smtp_email').show();
	
	// 
	ajaxl( web_link + 'test_email?email=' + to, 'test_smtp_email', 1, function () {
		$('#test_smtp_email').html( $('#test_smtp_email').html().replace(/\n/g, '<br>') );
	} );
	
	//
	/*
	return false;
	
	//
	if (from != '' && pass != '' && host != '' && to != '') {
		dog('test_smtp_email', 'Đang tải...');
		$('#test_smtp_email').show();
//		ajaxl('guest.php?act=test_smtp_email&t_email=' + to, 'test_smtp_email', 1);
	} else {
		alert('Nhập đầy đủ Email, Host, Pass để thử');
		f.cf_smtp_email.focus();
	}
	*/
	
}


function load_config_for_custom_logo ( arr, arr_name, arr_alt, arr_input_type, a, op, ops ) {
	var str = '',
		str_input = '',
		sl = '',
		// ký tự tối đa cảu input text
		maxlength = '',
		// class định hình độ dài của input text
		with_class = '',
		name_input = '';
	
	//
	if ( typeof a != 'object' ) {
		if ( a != '' ) {
//			console.log(a);
			a = unescape( a );
//			console.log(a);
			a = eval( '[' + a + ']' );
			a = a[0];
//			a = eval( unescape( a ) );
		}
		else {
			a = {};
		}
	}
//	console.log(arr);
//	console.log(a);
//	console.log('---------------');
//	console.log(arr_name);
//	console.log(arr_alt);
	
	//
	if ( typeof ops != 'object' ) {
		ops = {};
	}
	
	//
	if ( typeof op['input_css'] == 'undefined' ) {
		op['input_css'] = 'each-to-css-for-' + op['input_name'];
	}
	if ( typeof op['after_html'] == 'undefined' ) {
		op['after_html'] = 'custom_css_for_' + op['input_name'];
	}
//	console.log( op );
	
	//
	for ( var x in arr ) {
//		console.log(x);
		
		// kiểm tra và bổ sung những mảng chưa có, hoặc xóa những mảng dư thừa
		/*
		if ( a != '' && typeof a[x] != 'undefined' ) {
			arr[x] = a[x];
		}
		console.log(arr[x]);
		*/
		if ( typeof a[x] == 'undefined' ) {
			a[x] = '';
		}
		
		// xong mới tạo tên cho input
		name_input = op['input_name'] + '_custom_css_in_js_' + x;
		
		//
		if ( typeof arr_input_type[x] == 'undefined' ) {
			arr_input_type[x] = 'text';
		}
		
		// tạo input
		str_input = '';
		if ( typeof arr[x] == 'object' ) {
			for ( var x2 in arr[x] ) {
				sl = '';
//				if ( typeof a[x] != 'undefined' && x2 == a[x] ) {
				if ( x2 == a[x] ) {
					sl = ' selected="selected"';
				}
				
				//
				str_input += '<option value="' + x2 + '"' + sl + '>' + arr[x][x2] + '</option>';
			}
			str_input = '<select name="' + name_input + '" class="' + op['input_css'] + '">' + str_input + '</select>';
		}
		else if ( arr_input_type[x] == 'number' ) {
			str_input = '<input type="' + arr_input_type[x] + '" name="' + name_input + '" value="' + a[x] + '" class="s ' + op['input_css'] + '" />';
		}
		else if ( arr_input_type[x] == 'color' ) {
			str_input = '<input type="' + arr_input_type[x] + '" name="' + name_input + '" value="' + a[x] + '" placeholder="#notset" class="ebe-color-picker ' + op['input_css'] + '" />';
			
			str_input += ' - <a href="javascript:;" data-set="' + name_input + '" class="click-to-set-site-color">Nhập mã màu</a> - <a href="javascript:;" data-set="' + name_input + '" class="click-to-reset-site-color">Mặc định</a> ';
		}
		else {
			maxlength = 155;
			if ( typeof ops['maxlength'] != 'undefined' && typeof ops['maxlength'][x] != 'undefined' ) {
				maxlength = ops['maxlength'][x];
			}
			
			//
			with_class = 's';
			if ( maxlength > 155 ) {
				with_class = 'l';
			}
			else if ( maxlength > 75 ) {
				with_class = 'm';
			}
			else if ( maxlength > 25 ) {
				with_class = 'n';
			}
			
			//
			str_input = '<input type="' + arr_input_type[x] + '" name="' + name_input + '" value="' + a[x] + '" class="' + with_class + ' ' + op['input_css'] + '" maxlength="' + maxlength + '" />';
		}
		
		// ghi chú
		if ( typeof arr_alt[x] != 'undefined' ) {
			str_input += '<p class="description">' + arr_alt[x] + '</p>';
		}
		
		//
		str += '' +
		'<tr>' +
			'<td valign="top" class="t">' + arr_name[x] + '</td>' +
			'<td class="i">' + str_input + '</td>' +
		'</tr>';
	}
	
	//
	$('#' + op['after_html']).after(str);
}

function create_css_for_custom_in_js ( clat, jd, cs ) {
//	str += (function ( clat, jd, cs ) {
//	$('.each-to-css-for-logo').off('change').change(function () {
		var str = '',
			str_mobile = '',
			arr = {};
		
		// class cho bản mobile
		if ( typeof cs['mobile'] == 'undefined' || cs['mobile'] == '' ) {
			cs['mobile'] = '.style-for-mobile ' + cs['pc'];
		}
		
		//
		$( clat ).each(function () {
			var a = $(this).val() || '',
				n = $(this).attr('name') || '';
			
			if ( a != '' && n != '' ) {
//				n = n.replace('custom_css_in_js_', '');
				n = n.split('_custom_css_in_js_')[1];
				arr[n] = a;
				
				//
				if ( n.split('_for_mobile').length > 1 ) {
					str_mobile += n.replace('_for_mobile', '').replace(/\_/g, '-') + ':' + a + ';';
				}
				else {
					str += n.replace(/\_/g, '-') + ':' + a + ';';
				}
			}
		});
//		console.log(arr);
		
		//
		if ( str == '' && str_mobile == '' ) {
//			arr = '';
			arr = {};
		}
		else {
			if ( str != '' ) {
				str = cs['pc'] + '{' + str + '}';
				console.log(str);
			}
//			console.log(str);
			
			if ( str_mobile != '' ) {
				str_mobile = cs['mobile'] + '{' + str_mobile + '}';
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



//
//func_preview_cf_logo();
//fix_textarea_height();





/*
* Bật tắt chế độ lưu trữ dữ liệu qua JSON
*/
function click_on_off_eb_cf_json ( key, val ) {
	console.log(key);
	
	var str = '';
	
	if ( val == 1 ) {
		str = $('label[for="' + key + '"]').attr('data-off') || '';
	}
	else {
		str = $('label[for="' + key + '"]').attr('data-on') || '';
	}
	$('label[for="' + key + '"]').html( str );
}

function show_note_for_checkbox_config ( key ) {
	console.log(key);
	
	$('#' + key).click(function () {
		if ( dog(key).checked == true ) {
			$(this).val( 1 );
		} else {
//			$(this).val( 0 );
//			$(this).val( 'on' );
			$(this).val( 'off' );
		}
		
		//
		click_on_off_eb_cf_json( key, $(this).val() );
	});
	
	//
	if ( $('#' + key).val() == 1 ) {
		dog(key).checked = true;
	}
	
	click_on_off_eb_cf_json( key, $('#' + key).val() );
}





/*
* Định vị vị trí trụ sở chính của website
*/
function click_get_user_position ( ip_or_gps ) {
	var f = document.frm_config;
	
	//
	f.cf_region.value = '';
	f.cf_placename.value = '';
	f.cf_position.value = '';
//	f.cf_content_language.value = '';
	
	//
//	auto_get_user_position();
	if ( typeof ip_or_gps != 'undefined' && ip_or_gps == 'ip' ) {
		_global_js_eb.user_auto_loc( function ( data ) {
			WGR_after_load_user_location( data );
		});
	}
	else {
		auto_get_user_position(1);
	}
}


// config
if ( current_module_config != 'config_theme' ) {
	show_note_for_checkbox_config( 'cf_tester_mode' );
	show_note_for_checkbox_config( 'cf_debug_mode' );
	show_note_for_checkbox_config( 'cf_js_optimize' );
	show_note_for_checkbox_config( 'cf_css_optimize' );
	show_note_for_checkbox_config( 'cf_gtag_id' );
	show_note_for_checkbox_config( 'cf_disable_tracking' );
	show_note_for_checkbox_config( 'cf_on_off_json' );
	show_note_for_checkbox_config( 'cf_on_off_xmlrpc' );
	show_note_for_checkbox_config( 'cf_on_off_wpcron' );
	show_note_for_checkbox_config( 'cf_on_off_feed' );
	
	show_note_for_checkbox_config( 'cf_remove_category_base' );
	show_note_for_checkbox_config( 'cf_remove_post_option_base' );
	show_note_for_checkbox_config( 'cf_alow_post_option_index' );
	show_note_for_checkbox_config( 'cf_alow_post_tag_index' );
	
	show_note_for_checkbox_config( 'cf_on_off_echbay_seo' );
	show_note_for_checkbox_config( 'cf_on_off_echbay_logo' );
	
	show_note_for_checkbox_config( 'cf_on_off_amp_logo' );
	show_note_for_checkbox_config( 'cf_on_off_amp_category' );
	show_note_for_checkbox_config( 'cf_on_off_amp_product' );
	show_note_for_checkbox_config( 'cf_on_off_amp_blogs' );
	show_note_for_checkbox_config( 'cf_on_off_amp_blog' );
	show_note_for_checkbox_config( 'cf_on_off_auto_update_wp' );
	show_note_for_checkbox_config( 'cf_disable_auto_get_thumb' );
	show_note_for_checkbox_config( 'cf_set_link_for_h1' );
	show_note_for_checkbox_config( 'cf_h1_logo' );
	show_note_for_checkbox_config( 'cf_auto_nofollow' );
	show_note_for_checkbox_config( 'cf_set_mask_for_details' );
	show_note_for_checkbox_config( 'cf_current_price_before' );
	show_note_for_checkbox_config( 'cf_hide_supper_admin_menu' );
	show_note_for_checkbox_config( 'cf_alow_edit_plugin_theme' );
	show_note_for_checkbox_config( 'cf_alow_edit_theme_plugin' );
	show_note_for_checkbox_config( 'cf_set_news_version' );
	show_note_for_checkbox_config( 'cf_set_raovat_version' );
	show_note_for_checkbox_config( 'cf_remove_raovat_meta' );
	show_note_for_checkbox_config( 'cf_echbay_migrate_version' );
	show_note_for_checkbox_config( 'cf_search_advanced_auto_submit' );
	show_note_for_checkbox_config( 'cf_search_by_echbay' );
	show_note_for_checkbox_config( 'cf_global_big_banner' );
	show_note_for_checkbox_config( 'cf_arrow_big_banner' );
	show_note_for_checkbox_config( 'cf_swipe_big_banner' );
	show_note_for_checkbox_config( 'cf_auto_get_ads_size' );
	
	
	//
	$('select[name="cf_current_price"]').change(function () {
		var a = $(this).val() || '',
			new_a = 'VND';
		
		// tạo định dạng tiền tệ tương ứng
		if ( a == '' || a == 'vn/0111' || a == 'VN/00d0' ) {
		}
		else if ( a == '$' ) {
			new_a = 'USD';
		}
		// nhân dân tệ
		else if ( a == '/00A5' ) {
			new_a = 'CNY';
		}
		else {
			new_a = a;
		}
//		console.log( a );
//		console.log( new_a );
		
		$('#cf_current_sd_price_show, #cf_current_sd_price').val( new_a );
		
	});
	
	//
	(function () {
		var f = document.frm_config;
		
		//
		create_img_gg_map ( f.cf_position.value, 1 );
		
		//
		if ( f.cf_region.value == '' || f.cf_placename.value == '' || f.cf_position.value == '' ) {
			auto_get_user_position();
		}
	})();
	
	
	// thêm các thông số điều chỉnh CSS cho logo
	(function () {
		console.log('CUSTOM CSS FOR LOGO');
		
		// lấy mảng dữ liệu đã được gán sẵn
		var data = $('#cf_css_body').val() || '';
		if ( data == '' ) {
			data = {};
		}
		// chỉnh lại data nếu chưa có
		else {
//			console.log(data);
			data = unescape( data );
//			console.log(data);
			data = eval( '[' + data + ']' );
			data = data[0];
//			console.log(data);
			
			// nếu không có mảng dữ liệu -> vẫn là mảng cũ -> loại luôn
			if ( typeof data['cf_css_body'] == 'undefined' ) {
				data = {
					'cf_css_body' : data
				};
			}
		}
		
		// gán dữ liệu nếu chưa có
		if ( typeof data['cf_css_mobile_logo'] == 'undefined' ) {
			data['cf_css_mobile_logo'] = {};
		}
		if ( typeof data['cf_css_logo'] == 'undefined' ) {
			data['cf_css_logo'] = {};
		}
		if ( typeof data['cf_css_w90'] == 'undefined' ) {
			data['cf_css_w90'] = {};
		}
		if ( typeof data['cf_css_body'] == 'undefined' ) {
			data['cf_css_body'] = {};
		}
		if ( typeof data['cf_css_details'] == 'undefined' ) {
			data['cf_css_details'] = {};
		}
//		console.log(data);
		
		
		
		// LOGO
		var arr = {
			'background_position' : {
				'' : 'Mặc định (theo thiết kế của tác giả)',
				'top left' : 'Góc trên bên trái',
				'top center' : 'Góc trên ở giữa',
				'top right' : 'Góc trên bên phải',
				'center left' : 'Giữa bên trái',
				'center center' : 'Chính giữa',
				'center right' : 'Giữa bên phải',
				'bottom left' : 'Phía dưới Bên trái',
				'bottom center' : 'Phía dưới ở giữa',
				'bottom right' : 'Góc phải phía dưới'
			},
			'background_size' : {
				'' : 'Mặc định (theo thiết kế của tác giả)',
				'cover' : 'Bao phủ',
				'contain' : 'Contain',
				'auto' : 'Tự động',
				'auto 95%' : 'Rộng (tự động) x Cao (95%)',
				'auto 90%' : 'Rộng (tự động) x Cao (90%)',
				'auto 85%' : 'Rộng (tự động) x Cao (85%)',
				'auto 80%' : 'Rộng (tự động) x Cao (80%)',
				'auto 75%' : 'Rộng (tự động) x Cao (75%)',
				'auto 70%' : 'Rộng (tự động) x Cao (70%)',
				'auto 65%' : 'Rộng (tự động) x Cao (65%)',
				'auto 60%' : 'Rộng (tự động) x Cao (60%)',
				'auto 55%' : 'Rộng (tự động) x Cao (55%)',
				'auto 50%' : 'Rộng (tự động) x Cao (50%)',
				'95% auto' : 'Rộng (95%) x Cao (tự động)',
				'90% auto' : 'Rộng (90%) x Cao (tự động)',
				'85% auto' : 'Rộng (85%) x Cao (tự động)',
				'80% auto' : 'Rộng (80%) x Cao (tự động)',
				'75% auto' : 'Rộng (75%) x Cao (tự động)',
				'70% auto' : 'Rộng (70%) x Cao (tự động)',
				'65% auto' : 'Rộng (65%) x Cao (tự động)',
				'60% auto' : 'Rộng (60%) x Cao (tự động)',
				'55% auto' : 'Rộng (55%) x Cao (tự động)',
				'50% auto' : 'Rộng (50%) x Cao (tự động)'
			}
		};
		
		// thêm dữ liệu cho bản mobile
		arr['background_position_for_mobile'] = arr['background_position'];
		arr['background_size_for_mobile'] = arr['background_size'];
		
		//
		load_config_for_custom_logo(
			arr,
			{
				'background_position' : 'Vị trí',
				'background_size' : 'Kích thước',
				'background_position_for_mobile' : 'Vị trí (mobile)',
				'background_size_for_mobile' : 'Kích thước (mobile)'
			}, {
				'background_position' : 'Căn vị trí hình ảnh sẽ xuất hiện trên khung của logo',
				'background_size' : 'Với mỗi logo, sẽ có tỷ lệ giữa chiều rộng với chiều cao khác nhau, dùng thuộc tính này để định hành tránh bị vỡ khung logo',
				'background_position_for_mobile' : 'Sử dụng khi bạn muốn logo trên bản mobile ở một vị trí khác so với bản desktop',
				'background_size_for_mobile' : 'Sử dụng khi bạn muốn logo trên bản mobile có kích thước khác so với bản desktop'
			},
			{},
//			a = $('#cf_css_logo').val() || '',
			data['cf_css_logo'],
			{
//				'input_css' : 'each-to-css-for-logo',
//				'after_html' : 'custom_css_for_logo',
				'input_name' : 'logo'
			}
		);
		
		
		// Mobile Logo
		load_config_for_custom_logo(
			{
				'background_image' : ''
			},
			{
				'background_image' : 'Logo (mobile)'
			}, {
				'background_image' : 'Sử dụng khi bạn muốn logo trên bản mobile khác với Logo bản desktop. Copy và nhập URL ảnh vào đây là được, tính năng này chỉ hỗ trợ các phiên bản theme có sử dụng logo trên mobile.'
			},
			{},
			data['cf_css_mobile_logo'],
			{
//				'input_css' : 'each-to-css-for-logo',
				'after_html' : 'custom_css_for_logo',
				'input_name' : 'mobile_logo'
			}, {
				'maxlength' : {
					'background_image' : 255
				}
			}
		);
		
		
		
		// w90
		load_config_for_custom_logo(
			{
//				'max_width' : '',
				'max_width' : {
					'' : 'Mặc định (HD 1366px)',
					'1024px' : 'XGA 1024px',
					'1280px' : 'WXGA 1280px',
//					'1366px' : 'HD 1366px',
					'1600px' : 'HD+ 1600px',
					'1920px' : 'Full HD 1920px',
					'2560px' : 'WQHD 2560px'
				}
			}, {
				'max_width' : 'Chiều rộng tối đa (khung)'
			}, {
				'max_width' : 'Bạn có thể thiết lập chiều rộng tối đa cho khung, điều này giúp cho các khối HTML trong đó giữ được tính ổn định cao.'
			}, {
				'max_width' : 'number'
			},
			data['cf_css_w90'],
			{
//				'input_css' : 'each-to-css-for-w90',
				'after_html' : 'custom_css_for_body',
				'input_name' : 'w90'
			}
		);
		
		
		
		// BODY
		load_config_for_custom_logo(
			{
//				'max_width' : '',
				'max_width' : {
					'' : 'Mặc định (HD+ 1600px)',
					'1024px' : 'XGA 1024px',
					'1280px' : 'WXGA 1280px',
					'1366px' : 'HD 1366px',
//					'1600px' : 'HD+ 1600px',
					'1920px' : 'Full HD 1920px',
					'2560px' : 'WQHD 2560px'
				},
//				'font_size' : '',
				'font_size' : {
					'' : 'Mặc định (13px)',
					'10px' : '10px',
					'11px' : '11px',
					'12px' : '12px',
					'14px' : '14px',
					'15px' : '15px',
					'16px' : '16px',
					'17px' : '17px',
					'18px' : '18px',
					'19px' : '19px',
					'20px' : '20px',
					'21px' : '21px',
					'22px' : '22px',
					'23px' : '23px',
					'24px' : '24px',
					'25px' : '25px',
					'26px' : '26px',
					'27px' : '27px',
					'28px' : '28px',
					'29px' : '29px',
					'30px' : '30px'
				},
				'font_family' : ''
			}, {
				'max_width' : 'Chiều rộng tối đa (BODY)',
				'font_size' : 'Cỡ chữ mặc định',
				'font_family' : 'Phông chữ'
			}, {
//				'max_width' : 'Đây là kích thước lớn nhất của website mà bạn muốn đạt tới, thông thường thì ngưỡng phổ biến là HD 1366px, với các kích thước lớn hơn thì chi phí thiết kế cũng sẽ đắt đỏ hơn. Gợi ý một số kích thước phổ biến khác mà bạn có thể chọn: HD+ <strong>1600</strong>, Full HD <strong>1920</strong>, QWXGA <strong>2048</strong>, WQHD <strong>2560</strong>.',
				'max_width' : 'Đây là kích thước lớn nhất của website mà bạn muốn đạt tới, thông thường thì ngưỡng phổ biến là HD 1366px, với các kích thước lớn hơn thì chi phí thiết kế cũng sẽ đắt đỏ hơn.',
				'font_size' : 'Mặc định sẽ sử dụng cỡ chữ tiêu chuẩn là <strong>13px</strong> (~10pt), bạn có thể thay đổi kích thước này tại đây',
				'font_family' : 'Phông chữ mặc định là <strong>\'Helvetica Neue\', Helvetica, Arial, Tahoma, Verdana, sans-serif</strong>, trường hợp bạn muốn sử dụng phông chữ khác, hãy nhập tại đây. <br><span class="orgcolor">* Lưu ý là các font chữ bạn chọn nếu không phải font phổ biến thì cần nhúng font vào website. Khuyên dùng: <a href="https:\/\/fonts.google.com\/" rel="nofollow" target="_blank">https:\/\/fonts.google.com\/</a></span>'
			}, {
				'max_width' : 'number'
			},
//			$('#cf_css_body').val() || '',
			data['cf_css_body'],
			{
//				'input_css' : 'each-to-css-for-body',
//				'after_html' : 'custom_css_for_body',
				'input_name' : 'body'
			}
		);
		
		
		
		// Details
		load_config_for_custom_logo(
			{
				'background_color' : ''
			}, {
				'background_color' : 'Màu tab trang chi tiết'
			}, {
				'background_color' : 'Màu nền tab nội dung trong trang chi tiết. Mã màu gồm dấu # và 3 hoặc 6 ký tự đại diện đằng sau.'
			}, {
//				'background_color' : 'color'
			},
			data['cf_css_details'],
			{
//				'input_css' : 'each-to-css-for-details',
//				'after_html' : 'custom_css_for_details',
				'input_name' : 'details'
			}, {
				'maxlength' : {
					'background_color' : 7
				}
			}
		);
		
	})();
	
}
// config_theme
else {
	show_note_for_checkbox_config( 'cf_list_sidebar_new_style' );
	
	show_note_for_checkbox_config( 'cf_details_content_slider' );
	show_note_for_checkbox_config( 'cf_details_show_list_next' );
	show_note_for_checkbox_config( 'cf_details_show_list_thumb' );
	show_note_for_checkbox_config( 'cf_details_right_thumbnail' );
	show_note_for_checkbox_config( 'cf_details_left_thumbnail' );
	show_note_for_checkbox_config( 'cf_details_show_quick_cart' );
	show_note_for_checkbox_config( 'cf_details_short_quick_cart' );
	show_note_for_checkbox_config( 'cf_required_name_cart' );
	show_note_for_checkbox_config( 'cf_required_email_cart' );
	show_note_for_checkbox_config( 'cf_required_address_cart' );
	show_note_for_checkbox_config( 'cf_details_excerpt' );
	show_note_for_checkbox_config( 'cf_details_bold_excerpt' );
	show_note_for_checkbox_config( 'cf_options_excerpt' );
	show_note_for_checkbox_config( 'cf_details_ul_options' );
	show_note_for_checkbox_config( 'cf_post_big_banner' );
	show_note_for_checkbox_config( 'cf_post_rm_img_width' );
	show_note_for_checkbox_config( 'cf_blog_rm_img_width' );
	show_note_for_checkbox_config( 'cf_blogs_content_bottom' );
}





//
$('input[name=cf_position]').off('change').change(function () {
	var a = $(this).val() || '';
	
	if ( a != '' ) {
		// chuẩn hóa dữ liệu đầu vào
		
		// nếu người dùng nhập từ url
		a = a.split( '@' );
		if ( a.length > 1 ) {
			a = a[1];
		}
		else {
			a = a[0];
		}
		
		//
		a = a.replace( ';', ',' );
		
		// chỉ lấy 2 tọa độ
		a = a.split( ',' );
		if ( a.length > 1 ) {
			a = a[0] + ',' + a[1];
			
			create_img_gg_map ( a, 1 );
		}
		// dữ liệu sai -> xóa trắng
		else {
			a = '';
		}
		
		//
		$('input[name=cf_position]').val( a );
	}
});





//
/*
$('#oi_smtp_pass').off('focus').focus(function () {
	$(this).attr({
		type : 'password'
	}).val( $('input[name=cf_smtp_pass]').val() );
}).off('blur').blur(function () {
	var a = $(this).val() || '',
		str = '';
	
	if ( a != '' ) {
		for ( var i = 0; i < a.length; i++ ) str += '*';
	}
	
	//
	$(this).attr({
		type : 'text'
	}).val( str );
}).off('keyup').keyup(function () {
	$('input[name=cf_smtp_pass]').val( $(this).val() || '' );
}).blur();
*/



//
var smtp_random_pass = Math.random().toString(32).split('.')[1];


//
$('#oi_smtp_pass')
.off('focus').focus(function () {
/*
	$(this)
//	.attr({
//		type : 'password'
//	})
	.val( $('input[name=cf_smtp_pass]').val() );
})
.off('dblclick').dblclick(function () {
*/
//	console.log(smtp_random_pass);
	$(this).val( smtp_random_pass + $('input[name=cf_smtp_pass]').val() );
})
.off('blur').blur(function () {
	var a = $(this).val() || '',
		str = '';
	
	if ( a != '' ) {
		for ( var i = 0; i < a.length; i++ ) str += '*';
	}
	
	//
	$(this).attr({
		type : 'text'
	}).val( str );
})
.off('keyup').keyup(function () {
	var a = $(this).val() || '';
	if ( a != '' ) {
		a = a.replace( smtp_random_pass, '' );
	}
	
	$('input[name=cf_smtp_pass]').val( a );
})
.blur();






//
var add_class_bg_for_tr_support = false;

(function () {
	
	// hiển thị module config tương ứng
	$('.show-if-module-' + current_module_config).show();
	
	//
	var str = '';
	// eb-admin-config-tab
	// eb-admin-config_theme-tab
	$('.eb-admin-' + current_module_config + '-tab').each(function() {
		var i = $(this).attr('id') || '',
			tit = $(this).attr('title') || '';
		
		str += '<li data-id="' +i+ '" data-tab="' + i.replace('eb-admin-config-', '') + '">' + tit + '</li>';
	});
	
	$('#list-tab-eb-admin-config').html( str );
	
	$('#list-tab-eb-admin-config li').click(function () {
		var a = $(this).attr('data-id') || '',
			tab = $(this).attr('data-tab') || '';
		if ( a != '' ) {
			$('.eb-admin-hide-config-tab').hide();
			$('#' + a).show();
			
			//
			$('#list-tab-eb-admin-config li').removeClass('selected');
			$(this).addClass('selected');
			
			//
			_global_js_eb.change_url_tab( 'tab', tab );
//			window.history.pushState( "", '', window.location.href.split('&tab=')[0] + '&tab=' + tab );
		}
	});
	
	var a = window.location.href.split('&tab=');
	if ( a.length > 1 ) {
		a = a[1].split('&')[0].split('#')[0];
		console.log('Config tab: ' + a);
		
		$('.eb-admin-tab a[data-tab="' +a+ '"]').addClass('selected');
		$('#list-tab-eb-admin-config li[data-tab="' +a+ '"]').click();
	} else {
		$('#list-tab-eb-admin-config li:first').click();
	}
	
	
	
	
	// bổ sung các thẻ label nếu chưa có -> do code trước đó cái có cái không
	$('.eb-support-table tr').each(function() {
		// nếu không có label
		if ( $('label', this).length == 0 ) {
			// kiểm tra xem có input hay select nào không
			var get_input = '';
			
			// phải có từ 2 td trở lên và thẻ td đầu tiên chỉ được chứa chữ, không chứa các thứ như: input, select...
			if ( $('td', this).length > 1 && $('td:first input', this).length == 0 && $('td:first textarea', this).length == 0 && $('td:first select', this).length == 0 ) {
				// xác định từng thể loại input
				if ( $('input[type="number"]', this).length > 0 ) {
					get_input = 'input[type="number"]';
				}
				else if ( $('input[type="email"]', this).length > 0 ) {
					get_input = 'input[type="email"]';
				}
				else if ( $('textarea', this).length > 0 ) {
					get_input = 'textarea';
				}
				else if ( $('select', this).length > 0 ) {
					get_input = 'select';
				}
				// input text ưu tiên cuối cùng
				else if ( $('input[type="text"]', this).length > 0 ) {
					get_input = 'input[type="text"]';
				}
			}
			
			//
			if ( get_input != '' ) {
				var a = $('td:first', this).html() || '';
				console.log('Auto create label for: ' + a);
				
				//
				if ( a != '' ) {
					// lấy ID để label focus tới
					var label_for = $(get_input, this).attr('id') || '';
					// nếu không có -> lấy theo tên
					if ( label_for == '' ) {
						label_for = $(get_input, this).attr('name') || '';
						
						// tạo ID để label focus
						if ( label_for != '' ) {
							$(get_input, this).attr({
								id: label_for
							});
						}
					}
					
					if ( label_for != '' ) {
						$('td:first', this).html( '<label for="' + label_for + '">' + a + '</label>' );
					}
				}
			}
		}
	});
	
	// khi người dùng bấm vào thẻ TR trong khung có hỗ trợ support
	$('.eb-support-table label').click(function () {
		add_class_bg_for_tr_support = true;
		
		$('.eb-support-table tr').removeClass('current-selected-support');
		
		//
		var a = $(this).attr('for') || $(this).attr('data-tab') || '';
		
		// thêm hash nếu có
		/*
		if ( a != '' ) {
//			window.location.hash = a;
//			window.history.pushState("", '', window.location.href.split('#')[0] + '#' + a);
			window.history.pushState("", '', window.location.href.split('&support_tab=')[0] + '&support_tab=' + a);
		}
		// dọn hash nếu không có
		else {
//			window.history.pushState("", '', window.location.href.split('#')[0]);
			window.history.pushState("", '', window.location.href.split('&support_tab=')[0]);
		}
		*/
		
		//
		_global_js_eb.change_url_tab( 'support_tab', a );
	});
	
	// tự động trỏ đến TR đang cần support
	setTimeout(function () {
		if ( add_class_bg_for_tr_support == false ) {
			var get_support_tab = window.location.href.split('&support_tab=');
			if ( get_support_tab.length > 1 && $('.eb-support-table').length > 0 ) {
				get_support_tab = get_support_tab[1].split('&')[0].split('#')[0];
				console.log(get_support_tab);
				
				// chạy và tìm thẻ TR có chứa cái thẻ label này
				if ( get_support_tab != '' && $('label[for="' + get_support_tab + '"]').length > 0 ) {
					
					// cuộn chuột đến khu vực cần xem -> xem cho dễ
					window.scroll( 0, $('label[for="' + get_support_tab + '"]').offset().top - ( $(window).height()/ 3 ) );
					
					$('.eb-support-table tr').each(function() {
						// nếu tìm được thì thoát thôi
						if ( $('label[for="' + get_support_tab + '"]', this).length > 0 ) {
							$(this).addClass('current-selected-support');
							return true;
						}
					});
					
				}
			}
		}
	}, 1200);
	
	
})();



//
eb_drop_menu('oi_select_timezone', cf_timezone);






//
/*
$('#cf_sys_email').click(function () {
	if ( dog('cf_sys_email').checked == true ) {
		$(this).val( 1 );
	} else {
		$(this).val( 0 );
	}
});

//
if ( $('#cf_sys_email').val() == 1 ) {
	dog('cf_sys_email').checked = true;
}
*/





//
convert_size_to_one_format();




//
$('.click-to-set-site-color').click(function () {
	var a = $(this).attr('data-set') || '';
	
	if ( a == '' ) {
		console.log('Color picker not found');
		return false;
	}
	
	var b = $('input[name="' + a + '"]').val() || $('input[name="' + a + '"]').attr('placeholder') || '';
	var n = prompt('Color code #:', b);
//	console.log(n);
	
	// cho về mã hiện tại nếu người dùng hủy hoặc không nhập màu
	if ( n == null || n == '' ) {
		n = b;
	}
	n = g_func.trim( n.replace( /\s/g, '' ) );
	if ( n == '' ) {
		n = b;
	}
	
	// bỏ dấu # ở đầu đi để định dạng lại
	if ( n.substr(0, 1) == '#' ) {
		n = n.substr(1);
	}
	
	// tự chuyển thành mã 6 màu nếu mã màu nhập vào là 3
	if ( n.length == 3 ) {
		n = n.substr(0, 1) + n.substr(0, 1) + n.substr(1, 1) + n.substr(1, 1) + n.substr(2, 1) + n.substr(2, 1);
	}
	
	// đến đây, mã màu bắt buộc phải là 6 ký tự
	if ( n.length != 6 ) {
		alert('Color code with 6 character');
		return false;
	}
	
	// done
	$('input[name="' + a + '"]').val( '#' + n );
});

$('.click-to-reset-site-color').click(function () {
	var a = $(this).attr('data-set') || '';
	
	if ( a == '' ) {
		console.log('Color picker not found');
		return false;
	}
	
	var b = $('input[name="' + a + '"]').attr('placeholder') || '';
	if ( b != '' ) {
		$('input[name="' + a + '"]').val( b );
	}
});





// các hàm chạy sau một chút
if ( current_module_config != 'config_theme' ) {
	
	$('.click-add-url-for-logo').click(function () {
		var a = $(this).attr('data-id') || '';
		if ( a != '' ) {
			var b = prompt('URL image:', $('#' + a).val() || '');
			if ( b != null && b != '' ) {
				EBA_add_img_logo( b, a );
			}
		}
	});
	
	//
	setTimeout(function () {
		EBA_preview_img_logo( dog('cf_logo').value, 'cf_logo' );
		EBA_preview_img_logo( dog('cf_favicon').value, 'cf_favicon' );
	}, 600);
	
	
	// Tạo danh sách các file CSS mẫu -> bấm add là được
//	$('#list_template_css').html('aaaaaaa');
}


