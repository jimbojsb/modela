<?php
class Modela_Collection
{
    protected $_defaultObject;
    protected $_storage;
    
    public function __construct()
    {
    }   
    
    public function findByField($field, $value)
    {
        $query = $this->getQuery();
        $query->$field = $value;
        return $this->_runQuery($query);      
    }
    
    public function find()
    {
        $query = $this->getQuery();
        return $this->_runQuery($query);
    }
    
    protected function _runQuery($query)
    {
        $core = Modela_Core::getInstance();
        $adapter = $core->getAdapter();
        $results = $adapter->find($query);
        if ($results) {
            return $this->_processResults($results);
        }
        return null;
    }
    
    protected function _processResults($resultArray)
    {
         $this->_storage = array();
         $objClass = $this->getDefaultObject();
         foreach ($resultArray as $result) {
            $obj = new $objClass();
            foreach ($result as $key => $val) {
                $obj->$key = $val;
            }
            $this->_storage[] = $obj;
        }
        return $this->_storage;
    }
    
    public function getName()
    {
        return strtolower(get_class($this));
    }
    
    public function getDefaultObject()
    {
        return $this->_defaultObject;
    }
    
    public function getQuery()
    {
        $q = new Modela_Query();
        $q->setCollection($this);
        return $q;
    }
}