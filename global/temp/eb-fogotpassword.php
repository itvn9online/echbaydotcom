<?php
if ($mtv_id > 0) {
    include_once EB_THEME_PLUGIN_INDEX . 'login-exist.php';
}
?>
<div class="popup-border" style="display:none;">
    <div class="cf opoup-title-bg default-bg">
        <div class="lf f70 bold">Quên mật khẩu</div>
        <div align="right" class="lf f30"><a onclick="g_func.opopup();" href="javascript:;">Đóng [x]</a></div>
    </div>
    <div class="popup-padding l19">
        <div>Nhập email đăng nhập của bạn tại đây, sau đó kiểm tra email và làm theo hướng dẫn để lấy lại mật khẩu.</div>
        <br />
        <form name="frm_quenpass" method="post" action="process?set_module=fogotpassword" target="target_eb_iframe" onSubmit="return _global_js_eb.check_forgot_pasword_frm();">
            <div id="frm_fpasswd_token" class="d-none"></div>
            <div>
                <label for="t_email"><strong>Email</strong></label>
            </div>
            <div>
                <input type="email" name="t_email" value="" placeholder="Email" aria-required="true" required />
            </div>
            <br />
            <div>
                <button type="submit" class="cur">Gửi mật khẩu</button>
                <a href="javascript:;" onclick="g_func.opopup('login')">Trở về trang đăng nhập</a>
            </div>
            <br>
        </form>
    </div>
</div>