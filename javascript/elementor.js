//console.log( Math.random() );
//alert( Math.random() );

// kiểm tra danh sách các file JS sau đã được add vào web chưa, chưa thì add thôi
(function () {
	//
	let v = Math.random();

	// add js
	let arr = [
		"javascript/slider.js",
		"javascript/functions.js",
		"javascript/eb.js",
		"javascript/all.js",
		"",
	];

	//
	for (let i = 0; i < arr.length; i++) {
		if (arr[i] != "") {
			let check_id = "echbaydotcom_" + arr[i].replace(/\//g, "_");

			// kiểm tra nếu chưa có thì add vào
			if (document.getElementById(check_id) == null) {
				console.log("Add JS: " + url_for_add_elementor + arr[i]);

				//
				jQuery("head").append(
					'<script id="' +
						check_id +
						'" src="' +
						url_for_add_elementor +
						arr[i] +
						"?v=" +
						v +
						'"></script>'
				);
			}
		}
	}

	// add css
	let arr = [
		"css/d.css",
		//		'css/d2.css',
		// -> admin css làm bị tràn khung thiết kế của widget blog -> bỏ
		//		'css/admin.css',
		"css/admin-blog-widget.css",
		"css/elementor.css",
		"",
	];

	//
	for (let i = 0; i < arr.length; i++) {
		if (arr[i] != "") {
			let check_id = "echbaydotcom_" + arr[i].replace(/\//g, "_");

			// kiểm tra nếu chưa có thì add vào
			if (document.getElementById(check_id) == null) {
				console.log("Add CSS: " + url_for_add_elementor + arr[i]);

				//
				jQuery("head").append(
					'<link id="' +
						check_id +
						'" rel="stylesheet" href="' +
						url_for_add_elementor +
						arr[i] +
						"?v=" +
						v +
						'" type="text/css" media="all" />'
				);
			}
		}
	}

	//
	/*
	if ( typeof _global_js_eb != 'undefined' && typeof jQuery != 'undefined' ) {
//		console.log( Math.random() );
		console.log( jQuery(document).height() * 2 );
		
		_global_js_eb.ebBgLazzyLoad();
		
		setTimeout(function () {
			_global_js_eb.ebBgLazzyLoad( jQuery(document).height() * 2 );
		}, 600);
	}
	*/
})();
