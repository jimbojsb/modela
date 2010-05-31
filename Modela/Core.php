<?php
class Modela_Core
{
    protected static $_config;
    protected static $_dbAdapter;
    protected static $_cacheAdapter;
    protected static $_isInitialized;
    
    public static function init(Modela_Config $config)
    {
        self::$_config = $config;    
        
        if (self::$_config->dbConnectionString) {
            self::initDbAdapter();
        }
        
        
        self::$_isInitialized = true; 
    }
    
    public static function reset()
    {
        self::$_options = null;
        self::$_dbAdapter = null;
        self::$_cacheAdapter = null;
        self::$_isInitialized = false;
    }
    
    protected static function initDbAdapter()
    {
        $config = self::$_config;
        $dbParams = $config->getDbConnectionParams();
        $dbAdapterClass = "Modela_Adapter_Db_" . $dbParams->adapterType;
        $adapter = new $dbAdapterClass($dbParams);
    }
    
    protected static function initCacheAdapter($adapterOptions)
    {
        
    }
}