<?php
class Modela_Cli
{
    protected $_arguments;
    protected $_action;
    protected $_options;
    
    public function __construct($argv)
    {
        $this->_arguments = $argv;
        foreach ($this->_arguments as $argument) {
            if (preg_match('`--([a-z]+)=([a-z0-9]+)`', $argument, $matches)) {
                $this->_options[$matches[1]] = $matches[2];
            } else {
                $this->_action = $argument;
            }
        }
    }   
    
    
    
    public function run()
    {
        switch ($this->_action) {
            case "loadviews":
                if ($this->_options['viewspath']) {
                    $viewsPath = $this->_options['viewspath'];
                } else {
                    $viewsPath = realpath(getcwd());
                }
                Modela_View::loadViews($viewsPath);
                break;
        }    
    }
}