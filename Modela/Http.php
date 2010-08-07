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
    protected $_headers;
    protected $_response;
    
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
        $this->_data = $data;   
    }
    
    
    public function request()
    {
        if ($this->_uri === null) {
            throw new Modela_Exception("How can I make a request without a uri?");
        }
        $uriParts = parse_url($this->_uri);
        
        $sock = fsockopen($uriParts["host"], $uriParts["port"]);
        $requestString = $this->_method . " " . $uriParts["path"];
        
        $socketData = $requestString . "\r\n";;
        if ($this->_data) {
            $socketData .= "Content-length: " . strlen($this->_data) . "\r\n";
            $socketData .= "Content-type: application/json \r\n";
            $socketData .= "\r\n";
            $socketData .= $this->_data . "\r\n";
        }
        $socketData .= "\r\n\r\n";
        
        fwrite($sock, $socketData);
        
        $output = '';
        while (!feof($sock)) {
            $output .= fread($sock, 1024);
        }
        list($this->_headers, $this->_response) = explode("\r\n\r\n", $output);
        $this->_response = trim($this->_response);  
        fclose($sock);
        return $this->_response;
    }
}