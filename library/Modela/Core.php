<?php
class Modela_Core
{

    const DEFAULT_COUCHDB_PORT = 5984;
    const DEFAULT_COUCHDB_HOST = 'localhost';
    
    protected static $_instance;
  
    protected $_database;
    protected $_port = self::DEFAULT_COUCHDB_PORT;
    protected $_hostname = self::DEFAULT_COUCHDB_HOST;
    protected $_views;
    protected $_http;
    
    /**
     * 
     * @param array $options
     * @return Modela_Core
     */
    public static function getInstance()
    {
        if (self::$_instance instanceof Modela_Core) {
            return self::$_instance;
        } else {
            self::$_instance = new Modela_Core();
            return self::$_instance;
        }
    }
    
    private function __construct()
    {
        $http = new Modela_Http();
        $this->setHttp($http);
    }

    public function setHostname($hostname)
    {
        $this->_hostname = $hostname;
    }
    
    public function setDatabase($database)
    {
        $this->_database = $database;
    }
   
    public function registerView($designDoc, $viewName)
    {
        $this->_views[$designDoc][] = $viewName;
    }
    
    public function getDesignDocs()
    {
        return $this->_designDocs;
    }
    
    public function getDesignDoc($name)
    {
        return $this->_designDocs[$name];
    }
    
    public function setHttp(Modela_Http $http)
    {
        $this->_http = $http;
    }
    
    public function getBaseUrl($includeDatabase)
    {
        $url = 'http://' . $this->_hostname . ':' . $this->_port;
        if ($includeDatabase) {
            $url .= '/' . $this->_database;
        }
        return $url;
    }
    
    public function doRequest($method, $uri, $data, $isDatabaseRequest = true)
    {
        $http = $this->_http;
        $http->setMethod($method);

        if ($isDatabaseRequest) {
            $uri = '/' . $this->_database . $uri;
        }
        
        $realUri = 'http://' . $this->_hostname . ':' . $this->_port . $uri; 

        
        if ($method == Modela_Http::METHOD_POST || $method == Modela_Http::METHOD_PUT) {
            $http->setData($data);
        } else if ($method == Modela_Http::METHOD_GET && is_array($data)) {
            $data = self::_sanitizeData($data);
            $queryString = http_build_query($data);
            $realUri .= "?" . $queryString;
        }
        
        $http->setUri($realUri);
        $response = $http->request();
        if ($response) {
            $decodedResponse = json_decode($response, true);
            
            if ($decodedResponse) {
                return $decodedResponse;
            }
        }
        return false;
    }
    
    private function _sanitizeData($data)
    {
        $keys = array_keys($data);
        $vals = array_values($data);
        
        for ($c = 0; $c < count($keys); $c++) {
            $val = $vals[$c];
            $key = $keys[$c];
            switch (gettype($val)) {
                case 'boolean':
                    $realval = $val === true ? "true" : "false";
                    $data[$key] = $realval;           
                    break;
                case 'string':
                    $realval = '"' . $val . '"';
                    $data[$key] = $realval;
                    break;
                case 'array' :
                    $realval = json_encode($val);
                    $data[$key] = $realval;
                    break;
            }
        }        
        return $data;
    }

    public static function reset()
    {
        self::$_instance = null;
    }
}