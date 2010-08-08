<?php
class Modela_View
{
    protected $_mapFunction;
    protected $_reduceFunction;
    
    public function __toString()
    {
        $name = strtolower(get_class($this));
        $view = array();
        $view[$name]["map"] = $this->_mapFunction;
        $view[$name]["reduce"] = $this->_reduceFunction;
        return json_encode($view);
    }
}