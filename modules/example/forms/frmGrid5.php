<?php

class frmGrid5 extends MForm
{
    public function __construct()
    {   
        parent::__construct(_M('Grid', MIOLO::getCurrentModule()));
        $this->eventHandler();
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $fields[] = $this->manager->getUI()->getGrid($module, 'grdList5', _M('Grid @1', $module, '1'));
        $fields[] = $this->manager->getUI()->getGrid($module, 'grdList5', _M('Grid @1', $module, '2'));

        $this->addFields($fields);

        $buttons[] = new MBackButton();
        $this->setButtons($buttons);
    }
}
