<?php

namespace reuven\controllers;

use \reuven\controllers\MainController;
use reuven\helpers\HttpHelper;
use \reuven\helpers\InputHelper;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class HomeController extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->loadHelpers('InputHelper');
    }

    public function actionAbout($a)
    {
        $this->loadModel('User', 'user');
        $getInfo = $this->user->getInfoDb();
        //$login = InputHelper::input($_GET['login'], 'string');

        $this->setVar('param', $getInfo);
        $this->callView('about');
    }

    public function actionPrice($a, $e)
    {
        echo $a.'='.$e;
        exit;
    }
}