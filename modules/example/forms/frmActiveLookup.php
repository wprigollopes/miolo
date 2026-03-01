<?php

class frmActiveLookup extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('@1 Sample', $module, 'ActiveLookup'));
        $objGrupo = $this->manager->getBusinessMAD('group');
        $objQuery = $objGrupo->listAll();

        $fields = array(
            new MHiddenField('idtrans', ''),
            new MTextField('grupo', '', _M('Group', $module)),
            new MActiveLookup('lkpTransacao', '', _M('Transaction', $module), 45)
        );
        $this->setFields($fields);
        $this->setFieldAttr('lkpTransacao', 'baseModule', 'example');
        $this->setFieldAttr('lkpTransacao', 'module', 'example');
        $this->setFieldAttr('lkpTransacao', 'item', 'transaction');
        $this->setFieldAttr('lkpTransacao', 'event', 'filler');
        $this->setFieldAttr('lkpTransacao', 'related', 'idtrans,lkpTransacao');
        $this->setFieldAttr('lkpTransacao', 'lwidth', '400');
        $this->setFieldAttr('lkpTransacao', 'lheight', '215');
        $buttons = array(
            new MButton('btnPost', _M('Send'))
        );
        $this->setButtons($buttons);
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
    }
}
