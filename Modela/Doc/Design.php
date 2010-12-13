<?php
class Modela_Doc_Design extends Modela_Doc
{
    const PREFIX_DESIGN_DOC = "_design/";
    
    public function setId($value)
    {
        if (strpos($value, self::PREFIX_DESIGN_DOC) !== 0) {
            $value = self::PREFIX_DESIGN_DOC . $value;
        }                
        $this->_storage['_id'] = $value;
    }
    
    public function addView($name, $view)
    {
        $this->_storage['views'][$name] = $view;
    }
    
    public function getView($viewName)
    {
        return $this->_storage['views'][$viewName];
    }
    
    public function __toString()
    {
        $vars = $this->_storage;
        unset($vars['type']);
        $output = json_encode($vars);
        return $output;
    }

}