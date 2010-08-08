<?php
class Modela_Loader
{
    const MODELA_FOLDER_VIEWS = 'views';
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

        
        
        $viewsFolder = $this->_modelsPath . "/" . self::MODELA_FOLDER_VIEWS;
        $objectsFolder = $this->_modelsPath . "/" . self::MODELA_FOLDER_OBJECTS;
        
        $core = Modela_Core::getInstance();
        
        $di = new DirectoryIterator($viewsFolder);
        foreach ($di as $file) {
            if (!$file->isDot() && $file->isDir()) {
                $di2 = new DirectoryIterator($file->getPathname());
                foreach ($di2 as $file2) {
                    if (!$file2->isDot()) {
                        $designDocFull = strtolower($file2->getPath());
                        $designDocParts = explode('/', $designDocFull);
                        $designDoc = $designDocParts[count($designDocParts) - 1];
                        $viewName = str_replace('.php', '', $file2->getFilename());
                        $core->registerView($viewName, $designDoc);
                        require_once($file2->getPathname());
                    }
                }

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