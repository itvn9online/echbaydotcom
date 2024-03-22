/*
 * d blog
 */

function ___eb_global_blog_details_runing(r) {
	if (khong_chay_function_o_theme_nua == 1) {
		console.log(
			"WARNING!!!!!!!!!!!!!!!!!!! function ___eb_global_blog_details_runing has been runing..."
		);
		return false;
	}
	khong_chay_function_o_theme_nua = 1;

	if (typeof Child_eb_global_blog_details_runing == "function") {
		Child_eb_global_blog_details_runing();
	}

	// lưu cookie tạo view cho blog, page
	if (pid > 0) {
		var check_update_views = g_func.getc("wgr_post_id_view_history"),
			new_id = "[" + pid + "]";
		if (WGR_check_option_on(cf_tester_mode)) {
			console.log(check_update_views);
		}

		// nếu không có dữ liệu hoặc xem nhiều tin quá -> xóa bớt đi thôi
		if (
			check_update_views == null ||
			check_update_views.split("][").length > 30
		) {
			check_update_views = "";
		}

		//
		if (check_update_views.includes(new_id) == false) {
			g_func.setc(
				"wgr_post_id_view_history",
				new_id + check_update_views,
				0,
				7
			);
		}
	}
}
