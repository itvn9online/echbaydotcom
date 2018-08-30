



//console.log( Math.random() );
//alert( Math.random() );


// kiểm tra danh sách các file JS sau đã được add vào web chưa, chưa thì add thôi
(function () {
	//
	var v = Math.random();
	
	// add js
	var arr = [
		'javascript/slider.js',
		'javascript/functions.js',
		'javascript/eb.js',
		'javascript/all.js',
		''
	];
	
	//
	for ( var i = 0; i < arr.length; i++ ) {
		if ( arr[i] != '' ) {
			var check_id = 'echbaydotcom_' + arr[i].replace( /\//g, '_' );
			
			// kiểm tra nếu chưa có thì add vào
			if ( document.getElementById( check_id ) == null ) {
				console.log( 'Add JS: ' + url_for_add_elementor + arr[i] );
				
				//
				jQuery('head').append('<script id="' + check_id + '" src="' + url_for_add_elementor + arr[i] + '?v=' + v + '"><\/script>');
			}
		}
	}
	
	// add css
	var arr = [
		'css/d.css',
//		'css/d2.css',
		// -> admin css làm bị tràn khung thiết kế của widget blog -> bỏ
//		'css/admin.css',
		'css/admin-blog-widget.css',
		'css/elementor.css',
		''
	];
	
	//
	for ( var i = 0; i < arr.length; i++ ) {
		if ( arr[i] != '' ) {
			var check_id = 'echbaydotcom_' + arr[i].replace( /\//g, '_' );
			
			// kiểm tra nếu chưa có thì add vào
			if ( document.getElementById( check_id ) == null ) {
				console.log( 'Add CSS: ' + url_for_add_elementor + arr[i] );
				
				//
				jQuery('head').append('<link id="' + check_id + '" rel="stylesheet" href="' + url_for_add_elementor + arr[i] + '?v=' + v + '" type="text/css" media="all" \/>');
			}
		}
	}
})();



