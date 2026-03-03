<?php

class frmJavascript extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Using Javascript', MIOLO::getCurrentModule()));
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

        for ( $i = 0; $i < 10; $i++ )
        {
            $c = new MTextField("edtMoney$i", '0', _M('Value', $module));
            $c->addEvent('blur', 'refreshSoma();');
            $control[] = $c;
        }

        $fields['txt'] = new MText("txt", _M('The sum is updated for each typed value', $module));
        $fields['txt']->setBold();

        $fields[] = new MSeparator();
        $fields[] = new MIndexedControl('ixcontrol', _M('Label', $module), $control);
        $fields[] = new MTextField("edtSoma", '0', _M('Sum', $module));

        $this->setFields($fields);

        $buttons[] = new MButton('btnPost', _M('Send'));
        $this->setButtons($buttons);

        $message = _M('Invalid number!', $module);
        $code =
                <<< HERE
function refreshSoma()
{
    var somaObject = miolo.getElementById("edtSoma");
    var sum = 0;
    for ( var i = 0; i < 10; i++ )
    {
        auxObject = miolo.getElementById("ixcontrol_"+i);

        if (isNaN(Number(auxObject.value)))
        {
            alert('$message');
            auxObject.focus();
        }
        else
        {
            sum += Number(auxObject.value);
        }
    }
    somaObject.value = sum;
}
HERE;

        $this->page->addJsCode($code);
    }
}
?>
