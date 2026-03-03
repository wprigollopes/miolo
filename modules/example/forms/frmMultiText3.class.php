<?php

class frmMultiText3 extends MForm
{
    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $page = $MIOLO->getPage();

        parent::__construct('MMultiTextField 3');

        $options_arr1 = array(
            'op1' => _M('Option @1', $module, '1'),
            'op2' => _M('Option @1', $module, '2'),
            'op3' => _M('Option @1', $module, '3'),
            'op4' => _M('Option @1', $module, '4'),
            'op5' => _M('Option @1', $module, '5')
        );
        $options_arr2 = array( 'op1', 'op2', 'op3', 'op4', 'op5' );

        $lkp1 = new MLookupTextField('lkpAluno1', '', _M('Student', $module), 30);
        $lkp1->baseModule = 'example';
        $lkp1->module = 'example';
        $lkp1->item = 'aluno';
        $lkp1->event = 'filler';
        $lkp1->related = 'id1,lkpAluno1';
        $lkp2 = new MLookupTextField('lkpAluno2', '', _M('Student', $module), 30);
        $lkp2->baseModule = 'example';
        $lkp2->module = 'example';
        $lkp2->item = 'aluno';
        $lkp2->event = 'filler';
        $lkp2->related = 'id2,lkpAluno2';

        $f = array( array( 'id', 'label', 'nome' ) );
        $f1 = array(
            new MTextField('id1', '', _M('Text', $module), 20),
            $lkp1,
            new MSelection('selIdSistema1', '', _M('System', $module), $options_arr1)
        );
        $f2 = array(
            new MTextField('id2', '', _M('Text', $module), 20),
            $lkp2,
            new MSelection('selIdSistema2', '', _M('System', $module), $options_arr1)
        );
        $fields = array(
            new MMultiTextField3('mt1', NULL, _M('Example with vertical layout', $module), $f1, 240, true, 'vertical'),
            new MMultiTextField3('mt2', NULL, _M('Example with horizontal layout', $module), $f2, 240, true, 'horizontal'),
        );
        $this->setFields($fields);
        $this->setFieldAttr('mt1', 'info', _M('This is the info attribute', $module));
        $this->setFieldAttr('mt2', 'info', _M('This is the info attribute', $module));

        $buttons = array(
            new MButton('btnPost', _M('Send')),
        );
        $this->setButtons($buttons);
        if ( $page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function btnPost_click()
    {
        $module = MIOLO::getCurrentModule();

        $value = $this->mt1->getCodeValue();
        $this->addField(new MTableRaw(_M('Field', $module) . ' mt1', $value));
        $value = $this->mt2->getCodeValue();
        $this->addField(new MTableRaw(_M('Field', $module) . ' mt2', $value));
    }
}
?>
