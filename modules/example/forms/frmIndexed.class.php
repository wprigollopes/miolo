<?php

class frmIndexed extends MForm
{

    public function __construct()
    {
        parent::__construct(_M('Indexed Controls', MIOLO::getCurrentModule()));

        // verify if the form was submitted
        if ( $this->page->isPostBack() )
        {
            // call the form method: btnPost_click()
            $this->eventHandler();
        }

        // add fields to the form
        // separator
        $this->addField(new MSeparator);
        // creates a link to open the source code
        $this->addField(new ViewSource(__FILE__));
    }

    /**
     * this method is automatically called by MIOLO
     * and is intended to create the form fields
     */
    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $aLabel = new MLabel(_M('This is an example of MIndexedControl. This control receives controls array which are accessed by a unique index (0,1,...,n)', $module), 'red', true);

        $aOptionsAssoc = array(
            'valueA' => 'optA',
            'valueB' => 'optB',
            'valueC' => 'optC',
            'valueD' => 'optD',
            'valueE' => 'optE',
            'valueF' => 'optF'
        );
        $aSelection = new MSelection('aSelection', 'valueB', _M('Label', $module), $aOptionsAssoc);

        // array of fields
        $control[] = new MTextField('edtMoney1', '', _M('Value 1'));
        $control[] = new MTextField('edtMoney2', '', _M('Value 2'));
        $control[] = new MTextField('edtMoney3', '', _M('Value 3'));
        $control[] = $aSelection;

        // creates an indexed control of the fields
        $ixControl = new MIndexedControl('ixcontrol', _M('Label', $module), $control);

        // array of fields
        $fields = array(
            $aLabel,
            $ixControl,
        );

        // array of buttons
        $buttons = array( new MButton('btnPost', _M('Send')) );

        // add the fields and buttons 
        $this->setFields($fields);
        $this->setButtons($buttons);
    }

    /**
     * this method handles the button click (submit) and
     * is called by the constructor (eventHandler)
     */
    protected function btnPost_click()
    {
        // gets the indexed control
        $indexed = $this->getFieldValue('ixcontrol');

        // add fields to the form, based on the field index
        $this->addField(new MLabel(_M('Value of control with index @1', $module, '0: ') . $indexed[0]));
        $this->addField(new MLabel(_M('Value of control with index @1', $module, '1: ') . $indexed[1]));
        $this->addField(new MLabel(_M('Value of control with index @1', $module, '2: ') . $indexed[2]));
        $this->addField(new MLabel(_M('Value of control with index @1', $module, '3: ') . $indexed[3]));
    }

}
?>
