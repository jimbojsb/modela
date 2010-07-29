<?php
interface Modela_Adapter_Interface
{
    public function getDb();
    public function save(Modela_Doc $doc);
    public function delete(Modela_Doc $doc);
}