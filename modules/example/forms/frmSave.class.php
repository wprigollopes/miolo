<?php

class frmSave extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        $this->form(_M('Save file', $module) . ' html/files/exampleFile.txt');
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $buttons[] = new MButton('btnSave', _M('Save', $module) . ' exampleFile.txt');
        $this->setButtons($buttons);
    }

    public function btnSave_click()
    {
        $MIOLO = MIOLO::getInstance();
        $MIOLO->saveFile('example', 'exampleFile.txt');
    }
}
?>
