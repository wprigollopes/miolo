<?php

class frmButtons extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Button Controls', MIOLO::getCurrentModule()));

        // creates a link to open the source code
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $fields[] = new MLabel(_M('Submit', $module).' MButton:');
        $fields[] = new MButton('btnForm1', _M('Submit', $module), 'submit');
        $fields[] = new MSeparator();

        $fields[] = new MLabel(_M('Reset', $module).' MButton:');
        $fields[] = new MButton('btnForm2', _M('Reset', $module), 'reset');
        $fields[] = new MSeparator();

        $fields[] = new MLabel(_M('Print', $module).' MButton:');
        $fields[] = new MButton('btnForm3', _M('Print', $module), 'print');
        $fields[] = new MSeparator();

        // Returns to the last page via ajax
        $fields[] = new MLabel(_M('Return', $module).' MButton  :');
        $fields[] = new MButton('btnForm4', _M('Return', $module), 'return');
        $fields[] = new MSeparator();

        $fields[] = new MLabel('MButton ' . _M('using', $module) . ' Javascript:');
        $fields[] = new MButton('btnForm7', _M('Execute Javascript', $module), "alert('Javascript!');");
        $fields[] = new MSeparator();

        $fields[] = new MLabel('MButton ' . _M('using', $module).' URL:');
        $fields[] = new MButton('btnForm5', _M('Goto URL', $module), 'window:http://www.miolo.org.br');
        $fields[] = new MSeparator();

        $img = $MIOLO->getUI()->getImage( '', 'button_edit.png' );
        $btnForm6 = new MButton('btnForm6', _M('Image', $module), 'submit');
        $btnForm6->setImage($img);
        $btnForm6->setAction(MIOLO::getCurrentURL());
        $fields[] = new MLabel(_M('Image in', $module).' MButton:');
        $fields[] = $btnForm6;
        $fields[] = new MSeparator();

        $fields[] = new MLabel('MLinkButton:');
        $fields[] = new MLinkButton('lnkButton', _M('A label for', $module) . ' MLinkButton', MIOLO::getCurrentURL());
        $fields[] = new MSeparator();

        $fields[] = new MLabel('MImageButton:');
        // the first parameter indicates the module or miolo's html/images by default
        $fields[] = new MImageButton('imgButton', _M('A label for', $module) . ' MImageButton', MIOLO::getCurrentURL(), $img);
        $fields[] = new MSeparator();

        // setting css class to the button
        $inpButton = new MInputButton('inpButton', 'MInputButton', MIOLO::getCurrentURL());
        $inpButton->setClass('mButtonFind');
        $fields[] = new MLabel(_M('MInputButton (eg. find)', $module).':');
        $fields[] = $inpButton;
        $fields[] = new MSeparator();

        $dropDownButton = new MDropDownButton('dropDownButton', _M('Drop Down', $module));
        $dropDownButton->addItem(_M('New', $module), 'mImageNew', "alert('"._M('New', $module)."');");
        $dropDownButton->addItem(_M('Save', $module), 'mImageSave', "alert('"._M('Save', $module)."');");
        $dropDownButton->addSeparator();
        $dropDownButton->addItem(_M('Exit', $module), 'mImageExit', "alert('"._M('Exit', $module)."');");
        $fields[] = new MLabel('MDropDownButton:');
        $fields[] = $dropDownButton;
        $fields[] = new MSeparator();

        $fields[] = new MLabel('MButtonWindow:');
        $fields[] = new MButtonWindow('btnWindow', _M('Open Google at new window', $module), "http://www.google.com");
        $fields[] = new MSeparator();

        $fields[] = new MLabel('MBackButton:');
        $fields[] = new MBackButton();

        $this->addFields($fields);
        $this->setButtons(array());
    }
}
?>
