<?php

namespace reuven\routes;

use reuven\helpers\HttpHelper;

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
        $segments = HttpHelper::getSegments();
        $segmentsStr = implode('/', $segments);

        for($i=0; $i<count($this->_config_routes); $i++){
            if($_SERVER['REQUEST_METHOD'] == $this->_config_routes[$i]['method']){
                $uri = trim($this->_config_routes[$i]['uri']);

                if(count($segments) !== substr_count($uri, '/')+1){
                    continue;
                }

                $uri = str_replace('#', '(.+)', $uri);

                if(preg_match('#^'.$uri.'$#', $segmentsStr)){
                    for($j=0; $j<count($this->_config_routes[$i]['regex']); $j++){
                        $segment = $segments[$this->_config_routes[$i]['regex'][$j]['segment']];
                        $rule = $this->_config_routes[$i]['regex'][$j]['rule'];

                        if(preg_match($rule, $segment) == 0){
                            continue 2;
                        }
                    }

                    $run_controller = trim($this->_config_routes[$i]['run_controller'], '/');

                    if(preg_match('^$^', $run_controller) !== 0){
                        $run_controller = preg_replace('#^'.$uri.'$#', $run_controller, $segmentsStr);
                    }

                    return $this->loadControllers($run_controller, $this->_config_routes[$i]['module']);
                }
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
            $title_controller = '\reuven\modules\\'.$title_module.'\controllers\\'.$data[1];
            $path_controller = 'application/modules/'.$title_module.'/controllers/'.$data[1].'.php';
            $title_method = $data[2];

            unset($data[0], $data[1], $data[2]);
        }
        else{
            $title_controller = '\reuven\controllers\\'.$data[0];
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