<?php
class Modela_Doc
{
    public $type;
    protected $_storage = array();
    protected $_collection;

    
    public function __get($key)
    {
        return $this->_storage[$key];
    }
    
    public function __set($key, $value) 
    {
        $this->_storage[$key] = $value;
    }
    
    protected function _preSave()
    {
    }
    
    protected function _postSave()
    {
    }
    
    public function save()
    {
        $this->_preSave();
        
        $core = Modela_Core::getInstance();
        $db = $core->getAdapter()->save($this);

        $this->_postSave();
    }
    
    public function delete()
    {
        
    }
    
    public function setCollection($collectionName)
    {
        $this->_collection = $collectionName;
    }
    
    public function getCollection()
    {
        return $this->_collection;
    }
    
    public function asArray()
    {
        return $this->_storage;
    }
}