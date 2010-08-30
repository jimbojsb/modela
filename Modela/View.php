<?php
class Modela_View
{
    protected $_map;
    protected $_reduce;
    protected $_defaultParams;
 
    public function getDefaultParams()
    {
        return $this->_defaultParams;
    }
    
    public function getSerializable()
    {
        $obj = new stdClass();
        $obj->map = $this->_map;
        $obj->reduce = $this->_reduce;
        return $obj;
    }
    
    public static function getView($designDocName, $viewName)
    {
        $className = ucfirst($designDocName) . "_" . $viewName;
        return new $className();
    }
    
}