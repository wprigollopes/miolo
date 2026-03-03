<?php

class frmDialog extends MForm
{
    public function __construct()
    {   
        parent::__construct(_M('Dialog', MIOLO::getCurrentModule()));
        $this->eventHandler();
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $fields[] = MMessage::getMessageContainer();
        $fields[] = MDialog::getDefaultContainer();

        $buttons[] = new MButton('btnShow', _M('Dialog example', $module), ':showDialog');
        $fields[] = new MHContainer('buttons', $buttons);

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }
    
    public function sendData($args)
    {
        new MMessageInformation('<pre>' . print_r($this->getAjaxData(), 1) . '</pre>');
        MDialog::close('myDialog');
    }

    public function showDialog()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $fields[] = new MLookupContainer('test', NULL, _M('Test2'), 'example', 'aluno', false, 'hint ');
        $fields[] = new MTextField('textField1', NULL, _M('Text Field', $module).'1');
        $fields[] = new MTextField('textField2', NULL, _M('Text Field', $module).'2');
        $fields[] = new MTextField('textField3', NULL, _M('Text Field', $module).'3');
        
        $fields[] = new MButton('sendDataButton', _M('Send', $module), ':sendData');

        $dialog = new MDialog('myDialog', _M('Dialog example', $module), $fields);
        $dialog->show();
        
    }
}

?>