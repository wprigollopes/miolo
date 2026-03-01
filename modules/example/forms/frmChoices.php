<?php

class frmChoices extends controlForm
{
    public function __construct()
    {
        parent::__construct(_M('Choice Controls', MIOLO::getCurrentModule()), __FILE__);
    }

    public function createFields()
    {
        parent::createFields();
        $module = MIOLO::getCurrentModule();
        
        // creates check boxes
        $chkBox = new MCheckBox('chkBox', 'value', _M('Label', $module), true, _M('A text for', $module) . ' MCheckBox');
        $chkBox1 = new MCheckBox('chkBox1', 'value1', _M('Label', $module), false, _M('Another', $module) . ' MCheckBox');
        $chkBox1->setChecked(true);

        // creates radio buttons
        $radio = new MRadioButton('radio', 'value', _M('Label', $module), true, _M('A text for', $module) . ' MRadioButton');
        $radio1 = new MRadioButton('radio', 'value1', _M('Label', $module), false, _M('Another', $module) . ' MRadioButton ' . _M('with same id', $module));

        $fields = array(
            new MLabel('MCheckBoxes: '), $chkBox, $chkBox1,
            new MSeparator(),
            new MLabel('MRadioButtons: '), $radio, $radio1,
        );

        $this->addFields($fields);
    }

}
