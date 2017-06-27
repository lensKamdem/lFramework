<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Db\Mysql;

use LFramework\Exception\InvalidServiceException as InvalidServiceException;
use LFramework\Exception\ErrorException as ErrorException;

/**
 * Description of Connector
 *
 * @author Lens
 */

class Connector extends \LFramework\Db\Connector
{
    
    protected $_service;
    /**
     * @readwrite
     */
    protected $_type;
    /**
     * @readwrite
     */
    protected $_host;
    /**
     * @readwrite
     */
    protected $_username;
    /**
     * @readwrite
     */
    protected $_password;
    /**
     * @readwrite
     */
    protected $_schema;
    /**
     * @readwrite
     */
    protected $_port = "3306";
    /**
     * @readwrite
     */
    protected $_charset = "utf8";
    /**
     * @readwrite
     */
    protected $_engine = "InnoDB";
    /**
     * @readwrite
     */
    protected $_isConnected = false;
    
    /* Check if connected to the database */
    protected function _isValidService() {
        /* $isEmpty = empty($this->_service); */
        /* $isInstance = $this->_service instanceof PDO; */
        if ($this->_isConnected) {
            return true;
        }
        return false;
    }
    /* Connected to the database */
    public function connect() {
        if (!$this->_isValidService()) {
            try{
                $this->_service = new \PDO(
                    sprintf("%s:host=%s;dbname=%s;port=%s;charset=%s",
                        $this->_type,
                        $this->_host,
                        $this->_schema,
                        $this->_port,
                        $this->_charset
                    ), $this->_username, $this->_password
                );
            }
            catch (\Exception $e) {
                throw new InvalidServiceException("Unable to connect to service");
            }
            $this->_isConnected = true;
        }
        return $this;
    }
    /* Disconnect from the database */
    public function disconnect() {
        if ($this->_isValidService()) {
            $this->_isConnected = false;
            $this->_service = null;
        }
        return $this;
    }
    /* Returns a corresponding query instance */
    public function query() {
        return new \LFramework\Db\Mysql\Query(array(
            "connector" => $this
        ));
    }
    /* Excecutes the provided SQL statement */
    public function execute($sql) {
        if (!$this->_isValidService()) {
            throw new InvalidServiceException("Not connected to a valid service");
        }
        return $this->_service->query($sql);
    }
    /* Escapes the provided value to make it safe for queries */
    public function escape($value) {
        if (!$this->_isValidService()) {
            throw new InvalidServiceException("Not connected to a valid service");
        }
        $value = trim($value);
        $value = htmlspecialchars($value);
        $value = htmlentities($value);
        if (!get_magic_quotes_gpc()) {
            $value = addslashes($value);
        }
        
        return $value; 
    }
    /* Returns the number rows affected by the last SQL query 
     * executed 
     */
    public function getAffectedRows() {
        if (!$this->_isValidService()) {
            throw new InvalidServiceException("Not connected to a valid service");
        }
        return $this->_service->rowCount();
    }
    /* Returns the last error of occur */
    public function getLastError() {
        if (!$this->_isValidService()) {
            throw new InvalidServiceException("Not connected to a valid service");
        }
        return join(", ", $this->_service->errorInfo()) . "\n. ";
    }
    
    /* For models */
    public function sync($model) {
        $lines = array();
        $indices = array();
        $columns = $model->_columns;
        $template = "CREATE TABLE %s (\n%s,\n%s\n) ENGINE=%s DEFAULT CHARSET=%s;";
        foreach ($columns as $column) {
            $raw = $column["raw"];
            $name = $column["name"];
            $type = $column["type"];
            $length = $column["length"];
            if ($column["primary"]) {
                $indices[] = "PRIMARY KEY ({$name})";
            }
            if ($column["index"]) {
                $indices[] = "KEY {$name} ({$name})";
            }
            switch ($type) {
                case "autonumber": {
                    $lines[] = "{$name} int(11) NOT NULL AUTO_INCREMENT";
                    break;
                }
                case "text": {
                    if ($length !== null && $length <= 255) {
                        $lines[] = "{$name} varchar({$length}) DEFAULT NULL";
                    }
                    else {
                        $lines[] = "{$name} text";
                    }
                    break;
                }
                case "integer": {
                    $lines[] = "{$name} int(11) DEFAULT NULL";
                    break;
                }   
                case "decimal": {
                    $lines[] = "{$name} float DEFAULT NULL";
                    break;
                }
                case "boolean": {
                    $lines[] = "{$name} tinyint(4) DEFAULT NULL";
                    break;
                }
                case "datetime": {
                    $lines[] = "{$name} datetime DEFAULT NULL";
                    break;
                }
            }
            if ($column["foreign"]) {
                $foreign = $column["foreign"];
                $line = array_pop($lines) ." REFERENCES {$foreign}";
                $lines[] = $line;
            }
        }
        $table = $model->_table;
        $sql = sprintf(
            $template,
            $table,
            join(",\n", $lines),
            join(",\n", $indices),
            $this->_engine,
            $this->_charset
        );
        $result = $this->execute("DROP TABLE IF EXISTS {$table};");
        if ($result === false) {
            $error = $this->getLastError();
            throw new ErrorException("There was an error in the query: {$error}");
        }
        $result = $this->execute($sql);
        if ($result === false) {
            $error = $this->getLastError();
            throw new ErrorException("There was an error in the query: {$sql} \n {$error}");
        }
        return $this;
    }
}
