<?php
class Modela_Adapter_Db_Mysqli implements Modela_Adapter_Db_Interface
{
    protected $_conn;
    
    public function __construct(stdClass $params)
    {
        $this->_conn = new mysqli($params->host, $params->username, $params->password, $params->databse);    
    }
    
    public function query($query)
    {
        
    }
    
    public function fetchOne()
    {
        
    }
    
	public function fetchAll ()
    {
        
    }

	public function fetchRow ()
    {
        
    }

	public function getConnection ()
    {
        
    }
}