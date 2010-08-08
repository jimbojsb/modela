<?php
class Modela_View
{
    public $map;
    public $reduce;
        
    public function __toString()
    {
        $obj = new stdClass();
        $obj->map = $this->_mapFunction;
        $obj->reduce = $this->_reduceFunction;
        return json_encode($obj);
    }
    
    public function getName()
    {
        return strtolower(get_class($this));
    }
}