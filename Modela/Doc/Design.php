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
    
    public function addView($name, Modela_View $view)
    {
        $this->_storage['views'][$name] = $view;
    }
    
    public function __toString()
    {
        $output = json_encode($this->_storage);
        return $output;
    }
}