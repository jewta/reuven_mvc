<?php

namespace reuven\models;

use \reuven\models\Model;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class User extends Model
{
    private $_table = 'user';

    public function __construct()
    {
        parent::__construct();
    }

    public function getInfoDb()
    {
        //$this->db_model->select('id, name, email');
//        $this->db_model->from($this->_table);
//        $this->db_model->where('name', ':name');
//        $this->db_model->addPrepare(':name', 'Ruvi');
//        $this->db_model->update(
//            array(
//                array(
//                    'field'=>'name',
//                    'value'=>'\'reva2\''
//                )
//            )
//        );
//        $this->model_exec();

        $this->db_model->select('id, name, email');
        $this->db_model->from($this->_table);
        return $this->model_exec();
    }
}