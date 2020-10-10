/*
* d category
* Các function dùng chung cho phần danh sách bài viết sẽ được cho vào đây
* Sau đó các file js riêng của từng theme sẽ kế thừa và sử dụng các chức năng bằng cách gọi function
*/


// Sắp xếp sản phẩm theo ý muốn người dùng
(function () {
	if ( jQuery('#oi_order_by').length == 0 ) {
		return false;
	}
	
	
	//
	var arr = {
		time : lang_order_by,
//		time : 'Mới nhất',
		view : lang_order_view,
		price_down : lang_order_price_down,
		price_up : lang_order_price_up,
		az : lang_order_az,
		za : lang_order_za
	};
	
	//
	var str = '',
		sl = '';
	for (var x in arr) {
		sl = '';
		if ( x == current_order ) {
			sl = ' selected="selected"';
		}
		
		//
		str += '<option value="' + eb_this_current_url + '?orderby=' +x+ '" ' +sl+ '>' +arr[x]+ '</option>';
	}
	
	//
	dog( 'oi_order_by', '<select>' +str+ '</select>' );
	jQuery('#oi_order_by select').change(function () {
		var a = jQuery(this).val() || '';
		if ( a != '' ) {
//				alert(a);
			window.location = a;
		}
	});
})();

//
function ___eb_list_product_order () {
	console.log('WARNING! Function ___eb_list_product_order bas been remove, please clear in your code!');
}

//
function ___eb_list_post_run ( r ) {
	if ( khong_chay_function_o_theme_nua == 1 ) {
		console.log('WARNING!!!!!!!!!!!!!!!!!!! function ___eb_list_post_run has been runing...');
		return false;
	}
	khong_chay_function_o_theme_nua = 1;
	
	if ( typeof Child_eb_list_post_run == 'function' ) {
		if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('Child_eb_list_post_run');
		
		Child_eb_list_post_run();
	}
	
	
	//
	(function ( a ) {
		
		if ( a != '' ) {
			
			// chỉ xử lý khi nội dung đủ lớn
			if ( cf_cats_description_viewmore < 0 ) {
				if ( WGR_check_option_on ( cf_tester_mode ) ) console.log('View more category disable!');
				
				jQuery('.global-cats-description').hide();
				
				return false;
			}
//			else if ( cf_cats_description_viewmore > 0 && jQuery('.global-cats-description').height() < cf_cats_description_viewmore * 1.5 ) {
			else if ( cf_cats_description_viewmore == 0 || jQuery('.global-cats-description').height() < cf_cats_description_viewmore * 1.5 ) {
//				console.log( jQuery('.global-cats-description').height() );
				jQuery('.global-cats-description').addClass('global-cats-description-active');
				return false;
			}
			
			// hiển thị nút bấm hiển thị thêm nội dung
			jQuery('.viewmore-cats-description').show();
			
			// thêm class tạo hiệu ứng thu gọn nội dung
			jQuery('.global-cats-description').addClass('global-cats-description-scroll').height( cf_cats_description_viewmore );
			
			//
			jQuery('.click-viewmore-cats-description').click(function () {
				jQuery('.global-cats-description').toggleClass('global-cats-description-active');
				
				window.scroll( 0, jQuery('.global-cats-description').offset().top - 90 );
			});
			
		} else {
			jQuery('.global-cats-description').hide();
		}
		
	})( jQuery('.global-cats-description').html() || '' );
}




/*
 * seach advanced
 */
var url_for_advanced_search_filter = '',
    // không cho chuyển URL khi click tự động -> 1
    search_advanced_auto_click = 1,
	timeout_search_advanced_auto_submit = null;



// tạo base URL cho tìm kiếm nâng cao
function ___eb_set_base_url_for_search_advanced() {
    if (url_for_advanced_search_filter == '') {
        url_for_advanced_search_filter = window.location.href.split('#')[0].split('&search_advanced=')[0].split('?search_advanced=')[0].split('/page/')[0];
        if (url_for_advanced_search_filter.split('?').length > 1) {
            url_for_advanced_search_filter += '&';
        } else {
            url_for_advanced_search_filter += '?';
        }
        url_for_advanced_search_filter += 'search_advanced=1';
    }
    if (WGR_check_option_on(cf_tester_mode))
        console.log(url_for_advanced_search_filter);
}


function ___eb_search_advanced_get_parameter(a) {
    if (typeof a == 'undefined') {
        return '';
    }

    var u = window.location.href.replace(/\?|\#|\&amp\;/g, '&');
    //	console.log( u );

    u = u.split('&' + a + '=');

    if (u.length > 1) {
        return u[1].split('&')[0];
    }

    return '';
}

// tạo URL bao gồm các tham số tìm kiếm và chuyển đi
function ___eb_search_advanced_go_to_url(op) {
    /* option mẫu
     op = {
     'price_in': '',
     'post_options': '',
     'category': ''
     }
     */

    //
    if (typeof op != 'object') {
        if (WGR_check_option_on(cf_tester_mode))
            console.log('option not found in URL search advanced');
        return false;
    }
    if (WGR_check_option_on(cf_tester_mode))
        console.log(op);

    // tạo các option khác nếu chưa có
    if (typeof op.price_in == 'undefined') {
        // thử lấy khoảng giá trên URL
        op.price_in = ___eb_search_advanced_get_parameter('price_in');

        // nếu ko có -> thử tìm theo class có sẵn
        if (op.price_in == '') {
            op.price_in = jQuery('.echbay-product-price-between a.selected').attr('data-price');
        }
        if (WGR_check_option_on(cf_tester_mode))
            console.log(op.price_in);
    }
    if (typeof op.category == 'undefined' || typeof op.post_options == 'undefined') {
        var filter_category = '',
            filter_options = '';
        jQuery('.widget-search-advanced .widget_echbay_category a.selected').each(function() {
            var tax = jQuery(this).attr('data-taxonomy') || '',
                j = jQuery(this).attr('data-id') || 0;

            if (tax == 'category') {
                filter_category += ',' + j;
            } else if (tax == 'post_options') {
                filter_options += ',' + j;
            }
            //			console.log( filter_category );
            //			console.log( filter_options );
        });

        //
        op.category = filter_category;
        op.post_options = filter_options;
    }

    //
    var new_url = url_for_advanced_search_filter;

    if (typeof op.price_in != 'undefined' && op.price_in != '') {
        new_url += '&price_in=' + op.price_in;
    }
    if (typeof op.category != 'undefined' && op.category != '') {
        if (op.category.substr(0, 1) == ',') {
            op.category = op.category.substr(1);
        }

        new_url += '&filter_cats=' + op.category;
    }
    if (typeof op.post_options != 'undefined' && op.post_options != '') {
        if (op.post_options.substr(0, 1) == ',') {
            op.post_options = op.post_options.substr(1);
        }

        new_url += '&filter=' + op.post_options;
    }
    if (WGR_check_option_on(cf_tester_mode)) console.log(new_url);

    //
    //	return false;

    //
    if (new_url == '') {
        if (WGR_check_option_on(cf_tester_mode)) console.log('new_url not found in URL search advanced');
        return false;
    }

    //
    if ( WGR_check_option_on(cf_search_advanced_auto_submit) ) {
		WGR_load_category_with_ajax( new_url, 2000 );
    } else {
        jQuery('.click-to-search-advanced').attr({
            href: new_url
        }).css({
            display: 'inline-block'
        });
    }
}


var cache_echo_url_load = '';
function WGR_load_category_with_ajax ( new_url, time_load ) {
	if ( typeof new_url == 'undefined' || new_url == '' ) {
		console.log('new_url not found!');
		return false;
	}
	else if ( new_url == cache_echo_url_load ) {
		console.log('new_url in load!');
		return false;
	}
	cache_echo_url_load = new_url;
	
	//
	jQuery('#category_main').html('<li class="no-set-width-this-li"><div class="waiting-load">&nbsp;</div></li>');
	
	// no ajax
	if ( dog('category_main') == null ) {
		window.location = new_url;
		return true;
	}
	
	// ajax
	window.history.pushState("", '', new_url);
	
	//
	if ( typeof time_load != 'number' ) {
		time_load = 100;
	}
	
	//
	jQuery('body').addClass('body-onload');
	
	//
	clearTimeout(timeout_search_advanced_auto_submit);
	timeout_search_advanced_auto_submit = setTimeout(function () {
		if ( new_url.split('?').length == 1 ) {
			new_url += '?';
		}
		else {
			new_url += '&';
		}
		new_url += 'echo_now=1';
		console.log( new_url );
		
		//
		ajaxl( new_url, 'category_main', 9, function () {
			// về đầu trang
			window.scroll( 0, jQuery('#webgiare__top').height() || jQuery('#container').offset().top || jQuery('#category_main').offset().top || 0 );
			
			// sau đó load các hiệu ứng
			disable_eblazzy_load = false;
			_global_js_eb.auto_margin();
			_global_js_eb.ebBgLazzyLoad();
//			_global_js_eb.ebBgLazzyLoad( window.scrollY || jQuery(window).scrollTop() );
			_global_js_eb.ebe_currency_format();
			jQuery('body').removeClass('body-onload');
			
			// nạp trang mới cho option vừa search
			jQuery('.public-part-page').html( jQuery('.new-part-page').html() || '' );
			
			// hiệu ứng mở link trong tab mới nếu có
			WGR_thread_list_quickview();
			
			// nạp lại url mới
			current_ls_url = window.location.href;
			
			// tạo hiệu ứng nạp trang qua ajax
			WGR_open_new_part_with_ajax();
		});
	}, time_load);
	
	return false;
}

function WGR_open_new_part_with_ajax () {
	if ( dog('category_main') == null ) {
		console.log('category_main not found!');
		return false;
	}
	
	//
	jQuery('.public-part-page a').each(function() {
		var a = jQuery(this).attr('href') || '';
//		console.log( a );
//		console.log( a.split('#')[0].split('&echo_now=')[0] );
		jQuery(this).attr({
			'href': a.split('#')[0].split('&echo_now=')[0].split('?echo_now=')[0]
		});
	}).off('click').click(function () {
		return WGR_load_category_with_ajax( jQuery(this).attr('href') || '' );
	});
	
	//
	return false;
}
WGR_open_new_part_with_ajax();


// tự động đánh dấu các thuộc tính đang được chọn
function ___eb_auto_click_for_search_advanced(clat, a) {
    //	console.log(a);

    //
    for (var i = 0; i < a.length; i++) {
        a[i] = g_func.only_number(a[i]);

        if (a[i] > 0) {

            // cho thẻ A
            jQuery(clat + ' a[data-id="' + a[i] + '"]').click();

            // ẩn nút tạo link đi -> vì đây chỉ tự động check
            jQuery('.click-to-search-advanced').hide();

        }
    }
}



// tìm theo khoảng giá
function ___eb_set_url_for_search_price_in_button(clat) {

    // chỉ tìm ở trang danh sách sản phẩm
//	if (typeof switch_taxonomy == 'undefined' || switch_taxonomy != 'category') {
	if (typeof switch_taxonomy == 'undefined') {
        if (WGR_check_option_on(cf_tester_mode)) {
            console.log('search price is active, but run only category page -> STOP.');
		}
    }
    else if (switch_taxonomy == 'category' || switch_taxonomy == 'post_options') {
	}
	else {
        if (WGR_check_option_on(cf_tester_mode)) {
            console.log('search price is active, but run only category page -> STOP.');
		}
	}

    //
    if (typeof clat == 'undefined' || clat == '') {
        clat = '.echbay-product-price-between';
    }

    // nếu không có class này thì hủy chức năng luôn
    if (jQuery(clat).length == 0) {
        if (WGR_check_option_on(cf_tester_mode))
            console.log('search price is active, but element ' + clat + ' not found -> STOP.');
        return false;
    }

    //
    ___eb_set_base_url_for_search_advanced();

    // nếu có -> tìm vào dựng URL choc ác thẻ A
    /*
     if ( WGR_check_option_on ( cf_search_advanced_auto_submit ) ) {
     jQuery(clat + ' a').each(function() {
     var a = jQuery(this).attr('data-price') || '';
     
     if ( a != '' ) {
     jQuery(this).attr({
     href : url_for_advanced_search_filter + '&price_in=' + a
     });
     }
     else {
     jQuery(this).attr({
     href : url_for_advanced_search_filter
     });
     }
     });
     }
     else {
     */
    jQuery(clat + ' a').click(function() {
        jQuery(clat + ' a').removeClass('selected');
        jQuery(this).addClass('selected');

        //
        var a = jQuery(this).attr('data-price') || '';

        //
        jQuery('.echbay-widget-price-title div').html(jQuery(this).html());

        ___eb_search_advanced_go_to_url({
            'price_in': a
        });

        return false;
        //		}).attr({
    });
    //	}
	
	//
	if ( seach_advanced_by_price != '' ) {
        jQuery('.echbay-product-price-between a[data-price="' + seach_advanced_by_price + '"]').addClass('selected');
	}
	
    //
    //	jQuery(clat + ' a:first').before( '<li><a href="' + eb_this_current_url + '">Tất cả khoảng giá</a></li>' );

    // hiển thị giá đang lọc theo URL
    var a = ___eb_search_advanced_get_parameter('price_in');
    if (a != '') {
        jQuery('.echbay-widget-price-title div').html(jQuery('.echbay-product-price-between a[data-price="' + a + '"]').html());
    }

}
___eb_set_url_for_search_price_in_button();




// tìm theo thuộc tính
function ___eb_set_url_for_search_advanced_button(clat, inner_clat, go_to_url) {

    // hiển thị nút go to nếu go_to_url = false, mặc định là nhảy URL luôn
    /*
     if ( typeof go_to_url == 'undefined' ) {
     if ( WGR_check_option_on ( cf_search_advanced_auto_submit ) ) {
     go_to_url = true;
     }
     else {
     go_to_url = false;
     }
     }
     */

    // chỉ tìm ở trang danh sách sản phẩm
	/*
    if (typeof switch_taxonomy == 'undefined' || switch_taxonomy != 'category') {
        if (WGR_check_option_on(cf_tester_mode))
            console.log('search advanced is active, but run only category page -> STOP.');
        return false;
    }
	*/
	if (typeof switch_taxonomy == 'undefined') {
        if (WGR_check_option_on(cf_tester_mode)) {
            console.log('search price is active, but run only category page -> STOP.');
		}
    }
    else if (switch_taxonomy == 'category' || switch_taxonomy == 'post_options') {
	}
	else {
        if (WGR_check_option_on(cf_tester_mode)) {
            console.log('search price is active, but run only category page -> STOP.');
		}
	}

    //
    if (typeof clat == 'undefined' || clat == '') {
        clat = '.widget-search-advanced .widget_echbay_category';
    }

    //
    if (jQuery(clat).length == 0) {
        if (WGR_check_option_on(cf_tester_mode))
            console.log('search advanced is active, but element ' + clat + ' not found -> STOP.');
        return false;
    }
    //	console.log(jQuery(clat).length);
    console.log('set search advanced multi (v2) for ' + clat);

    // tạo nút tìm kiếm nếu chưa có
    if (jQuery(clat + ' a.click-to-search-advanced').length == 0) {
        if (typeof inner_clat == 'undefined' || inner_clat == '') {
            inner_clat = '.widget-search-advanced';
        }
        jQuery(inner_clat).append('<div class="global-button-for-seach-advanced"><a href="javascript:;" class="click-to-search-advanced search-advanced-btn d-none whitecolor"><i class="fa fa-search"></i> <span>Lọc sản phẩm</span></a></div>');
    }

    //
    ___eb_set_base_url_for_search_advanced();

    // Tạo thẻ xem tất cả sản phẩm
    //	if ( jQuery(clat + ' ul').length > 0 ) {
    jQuery(clat + ' ul').each(function() {
        var data_node_id = jQuery('li:first a', this).attr('data-node-id') || '';

        //
        if (data_node_id != '') {
            var data_parent = jQuery('li:first a', this).attr('data-parent') || 0,
                text = jQuery('#' + data_node_id + ' .echbay-widget-title div').html() || '';

            //
            if (text != '') {
                text = 'Tất cả ' + text;

                jQuery('li:first', this).before('<li style="order:9999999999;"><div><a data-parent="' + data_parent + '" data-node-id="' + data_node_id + '" title="' + text + '" href="javascript:;">' + text + '</a></div></li>');
            }
        }
    });
    //	}

    //
    jQuery(clat + ' a').each(function() {
        var tax = jQuery(this).attr('data-taxonomy') || '';

        //
        if (tax != 'category') {
            jQuery(this).attr({
                'data-href': jQuery(this).attr('data-href') || jQuery(this).attr('href') || 'javascript:;',
                'href': 'javascript:;'
            });
        }
        //	}).attr({
        //		'href' : 'javascript:;'
    }).off('click').click(function() {
        var cha = jQuery(this).attr('data-parent') || 0,
            con = jQuery(this).attr('data-id') || 0,
            filter_category = '',
            filter_options = '',
            node_id = jQuery(this).attr('data-node-id') || '',
            this_tax = jQuery(this).attr('data-taxonomy') || '';

        //
        if (node_id != '') {
            node_id = '#' + node_id + ' .echbay-widget-title div';

            jQuery(node_id).html(jQuery(this).attr('title') || '');
        }

        //
        jQuery(clat + ' a[data-parent="' + cha + '"]').removeClass('selected');

        // Chỉ add class select cho nhóm con, không add cho nhóm tất cả
        if (con != 0) {
            jQuery(this).addClass('selected');
        }


        // nếu là auto click -> chỉ cần set class selected cho thuộc tính thôi
        if (WGR_check_option_on(search_advanced_auto_click)) {
            return false;
        }
        // nếu là category -> chuyển URL luôn
        else if (this_tax == 'category') {
            if (WGR_check_option_on(cf_tester_mode)) console.log('search advanced not run if taxonomy == category');
            return true;
        }


        //
        jQuery(clat + ' a.selected').each(function() {
            var tax = jQuery(this).attr('data-taxonomy') || '',
                j = jQuery(this).attr('data-id') || 0;

            if (tax == 'category') {
                filter_category += ',' + j;
            } else if (tax == 'post_options') {
                filter_options += ',' + j;
            }
            //			console.log( filter_category );
            //			console.log( filter_options );
        });

        //
        ___eb_search_advanced_go_to_url({
            'price_in': seach_advanced_by_price,
            'post_options': filter_options,
            'category': filter_category
        });

        // V1
        /*
         var new_url = '';
         // category -> chuyển link luôn -> bỏ qua phần search nâng cao ở đây
         if ( filter_category != '' ) {
         new_url += '&filter_cats=' + filter_category.substr( 1 );
         }
         if ( filter_options != '' ) {
         new_url += '&filter=' + filter_options.substr( 1 );
         }
         if ( WGR_check_option_on ( cf_tester_mode ) ) console.log( url_for_advanced_search_filter + new_url );
         
         // nếu lệnh chuyển URL chuyển URL
         if ( go_to_url == true ) {
         window.location = url_for_advanced_search_filter + new_url;
         } else {
         jQuery('.click-to-search-advanced').attr({
         href : url_for_advanced_search_filter + new_url
         }).css({
         display : 'inline-block'
         });
         }
         */

        //
        return false;
    });


    // không cho chuyển URL khi click tự động
    //	search_advanced_auto_click = 1;

    // đanh dấu dữ liệu cũ đã được chọn
    if (seach_advanced_value != '') {
        ___eb_auto_click_for_search_advanced(clat, seach_advanced_value.split(','));
    }
    if (seach_advanced_value != '') {
        ___eb_auto_click_for_search_advanced(clat, seach_advanced_by_cats.split(','));
    }
    if (cid > 0) {
        ___eb_auto_click_for_search_advanced(clat, [cid]);
    }

    // reset lại lệnh -> cho phép chuyển link
    search_advanced_auto_click = 0;

}
___eb_set_url_for_search_advanced_button();



function WGR_widget_search_advanced () {
	var clat = '.widget-search-advanced .category-search-advanced';
	if ( cid > 0 ) {
		// nếu không có class này thì hủy chức năng luôn
		if (jQuery(clat).length == 0) {
			if (WGR_check_option_on(cf_tester_mode)) {
				console.log('search price is active, but element ' + clat + ' not found -> STOP.');
			}
			return false;
		}
	}
	else {
		return false;
	}
	
	//
	___eb_set_base_url_for_search_advanced();
	
	//
	jQuery(clat + ' a').each(function() {
		jQuery(this).attr({
			'data-href': jQuery(this).attr('data-href') || jQuery(this).attr('href') || 'javascript:;',
			'href': 'javascript:;'
		});
	}).off('click').click(function () {
		var cha = jQuery(this).attr('data-parent') || 0,
			con = jQuery(this).attr('data-id') || 0,
			filter_category = '',
			filter_options = '',
			node_id = jQuery(this).attr('data-node-id') || '',
			this_tax = jQuery(this).attr('data-taxonomy') || '',
			this_class = jQuery(this).attr('class') || '';
//		console.log(this_class);
//		console.log(this_class.split('selected').length);
		
		//
		if ( this_class.split('selected').length > 1 ) {
			jQuery(this).removeClass('selected');
		}
		else {
			jQuery(this).addClass('selected');
		}
		
		//
		jQuery(clat + ' a.selected').each(function() {
			var tax = jQuery(this).attr('data-taxonomy') || '',
				j = jQuery(this).attr('data-id') || 0;
			
			if (tax == 'category') {
				filter_category += ',' + j;
			} else if (tax == 'post_options') {
				filter_options += ',' + j;
			}
		});
		console.log( filter_category );
		console.log( filter_options );
		
		//
		___eb_search_advanced_go_to_url({
			'price_in': seach_advanced_by_price,
			'post_options': filter_options,
			'category': filter_category
		});
		
		//
		return false;
	});
}
WGR_widget_search_advanced();


