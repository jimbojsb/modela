<?php
class Modela_Collection implements IteratorAggregate
{
    protected $_storage;
    
    protected static $_sortProperty;
    protected static $_sortBackward;
    
    public function __construct(Array $data = null)
    {
        if ($data !== null) {
            $this->setData($data);
        }
    }
    
    public function setData($data)
    {
        $this->_storage = $data;
    }
    
    public function getIterator()
    {
        return new ArrayIterator($this->_storage);
    }
    
    public function asArray()
    {
        
    }
    
    public function sort($property, $backward = false)
    {
        self::$_sortProperty = $property;
        if ($backward) {
            self::$_sortBackward = true;
        }
        usort($this->_storage, array('self', 'propertySort'));
    }
    
    public function sortCallback($callback)
    {
        usort($this->_storage, $callback);
    }
    
    protected static function propertySort($val1, $val2)
    {
        $property = self::$_sortProperty;
        
        if (self::$_sortBackward) {
            $tmp = $val1;
            $val1 = $val2;
            $val2 = $tmp;
        }
        
        if ($val1->$property > $val2->$property) {
            return 1;
        } else if ($val1->$property < $val2->$property) {
            return -1;
        }
        return 0;
    }

}