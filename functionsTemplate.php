<?php


/*
 * Các đoạn HTML thường dùng
 */
function EBE_get_html_logo($set_h1 = 0)
{
    global $__cf_row;

    // v2 -> custom height
    $logo_tag = 'div';
    if ($set_h1 == 1 && $__cf_row['cf_h1_logo'] == 1) {
        global $act;

        if ($act == '') {
            $logo_tag = 'h1';
        }
    }

    //
    return '<' . $logo_tag . '><a href="./" class="web-logo d-block" style="background-image:url(' . $__cf_row['cf_logo'] . ');" aria-label="Home">&nbsp;</a></' . $logo_tag . '>';

    // v1 -> auto set height
    //	return '<div><a data-size="' . $__cf_row['cf_size_logo'] . '" href="./" class="web-logo ti-le-global d-block" style="background-image:url(' . $__cf_row['cf_logo'] . ');">&nbsp;</a></div>';
}

function EBE_get_html_search($class_for_search = 'div-search-margin', $echbay_search = '')
{
    global $current_search_key;
    global $__cf_row;

    if ($class_for_search == '') {
        $class_for_search = 'div-search-margin';
    }

    // sử dụng google tìm kiếm tùy chỉnh
    if ($__cf_row['cf_gse'] != '') {
        return "
<div class='" . $class_for_search . "'>
	<script>
	(function() {
		var cx = '" . $__cf_row['cf_gse'] . "';
		var gcse = document.createElement('script');
		gcse.type = 'text/javascript';
		gcse.async = true;
		gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(gcse, s);
	})();
	</script>
	<gcse:search></gcse:search>
</div>";
    }


    /*
     * class_for_search: tạo class riêng với 1 số trường hợp
     */

    //
    $echbay_search_type = 'post_type';
    if ($echbay_search != '') {
        $echbay_search_name = 'q';
        $echbay_search_type = 'for_post_type';
    } else if ($__cf_row['cf_search_by_echbay'] == 1) {
        $echbay_search = 'ebsearch/';
        $echbay_search_name = 'q';
        $echbay_search_type = 'for_post_type';
    } else {
        $echbay_search_name = 's';
    }

    //
    $post_type = 'post';
    if (defined('WGR_FOR_WOOCOMERCE')) {
        $current_search_key = trim(get_search_query());
        $post_type = 'product';
    }

    //
    return '
<div class="' . $class_for_search . '">
	<div class="div-search">
		<form role="search" method="get" action="' . web_link . $echbay_search . '">
			<input type="search" placeholder="' . EBE_get_lang('searchp') . '" value="' . $current_search_key . '" name="' . $echbay_search_name . '" autocomplete="' . EBE_get_lang('search_autocomplete') . '" aria-required="true" required>
			<input type="hidden" name="' . $echbay_search_type . '" value="' . $post_type . '" />
			<button type="submit" class="default-bg" aria-label="Search"><i class="fas fa-search"></i><span class="d-none">' . EBE_get_lang('search') . '</span></button>
			<span data-active="' . $class_for_search . '" class="span-search-icon cur"><i class="fas fa-search"></i></span>
		</form>
	</div>
	<div id="oiSearchAjax"></div>
</div>';
}

function EBE_get_html_cart($icon_only = 0, $cart_icon = 'fa fa-shopping-cart')
{
    $a = EBE_get_lang('cart');

    $text = '';
    if ($icon_only == 0) {
        $text = $a;
    }

    return '<div class="btn-to-cart cf"><a title="' . $a . '" href="' . web_link . 'cart" rel="nofollow"><i class="' . $cart_icon . '"></i> <span>' . $text . '</span> <em class="show_count_cart d-none">0</em></a></div>';
}

function EBE_get_html_profile()
{
    return '<div class="oi_member_func">.</div>';
}

function EBE_get_html_address($ops = [])
{
    global $__cf_row;

    //
    if ($__cf_row['cf_p_diachi'] == '') {
        $dc = EBE_get_lang('fd_diachi') . ' ' . nl2br(trim($__cf_row['cf_diachi']));
    } else {
        $dc = str_replace('%tmp.fd_diachi%', EBE_get_lang('fd_diachi'), $__cf_row['cf_p_diachi']);
    }

    //
    $str = '';
    if (isset($ops['title'])) {
        $str .= '<div class="footer-address-title">' . $ops['title'] . '</div>';
    }

    //
    $str .= EBE_html_template(WGR_get_html_template_lang('footer_address'), array(
        'tmp.cf_ten_cty' => $__cf_row['cf_ten_cty'],
        'tmp.dc' => $dc,
        'tmp.fd_hotline' => EBE_get_lang('fd_hotline'),
        'tmp.cf_call_hotline' => $__cf_row['cf_call_hotline'],
        'tmp.fd_dienthoai' => EBE_get_lang('fd_dienthoai'),
        'tmp.cf_call_dienthoai' => $__cf_row['cf_call_dienthoai'],
        'tmp.fd_email' => EBE_get_lang('fd_email'),
        'tmp.cf_email' => $__cf_row['cf_email']
    ));

    return $str;
}

function EBE_html_address($ops = [])
{
    echo EBE_get_html_address($ops);
}

function WGR_get_bigbanner()
{
    global $str_big_banner;

    //
    if ($str_big_banner == '') {
        return '<!-- HTML for big banner -->';
    }

    //
    //	return '<div class="oi_big_banner">' . $str_big_banner . '</div>';
    return $str_big_banner;
}

function WGR_get_footer_social()
{
    global $__cf_row;

    $str = '';

    if ($__cf_row['cf_facebook_page'] != '') {
        $str .= ' <li class="footer-social-fb"><a href="javascript:;" class="ahref-to-facebook" target="_blank" rel="nofollow" aria-label="Facebook page"><i class="' . EBE_get_lang('social_facebook') . '"></i> <span>Facebook</span></a></li>';
    }

    if ($__cf_row['cf_instagram_page'] != '') {
        $str .= ' <li class="footer-social-it"><a href="javascript:;" class="ahref-to-instagram" target="_blank" rel="nofollow" aria-label="Instagram page"><i class="' . EBE_get_lang('social_instagram') . '"></i> <span>Instagram</span></a></li>';
    }

    if ($__cf_row['cf_twitter_page'] != '') {
        $str .= ' <li class="footer-social-tw"><a href="javascript:;" class="each-to-twitter-page" target="_blank" rel="nofollow" aria-label="Twitter page"><i class="' . EBE_get_lang('social_twitter') . '"></i> <span>Twitter</span></a></li>';
    }

    if ($__cf_row['cf_youtube_chanel'] != '') {
        $str .= ' <li class="footer-social-yt"><a href="javascript:;" class="each-to-youtube-chanel" target="_blank" rel="nofollow" aria-label="Youtube chanel"><i class="' . EBE_get_lang('social_youtube') . '"></i> <span>Youtube</span></a></li>';
    }

    if ($__cf_row['cf_google_plus'] != '') {
        $str .= ' <li class="footer-social-gg"><a href="javascript:;" class="ahref-to-gooplus" target="_blank" rel="nofollow" aria-label="Google plus"><i class="' . EBE_get_lang('social_google_plus') . '"></i> <span>Google+</span></a></li>';
    }

    if ($__cf_row['cf_pinterest_page'] != '') {
        $str .= ' <li class="footer-social-tw"><a href="javascript:;" class="each-to-pinterest-page" target="_blank" rel="nofollow" aria-label="Pinterest page"><i class="' . EBE_get_lang('social_pinterest') . '"></i> <span>Twitter</span></a></li>';
    }

    //
    return '<ul class="footer-social text-center cf">' . $str . '</ul>';
}

function WGR_get_fb_like_box()
{
    global $__cf_row;

    //
    if ($__cf_row['cf_facebook_page'] == '') {
        return '<div class="facebook-likebox-null medium orgcolor">* <em>Dữ liệu của bạn đang bị thiếu, <a href="' . admin_link . 'admin.php?page=eb-config&tab=social&support_tab=cf_facebook_page" target="_blank" rel="nofollow">Bấm vào đây</a> để bổ sung thêm URL cho Fanpage Facebook của bạn.</em></div>';
    }

    //
    return '
	<div class="each-to-facebook">
		<div class="fb-page" data-small-header="false" data-hide-cover="false" data-show-facepile="true"></div>
	</div>';
}

function WGR_get_quick_register($form_name = 'frm_dk_nhantin')
{
    $l = array(
        'name' => EBE_get_lang('qreg_name'),
        'phone' => EBE_get_lang('qreg_phone'),
        'mail' => EBE_get_lang('qreg_email'),
        'submit' => EBE_get_lang('qreg_submit')
    );

    //
    return '
	<div class="hpsbnlbx">
		<form name="' . $form_name . '" method="post" action="process?set_module=quick-register" target="target_eb_iframe" onSubmit="return _global_js_eb.check_quick_register(\'' . $form_name . '\');">
			<div class="cf">
				<div class="quick-register-left quick-register-hoten"><span class="d-none">' . $l['name'] . '</span>
					<input type="text" name="t_hoten" value="" placeholder="' . $l['name'] . '" />
				</div>
				<div class="quick-register-left quick-register-phone d-none"><span class="d-none">' . $l['phone'] . '</span>
					<input type="text" name="t_dienthoai" value="" placeholder="' . $l['phone'] . '" />
				</div>
				<div class="quick-register-left quick-register-email"><span class="d-none">' . $l['mail'] . '</span>
					<input type="email" name="t_email" value="" placeholder="' . $l['mail'] . '" autocomplete="off" aria-required="true" required />
				</div>
				<div class="quick-register-left quick-register-submit">
					<button type="submit" class="cur">' . $l['submit'] . '</button>
				</div>
			</div>
		</form>
	</div>';
}

function EBE_echbay_license()
{
    global $__cf_row;
    global $web_name;
    global $year_curent;
    global $arr_private_info_setting;

    //
    $str = ($__cf_row['cf_web_name'] == '') ? $web_name : $__cf_row['cf_web_name'];

    // ghép thành chuỗi
    $str = '<div class="global-footer-copyright">' . EBE_get_lang('copyright') . ' &copy; ' . $year_curent . ' <span>' . $str . '</span> ' . EBE_get_lang('allrights') . ' <span class="powered-by-echbay">' . EBE_get_lang('poweredby') . ' <a href="#" title="Cung cấp bởi ' . $arr_private_info_setting['author'] . ' - Thiết kế web chuyên nghiệp" target="_blank" rel="nofollow">' . $arr_private_info_setting['site_upper'] . '</a></span></div>';

    //
    return $str;
}
