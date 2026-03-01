<?php

class frmGridLimitOffset extends MForm
{
    public function __construct()
    {   
        parent::__construct(_M('Grid using LIMIT/OFFSET', MIOLO::getCurrentModule()));
        $this->eventHandler();
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $fields[] = $this->manager->getUI()->getGrid($module, 'grdLimitOffset');

        $this->addFields($fields);

        $buttons[] = new MBackButton();
        $this->setButtons($buttons);
    }
}
