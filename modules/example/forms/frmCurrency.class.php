<?php
$MIOLO->import('modules::example::controls::viewsource');

// the class name is the same as the file name
class FrmCurrency extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('@1 Sample', MIOLO::getCurrentModule(), 'MCurrencyField'));

        // if the form was submitted
        if ( $this->page->isPostBack() )
        {
            // calls the events (in this form: btnPost_click)
            $this->eventHandler();
        }

        // view source link
        $this->addField(new ViewSource(__FILE__));
    }

    // create the form fields
    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        // define some currency fields
        // note: _M() is the Miolo's i18n method
        $fields = array( new MCurrencyField('edtMoney1', '', _M('First value', $module)),
            new MCurrencyField('edtMoney2', '', _M('Second value', $module)),
            new MTextLabel('edtMoney3', '', _M('Sum', $module)),
            new MSeparator()
        );

        // set the fields on the form
        $this->setFields($fields);

        // define the submit button
        $btn1 = new MButton('btnPost', _M('Send'));
        // set a javascript hint
        $btn1->setJsHint(_M('Press to sum the values', $module));
        $buttons = array( $btn1 );

        // set the button to the form
        $this->setButtons($buttons);
    }

    // this method is automatically called when the form is submitted
    // the submit button
    public function btnPost_click()
    {
        // get the values filled by the user
        $money1 = $this->getFieldValue('edtMoney1');
        $money2 = $this->getFieldValue('edtMoney2');

        // currency formatter
        $cf = new MCurrencyFormatter();
        $money3 = $cf->formatWithSymbol($cf->toDecimal($money1) + $cf->toDecimal($money2));

        // add two fields with the submitted values
        $this->addField(new MTextLabel('lblMoney1', $money1, _M('First value', $module)));
        $this->addField(new MTextLabel('lblMoney2', $money2, _M('Second value', $module)));

        // edtMoney3 was created in createFields method, here we
        // update the values
        $this->setFieldValue('edtMoney3', $money3);
    }
}
?>
