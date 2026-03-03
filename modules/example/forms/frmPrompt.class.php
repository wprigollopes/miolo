<?php

class frmPrompt extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('@1 Example', $module, 'MPrompt'));
        $this->eventHandler();
        $this->defaultButton = false;
        // creates a link to view the source
        $this->addField( new ViewSource( __FILE__ ) );
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $fields[] = new MTextLabel('lblText', '', _M('Text', $module));
        $fields[] = new MButton('btnError', _M('Error', $module));
        $fields[] = new MButton('btnInfo', _M('Information', $module));
        $fields[] = new MButton('btnConf', _M('Confirmation', $module));
        $fields[] = new MButton('btnQuestion', _M('Question', $module));
        $fields[] = new MButton('btnAlert', _M('Alert', $module));

        $this->setFields($fields);
    }

    public function btnError_click()
    {
        global $self;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $msg = 'message error';
        $goto = $MIOLO->getActionURL($module, $self);
        $caption = 'error caption';
        // event: method_name;parameter
        $event = "promptHandler;error";
        $MIOLO->error($msg, $goto, $caption, $event);
    }

    public function btnInfo_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $msg = _M('Information message', $module);
        $goto = MIOLO::getCurrentURL();
        $event = "promptHandler;information";

        $MIOLO->information($msg, $goto, $event);
    }

    public function btnConf_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $msg = _M('Confirmation message', $module);
        $gotoOk = $gotoCancel = MIOLO::getCurrentURL();
        $eventOk = "promptHandler;Confirmation_Ok";
        $eventCancel = "promptHandler;Confirmation_Cancel";

        $MIOLO->confirmation($msg, $gotoOk, $gotoCancel, $eventOk, $eventCancel);
    }

    public function btnQuestion_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $msg = _M('Question message', $module);
        $gotoYes = $gotoNo = MIOLO::getCurrentURL();
        $eventYes = "promptHandler;Question_Yes";
        $eventNo = "promptHandler;Question_No";

        $MIOLO->question($msg, $gotoYes, $gotoNo, $eventYes, $eventNo);
    }

    public function btnAlert_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $msg = _M('Alert message', $module);
        $MIOLO->alert($msg);
    }

    public function promptHandler($text='')
    {
        $this->setFieldValue('lblText', $text);
    }
}
?>
