<?php

class frmMultiText4 extends MForm
{
    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $page = $MIOLO->getPage();

        parent::__construct('MMultiTextField 4');

        $options_arr1 = array(
            'op1' => _M('Option @1', $module, '1'),
            'op2' => _M('Option @1', $module, '2'),
            'op3' => _M('Option @1', $module, '3'),
            'op4' => _M('Option @1', $module, '4'),
            'op5' => _M('Option @1', $module, '5')
        );
        $options_arr2 = array( 'op1', 'op2', 'op3', 'op4', 'op5' );

        $f = array( array( 'id', 'label', 'nome' ) );
        $lkp = new MLookupTextField('lkpAluno', '', _M('Student', $module), 25);
        $lkp->baseModule = 'example';
        $lkp->module = 'example';
        $lkp->item = 'aluno';
        $lkp->event = 'filler';
        $lkp->related = 'none,lkpAluno';
        $f1 = array(
            new MTextField('txfId', '', 'Texto', 20),
            $lkp,
            new MSelection('selIdSistema', '', _M('System', $module), $options_arr1)
        );
        $fields = array(
            new MMultiTextField4('mt2', NULL, _M('Example with horizontal layout', $module), $f1, 200, TRUE, 'horizontal'),
        );
        $this->setFields($fields);
        $this->setFieldAttr('mt2', 'info', _M('This is the info attribute', $module));
        $this->setFieldAttr('mt2', 'fieldWidth', 240);
        $this->mt2->setListTitle(array( _M('Text', $module).':80', _M('Student', $module).':80', _M('System', $module) ));

        $buttons = array( new MButton('btnPost', _M('Send', $module)) );
        $this->setButtons($buttons);
        if ( $page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function btnPost_click()
    {
        $module = MIOLO::getCurrentModule();
        $value = $this->mt2->getCodeValue();
        $this->addField(new MTableRaw(_M('Field @1', $module, 'mt2'), $value));
    }
}
