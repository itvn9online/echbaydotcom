<?php


// mặc định là giới thiệu về chủ sở hữu website
// https://developers.google.com/search/docs/guides/intro-structured-data
//if ( $schema_BreadcrumbList == '' ) {
if (empty($schema_BreadcrumbList)) {

    //$json_social_sameAs = '';
    $json_social_sameAs = [];

    if ($__cf_row['cf_facebook_page'] != '') {
        //$json_social_sameAs .= ',"' . $__cf_row['cf_facebook_page'] . '"';
        $json_social_sameAs[] = $__cf_row['cf_facebook_page'];
    }

    if ($__cf_row['cf_instagram_page'] != '') {
        //$json_social_sameAs .= ',"' . $__cf_row['cf_instagram_page'] . '"';
        $json_social_sameAs[] = $__cf_row['cf_instagram_page'];
    }

    if ($__cf_row['cf_twitter_page'] != '') {
        //$json_social_sameAs .= ',"' . $__cf_row['cf_twitter_page'] . '"';
        $json_social_sameAs[] = $__cf_row['cf_twitter_page'];
    }

    if ($__cf_row['cf_youtube_chanel'] != '') {
        //$json_social_sameAs .= ',"' . $__cf_row['cf_youtube_chanel'] . '"';
        $json_social_sameAs[] = $__cf_row['cf_youtube_chanel'];
    }

    if ($__cf_row['cf_google_plus'] != '') {
        //$json_social_sameAs .= ',"' . $__cf_row['cf_google_plus'] . '"';
        $json_social_sameAs[] = $__cf_row['cf_google_plus'];
    }

    if ($__cf_row['cf_pinterest_page'] != '') {
        //$json_social_sameAs .= ',"' . $__cf_row['cf_pinterest_page'] . '"';
        $json_social_sameAs[] = $__cf_row['cf_pinterest_page'];
    }

    //
    $dynamic_meta .= '<script type="application/ld+json">' . json_encode([
        '@context' => 'http://schema.org',
        '@type' => EBE_get_lang('schema_home_type'),
        "url" => web_link,
        "sameAs" => $json_social_sameAs,
        "name" => $web_name,
        "contactPoint" => [
            '@type' => 'ContactPoint',
            'telephone' => $__cf_row['cf_structured_data_phone'],
            'contactType' => 'customer support'
        ],
    ]) . '</script>';
}
// hoặc breadcrumb nếu có
else {
    //print_r($schema_BreadcrumbList);
    //echo implode(' ', $schema_BreadcrumbList);

    //
    $itemListElement = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'item' => [
                '@id' => web_link,
                'name' => EBE_get_lang('home'),
            ]
        ]
    ];
    foreach ($schema_BreadcrumbList as $v) {
        $itemListElement[] = $v;
    }

    //
    $dynamic_meta .= '<script type="application/ld+json">' . json_encode([
        '@context' => 'http://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $itemListElement
    ]) . '</script>';
}
