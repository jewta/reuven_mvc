<?php

namespace eshoper\routes;

use \eshoper\helpers\HttpHelper;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class Routes
{
    private $_config_routes;

    public function __construct()
    {
        $this->_config_routes = array();
    }

    public function setConfig($config)
    {
        $this->_config_routes = $config;
    }

    //анализ какой пришел запрос и какой контроллер выбрать
    public function loadRoutes()
    {
        $request_uri_str = implode('/', HttpHelper::getUri());

        for($i=0; $i<count($this->_config_routes); $i++){
            $uri = trim($this->_config_routes[$i]['uri'], '/');
            $controller = $this->_config_routes[$i]['controller'];
            $is_module = $this->_config_routes[$i]['module'];

            if(preg_match("~$uri~", $request_uri_str)){
                $controller = preg_replace("~$uri~", $controller, $request_uri_str);
                return $this->loadControllers($controller, $is_module);
            }
        }
        return false;
    }

    //подключение контроллера
    public function loadControllers($run_controller, $is_module)
    {
        $data = explode('/', $run_controller);
        $title_controller = '';
        $path_controller = '';
        $title_method = '';

        if($is_module){
            $title_module = $data[0];
            $title_controller = '\eshoper\modules\\'.$title_module.'\controllers\\'.$data[1];
            $path_controller = 'application/modules/'.$title_module.'/controllers/'.$data[1].'.php';
            $title_method = $data[2];

            unset($data[0], $data[1], $data[2]);
        }
        else{
            $title_controller = '\eshoper\controllers\\'.$data[0];
            $path_controller = 'application/controllers/'.$data[0].'.php';
            $title_method = $data[1];
            unset($data[0], $data[1]);
        }



        $params = array_values($data);


        if(file_exists($path_controller)){
            require_once($path_controller);
        }

        if(class_exists($title_controller) && method_exists($title_controller, $title_method)){
            $customController = new $title_controller();
            call_user_func_array(array($customController, $title_method), $params);
            return true;
        }

        return false;
    }
}