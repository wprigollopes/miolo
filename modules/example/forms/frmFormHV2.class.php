<?php

class frmFormHV2 extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Form example', MIOLO::getCurrentModule()));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $options = array( 'op1', 'op2', 'op3', 'op4', 'op5' );
        $selection = new MSelection('selDummy', '', _M('Options', $module), $options);

        $v0 = array(
            new MTextField('v0', '', 'V0'),
            new MTextField('v1', '', 'V1'),
            new MTextField('v2', 'v2', ''), // no label
            $selection
        );
        $v0Cont = new MVContainer('', $v0);
        $v0Cont->backgroundColor = '#CCF';
        $v0Cont->width = '100px';
        $v0Cont->showLabel = true;
        $h0 = array(
            new MTextField('h0', '', 'h0'),
            $selection,
            new MTextField('h2', '', 'h2'),
            new MTextField('h3', '', 'h3'),
            new MLookupTextField('h4', '', 'h4', 20),
        );
        $h0Cont = new MHContainer('', $h0);
        $h0Cont->backgroundColor = '#9FF';
        $h0Cont->width = '500px';
        $h0Cont->showLabel = true;
        $h1Cont = new MHContainer('', array( $v0Cont, $h0Cont ));
        $h2Cont = new MHContainer('', $h0);
        $h2Cont->backgroundColor = '#9CF';
        $h2Cont->width = '500px';
        $h2Cont->showLabel = false;
        $v1Cont = new MVContainer('', array( $h2Cont, $h2Cont, $h2Cont ));
        $v1Cont->backgroundColor = '#0E0';
        $v1Cont->width = '500px';
        $h3Cont = new MHContainer('', array( $v0Cont, $v1Cont, $v0Cont ));

        $fields[] = new MSeparator(_M('Fields with horizontal and vertical layouts, using containers', $module));
        $fields[] = new MSeparator(_M('Vertical container', $module));
        $fields[] = $v0Cont;
        $fields[] = new MSeparator(_M('Horizontal container', $module));
        $fields[] = $h0Cont;
        $fields[] = new MSeparator(_M('Horizontal container with different aligned containers', $module));
        $fields[] = $h1Cont;
        $fields[] = new MSeparator(_M('Horizontal container with 3 vertical containers. The one in the middle has 3 horizontal containers', $module));
        $fields[] = $h3Cont;

        $this->setFields($fields);

        $this->setFieldAttr('h4', 'module', 'admin');
        $this->setFieldAttr('h4', 'item', 'transaction');
        $this->setFieldAttr('h4', 'event', 'btnEdit:click');

        $buttons = array(
            new MButton('btnPost', _M('Send')),
            new MButton('btnNew', _M('New', $module)),
            new MButton('btnDelete', _M('Remove', $module)),
            new MButton('btnList', _M('Relation', $module)),
            new MButton('btnReport', _M('Reports', $module)),
        );
        $this->setButtons($buttons);
    }
}
?>
