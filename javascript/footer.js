



/*
 * Các function sẽ được nạp từ theme -> nếu có thì chạy ở đây
 */
// for home
if (act == '') {
//if ( typeof ___eb_global_home_runing == 'function' ) {
    ___eb_global_home_runing();
}
// end home

// archive (category/ blog)
else if (act == 'archive') {
    // category
    if (switch_taxonomy == 'category' ||
        switch_taxonomy == 'post_tag' ||
        switch_taxonomy == 'post_options') {
        ___eb_list_post_run();
    }
    // blog
    else {
        ___eb_global_blogs_runing();
    }
}
// end archive

// for details
else if (act == 'single') {
    WGR_for_post_details();

    //
    jQuery('.wp-caption-text').each(function() {
        var a = jQuery(this).html() || '';
        if (a != '') {
            jQuery(this).html(a.replace(/\\n/g, '<br>'));
        }
    });
	
	// hiển thị bộ thẻ tag nếu có
	if ( jQuery('.thread-details-tags a').length > 0 ) {
		jQuery('.thread-details-tags').show();
	}
	//console.log( jQuery('.thread-details-tags a').length );
}
// end details

// for contact
else if (act == 'contact') {
    _global_js_eb.contact_func();
}
// end contact

// for cart
else if (act == 'cart') {
    _global_js_eb.cart_func();

    // hiển thị hộp nhập mã giảm giá nếu có
	if ( typeof co_ma_giam_gia != 'undefined' ) {
	    _global_js_eb.cart_discount_code(co_ma_giam_gia, '.eb-global-cart-coupon');
	}
	
	if ( typeof cart_shipping_content != 'undefined' && cart_shipping_content != '' ) {
		$('.eb-global-cart-shipping_fee').show();
	}
}
// end cart

// for 404
else if (act == '404') {
    _global_js_eb.page404_func();
}
// end 404




// tạo slider cho widget
(function() {
    var i = 0;

    // Tạo số lượng chạy slider dựa theo kích cỡ ảnh đã được chỉ định
    if (jQuery(window).width() > 768) {
        jQuery('.ebwidget-run-slider .thread-list50').attr({
            'data-visible': 2
        });
        jQuery('.ebwidget-run-slider .thread-list33').attr({
            'data-visible': 3
        });
        jQuery('.ebwidget-run-slider .thread-list25').attr({
            'data-visible': 4
        });
        jQuery('.ebwidget-run-slider .thread-list20').attr({
            'data-visible': 5
        });
    }

    //
    setTimeout(function() {
        jQuery('.ebwidget-run-slider').each(function() {
            var c = 'ebwidget-run-slider' + i,
                s = '';

            jQuery(this).addClass(c);

            c = '.' + c;

            //
            if (s == 'full') {
                var lh = jQuery(window).height();

                jQuery(c).height('auto');

                jQuery(c + ' .echbay-blog-avt').height(lh).css({
                    'line-height': lh + 'px'
                });
            } else if (s == 'li') {
                //				s = jQuery( c + ' li:first').height() + '/' + jQuery( c + ' li:first').width();
            } else {
                s = jQuery(c + ' li:first .echbay-blog-avt').attr('data-size') || '';
            }

            //
            console.log('Run widget slider ' + c + ' with size: ' + s);
            setTimeout(function() {
                jEBE_slider(c, {
//					size : jQuery( c + ' li:first .echbay-blog-avt').attr('data-size') || '',
                    size: s,

                    visible: jQuery(c + ' ul').attr('data-visible') || 1,
//					buttonListNext: false,
                    autoplay: true
//				}, function () {
                });
            }, 2000);

            i++;
        });
    }, 1000);
})();




// hệ thống banner quảng cáo
//___eb_logo_doitac_chantrang(6);
___eb_thread_details_timeend();
___eb_thread_list_li();
//___eb_add_space_for_breadcrumb();
//___eb_click_open_video_popup();


// fix menu khi cuộn chuột
//___eb_fix_left_right_menu();



// thuộc tính mở rộng khung tìm kiếm
(function() {
    jQuery('.span-search-icon').click(function() {
        var a = jQuery(this).attr('data-active') || '';

        if (a != '') {
            jQuery('.' + a + ' .div-search').toggleClass('active');
            jQuery('.' + a + ' input[type="search"]').focus();
        }
    });
})();


/*
 jQuery('.ebe-currency-format').each(function() {
 var a = jQuery(this).attr('data-num') || jQuery(this).html() || '';
 
 if ( a != '' && a != '0' ) {
 jQuery(this).html( g_func.money_format( a ) );
 }
 });
 */
_global_js_eb.ebe_currency_format();



// chỉnh lại chiều cao của các menu ngang, để nó không vượt quá chiều cao tổng thể
WGR_fixed_tab_height_in_line('.auto-fixed-menu-height');



// đánh dấu sản phẩm yêu thích
function WGR_click_add_product_to_favorite() {
    var cookie_name = 'wgr_product_id_user_favorite',
        limit_save = 30;

    //
    var str_favorite = g_func.getc(cookie_name);
    if (str_favorite == null || str_favorite == '') {
        str_favorite = '';
    }

    // Khi người dùng bấm vào lưu sản phẩm yêu thích
    jQuery('.add-to-favorite').click(function() {
        var a = jQuery(this).attr('data-id') || pid;
        var b = ___wgr_set_product_id_cookie(cookie_name, a, 50, limit_save);

        // nếu add không thành công -> đã có -> xóa sản phẩm khỏi favorite
        if (b == false) {
            //			jQuery(this).removeClass('selected');
            //			jQuery('.add-to-favorite[data-id="' + a + '"]').removeClass('selected').removeClass('fa-heart').addClass('fa-heart-o');
            jQuery('.add-to-favorite[data-id="' + a + '"]').removeClass('selected');

            // lấy lại cookie
            b = g_func.getc(cookie_name);

            // không có thì thoát luôn
            if (b == null || b == '') {
                return false;
            }

            // có thì xóa khỏi cookie luôn
            b = b.replace('[' + a + ']', '');
            //			console.log(str_favorite);
            g_func.setc(cookie_name, b, 0, limit_save);
        }
        // nếu không -> thêm class đánh dấu cho sản phẩm vừa chọn
        else {
            //			jQuery(this).addClass('selected');
            //			jQuery('.add-to-favorite[data-id="' + a + '"]').addClass('selected').removeClass('fa-heart-o').addClass('fa-heart');
            jQuery('.add-to-favorite[data-id="' + a + '"]').addClass('selected');

            //
            _global_js_eb.ga_event_track('', '', '', {
//				'category' : '',
//				'label' : '',
                'action': 'add_to_wishlist'
            });
        }

        // nếu người dùng đang đăng nhập
        if (isLogin > 0) {
            // lưu cookie vào database cho người dùng
            //			ajaxl('');
        }
    });

    //
    if (str_favorite == '') {
        return false;
    }
    var check_favorite = str_favorite.split('][');
    //	console.log(check_favorite);

    // chạy vòng lặp và tạo hiệu ứng select cho các sản phẩm đã lưu
    for (var i = 0; i < check_favorite.length; i++) {
        check_favorite[i] = check_favorite[i].replace(/\[|\]/g, '');
        //		check_favorite[i] = parseInt( check_favorite[i], 10 );
        //		console.log(check_favorite[i]);

        //
        //		jQuery('.add-to-favorite[data-id="' + check_favorite[i] + '"]').addClass('selected').removeClass('fa-heart-o').addClass('fa-heart');
        jQuery('.add-to-favorite[data-id="' + check_favorite[i] + '"]').addClass('selected');
    }
}
WGR_click_add_product_to_favorite();



//
/*
 if ( jQuery('.quick-register-email').length > 0 ) {
 jQuery('form[name="frm_dk_nhantin"] input[name="t_email"]').click(function() {
 _global_js_eb.add_primari_iframe();
 });
 }
 */



// social function /////////////////////////////////////////////////////////////////


// remove function
function ___eb_add_href_for_fb() {
    console.log('___eb_add_href_for_fb has been remove');
}

function ___eb_load_social_module() {
    console.log('___eb_load_social_module has been remove');
}



// chức năng thêm khung chat của fb vào web, sử dụng thì bỏ comment hàm gọi function đi là được
function add_fb_messages_for_page() {
    // thêm style cho khung chat
    jQuery('body').append('\
	<div id="cfacebook"> <a href="javascript:;" class="chat_fb"><i class="fa fa-facebook-square"></i> Hỗ trợ trực tuyến</a>\
		<div class="fchat each-to-facebook">\
			<div class="fb-page" data-tabs="messages" data-href="" data-width="250" data-height="400" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>\
		</div>\
	</div>');

    //
    jQuery(".chat_fb").click(function() {
        jQuery('.fchat').toggle('slow');
    });
}
//add_fb_messages_for_page();


// module mạng xã hội add xuống cuối file để ưu tiên giao diện chính của web được chạy trước


// tạo href cho facebook
WGR_create_href_for_facebook();




// load các module mạng xã hội
(function() {
	
	//
	if ( WGR_check_option_on ( cf_tester_mode ) ) {
		console.log('%c Social disable by cf_tester_mode', 'color: red;');
		return false;
	}
	
	// căn lại chiều rộng cho fb plugin
	// https://developers.facebook.com/docs/plugins/comments/
//	jQuery('.fb-like, .fb-comments').each(function() {
//		jQuery(this).attr({
//			'data-width': Math.ceil(jQuery(this).width() || 320)
//		});
//	});
	
	
    jQuery(document).ready(function() {
        window.___gcfg = {
            lang: 'vi'
        };

        /*
         * G+ button
         */
        var po = document.createElement('script');
        po.type = 'text/javascript';
        po.async = true;
        po.src = 'https://apis.google.com/js/platform.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);


        /*
         * G+ Comment
         */
        jQuery('.g-comments').each(function() {
            jQuery(this).attr({
                'data-width': jQuery(this).width()
            });
        });

        //
        if (jQuery('.g-comments').length > 0) {
            po = document.createElement('script');
            po.type = 'text/javascript';
//			po.async = true;
            po.src = 'https://apis.google.com/js/plusone.js';
            s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(po, s);
        }



        if (__global_facebook_id != '') {

            //
            if (WGR_check_option_on(cf_tester_mode)) console.log('FB app ID: ' + __global_facebook_id);
			
			//
             window.fbAsyncInit = function() {
				 FB.init({
//					 appId: __global_facebook_id,
					 xfbml: true,
					 version: 'v6.0'
				 });
             };
			
			// căn lại chiều rộng cho fb plugin
//			jQuery('.fb-like, .fb-comments').each(function() {
//				jQuery(this).attr({
//					'data-width': Math.ceil(jQuery(this).width() || 320)
//				});
//			});


            //
            var fb_lang = (function() {
                var lang = jQuery('html').attr('lang') || navigator.language || navigator.userLanguage || '';
                if (lang != '') {
                    lang = lang.split('_')[0].split('-')[0].toLowerCase();
                    //					console.log( lang );
                    if (lang == 'vi') {
                        return 'vi_VN';
                    }
                }
                return 'en_US';
            })();
//			console.log( fb_lang );

            //
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {
					return;
				}
				js = d.createElement(s);
				js.id = id;
				js.async = true;
				js.defer = true;
				js.crossorigin = "anonymous";
//				js.src = 'https://connect.facebook.net/' + fb_lang + '/sdk.js#xfbml=1&version=v3.2&appId=' + __global_facebook_id + '&autoLogAppEvents=1';
				js.src = 'https://connect.facebook.net/' + fb_lang + '/sdk.js#xfbml=1&version=v3.3&appId=' + __global_facebook_id + '&autoLogAppEvents=1';
				fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));


            /*
             (function(d, s, id) {
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) return;
             js = d.createElement(s);
             js.id = id;
             js.src = "//connect.facebook.net/" + fb_lang + "/sdk.js#xfbml=1&version=v2.9&appId=" + __global_facebook_id;
             fjs.parentNode.insertBefore(js, fjs);
             }(document, 'script', 'facebook-jssdk'));
             */


            //
            /*
             (function(d, s, id) {
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {
             return;
             }
             js = d.createElement(s);
             js.id = id;
             js.src = "//connect.facebook.net/" + fb_lang + "/sdk.js";
             fjs.parentNode.insertBefore(js, fjs);
             }(document, 'script', 'facebook-jssdk'));
             */
        }

    });

})();

// end social function /////////////////////////////////////////////////////////////////




// Thêm link bản quyền cho theme
jQuery('.powered-by-echbay a').attr({
//	href: 'https://echbay.com/'
	href: private_info_setting_site_url
});

// kiểm tra phiên bản HTML mới hay cũ
setTimeout(function() {
    //	if ( jQuery('title').length != 1 ) {
    if (jQuery('head title').length != 1) {
        a_lert('Lỗi HTML! vui lòng kiểm tra lại (HTML ERROR!)');
        console.log('Reinstall theme or call to +84984533228');
    }
}, 1200);




// xử lý với video youtube
___eb_click_open_video_popup();




// ẩn các khối không có dữ liệu
jQuery('.hide-if-zero-post, .hide-if-zero-other').each(function() {
    if (jQuery('li', this).length == 0) {
        jQuery(this).hide();
    }
});




// tạo nút share lên các mạng xã hội
(function() {
    if (pid == 0 || typeof post_canonical_url == 'undefined' || post_canonical_url == '') {
        return false;
    }

    //
    var url = encodeURIComponent(post_canonical_url),
        des = encodeURIComponent(jQuery('meta[name="description"]').attr('content') || ''),
        arr = {
            'facebook-f': 'https://facebook.com/sharer/sharer.php?u=' + url,
            'google': 'https://plus.google.com/share?url=' + url,
            'twitter': 'https://twitter.com/share?url=' + url + '&amp;text=' + des,
            'linkedin-in': 'https://www.linkedin.com/shareArticle?mini=true&amp;url=' + url,
            'pinterest-p': 'https://pinterest.com/pin/create/bookmarklet/?&amp;url=' + url + '&amp;description=' + des
        },
        str = '',
        utm_content = _date('Y-m-d', date_time);

    //
    for (var x in arr) {
        str += '<div class="jssocials-share jssocials-share-' + x + '"><a rel="nofollow" target="_blank" href="' + arr[x] + encodeURIComponent('?utm_source=' + x + '&utm_medium=share_button&utm_campaign=share' + isLogin + '&utm_content=' + utm_content) + '" class="jssocials-share-link"><i class="fab fa-' + x + ' jssocials-share-logo"></i></a></div>';
    }

    //
    jQuery('.each-to-share-social').html(str).addClass('jssocials-shares');
})();




// lưu thông tin các thuộc tính utm nếu có
(function(src) {
    if (src == '') {
        return false;
    }

    //
    var med = _global_js_eb._get('utm_medium'),
        cam = _global_js_eb._get('utm_campaign');

    //
    g_func.setc('WGR_ref_for_campaign', escape(JSON.stringify({
        'src': src,
        'med': med,
        'cam': cam
    })), 1, 30);
})(_global_js_eb._get('utm_source'));




// tạo link để admin chỉnh sửa khi cần
WGR_create_quick_link_edit_post();
WGR_open_local_link_in_iframe();




//
//jQuery('.fa').addClass('fas').removeClass('fa');



