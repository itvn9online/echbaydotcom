<?php
//if ( !defined( 'WGR_APP_PATH' ) )die( 'No direct script access allowed #' . basename( __FILE__ . ':' . __LINE__ ) );

/*
* Cách sử dụng:
Wgr::$eb->BaseModelWgr->functionName();
*/

class BaseModelWgr
{
    public $base_url = '';

    public function __construct()
    {
        //
        $this->base_url = get_site_url() . '/';
    }

    public function add_css($f, $attr = 'rel="stylesheet" type="text/css" media="all"')
    {
        if ($f == '') {
            return false;
        }

        //
        if (!is_file($f)) {
            echo '<!-- File not exist: ' . str_replace(ABSPATH, '', $f) . ' -->';
            return false;
        }

        //
        echo '<link href="' . str_replace(ABSPATH, '', $f) . '?v=' . filemtime($f) . '" ' . $attr . ' />' . "\n";
        return true;
    }

    public function adds_css($arr, $attr = 'rel="stylesheet" type="text/css" media="all"')
    {
        foreach ($arr as $f) {
            $this->add_css($f, $attr);
        }
    }

    public function add_js($f, $attr = 'type="text/javascript" defer')
    {
        if ($f == '') {
            return false;
        }

        //
        if (!is_file($f)) {
            echo '<!-- File not exist: ' . str_replace(ABSPATH, '', $f) . ' -->';
            return false;
        }

        //
        echo '<script src="' . str_replace(ABSPATH, '', $f) . '?v=' . filemtime($f) . '" ' . $attr . '></script>' . "\n";
        return true;
    }

    public function adds_js($arr, $attr = 'type="text/javascript" defer')
    {
        foreach ($arr as $f) {
            $this->add_js($f, $attr);
        }
    }
}
