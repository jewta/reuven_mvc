<?php

namespace reuven\controllers;

use \reuven\controllers\Controller;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class MainController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
}