<?php

class frmWebServices extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('WebServices demo', $this->module));
        
        $this->eventHandler();
    }
    
    public function createFields()
    {    
        $postalCode = new MTextField(
            'postalCode',
            $this->postalCode->value,
            _M('Postal code', $this->module),
            20,
            _M('Valid codes are', $this->module) . ': 95880-000, 95885-000, 95900-000'
        );
        $postalCode->setJsHint(_M('Please, inform a postal code', $this->module));
        $fields[] = $postalCode;
    
        $fields[] = new MDiv('resultDiv', '');
        
        $buttons = array( new MButton('btnGet', _M('Get city name', $this->module), ':getWebService') );

        $this->setShowPostButton(false);
        $this->setButtons($buttons);
        
        $this->setFields($fields);
        $validators = array();
        $this->setValidators($validators);        
    }
    
    public function getWebService($sender=NULL)
    {
        // wsClientDemo is located in directory webservices
        $ws = $this->manager->getWebServices('example', 'wsClientDemo');
        // this method must exist in the above file  
        $rs = $ws->getCityName(MIOLO::_REQUEST('postalCode'));
        
        $this->setResponse(_M('Server result', $this->module) . ': '.$rs, 'resultDiv');
    }
}
