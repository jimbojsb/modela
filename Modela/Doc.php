<?php
class Modela_Doc
{
    protected $_storage = array();

    public function __construct(Array $data = null)
    {
        if ($data !== null) {
            foreach ($data as $key => $val) {
                $this->$key = $val;
            }
        }
    }
    
    public function __get($key)
    {
        return $this->_storage[$key];
    }
    
    public function __set($key, $value) 
    {
        $this->_storage[$key] = $value;
    }
    
    public function save()
    {
        $method = Modela_Http::METHOD_POST;
        $data = $this->_storage;
        $uri = '/';
        if ($this->_id !== null) {
            $method = Modela_Http::METHOD_PUT;
            $uri .= $data["_id"];
        }
        $core = Modela_Core::getInstance();
        $response = $core->doRequest($method, $uri, $data, true); 
        if ($response["ok"] === true) {
            $this->_rev = $response["rev"];
            return true;
        }
        return false;
    }
    
    public function delete()
    {
    }
    
    public static function get($documentId)
    {
        $uri = '/' . $documentId;
        $core = Modela_Core::getInstance();
        $response = $core->doRequest(Modela_Http::METHOD_GET, $uri, null, true);
        return self::processResponseArray($response);
    }
    
    public static function find($params = null)
    {
        $uri = '/';
        $core = Modela_Core::getInstance();
        if ($params === null) {
            $uri .= '_all_docs';
        }
        $response = $core->doRequest(Modela_Http::METHOD_GET, $uri, null, true);
        foreach ($response["rows"] as $row) {
            $doc = self::get($row["id"]);
        }
    }
    
    public static function processResponseArray($response)
    {
        $type = $response["type"];
        $className = ucfirst($type);
        $obj = new $className($response);
        return $obj;
    }
}