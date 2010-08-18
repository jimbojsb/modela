<?php
require_once ('../Modela/View.php');
class ViewTest extends PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $view = new Modela_View();
        $name = $view->getName();
        $this->assertEquals($name, 'modela_view');
    }
}