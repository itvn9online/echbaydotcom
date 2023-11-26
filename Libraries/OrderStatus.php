<?php

//namespace App\Libraries;

class OrderStatus
{

    // order status
    const BLACK_LIST = 12;

    public static function getList($key = '')
    {
        $arr = [
            self::BLACK_LIST => '',
        ];

        //
        if ($key == '') {
            return $arr;
        }

        //
        if (isset($arr[$key])) {
            return $arr[$key];
        }
        return '';
    }
}
