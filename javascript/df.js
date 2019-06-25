/*
* d function
*/





//
var product_selected_url = '',
	eb_this_current_url = jQuery('link[rel="canonical"]:first').attr('href') || window.location.href.split('?')[0].split('&')[0].split('#')[0],
	size_rieng_cua_tung_mau = '',
	// nếu size đã được load rồi thì thôi không load lại
	size_has_been_load = false,
	price_by_color = 0,
	price_for_quick_cart = 0,
	global_window_width = jQuery(window).width();
//console.log(eb_this_current_url);
if ( eb_this_current_url == web_link ) {
	eb_this_current_url = window.location.href.split('#')[0];
}



//
if ( act != '' && jQuery('body.home').length > 0 ) {
	act = '';
}




function ___eb_set_thumb_to_fullsize ( s ) {
	if ( typeof s == 'undefined' || s == '' ) {
		return '';
	}
//	console.log(s);
	
	//
	if ( s.split(wp_content + '/uploads/').length > 1 ) {
		var t = s.split('-');
		t = t[ t.length - 1 ];
//		console.log( t );
		
		if ( t.split('.').length == 2 ) {
			t = t.split('.')[0].split('x');
			
			if ( t.length == 2 ) {
				var re = /^\d+$/;
				
				// nếu đang là thumbnail hoặc ảnh thu nhỏ -> thì mới cần chuyển sang ảnh to
				if ( re.test( t[ 0 ] ) == true
				&& re.test( t[ 1 ] ) == true ) {
					s = s.replace( '-' + t[ 0 ] + 'x' + t[ 1 ] + '.', '.' );
				}
			}
		}
	}
//	console.log(s);
	
	// bỏ thumb đi
	return s;
}

function WGR_get_thumb_in_srcset ( srcset ) {
	// tìm thumbnail trong srcset
	srcset = srcset.split( ' 150w,' );
	
	// có thì trả về luôn
	if ( srcset.length > 1 ) {
		srcset = srcset[0].split(',');
		return jQuery.trim( srcset[ srcset.length - 1 ] );
	}
	
	return '';
}

function ___eb_set_img_to_thumbnail ( sr, srcset ) {
	if ( typeof sr == 'undefined' || sr == '' ) {
		return '';
	}
//	console.log( sr );
	
	// nếu có tham số này -> site không sử dụng thumb hoặc không có thumb
	// cf_disable_auto_get_thumb
	if ( typeof eb_disable_auto_get_thumb == 'number' && WGR_check_option_on ( eb_disable_auto_get_thumb ) ) {
		
		// ưu tiên lấy theo srcset -> có thumb thì vẫn dùng thumb như thường
		if ( typeof srcset != 'undefined' && srcset != '' ) {
			srcset = WGR_get_thumb_in_srcset( srcset );
			if ( srcset != '' ) {
				return srcset;
			}
		}
		
		//
		if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('Auto get thumbnail disable');
	}
	// lấy thumb để làm ảnh slider -> load cho nhanh
	else if ( sr.split(wp_content + '/uploads/').length > 1 ) {
		
		// ưu tiên lấy theo srcset
		if ( typeof srcset != 'undefined' && srcset != '' ) {
			srcset = WGR_get_thumb_in_srcset( srcset );
			if ( srcset != '' ) {
				return srcset;
			}
		}
		
		
		// cắt lấy chuỗi cuối cùng của ảnh để kiểm tra xem có phải thumb hay không
		var file_name = sr.split('/');
		file_name = file_name[ file_name.length - 1 ];
//		console.log( file_name );
		
//		var is_thumb = sr.split('/').pop().split('-').pop().split('.')[0];
		var is_thumb = file_name.split('-');
		is_thumb = is_thumb[ is_thumb.length - 1 ];
//		console.log( is_thumb );
		
		//
		if ( is_thumb.split('.').length == 2 ) {
			var file_type = file_name.split('.');
			file_type = file_type[ file_type.length - 1 ];
//			console.log( file_type );
			
			var thumbnail = '-150x150.' + file_type;
//			console.log( thumbnail );
			
			is_thumb = is_thumb.split('.')[0];
//			console.log( is_thumb );
			
			// có chữ x -> có thể là thumb -> xóa cái đoạn đó đi -> có thể gây lỗi nếu đó là tên file ảnh =))
			if ( is_thumb.split('x').length > 1 ) {
				var re = /^\d+$/;
				is_thumb = is_thumb.split('x');
				
				// nếu đang là thumbnail hoặc ảnh thu nhỏ
				if ( re.test( is_thumb[ is_thumb.length - 2 ] ) == true
				&& re.test( is_thumb[ is_thumb.length - 1 ] ) == true ) {
//					console.log( is_thumb[ is_thumb.length - 2 ] );
//					console.log( is_thumb[ is_thumb.length - 1 ] );
					
					sr = sr.replace( '-' + is_thumb[ is_thumb.length - 2 ] + 'x' + is_thumb[ is_thumb.length - 1 ] + '.' + file_type, thumbnail );
				}
				// nếu không phải thumbnail -> tạo thumbnail luôn
				else {
					sr = sr.replace( '.' + file_type, thumbnail );
				}
			}
			// nếu không có chữ x -> không phải thumb
			else {
//			if ( is_thumb.split('x').length != 2 ) {
				// -> thêm thumb
//				var img_type = sr.split('.').pop();
				
				sr = sr.replace( '.' + file_type, thumbnail );
			}
		}
	}
//	console.log( sr );
	
	return sr;
}





function WGR_fixed_tab_height_in_line ( jd, for_tag, max_height ) {
	// tab sẽ chịu ảnh hưởng, thường là thẻ A hoặc LI
	if ( typeof for_tag == 'undefined' || for_tag == '' ) {
		for_tag = 'a';
	}
	
	// chiều cao dùng để so sánh, chiều cao menu phổ thông là 30px, gấp đôi lên sẽ hơn 40px
//	if ( typeof max_height == 'undefined' ) {
//		max_height = 40;
//	}
	
	// Chiều cao định vị cho tab
	var min_tab_height = jQuery( jd ).attr('data-max-height') || '',
		tag = jQuery( jd ).attr('data-tag') || for_tag;
	
	//
	if ( min_tab_height == '' ) {
		min_tab_height = jQuery( jd + ' li:first' ).height() || '';
		
		//
		if ( min_tab_height == '' ) {
			console.log( 'Not get height of LI in ' + jd );
			return false;
		}
		// gấp rưỡi chiều cao để xử lý dễ hơn chút
		else {
			min_tab_height *= 1.5;
			min_tab_height = Math.ceil( min_tab_height );
		}
	}
	
	//
	if ( WGR_check_option_on ( cf_tester_mode ) ) console.log( 'Fixed data height (max ' + min_tab_height + 'px) for ' + jd );
	
//	console.log( jQuery('.thread-details-tab').height() );
	if ( jQuery( jd ).height() > min_tab_height ) {
		var j = 30;
		for ( var i = 0; i < 28; i++ ) {
			jQuery( jd + ' ' + tag ).css({
//				padding: '0 ' +j+ 'px',
				'padding-left' : j + 'px',
				'padding-right' : j + 'px'
			});
			
			//
//			console.log( jQuery( jd ).height() );
			if ( jQuery( jd ).height() < min_tab_height ) {
				break;
			}
			
			//
			j--;
		}
	}
	
	// nếu vẫn chưa được -> màn hình có thể còn nhỏ hơn nữa -> tiếp tục thu font-size
	if ( jQuery( jd ).height() > min_tab_height ) {
		var j = 17;
		for ( var i = 0; i < 5; i++ ) {
			jQuery( jd + ' ' + tag ).css({
				'font-size' : j+ 'px'
			});
			
			//
//			console.log( jQuery( jd ).height() );
			if ( jQuery( jd ).height() < min_tab_height ) {
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

function ___eb_big_banner () {
	console.log('WARNING! Function ___eb_big_banner bas been remove, please clear in your code!');
}

function ___eb_logo_doitac_chantrang ( so_the_li_mong_muon, li_fo_scroll ) {
	console.log('WARNING! Function ___eb_logo_doitac_chantrang bas been remove, please clear in your code!');
}



// tạo hiệu ứng với phần danh sách sản phẩm
function ___eb_thread_list_li () {
	jQuery('.thread-list li').each(function() {
		var ngay = jQuery(this).attr('data-ngay') || 0,
	//		giacu = jQuery(this).attr('data-gia') || '',
	//		giamoi = jQuery(this).attr('data-giamoi') || '',
	//		a = jQuery(this).attr('data-giovang') || '',
	//		b = jQuery(this).attr('data-soluong') || 0,
	//		gia = jQuery(this).attr('data-gia') || 0,
			per = jQuery(this).attr('data-per') || 0;
		
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
				jQuery(this).addClass('thread-list-giovangshow');
			}
		}
	});
	
	
	//
//	jQuery('.hide-if-gia-zero[data-per="0"]').addClass('aaaaaaaaa');
}


// thêm dòng phân cách cho thẻ breadcrumb
function ___eb_add_space_for_breadcrumb ( con ) {
	console.log('___eb_add_space_for_breadcrumb has been remove');
	
	/*
	if ( typeof con == 'undefined' ) {
		con = '/';
	}
	
	jQuery('.thread-details-tohome li').before('<li>' + con + '</li>');
	jQuery('.thread-details-tohome li:first').remove();
	*/
}

function WGR_active_popup ( op ) {
	if ( WGR_check_option_on ( cf_tester_mode ) ) console.log(op);
	
	// kiểm tra và tạo các giá trị mặc định
	if ( typeof op['id_event'] == 'undefined' || op['id_event'] == '' ) {
		console.log('id_event not found!');
		return false;
	}
	if ( op['id_event'].substr( 0, 1 ) != '#' && op['id_event'].substr( 0, 1 ) != '.' ) {
		op['id_event'] = '#' + op['id_event'];
	}
	
	if ( typeof op['cookie_name'] == 'undefined' || op['cookie_name'] == '' ) {
		op['cookie_name'] = 'WGR_popup_ckname_' + op['id_event'].replace(/\#|\./g, '_');
	}
	if ( typeof op['cookie_time'] != 'number' || op['cookie_time'] * 1 < 0 ) {
		op['cookie_time'] = 0;
	}
	if ( typeof op['cookie_time2'] != 'number' || op['cookie_time2'] * 1 < 0 ) {
		op['cookie_time2'] = 0;
	}
	// nếu không có thời gian xác định cho cookie -> hủy bỏ luôn
	if ( op['cookie_time'] == 0 && op['cookie_time2'] == 0 ) {
		console.log('time not set!');
		return false;
	}
	
	if ( typeof op['time_start'] != 'number' || op['time_start'] * 1 < 0 ) {
		op['time_start'] = 5;
	}
	if ( typeof op['time_end'] != 'number' || op['time_end'] * 1 < 0 ) {
		op['time_end'] = 30;
	}
	if ( WGR_check_option_on ( cf_tester_mode ) ) console.log(op);
	
	//
	var a = g_func.getc( op['cookie_name'] );
    if (a != null) {
		console.log('Popup hide by WGR_active_popup');
		return false;
	}
	
	// bật popup lên
	if ( op['time_start'] > 0 ) {
		setTimeout(function () {
			jQuery( op['id_event'] ).fadeIn();
		}, op['time_start'] * 1000);
	}
	else {
		jQuery( op['id_event'] ).fadeIn();
	}
	
	// tắt popup
	if ( op['time_end'] > 0 ) {
		setTimeout(function () {
			jQuery( op['id_event'] ).fadeOut();
		}, ( op['time_start'] + op['time_end'] ) * 1000);
	}
	
	if ( typeof op['close_icon'] != 'undefined' && op['close_icon'] != '' ) {
		jQuery( op['id_event'] ).prepend('<div class="wgr-popup-close" onclick="jQuery(' + op['id_event'] + ').hide();"><i class="fa ' + op['id_event'] + '"></i></div>');
	}
	
	//
	g_func.setc( op['cookie_name'], 1, op['cookie_time'] * 60, op['cookie_time2'] );
}

function WGR_close_popup () {
	close_img_quick_video_details();
}

function close_img_quick_video_details () {
	// ẩn video
	jQuery('.quick-video').hide().height('auto');
	
	// xóa nội dung -> tránh video vẫn đang bật
	dog( 'quick-video-content', '&nbsp;' );
	
	//
	jQuery('#click_show_cpa, .hide-if-esc').hide();
	
	//
	jQuery('body').removeClass('body-no-scroll');
	
	window.history.pushState("", '', current_ls_url);
	
}

function ___eb_click_open_video_popup () {
	jQuery('.click-quick-view-video').each(function() {
		var a = jQuery(this).attr('data-video') || '',
			lnk = jQuery(this).attr('href') || '',
			module = jQuery(this).attr('data-module') || '';
		if ( module == '' ) {
			jQuery(this).attr({
				'data-module': 'video_no_group'
			});
		}
		
		// lấy URL để tạo ID cho youtube nếu không có ID
		if ( a == '' && lnk != '' ) {
			a = _global_js_eb.youtube_id( lnk );
//			if ( WGR_check_option_on ( cf_tester_mode ) ) console.log( lnk );
			if ( a != '' ) {
//				if ( WGR_check_option_on ( cf_tester_mode ) ) console.log( a );
				jQuery(this).attr({
					'data-video': '//www.youtube.com/embed/' + a
				});
			}
		}
	}).off('click').click(function () {
//		alert(1);
		var a = jQuery(this).attr('data-video') || '',
			tit = jQuery(this).attr('title') || '',
			lnk = jQuery(this).attr('href') || '',
			// nhóm các video liên quan theo module
			module = jQuery(this).attr('data-module') || '',
			str = '',
			arr_list_video = {};
		
		//
		if ( a != '' ) {
			
			//
			jQuery('.quick-video').show().height( jQuery(document).height() );
//			jQuery('body').addClass('body-scroll-disable');
			
			//
			var wit = jQuery('#quick-video-content').width(),
				hai = jQuery(window).height(),
				wit_hai = 0;
			
			// xem đang là màn ngang hay dọc
			if ( hai > wit ) {
				wit_hai = wit;
				wit = hai;
				hai = wit_hai;
			}
//			console.log( wit );
//			console.log( hai );
			
			// chiều cao video -> không để hết màn hình là đẹp
			hai = Math.ceil( hai/ 100 * 90 );
//			console.log( hai );
			
			//
			wit = Math.ceil( hai/ youtube_video_default_size );
//			console.log( wit );
			
			if ( wit > 1024 ) {
				wit = 1024;
				hai = Math.ceil( wit * youtube_video_default_size );
			}
			
			/*
			if ( hai > 600 ) {
				hai = 400;
			} else {
				hai = hai / 3 * 2;
			}
			*/
			
			//
			a = _global_js_eb.youtube_id( a );
//			alert(a);
			
			//
			if ( lnk == '' || lnk == 'javascript:;' ) {
			} else {
				// chỉ link nội bộ mới sử dụng chức năng này
				if ( lnk.split('//').length == 1 || lnk.split(web_link).length > 1 ) {
					window.history.pushState("", '', lnk);
				}
			}
			
			//
			str += '' +
			'<div class="quick-video-node">' +
				'<div class="quick-video-width" style="width:' + wit + 'px">' +
					'<div class="quick-video-title bold">' +tit+ '</div>' +
					'<iframe width="' + wit + '" height="' +hai+ '" src="//www.youtube.com/embed/' +a+ '?autoplay=1" frameborder="0" allowfullscreen></iframe>' +
				'</div>' +
			'</div>';
			
			
			
			// Tạo list video -> Lấy các video khác trên cùng trang
			var get_other_video = '.click-quick-view-video';
			if ( module != '' ) {
				if ( WGR_check_option_on ( cf_tester_mode ) ) console.log(module);
				get_other_video = '.click-quick-view-video[data-module="' +module+ '"]';
			}
			if ( WGR_check_option_on ( cf_tester_mode ) ) {
				console.log(get_other_video);
				console.log(jQuery(get_other_video).length);
			}
			
			//
			jQuery(get_other_video).each(function () {
				var a2 = jQuery(this).attr('data-video') || '',
					tit2 = jQuery(this).attr('title') || '';
				
				//
				if ( a2 != '' ) {
					a2 = _global_js_eb.youtube_id( a2 );
					
					//
					if ( a2 != a ) {
						arr_list_video[a2] = tit2;
					}
				}
			});
//			console.log( arr_list_video );
			
			//
			for ( var x in arr_list_video ) {
				
				//
				str += '' +
				'<div class="quick-video-node">' +
					'<div class="quick-video-width" style="width:' + wit + 'px">' +
						'<div class="quick-video-title bold">' +arr_list_video[x]+ '</div>' +
						'<iframe width="' + wit + '" height="' +hai+ '" src="//www.youtube.com/embed/' +x+ '" frameborder="0" allowfullscreen></iframe>' +
					'</div>' +
				'</div>';
				
			}
			
			
			//
			var new_scroll_top = window.scrollY || jQuery(window).scrollTop();
			
			//
			jQuery('#quick-video-content').css({
				'padding-top' : new_scroll_top + 'px'
			});
			
			//
			dog( 'quick-video-content', str );
//			dog( 'quick-video-content', str + str + str + str + str + str + str );
			
			
			// chỉnh lại chiều cao dữ liệu một lần nữa
			jQuery('.quick-video').show().height( jQuery(document).height() );
			
			
			//
			return false;
		}
	});
//	jQuery('.click-quick-view-video:first').click();
	
	
	//
	jQuery('.quick-video-close i.fa-remove').click(function () {
		close_img_quick_video_details();
	});
}

//
function ___eb_func_fix_right_menu () {
	
	// chiều cao của menu phải
//	var a = jQuery('.fix-right-menu').height();
	fix_right_window_height = jQuery(window).height();
	
	// chiều cao của main -> lớn hơn right thì mới dùng chức năng này
	privary_main_height = jQuery( id_for_fix_main_content ).height() || 0;
//	console.log( 'main: ' + privary_main_height );
	
	// điểm bắt đầu fix menu
	right_main_height = jQuery( id_for_fix_menu_content ).offset().top || 0;
	right_main_height += jQuery('.fix-right-menu').height();
//	console.log( 'main_right: ' + right_main_height );
	
	// xác định có auto scroll hay không
	fix_details_right_menu = false;
	if ( right_main_height < privary_main_height ) {
		fix_details_right_menu = true;
	}
	
	// thêm vào chiều cao của window để điểm cuối của scroll được ok
	if ( fix_menu_top_or_bottom == 'bottom' ) {
		right_main_height -= fix_right_window_height;
	}
	
	// điểm kết thúc fix menu
	end_right_top_menu = jQuery('#fix_end_right_menu').offset().top - fix_right_window_height;
//	console.log( 'end right: ' + end_right_top_menu );
	
	// fix style cho menu bên này, tránh bị vỡ khung
	jQuery('.fix-right-menu').width( jQuery('#fix_right_menu').width() ).css({
		left : jQuery('#fix_right_menu').offset().left + 'px'
	});
	
	//
	jQuery(window).scroll();
}

function ___eb_fix_left_right_menu () {
	
	//
	if ( g_func.mb_v2() == true ) {
		return false;
	}
	
	// Xác định lại vị trí menu
	setTimeout(function () {
		___eb_func_fix_right_menu();
	}, 2000);
	
	setInterval(function () {
		___eb_func_fix_right_menu();
	}, 5000);
	
	//
	jQuery(window).resize(function () {
		___eb_func_fix_right_menu();
//	}).on('load', function() {
//		___eb_func_fix_right_menu();
	}).scroll(function() {
//		console.log( fix_right_left_menu );
//		console.log( fix_right_top_menu );
//		console.log( end_right_top_menu );
		
		//
		var a = window.scrollY || jQuery(window).scrollTop();
//		console.log( end_right_top_menu );
		
		if ( fix_details_right_menu == true ) {
			if ( a > right_main_height ) {
				// fixed
				if ( a < end_right_top_menu ) {
					jQuery('body').removeClass('abs-right-menu').addClass('fixed-right-menu');
				}
				// absolute
				else {
					jQuery('body').removeClass('fixed-right-menu').addClass('abs-right-menu');
				}
			} else {
				jQuery('body').removeClass('fixed-right-menu').removeClass('abs-right-menu');
			}
		}
	});
}


function ___eb_show_cart_count () {
	var c = 'eb_cookie_cart_list_id',
		cart_id_in_cookie = g_func.getc( c );
//	console.log( cart_id_in_cookie );
	if ( cart_id_in_cookie == null ) {
		return false;
	}
//	console.log( cart_id_in_cookie );
	
	//
	cart_id_in_cookie = jQuery.trim(cart_id_in_cookie);
	if ( cart_id_in_cookie == '' ) {
		return false;
	}
	
	// bỏ dấu , ở đầu chuỗi
	if ( cart_id_in_cookie.substr(0, 1) == ',' ) {
		cart_id_in_cookie = cart_id_in_cookie.substr(1);
	}
	
	// tính tổng số SP
	jQuery('.show_count_cart').html( cart_id_in_cookie.split(',').length );
}

// danh sách sản phẩm đã xem, lưu dưới dạng cookies
function ___wgr_set_product_id_cookie ( cookie_name, add_id, limit_history, limit_save ) {
	// tên của cookie lưu trữ
	if ( typeof cookie_name == 'undefined' || cookie_name == '' ) {
		cookie_name = 'wgr_product_id_view_history';
	}
	
	// giới hạn lưu trữ
	if ( typeof limit_history == 'undefined' || limit_history < 0 ) {
		limit_history = 25;
	}
	else {
		limit_history = parseInt( limit_history, 10 );
	}
	
	// thời hạn lưu trữ
	if ( typeof limit_save == 'undefined' || limit_save < 0 ) {
		limit_save = 7;
	}
	else {
		limit_save = parseInt( limit_save, 10 );
	}
	
	// ID lưu trữ
//	console.log(typeof add_id);
//	console.log(add_id);
	if ( typeof add_id == 'undefined' || add_id == '' || add_id < 0 ) {
		add_id = pid;
	}
	else {
		add_id = parseInt( add_id, 10 );
	}
//	console.log(typeof add_id);
//	console.log(add_id);
	if ( add_id <= 0 ) {
		if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('new ID for add not found: ' + add_id);
		return false;
	}
	
	// lấy danh sách trong cookie trước đó
	var str_history = g_func.getc(cookie_name),
		new_id = '[' + add_id + ']';
	if ( WGR_check_option_on ( cf_tester_mode ) ) {
		console.log(str_history);
		limit_history = 5;
	}
	
	// nếu chưa có -> null
	if ( str_history == null || str_history == '' ) {
		str_history = '';
	}
	// nếu có rồi -> kiểm tra có trùng với ID hiện tại không
	else if ( str_history.split( new_id ).length > 1 ) {
		if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('product ID exist in history cookie');
		return false;
	}
	
	//
//	str_history = str_history.replace( '[' + pid + ']', '' );
//	console.log(str_history);
	
	// kiểm tra đọ dài của log
	var check_history = str_history.split('][');
//	console.log(check_history.length);
//	console.log(check_history);
	
	// nếu nhiều quá -> thay mảng cuối bằng ID hiện tại
	if ( check_history.length >= limit_history ) {
		// thêm vào cuối
//		check_history[ check_history.length - 1 ] = pid + ']';
		
		// sau đó ghép chuỗi lại -> trong giới hạn cho phép thì ghép luôn là được
		if ( check_history.length < limit_history * 2 ) {
			// thêm vào đầu
			check_history[ 0 ] = '[' + add_id;
			
			str_history = check_history.join('][');
		}
		else {
			str_history = '';
			for ( var i = 0; i < limit_history + 1; i++ ) {
				str_history += check_history[i] + '][';
			}
			// thêm vào cuối
			str_history += add_id + ']';
		}
		if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('Split history: ' + str_history);
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




function WGR_show_or_hide_to_top () {
	var new_scroll_top = window.scrollY || jQuery(window).scrollTop();
	
	//
	/*
	if ( didScroll == true ) {
		didScroll = false;
		*/
	if (new_scroll_top > 120) {
		jQuery('body').addClass('ebfixed-top-menu');
		
		//
		if (new_scroll_top > 500) {
//		if ( new_scroll_top < old_scroll_top ) {
			jQuery('body').addClass('ebshow-top-scroll');
			
			//
			_global_js_eb.ebBgLazzyLoad(new_scroll_top);
		}
		else {
			jQuery('body').removeClass('ebshow-top-scroll');
		}
	} else {
		jQuery('body').removeClass('ebfixed-top-menu').removeClass('ebshow-top-scroll');
	}
	/*
	clearTimeout( set_old_scroll_top );
	set_old_scroll_top = setTimeout(function () {
//		old_scroll_top = new_scroll_top;
		didScroll = true;
	}, 200);
//	}
*/
	
	/*
	if (new_scroll_top > 500) {
//		jQuery('#oi_scroll_top').show();
		
		_global_js_eb.ebBgLazzyLoad(new_scroll_top);
//	} else {
//		jQuery('#oi_scroll_top').hide();
	}
	*/
}
function ___eb_thread_details_timeend () {
	threadDetailsTimeend = setInterval(function () {
		if ( jQuery('.thread-details-timeend').length == 0 ) {
			clearInterval(threadDetailsTimeend);
			return false;
		}
		
		//
		jQuery('.thread-details-timeend').each(function() {
			var te = jQuery(this).attr('data-timeend') || '';
	//		te = date_time + 24 * 3600 + 5;
	//		console.log( te );
			if ( te != '' ) {
				var a = te - date_time;
				
				//
				if ( a > 0 ) {
					var mot_ngay = 24 * 3600,
						giay = a % 60,
						phut = a - giay,
						phut = a > 3600 ? phut % 3600 : phut,
						ngay = a > mot_ngay ? Math.ceil( a/ mot_ngay ) - 1 : 0,
						gio = ngay > 0 ? a - ngay * mot_ngay : a,
	//					so_du = a > mot_ngay ? a % mot_ngay : a,
	//					phut = gio > 0 ? a % 3600 : a,
						bbbbbbb = 1;
					
					//
					gio = Math.ceil( gio/ 3600 ) - 1;
					phut = phut/ 60;
					if (phut == 0 && giay == 0) {
						phut = 59;
					}
					
					//
					ngay = ngay < 10 ? "0" + ngay : ngay;
					gio = gio < 10 ? "0" + gio : gio;
					phut = phut < 10 ? "0" + phut : phut;
					giay = giay < 10 ? "0" + giay : giay;
					
					//
					jQuery(this).attr({
						'data-timeend' : te - 1
					}).html( '<li><div><span>' +ngay+ '<em>Ngày</em></span></div></li>' +
						'<li><div><span>' +gio+ '<em>Giờ</em></span></div></li>' +
						'<li><div><span>' +phut+ '<em>Phút</em></span></div></li>' +
						'<li><div><span>' +giay+ '<em>Giây</em></span></div></li>' )
					.show();
				} else {
					jQuery(this).removeClass('thread-details-timeend');
				}
			} else {
				jQuery(this).removeClass('thread-details-timeend');
			}
		});
	}, 1000);
}




// load danh sách nhóm dưới dạng JS
function WGR_get_js_sub_category_to_menu ( arr ) {
	if ( arr.length == 0 ) {
		return '';
	}
	// sắp xếp mảng từ to đến bé
//	console.log( arr );
	arr.sort( function ( a, b ) {
		return parseFloat(b.order) - parseFloat(a.order);
	} );
//	console.log( arr );
	
	//
//	var str = '<!-- JS for sub-category menu -->';
	var str = '',
		avt = '',
		icon = '';
	
	for ( var i = 0; i < arr.length; i++ ) {
		if ( typeof arr[i].hidden == 'undefined' || arr[i].hidden * 1 != 1 ) {
			// tạo style ảnh nền cho menu
			avt = '';
			if ( arr[i].avt != '' ) {
				avt = 'background-image:url(\'' + arr[i].avt + '\')';
			}
			icon = '';
			if ( arr[i].icon != '' ) {
				icon = 'background-image:url(\'' + arr[i].icon + '\')';
			}
			
			//
			str += '<li style="order:' + arr[i].order + ';"><a href="' + arr[i].lnk + '" class="js-mega-menu"><span class="d-none avt_mega_menu avt_mega_menu' + arr[i].id + '" style="' + avt + '">&nbsp;</span> <span class="d-none icon_mega_menu icon_mega_menu' + arr[i].id + '" style="' + icon + '">&nbsp;</span> ' + arr[i].ten + '</a>' + WGR_get_js_sub_category_to_menu( arr[i].arr ) + '</li>';
		}
	}
	if ( str != '' ) {
		str = '<ul class="sub-menu cf">' + str + '</ul>';
	}
	
	//
	return str;
}

function WGR_get_js_category_to_menu ( arr ) {
	if ( arr.length == 0 ) {
		return '';
	}
	// sắp xếp mảng từ to đến bé
//	console.log( arr );
	arr.sort( function ( a, b ) {
		return parseFloat(b.order) - parseFloat(a.order);
	} );
//	console.log( arr );
	
	//
	var str = '<!-- JS for category menu -->';
	
	for ( var i = 0; i < arr.length; i++ ) {
		if ( typeof arr[i].hidden == 'undefined' || arr[i].hidden * 1 != 1 ) {
			str += '<li class="echbay-category-order"><a href="' + arr[i].lnk + '">' + arr[i].ten + '</a>' + WGR_get_js_sub_category_to_menu( arr[i].arr ) + '</li>';
		}
	}
	
	return str;
}

function WGR_check_load_js_category ( i ) {
	if ( typeof i == 'undefined' ) {
		i = 20;
	}
	else if ( i < 0 ) {
		console.log('Max load eb_site_group or eb_blog_group');
		return false;
	}
	
	if ( typeof eb_site_group == 'undefined' ) {
		setTimeout(function () {
			WGR_check_load_js_category( i - 1 );
		}, 200);
		
		return false;
	}
//	return false;
	
	//
//	console.log( eb_site_group );
	
	// MENU chính -> xóa LI hiện tại, ghi nội dung mới vào
	// catgory
//	if ( eb_site_group.length > 0 && jQuery('.wgr-load-js-category').length > 0 ) {
	if ( jQuery('.wgr-load-js-category').length > 0 ) {
		jQuery('.wgr-load-js-category').after( WGR_get_js_category_to_menu( eb_site_group ) ).remove();
	}
	
	// blog group
//	if ( eb_blog_group.length > 0 && jQuery('.wgr-load-js-blogs').length > 0 ) {
	if ( jQuery('.wgr-load-js-blogs').length > 0 ) {
		jQuery('.wgr-load-js-blogs').after( WGR_get_js_category_to_menu( eb_blog_group ) ).remove();
	}
	
	// SUB-MENU -> bổ sung nội dung vào thẻ LI hiện tại
	// nhớ add thêm class echbay-category-order để order cho phần li
	// sub catgory
//	if ( eb_site_group.length > 0 && jQuery('.wgr-load-js-sub-category').length > 0 ) {
	if ( jQuery('.wgr-load-js-sub-category').length > 0 ) {
		jQuery('.wgr-load-js-sub-category').addClass('echbay-category-order').append( WGR_get_js_sub_category_to_menu( eb_site_group ) );
	}
	
	// sub blog group
//	if ( eb_blog_group.length > 0 && jQuery('.wgr-load-js-sub-blogs').length > 0 ) {
	if ( jQuery('.wgr-load-js-sub-blogs').length > 0 ) {
		jQuery('.wgr-load-js-sub-blogs').addClass('echbay-category-order').append( WGR_get_js_sub_category_to_menu( eb_blog_group ) );
	}
	
}


//
function WGR_show_or_scroll_to_quick_cart () {
	
	// Nếu có thuộc tính hiển thị quick cart -> cuộn chuột đến đó
	if ( jQuery('.clone-show-quick-cart').length > 0 ) {
//		window.scroll( 0, jQuery('.clone-show-quick-cart').offset().top - 90 );
		jQuery('body,html').animate({
			scrollTop: jQuery('.clone-show-quick-cart').offset().top - 90
		}, 800, function () {
			jQuery('.clone-show-quick-cart input[name="t_dienthoai"]').focus();
		});
		
		return true;
	}
	
	// nếu không -> trả về false để thực thi lệnh tiếp theo
	return false;
	
}

//
function WGR_set_a_target_top () {
	jQuery('a').each(function() {
		var a = jQuery(this).attr('target') || '';
		if ( a == '' ) {
			jQuery(this).attr({
				target: '_top'
			});
		}
	});
}

function close_ebe_quick_view () {
	jQuery('#oi_ebe_quick_view').hide();
	jQuery('body').removeClass('body-no-scroll').removeClass('open-fake-in-iframe');
	
	window.history.pushState("", '', current_ls_url);
	
	pid = current_pid_quicview;
	
	document.title = cache_for_quick_view_title;
	
	_global_js_eb.auto_margin();
}

function WGR_set_quick_view_height () {
	if ( set_new_height_for_quick_view == false ) {
		return false;
	}
	setTimeout(function () {
		WGR_set_quick_view_height();
	}, time_for_new_height_quick_view);
	
	//
	var h = jQuery( '#ui_ebe_quick_view' ).contents().find( 'body' ).height() || 0;
//	console.log(h);
	if ( h == 0 ) {
		h = 600;
	}
	else {
		h -= -200;
	}
//	console.log(h);
	jQuery('#ui_ebe_quick_view').height( h ).scrollTop(0);
	
	//
//	window.scroll( 0, 0 );
}

//
function ___eb_custom_login_done () {
	window.location = window.location.href;
}

function ___eb_global_home_runing ( r ) {
	if ( khong_chay_function_o_theme_nua == 1 ) {
		console.log('WARNING!!!!!!!!!!!!!!!!!!! function ___eb_global_home_runing has been runing...');
		return false;
	}
	khong_chay_function_o_theme_nua = 1;
	
	if ( typeof Child_eb_global_home_runing == 'function' ) {
		Child_eb_global_home_runing();
	}
}



// tạo mục lục cho trang chi tiết sản phẩm, chi tiết blog
function WGR_list_of_content ( b, a ) {
	
	//
	if ( b == '' ) {
		return false;
	}
	
	//
	if ( typeof a == 'undefined' || a == '' ) {
		a = '.thread-content-bmask';
	}
	else if ( a.substr(0, 1) != '.' && a.substr(0, 1) != '#' ) {
		a = '.' + a;
	}
	
	//
	var str = '';
	
	jQuery(b + ' ' + b).each(function() {
		var jd = jQuery(this).attr('id') || '',
			a = jQuery(this).html();
		
		if ( jd == '' ) {
			jd = '_' + Math.random().toString(32).replace(/\./g, '_');
			
			jQuery(this).attr({
				id: jd
			});
		}
		
		str += '<li data-id="' + jd + '">' + g_func.strip_tags( a ) + '</li>';
	});
	
	//
	if ( str == '' ) {
		if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('Post index not found!');
		return false;
	}
	str = '<div class="thread-details-index"><strong>Nội dung chính:</strong><ul>' + str + '</ul></div>';
	
	// cho đến thẻ H2 đầu tiên
	if ( jQuery(b + ' p').length > 0 ) {
		jQuery(b + ' p:first').after( str );
	}
	else if ( jQuery(b + ' .ul-default-style div').length > 0 ) {
		jQuery(b + ' .ul-default-style:first div:first').after( str );
	}
	else {
		jQuery(b).before( str );
//		jQuery(b + ' h2:first').before( str );
	}
	
	//
	jQuery('.thread-details-index li').click(function () {
		var a = jQuery(this).attr('data-id') || '';
		
		if ( a != '' ) {
//			window.scroll( 0, jQuery('#' + a).offset().top - 90 );
			jQuery('body,html').animate({
				scrollTop: jQuery('#' + a).offset().top - 90
			}, 800);
		}
	});
	
}


//
function WGR_for_post_details ( function_for_post, function_for_blog ) {
	
	//
	if ( typeof switch_taxonomy == 'undefined' ) {
		console.log('switch_taxonomy not found');
		return false;
	}
	
	// post
	if ( switch_taxonomy == 'post' ) {
		/*
		if ( typeof function_for_post == 'function' ) {
			___eb_details_post_run( function_for_post );
		}
		else {
			*/
			___eb_details_post_run();
			WGR_list_of_content( cf_post_index_content );
//		}
	}
	// blog, page...
	else {
		/*
		if ( typeof function_for_blog == 'function' ) {
			___eb_global_blog_details_runing( function_for_blog );
		}
		else {
			*/
			___eb_global_blog_details_runing();
			WGR_list_of_content( cf_blog_index_content );
//		}
	}
	
}


