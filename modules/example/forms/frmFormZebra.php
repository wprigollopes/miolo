<?php

class frmFormZebra extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Zebra Form Sample', MIOLO::getCurrentModule()));
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $selection = array( 'a', 'b', 'c', 'd', 'e' );

        $rYes = new MRadioButton('rYes', 'yes', '', false, _M('Yes', $module));
        $rNo = new MRadioButton('rNo', 'no', '', false, _M('No', $module));
        $rMaybe = new MRadioButton('rMaybe', 'maybe', '', false, _M('Maybe', $module));

        $fields = array(
            new MTextField('idaluno', '', 'Id', 15, 'autoPostBack'),
            new MLookupTextField('aluno', 'A', _M('Student', $module), 35),
            new MCalendarField('cal', '01/01/2005', _M('Date', $module)),
            new MSelection('idmodulo', 'admin', _M('System', $module), $selection, false, 'autoPostBack'),
            new MRadioButtonGroup('rGroup', _M('A choice with "autoPostBack"', $module), array( $rYes, $rNo, $rMaybe )),
            new MCheckBox('chk', 'default', _M('A check', $module), true, _M('A text', $module), 'autoPostBack'),
        );

        $this->setFields($fields);
        $this->setFieldAttr('aluno', 'module', 'example');
        $this->setFieldAttr('aluno', 'item', 'aluno');
        $this->setFieldAttr('aluno', 'event', 'filler');
        $this->setFieldAttr('aluno', 'related', 'idaluno,aluno');

        $this->setFieldAttr('idaluno', 'autoPostBack', true);
        $this->setFieldAttr('idmodulo', 'autoPostBack', true);
        $this->setFieldAttr('chk', 'autoPostBack', true);

        $buttons = array(
            new MButton('btnPost', _M('Send')),
            new MButton('btnNew', _M('New', $module)),
            new MButton('btnDelete', _M('Remove', $module)),
            new MButton('btnList', _M('Relation', $module)),
            new MButton('btnReport', _M('Reports', $module)),
        );

        $this->setButtons($buttons);
        $this->setLabelWidth('20');
        $this->setAlternate('#fff', '#ccc');
        $this->setBackgroundColor('#FFF');
    }

    public function btnPost_click()
    {
        
    }

    public function btnEdit_click()
    {

    }

    public function btnList_click()
    {
        
    }

    public function btnDelete_click()
    {

    }

    public function btnNew_click()
    {
        
    }
}
