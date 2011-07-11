<?php
class Modela_Doc_Design extends Modela_Doc
{
    const PREFIX_DESIGN_DOC = "_design/";
    
    /** 
     * magic setter override that handles automatic prefixing of
     * _design/ onto design documents per CouchDB view api
     * @param string $value
     * @see Modela_Doc::_set()
     */
    public function setId($value)
    {
        if (strpos($value, self::PREFIX_DESIGN_DOC) !== 0) {
            $value = self::PREFIX_DESIGN_DOC . $value;
        }                
        $this->_storage['_id'] = $value;
    }
    
    /**
     * add a view to this design document
     * @param string $name
     * @param Modela_View $view
     */
    public function addView($name, Modela_View $view)
    {
        $this->_storage['views'][$name] = $view;
    }
    
    /**
     * convert this to JSON for saving
     * @see Modela_Doc::__toString()
     */
    public function __toString()
    {
        // remove the type that is autmatically generated
        // on construct because it isn't part of the standard
        // CouchDB view spec. Do this here because it's not easy
        // to unset properties with magic getters and setters in play
        $vars = $this->_storage;
        unset($vars['type']);
        
        $output = json_encode($vars);
        return $output;
    }

}