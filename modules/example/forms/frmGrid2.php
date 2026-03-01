<?php

class frmGrid2 extends MForm
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

        $fields[] = $this->manager->getUI()->getGrid($module,'grdList2');

        $this->addFields($fields);

        $buttons[] = new MBackButton();
        $this->setButtons($buttons);
    }
}
