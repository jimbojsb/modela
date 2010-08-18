<?php
require_once ('../Modela/Http.php');
require_once ('../Modela/Exception.php');
class HttpTest extends PHPUnit_Framework_TestCase
{
    protected $http;
    
    public function setUp()
    {
        $this->http = new Modela_Http();
    }
    
    public function tearDown()
    {
        unset($this->http);
    }
    public function testsetMethodAcceptsValidParams()
    {
        $this->http = new Modela_Http();
        $validMethods = array(Modela_Http::METHOD_DELETE,
                              Modela_http::METHOD_GET,
                              Modela_Http::METHOD_POST,
                              Modela_Http::METHOD_PUT);

        foreach ($validMethods as $method) {
            try {
                $this->http->setMethod($method);
            } catch (Modela_Exception $e) {
                $this->fail('Setting a valid method should not throw an exception');
            }
        }                              
    }
    
    public function testSetMethodRejectsInvalidParams()
    {
        $this->setExpectedException('Modela_Exception', "foo is not a valid HTTP method");
        $this->http->setMethod('foo');
    }
    
    public function testSetUriCatchesNullUri()
    {
        $this->setExpectedException('Modela_Exception', 'uri is not valid');
        $this->http->setUri(null);
    }
    
    public function testRequestCatchesNullUri()
    {
        $this->setExpectedException('Modela_Exception', 'uri is not valid');
        $this->http->request();
    }
    
    public function testSetUriCatchesInvalidUri()
    {
        $this->setExpectedException('Modela_Exception', 'uri is not valid');
        $this->http->setUri('foo');
    }
    
    public function testSetUriAcceptsValidUri()
    {
        try {
            $this->http->setUri('http://localhost:5984/foo');
        } catch (Modela_Exception $e) {
            $this->fail('Modela_Http::setUri() should accept valid uris without throwing an exception');
        }
    }
    
    public function testRequestCatchesBadSocket()
    {
        $this->setExpectedException('Modela_Exception', 'unable to open socket');
        $this->http->setUri('http://www.example.com:9345/');
        $this->http->request();
    }
}