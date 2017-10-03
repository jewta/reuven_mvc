<?php

namespace reuven\index;

use reuven\database\Database;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class Bootstrap
{
    private $_configs;

    public function __construct()
    {
        $this->_configs = array();
    }

    public function setConfig($configs)
    {
        $this->_configs = $configs;
    }

    public function loadHelpers()
    {
        $helpers = $this->_configs['load']['helpers'];

        for($i=0; $i<count($helpers); $i++){
            $path_core_helper = 'core/helpers/'.$helpers[$i].'.php';
            $path_app_helper = 'application/helpers/'.$helpers[$i].'.php';

            if(file_exists($path_core_helper)){
                require_once($path_core_helper);
            }

            if(file_exists($path_app_helper)){
                require_once($path_app_helper);
            }
        }
    }

    public function loadControllers()
    {
        $path_core_controller = 'core/controllers/Controller.php';
        $controllers = $this->_configs['load']['controllers'];

        if(file_exists($path_core_controller)){
            require_once($path_core_controller);
        }

        for($i=0; $i<count($controllers); $i++){
            $path_app_controller = 'application/controllers/'.$controllers[$i].'.php';

            if(file_exists($path_app_controller)){
                require_once($path_app_controller);
            }
        }
    }

    public function loadDb()
    {
        $db = new Database();
        $db->setHost($this->_configs['db']['host']);
        $db->setPort($this->_configs['db']['port']);
        $db->setUser($this->_configs['db']['user']);
        $db->setPassword($this->_configs['db']['password']);
        $db->setDatabase($this->_configs['db']['database']);
        $db->setCharset($this->_configs['db']['charset']);

        $db->initDb();

        $GLOBALS['db'] = $db;
    }
}