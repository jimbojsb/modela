<?php
class Modela_View
{
    public $map;
    public $reduce;
    protected $_defaultParams;
    
    public function setDefaultParams(Array $defaults)
    {
        $this->_defaultParams = $defaults;
    }
    
    public function getDefaultParams()
    {
        return $this->_defaultParams;
    }
}