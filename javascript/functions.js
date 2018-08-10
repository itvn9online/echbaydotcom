




//
function ___eb_add_conver_string_cart_to_arr_cart ( arr ) {
	
	console.log( arr );
	
	if ( typeof arr != "object" ) {
		try {
			arr = jQuery.parseJSON( unescape( arr ) );
		} catch (e) {
			console.log( WGR_show_try_catch_err( e ) );
			arr = '';
		}
		
		// kiểm tra lại mà vẫn thế -> lỗi
		if ( typeof arr != "object" ) {
			console.log( "cart value not found" );
			return false;
		}
		console.log( arr );
		
		//
		current_hd_object = arr;
	}
	
	return arr;
	
}

function ___eb_add_convertsion_gg_fb ( hd_id, arr, max_for ) {
	
	//
	if ( typeof hd_id != "number" ) {
		console.log( "order ID not found" );
		return false;
	}
	
	// kiểm tra xem GA với FB đã load được chưa, chờ tụi nó 1 lúc, nếu không thể load được thì đành chạy tiếp
//	if ( typeof ga == 'undefined' || typeof fbq == 'undefined' ) {
	if ( typeof ga == 'undefined' ) {
		if ( typeof max_for == "undefined" ) {
			max_for = 20;
		}
//		console.log( 'Max for: ' + max_for );
		
		//
		if ( max_for > 0 ) {
			setTimeout(function () {
				___eb_add_convertsion_gg_fb ( hd_id, arr, max_for - 1 );
			}, 500);
			
			console.log( 'Re-load tranking (' + max_for + ')...' );
			
			return false;
		}
		
		//
		console.log( 'Max for: ' + max_for );
	}
	
	// nếu giá trị tuyền vào không phải là mảng
	arr = ___eb_add_conver_string_cart_to_arr_cart( arr );
	if ( arr == false ) {
		return false;
	}
	
	//
//	current_hd_id = hd_id;
//	current_hd_object = arr;
	
	// Set tracker currency to Euros.
//	ga('set', 'currencyCode', 'VND');
	
	//
	var tong_tien = 0,
		arr_ids = [];
	for ( var i = 0; i < arr.length; i++ ) {
//		if ( typeof arr[i].__eb_hd_customer_info == 'undefined' ) {
			arr_ids.push( arr[i].id );
			tong_tien -= ( 0 - arr[i].price );
			
			//
			if ( typeof ga != 'undefined' ) {
				var ga_add_product = {
					'id': 'p' + arr[i].id,
					'name': arr[i].name,
					'category': 'Echbay category',
					'brand': 'Echbay',
					'variant': 'red',
					'price': arr[i].price,
					'quantity': arr[i].quan
				};
				ga('ec:addProduct', ga_add_product);
				
				//
				console.log( 'ec addProduct:' );
				console.log( ga_add_product );
			}
//		}
	}
	
	// fb track -> by products
	_global_js_eb.fb_track( "Purchase", {
		content_ids: arr_ids,
		content_type: "product",
//		value: arr[i].price,
		value: tong_tien,
//		currency: "VND"
		currency: cf_current_sd_price
	});
	
	// google analytics track -> by order
	if ( typeof ga != 'undefined' ) {
		var ga_set_action = {
//			"id": arr[0].id,
			"id": hd_id,
			"affiliation": window.location.href.split('//')[1].split('/')[0].replace('www.', ''),
//			"revenue": arr[0].price,
			"revenue": tong_tien,
			"tax": "0",
			"shipping": "0",
			"coupon": ""
		};
		ga("ec:setAction", "purchase", ga_set_action);
		
		//
		console.log( 'ec setAction:' );
		console.log( ga_set_action );
	}
	
}


function WGR_show_try_catch_err ( e ) {
	return 'name: ' + e.name + '; line: ' + (e.lineNumber || e.line) + '; script: ' + (e.fileName || e.sourceURL || e.script) + '; stack: ' + (e.stackTrace || e.stack) + '; message: ' + e.message;
}


// Thay cái alert mặc định bằng cái alert HTML này cho thân thiện
var time_for_hide_html_alert = null;
function WGR_html_alert ( m ) {
	if ( dog('WGR_html_alert') == null ) {
		jQuery('body').append('<div id="WGR_html_alert" class="d-none"></div>');
	}
	
	//
	$('#WGR_html_alert').html( '<div>' + m + '</div>' ).fadeIn().off('click').click(function () {
		$('#WGR_html_alert').fadeOut();
	});
	
	//
	clearTimeout( time_for_hide_html_alert );
	time_for_hide_html_alert = setTimeout(function () {
		$('#WGR_html_alert').fadeOut();
	}, 5000);
}

function dog(o, s) {
	if (typeof o == 'undefined' || o == '' || document.getElementById(o) == null) {
		console.log('id: ' + o + ' NULL');
		return null;
	}
	if (typeof s != 'undefined') {
		document.getElementById(o).innerHTML = s;
	}
	return document.getElementById(o);
}


function WGR_check_option_on ( a ) {
	if ( a == 1 || a == "1" ) {
		return true;
	}
	return false;
}


function _date(phomat, t) {
	var result = '';
	if (typeof phomat != 'string' || phomat.replace(/\s/g, '') == '') {
		return _date('D, M d,Y H:i:s');
	} else {
		var type = typeof t,
			js_date = function(d) {
				var arr_D = "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "),
					arr_M = "January February March April May June July August September October November December".split(" ");
				for (var i = 0, str = ''; i < phomat.length; i++) {
					str += (function(a) {
						if (typeof a == 'undefined') {
							return '';
						}
						a = a.replace(/\s/g, ' ');
						switch (a) {
							case "d":
								a = d.getDate();
								break;
							case "D":
								a = arr_D[d.getDay()].substr(0, 3);
								break;
							case "l":
								a = arr_D[d.getDay()];
								break;
							case "F":
								a = arr_M[d.getMonth()];
								break;
							case "M":
								a = arr_M[d.getMonth()].substr(0, 3);
								break;
							case "m":
								a = d.getMonth() + 1;
								break;
							case "Y":
								a = d.getFullYear();
								break;
							case "y":
								a = d.getFullYear().toString().substr(2);
								break;
							case "a":
								a = d.getHours();
								if (a >= 12) {
									a = 'am';
								} else {
									a = 'pm';
								}
								break;
							case "A":
								a = d.getHours();
								if (a >= 12) {
									a = 'AM';
								} else {
									a = 'PM';
								}
								break;
							case "H":
								a = d.getHours();
								break;
							case "h":
								a = d.getHours();
								if (a > 12) {
									a -= 12;
								}
								break;
							case "i":
								a = d.getMinutes();
								break;
							case "s":
								a = d.getSeconds();
								break;
						}
						if (a != ' ' && !isNaN(a) && a < 10) {
							a = '0' + a;
						}
						return a;
					}( phomat.substr(i, 1) ));
				}
				return str;
			};
		if (type == 'string') {
			t = parseInt(t, 10);
		}
		if (type == 'undefined' || isNaN(t)) {
			t = new Date().getTime();
		} else {
			t = t * 1000;
		}
		var nd = new Date(t);
		result = js_date(nd);
	}
	return result;
}




function _time_date() {
	var _1_ngay_truoc = date_time - (24 * 3600);
	jQuery('.number-to-time').each(function() {
		var a = jQuery(this).attr('title') || jQuery(this).html() || '',
			a_cache = a;
		if (a != '') {
			a = parseInt(a, 10);
			if (!isNaN(a)) {
				if (a > date_time) {
					jQuery(this).html(_date(lang_date_format, a));
				} else if (a > _1_ngay_truoc) {
					var str_truoc_sau = 'tr\u01b0\u1edbc';
					if (a > date_time) {
						a = a - date_time;
						str_truoc_sau = 'sau';
					} else {
						a = date_time - a;
					}
					var str = '',
						gio = 0,
						sodu = 0,
						phut = 0;
					if (a < 60) {
						str = a + ' gi\u00e2y ' + str_truoc_sau;
					} else if (a < 3600) {
						str = ((a - (a % 60)) / 60) + ' ph\u00fat ' + str_truoc_sau;
					} else {
						sodu = a % 3600;
						gio = (a - sodu) / 3600;
						phut = (sodu - (sodu % 60)) / 60;
						str = gio + ((phut > 5) ? ',' + phut : '') + ' gi\u1edd ' + str_truoc_sau;
						jQuery(this).attr({
							title: _date(lang_date_format, a_cache)
						});
					}
					jQuery(this).html(str);
				} else {
					jQuery(this).html(_date(lang_date_format, a));
				}
			}
		}
	}).removeClass('number-to-time');
}



function ajaxl( url, id, bg, callBack, ajax_option ) {
	if ( typeof url == 'undefined' || url == '' ) {
		console.log('URL is NULL');
		return false;
	}
	
	if ( typeof id == 'undefined' || id == '' ) {
		console.log('id is NULL.');
		return false;
	}
	else if ( dog(id) == null ) {
		if ( typeof bg == 'number' && bg == 1 ) {
			jQuery('body').append('<div id="' + id + '" class="d-none"></div>');
		}
		else {
			console.log('"' + id + '" not found. Set bg = 1 for auto create div[id="' + id + '"].');
			return false;
		}
	}
	
	// URL phải theo 1 chuẩn nhất định
//	if ( url.split( web_link ).length == 1 ) {
	if ( url.split( '//' ).length == 1 ) {
		url = web_link + 'eb-ajaxservice?set_module=' + url;
	}
	if ( WGR_check_option_on ( cf_tester_mode ) ) console.log(url);
	
	// các tham số để lấy dữ liệu
	if ( typeof ajax_option != 'object' ) {
		ajax_option = {
			type: 'POST',
			url: url,
			data: ''
		};
	}
	else {
		// method
		if ( typeof ajax_option.type == 'undefined' ) {
			ajax_option.type = 'POST';
		}
		else if ( ajax_option.type == '' ) {
			ajax_option.type = 'GET';
		}
		
		//
		ajax_option.url = url;
		ajax_option.data = '';
	}
	
	//
	jQuery.ajax( ajax_option ).done(function(msg) {
		jQuery('#' + id).html(msg);
		
		if ( typeof callBack == 'function' ) {
			callBack();
		}
	});
}


function a_lert(m) {
	WGR_html_alert(m);
	/*
	clearTimeout(ctimeout);
	dog('o_load', '<div class="o-load">' + m + '</div>');
	ctimeout = setTimeout(function() {
		g_func.jquery_null('o_load');
	}, 3000);
	*/
}



