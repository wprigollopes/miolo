<?php

class frmDlgPrompt extends MForm
{
    public function __construct()
    {
        parent::__construct('Dialogs Prompt Controls Examples');
        $this->eventHandler();
    }

    public function createFields()
    {
        $fields = array(
            new MTextLabel('lblText', '', 'Texto'),
            new MButton('btnError', 'Error'),
            new MButton('btnInfo', 'Information'),
            new MButton('btnConf', 'Confirmation'),
            new MButton('btnQuestion', 'Question')
        );
        $this->setFields($fields);
        $this->defaultButton = false;
    }

    public function btnError_click()
    {
        global $self;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $msg = 'message error';
        $goto = $MIOLO->getActionURL($module, $self);
        $caption = _M('Error', $module);
        // event: method_name;parameter
        $event = "promptHandler;error";
        $dlgError = new MDialogError($msg, $goto, $caption, $event);
        $MIOLO->dialogPrompt($dlgError);
    }

    public function btnInfo_click()
    {
        global $self;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $msg = _M('Information', $module);
        $goto = $MIOLO->getActionURL($module, $self);
        $event = "promptHandler;information";
        $dlgInfo = new MDialogInformation($msg, $goto, $event);
        $MIOLO->dialogPrompt($dlgInfo);
    }

    public function btnConf_click()
    {
        global $self;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $msg = _M('Confirmation', $module);
        $gotoOk = $MIOLO->getActionURL($module, $self);
        $gotoCancel = $MIOLO->getActionURL($module, $self);
        $eventOk = "promptHandler;Confirmation_Ok";
        $eventCancel = "promptHandler;Confirmation_Cancel";
        $dlgConf = new MDialogConfirmation($msg, $gotoOk, $gotoCancel, $eventOk, $eventCancel);
        $MIOLO->dialogPrompt($dlgConf);
    }

    public function btnQuestion_click()
    {
        global $self;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $msg = _M('Question', $module);
        $gotoYes = $MIOLO->getActionURL($module, $self);
        $gotoNo = $MIOLO->getActionURL($module, $self);
        $eventYes = "promptHandler;Question_Yes";
        $eventNo = "promptHandler;Question_No";
        $dlgQuestion = new MDialogQuestion($msg, $gotoYes, $gotoNo, $eventYes, $eventNo);
        $MIOLO->dialogPrompt($dlgQuestion);
    }

    public function promptHandler($sender=NULL, $text='')
    {
        $this->setFieldValue('lblText', $text);
    }
}
?>
