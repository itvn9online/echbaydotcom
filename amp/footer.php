<article class="amp-wp-article">
	<footer class="amp-wp-article-footer">
		<div class="amp-wp-meta amp-wp-tax-category"><a href="<?php echo web_link; ?>"><?php echo EBE_get_lang('home'); ?></a> <?php echo $amp_str_go_to; ?> </div>
	</footer>
</article>
<footer class="amp-wp-footer">
	<div>
		<p>&copy; <?php echo EBE_get_lang('amp_copyright'); ?> <?php echo $year_curent; ?> <?php echo web_name; ?>. <?php echo EBE_get_lang('amp_all_rights'); ?> - <a href="<?php echo $arr_private_info_setting['site_url']; ?>" target="_blank" rel="nofollow">AMP by <?php echo $arr_private_info_setting['site_upper']; ?></a></p>
		<p class="back-to-top"> <a href="#development=1"><?php echo EBE_get_lang('amp_development'); ?></a> | <a href="#top"><?php echo EBE_get_lang('amp_to_top'); ?></a></p>
	</div>
</footer>
<div class="amp-wp-comments-link"><a href="<?php echo $url_og_url; ?>"><?php echo EBE_get_lang('amp_full_version'); ?></a></div>
<br>
<?php
if ( $__cf_row['cf_ga_id'] != '' ) {
//include EB_THEME_PLUGIN_INDEX . 'amp/amp-analytics-v1.php';
include EB_THEME_PLUGIN_INDEX . 'amp/amp-analytics.php';
}

