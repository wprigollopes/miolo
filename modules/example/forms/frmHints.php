<?php

class frmHints extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Controls Hints Sample', MIOLO::getCurrentModule()));
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $fields['txt'] = new MText('txt', _M('Put the cursor upon the field to see the hint', $module));
        $fields['txt']->setBold();

        $fields['field1'] = new MTextField('field1', '', _M('Field', $module));
        $fields['field2'] = new MTextField('field2', '', _M('Read-only Field', $module));

        $fields['field1']->setJsHint(_M('A hint', $module));
        $fields['field2']->setJsHint(_M('Another hint', $module));
        $fields['field2']->setReadOnly(true);

        $this->setFields($fields);

        $buttons[] = new MBackButton();
        $this->setButtons($buttons);
    }
}
