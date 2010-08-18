<?php
class Modela_Core
{

    const DEFAULT_COUCHDB_PORT = 5984;
    
    protected static $_instance;
  
    protected $_database;
    protected $_port = self::DEFAULT_COUCHDB_PORT;
    protected $_hostname;
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
   
    public function registerView($viewName, $designDoc)
    {
        $this->_views[$designDoc][] = $viewName;
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
        foreach ($this->_views as $designDoc => $views) {
            $doc = new Modela_Doc_Design();
            $doc->_id = $designDoc;
            $docExists = Modela_Doc::get($doc->_id);
            if ($docExists->_rev) {
                $doc->_rev = $docExists->_rev;
            }
            foreach ($views as $view) {
                $view = new $view();
                $doc->addView($view);
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