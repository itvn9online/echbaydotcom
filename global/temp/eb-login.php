<?php
if ( $mtv_id > 0 ) {
    include_once EB_THEME_PLUGIN_INDEX . 'login-exist.php';
}
?>
<div class="popup-border" style="display:none;">
    <div class="cf opoup-title-bg default-bg">
        <div class="lf f70 bold">Đăng nhập</div>
        <div align="right" class="lf f30"><a onclick="g_func.opopup();" href="javascript:;">Đóng [x]</a></div>
    </div>
    <div class="popup-padding l19">
        <form name="frm_dangnhap" method="post" action="process/?set_module=login" target="target_eb_iframe" onSubmit="return _global_js_eb.check_login_frm();">
            <div id="frm_login_token" class="d-none"></div>
            <div>
                <label class="bold"><?php echo EBE_get_lang('login_username'); ?></label>
            </div>
            <div>
                <input type="text" name="t_email" value="" placeholder="<?php echo EBE_get_lang('login_username'); ?>" aria-required="true" required />
            </div>
            <br />
            <div>
                <label class="bold">Mật khẩu</label>
            </div>
            <div>
                <input type="password" name="t_matkhau" value="" placeholder="Password" aria-required="true" required />
            </div>
            <br />
            <div>
                <input type="checkbox" name="t_remember" id="label_for_t_remember" value="1" />
                <label for="label_for_t_remember" style="color:#666">Duy trì trạng thái đăng nhập</label>
            </div>
            <br />
            <div>
                <button type="submit" class="cur btn primary">Đăng nhập</button>
            </div>
            <br />
            <div><a href="javascript:;" onClick="g_func.opopup('fogotpassword');">Bạn quên mật khẩu? lấy lại mật khẩu tại đây</a></div>
        </form>
    </div>
</div>
