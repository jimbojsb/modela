<?php
class Modela_Zend_Application_Resource_Modela extends Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {    
        require_once('Modela/Loader.php');
        $loader = Modela_Loader::getInstance();
        $loader->loadModels($this->_options['modelsPath']);
        
        $modela = Modela_Core::getInstance();
        $modela->setHostname($this->_options["hostname"]);
        $modela->setDatabase($this->_options["database"]);

        return $modela;
    }
}