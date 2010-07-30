<?php
class Modela_Query
{
    protected $_criteria = array();
    protected $_collection;
    
    public function __set($key, $value)
    {
        $this->_criteria[$key] = $value;
    }
    
    public function __get($key)
    {
        return $this->_criteria[$key];
    }

    public function getCollection()
    {
        return $this->_collection;
    }
    
    public function setCollection($collection)
    {
        return $this->_collection = $collection;
    }
    
    public function asArray()
    {
        return $this->_criteria;
    }

}