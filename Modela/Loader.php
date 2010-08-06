<?php
class Modela_Loader
{
    const MODELA_FOLDER_COLLECTIONS = 'collections';
    const MODELA_FOLDER_OBJECTS = 'objects';
    
    protected static $_instance;
    protected $_modelsPath;
    
    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    protected function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
    
    public function loadClass($className)
    {
        $classParts = explode('_', $className);
        if ($classParts[0] != 'Modela') {
            return;
        }
        $realClassPath = implode('/', $classParts) . ".php";
        require_once($realClassPath);
    }
    
    public function loadModels($modelsPath)
    {       
        $this->_modelsPath = $modelsPath;

        
        
        $collectionsFolder = $this->_modelsPath . "/" . self::MODELA_FOLDER_COLLECTIONS;
        $objectsFolder = $this->_modelsPath . "/" . self::MODELA_FOLDER_OBJECTS;
        
        $core = Modela_Core::getInstance();
        
        $di = new DirectoryIterator($collectionsFolder);
        foreach ($di as $file) {
            if (!$file->isDot()) {
                $collectionName = str_replace('.php', '', $file);
                require_once($file->getPathname());
            }
        }
        
        $di = new DirectoryIterator($objectsFolder);
        foreach ($di as $file) {
            if (!$file->isDot()) {
                $objectName = str_replace('.php', '', $file);
                require_once($file->getPathname());
            }
        }
    }
}