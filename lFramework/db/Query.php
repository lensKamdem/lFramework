<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Db;

/**
 * Description of Query
 *
 * @author Lens
 */

use LFramework\Base\BaseClass as BaseClass;
use LFramework\Helpers\ArrayMethods as ArrayMethods;
use LFramework\Exception\InvalidArgumentException as InvalidArgumentException;
use LFramework\Exception\ErrorException as ErrorException;

class Query extends BaseClass {
    
    /**
     * @readwrite
     */
    protected $_connector;
    /**
     * @read
     */
    protected $_from;
    /**
     * @read
     */
    protected $_fields;
    /**
     * @read
     */
    protected $_limit;
    /**
     * @read
     */
    protected $_offset;
    /**
     * @read
     */
    protected $_order;
    /**
     * @read
     */
    protected $_direction;
    /**
     * @read
     */
    protected $_join = array();
    /**
     * @read
     */
    protected $_where = array();
    
    protected function _quote($value) {
        if (is_string($value)) {
            $escaped = $this->getConnector()->escape($value);
            return "'{$escaped}'";
        }
        if(is_array($value)) {
            $buffer = array();
            foreach ($value as $i) {
                array_push($buffer, $this->_quote($i));
            }
            $buffer = join(",", $buffer);
            return "({$buffer})";
        }
        if(is_null($value)) {
            return "NULL";
        }
        if (is_bool($value)) {
            return (int) $value;
        }
        return $this->_connector->escape($value);
    }
    public function _buildSelect() {
        $fields = $tables = array();
        $where = $order = $limit = $join = "";
        $template = "SELECT %s FROM %s %s %s %s %s";
        
        foreach ($this->_fields as $table => $_fields) {
            foreach ($_fields as $field => $alias) {
                if (is_string($field)) {
                    $fields[] = "{$field} AS {$alias}";
                }
                else {
                    $fields[] = $alias;
                }
            }
        }
        if (is_array($this->_from)) {
            foreach ($this->_from as $table => $alias) {
                if (is_string($table)) {
                    $tables[] = "{$table} AS {$alias}";
                }
                else {
                    $tables[] = $alias;
                }
            }
        }
        
        $tables = is_array($this->_from) ? join(",", $tables) : $this->_from;
        
        $fields = join(",", $fields);
        $_join = $this->_join;
        if (!empty($_join)) {
            $join = join(" ", $_join);
        }
        
        $_where = $this->_where;
        if (!empty($_where)) {
            $joined = join(" AND ", $_where);
            $where = "WHERE {$joined}";
        }
        
        $_order = $this->_order;
        if (!empty($_order)) {
            $_direction = $this->_direction;
            $order = "ORDER BY {$order} {$direction}";
        }
        
        $_limit = $this->_limit;
        if (!empty($_limit)) {
            $_offset = $this->_offset;
            if ($_offset) {
                $limit = "LIMIT {$_limit}, {$_offset}";
            }
            else {
                $limit = "LIMIT {$_limit}";
            }
        }
        
        return sprintf($template, $fields, $tables, $join, $where, $order, $limit);
        
    }
    protected function _buildInsert($data) {
        $fields = array();
        $values = array();
        $template = "INSERT INTO %s ('%s') VALUES (%s)";
        
        foreach ($data as $field => $value) {
            $fields[] = $field;
            $values[] = $this->_quote($value);
        }
        $fields = join("','", $fields);
        $values = join("','", $values);
        return sprintf($template, $this->_from, $fields, $values);
    }
    protected function _buildUpdate($data) {
        $parts = array();
        $where = $limit = "";
        $template = "UPDATE %s SET %s %s %s";
        
        foreach ($data as $field => $value) {
            $parts[] = "{$field} = ".$this->_quote($value);
        }
        $parts = join(",", $parts);
        
        $_where = $this->_where;
        if (!empty($_where)) {
            $joined = join(",", $_where);
            $where = "WHERE {$joined}";
        }
        
        $_limit = $this->_limit;
        if (!empty($_limit)) {
            $_offset = $this->_offset;
            $limmit = "LIMIT {$_limit} {$_offset}";
        }
        return sprintf($template, $this->_from, $parts, $where, $limmit);
    }
    protected function _buildDelete() {
        $where = $limit = "";
        $template = "DELETE FROM %s %s %s";
        
        $_where = $this->_where;
        if (!empty($_where)) {
            $joined = join(",", $_where);
            $where = "WHERE {$joined}";
        }
        
        $_limit = $this->_limit;
        if (!empty($_limit)) {
            $_offset = $this->offset;
            $limit = "LIMIT {$_limit} {$_offset}";
        }
        
        return sprintf($template, $this->_from, $where, $limit);
    }
    
    public function from($from, $fields = array("*")) {
        if(empty($from)) {
            throw new InvalidArgumentException("Invalid argument");
        }
        $this->_from = $from;
        if ($fields) {
            if (is_string($this->_from)) {
                $this->_fields[$from] = $fields;
            }
            else {
                $this->_fields[] = $fields;
            }
        }
        return $this;
    }
    public function join($join, $on, $fields = array()) {
        if(empty($join)) {
            throw new InvalidArgumentException("Invalid argument");
        }
        if(empty($on)) {
            throw new InvalidArgumentException("Invalid argument");
        }
        $this->_fields += array($join => $fields);
        $this->_join[] = "JOIN {$join} ON {$on}";
        return $this;
    }
    public function limit($limit, $page=1) {
        if(empty($limit)) {
            throw new InvalidArgumentException("Invalid argument");
        }
        $this->_limit = $limit;
        $this->_offset = $limit * ($page - 1);
        return $this;
    }
    public function order($order, $direction = "asc") {
        if(empty($order)) {
            throw new InvalidArgumentException("Invalid argument");
        }
        $this->_order = $order;
        $this->_direction = $direction;
        return $this;
    }
    public function where() {
        $arguments = func_get_args();
        if(sizeof($arguments) < 1) {
            throw new InvalidArgumentException("Invalid argument");
        }
        $arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);
        foreach (array_slice($arguments, 1, null, true) as $i => $parameter) {
            $arg = preg_match("#{\w+}#", $arguments[$i], $m);
            if ($arg) {
                $m[0] = ltrim($m[0], "{");
                $m[0] = rtrim($m[0], "}");
                $arguments[$i] = $m[0];
            }
            else {
                $arguments[$i] = $this->_quote($arguments[$i]);
            }
        }
        $this->_where[] = call_user_func_array("sprintf", $arguments);
        return $this;
    }
    public function save($data) {
        $isInsert = sizeof($this->_where) == 0;
        if ($isInsert) {
            $sql = $this->_buildInsert($data);
        }
        else {
            $sql = $this->_buildUpdate($data);
        }
        
        $result = $this->_connector->execute($sql);
        
        if ($result === false) {
            throw new ErrorException("Query could not be executed");
        }
        return 0;
    }
    public function delete() {
        $sql = $this->_buildDelete();
        $result = $this->_connector->execute($sql);
        
        if ($result === false) {
            throw new ErrorException();
        }
        return $this->_connector->rowCount();
    }
    public function first() {
        $limit = $this->_limit;
        $offset = $this->_offset;
        
        $this->limit(1);
        
        $all = $this->all();
        $first = ArrayMethods::first($all);
        if ($limit) {
            $this->_limit = $limit;
        }
        if ($offset) {
            $this->_offset = $offset;
        }
        return $first;
    }
    public function count() {
        $limit = $this->_limit;
        $offset = $this->_offset;
        $fields = $this->_fields;
        $this->_fields = array($this->_from => array("COUNT(1)" => "rows"));
        
        $this->limit(1);
        $row = $this->first(all);
        $this->_fields = $fields;
        if ($fields) {
            $this->_fields = $fields;
        }
        if ($limit) {
            $this->_limit = $limit;
        }
        if ($offset) {
            $this->_offset = $offset;
        }
        return $rows["rows"];
    }
    public function union($secondSql) 
    {
        $firstSql = $this->_buildSelect();
        $sql = "{$firstSql} UNION {$secondSql}";
        return $sql;
    }
}