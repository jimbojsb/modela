<?php
class TestObject extends Modela_Object
{    
    protected static $_returnType = 'TestObject';
    '
    public static function get()
    {
        $sql = "SELECT *
                FROM offers
                LIMIT 3";
        return self::query($sql);
    }
}