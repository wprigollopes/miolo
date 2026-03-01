<?php

class frmFileContent extends MForm
{
    public function __construct()
    {
        parent::__construct('MFileContent');
        $this->defaultButton = false;
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $filename = $MIOLO->getModulePath('example', '/forms/frmFileContent.php');
        $fields[] = new MFileContent($filename, true);
        $this->setFields($fields);
    }
}
