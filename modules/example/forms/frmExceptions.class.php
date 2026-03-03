<?php

class FrmExceptions extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Exceptions Sample', MIOLO::getCurrentModule()));
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields = array(
            new MTextField('edtLogin', '', _M('Login', $module)),
            new MTextField('edtSenha', '', _M('Password', $module))
        );
        $this->setFields($fields);
        $buttons = array(
            new MButton('btnE1', _M('Safe include', $module)),
            new MButton('btnE2', _M('Include generating exception', $module))
        );
        $this->setButtons($buttons);
    }

    public function btnE1_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $usuario = $MIOLO->getBusiness('example', 'user');
        $usuario->login = $this->getFieldValue('edtLogin');
        $usuario->password = $this->getFieldValue('edtSenha');
        try
        {
            $usuario->save_ok();
            $this->addInfo(_M('Data successfully saved', $module));
        }
        catch ( Exception $e )
        {
            $this->addError($e->getMessage());
        }
    }

    public function btnE2_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $usuario = $MIOLO->getBusiness('example', 'user');
        $usuario->login = $this->getFieldValue('edtLogin');
        $usuario->password = $this->getFieldValue('edtSenha');
        try
        {
            $usuario->save_error();
            $this->addInfo(_M('Data successfully saved', $module));
        }
        catch ( Exception $e )
        {
            $this->addError($e->getMessage());
        }
    }
}
?>
