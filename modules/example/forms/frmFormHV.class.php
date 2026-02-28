<?php

class frmFormHV extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Form example', MIOLO::getCurrentModule()));
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields[] = new MSeparator(_M('Fields with horizontal and vertical layouts, using arrays', $module));
        $fields[] = new MSeparator(_M('Fields with vertical layout', $module));
        $fields[] = new MTextField('v0', '', 'V0');
        $fields[] = new MTextField('v1', '', 'V1');
        $fields[] = new MTextField('v2', 'v2', ''); // no label
        $fields[] = new MTextField('v3', '', 'V3');
        $fields[] = new MSeparator(_M('Fields with horizontal layout using arrays', $module));
        $fields[] = array(
            new MCheckBox('h2Visible', 'visible', '&nbsp;', null, 'H2 '. _M('Visible', $module)),
            new MTextField('h0', '', 'h0'),
            new MTextField('h1', '', 'h1'),
            new MTextField('h2', '', 'h2'),
            new MTextField('h3', '', 'h3'),
        );
        $fields[] = new MSeparator(_M('Fields with vertical layout', $module));
        $fields[] = new MTextField('v4', '', 'V4');
        $fields[] = new MTextField('v5', '', 'V5');
        $fields[] = new MTextField('v6', 'v6', ''); // no label
        $fields[] = new MTextField('v7', 'formMode:3', 'V7:', 20);
        $fields[] = new MSeparator(_M('Fields with horizontal layout using arrays', $module));
        $fields[] = array(
            new MTextField('h4', '', 'h4'),
            new MTextField('h5', '', 'h5'),
            new MTextField('h6', '', 'h6'),
            new MTextField('h7', '', 'h7'),
        );
        $fields[] = new MSeparator(_M('Fields with vertical layout', $module));
        $fields[] = new MTextField('v8', 'formMode:2', 'V8', 20);
        $fields[] = new MTextField('v9', 'formMode:2', 'V9', 20);
        $fields[] = new MTextField('v10', 'v10', ''); // no label
        $fields[] = new MTextField('v11', '', 'V11');

        $this->setFields($fields);
        $this->setFieldAttr('h2Visible', 'autoPostBack', true);
        $this->setFieldAttr('v7', 'formMode', 3);
        $this->setFieldAttr('v8', 'formMode', 2);
        $this->setFieldAttr('v9', 'formMode', 2);

        $buttons = array(
            new MButton('btnPost', _M('Send')),
            new MButton('btnNew', _M('New', $module)),
            new MButton('btnDelete', _M('Remove', $module)),
            new MButton('btnList', _M('Relation', $module)),
            new MButton('btnReport', _M('Reports', $module)),
        );
        $this->setButtons($buttons);
    }

    public function h2Visible_click($sender)
    {
        $this->h2->setVisibility($sender->checked);
    }
}
?>
