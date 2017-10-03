<?php

namespace reuven\database;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class ActiveRecords
{
    private $_select = 'SELECT *';
    private $_from = '';
    private $_join = '';
    private $_where = '';
    private $_limit = '';
    private $_order_by = '';
    private $_group_by = '';
    private $_having = '';
    private $_truncate = '';
    private $_update = '';

    private $_lastEqualWhere = '';

    private $_prepared = array();

    private $_type_query = 'select';

    public function __construct(){}

    public function select($select)
    {
        $this->_select = 'SELECT '. $select;
    }

    public function from($from)
    {
        $this->_from = 'FROM '.$from;
    }

    public function join($table, $condition, $type = 'INNER')
    {
        $this->_join = $type.' JOIN '.$table.' ON '.$condition;
    }

    public function where($field, $value, $equals = '=', $type = 'AND')
    {
        $this->_condition($field.' '.$equals.' '.$value, $type);
    }

    public function whereIn($field, $values, $type = 'AND')
    {
        $this->_condition($field.' IN('.implode(',', $values).')', $type);
    }

    public function whereNotIn($field, $values, $type = 'AND')
    {
        $this->_condition($field.' NOT IN('.implode(',', $values).')', $type);
    }

    public function whereLike($field, $value, $type = 'AND')
    {
        $this->_condition($field.' LIKE '.$value, $type);
    }

    public function whereNoteLike($field, $value, $type = 'AND')
    {
        $this->_condition($field.' NOT LIKE '.$value, $type);
    }

    public function limit($position, $count = '')
    {
        $this->_limit = 'LIMIT '.$position;
        if($count != ''){
            $this->_limit .= ', '.$count;
        }
    }

    public function distinct($distinct = true)
    {
        if(is_bool($distinct) && $distinct){
            $this->_select = str_replace('SELECT ', 'SELECT DISTINCT ', $this->_select);
        }
        elseif(is_bool($distinct) && !$distinct){
            $this->_select = str_replace('SELECT DISTINCT ', 'SELECT ', $this->_select);
        }
    }

    public function orderBy($order_by, $sort = 'ASC')
    {
        $this->_order_by = 'ORDER BY '.$order_by.' '.$sort;
    }

    public function groupBy($group)
    {
        $this->_group_by = 'GROUPING BY '.$group;
    }

    public function having($having)
    {
        $this->_having = 'HAVING '.$having;
    }

    public function truncate($title_table)
    {
        $this->_truncate = 'TRUNCATE TABLE '.$title_table;
        $this->_type_query = 'truncate';
    }

    public function update($updates)
    {
        for($i=0; $i<count($updates); $i++){
            $this->_update = $updates[$i]['field'].' = '.$updates[$i]['value'].',';
        }

        $this->_update = trim($this->_update,',').' ';
        $this->_type_query = 'update';
    }

    public function delete()
    {
        $this->_type_query = 'delete';
    }

    public function addPrepare($key, $value)
    {
        $this->_prepared[] = array(
            'key' => $key,
            'value' => $value
        );
    }

    public function addPrepares($prepares)
    {
        $this->_prepared = $prepares;
    }

    public function getPrepare()
    {
        return $this->_prepared;
    }

    public function _condition($condition, $type)
    {
        if($this->_lastEqualWhere !== ''){
            $this->_where .= ' '.$this->_lastEqualWhere.' ';
        }
        $this->_where .= $condition;
        $this->_lastEqualWhere = $type;
    }

    public function getSQL()
    {
        $sql = '';

        if($this->_where !== ''){
            $this->_where = ' WHERE '.$this->_where;
        }

        if($this->_type_query == 'select'){
            $sql = $this->_select.' '.
                $this->_from.' '.
                $this->_join.' '.
                $this->_where.' '.
                $this->_order_by.' '.
                $this->_limit;
        }
        elseif($this->_type_query == 'update'){
            $this->_from = str_replace('FROM', '', $this->_from);

            $sql = 'UPDATE '.
                $this->_from.' SET '.
                $this->_update.' '.
                $this->_join.' '.
                $this->_where;
        }
        elseif($this->_type_query == 'delete'){
            $this->_from = str_replace('FROM', '', $this->_from);
            $sql = 'DELETE '.
                $this->_from.' FROM '.
                $this->_from.
                $this->_join.' '.
                $this->_where;
        }
        elseif($this->_type_query == 'truncate'){
            $sql = $this->_truncate;
        }
        return $sql;
    }

    public function clean()
    {
        $this->_select = 'SELECT *';
        $this->_from = '';
        $this->_join = '';
        $this->_where = '';
        $this->_limit = '';
        $this->_order_by = '';
        $this->_group_by = '';
        $this->_having = '';
        $this->_truncate = '';
        $this->_update = '';
        $this->_lastEqualWhere = '';
        $this->_prepared = array();
        $this->_type_query = 'select';
    }
}