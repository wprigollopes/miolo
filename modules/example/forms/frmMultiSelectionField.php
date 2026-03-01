<?php

class FrmMultiSelectionField extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('@1 Example', MIOLO::getCurrentModule(), 'MMultiSelectionField'));

        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }

        // creates a link to view the source
        $this->addField( new ViewSource( __FILE__ ) );
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $options1 = array(
            'op1' => _M('Option @1', $module, '1'),
            'op2' => _M('Option @1', $module, '2'),
            'op3' => _M('Option @1', $module, '3'),
            'op4' => _M('Option @1', $module, '4'),
            'op5' => _M('Option @1', $module, '5')
        );
        $options2 = array( 1 => 'op1', 'op2', 'op3', 'op4', 'op5' );

        $controls[] = new MSelection('sel1', '', 'Selection 1', $options1);
        $controls[] = new MSelection('sel2', '', 'Selection 2', $options2);

        $fields[] = new MMultiSelectionField('msf', null, 'MMultiSelectionField', $controls, 200, true, 'Info');
        $fields['text'] = new MText('text');
        $fields['text']->setAttribute('color', 'blue');

        $this->setFields($fields);

        $buttons[] = new MBackButton();
        $buttons[] = new MButton('btnPost', _M('Submit', $module));
        $this->setButtons($buttons);
    }

    public function btnPost_click()
    {
        $module = MIOLO::getCurrentModule();
        $value = $this->msf->getCodeValue();
        $this->addField(new MTableRaw(_M('Returned value', $module), $value));
    }
}
