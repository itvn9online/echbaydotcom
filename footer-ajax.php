<div id="webgiare__footer">
	<?php

//
$strCacheFilter = 'footer-ajax';
$main_content = _eb_get_static_html ( $strCacheFilter );
if ($main_content == false) {
	$main_content = '<h2>ERROR! get footer ajax content...</h2>';
}
else {
	include EB_THEME_PLUGIN_INDEX . 'common_content.php';
}
echo $main_content;

?>
</div>
<!-- quick view video -->
<div class="quick-video">
	<div class="quick-video-close big cf">
		<div class="lf f40 show-if-mobile"><i title="Close" class="fa fa-remove cur d-block"></i></div>
		<div class="text-right rf f20 hide-if-mobile"><i title="Close" class="fa fa-remove cur d-block"></i></div>
	</div>
	<div class="quick-video-padding">
		<div id="quick-video-content"></div>
	</div>
</div>
<?php



exit();



