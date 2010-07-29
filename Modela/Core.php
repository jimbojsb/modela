<?php
class Modela_Core
{

    
    protected static $_instance;
    
    protected $_adapter;
    protected $_options;
    protected $_objects;
    protected $_collections;
    
    /**
     * 
     * @param array $options
     * @return Modela_Core
     */
    public static function getInstance()
    {
        if (self::$_instance instanceof Modela_Core) {
            return self::$_instance;
        } else {
            self::$_instance = new Modela_Core();
            return self::$_instance;
        }
    }
    
    protected function __construct()
    {
    
    }
    
    public function setOptions(Array $options)
    {
        $this->_options = $options;
        $adapterOptions = $options["adapter"];
        if ($adapterOptions["type"] && $adapterOptions["host"] &&  $adapterOptions["db"]) {
            $this->_getAdapter($options["adapter"]);
        }
    }
      
    public function registerCollection($collectionName)
    {
        $this->_collections[] = $collectionName;
        $this->_collections = array_unique($this->_collections);
    }
    
    public function registerObject($objectName)
    {
        $this->_objects[] = $objectName;
        $this->_objects = array_unique($this->_objects);
    }
    
    protected function _getAdapter($options)
    {
        $type = $options["type"];
        if ($type) {
            $adapterClassName = "Modela_Adapter_" . ucfirst((strtolower($type)));  
            try {
                $adapter = new $adapterClassName($options);
                return $adapter;
            } catch (Modela_Exception $e) {
                return null;
            }         
        } 
        return null;
    }
    
    public static function reset()
    {
        self::$_instance = null;
    }
    
    public static function isInitialized()
    {
        return self::$_instance instanceof Modela_Core;
    }
}