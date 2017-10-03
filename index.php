<?php

namespace reuven\index;

use reuven\helpers\HttpHelper;
use reuven\routes\Routes;

define("ACCESS", true);
define("REUVEN_MODE", 'development'); //production

switch (REUVEN_MODE){
    case 'development':
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        break;

    case 'production':
        ini_set('error_reporting', ~E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('display_startup_errors', 'Off');
        break;
}

require_once('application/configs/configs.php');
require_once('core/Bootstrap.php');
require_once('core/routes/Routes.php');
require_once('core/models/Model.php');

$bootstrap = new Bootstrap();
$bootstrap->setConfig($configs);
$bootstrap->loadHelpers();
$bootstrap->loadControllers();

HttpHelper::prepareGet();

$helper = new HttpHelper();
$helper->prepareGet();

if($configs['db']['is_db']){
    require_once('core/database/ActiveRecords.php');
    require_once('core/database/Database.php');
    $bootstrap->loadDb();
}

//month: 11, Day: 21, Year: 2017

$string = '11-21-2017';
$pattern = '/([0-9]{2}) - ([0-9]{2}) - ([0-9]{4})/';
$replacestring = 'month: $1, Day: $2, Year: $3';

$e = preg_replace($pattern, $replacestring, $string);

$routes = new Routes();
$routes->setConfig($configs['routes']);

if($routes->loadRoutes() !== false){
    HttpHelper::status_404_helper();
}

