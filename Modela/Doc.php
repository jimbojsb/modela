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
        $getterOverrideName = "get" . ucfirst(str_replace("_", "", $key));
        if (method_exists($this, $getterOverrideName)) {
            return $this->$getterOverrideName();
        }
        return $this->_storage[$key];
    }
    
    public function __set($key, $value) 
    {
        $setterOverrideName = "set" . ucfirst(str_replace("_", "", $key));
        if (method_exists($this, $setterOverrideName)) {
            return $this->$setterOverrideName($value);
        }
        $this->_storage[$key] = $value;
    }
    
    public function save()
    {
        $method = Modela_Http::METHOD_PUT;
        $uri = '/';
        if ($this->_id === null) {
            $this->_id = self::generateId();
        }
        $uri .= $this->_id;
        $core = Modela_Core::getInstance();
        $response = $core->doRequest($method, $uri, $this->__toString(), true); 
        if ($response["ok"] === true) {
            $this->_rev = $response["rev"];
            return true;
        }
        return false;
    }
    
    public function delete()
    {
        if ($this->_id && $this->_rev) {
            $uri = '/' . $this->_id . "?rev=" . $this->_rev;
            $core = Modela_Core::getInstance();
            $response = $core->doRequest(Modela_Http::METHOD_DELETE, $uri, null, true);
            if ($response["ok"] === true) {
                $this->_storage = array();
                return true;
            }
            return false;
        }
    }
    
    public function refresh()
    {
        
    }
    
    public static function generateId()
    {
        $rand1 = mt_rand();
        $rand2 = mt_rand();
        $rand3 = mt_rand();
        $rand4 = mt_rand();
        $bin = pack("N4", $rand1, $rand2, $rand3, $rand4);
        $hex = bin2hex($bin);
        return $hex;
    }
    
    public function __toString()
    {
        $output = json_encode($this->_storage);
        return $output;
    }
    
    public static function get($documentId = null)
    {
        $uri = '/' . $documentId;
        $core = Modela_Core::getInstance();
        $response = $core->doRequest(Modela_Http::METHOD_GET, $uri, null, true);
        return self::processResponseArray($response);
    }
    
    public static function find($designDocName = null, $viewName = null, $params = null)
    {        
        $uri = '/';
        $core = Modela_Core::getInstance();
        if ($params === null) {
            $uri .= '_all_docs';
        } else {
            $uri .= '_design/' . $designDocName . '/_view/' . $viewName;
        }
        $response = $core->doRequest(Modela_Http::METHOD_GET, $uri, $params, true);
        $rows = array();
        foreach ($response["rows"] as $row) {
            if ($row["id"]) {
                $doc = self::get($row["id"]);
                $rows[] = $doc;
            } else if ($row["key"]) {
                $doc = new Modela_Response();
                $doc->key = $row["key"];
                $doc->value = $row["value"];
                $rows[] = $doc;
            }
        }
        return $rows;
    }
    
    public static function processResponseArray($response)
    {
        $type = $response["type"];
        $className = ucfirst($type);
        if (!$className) {
            $className = "Modela_Doc";
        }
        $obj = new $className($response);
        return $obj;
    }
}