<?php
class Modela_Http
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT = 'PUT';
    const HTTP_CRLF = "\r\n";
    
    protected $_data;
    protected $_uri;
    protected $_uriParts = array();
    protected $_method;
    protected $_headers;
    protected $_response;
    
    public function __construct($uri = null) 
    {
        if ($uri !== null) {
            $this->setUri($uri);
        }    
    }
    
    public function setMethod($method)
    {
        $validMethods = array(Modela_Http::METHOD_DELETE,
                              Modela_http::METHOD_GET,
                              Modela_Http::METHOD_POST,
                              Modela_Http::METHOD_PUT);
        if (in_array($method, $validMethods)) {
            $this->_method = $method;
        } else {
            throw new Modela_Exception("$method is not a valid HTTP method");
        }
    }
    
    public function setUri($uri)
    {
        if ($uri === null) {
            throw new Modela_Exception("uri is not valid");
        }
        $uriParts = parse_url($uri);
        if (!(isset($uriParts['path']) &&
              isset($uriParts['host']) &&
              isset($uriParts['scheme']))) {
            throw new Modela_Exception("uri is not valid");
        }
        $this->_uri = $uri;
        $this->_uriParts = $uriParts;
    }
    
    public function setData($data)
    {
        $this->_data = $data;   
    }
    
    
    public function request()
    {
        if ($this->_uri === null) {
            throw new Modela_Exception("uri is not valid");
        }
        $sock = @fsockopen($this->_uriParts["host"], $this->_uriParts["port"]);
        if (!$sock) {
            throw new Modela_Exception('unable to open socket');
        }
        $requestString = $this->_method . " " . $this->_uriParts["path"];
        if ($this->_uriParts["query"]) {
            $requestString .= "?" . $this->_uriParts["query"];
        }
        
        $socketData = $requestString . self::HTTP_CRLF;
        if ($this->_data) {
            $socketData .= "Content-length: " . strlen($this->_data) . self::HTTP_CRLF;
            $socketData .= "Content-type: application/json" . self::HTTP_CRLF;
            $socketData .= self::HTTP_CRLF;
            $socketData .= $this->_data . self::HTTP_CRLF;
        }
        $socketData .= self::HTTP_CRLF . self::HTTP_CRLF;
        
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