<?php
class Modela_View
{
    public $map;
    public $reduce;
    
    public function getName()
    {
        return strtolower(get_class($this));
    }
}