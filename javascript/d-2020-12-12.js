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

            //			thumbnail : '.banner-ads-media',
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

        //		thumbnail : 'ul li',
        size: jQuery('.banner-chan-trang li:first .ti-le-global').attr('data-size') || ''
    });

})();


// hiệu ứng xem video tại chỗ
var press_esc_to_quickvideo_close = false,
    current_ls_url = window.location.href;


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
WGR_reload_lazy_function();
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
    if (jQuery(document).height() > jQuery(window).height() * 1.5) {
    }
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
    if ($(document).height() - 250 < $(window).height()) {
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
    jQuery('.each-to-fix-ptags').removeClass('each-to-fix-ptags');

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
WGR_check_load_js_category();

// nút thêm sản phẩm vào giỏ hàng
var add_to_cart_running = false;
setTimeout(function () {
    jQuery('.click-jquery-add-to-cart').click(function () {
        // nếu trong chi tiết sản phẩm -> sẽ kiểm tra sản phẩm có đang bị đánh dấu hết hàng không
        if (typeof pid != 'undefined' && pid > 0) {
            if (product_post_status != 'publish') {
                a_lert('Xin lỗi quý khách! Sản phẩm hiện đang ngừng bán');
                return false;
            } else if (product_trv_trangthai * 1 === 7) {
                a_lert('Xin lỗi quý khách! Sản phẩm hiện đang hết hàng');
                return false;
            }
        }

        //
        //		if ( pid == 0 ) {
        if (add_to_cart_running == true) {
            console.log('add to cart running');
            return false;
        }
        add_to_cart_running = true;

        //
        var product_price = jQuery(this).attr('data-gia') || jQuery(this).attr('data-price') || '',
            //			tr = jQuery(this).attr('data-open-iframe') || 0,
            product_object = {};

        //
        if (product_price == '') {
            product_price = 0;

            if (pid > 0) {

                // nếu chưa chọn màu hoặc size -> yêu cầu chọn
                // sau sư dụng php để tạo list chọn size, color
                /*
                if ( _global_js_eb.check_size_color_cart() == false ) {
                	return false;
                }
                */

                if (typeof product_js.gm == 'number' && product_js.gm > 0) {
                    product_price = product_js.gm;
                }

                // lưu thông tin color, size để show trong giỏ hàng
                var a = g_func.getc('eb_cookie_cart_lists');
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
                a['_' + pid] = {
                    'size': jQuery('.oi_product_size li.selected').attr('data-name') || jQuery('.oi_product_size li:first').attr('data-name') || '',
                    'color': jQuery('.oi_product_color li.selected').attr('title') || jQuery('.oi_product_color li:first').attr('title') || '',
                    'quan': jQuery('#oi_change_soluong input').val() || jQuery('#oi_change_soluong select').val() || 1
                };
                //				console.log( a );
                g_func.setc('eb_cookie_cart_lists', escape(JSON.stringify(a)), 6 * 3600);
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

        _global_js_eb.cart_add_item(jQuery(this).attr('data-id') || pid || 0, product_object);
    });


    //
    jQuery('.click-jquery-show-quick-cart').click(function () {
        if (pid == 0) {
            if (WGR_check_option_on(cf_tester_mode)) console.log('for post only');
            return false;
        }

        // nếu có URL một website nào đó -> nhảy sang link web đó -> bán hàng thông qua lazada, shopee để cắt giảm chi phí
        if (_eb_product_supper_shop != '') {
            // thêm các thông số track
            var a = [];
            if (_eb_product_supper_shop.split('utm_source').length == 1) {
                a.push('utm_source=' + document.domain.replace(/\./g, '_'));
            }
            if (_eb_product_supper_shop.split('utm_medium').length == 1) {
                a.push('utm_medium=echbay_com');
            }
            if (_eb_product_supper_shop.split('utm_campaign').length == 1) {
                a.push('utm_medium=webgiare_org');
            }
            a = a.join('&');

            // nếu url không có dấu ? -> đổi lại dấu hỏi luôn
            if (_eb_product_supper_shop.split('?').length == 1) {
                a = '?' + a.substr(1);
            }
            _eb_product_supper_shop += a;

            //
            _global_js_eb.ga_event_track('Go to supper shop (' + document.domain + ')', '', {
                //				'category' : '',
                //				'label' : '',
                'action': 'go_to_shop'
            });
            window.open(_eb_product_supper_shop, '_blank');
            return false;
        }

        // Hiển thị quick cart dạng popup nếu quick cart không hiển thị sẵn
        if (WGR_show_or_scroll_to_quick_cart() == false) {
            // nếu đang xem trong iframe -> mở ra giỏ hàng luôn
            if (top != self) {
                parent.window.location = web_link + 'cart/?id=' + pid;
                return false;
            }

            // Hiển thị bình thường
            jQuery('#click_show_cpa').show();
            jQuery('body').addClass('body-no-scroll');
            jQuery('#click_show_cpa input[name="t_dienthoai"]').focus();

            //
            var a = jQuery(window).height() - jQuery('.cart-quick').height();
            if (a > 0) {
                a = a / 3;
            } else {
                a = 25;
            }
            jQuery('.cart-quick').css({
                'margin-top': a + 'px'
            });
        }
    });


    // Nút kép -> nhảy sang giỏ hàng hoặc mở quick cart
    jQuery('.click-jquery-quickcart-or-cart').click(function (e) {
        // Chuyển sang giỏ hàng nếu không có quick cart
        if (WGR_show_or_scroll_to_quick_cart() == false) {
            jQuery('.click-jquery-add-to-cart:first').click();
        }
    });


    //
    /*
    jQuery('.click-jquery-add-to-cart a, .click-jquery-quickcart-or-cart a').click(function() {
    	return false;
    });
    */


    // do trên fb có chút lỗi khi redrect trình duyệt 2 lần -> chỉnh lại phương thức hiển thị nút mua
    jQuery('.show-if-javascript').removeClass('show-if-javascript');
    jQuery('.hide-if-javascript').hide();


    //
    //	console.log('TEST');
    //	jQuery('.click-jquery-show-quick-cart:first').click();
}, 600);


// chuyển các URL video thành dạng list video
(function () {
    jQuery('.echbay-widget-youtube-padding .img-max-width').each(function () {
        var a = jQuery(this).html() || '';

        if (a != '') {
            jQuery('.echbay-widget-youtube-padding .vhidden').removeClass('vhidden');
            jQuery('.echbay-widget-youtube-remove').hide().remove();

            //
            var str = '',
                wit = jQuery(this).width(),
                hai = Math.ceil(wit * youtube_video_default_size),
                wit = Math.ceil(wit);

            //
            //			console.log(wit);
            //			console.log(hai);
            //			console.log(a);
            a = a.split("\n");
            //			console.log(a);

            for (var i = 0; i < a.length; i++) {
                a[i] = g_func.trim(a[i]);

                if (a[i] != '') {
                    a[i] = _global_js_eb.youtube_id(a[i]);
                }

                if (a[i] != '') {
                    if (WGR_check_option_on(cf_tester_mode)) console.log('Widget YouTube video: ' + a[i]);

                    str += '<div class="widget_echbay_youtube-node"><iframe src="//www.youtube.com/embed/' + a[i] + '" allowfullscreen="" frameborder="0" height="' + hai + '" width="' + wit + '"></iframe></div>';
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
    jQuery('.url-to-google-map').each(function () {
        var a = jQuery(this).html() || '',
            str = '',
            wit = 4 / 5,
            scrolling = jQuery(this).attr('data-scrolling') || '',
            localtion = jQuery(this).attr('data-localtion') || '',
            zoom = jQuery(this).attr('data-zoom') || 10,
            w = jQuery(this).attr('data-width') || '',
            h = jQuery(this).attr('data-height') || '',
            other_attr = '';
        //		console.log( localtion );

        // tự tạo localtion theo địa chỉ
        if (localtion != '') {
            localtion = g_func.non_mark(localtion);
            //			console.log( localtion );

            // danh sách địa chỉ
            other_attr += ' aria-label="' + localtion.replace(/\"/g, '&quot;') + '"';

            //
            if (zoom == '') {
                zoom = 14;
            }

            // thay thế url
            a = 'https://maps.google.com/maps?q=' + encodeURIComponent(localtion) + '&t=m&z=' + zoom.toString() + '&output=embed&iwloc=near'.replace(/\&/g, '&amp;');
            //			console.log( a );
        }

        if (a != '') {
            a = a.split("\n")[0];
            a = g_func.trim(a);

            if (a != '') {
                jQuery(this).show().html('');

                // tính toán kích thước
                if (w == '') {
                    w = '100%';
                }
                if (h == '' || global_window_width < 755) {
                    h = jQuery(this).width() * wit;
                    if (h > 450) {
                        h = 450;
                    }
                }
                if (scrolling == 'on') {
                    other_attr += ' scrolling="no"';
                }

                str += '<div class="widget_echbay_gg_map-node"><iframe src="' + a + '" width="' + w + '" height="' + h + 'px" frameborder="0" marginheight="0" marginwidth="0"' + other_attr + ' style="border:0" allowfullscreen=""></iframe></div>';

                jQuery(this).attr({
                    'data-iframe': escape(str)
                });
            }
        }
    });
})();


//
//console.log(act);

//
if (act == 'search') {
    jQuery('.thread-search-avt[data-img=""]').hide();

    //
    _global_js_eb.ga_event_track('', '', '', {
        //		'category' : '',
        //		'label' : '',
        'action': 'view_search_results'
    });
}

// google analytics event tracking
setTimeout(function () {
    if (pid > 0) {
        // đối với sản phẩm
        if (eb_wp_post_type == 'post') {
            _global_js_eb.ga_event_track('View product', document.title, '', {
                //				'category' : '',
                //				'label' : '',
                'items': [{
                    "id": "P" + pid,
                    "name": product_js.tieude,
                    "price": product_js.gm > 0 ? product_js.gm : product_js.gia
                }],
                'action': 'view_item',
            });
        }
        // mặc định là cho blog
        else {
            _global_js_eb.ga_event_track('View blog', document.title, '', {
                //				'category' : '',
                //				'label' : '',
                'action': 'view_blog'
            });
        }
    } else if (act == 'archive') {
        if (switch_taxonomy == 'category'
            || switch_taxonomy == 'post_tag'
            || switch_taxonomy == 'post_options') {
            if (WGR_check_option_on(cf_tester_mode)) console.log('test track for fb');

            //
            var track_arr = {
                'content_name': jQuery('h1:first a').html() || jQuery('h1:first').html() || document.title
            };

            //
            var ids = '',
                args = [];
            jQuery('.thread-list li').slice(0, 10).each(function () {
                var a = jQuery(this).attr('data-id') || '';

                if (a != '') {
                    ids += ',' + a;
                }

                //
                args.push({
                    "id": "P" + a,
                    "name": jQuery('a:first', this).attr('title') || '',
                    "price": jQuery(this).attr('data-price') || 0
                });
            });
            if (ids != '') {
                track_arr['content_ids'] = ids.substr(1).split(',');

                //
                _global_js_eb.fb_track('ViewContent', track_arr);
            } else {
                console.log('ids for facebok track not found');
            }

            //
            _global_js_eb.ga_event_track('View list', 'Xem danh sach san pham', '', {
                //				'category' : '',
                //				'label' : '',
                'items': args,
                'action': 'view_item_list'
            });
        }
    } else if (act == 'cart') {
        _global_js_eb.ga_event_track('View cart', 'Xem gio hang', '', {
            //			'category' : '',
            //			'label' : '',
            'action': 'view_cart'
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
    cache_for_quick_view_url = '';


// nhảy đến 1 ID đã được xác định (tương tự như thẻ A name)
(function () {
    var nol = 0,
        dm = document.domain;
    if (WGR_check_option_on(cf_auto_nofollow)) {
        nol = 1;
    }

    // với các link # -> tắt chức năng click
    //	jQuery('a[href="#"]').attr({
    //		href : 'javascript:;'
    //	}).click(function () {
    //		return false;
    //	});

    //
    jQuery('a').each(function () {
        var a = jQuery(this).attr('href') || '';
        //		console.log(a);

        if (a != '') {
            //			console.log(a.substr(0, 1));

            // Chế độ nhảy đến link
            if (a.substr(0, 1) == '#') {
                //				console.log(a);
                a = a.split('#')[1];

                if (a != '') {
                    jQuery(this).on('click', function () {
                        if (a == 'top') {
                            jQuery('body,html').animate({
                                scrollTop: 0
                            }, 800);

                            window.history.pushState("", '', window.location.href.split('#')[0]);
                        } else {
                            var goto = 0;

                            // chuyển tới 1 ID, class hoặc input nào đó
                            if (jQuery('#' + a).length > 0) {
                                goto = jQuery('#' + a).offset().top;
                            } else if (jQuery('a[name="' + a + '"]').length > 0) {
                                goto = jQuery('a[name="' + a + '"]').offset().top;
                            } else if (jQuery('.' + a).length > 0) {
                                goto = jQuery('.' + a).offset().top;
                            }
                            // mở popup đăng nhập/ đăng ký
                            else if (a == 'login' || a == 'register') {
                                g_func.opopup(a);
                                return false;
                            }

                            if (goto > 90) {
                                //								window.scroll( 0, goto - 110 );
                                jQuery('body,html').animate({
                                    scrollTop: goto - 110
                                }, 800);

                                window.location.hash = a;

                                return false;
                            }
                        }
                    });
                } else {
                    jQuery(this).attr({
                        href: 'javascript:;'
                    });
                }
            } else if (a == '/' || a == './' || a == web_link) {
                jQuery(this).addClass('wgr-rel-home');
            } else {
                // tự thêm nofollow vào các liên kết ngoài
                if (a.split('//').length > 1 && a.split('//')[1].split('/')[0] != dm) {
                    if (nol == 1) {
                        jQuery(this).attr({
                            target: '_blank',
                            rel: 'nofollow'
                        });
                    }
                }
                //				else {
                //					jQuery(this).addClass('wgr-rel-iframe');
                //				}
            }
        }
    });
})();


//
function WGR_thread_list_quickview() {
    if (cf_post_class_style == '' && cf_blog_class_style != '') {
        cf_post_class_style = cf_blog_class_style;
    }

    //
    if (top != self) {
        console.log('quick view not active in iframe');

        // chuyển các thẻ A, chưa có target thành target top
        WGR_set_a_target_top();
        setTimeout(function () {
            WGR_set_a_target_top();

            //
            setTimeout(function () {
                WGR_set_a_target_top();
            }, 1200);
        }, 600);

        //
        return false;
    }

    //
    //	jQuery('.thread-list li').each(function() {
    //		var a = jQuery(this).attr('data-id') || '';
    //		jQuery('.wgr-fake-iframe', this).attr({
    //			'data-id': a
    //		});
    //	});

    // giả lập mở trong iframe
    //	jQuery('.wgr-fake-iframe').addClass('thread-list-wgr-quickview').attr({
    //		'data-rel': 'iframe'
    //	});

    //
    jQuery('.thread-list-wgr-quickview').off('click').click(function () {

        var a = jQuery(this).attr('data-id') || '',
            h = jQuery(this).attr('href') || '',
            r = jQuery(this).attr('data-rel') || '';

        //
        a = g_func.number_only(a);

        // hỗ trợ mở cả blog
        if (h == '') {
            return false;
        }
        if (a > 0) {
            pid = a;
        }

        //
        if (dog('oi_ebe_quick_view') == null) {
            console.log('oi_ebe_quick_view not found!');
            return false;
        }

        // nếu có lệnh mở dạng iframe -> tải luôn trong đó
        if (r == 'iframe') {
            var scron = jQuery('#webgiare__top').height() || 0;
            if (scron > 90) {
                scron -= 90;
            }
            console.log(scron);
            window.scroll(0, scron);

            //
            jQuery('body').addClass('open-fake-in-iframe').removeClass('body-no-scroll');
            //			jQuery('#oi_ebe_quick_view').css({
            //				'margin-top': jQuery('#webgiare__top').height() + 'px'
            //			});
        } else {
            jQuery('body').removeClass('open-fake-in-iframe').addClass('body-no-scroll');
            jQuery('#oi_ebe_quick_view').removeAttr('style');
        }
        jQuery('#oi_ebe_quick_view').show();

        // nếu chưa có khung load dữ liệu thì add thêm vào
        //		if ( jQuery('div#ui_ebe_quick_view').length ==  0 ) {
        //			jQuery('#oi_ebe_quick_view .quick-view-padding').html('<div id="ui_ebe_quick_view">Đang tải...</div>');
        if (jQuery('iframe#ui_ebe_quick_view').length == 0) {
            jQuery('#oi_ebe_quick_view .quick-view-padding').html('<iframe id="ui_ebe_quick_view" name="ui_ebe_quick_view" src="about:blank" width="100%" height="600" frameborder="0">AJAX form</iframe>');
        }

        //
        window.history.pushState("", '', h);
        document.title = jQuery(this).attr('title') || cache_for_quick_view_title;

        // nếu ID mới này giống với ID cũ -> không load lại
        if (a > 0 && a == cache_for_quick_view_id) {
            set_new_height_for_quick_view = true;

            //
            setTimeout(function () {
                WGR_set_quick_view_height();

                //
                setTimeout(function () {
                    set_new_height_for_quick_view = false;
                }, time_for_new_height_quick_view);
            }, time_for_new_height_quick_view);

            return false;
        }
        // lưu lại phiên của cache
        cache_for_quick_view_id = a;

        // sử dụng ajax
        //		ajaxl('quick_view&id=' + a, 'ui_ebe_quick_view');

        //
        var device = 'desktop';
        if (global_window_width < 750) {
            device = 'mobile';
        }

        // sử dụng iframe
        dog('ui_ebe_quick_view').src = 'about:blank';

        var uri = '';
        // có ID- > load sản phẩm
        if (a > 0) {
            uri = web_link + 'eb-ajaxservice?set_module=quick_view&id=' + a + '&';
        }
        // không có -> load chính cái url được gửi tới
        else if (h.split('//').length == 1 || h.split(document.domain).length > 1) {
            uri = h;
            if (uri.split('?').length > 1) {
                uri += '&';
            } else {
                uri += '?';
            }
        } else {
            console.log('URL not support in popup!');
            return false;
        }
        uri += 'view_type=iframe&set_device=' + device + '&set_iframe=' + r;
        dog('ui_ebe_quick_view').src = uri;

        // chỉnh chiều cao cho iframe
        set_new_height_for_quick_view = true;

        jQuery('#ui_ebe_quick_view').height(jQuery(window).height() - 110);

        setTimeout(function () {
            WGR_set_quick_view_height();
        }, time_for_new_height_quick_view);

        jQuery('#ui_ebe_quick_view').on('load', function () {
            // sau đó thì không cho set lại chiều cao của quick view nữa
            setTimeout(function () {
                set_new_height_for_quick_view = false;
            }, time_for_new_height_quick_view);
        });

        //
        return false;

        //	}).removeClass('wgr-rel-iframe');
    });
}
WGR_thread_list_quickview();


//
if (press_esc_to_quickvideo_close == false) {
    if (WGR_check_option_on(cf_tester_mode)) console.log('create event close by ESC');

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
                    if (WGR_check_option_on(cf_tester_mode)) console.log('Close popup window');

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
    var i = 0,
        fn = 'frm_search',
        fn_rand = '';
    jQuery('.div-search form').each(function () {
        var a = jQuery(this).attr('name') || '';

        if (a == '') {
            if (i > 0) {
                fn_rand = i;
            }

            jQuery(this).attr({
                name: fn + fn_rand
            });

            i++;
        }
    });
})();

// menu for mobile
if (typeof document.frm_search != 'undefined') {
    if (jQuery('#click_add_to_search').length > 0) {
        jQuery('#value_add_to_search').off().keyup(function (e) {
            //	console.log(e.keyCode);
            if (e.keyCode == 13) {
                jQuery('#click_add_to_search').click();
                return false;
            }
            //	}).val( jQuery('input[type="search"]').val() || '' );
            //		}).val( document.frm_search.s.value );
        }).val(jQuery('form[name="frm_search"] input[type="search"]').val() || '');


        //
        jQuery('#click_add_to_search').off('click').click(function () {
            //			document.frm_search.s.value = jQuery('#value_add_to_search').val() || '';
            jQuery('form[name="frm_search"] input[type="search"]').val(jQuery('#value_add_to_search').val() || '');

            //
            //			if ( document.frm_search.s.value.length > 2 ) {
            if (jQuery('form[name="frm_search"] input[type="search"]').val().length > 2) {
                document.frm_search.submit();
            } else {
                jQuery('#value_add_to_search').focus();
            }
        });
    }
}


//
_global_js_eb._log_click_ref();


// báo lỗi nếu có thẻ dynamic_title_tag chưa được chuyển đổi
if (jQuery('dynamic_title_tag').length > 0) {
    a_lert('dynamic_title_tag cần được thay đổi sang DIV hoặc H*');
    console.log('================= dynamic_title_tag =================');
} else if (jQuery('dynamic_widget_tag').length > 0) {
    a_lert('dynamic_widget_tag cần được thay đổi sang DIV hoặc H*');
    console.log('================= dynamic_widget_tag =================');
}


// TEST
//g_func.opopup('login');
//g_func.opopup('register');
