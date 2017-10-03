<?php

namespace reuven\controllers;

use \reuven\controllers\Controller;
use reuven\helpers\HttpHelper;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class ErrorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function status404()
    {
        header(HttpHelper::getSetverProtocol().' 404 Not Found');
        $this->callView('page_404');
    }
}