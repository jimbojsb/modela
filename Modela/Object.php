<?php
abstract class Modela_Object
{
	protected $_storage = array();
	
	/**
	 * 
	 * @var Modela_Mapper
	 */
	protected $_mapper;
	protected $_table;
	protected $_mapper_class;
	
	public function __construct($properties = null)
	{
		if (is_array($properties)) {
			foreach ($properties as $key=>$value) {
				$this->$key = $value;
			}
		}

		if (isset($this->_mapper_class)) {

		} else if (false) {
		} else {
			$classSuffix = get_class($this);
			$classSuffix = ucfirst(str_replace('Model_', '', $classSuffix));
			//@todo make this autoload
			//temporary, related to github issue #1
			require_once (APPLICATION_PATH . "/models/mappers/{$classSuffix}Mapper.php");
			$this->_mapper_class = "Mapper_$classSuffix";
			$this->_mapper = new $this->_mapper_class();
		}
		
		return $this;
	}
	
	public function __get($property)
	{
		$methodstring = "get$property";
		if (method_exists($this, $methodstring)) {
			return call_user_func(array($this, $methodstring));
		}
		return $this->_storage[$property];
	}
	
	public function __set($property, $value)
	{
		$this->_storage[$property] = $value;
	}
	
	public function __call($function, $args)
	{
		if (!method_exists($this, $function) || substr($function, 0, 2) === '__') {
			$args[] = $this;
			call_user_func_array(array($this->_mapper, $function), $args);
		}
	}
}