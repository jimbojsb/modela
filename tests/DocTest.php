<?php
require_once ('../Modela/Exception.php');
require_once ('../Modela/Http.php');
require_once ('../Modela/Response.php');
require_once ('mocks/CoreMock.php');
require_once ('mocks/ModelMock.php');
class DocTest extends PHPUnit_Framework_TestCase
{     
    public function testSetterOverrides()
    {
        $model = new ModelMock();
        $model->name = 'test';
        $model->overrides = 'test';
        $this->assertEquals($model->name, 'test');
        $this->assertEquals($model->overrides, 'testpasses');
    }
    
    public function testGetterOverrides()
    {
        $model = new ModelMock();
        $this->assertEquals($model->name, 'testobject');
        $this->assertEquals($model->getters, 'testpasses');
    }
    
    public function testConstructorWithData()
    {
        $data = array('foo' => 'bar');
        $model = new Modela_Doc($data);
        $this->assertEquals($model->asArray(), array('foo' => 'bar', 'type' => 'modela_doc'));
        
        $model = new Modela_Doc();
        $this->assertEquals($model->asArray(), array('type' => 'modela_doc'));
    }
    
    public function testSet()
    {
        $model = new Modela_Doc();
        $model->set('foo', 'bar');
        $this->assertEquals($model->foo, 'bar');
        
        $model->set('foo', null);
        $this->assertEquals($mode->foo, null);
        $this->assertEquals($model->asArray(), array('type' => 'modela_doc'));
    }
    
    public function testToString()
    {
        $model = new Modela_Doc();
        $this->assertEquals($model->__toString(), json_encode($model->asArray()));
    }
    
    public function testProcesResponse()
    {
        $response = array('type' => 'ModelMock', 'foo' => 'bar');
        $obj = Modela_Doc::processResponseArray($response);
        $this->assertEquals($obj->foo, 'bar');
        $this->assertEquals(get_class($obj), 'ModelMock');
        
        $response = array('foo' => 'bar');
        $obj = Modela_Doc::processResponseArray($response);
        $this->assertEquals($obj->foo, 'bar');
        $this->assertEquals(get_class($obj), 'Modela_Doc');
    }
}