<?php

class frmContent extends MForm
{
    public function __construct()
    {
        parent::__construct('MContent');
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
        $this->defaultButton = false;
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields = array(
            new MLabel(_M('Content of file', $module) . ': example/html/files/miolo.txt', 'blue', true),
            new MContent('example', '/html/files/miolo.txt')
        );
        $this->setFields($fields);
    }
}
