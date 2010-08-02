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
        $data = $this->_convertId($doc->asArray());
        $collection->save($data);   
    }
    
    public function delete(Modela_Doc $doc)
    {
        if (!$doc->id) {
            throw new Modela_Exception("document has no id therefore it would be difficult to delete it");
        }
        $query = new Modela_Query();
        $data = $this->_convertId($doc->asArray());
        $query->_id = $data["_id"];
        $collectionName = $doc->getCollection();
        $collection = $this->_db->$collectionName;
        $collection->remove($query->asArray());     
    }
    
    public function find(Modela_Query $query)
    {
        $collectionName = $query->getCollection()->getName();
        $collection = $this->_db->$collectionName;
        $data = $collection->find($query->asArray());
        
        if ($data->count() > 0) {
            $res = array();
            while ($data->hasNext()) {
                $row = $data->getNext();
                $row["id"] = $row["_id"]->__toString();
                unset($row["_id"]);
                $res[] = $row;   
            }
            return $res;
        } else {
            return array();
        }
    }
    
    public function setDb($dbName)
    {
        $this->_db = $dbName;
    }
    
    /**
     * 
     * @param Model_Doc $doc
     * @return Modela_Doc
     */
    protected function _convertId(Array $data) 
    {
        if (isset($data["id"])) {
            $data["_id"] = new MongoId($data["id"]);
            unset($data["id"]);
        }
        return $data;
    }
}