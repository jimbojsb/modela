<?php
class Modela_Doc_Attachment
{
    protected $_storage;
    
    public function __construct($data = array())
    {
        $this->_storage = $data;
    }
    
    public function setRawData($data)
    {
        $this->_storage['data'] = base64_encode($data);
        $this->_storage['length'] = strlen($this->_storage['data']);
    }
    
    public function setContentType($type)
    {
        $this->_storage['content_type'] = $type;
    }
    
    public function setFilename($filename)
    {
        $this->_storage['filename'] = $filename;
    }
    
    public function 
    
    public function __toString()
    {
        $data = $this->_storage;
        $filename = $data['filename'];
        unset($data['filename']);
        $ret[$filename] = (object)$data;
        return json_encode($data);
    }
    
    public function getUrl()
    {
        $doc = $this->_data["parent_document"];
        $core = Modela_Core::getInstance();
        $url = $core->getBaseUrl(true);
        $url .= '/' . $doc->_id . '/' . $this->_data['filename'];
        return $url;
    }
}