<?php

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

$configs = array();

$configs['db']['is_db'] = true;
$configs['db']['host'] = 'localhost';
$configs['db']['port'] = '3306';
$configs['db']['user'] = 'reuven';
$configs['db']['password'] = 'reuven';
$configs['db']['database'] = 'reuven';
$configs['db']['charset'] = 'utf8';

$configs['load']['controllers'] = array('MainController');
$configs['load']['helpers'] = array('HttpHelper');

$configs['routes'][] = array(
    'uri' => 'about/#',
    'method' => 'GET',
    'run_controller' => 'HomeController/actionAbout/$1',
    'module' => false,
    'regex' => array(
        array(
            'segment' => '1',
            'rule' => '/^[0-9]{1,2}$/'
        )
    )
);

$configs['routes'][] = array(
    'uri' => 'price/#/#',
    'method' => 'GET',
    'run_controller' => 'HomeController/actionPrice/$1/$2',
    'module' => false,
    'regex' => array(
        array(
            'segment' => '1',
            'rule' => '/^[0-9]{1,2}$/'
        ),
        array(
            'segment' => '2',
            'rule' => '/^[a-z]{3}$/'
        )
    )
);