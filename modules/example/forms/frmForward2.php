<?php

class frmForward2 extends MForm
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
            new MLabel(_M('The request variables', $module) . ': '),
            new MUnorderedList('vars', $v),
            new MLabel(_M('A value received from forward', $module) . ': ' . $this->getFormValue('avalue'))
        );
        $this->setFields($fields);
        $this->defaultButton = false;
    }
}
