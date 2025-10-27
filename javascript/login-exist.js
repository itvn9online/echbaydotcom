// nếu người dùng đang đăng nhập mà không có cookie tạo ebsuppercache -> tạo nó thôi
(function () {
	var a = g_func.getc("wgr_ebsuppercache_timeout");
	//console.log(a);

	//
	if (a == null) {
		g_func.setc("wgr_ebsuppercache_timeout", 1, 1800);

		// nạp lại trang
		setTimeout(function () {
			window.location = window.location.href;
		}, 200);
	}
})();
