<?php

class frmBlockControls extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Block Controls', MIOLO::getCurrentModule()));

        // creates a link to open the source code
        $this->addField( new ViewSource( __FILE__ ) );
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        // span
        $span = new MSpan('span', _M('A MSpan with style', $module));
        $span->addStyle('color', 'blue');

        // div
        $div = new MDiv('div1', _M('A MDiv with right float and background color', $module));
        $div->addStyle('float', 'right');
        $div->addStyle('background-color', '#ACC');

        // div to be used as internal content in div3
        $div2 = new MDiv('div2', _M('A nested MDiv', $module));
        $div2->addStyle('background-color', '#999900');
        $div2->addStyle('width', '100px');
        $div2->addStyle('color', '#FFF');

        // div with $div2 as content
        $div3 = new MDiv('div3', $div2);
        $div3->addStyle('background-color', '#DDD');
        $div3->addStyle('width', '300px');
        $div3->addStyle('padding', '5px');
        $div3->addStyle('float', 'left');

        $controls[] = new MLabel(_M('A MDiv with a MLabel and a MCheckBox component', $module));
        $controls[] = new MCheckBox('chkBox', 'value', _M('Check Box', $module), true, _M('Text', $module));
        $div4 = new MDiv('div4', $controls);

        $fields = array( $span, new MSeparator(), $div, new MSeparator(), $div3, new MSeparator(), $div4 );

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }
}
