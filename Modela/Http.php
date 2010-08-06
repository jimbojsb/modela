<?php
class Modela_Http
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT = 'PUT';
    
    protected $_data;
    protected $_uri;
    protected $_method;
    
    
    public function setMethod($method)
    {
        $this->_method = $method;
    }
    
    public function setUri($uri)
    {
        $this->_uri = $uri;
    }
    
    public function setData($data)
    {
        
    }
    
    
    public function request()
    {
        if ($this->_uri === null) {
            throw new Modela_Exception("How can I make a request without a uri?");
        }
        $uriParts = parse_url($this->_uri);
        
        $sock = fsockopen($uriParts["host"], $uriParts["port"]);
        $requestString = $this->_method . " " . $uriParts["path"];
        fwrite($sock, $requestString);
        fwrite($sock, "\r\n\r\n");
        
        $output = '';
        while (!feof($sock)) {
            $output .= fread($sock, 1024);
        }
        fclose($sock);
        return $output;        
    }
}