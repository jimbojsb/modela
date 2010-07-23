<?php
class Modela_Core
{
    const MODELA_FOLDER_COLLECTIONS = 'collections';
    const MODELA_FOLDER_OBJECTS = 'objects';
    
    protected static $_adap;
    protected static $_modelPath;
    
    public static function init($adapter)
    {
        self::$_adap = $adapter;
    }
    
    public static function setModelPath($path)
    {
        self::$modelPath = $path;
    }
    
    public static function loadModels($lazy = true)
    {
        $path = self::$_modelPath;
    }
    
    public static function reset()
    {
        self::$_adap = null;
        self::$_modelPath = null;
    }
}