<?php
abstract class Modela_Object
{
	protected $_storage = array();
	
	public static function query($query, $params = null)
	{
	    $returnType = self::$_returnType;
	    $adapter = Modela_Core::getDbAdapter();
	    $results = $adapter->fetchAll($query, $params);
	    $data = array();
	    foreach ($results as $result) {
	        $obj = new $returnType();
	        foreach ($result as $key => $val) {
	            $obj->$key = $val;
	        }
	        $data[] = $obj;
	    }
	    return new Modela_Collection($obj);
	}
}