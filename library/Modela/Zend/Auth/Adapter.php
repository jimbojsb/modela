<?php
class Modela_Zend_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
    protected $_passwordValue;
    protected $_passwordField;
    protected $_identityField;
    protected $_identityValue;
    protected $_designDoc;
    protected $_view;
    
    public function authenticate()
    {
        $user = Modela_Doc::find($this->_designDoc, $this->_view, array("key" => $this->_identityValue), true);
        if ($user) {
            $user = array_shift($user);
            if ($this->_passwordIsValid($user)) {
                $result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user, array());                
            } else {
                $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null);
            }
        } else {
            $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null);                
        }
        return $result;
    }
    
    protected function _passwordIsValid($user)
    {
        $sentPassword = md5($this->_passwordValue);
        if ($sentPassword == $user->{$this->_passwordField}) {
            return true;
        }
        return false;
    }
    
	public function setPasswordValue($_passwordValue)
    {
        $this->_passwordValue = $_passwordValue;
        return $this;
    }

	public function setPasswordField($_passwordField)
    {
        $this->_passwordField = $_passwordField;
        return $this;
    }

	public function setIdentityField($_identityField)
    {
        $this->_identityField = $_identityField;
        return $this;
    }

	public function setIdentityValue($_identityValue)
    {
        $this->_identityValue = $_identityValue;
        return $this;
    }

	public function setDesignDoc($_designDoc)
    {
        $this->_designDoc = $_designDoc;
        return $this;
    }

	public function setView($_view)
    {
        $this->_view = $_view;
        return $this;
    }
}