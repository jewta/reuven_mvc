<?php

namespace reuven\controllers;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class Controller
{
    private $_variables;

    public function __construct()
    {
        $this->_variables = array();
    }

    public function setVar($title_var, $title_value)
    {
        $this->_variables[] = array(
            'title' => $title_var,
            'value' => $title_value
        );
    }

    public function callView($title_view)
    {
        for($i=0; $i<count($this->_variables); $i++){
            ${$this->_variables[$i]['title']} = $this->_variables[$i]['value'];
        }

        $path_view = '';

        if($this->titleModule()){
            $title_module = $this->titleModule();
            $path_view = 'application/module/'.$title_module.'/views/'.$title_view.'.php';

            if(file_exists($path_view)){
                require_once($path_view);
                return true;
            }
        }
        else{
            $path_view = 'application/views/'.$title_view.'.php';
            if(file_exists($path_view)){
                require_once($path_view);
                return true;
            }
        }
    }

    public function loadHelpers($title_helper)
    {
        $path_core_helper = 'core/helpers/'.$title_helper.'.php';
        $path_app_helper = 'application/helpers/'.$title_helper.'.php';

        if(file_exists($path_core_helper)){
            require_once($path_core_helper);
            return true;
        }

        if(file_exists($path_app_helper)){
            require_once($path_app_helper);
            return true;
        }

        return false;
    }

    public function loadLibraries($title_library, $aliac_library)
    {
        $path_lib = 'application/libraries/'.$title_library.'.php';

        if(file_exists($path_lib))
        {
            require_once($path_lib);

            $title_library = '\reuven\libraries\\'.$title_library;
            $this->$aliac_library = new $title_library();
            return true;
        }

        return false;
    }

    public function loadModel($title_model, $aliac_model)
    {
        $title_module = $this->titleModule();
        $path_model = '';
        $instance_model = '';

        if($title_module){
            $path_model = 'application/modules/'.$title_module.'/models/'.$title_model.'.php';
            $instance_model = '\reuven\modules\\'.$title_module.'\models\\'.$title_model;
        }
        else{
            $path_model = 'application/models/'.$title_model.'.php';
            $instance_model = '\reuven\models\\'.$title_model;
        }

        if(file_exists($path_model)){
            require_once($path_model);
            $this->$aliac_model = new $instance_model();
            return true;
        }

        return false;
    }

    public function titleModule()
    {
        return false;
    }
}