<?php
class Modela_Core
{
    protected static $_options;
    protected static $_dbAdapter;
    protected static $_cacheAdapter;
    protected static $_isInitialized;
    
    public static function init($options)
    {
        self::$_options = $options;    
        
        
        self::$_isInitialized = true;
    }
    
    public static function reset()
    {
        self::$_options = null;
        self::$_dbAdapter = null;
        self::$_cacheAdapter = null;
        self::$_isInitialized = false;
    }
    
    protected static function initDbAdapter($adapterOptions)
    {
        
    }
    
    protected static function initCacheAdapter($adapterOptions)
    {
        
    }

    protected static function loadClass($class)
    {
        
    }
}