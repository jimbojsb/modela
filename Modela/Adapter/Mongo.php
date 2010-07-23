<?php
class Modela_Adapter_Mongo implements Modela_Adapter_Interface
{
    private $_conn;
    private $_db;
    
    public function __construct($hostname, $username, $password, $dbname)
    {
        $connectionString = "mongodb://" . $hostname;
        $this->_conn = new Mongo($connectionString);
        $this->_db = $this->_conn->$dbname;    
    }
    
    public function getConnection()
    {
        return $this->_conn;
    }
    
    public function setDb($dbName)
    {
        $this->_db = $dbName;
    }
}