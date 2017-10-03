<?php

namespace reuven\helpers;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class InputHelper
{
    public function __construct()
    {
    }

    public static function input($value, $type='string', $is_html = false)
    {
        $result = trim($value);

        switch ($type){
            case 'int': $value = intval($value); break;
            case 'string': $value = strval($value); break;
            case 'float': $value = floatval($value); break;
            case 'double': $value = floatval($value); break;
            default: $value = strval($value);
        }

        if($is_html === true){
            $value = htmlentities($value, ENT_QUOTES);
        }

        return $result;
    }
}