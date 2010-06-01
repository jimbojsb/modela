<?php
class Modela_Core
{
    protected static $_config;
    protected static $_dbAdapter;
    protected static $_cacheAdapter;
    protected static $_isInitialized;
    protected static $_debugMode;
    
    public static function init(Modela_Config $config)
    {
        self::$_config = $config;    
        
        if (self::$_config->dbConnectionString) {
            self::initDbAdapter();
        }
        
        if (self::$_config->debug) {
            self::debugMode(true);
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
    
    public static function debugMode($enableDebug)
    {
        self::$_debugMode = $enableDebug;
        return self::$_debugMode;    
    }
    
    /**
     * @return Modela_Adapter_Db_Interface
     */
    public static function getDbAdapter()
    {
        return self::$_dbAdapter;
    }
    
    protected static function initDbAdapter()
    {
        $config = self::$_config;
        $dbParams = $config->getDbConnectionParams();
        $dbAdapterClass = "Modela_Adapter_Db_" . $dbParams->adapterType;
        $adapter = new $dbAdapterClass($dbParams);
        self::$_dbAdapter = $adapter;
    }
    
    protected static function initCacheAdapter($adapterOptions)
    {
        
    }
}