<?php
class Modela_Adapter_Mongo implements Modela_Adapter_Interface
{
    private $_conn;
    private $_db;
    
    public function __construct($options)
    {
        $hostname = $options["host"];
        $db = $options["db"];
        $connectionString = "mongodb://" . $hostname;
        $this->_conn = new Mongo($connectionString);
        $this->_db = $this->_conn->$db;    
    }
    
    public function getConnection()
    {
        return $this->_conn;
    }
    
    public function getDb()
    {
        return $this->_db;
    }
    
    public function save(Modela_Doc $doc)
    {
        $collectionName = strtolower($doc->getCollection());
        $collection = $this->_db->$collectionName;
        $data = $doc->asArray();
        $collection->save($data);   
    }
    
    public function delete(Modela_Doc $doc)
    {
        
    }
    
    public function setDb($dbName)
    {
        $this->_db = $dbName;
    }
}