<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;

class Helper
{
    public static function dateFromDatetime($datetime)
    {
        $date = explode(" ", $datetime);
        return $date[0];
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        return $randomString;
    }

    public static function generateRandomNumber($length = 3)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        return $randomString;
    }

    public static function getStyle($orientation, $style_id)
    {
        switch ($style_id) {
            case 1:
                return '1 photo 4 x 6"';
                break;
//            case 2:
//                return '1 photo 4 x 6"';
//                break;
            case 3:
                if ($orientation == "Portrait")
                    return '3 photos strip + GIF';
                else
                    return '4 photos strip + GIF';
                break;
//            case 4:
//                return '1 photo 4 x 6"';
//                break;
            default:
                return '';
                break;
        }
    }
}
