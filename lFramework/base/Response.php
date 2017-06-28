<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

/**
 * Description of Responnse
 *
 * @author Lens
 */

use LFramework\Base\Component as Component;

class Responnse  extends Component {
    
    protected $_response;
    /**
    * @read
    */
    protected $_body = null;
    /**
    * @read
    */
    protected $_headers = array();
    
    function __construct($options = array()) {
        if (!empty($options["response"])) {
            $response = $this->_response = $options["response"];
            unset($options["response"]);
        }
        parent::__construct($options);
        $pattern = '#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims';
        preg_match_all($pattern, $response, $matches);
        $headers = array_pop($matches[0]);
        $headers = explode("\r\n", str_replace("\r\n\r\n", "", $headers));
        $this->_body = str_replace($headers, "", $response);
        $version = array_shift($headers);
        preg_match('#HTTP/(\d\.\d)\s(\d\d\d)\s(.*)#', $version, $matches);
        $this->_headers["Http-Version"] = $matches[1];
        $this->_headers["Status-Code"] = $matches[2];
        $this->_headers["Status"] = $matches[2]." ".$matches[3];
        foreach ($headers as $header) {
            preg_match('#(.*?)\:\s(.*)#', $header, $matches);
            $this->_headers[$matches[1]] = $matches[2];
        }
    }
    function __toString() {
     
        return $this->getBody();
    }
    
}
