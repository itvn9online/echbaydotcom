<p class="small">* Bấm Ctrl + A rồi copy toàn bộ trang này cho vào file Excel hoặc Google trang tính.</p>
<p class="bold"><span class="cur click-change-css-table">Thêm border cho table để xem cho dễ</span></p>
<?php




$a = array(
	EB_THEME_PLUGIN_INDEX . 'outsource/javascript/jquery/3.2.1.min.js',
	EB_THEME_PLUGIN_INDEX . 'outsource/javascript/jquery/migrate-3.0.0.min.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/functions.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/eb.js',
	EB_THEME_PLUGIN_INDEX . 'javascript/all.js',
//	EB_THEME_PLUGIN_INDEX . 'javascript/edit_post.js'
	EB_THEME_PLUGIN_INDEX . 'echbay/js/export_functions.js'
);
foreach ( $a as $v ) {
//	if ( file_exists( $v ) ) {
		echo '<script type="text/javascript" src="' . str_replace( ABSPATH, web_link, $v ) . '?v=' . filemtime( $v ) . '"></script>' . "\n";
//	}
}




