<?php

class frmMessageDemo extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Message Demo', MIOLO::getCurrentModule()));

        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
        // creates a link to view the source
        $this->addField(new ViewSource(__FILE__));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $fields[] = MMessage::getMessageContainer();
        $fields[] = new MTextField('tf1', '', _M('Input field', $module), 20);

        $buttons = array(
            new MButton('btnSuccess', _M('Success', $module), ':successMessage'),
            new MButton('btnInformation', _M('Information', $module), ':informationMessage'),
            new MButton('btnWarning', _M('Warning', $module), ':warningMessage'),
            new MButton('btnError', _M('Error', $module), ':errorMessage')
        );

        $static = MMessage::getStaticMessage(NULL, _M('Static message', $module), MMessage::TYPE_WARNING);
        $static->setWidth('30%');
        $fields[] = $static;

        $fields[] = new MHContainer('container', $buttons);

        $this->setFields($fields);
        $this->setButtons(new MBackButton());
    }

    public function successMessage()
    {
        $msg = array( 
            _M('You clicked the Success button.', MIOLO::getCurrentModule()),
            _M('Field value', MIOLO::getCurrentModule()) .': '. MForm::getFormvalue('tf1') );
        new MMessage($msg, MMessage::TYPE_SUCCESS, true, MMessage::MSG_CONTAINER_ID, false);
    }

    public function informationMessage()
    {
        new MMessageInformation(_M('You clicked the Information button', MIOLO::getCurrentModule()));
        return true;
    }

    public function warningMessage()
    {
        $msg = array(
            _M('You clicked the Warning button.', MIOLO::getCurrentModule()),
            _M('Field value', MIOLO::getCurrentModule()) .': '. MForm::getFormvalue('tf1')
        );
        new MMessageWarning($msg);
    }

    public function errorMessage()
    {
        new MMessage(_M('You clicked the Error button!', MIOLO::getCurrentModule()), MMessage::TYPE_ERROR);
    }
}
?>
