<?php
require_once('Modela/Loader.php');
$loader = Modela_Loader::getInstance();

 
$config = new Modela_Config();
$config->dbConnectionString = "mysqli://offers_site:2ownOffersSite@localhost/offers_site";
Modela_Core::init($config);

require_once('TestObject.php');
$offers = TestObject::get();
print_r($offers);