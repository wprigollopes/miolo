<?php

class frmWindow1 extends MForm
{
//
//  Sample form which will reside inside a window
//
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Dialog Sample', $module));
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields = array(
            new MTextField('edtMoney1', '', _M('Value @1', $module, '1')),
            new MTextField('edtMoney2', '', _M('Value @1', $module, '2')),
            new MTextField('edtSoma', '', _M('Sum', $module)),
        );
        $this->setFields($fields);

        $buttons = array(
            new MButton('btnSum', _M('Sum', $module)),
            // how to create a close button
            new MButton('btnClose', _M('Close', $module), $this->getCloseWindow()),
        );
        $this->setButtons($buttons);
    }

    public function sum()
    {
        $money1 = $this->getFieldValue('edtMoney1');
        $money2 = $this->getFieldValue('edtMoney2');
        return $money1 + $money2;
    }

    public function btnSum_click()
    {
        $soma = $this->sum();
        $this->setFieldValue('edtSoma', $soma);
    }
}
