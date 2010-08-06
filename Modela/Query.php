<?php
class Modela_Query
{
    protected $_criteria = array();
    protected $_collection;
    
    public function __construct(Array $criteria = null) 
    {
        if ($criteria !== null) {
            $this->_criteria = $criteria;
        }    
    }
    
    public function __set($key, $value)
    {
        $this->_criteria[$key] = $value;
        foreach ($this->_criteria as $key => $val) {
            if ($val === null) {
                unset($this->_criteria[$key]);
            }
        }
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