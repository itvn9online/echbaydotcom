<?php


// mặc định là giới thiệu về chủ sở hữu website
// https://developers.google.com/search/docs/guides/intro-structured-data
//if ( $schema_BreadcrumbList == '' ) {
if ( empty( $schema_BreadcrumbList ) ) {

    $json_social_sameAs = '';

    if ( $__cf_row[ 'cf_facebook_page' ] != '' ) {
        $json_social_sameAs .= ',"' . $__cf_row[ 'cf_facebook_page' ] . '"';
    }

    if ( $__cf_row[ 'cf_instagram_page' ] != '' ) {
        $json_social_sameAs .= ',"' . $__cf_row[ 'cf_instagram_page' ] . '"';
    }

    if ( $__cf_row[ 'cf_twitter_page' ] != '' ) {
        $json_social_sameAs .= ',"' . $__cf_row[ 'cf_twitter_page' ] . '"';
    }

    if ( $__cf_row[ 'cf_youtube_chanel' ] != '' ) {
        $json_social_sameAs .= ',"' . $__cf_row[ 'cf_youtube_chanel' ] . '"';
    }

    if ( $__cf_row[ 'cf_google_plus' ] != '' ) {
        $json_social_sameAs .= ',"' . $__cf_row[ 'cf_google_plus' ] . '"';
    }

    if ( $__cf_row[ 'cf_pinterest_page' ] != '' ) {
        $json_social_sameAs .= ',"' . $__cf_row[ 'cf_pinterest_page' ] . '"';
    }

    //
    $dynamic_meta .= _eb_del_line( '
<script type="application/ld+json">
{
    "@context": "http:\/\/schema.org",
    "@type": "' . EBE_get_lang( 'schema_home_type' ) . '",
    "url": "' . web_link . '",
    "sameAs": [' . substr( $json_social_sameAs, 1 ) . '],
    "name": "' . _eb_str_block_fix_content( $web_name ) . '",
	"contactPoint": {
		"@type": "ContactPoint",
		"telephone": "' . $__cf_row[ 'cf_structured_data_phone' ] . '",
		"contactType": "customer support"
	}
}
</script>' );

}
// hoặc breadcrumb nếu có
else {

    //	print_r( $schema_BreadcrumbList );

    $dynamic_meta .= _eb_del_line( '
<script type="application/ld+json">
{
	"@context": "http:\/\/schema.org",
	"@type": "BreadcrumbList",
	"itemListElement": [{
		"@type": "ListItem",
		"position": 1,
		"item": {
			"@id": "' . str_replace( '/', '\/', web_link ) . '",
			"name": "' . str_replace( '"', '&quot;', EBE_get_lang( 'home' ) ) . '"
		}
	} ' . implode( ' ', $schema_BreadcrumbList ) . ' ]
}
</script>' );

}