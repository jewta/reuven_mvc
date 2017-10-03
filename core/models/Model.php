<?php

namespace reuven\models;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class Model
{
    public $db_model;

    public function __construct()
    {
        if(isset($GLOBALS['db'])){
            global $db;
            $this->db_model = $db;
        }
    }

    public function model_exec()
    {
        $querySQl = $this->db_model->getSQL();
        $prepare = $this->db_model->getPrepare();

        return $this->db_model->execSql($querySQl, $prepare);
    }
}