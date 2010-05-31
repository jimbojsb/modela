<?php
require_once('Modela/Loader.php');
$loader = Modela_Loader::getInstance();

 
$config = new Modela_Config();
$config->dbConnectionString = "mysqli://offers_site:2ownOffersSite@localhost/offers_site";

Modela_Core::init($config);