<?php
class Modela_Config
{
    public $dbConnectionString;
    public $cacheConnectionString;
    
    public function getDbConnectionParams()
    {
        if (isset($this->dbConnectionString)) {
            $parts = parse_url($this->dbConnectionString);
            $params = new stdClass();
            $params->adapterType = $parts["scheme"];
            $params->username = $parts["user"];
            $params->password = $parts["pass"];
            $params->host = $parts["host"];
            $params->database = $parts["path"];
            return $params;
        }
        return false;
    }
}