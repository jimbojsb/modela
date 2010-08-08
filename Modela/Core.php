<?php
class Modela_Core
{

    const DEFAULT_COUCHDB_PORT = 5984;
    
    protected static $_instance;
  
    protected $_database;
    protected $_port = self::DEFAULT_COUCHDB_PORT;
    protected $_hostname;
    protected $_views;
    
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
   
    public function registerView($viewName, $designDoc)
    {
        $this->_views[$designDoc][] = $viewName;
    }
    
    public function doRequest($method, $uri, $data, $isDatabaseRequest = true)
    {
        $http = new Modela_Http();
        $http->setMethod($method);

        if ($isDatabaseRequest) {
            $uri = '/' . $this->_database . $uri;
        }
        
        $realUri = 'http://' . $this->_hostname . ':' . $this->_port . $uri; 

        
        if ($method == Modela_Http::METHOD_POST || $method == Modela_Http::METHOD_PUT) {
            $http->setData($data);
        } else if ($method == Modela_Http::METHOD_GET && is_array($data)) {
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
            if ($docExists instanceof Modela_Doc) {
                $doc->_rev = $docExists->_rev;
            }
            foreach ($views as $view) {
                $view = new $view();
                $doc->addView($view);
            }
            $doc->save();
        }
    }

    public static function reset()
    {
        self::$_instance = null;
    }
}