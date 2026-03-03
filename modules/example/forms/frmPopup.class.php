<?php

class frmPopup extends MForm
{
    public function __construct()
    {   
        parent::__construct(_M('Popup'));
        $this->eventHandler();
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $fields[] = MMessage::getMessageContainer();
        $fields[] = MPopup::getPopupContainer();
        $fields[] = new MDiv('anotherPopupContainer', NULL);

        $fields[] = new MLabel(_M('A prompt popup example must be opened on loading the page', $module), '', true);
        $fields[] = new MPopupPrompt(_M('What do you want?', $module), _M('Prompt Popup', $module), ':promptConfirmation');

        $buttons[] = new MButton('btnShow', _M('Default', $module), ':show');
        $buttons[] = new MButton('btnConfirm', _M('Confirm', $module), ':confirm');
        $buttons[] = new MButton('btnAlert', _M('Alert', $module), ':alert');
        $fields[] = new MHContainer('buttons', $buttons);

        $this->addFields($fields);
        $this->setButtons(new MBackButton());
    }

    public function show()
    {
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        $fields[] = new MTextField('textField1', NULL, _M('Text Field', $module).'1');
        $fields[] = new MTextField('textField2', NULL, _M('Text Field', $module).'2');
        $fields[] = new MTextField('textField3', NULL, _M('Text Field', $module).'3');

        MPopup::show('dynamicPopup', $fields, _M('A popup with some fields', $module));
    }

    public function confirm()
    {
        $module = MIOLO::getCurrentModule();
        MPopup::setResponseContainer('anotherPopupContainer');
        MPopup::confirm(_M('Do you want to continue?', $module), _M('Confirm Popup', $module));
    }

    public function alert()
    {
        $module = MIOLO::getCurrentModule();
        MPopup::alert(_M('This will close when clicking the OK button', $module), _M('Alert Popup', $module));
    }

    public function promptConfirmation($args)
    {
        $module = MIOLO::getCurrentModule();
        MPopup::remove();
        new MMessage(_M('You want', $module) . ': ' . $args->{MPopup::PROMPT_FIELD_ID});
    }

    public function confirmAction()
    {
        $module = MIOLO::getCurrentModule();
        MPopup::remove();
        new MMessage(_M('You have confirmed', $module));
    }
}
?>
