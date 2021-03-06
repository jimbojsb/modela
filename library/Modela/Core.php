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
    protected $_username;
    protected $_password;
    
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
    
    public function setUsername($username)
    {
        $this->_username = $username;
    }
    
    public function setPassword($password)
    {
        $this->_password = $password;
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

        $baseUri = $this->getBaseUrl($isDatabaseRequest);
        $realUri = $baseUri . $uri;
        if ($method == Modela_Http::METHOD_POST || $method == Modela_Http::METHOD_PUT) {
            $http->setData($data);
        } else if ($method == Modela_Http::METHOD_GET && is_array($data)) {
            $data = self::sanitizeData($data);
            $queryString = http_build_query($data);
            $realUri .= "?" . $queryString;
        }

        $http->setUri($realUri);
        if ($this->_username && $this->_password) {
            $http->setUsername($this->_username);
            $http->setPassword($this->_password);
        }
        $response = $http->request();
        if ($response) {
            $decodedResponse = json_decode($response);
            if ($decodedResponse) {
                return $decodedResponse;
            }
        }
        return false;
    }
    
    public static function sanitizeData($data)
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