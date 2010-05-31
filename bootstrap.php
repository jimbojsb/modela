<?php
require_once('Modela/Loader.php');
$loader = Modela_Loader::getInstance();
spl_autoload_register(array($loader, 'loadClass'));
 
Modela_Core::init();