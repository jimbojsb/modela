<?php
class Modela_Loader
{
    
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
                
        $di = new DirectoryIterator($modelsPath);
        foreach ($di as $file) {
            if (!$file->isDot() && !$file->isDir()) {
                $objectName = str_replace('.php', '', $file);
                require_once($file->getPathname());
            }
        }
    }
}