<?php
interface Modela_Adapter_Db_Interface
{
    public function getConnection();
    public function query($query);
    public function fetchRow();
    public function fetchAll();
    public function fetchOne();
}