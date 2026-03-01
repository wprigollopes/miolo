<?php

class frmInput extends controlForm
{
    public function __construct()
    {
        parent::__construct(_M('Input Controls', MIOLO::getCurrentModule()), __FILE__);
    }

    public function createFields()
    {
        parent::createFields();
        $module = MIOLO::getCurrentModule();

        // text input with a javascript hint
        $fields[] = new MLabel(_M('@1 with javascript hint', $module, 'MTextField').':');
        $fields['city'] = new MTextField('city', '', _M('City', $module), 20);
        $fields['city']->setJsHint(_M('Type here the city you live'));
        $fields[] = new MSeparator();

        // read-only MTextField
        $fields[] = new MLabel(_M('Read-only', $module).' MTextField:');
        $fields['country'] = new MTextField('country', _M('Brazil', $module), _M('Country', $module), 20);
        $fields['country']->setReadOnly(true);
        $fields[] = new MSeparator();
        
        // password input
        $fields[] = new MLabel('MPasswordField:');
        $fields[] = new MPasswordField('password', 'value', _M('Password', $module), 15);
        $fields[] = new MSeparator();

        // hidden input
        $fields[] = new MLabel("MHiddenField: " . _M("You can't see it", $module));
        $fields[] = new MHiddenField('hiddenField', 'value');
        $fields[] = new MSeparator();

        // text input
        $fields[] = new MLabel('MMultiLineField:');
        $fields[] = new MMultilineField('multiField', '', _M('Description', $module), 25, 5, 20);
        $fields[] = new MSeparator();

        // input with a calendar selector
        $fields[] = new MLabel(_M('@1 with javascript hint', $module, 'MCalendarField').':');
        $fields['date'] = new MCalendarField('date', '', _M('Date', $module));
        $fields['date']->setJsHint(_M('Click the image to open the calendar', $module));
        $fields[] = new MSeparator();

        // input for money values
        $fields[] = new MLabel('MCurrencyField:');
        $fields[] = new MCurrencyField('price', '10', _M('Price', $module));
        $fields[] = new MSeparator();

        $fields[] = new MLabel('MIntegerField:');
        $fields[] = new MIntegerField('number', '', _M('Number', $module));
        $fields[] = new MSeparator();

        $fields[] = new MLabel('MFloatField:');
        $fields[] = new MFloatField('float', '', _M('Float', $module));

        // FIXME: check what's going wrong with the dijit editor component
        /*$fields[] = new MSeparator();

        $htmlEditor = new MMultiLineField('htmlMultiLine');
        $htmlEditor->setHtmlEditor();
        $fields[] = new MLabel( 'MMultiLineField with html editor:');
        $fields[] = $htmlEditor;*/

        
        $this->page->onload("dojo.byId('city').value ='<script></script>';");
        //$this->page->onload("dojo.byId('city').value ='" . ("\<script\>\</script\>") . "';");
        $this->addFields($fields);
    }
}
