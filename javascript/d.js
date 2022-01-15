//
/*
if ( typeof eb_site_group == 'undefined' ) {
	eb_site_group = [];
}
//console.log(eb_site_group);
//console.log(eb_blog_group);

// sắp xếp menu cho cat
(function () {
	for ( var i = 0; i < eb_site_group.length; i++ ) {
		jQuery( '.echbay-category-in-js .cat-item-' + eb_site_group[i].id ).css({
			order : eb_site_group[i].order
		});
	}
})();
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
if (eb_this_current_url == web_link) {
    eb_this_current_url = window.location.href.split('#')[0];
}


//
if (act != '' && jQuery('body.home').length > 0) {
    act = '';
}

//
var lazyload_footer_content = false;

//
var root_url_for_open_ia = window.location.href,
    root_url_for_open_count = 10,
    WGR_IA_for_load_via_iframe = 1,
    wgr_id_obj_document_title = {
        'root': document.title
    };
//console.log(root_url_for_open_ia);

setTimeout(function () {
    WGR_IA_for_load_via_iframe = 0;
}, 120 * 1000);


// chỉnh lại ảnh của big banner sang ảnh to với bản pc
if (global_window_width > 768) {
    jQuery('.oi_big_banner .ti-le-global').each(function () {
        jQuery(this).css({
            'background-image': 'url(' + jQuery(this).attr('data-img') + ')'
        });
    });
}
// tạo hiệu ứng chẵn lẻ cho nội dung sản phẩm
else {
    jQuery('.thread-list, .echbay-blog').each(function () {
        if (jQuery('li', this).length % 2 != 0) {
            jQuery(this).addClass('fullsize-li-first');
        }
    });
}


//
jQuery('.eb-set-menu-selected .sub-menu').addClass('cf');


// global function /////////////////////////////////////////////////////////////////
/*
 * Các function dùng chung cho toàn trang sẽ được cho vào đây
 * Sau đó các file js riêng của từng theme sẽ kế thừa và sử dụng các chức năng bằng cách gọi function
 */
// big banner
var big_banner_timeout1 = null;

(function () {
    // tạo mảng để nạp nhiều big banner trên 1 trang được
    var arr_multi_big_banner = [];

    //
    var big_banner_len = jQuery('.oi_big_banner li').length;
    if (big_banner_len > 0) {
        arr_multi_big_banner.push('.oi_big_bannerZero');
        jQuery('.oi_big_banner').addClass('oi_big_bannerZero');
    } else {
        jQuery('.hide-if-banner-null').hide();
    }

    // chạy vòng lặp, gọi các big banner khác
    jQuery('.each_big_banner').each(function () {
        var a = $(this).attr('class') || '';
        if (a != '') {
            arr_multi_big_banner.push('.' + a.split(' ')[0]);
        }
    }).addClass('oi_big_banner');

    //
    if (arr_multi_big_banner.length == 0) {
        return false;
    }
    console.log(arr_multi_big_banner);
    console.log(arr_multi_big_banner.join(','));

    // chuyển big banner đến vị trí mới (chỉ làm khi số lượng big banner là 1)
    if (big_banner_len > 0 && jQuery('.oi_big_banner').length == 1 && jQuery('.clone-big-banner-to-here').length > 0) {
        // thiết lập class để lát xóa
        jQuery('.oi_big_banner').addClass('oi_big_banner-remove');

        // nhân bản sang vị trí mới
        jQuery('.clone-big-banner-to-here').html(jQuery('.oi_big_banner').html()).addClass('oi_big_banner').show();

        // xóa cái cũ
        jQuery('.oi_big_banner-remove').remove();

        // bỏ chế độ hiển thị menu liên quan đến big banner
        jQuery('.show-menu-if-banner').removeClass('show-menu-if-banner');
    }

    // tải slider theo code mới
    if (WGR_check_option_on(cf_on_primary_slider)) {
        //		jEBE_slider( arr_multi_big_banner.join(','), {
        jEBE_multi_slider(arr_multi_big_banner.join(','), {
            autoplay: cf_slider_big_play > 0 ? true : false,
            //			swipemobile : true,
            swipemobile: WGR_check_option_on(cf_swipe_big_banner) ? true : false,
            // nếu số giây tự chuyển slider nhỏ quá -> chuyển sang tính theo giây
            speedNext: cf_slider_big_play < 60 ? cf_slider_big_play * 1000 : cf_slider_big_play,

            buttonListNext: WGR_check_option_on(cf_btn_big_banner) ? true : false,
            sliderArrow: WGR_check_option_on(cf_arrow_big_banner) ? true : false,
            version: cf_rand_slider,

            //thumbnail : '.banner-ads-media',
            size: jQuery('.oi_big_banner li:first .ti-le-global').attr('data-size') || ''
        });
    } else {
        console.log('oi_big_banner run by wiget: zEchBay run slider');
    }

    // Hiển thị menu NAV dưới dạng hover
    if (big_banner_len > 0 && jQuery('.show-menu-if-banner').length > 0) {
        jQuery('.show-menu-if-banner .all-category-hover').addClass('selected');
        jQuery('.oi_big_banner').css({
            'min-height': jQuery('.show-menu-if-banner .all-category-cats').height() + 'px'
        });
    }

})();


// logo đối tác
(function () {
    // chỉ chạy trên pc
    /*
    if ( g_func.mb_v2() == true ) {
    	return false;
    }
    */

    //
    jQuery('.sponsor-top-desktop').each(function () {
        if (jQuery('li', this).length == 0) {
            jQuery(this).hide().remove();
        }
    });

    //
    if (jQuery('.sponsor-top-desktop').length == 0) {
        if (WGR_check_option_on(cf_tester_mode)) console.log('sponsor-top-desktop not found');
        return false;
    }

    //
    var len = jQuery('.banner-chan-trang:first li').length || 0;
    /*
    if ( len == 0 ) {
    	jQuery('.sponsor-top-desktop, .hide-if-footer-banner-null').hide();
    	return false;
    }
    */

    // số thẻ LI mặc định được hiển thị/ 1000px
    var so_the_li_mong_muon = jQuery('.banner-chan-trang:first').attr('data-num') || 5;
    //	console.log( so_the_li_mong_muon );
    /*
    if ( typeof so_the_li_mong_muon != 'number' ) {
    	so_the_li_mong_muon = 5;
    }
    */
    // -> chiểu rộng trung bình của mỗi LI
    so_the_li_mong_muon = 999 / so_the_li_mong_muon - 1;
    //	console.log( so_the_li_mong_muon );
    //	console.log( jQuery('.banner-chan-trang:first').width() );

    // tính toán số thẻ LI được hiển thị
    var global_chantrang_len = jQuery('.banner-chan-trang:first').width() / so_the_li_mong_muon;
    global_chantrang_len = Math.ceil(global_chantrang_len) - 1;
    //	console.log( global_chantrang_len );

    // hiển thị 2 đối với bản mobile
    if (global_chantrang_len == 1 && global_window_width < 400) {
        global_chantrang_len = 2;
    }

    // nếu nhiều hơn so với số LI thật -> gán lại giá trị mới
    /*
    if ( global_chantrang_len > len ) {
    	global_chantrang_len = len;
    }
    */
    //	console.log( global_chantrang_len );

    //
    jQuery('.banner-chan-trang li').width((100 / global_chantrang_len) + '%');
    _global_js_eb.auto_margin();


    // tính toán lại chiều rộng mới nếu sau khi chỉnh mà vẫn còn thừa nhiều quá
    //	console.log( jQuery('.banner-chan-trang:first ul').width() );
    //	console.log( jQuery('.banner-chan-trang:first ul li:first').width() );
    //	console.log( len );
    //	console.log( jQuery('.banner-chan-trang:first ul li:first').width() * jQuery('.banner-chan-trang:first li').length );
    if (jQuery('.banner-chan-trang:first ul').width() > jQuery('.banner-chan-trang:first ul li:first').width() * len) {
        jQuery('.banner-chan-trang li').width((100 / len) + '%');
        _global_js_eb.auto_margin();
    }


    //
    //	jQuery('.home-next-chantrang, .home-prev-chantrang').hide();

    // không đủ thì thôi, ẩn nút next
    if (len <= global_chantrang_len) {

        jQuery('.banner-chan-trang').height('auto').css({
            'line-height': jQuery('.banner-chan-trang:first').height() + 'px'
        });

        return false;
    }

    // đủ thì hiển thị và tạo hiệu ứng
    /*
    var li_fo_scroll = jQuery('.banner-chan-trang:first').attr('data-scroll') || global_chantrang_len;
    if ( typeof li_fo_scroll != 'number' ) {
    	li_fo_scroll = global_chantrang_len;
    }
    */
    //	console.log( global_chantrang_len );

    //
    //	jQuery('.home-next-chantrang, .home-prev-chantrang').hide();

    jEBE_slider('.banner-chan-trang', {
        buttonListNext: false,
        //		autoplay : true,
        visible: global_chantrang_len,

        sliderArrow: true,
        //		sliderArrowWidthLeft : '40%',
        //		sliderArrowWidthRight : '60%',
        //		sliderArrowLeft : 'fa-chevron-circle-left',
        //		sliderArrowRight : 'fa-chevron-circle-right',

        //thumbnail : 'ul li',
        size: jQuery('.banner-chan-trang li:first .ti-le-global').attr('data-size') || ''
    });

})();


// hiệu ứng xem video tại chỗ
var current_ls_url = window.location.href;


// fix menu khi cuộn chuột
var fix_right_top_menu = 0,
    fix_menu_top_or_bottom = 'bottom',
    //	id_for_fix_main_content = '#main',
    id_for_fix_main_content = '#container',
    id_for_fix_menu_content = '#main_right',
    // Kết thúc fix menu
    end_right_top_menu = 0,
    privary_main_height = jQuery(id_for_fix_main_content).height() || 0,
    right_main_height = jQuery(id_for_fix_menu_content).height() || 0,
    fix_right_window_height = jQuery(window).height(),
    fix_details_right_menu = false;


// function cho từng action
___eb_show_cart_count();


// các function này trước được gọi ở theme, giờ chuyển vào plugin thì bổ sung biến để kiểm tra nó chạy rồi hay chưa
var khong_chay_function_o_theme_nua = 0;

// end global function /////////////////////////////////////////////////////////////////


// Kiểm tra người dùng đã đăng nhập chưa
if (isLogin > 0 && logout_url != '') {
    jQuery('.oi_member_func').html('<a href="' + web_link + 'profile" class="bold"><i class="fa fa-user"></i> <span>' + lang_taikhoan + '</span></a> <a onclick="return confirm(\'' + lang_xacnhan_thoat + '\');" href="' + logout_url + '"><span>' + lang_thoat + '</span></a>');
} else {
    jQuery('.oi_member_func').html('<a href="javascript:;" onclick="g_func.opopup(\'login\');"><i class="fa fa-user"></i> <span>' + lang_dangnhap + '</span></a> <a onclick="g_func.opopup(\'register\');" href="javascript:;"><span>' + lang_dangky + '</span></a>');
}
//jQuery('.oi_member_func').addClass('fa fa-user');


// tạo menu cho bản mobile ( nếu chưa có )
/*
if ( jQuery('#nav_mobile_top li').length == 0 ) {
	jQuery('#nav_mobile_top').html( '<ul>' + ( jQuery('.nav-menu ul').html() || jQuery('.global-nav ul').html() || '' ) + '</ul>' );
	
	jQuery('#nav_mobile_top li').removeAttr('id');
}

jQuery('#nav_mobile_top li li a').each(function() {
	jQuery(this).html( '<i class="fa fa-angle-right"></i> ' + jQuery(this).html() );
});
*/

//
jQuery('#nav_mobile_top li').click(function () {
    jQuery('#nav_mobile_top li').removeClass('active');
    jQuery(this).addClass('active');
});


//
jQuery('#click_show_mobile_bars').click(function () {
    var a = jQuery(this).attr('data-show') || '';

    // đang hiển thị
    if (a == 1) {
        a = 0;

        jQuery('.menu-mobile-nav').hide();

        jQuery('body').css({
            overflow: 'auto'
        });
    }
    // đang ẩn
    else {
        // trỏ vào khung tìm kiếm luôn
        jQuery('#value_add_to_search').focus();

        //
        a = 1;

        jQuery('.menu-mobile-nav').show().height(jQuery(window).height());

        jQuery('body').css({
            overflow: 'hidden'
        });
    }

    jQuery(this).attr({
        'data-show': a
    });

    jQuery('#click_show_mobile_bars i').toggleClass('fa-bars').toggleClass('fa-remove');

    //
    /*
    if ( jQuery('.menu-mobile-nav').height() > jQuery(window).height() ) {
    	jQuery('.menu-mobile-nav').height( jQuery(window).height() - 50 );
    	
    	jQuery('body').css({
    		overflow : 'hidden'
    	});
    } else {
    	jQuery('.menu-mobile-nav').height( '' );
    	
    	jQuery('body').css({
    		overflow : ''
    	});
    }
    */
});
//jQuery('#click_show_mobile_bars').click();


//
jQuery('#click_show_search_bars').click(function () {
    jQuery('#click_show_mobile_bars').click();
    //	jQuery('body').toggleClass('show-search-mobile');
    jQuery('#value_add_to_search').focus();
});
//jQuery('#click_show_search_bars').click();


//
var arr_detect_browser = (function (ua) {
    //	console.log(ua);

    var r = 'unknown';
    ua = ua.toLowerCase();

    // facebook browser
    if (ua.split('fban').length > 1 || ua.split('fbav').length > 1) {
        r = 'fb';
    } else if (ua.split('opr/').length > 1) {
        r = 'opera';
    } else if (ua.split('firefox/').length > 1) {
        r = 'firefox';
    } else if (ua.split('edge/').length > 1) {
        r = 'edge';
    } else if (ua.split('coc_coc_browser/').length > 1) {
        r = 'coc_coc';
    } else if (ua.split('chrome/').length > 1) {
        r = 'chrome';
    } else if (ua.split('safari/').length > 1) {
        r = 'safari';
    } else if (ua.split('msie/').length > 1
        // IF IE > 10
        || (!document.documentMode == true)) {
        r = 'ie';
    } else {
        // Internet Explorer 6-11
        var isIE = /*@cc_on!@*/ false || !!document.documentMode;
        if (isIE) {
            r = 'ie';
        } else {
            // Blink engine detection
            var isBlink = (isChrome || isOpera) && !!window.CSS;
            if (isBlink) {
                r = 'blink';
            }
        }
    }

    return r;
})(navigator.userAgent || navigator.vendor || window.opera);
//alert( arr_detect_browser );

//
/*
jQuery('.phone-numbers-block').attr({
	'data-block': 1
});
*/


//
var str_for_click_call = 'tel';
/*
if ( navigator.userAgent.toLowerCase().split("iphone").length > 1
|| arr_detect_browser == 'safari' ) {
if ( arr_detect_browser == 'safari' ) {
	str_for_click_call = 'callto';
}
*/


// trên safari nó tự nhận diện số điện thoại -> không can thiệp bằng thẻ a
//if ( arr_detect_browser == 'safari' && g_func.mb_v2() == true ) {
/*
if ( arr_detect_browser == 'safari' ) {
	jQuery('.phone-numbers-inline').each(function() {
		var a = jQuery(this).html() || '';
		
		a = a.replace(/<br\s*[\/]?>/gi, "\n").replace(/\r\n|\r|\n/g, " - ");
		
		jQuery(this).html(a);
	});
} else {
	*/
jQuery('.phone-numbers-inline, .phone-numbers-block').each(function () {

    if (jQuery('a', this).length > 0) {
        return false;
    }

    //	
    var a = jQuery(this).html() || '',
        block = jQuery(this).attr('data-block') || '';
    //		if (a.length >= 8) {
    if (a != '') {
        a = a.replace(/<br\s*[\/]?>/gi, "\n").replace(/\r\n|\r|\n/g, "[br]").split("[br]");
        //			console.log(a);

        var str = '';
        for (var i = 0; i < a.length; i++) {
            a[i] = g_func.trim(a[i]);
            if (a[i] != '') {
                if (block == '' && str != '') {
                    str += ' - ';
                }

                //
                //					str += '<a href="' + str_for_click_call + ':' + a[i].toString().replace(/[^0-9|\+]/g, '') + '" rel="nofollow" class="gg-phone-conversion">' + a[i] + '</a>';
                str += '<a title="' + jQuery.trim(g_func.strip_tags(a[i])) + '" class="phone-to-cell">' + a[i] + '</a>';
            }
        }

        jQuery(this).html(str);
    }

});

//
//	jQuery('.phone-numbers-block a').addClass('d-block');
//}

//
jQuery('.phone-numbers-inline a, .phone-numbers-block a, a.details-tu-van').addClass('gg-phone-conversion');


//
jQuery('a.phone-to-cell').each(function () {
    var a = jQuery(this).attr('title') || jQuery(this).html() || '';
    a = a.toString().replace(/[^0-9|\+]/g, '');
    //	if (a != '') {
    if (a.length >= 8) {
        jQuery(this).attr({
            href: str_for_click_call + ':' + a,
            //			target: "_blank",
            rel: "nofollow"
        }).removeAttr('title');
    }
}).removeClass('phone-to-cell').addClass('gg-phone-conversion');

// track for phone or add to cart
jQuery('a').each(function () {
    var a = jQuery(this).attr('href') || '';

    if (a != '') {
        if (a.split('tel:').length > 1) {
            $(this).addClass('gg-phone-conversion');
        } else if (a.split('mailto:').length > 1) {
            $(this).addClass('gg-mailto-conversion');
        }
    }
});

jQuery('a.gg-phone-conversion').click(function () {
    var a = jQuery(this).attr('href') || '';

    // nếu có chức năng kiểm tra lượt bấm gọi của gg -> nạp vào
    _global_js_eb.gg_track(a);

    _global_js_eb.ga_event_track('Click to phone', a, '', {
        //		'category' : '',
        //		'label' : '',
        //		'action' : 'click_to_call'
        'action': a
    });


    // khi người dùng nhấp gọi điện
    _global_js_eb.fb_track('Call');


    //
    //	return false;
});

jQuery('a.gg-mailto-conversion').click(function () {
    var a = jQuery(this).attr('href') || '';

    // nếu có chức năng kiểm tra lượt bấm gọi của gg -> nạp vào
    _global_js_eb.gg_track(a);

    _global_js_eb.ga_event_track('Click to mailto', a, '', {
        //		'category' : '',
        //		'label' : '',
        //		'action' : 'click_to_call'
        'action': a
    });


    // khi người dùng nhấp gọi điện
    _global_js_eb.fb_track('Mailto');


    //
    //	return false;
});


//
jQuery('#oi_scroll_top, .oi_scroll_top').click(function () {
    //	jQuery('body,html').animate({
    jQuery('body,html').animate({
        scrollTop: 0
    }, 800);
});


// thêm class định hình cho hình ảnh của elementor
jQuery('.elementor-image').addClass('img-max-width');

//_global_js_eb.ebBgLazzyLoadOffset();
if (typeof confirm_df2_loaded != 'undefined') WGR_reload_lazy_function();
//_global_js_eb.ebBgLazzyLoad();
//_global_js_eb.set_mobile_size();
//_global_js_eb.auto_margin();
setTimeout(function () {
    _global_js_eb.set_mobile_size();
    _global_js_eb.auto_margin();
}, 2000);


/*
var old_scroll_top = 0,
	set_old_scroll_top = null,
	didScroll = true;
*/
jQuery(window).resize(function () {
    global_window_width = jQuery(window).width();

    _global_js_eb.auto_margin();
    height_for_lazzy_load = jQuery(window).height();

    $('.wgr-ia-loader iframe').height(height_for_lazzy_load);
});

// khi document đã load xong
jQuery(document).ready(function () {
    // chiều cao của document đủ lớn
    /*
    if (jQuery(document).height() > jQuery(window).height() * 1.5) {
    }
    */
    setInterval(function () {
        WGR_show_or_hide_to_top();
    }, 250);

    //
    if (height_for_lazzy_load == 0) {
        height_for_lazzy_load = jQuery(window).height();
    }

    // nếu document ngắn hơn window height -> hiển thị luôn nội dung ( - min-hegiht của main)
    //console.log($(document).height());
    //console.log($(window).height());
    if (typeof confirm_df2_loaded != 'undefined' && $(document).height() - 250 < $(window).height()) {
        WGR_lazyload_footer_content();
    }
});


//
/*
setInterval(function () {
	if ( didScroll == true ) {
		didScroll = false;
		old_scroll_top = window.scrollY || jQuery(window).scrollTop();
	}
}, 250);
*/


//
/*
setTimeout(function () {
	_global_js_eb.user_loc();
}, 5000);
*/


/*
jQuery('.click-show-top-top-bars').click(function () {
	jQuery('.top-top-position').toggleClass('top-top-2position');
	
	jQuery('.click-show-top-top-bars i').toggleClass('fa-bars').toggleClass('fa-remove');
});
*/


/*
jQuery('.click-show-div-content').click(function () {
	var a = jQuery(this).attr('data-show') || '';
	
	//
	jQuery(a).toggle();
	
	//
	return false;
});
*/


// show content like the_content() -> wp nó tự bỏ thẻ P trong nội dung -> dùng cái này để tạo lại
(function () {

    //
    if (jQuery('.each-to-fix-ptags').length == 0) {
        return false;
    }
    //	console.log( jQuery('.each-to-fix-ptags').length );

    // xử lý lỗi nếu bị 2 lần fix ptag -> chưa xác định nguyên nhân cụ thể
    jQuery('.each-to-fix-ptags .each-to-fix-ptags').removeClass('each-to-fix-ptags');
    jQuery('.each-to-fix-ptags .each-to-fix-ptags').removeClass('each-to-fix-ptags');

    //
    jQuery('.each-to-fix-ptags').each(function () {
        if (jQuery('script', this).length > 0 || jQuery('script', this).length > 0) {
            console.log('each-to-fix-ptags has been active! but, SCRIPT or STYLE exist in this content.');
            return false;
        }

        //
        var a = jQuery(this).html() || '',
            tag = jQuery(this).attr('data-tag') || 'div';

        if (a != '') {
            a = g_func.trim(a);

            var arr = a.split("\n");
            //			console.log( arr );
            a = '';

            for (var i = 0; i < arr.length; i++) {
                arr[i] = g_func.trim(arr[i]);

                if (arr[i] != '') {
                    a += '<div>' + arr[i] + '</div>';
                }
            }

            jQuery(this).html(a);

            // test
            //			console.log( '<div>' + a.replace( /\r\n|\n|\r/gi, '</div><div>' ).replace( /\<div\>\<\/div\>/gi, '' ) + '</div>' );
        }
    });

    // xong việc thì xóa class này đi, để đỡ bị lặp lại -> phòng lỗi
    jQuery('.each-to-fix-ptags').removeClass('each-to-fix-ptags').removeClass('done-fixed-ptags');

})();


// tính số ngày hết hạn của sản phẩm
var threadDetailsTimeend = null;


// auto search
(function () {

    //
    if (typeof thread_js_list != 'object') {
        return false;
    }

    // kiểm tra chế độ tự động tìm kiếm
    var data_auto_search = jQuery('#search_keys').attr('data-auto-search') || '';
    //	console.log(data_auto_search);

    // nếu đang bật -> kích hoạt chức năng tự tìm
    if (data_auto_search == 'off') {
        return false;
    }

    //
    for (var x in thread_js_list) {
        thread_js_list[x].key = g_func.non_mark_seo(thread_js_list[x].tag) + thread_js_list[x].seo;
        thread_js_list[x].key = thread_js_list[x].key.replace(/\-/g, '');
        //		console.log(thread_js_list[x].key);
    }

    jQuery('#search_keys').attr({
        autocomplete: "off"
    }).focus(function () {
        jQuery('#oiSearchAjax').fadeIn();
    }).blur(function () {
        setTimeout(function () {
            jQuery('#oiSearchAjax').hide();
        }, 200);
    }).keyup(function (e) {
        var a = jQuery(this).val(),
            b = g_func.non_mark_seo(a);

        //
        if (b.length < 3) {
            jQuery('#oiSearchAjax').hide();
            return false;
        }

        //
        b = b.replace(/\-/g, '');

        // thử tìm sản phẩm trong js trước
        var str = '',
            i = 1;
        for (var x in thread_js_list) {
            if (thread_js_list[x].key.split(b).length > 1) {
                str += '<li><a title="' + thread_js_list[x].ten + '" href="' + _global_js_eb._p_link(thread_js_list[x].id, thread_js_list[x].seo) + '">' + thread_js_list[x].ten + '</a></li>';

                //
                i++;
                if (i > 10) {
                    break;
                }
            }
        }

        // nếu có -> hiển thị luôn
        if (str != '') {
            //			console.log(1);
            jQuery('#oiSearchAjax').show().html('<ul><li><i class="fa fa-lightbulb-o"></i> Sản phẩm</li>' + str + '</ul>');
            return false;
        }

        // nếu người dùng nhấn cách -> tìm luôn
        /*
		if ( data_auto_search != 'off' && e.keyCode == 32 ) {
//			console.log(2);
//			ajaxl('guest.php?act=search&key=' + a.replace(/\s/gi, '+'), 'oiSearchAjax', 9);
//		} else {
//			console.log(3);
		}
		*/
    });
})();


// cắt xén danh sách sản phẩm để tạo số lượng mong muốn
(function () {
    var len = jQuery('.thread-remove-endbegin').length || 0;
    if (len == 0) {
        return false;
    }

    // lấy HTML đầu tiên để tạo cho toàn bộ những cái còn lại, tránh spam nội dung trực tiếp
    var first_html = jQuery('.thread-remove-endbegin:first').html() || '';
    //	console.log(first_html);

    //
    jQuery('.thread-remove-endbegin').each(function () {
        jQuery(this).html(first_html);

        //
        var e = jQuery(this).attr('data-end') || 0,
            between = jQuery(this).attr('data-between') || 0,
            b = jQuery(this).attr('data-begin') || 0;

        // end -> xóa đằng sau, lấy đằng trước cho đủ end
        if (e > 0) {
            // Nếu tồn tại tham số lấy đoạn giữa -> xóa đằng trước -> lát xóa đằng sau nữa là ok
            if (between > 0) {
                for (var i = 0; i < between; i++) {
                    jQuery('li:first', this).remove();
                }
            }

            //
            for (var i = 0; i < 100; i++) {
                jQuery('li:last', this).remove();

                if (jQuery('li', this).length <= e) {
                    break;
                }
            }
        }
        // begin -> xóa đằng trước, lấy đằng sau cho đủ begin
        else if (b > 0) {
            for (var i = 0; i < 100; i++) {
                jQuery('li:first', this).remove();

                if (jQuery('li', this).length <= b) {
                    break;
                }
            }
        }
    });

    //
    jQuery('.thread-remove-endbegin li').show();

})();


// module hiển thị quảng cáo ngẫu nhiên
(function () {
    var len = jQuery('.each-to-share-ads').length || 0;
    if (len == 0) {
        return false;
    }

    //
    jQuery('.each-to-share-ads').each(function () {
        var len = jQuery('li', this).length;
        //		console.log( '---' + len );

        if (len > 0) {
            jQuery(this).show();

            jQuery('li', this).each(function () {
                var a = jQuery(this).attr('data-img') || '',
                    l = jQuery(this).attr('data-lnk') || '';
                if (a != '') {
                    //					console.log(a);
                    if (l == '') {
                        l = 'javascript:;';
                    }
                    jQuery(this).html('<a href="' + l + '"><img src="' + a + '" width="' + jQuery(this).width() + '" /></a>');
                }
            });

            // nếu có nhiều quảng cáo -> kiểm tra định dạng quảng cáo
            if (len > 1) {
                var slider = jQuery('ul', this).attr('data-slide') || '';
                lister = jQuery('ul', this).attr('data-list') || '';

                // Chạy slide
                if (slider == 1) {}
                // hiển thị theo list -> chả phải làm gì cả
                else if (lister == 1) {}
                // hiển thị theo kiểu chia sẻ
                else {
                    var i = 1,
                        min = 0,
                        max = len,
                        rand = Math.floor(Math.random() * (max - min)) + min;
                    //					console.log( rand );

                    //
                    jQuery('li', this).hide();
                    jQuery('li', this).eq(rand).show();
                }
            }
        }
    });

})();


// tạo hiệu ứng select với url trùng với link hiện tại
//try {
(function (u) {
    //	console.log(u);

    if (act == '') {
        jQuery('.eb-set-menu-selected a[href="./"], .eb-set-menu-selected a[href="' + web_link + '"]').addClass('current-url-select');
        return false;
    }

    // tạo class select với thẻ A trùng link đang xem
    jQuery('.eb-set-menu-selected a[href="' + u + '"], .eb-set-menu-selected a[href="' + u.split(web_link)[1] + '"]').addClass('current-url-select');
    //	console.log(jQuery('a.current-url-select').length);

    // nếu URL này không được tìm thấy -> thử theo canonical URL
    /*
    if ( jQuery('a.current-url-select').length == 0 ) {
    	jQuery('a[href="' + eb_this_current_url + '"]').addClass('current-url-select');
    }
    */

    // -> tạo select cho LI chứa nó
    /*
    	jQuery('li').each(function() {
    //		console.log( jQuery('a.current-url-select', this).length );
    		if ( jQuery('a.current-url-select', this).length > 0 ) {
    			jQuery(this).addClass('selected');
    		}
    	});
    	*/

    // add class vào thẻ LI có chứa thẻ A đang được select
    jQuery('.eb-set-menu-selected a.current-url-select').parent('li').addClass('selected');
    jQuery('.eb-set-menu-selected li.selected').parent('li').addClass('selected');

})(window.location.href.split('#')[0]);
/*
} catch ( e ) {
	console.log( WGR_show_try_catch_err( e ) );
}
*/


//
if (typeof confirm_df2_loaded != 'undefined') WGR_check_load_js_category();
