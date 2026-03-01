<?php

class frmForm extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('@1 Sample', $module, 'MForm').' - '._M('width', $module).': 70%');
        $this->setWidth('70%');
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $selection1 = array( 'a', 'b', 'c', 'd', 'e' );
        $selection2 = array( '1', '2', '3', '4', '6' );

        $mt[] = new MTextField('teste', '', _M('Label', $module));
        $mt[] = new MSelection('selection1', '', _M('Label', $module), $selection1);
        $mt[] = new MSelection('selection2', '', _M('Label', $module), $selection2);

        $mt0[] = new MTextField('teste0', '', _M('Label', $module));
        $mt0[] = new MSelection('selection1a', '', _M('Label', $module), $selection1);
        $mt0[] = new MSelection('selection2a', '', _M('Label', $module), $selection2);

        $checks[] = new MCheckBox('chk1', 'c1', 'Check 1', true, _M('@1 Text', $module, 'Check1'));
        $checks[] = new MCheckBox('chk2', 'c2', 'Check 2', false, _M('@1 Text', $module, 'Check2'));
        $checks[] = new MCheckBox('chk3', 'c3', 'Check 3', true, _M('@1 Text', $module, 'Check3'));
        $checks[] = new MCheckBox('chk4', 'c4', 'Check 4', false, _M('@1 Text', $module, 'Check4'));
        $checks[] = new MCheckBox('chk5', 'c5', 'Check 5', true, _M('@1 Text', $module, 'Check5'));

        $options[] = new MOption('opt1', 'o1', _M('Option @1', $module, '1'), false);
        $options[] = new MOption('opt2', 'o2', _M('Option @1', $module, '2'), false);
        $options[] = new MOption('opt3', 'o3', _M('Option @1', $module, '3'), false);
        $options[] = new MOption('opt4', 'o4', _M('Option @1', $module, '4'), false);
        $options[] = new MOption('opt5', 'o5', _M('Option @1', $module, '5'), false);

        $fields['bp'] = new MBaseGroup('bp', 'A MBaseGroup', $mt);
        $fields['bp']->setAttribute('showLabel', true);

        $fields['idaluno'] = new MTextField('idaluno', '', 'Id');
        $fields['idaluno']->setAttribute('visible', false);

        $fields['aluno'] = new MLookupTextField('aluno', '', 'formMode=1', 20, 'formMode=1');
        $fields['aluno']->setAttribute('formMode', '1');
        $fields['aluno']->setAttribute('module', 'example');
        $fields['aluno']->setAttribute('item', 'aluno');
        $fields['aluno']->setAttribute('event', 'btnEdit:click');

        $fields['cal'] = new MCalendarField('cal', '', "formMode = 2");
        $fields['cal']->setAttribute('formMode', '2');

        $fields['idgroup'] = new MSelection('idgroup', '', 'formMode = 3', $selection1);
        $fields['idgroup']->setAttribute('formMode', '3');

        $fields[] = new MSeparator();
        $fields[] = new MTextLabel('text', _M('Field with', $module) . ' 70%', _M('Label with', $module). ' 25%');
        $fields[] = new MSeparator();
        $fields[] = new MCheckBoxGroup('grpChk', 'MCheckBoxGroup ' . _M('using', $module) . ' MCheckBox', $checks, '', 'vertical', 'css');
        $fields[] = new MCheckBoxGroup('grpOpt', 'MCheckBoxGroup ' . _M('using', $module) . ' MOption', $options, '', 'horizontal', 'css');
        $fields[] = new MRadioButtonGroup('grpOptRadio', 'MRadioButtonGroup ' . _M('using', $module) . ' MOption', $options, 'o4', '', 'horizontal', 'css');
        $fields[] = new MMultiTextField3('grupos', null, _M('Access', $module), $mt0, 200, true, 'horizontal');

        $this->setFields($fields);
        $this->setLabelWidth('25');
        $this->setFocus('aluno');

        $buttons[] = new MButton('btnPost', _M('Send', $module));
        $buttons[] = new MButton('btnNew', _M('New', $module));
        $buttons[] = new MButton('btnBgColor', _M('Set blue background color', $module));

        $this->setButtons($buttons);
    }

    public function btnPost_click()
    {
        $v = $this->grpChk->getValue();
        $values = array( );
        foreach ( $v as $name => $value )
        {
            $values[] = array( $name, $value );
        }
        $this->addField(new MTableRaw('grpChk Value', $values));

        $v = $this->grpOpt->getValue();
        $values = array( );
        foreach ( $v as $name => $value )
        {
            $values[] = array( $name, $value );
        }
        $this->addField(new MTableRaw('grpOpt Value', $values));

        $v = $this->grpOptRadio->getValue();
        $this->addField(new MLabel('grpOptRadio Value = ' . $v));
    }

    public function btnEdit_click($key = '')
    {
        
    }

    public function btnBgColor_click()
    {
        $this->setBackgroundColor('blue');
    }
}
