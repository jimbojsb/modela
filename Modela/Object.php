<?php
abstract class Modela_Object
{
	protected $_storage = array();
	protected $_mapper;
	protected $_table;
	
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
			$this->_mapper = new Modela_Mapper();
		}
		
		
		return $this;
	}
	
	public function save()
	{
		if (!$this->_table) {
			throw new Exception();
		}
		
		$this->_mapper->save($this->_table, $this->_storage);
	}
	
	public function __get($property)
	{
		return $this->_storage[$property];
	}
	
	public function __set($property, $value)
	{
		$this->_storage[$property] = $value;
	}
}