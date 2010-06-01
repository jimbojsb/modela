<?php
interface Modela_Adapter_Db_Interface
{
    public function getConnection();
    public function fetchAll($query, $params);
    public function query($query, $params);
}