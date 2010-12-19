<?php
class Modela_Doc
{
    protected $_storage = array();

    public function __construct(Array $data = null)
    {
        if ($data !== null) {
            foreach ($data as $key => $val) {
                $this->set($key, $val);
            }
        }
        $this->type = strtolower(get_class($this));
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
        $this->set($key, $value);
    }
    
    public function set($key, $value)
    {
        if ($value === null) {
            unset($this->_storage[$key]);
        } else {
            $this->_storage[$key] = $value;
        }
    }
    
    public function save($refreshIfNeeded = false)
    {
        $method = Modela_Http::METHOD_PUT;
        $uri = '/';
        if ($this->_id === null) {
            $this->_id = $this->generateId();
        }
        $uri .= $this->_id;
        $core = Modela_Core::getInstance();
        $response = $core->doRequest($method, $uri, $this->__toString(), true); 
        if ($response["ok"] === true) {
            $this->_rev = $response["rev"];
            return true;
        } else if ($response["error"] == 'conflict' && $refreshIfNeeded) {
            $doc = self::get($this->_id);
            $this->_rev = $doc->_rev;
            $this->save();
        }
        return false;
    }
    
    public static function saveMany(Array $docs)
    {
        $method = Modela_Http::METHOD_POST;
        $uri = '/_bulk_docs';
        $core = Modela_Core::getInstance();
        $docStorage = array();
        foreach ($docs as $doc) {
            if ($doc->_id === null) {
                $doc->_id = $doc->generateId();
            }
            $docStorage[] = $doc->asArray();
        }
        $data = json_encode(array("docs" => $docStorage));
        $response = $core->doRequest($method, $uri, $data, true); 
        if (count($response) == count($docs)) {
            return true;
        }
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
    
    public function deleteMany(Array $docs)
    {
        foreach ($docs as $doc) {
            $doc->_deleted = true;
        }
        self::saveMany($docs);
    }
    
    public function refresh()
    {
        
    }
    
    public function hasAttachments()
    {
        return is_array($this->_attachments);
    }
    
    public function getAttachements()
    {
        return $this->_attachments;
    }
    
    public function removeAttachments()
    {
        $this->set('_attachments', null);
    }
    
    public function addAttachment($filename, $rawData, $contentType = null)
    {
        $attachments = is_array($this->_attachments) ? $this->_attachments : array();
        $newAttachment = array();
        $newAttachment['data'] = base64_encode($rawData);
        $newAttachment['content_type'] = $contentType;
        $attachments[$filename] = $newAttachment;
        $this->set("_attachments", $attachments);
    }
    
    public function generateId()
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
        $data = $this->_storage;
        $output = json_encode($data);
        return $output;
    }
    
    public function asArray()
    {
        return $this->_storage;
    }
    
    public static function get($documentId = null)
    {
        $uri = '/' . $documentId;
        $core = Modela_Core::getInstance();
        $response = $core->doRequest(Modela_Http::METHOD_GET, $uri, null, true);
        if ($response) {
            return self::createDocFromResponse($response);            
        }
        return null;
    }
    
    public static function find($designDocName = null, $viewName = null, $params = null, $docsOnly = false)
    {        
        $uri = '/';
        $core = Modela_Core::getInstance();
        
        if ($designDocName !== null && $viewName !== null) {
            $view = Modela_View::getView($designDocName, $viewName);
            $defaultViewParams = $view->getDefaultParams();
            if ($docsOnly) {
                $params["include_docs"] = true;
            }
            $mergedParams = self::_mergeParams($params, $defaultViewParams);
            $params = $mergedParams ? $mergedParams : null;
        }
        
        if ($params === null && $designDocName === null && $viewName === null) {
            $uri .= '_all_docs';
        } else {
            $uri .= '_design/' . $designDocName . '/_view/' . $viewName;
        }
        $response = $core->doRequest(Modela_Http::METHOD_GET, $uri, $params, true);
        $rows = array();
        foreach ($response["rows"] as $row) {
            if ($docsOnly) {
                $rows[] = self::createDocFromResponse($row["doc"]);
            } else if ($row["key"]) {
                $doc = new Modela_Response();
                if (method_exists($view, "callback")) {
                    $row = $view->callback($row);
                } 
                foreach ($row as $key => $value) {
                    $doc->$key = $value;
                }
                $rows[] = $doc;
            }
        }
        return $rows;
    }
    
    public static function findDocs($designDocName = null, $viewName = null, $params = null)
    {
        return self::find($designDocName, $viewName, $params, true);
    }
    
    public static function createDocFromResponse($response)
    {
        $type = $response["type"];
        $className = ucfirst($type);
        if (!$className) {
            $className = "Modela_Doc";
        }
        $obj = new $className($response);
        return $obj;
    }
    
    public function getAttachmentUrl($attachmentFilename)
    {
        $core = Modela_Core::getInstance();
        $url = $core->getBaseUrl(true);
        $url .= '/' . $this->_id . '/' . $attachmentFilename;
        return $url;
    }
    
    private static function _mergeParams($passedParams, $defaultViewParams)
    {
        $newParams = $defaultViewParams;
        if (!$passedParams) {
            return $newParams;
        }
        foreach ($passedParams as $key => $val) {
            $newParams[$key] = $val;
        }
        return $newParams;
    }
}