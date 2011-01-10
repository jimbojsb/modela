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
    
    public function loadView($designDocName, $viewName)
    {
        if (!$this->_modelsPath) {
            return false;
        }
        $path = $this->_modelsPath . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $designDocName . DIRECTORY_SEPARATOR . $viewName . '.php';
        if (file_exists($path)) {
            require_once($path);
            return true;
        }
        return false;
    }
}