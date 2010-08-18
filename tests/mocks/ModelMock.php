<?php
require_once '../Modela/Doc.php';
class ModelMock extends Modela_Doc
{
    protected $_storage = array('name' => 'testobject',
                                'overrides' => 'maybe',
                                'getters' => 'probably');
    
    public function setOverrides($val)
    {
        $this->_storage['overrides'] = 'testpasses';
    }
    
    public function getGetters()
    {
        return 'testpasses';
    }
    

}