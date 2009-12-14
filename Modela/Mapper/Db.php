<?php
class Modela_Mapper_Db
{
	protected $_db_primary_key = 'id';
	protected $dbcols = array();
	
	/**
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected static $_db_adapter;
	
	
	public function __construct()
	{
		
	}
	
	public static function setDbAdapter(Zend_Db_Adapter_Abstract $adapter)
	{
		if (null !== $adapter) {
			self::$_db_adapter = $adapter;
		}
	}
	
	protected function _query($sql, $params)
	{
		$adapter = self::$_db_adapter;
		
		if (!is_array($params) && $params !== null) {
			$params = array($params);
		}
		
		return $adapter->fetchAll($sql, $params);
	}
}