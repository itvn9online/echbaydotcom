<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $export_tilte; ?></title>
<?php



$a = array(
    // trong admin có gắn thêm bản font-fontawesome-5.JS -> chỉ load bản font-fontawesome-4.CSS thì nó mới không xung đột
	EB_THEME_PLUGIN_INDEX . 'outsource/fa-4.7.0/i.css',
	//EB_THEME_PLUGIN_INDEX . 'outsource/fontawesome-free-5.0.6/css/fontawesome.css',
	//EB_THEME_PLUGIN_INDEX . 'outsource/fa-5.3.0/css/i.css',
	//EB_THEME_PLUGIN_INDEX . 'outsource/fa-5.3.0/css/v4-shims.min.css',
	EB_THEME_PLUGIN_INDEX . 'css/d.css',
	EB_THEME_PLUGIN_INDEX . 'css/d2.css',
	EB_THEME_PLUGIN_INDEX . 'css/admin.css',
	EB_THEME_PLUGIN_INDEX . 'css/admin-blog-widget.css'
);
foreach ( $a as $v ) {
//	$k = EB_THEME_PLUGIN_INDEX . $v;
//	echo $k . '<br>' . "\n";
//	if ( file_exists( $v ) ) {
		echo '<link rel="stylesheet" href="' . str_replace( ABSPATH, web_link, $v ) . '?v=' . filemtime( $v ) . '" type="text/css" media="all" />' . "\n";
//	}
}





$order_max_post_new = 0;
$str_ads_status = '';
$str_product_status = '';
$web_ad_link = web_link;

include EB_THEME_PLUGIN_INDEX . 'class/custom/admin-js.php';


?>
<style type="text/css">
body { opacity: .1; }
body.done {
	opacity: 1;
	-moz-transition: all 0.8s ease;
	-o-transition: all 0.8s ease;
	-webkit-transition: all 0.8s ease;
	transition: all 0.8s ease;
}
body,
a { color: #000; }
a:hover { text-decoration: underline; }
#headerTable { line-height: 16px; }
#headerTable.pd tr:first-child { font-weight: bold; }
#headerTable tr.selected,
#headerTable tr:hover { background: #f2f2f2; }
/* thêm border cho table */
#headerTable.set-border { border-left: 1px #ccc solid; }
#headerTable.set-border td {
	border-right: 1px #ccc solid;
	padding: 0 3px 0 6px;
}
#headerTable.set-border { border-top: 1px #ccc solid; }
#headerTable.set-border td {
	padding: 5px 3px 5px 6px;
	border-bottom: 1px #ccc solid;
}
#headerTable.set-border tr:first-child td,
#headerTable.set-border td.text-center {
	padding-left: 3px;
	padding-right: 3px;
}
#headerTable.moz-transition {
	-moz-transition: all 0.8s ease;
	-o-transition: all 0.8s ease;
	-webkit-transition: all 0.8s ease;
	transition: all 0.8s ease;
}
</style>
</head>
<body>
