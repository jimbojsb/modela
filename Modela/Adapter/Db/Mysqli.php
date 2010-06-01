<?php
class Modela_Adapter_Db_Mysqli implements Modela_Adapter_Db_Interface
{
    protected $_conn;
    
    public function __construct(stdClass $params)
    {
        $this->_conn = new mysqli($params->host, $params->username, $params->password, $params->database);    
    }
    
    public function query($query, $params)
    {
        return $this->_conn->query($query);
    }
    
	public function fetchAll($query, $params)
    {
        $results = $this->query($query, $params);
        $return = array();
        while ($result = $results->fetch_assoc()) {
            $return[] = $result;   
        }
        return $return;
    }

	public function getConnection()
    {
        
    }
}