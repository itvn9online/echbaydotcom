<?php

$__cf_row ['cf_title'] = EBE_get_lang('lh_lienhe');
$group_go_to[] = ' <li><a href="' . _eb_full_url() . '" rel="nofollow">' . $__cf_row ['cf_title'] . '</a></li>';


//
$__cf_row ['cf_title'] .= ': ' . web_name . ' - ' . $__cf_row ['cf_abstract'];
$__cf_row ['cf_keywords'] = $__cf_row ['cf_title'];
$__cf_row ['cf_description'] = $__cf_row ['cf_title'];



include EB_THEME_PLUGIN_INDEX . 'global/contact_form.php';



