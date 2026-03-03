<?php

class FrmViewState extends MForm
{
    public function __construct()
    {
        global $state;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $page = $MIOLO->getPage();

        $this->form('ViewState');
        if ( $page->isPostBack() )
        {
            $state->set('a', $state->get('a') + 1);
            $this->setFieldValue('msg', "PostBack:  [a = " . $state->get('a') . ']');
        }
        else
        {
            $this->setFieldValue('msg', _M('First time', $module));
            $state->set('a', 1);
        }
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields = array(
            new TextLabel('msg', '', _M('Message', $module)),
        );
        $this->setFields($fields);
    }
}
?>
