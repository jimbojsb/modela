<?php
class Modela_Doc_Attachment
{
    protected $_data;
    
    public function __construct($data)
    {
        $this->_data = $data;
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