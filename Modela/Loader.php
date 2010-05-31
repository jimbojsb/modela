<?php
class Modela_Loader
{
    protected static $_instance;
    
    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
    
    public function loadClass($className)
    {
        $classParts = explode('_', $className);
        $realClassPath = implode('/', $classParts) . ".php";
        if (file_exists($realClassPath)) {
            require_once($realClassPath);
        }
    }
}