<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Base;

/**
 * Description of Request
 *
 * @author Lens
 */

use LFramework\Base\Component as Component;
use LFramework\Base\Response as Response;
use LFramework\Helpers\StringMethods as StringMethods;
use LFramework\Helpers\RequestMethods as RequestMethods;
use LFramework\Exception\ErrorException as ErrorException;

class Request extends Component {
    
    const EVENT_BEFORE_REQUEST = "lframework.request.request.before";
    const EVENT_AFTER_REQUEST = "lframework.request.request.after";
    
    protected $_request;
    /**
    * @readwrite
    */
    public $_willFollow = true;
    /**
    * @readwrite
    */
    protected $_headers = array();
    /**
    * @readwrite
    */
    protected $_options = array();
    /**
    * @readwrite
    */
    protected $_referer;
    /**
    * @readwrite
    */
    protected $_agent;
    /**
     * @var type 
     * 
     * @readwrite
     */
    protected $_baseUrl;
    /** 
     * @var type 
     * 
     * @readwrite
     */
    protected $_scriptFile;
     /** 
     * @var type 
     * 
     * @readwrite
     */
    protected $_scriptUrl;
    
    protected function _setOption($key, $value) {
        curl_setopt($this->_request, $key, $value);
        return $this;
    }
    protected function _normalize($key) {
        return "CURLOPT_".str_replace("CURLOPT_", "", strtoupper($key));
    }
    protected function _setRequestMethod($method) {
        switch (strtoupper($method)) {
            case "HEAD":
                $this->_setOption(CURLOPT_NOBODY, true);
                break;
            case "GET":
                $this->_setOption(CURLOPT_HTTPGET, true);
                break;
            case "POST":
                $this->_setOption(CURLOPT_POST, true);
                break;
            default:
                $this->_setOption(CURLOPT_CUSTOMREQUEST, $method);
                break;
        }
        return $this;
    }
    protected function _setRequestOptions($url, $parameters) {
    $this->_setOption(CURLOPT_URL, $url)
        ->_setOption(CURLOPT_HEADER, true)
        ->_setOption(CURLOPT_RETURNTRANSFER, true)
        ->_setOption(CURLOPT_USERAGENT, $this->getAgent());
        if (!empty($parameters)) {
            $this->_setOption(CURLOPT_POSTFIELDS, $parameters);
        }
        if ($this->getWillFollow()) {
            $this->_setOption(CURLOPT_FOLLOWLOCATION, true);
        }
        if ($this->getReferer()) {
            $this->_setOption(CURLOPT_REFERER, $this->getReferer());
        }
        foreach ($this->_options as $key => $value) {
            $this->_setOption(constant($this->_normalize($key)), $value);
        }
        return $this;
    }
    protected function _setRequestHeaders() {
        $headers = array();
        foreach ($this->getHeaders() as $key =>$value) {
            $headers[] = $key.': '.$value;
        }
        $this->_setOption(CURLOPT_HTTPHEADER, $headers);
        return $this;
    }
    
    public function __construct($options = array()) {
        parent::__construct($options);
        $this->setAgent(RequestMethods::server("HTTP_USER_AGENT", "Curl/PHP ".PHP_VERSION));
    }
    public function delete($url, $parameters = array()) {
        return $this->request("DELETE", $url, $parameters);
    }
    public function get($url, $parameters = array()) {
        if (!empty($parameters)) {
            $url .= StringMethods::indexOf($url, "?") ? "&" : "?";
            $url .= is_string($parameters) ? $parameters : http_build_query($parameters, "", "&");
        }
        return $this->request("GET", $url);
    }
    public function head($url, $parameters = array()) {
        return $this->request("HEAD", $url, $parameters);
    }
    public function post($url, $parameters = array()) {
        return $this->request("POST", $url, $parameters);
    }
    public function put($url, $parameters = array()) {
        return $this->request("PUT", $url, $parameters);
    }
    public function request($method, $url, $parameters = array()) {
     $this->trigger(self::EVENT_BEFORE_REQUEST, array($method, $url, $parameters));
        
        $request = $this->_request = curl_init();
        if (is_array($parameters)) {
            $parameters = http_build_query($parameters, "", "&");
        }
        $this->_setRequestMethod($method)
            ->_setRequestOptions($url, $parameters)
            ->_setRequestHeaders();
        $response = curl_exec($request);
        
        if ($response) {
            // Event beforeSend
            $response = new Request\Response(array(
                "response" => $response
            ));
            // Event afterSend
        }
        else {
            throw new ErrorException(curl_errno($request).' - '.curl_error($request));
        }
        curl_close($request);
        
        $this->trigger(self::EVENT_AFTER_REQUEST, array($method, $url, $paramters, $response));
        return $response;
    }
    public function getScriptFile()
    {
        if ($this->_scriptFile === null) {
            if (isset($_SERVER['SCRIPT_FILENAME'])) {
                $this->setScriptFile($_SERVER['SCRIPT_FILENAME']);
            } else {
                throw new InvalidConfigException('Unable to determine the entry script file path.');
            }
        }

        return $this->_scriptFile;
    }
    public function setScriptFile($value)
    {
        $scriptFile = realpath($value);
        if ($scriptFile !== false && is_file($scriptFile)) {
            $this->_scriptFile = $scriptFile;
        } else {
            throw new InvalidConfigException('Unable to determine the entry script file path.');
        }
    }
    public function getScriptUrl()
    {
        if ($this->_scriptUrl === null) {
            $scriptFile = $this->getScriptFile();
            $scriptName = basename($scriptFile);
            if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            } elseif (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            } elseif (isset($_SERVER['PHP_SELF']) && ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            } elseif (!empty($_SERVER['DOCUMENT_ROOT']) && strpos($scriptFile, $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->_scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $scriptFile));
            } else {
                throw new InvalidConfigException('Unable to determine the entry script URL.');
            }
        }

        return $this->_scriptUrl;
    }
    public function setScriptUrl($value)
    {
        $this->_scriptUrl = $value === null ? null : '/' . trim($value, '/');
    }
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }

        return $this->_baseUrl;
    }
    
}
