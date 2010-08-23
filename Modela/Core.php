<?php
class Modela_Core
{

    const DEFAULT_COUCHDB_PORT = 5984;
    
    protected static $_instance;
  
    protected $_database;
    protected $_port = self::DEFAULT_COUCHDB_PORT;
    protected $_hostname;
    protected $_designDocs;
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
   
    public function registerDesignDoc($designDoc)
    {
        $this->_designDocs[] = $designDoc;
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
            $keys = array_keys($data);
            $values = array_values($data);
            for ($c = 0; $c < count($keys); $c++) {
                if (is_string($values[$c])) {
                    $data[$keys[$c]] = '"' . $values[$c] . '"';
                } else if (is_bool($values[$c])) {
                    $data[$keys[$c]] = $values[$c] === true ? 'true' : 'false';
                }
            }
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
    
    public function createViews()
    {
        foreach ($this->_designDocs as $designDoc) {
            $className = "DD_" . $designDoc;
            $doc = new $className();
            $doc->_id = strtolower($designDoc);
            $docExists = Modela_Doc::get($doc->_id);
            if ($docExists->_rev) {
                $doc->_rev = $docExists->_rev;
            }
            $doc->save();
            unset($docExists);
            unset($view);
        }
    }

    public static function reset()
    {
        self::$_instance = null;
    }
}