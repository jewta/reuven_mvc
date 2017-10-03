<?php

namespace reuven\helpers;

use \reuven\routes\Routes;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class HttpHelper
{
    public function __construct()
    {
    }

    public static function getSegments()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $segments = explode('/', $uri);
        return $segments;
    }

    public static function prepareGet()
    {
        if(strpos($_SERVER['REQUEST_URI'], '?') !== false){
            $request_uri = explode('?', $_SERVER['REQUEST_URI']);
            $gets = array();
            parse_str($request_uri[1], $gets);
            $_GET = $gets;
            unset($gets);
        }
        else{
            $_GET = array();
        }
    }

    public static function getProtocol()
    {
        if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443){
            return 'https';
        }
        return 'http';
    }

    public static function getDomain()
    {
        return $_SERVER['HTTP_HOST'];
    }

    public static function status_404_helper()
    {
        ob_start();
        $route = new Routes();
        $route->loadControllers('ErrorController/status404', false);
        exit;
    }

    public static function getSetverProtocol()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }
}