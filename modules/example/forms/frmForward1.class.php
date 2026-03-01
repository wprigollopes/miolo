<?php

class frmForward1 extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Forward', MIOLO::getCurrentModule()));
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $variables = $this->manager->request->getParameters();
        foreach ( $variables as $key => $var )
        {
            $v[] = "\$$key = $var;";
        }
        $fields = array(
            new MLabel(_M('The "forward" method allow you to pass the execution flow to another handler, without browser interaction. The process happens only at server-side.', $module)),
            new MLabel(_M('The request variables', $module) . ': '),
            new MUnorderedList('vars', $v),
            new MTextField('avalue', '', _M('A value to forward', $module))
        );
        $this->setFields($fields);
        $buttons = array(
            new MButton('btnTo2c', _M('Click to use "forward" with "clearContent"', $module)),
            new MButton('btnTo2a', _M('Click to use "forward" with "appendContent"', $module))
        );
        $this->setButtons($buttons);
    }

    public function btnTo2c_click()
    {
        $go = $this->manager->getActionURL('example', 'forward2', 'clear');
        $this->page->forward($go);
    }

    public function btnTo2a_click()
    {
        $go = $this->manager->getActionURL('example', 'forward2', 'append');
        $this->page->forward($go);
    }
}
