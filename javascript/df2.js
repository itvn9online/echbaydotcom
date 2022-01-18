
// tham số để xác nhận file df đã load xong
var confirm_df2_loaded = true;


function WGR_show_or_hide_to_top() {
    var new_scroll_top = window.scrollY || jQuery(window).scrollTop();

    //
    /*
    if ( didScroll == true ) {
    	didScroll = false;
    	*/
    if (new_scroll_top > 120) {
        jQuery('body').addClass('ebfixed-top-menu');

        //
        WGR_lazyload_footer_content();

        //
        if (new_scroll_top > 500) {
            //		if ( new_scroll_top < old_scroll_top ) {
            jQuery('body').addClass('ebshow-top-scroll');

            //
            _global_js_eb.ebBgLazzyLoad(new_scroll_top);
        } else {
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


function WGR_lazyload_footer_content() {
    if (lazyload_footer_content === true) {
        return false;
    }
    lazyload_footer_content = true;

    //
    if (act == '' && $('#home-lazyload').length > 0) {
        //console.log('Load home content');
        ajaxl('eb-home-lazy', 'home-lazyload', 9, function () {
            WGR_reload_lazy_function();
            jQuery('#home-lazyload').addClass('remove-min-height');
        });
    }

    //
    if ($('#footer-lazyload').length > 0) {
        //console.log('Load footer content');
        ajaxl('eb-footer-lazy', 'footer-lazyload', 9, function () {
            WGR_reload_lazy_function();
            jQuery('#footer-lazyload').addClass('remove-min-height');
        });
    }
}

// thêm class css cho font awesome 5
function WGR_update_font_awesome5() {
    if (WGR_check_option_on(cf_fontawesome_v5)) {
        $('.fa').addClass('fab');
    }
}

function WGR_reload_lazy_function() {
    console.log('Re-load WGR lazy function');
    
    //
    disable_eblazzy_load = false;
    _global_js_eb.ebe_currency_format();
    WGR_check_load_js_category();

    _global_js_eb.ebBgLazzyLoad();
    _global_js_eb.set_mobile_size();
    _global_js_eb.auto_margin();

    WGR_create_quick_link_edit_post();
    ___eb_click_open_video_popup();

    //	WGR_update_font_awesome5();

    WGR_create_href_for_facebook();
    
    //
    WGR_fixed_a_tag();
}

function WGR_create_quick_link_edit_post () {
    if (typeof WGR_action_create_quick_link_edit_post == 'function') {
        WGR_action_create_quick_link_edit_post();
    }
}

function ___eb_thread_details_timeend() {
    threadDetailsTimeend = setInterval(function () {
        if (jQuery('.thread-details-timeend').length == 0) {
            clearInterval(threadDetailsTimeend);
            return false;
        }

        //
        jQuery('.thread-details-timeend').each(function () {
            var te = jQuery(this).attr('data-timeend') || '';
            //		te = date_time + 24 * 3600 + 5;
            //		console.log( te );
            if (te != '') {
                var a = te - date_time;

                //
                if (a > 0) {
                    var mot_ngay = 24 * 3600,
                        giay = a % 60,
                        phut = a - giay,
                        phut = a > 3600 ? phut % 3600 : phut,
                        ngay = a > mot_ngay ? Math.ceil(a / mot_ngay) - 1 : 0,
                        gio = ngay > 0 ? a - ngay * mot_ngay : a,
                        //					so_du = a > mot_ngay ? a % mot_ngay : a,
                        //					phut = gio > 0 ? a % 3600 : a,
                        bbbbbbb = 1;

                    //
                    gio = Math.ceil(gio / 3600) - 1;
                    phut = phut / 60;
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
                            'data-timeend': te - 1
                        }).html('<li><div><span>' + ngay + '<em>Ngày</em></span></div></li>'
                            + '<li><div><span>' + gio + '<em>Giờ</em></span></div></li>'
                            + '<li><div><span>' + phut + '<em>Phút</em></span></div></li>'
                            + '<li><div><span>' + giay + '<em>Giây</em></span></div></li>')
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
function WGR_get_js_sub_category_to_menu(arr, by_id) {
    if (arr.length == 0) {
        return '';
    }
    // sắp xếp mảng từ to đến bé
    //	console.log( arr );
    arr.sort(function (a, b) {
        return parseFloat(b.order) - parseFloat(a.order);
    });
    //	console.log( arr );

    /*
    if ( typeof by_id == 'undefined' ) {
    	by_id = 0;
    }
    console.log('by_id: ' + by_id);
    */

    //
    //	var str = '<!-- JS for sub-category menu -->';
    var str = '',
        avt = '',
        icon = '',
        check_id = 0;

    // lấy theo ID cụ thể
    if (typeof by_id != 'undefined' && by_id > 0) {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i].id == by_id) {
                check_id = 1;
                break;
            }
        }
        if (check_id == 1) {
            return WGR_get_js_sub_category_to_menu(arr[i].arr);
        } else {
            return '';
        }
    }

    // lấy tất cả
    for (var i = 0; i < arr.length; i++) {
        /*
        if (by_id > 0) console.log('arr[i].id: ' + arr[i].id);
        if ( by_id > 0 ) {
        	check_id = 0;
        	if ( arr[i].id == by_id ) {
        		check_id = 1;
        	}
        }
        else {
        	check_id = 1;
        }
        if (by_id > 0) console.log('check_id: ' + check_id);
        */

        //
        //		if ( check_id == 1 && typeof arr[i].hidden == 'undefined' || arr[i].hidden * 1 != 1 ) {
        if (typeof arr[i].hidden == 'undefined' || arr[i].hidden * 1 != 1) {
            // tạo style ảnh nền cho menu
            avt = '';
            if (arr[i].avt != '') {
                avt = 'background-image:url(\'' + arr[i].avt + '\')';
            }
            icon = '';
            if (arr[i].icon != '') {
                icon = 'background-image:url(\'' + arr[i].icon + '\')';
            }

            //
            str += '<li style="order:' + arr[i].order + ';"><a href="' + arr[i].lnk + '" class="js-mega-menu"><span class="d-none avt_mega_menu avt_mega_menu' + arr[i].id + '" style="' + avt + '">&nbsp;</span> <span class="d-none icon_mega_menu icon_mega_menu' + arr[i].id + '" style="' + icon + '">&nbsp;</span> ' + arr[i].ten + '</a>' + WGR_get_js_sub_category_to_menu(arr[i].arr) + '</li>';
        }
    }
    if (str != '') {
        str = '<ul class="sub-menu cf">' + str + '</ul>';
    }

    //
    return str;
}

function WGR_get_js_category_to_menu(arr) {
    if (arr.length == 0) {
        return '';
    }
    // sắp xếp mảng từ to đến bé
    //	console.log( arr );
    arr.sort(function (a, b) {
        return parseFloat(b.order) - parseFloat(a.order);
    });
    //	console.log( arr );

    //
    var str = '<!-- JS for category menu -->';

    for (var i = 0; i < arr.length; i++) {
        if (typeof arr[i].hidden == 'undefined' || arr[i].hidden * 1 != 1) {
            str += '<li class="echbay-category-order"><a href="' + arr[i].lnk + '">' + arr[i].ten + '</a>' + WGR_get_js_sub_category_to_menu(arr[i].arr) + '</li>';
        }
    }

    return str;
}

function WGR_check_load_js_category(i) {
    if (typeof i == 'undefined') {
        i = 20;
    } else if (i < 0) {
        console.log('Max load eb_site_group or eb_blog_group');
        return false;
    }

    if (typeof eb_site_group == 'undefined') {
        setTimeout(function () {
            WGR_check_load_js_category(i - 1);
        }, 200);

        return false;
    }
    //	return false;

    //
    //	console.log( eb_site_group );

    // MENU chính -> xóa LI hiện tại, ghi nội dung mới vào
    // catgory
    //	if ( eb_site_group.length > 0 && jQuery('.wgr-load-js-category').length > 0 ) {
    if (jQuery('.wgr-load-js-category').length > 0) {
        jQuery('.wgr-load-js-category').after(WGR_get_js_category_to_menu(eb_site_group)).remove();
    }

    // blog group
    //	if ( eb_blog_group.length > 0 && jQuery('.wgr-load-js-blogs').length > 0 ) {
    if (jQuery('.wgr-load-js-blogs').length > 0) {
        jQuery('.wgr-load-js-blogs').after(WGR_get_js_category_to_menu(eb_blog_group)).remove();
    }

    // SUB-MENU -> bổ sung nội dung vào thẻ LI hiện tại
    // nhớ add thêm class echbay-category-order để order cho phần li
    // sub catgory
    //	if ( eb_site_group.length > 0 && jQuery('.wgr-load-js-sub-category').length > 0 ) {
    if (jQuery('.wgr-load-js-sub-category').length > 0) {
        jQuery('.wgr-load-js-sub-category').addClass('echbay-category-order').append(WGR_get_js_sub_category_to_menu(eb_site_group)).removeClass('wgr-load-js-sub-category');
    }

    // sub blog group
    //	if ( eb_blog_group.length > 0 && jQuery('.wgr-load-js-sub-blogs').length > 0 ) {
    if (jQuery('.wgr-load-js-sub-blogs').length > 0) {
        jQuery('.wgr-load-js-sub-blogs').addClass('echbay-category-order').append(WGR_get_js_sub_category_to_menu(eb_blog_group)).removeClass('wgr-load-js-sub-blogs');
    }

    // sub taxonomy group -> tự xác định taxonomy theo title được truyền vào
    if (jQuery('.wgr-load-js-sub-taxonomy').length > 0) {
        jQuery('.wgr-load-js-sub-taxonomy a').each(function () {
            var a = $(this).attr('title') || '';
            console.log(a);

            if (a != '') {
                a = a.split('|');

                if (a.length == 2) {
                    a[1] = a[1] * 1;

                    // truyền cả ID cần lấy vào nếu có thì function sẽ lọc đúng id đó luôn
                    if (a[0] == 'category') {
                        $(this).after(WGR_get_js_sub_category_to_menu(eb_site_group, a[1]));
                    } else if (a[0] == 'blogs') {
                        $(this).after(WGR_get_js_sub_category_to_menu(eb_blog_group, a[1]));
                    } else if (a[0] == 'post_options') {
                        $(this).after(WGR_get_js_sub_category_to_menu(eb_post_options_group, a[1]));
                    } else {
                        console.log('Title taxonomy: (category/blogs/post_options)|taxonomy_id');
                    }
                } else {
                    console.log('Title: (category/blogs/post_options)|taxonomy_id');
                }
            }
        });
        jQuery('.wgr-load-js-sub-taxonomy').removeClass('wgr-load-js-sub-taxonomy');
        //		jQuery('.wgr-load-js-sub-taxonomy').addClass('echbay-category-order').append( WGR_get_js_sub_category_to_menu( eb_blog_group ) ).removeClass('wgr-load-js-sub-taxonomy');
    }

}


//
function WGR_show_or_scroll_to_quick_cart() {

    // Nếu có thuộc tính hiển thị quick cart -> cuộn chuột đến đó
    if (jQuery('.clone-show-quick-cart').length > 0) {
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
function WGR_set_a_target_top() {
    jQuery('a').each(function () {
        var a = jQuery(this).attr('target') || '';
        if (a == '') {
            jQuery(this).attr({
                target: '_top'
            });
        }
    });
}

function close_ebe_quick_view() {
    jQuery('#oi_ebe_quick_view').hide();
    jQuery('body').removeClass('body-no-scroll').removeClass('open-fake-in-iframe');

    window.history.pushState("", '', current_ls_url);

    pid = current_pid_quicview;

    document.title = cache_for_quick_view_title;

    _global_js_eb.auto_margin();
}

function WGR_set_quick_view_height() {
    if (set_new_height_for_quick_view == false) {
        return false;
    }

    $('.quick-view-margin').css({
        'margin-top': '25px'
    });

    setTimeout(function () {
        WGR_set_quick_view_height();
    }, time_for_new_height_quick_view);

    //
    var h = jQuery('#ui_ebe_quick_view').contents().find('body').height() || 0;
    console.log(h);
    if (h == 0) {
        h = 600;
    } else if (h > $(window).height()) {
        h -= -200;
    } else {
        var new_margin = $(window).height() - h;
        console.log(new_margin);

        $('.quick-view-margin').css({
            'margin-top': Math.ceil(new_margin / 3) + 'px'
        });
    }
    console.log(h);
    jQuery('#ui_ebe_quick_view').height(h).scrollTop(0);

    //
    //	window.scroll( 0, 0 );
}

//
function ___eb_custom_login_done() {
    // thêm cookie để bỏ qua chế độ ebsuppercache
    g_func.setc('wgr_ebsuppercache_timeout', 1, 1800);

    // re-load lại trang
    window.location = window.location.href;
}

function ___eb_global_home_runing(r) {
    if (khong_chay_function_o_theme_nua == 1) {
        console.log('WARNING!!!!!!!!!!!!!!!!!!! function ___eb_global_home_runing has been runing...');
        return false;
    }
    khong_chay_function_o_theme_nua = 1;

    if (typeof Child_eb_global_home_runing == 'function') {
        Child_eb_global_home_runing();
    }
}


// tạo mục lục cho trang chi tiết sản phẩm, chi tiết blog
function WGR_list_of_content(for_tag, for_class) {

    //
    if (for_tag == '') {
        return false;
    }

    //
    if (typeof for_class == 'undefined' || for_class == '') {
        for_class = '.thread-content-bmask';
    } else if (for_class.substr(0, 1) != '.' && for_class.substr(0, 1) != '#') {
        for_class = '.' + for_class;
    }

    //
    var str = '';

    //
    for_tag = $.trim(for_tag).replace(/\s|\;/g, ',').split(',');
    //console.log(for_tag);

    //
    var pho_tag = [];
    for (var i = 0; i < for_tag.length; i++) {
        if (for_tag[i] != '') {
            pho_tag.push(for_class + ' ' + for_tag[i]);
        }
    }
    //console.log(pho_tag);
    if (pho_tag.length > 0) {
        var base_href = window.location.href;
        jQuery(pho_tag.join(',')).each(function () {
            var jd = jQuery(this).attr('id') || '',
                a = jQuery(this).html();

            if (jd == '') {
                jd = '_' + Math.random().toString(32).replace(/\./g, '_');

                jQuery(this).attr({
                    id: jd
                });
            }
            a = g_func.strip_tags(a);
            var a_href = g_func.non_mark_seo(a);
            a_href = a_href.replace(/\-/g, '_');

            str += '<li><a data-id="' + jd + '" href="' + base_href + '#' + a_href + '" class="eb-index-link eb-index-heading" title="' + a + '">' + a + '</a></li>';
        });
    }

    //
    if (str == '') {
        if (WGR_check_option_on(cf_tester_mode)) console.log('Post index ' + for_class + ' ' + for_tag.join(',') + ' not found!');
        return false;
    }
    str = '<div class="thread-details-index"><strong>Nội dung chính:</strong><ul>' + str + '</ul></div>';

    // cho đến thẻ H2 đầu tiên
    if (jQuery(for_class + ' p').length > 0) {
        jQuery(for_class + ' p:first').after(str);
    } else if (jQuery(for_class + ' .ul-default-style div').length > 0) {
        jQuery(for_class + ' .ul-default-style:first div:first').after(str);
    } else {
        jQuery(for_class).before(str);
        //jQuery(b + ' h2:first').before( str );
    }

    //
    jQuery('.thread-details-index li a').click(function () {
        var a = jQuery(this).attr('data-id') || '';

        if (a != '') {
            //window.scroll( 0, jQuery('#' + a).offset().top - 90 );
            jQuery('body,html').animate({
                scrollTop: jQuery('#' + a).offset().top - 90
            }, 800);
        }

        //
        return false;
    });

}


//
function WGR_for_post_details(function_for_post, function_for_blog) {

    //
    if (typeof switch_taxonomy == 'undefined') {
        console.log('switch_taxonomy not found');
        return false;
    }

    // post
    if (switch_taxonomy == 'post') {
        /*
        if ( typeof function_for_post == 'function' ) {
        	___eb_details_post_run( function_for_post );
        }
        else {
        	*/
        ___eb_details_post_run();
        WGR_list_of_content(cf_post_index_content);
        //		}
    }
    // blog, page...
    else if (typeof ___eb_global_blog_details_runing == 'function') {
        /*
        if ( typeof function_for_blog == 'function' ) {
        	___eb_global_blog_details_runing( function_for_blog );
        }
        else {
        	*/
        ___eb_global_blog_details_runing();
        WGR_list_of_content(cf_blog_index_content);
        //		}
    }

}


function WGR_create_href_for_facebook() {
    var f = function (lnk, clat) {
            if (lnk != '') {
                jQuery('.' + clat + ' div').attr({
                    'data-href': lnk
                }).each(function () {
                    var w = jQuery(this).attr('data-width') || jQuery(this).width() || 0;
                    if (w == 0) {
                        w = 180;
                    } else {
                        w = Math.ceil(w) - 1;
                    }

                    jQuery(this).attr({
                        //						'data-href': lnk,
                        'data-width': w
                    });
                });
            }
        },
        al = function (lnk, clat) {
            if (lnk != '') {
                jQuery('.' + clat).attr({
                    href: lnk
                    /*
                     }).each(function() {
                     jQuery(this).attr({
                     href: lnk
                     });
                     */
                });
            }
        };

    // data-href
    f(cf_facebook_page, 'each-to-facebook');
    f(cf_google_plus, 'each-to-gooplus');
    // href
    al(cf_facebook_page, 'ahref-to-facebook');
    al(cf_instagram_page, 'ahref-to-instagram');
    al(cf_google_plus, 'ahref-to-gooplus');
    al(cf_youtube_chanel, 'each-to-youtube-chanel');
    al(cf_twitter_page, 'each-to-twitter-page');
    if (typeof cf_pinterest_page != 'undefined') al(cf_pinterest_page, 'each-to-pinterest-page');

    // thiết lập link nhắn tin cho page
    if (cf_facebook_page != '') {
        var a = cf_facebook_page.split('/');
        //console.log(a[a.length - 1]);

        //
        jQuery('.ahref-to-messenger').attr({
            href: 'https://m.me/' + a[a.length - 1].split('?')[0].split('&')[0]
        });
    }
}

function WGR_ia_create_iframe_title(title_id, title) {
    document.title = title;

    wgr_id_obj_document_title[title_id] = title;
}

function WGR_ia_set_iframe_title(title_id) {
    if (typeof wgr_id_obj_document_title[title_id] != 'undefined') {
        document.title = wgr_id_obj_document_title[title_id];
    }
    $(window).resize();
}

function WGR_create_local_link_in_iframe(by_id, by_link) {
    if (top != self) {
        //		console.log('Call to parent function');

        //
        try {
            return top.WGR_create_local_link_in_iframe(by_id, by_link);
        } catch (e) {
            console.log(WGR_show_try_catch_err(e));
        }
    } else {
        //		console.log('WGR_open_local_link_in_iframe: ' + by_link);

        // Nếu mở đúng URL gốc -> load lại cả trang luôn
        if (root_url_for_open_ia == by_link) {
            if (root_url_for_open_count < 0) {
                //				console.log('Reset root link: ' + root_url_for_open_ia);
                return true;
            } else {
                root_url_for_open_count--;
            }
        }

        // nếu ID dài quá thì cũng bỏ qua, lưu ID ngắn thôi
        if (by_id.length > 150) {
            //			console.log(by_id + ' length: ' + by_id.length);
            return true;
        }

        // nếu số lượng IA nhiều quá -> cũng tải lại web
        if (cf_wgr_ia > 50) {
            cf_wgr_ia = 50;
        }
        if (WGR_IA_for_load_via_iframe === 0 || $('.wgr-ia-loader').length > cf_wgr_ia) {
            //			console.log('.wgr-ia-loader length: ' + $('.wgr-ia-loader').length);
            return true;
        }

        // nếu iframe đã được tạo thì hiển thị nó ra thôi
        if ($('#' + by_id).length > 0) {
            //			console.log('#' + by_id + ' exist');

            window.history.pushState("", '', by_link);
            $('body').addClass('wgria-no-scroll');
            $('.wgr-ia-loader').removeClass('selected');
            $('#' + by_id).addClass('selected');
            WGR_ia_set_iframe_title(by_link);

            return false;
        }

        // nếu là link gốc -> chỉ cần hiển thị main chính là được
        if (root_url_for_open_ia == by_link) {
            //			console.log('Open root link: ' + root_url_for_open_ia);

            window.history.pushState("", '', by_link);
            $('body').removeClass('wgria-no-scroll');
            $('.wgr-ia-loader').removeClass('selected');
            WGR_ia_set_iframe_title('root');

            return false;
        }
        // còn lại sẽ tạo mới
        else if (window.location.href != by_link) {
            window.history.pushState("", '', by_link);

            $('body').addClass('wgria-no-scroll');

            $('.wgr-ia-loader').removeClass('selected');
            $('body').append('<div id="' + by_id + '" class="wgr-ia-loader selected"><iframe name="' + by_id + '" src="' + by_link + '" width="100%" height="' + $(window).height() + '">WGR.IA Loader</iframe></div>');

            return false;
        }
    }
    return true;
}

function WGR_open_local_link_in_iframe() {
    if (cf_wgr_ia < 1) {
        if (WGR_check_option_on(cf_tester_mode)) 'WGR.IA disable by value zero ' + cf_wgr_ia;
        return false;
    }

    //
    //	console.log('WGR_open_local_link_in_iframe: ' + Math.random());

    try {
        var a = document.getElementsByTagName('a');
        //		console.log(a.length);
        for (var i = 0; i < a.length; i++) {
            if (typeof a[i].href != 'undefined' && a[i].href != '' && a[i].href.split(web_link).length > 1) {
                // không tác động tới các link riêng của wp
                if (a[i].href.split(web_link + 'wp-').length > 1) {
                    a[i].setAttribute('data-local', 'localWPlink');
                } else {
                    //					if ( WGR_check_option_on ( cf_tester_mode ) ) console.log(a[i].href);
                    a[i].setAttribute('data-local', 'localink');
                    a[i].setAttribute('data-href', a[i].href);
                }
            }
        }

        //
        if (top != self) {
            top.WGR_ia_create_iframe_title(root_url_for_open_ia, document.title);
            $(window).resize();
        }

        //
        $('.thread-list a[data-local="localink"], .thread-list-wgr-quickview').removeAttr('data-local');

        //
        //		$('#webgiare__top a[data-local="localink"]').click(function() {
        $('a[data-local="localink"]').click(function () {
            var by_link = $(this).attr('data-href') || $(this).attr('href') || '',
                a = $(this).attr('href') || '',
                tar = $(this).attr('target') || '';

            // nếu không có lệnh mở trong cửa sổ mới
            if (tar == '' || tar == '_top') {
                //				console.log(a);
                var a = g_func.non_mark_seo(a);
                if (a == '') {
                    a = 'home';
                }
                //				console.log('WGR.IA: ' + a);
                //				console.log(a.length);

                return WGR_create_local_link_in_iframe(a, by_link);
            } else {
                console.log('WGR.IA target: ' + tar);
            }
        });
    } catch (e) {
        console.log(WGR_show_try_catch_err(e));
    }
}


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

// nhảy đến 1 ID đã được xác định (tương tự như thẻ A name)
function WGR_fixed_a_tag() {
    var nol = 0,
        dm = document.domain;
    if (WGR_check_option_on(cf_auto_nofollow)) {
        nol = 1;
    }

    // tạo sự liên đới trong tab của flatsome
    //var current_url
    var i = 0;
    $('.tabbed-content .nav li').each(function () {
        $(this).attr({
            'data-id': i
        });

        $('a', this).attr({
            'href': '#WGR-flatsome-tab' + i,
            'rel': 'noopener',
        }).addClass('WGR-fixed-atag'); // thêm class này để tab không chịu ảnh hưởng bởi điều phối của thẻ tag trong content

        i++;
    });

    //
    var i = 0;
    $('.tabbed-content .tab-panels .panel').each(function () {
        $(this).attr({
            'id': 'WGR-flatsome-tab' + i,
            'data-id': i
        });

        i++;
    });


    // với các link # -> tắt chức năng click
    //	jQuery('a[href="#"]').attr({
    //		href : 'javascript:;'
    //	}).click(function () {
    //		return false;
    //	});

    //
    //jQuery('a').addClass('hash-go-to-html');
    // để tránh xung đột với các plugin tạo menu khác
    //jQuery('#ez-toc-container a').removeClass('hash-go-to-html');
    // thêm base vào các URL chuyển hướng -> để lệnh lỗi thì nó sẽ nhảy xuống link đó được luôn
    var base_href = window.location.href;
    jQuery('a').each(function () {
        var a = jQuery(this).attr('href') || '';
        var label = jQuery(this).attr('aria-label') || '';
        //console.log(a);

        //
        if (label == '') {
            $(this).attr({
                'aria-label': $(this).attr('title') || 'External'
            });
        }

        //
        if (a != '' && jQuery(this).hasClass('WGR-fixed-atag') == false) {
            //console.log(a.substr(0, 1));
            jQuery(this).addClass('WGR-fixed-atag');

            // Chế độ nhảy đến link
            if (a.substr(0, 1) == '#') {
                //console.log(a);
                a = a.split('#')[1];

                if (a != '') {
                    jQuery(this).attr({
                        href: base_href + '#' + a
                    }).on('click', function () {
                        if (a == 'top') {
                            jQuery('body,html').animate({
                                scrollTop: 0
                            }, 800);

                            window.history.pushState("", '', window.location.href.split('#')[0]);
                        } else {
                            /*
                            a = g_func.non_mark(a);
                            console.log(a);
                            return false;
                            */

                            //
                            WGR_goto_a_tag(a);
                        }
                    });
                } else {
                    jQuery(this).attr({
                        href: 'javascript:;',
                        rel: 'noopener'
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
                            //rel: 'nofollow'
                            //rel: 'noreferrer'
                            rel: 'nofollow noreferrer'
                        });
                    }
                }
                //else {
                //  jQuery(this).addClass('wgr-rel-iframe');
                //}
            }
        }
    });

    //
    jQuery('a[href="./"], a[href="/"]').attr({
        'aria-label': 'Home',
        'href': web_link
    });
    jQuery('a[href="javascript:;"], a[href="#"]').addClass('no-href').attr({
        'aria-label': 'No href'
    });
}
