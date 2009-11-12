<?php
class Modela_Mapper
{
	protected static $_db_adapter;
	protected static $_cache_adapter;
	
	protected $_table;
	
	public function __construct()
	{
		
	}
	
	public static function setDbAdapter(Zend_Db_Adapter_Abstract $adapter)
	{
		if (null !== $adapter) {
			self::$_db_adapter = $adapter;
		}
	}
	
	public function save($table, $values, $overwrite = false)
	{
		$sql = "INSER";
	}
	
	protected function _query($sql, $params)
	{
		$adapter = self::$_db_adapter;
	}
}