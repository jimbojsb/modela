<?php
class Modela_Core
{
    public static function getInstance()
    {
        return new Modela_Core();
    }
    
    public function doRequest($method, $uri, $data, $isDatabaseRequest = true)
    {
        
    }
}