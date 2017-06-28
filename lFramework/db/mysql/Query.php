<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\db\Mysql;

use LFramework\Exception\ErrorException as ErrorException;

/**
 * Description of QueryBuilder
 *
 * @author Lens
 */
class Query extends \LFramework\Db\Query
{

    public function all($query = null) {
        $sql = $this->_buildSelect();
        if (isset($query)) {
            $sql = $query;
        }
        $result = $this->_connector->execute($sql);
        
        if ($result === false) {
            $error = $this->_connector->getLastError();
            throw new ErrorException("There was an error your SQL query: {$error}");
        }
        $rows = array();
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        
        return $rows;
    }

}
