<?php
class Modela_Core
{

    const DEFAULT_COUCHDB_PORT = 5984;
    
    protected static $_instance;
  
    protected $_database;
    protected $_port = self::DEFAULT_COUCHDB_PORT;
    protected $_hostname;
    
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
    }

    public function setHostname($hostname)
    {
        $this->_hostname = $hostname;
    }
    
    public function setDatabase($database)
    {
        $this->_database = $database;
    }
    
    public function doRequest($method, $uri, $data = array())
    {
        $http = new Modela_Http();
        $http->setMethod($method);
        
        $realUri = 'http://' . $this->_hostname . ':' . $this->_port . $uri; 
        $http->setUri($realUri);
        
        if ($method == Modela_Http::METHOD_POST || $method == Modela_Http::METHOD_PUT) {
            $http->setPostData($data);
        }
        $response = $http->request();
        return $response;
        if ($response) {
            $decodedResponse = json_decode($response);
            
            if ($decodedResponse) {
                return $decodedResponse;
            }
        }
        return false;
    }

    public static function reset()
    {
        self::$_instance = null;
    }
}