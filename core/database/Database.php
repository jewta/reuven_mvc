<?php

namespace reuven\database;

use \reuven\database\ActiveRecords;

if(!defined("ACCESS")){ exit('Вы хотите открыть системный файл!'); }

class Database extends ActiveRecords
{
    private $_host;
    private $_port;
    private $_user;
    private $_password;
    private $_database;
    private $_charset;
    private $_pdo;

    public function __construct()
    {
        parent::__construct();
    }

    public function setHost($host)
    {
        $this->_host = $host;
    }

    public function getHost()
    {
        return $this->_host;
    }

    public function setPort($port)
    {
        $this->_port = $port;
    }

    public function getPort()
    {
        return $this->_port;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    } 

    public function getUser()
    {
        return $this->_user;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setDatabase($database)
    {
        $this->_database = $database;
    }

    public function getDatabase()
    {
        return $this->_database;
    }

    public function setCharset($charset)
    {
        $this->_charset = $charset;
    }

    public function getCharset()
    {
        return $this->_charset;
    }

    public function initDb()
    {
        $mysql = 'mysql:host='.$this->_host.'; port='.$this->_port.';dbname='.$this->_database.';charset='.$this->_charset;
        $this->_pdo = new \PDO($mysql, $this->_user, $this->_password);
    }

    public function execSql($sql_query, $prepare)
    {
        $stmt = $this->_pdo->prepare($sql_query);

        for($i=0; $i<count($prepare); $i++){
            $stmt->bindParam($prepare[$i]['key'], $prepare[$i]['value']);
        }

        $this->clean();

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}