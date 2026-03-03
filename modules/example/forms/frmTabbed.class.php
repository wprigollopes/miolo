<?php

class frmTabbed extends MTabbedForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('@1 example', $module, 'TabbedForm'));

        $this->setPainterMethod('javascript');
//        $this->setPainterMethod('html'); 
        $form0 = new FrmTabbedZero($this);
        $form1 = new FrmTabbedOne($this);
        $form2 = new FrmTabbedTwo();
        $form3 = new FrmTabbedThree();
        $form4 = new FrmTabbedFour();

        $this->addPage($form0);
        $this->addPage($form1);
        $this->addPage($form2);
        $this->addPage($form3);
        $this->addPage($form4);

        //$this->header = "(header) in: [pagecount: $this->pagecount] [currentpage: $this->currentpage] [Page 2 disable]";
        $this->getPage(2)->enabled = false;
        if ( $this->page->isPostBack() )
        {
            if ( $this->getCurrentPage() == 4 )
            {
                $fields = $this->getFieldList();
                $s = '';
                foreach ( $fields as $f )
                {
                    $s .= $f->name . ' = [' . Form::getFormValue($f->name) . ']<br>';
                }
                $form4->setFieldValue('tabfields', $s);
            }

            $this->eventHandler();
        }
        else
        {
            $this->setCurrentPage(3);
        }
        $this->footer = "(footer) out: [pagecount: $this->pagecount] [currentpage: $this->currentpage]";
    }

}

class FrmTabbedZero extends MForm
{
    public $tabbedForm;

    public function __construct($tabbedForm)
    {
        $this->tabbedForm = $tabbedForm;
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Tab zero', $module));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields1 = array(
            new MTextField('field0', '', _M('Field @1', $module, '0'), 20),
            new MText('alert0', _M('Button post clicked: changed header', $module))
        );
        $this->setFields($fields1);
        $this->setFieldAttr('alert0', 'visible', false);
        $buttons = array(
            new MButton('btnPostZero', _M('Page zero', $module)),
            new MButton('btnGoOne', _M('Go to page one', $module) . ' >>', $this->tabbedForm->goForward())
        );
        $this->setButtons($buttons);
    }

    public function btnPost_click()
    {
        $this->setFieldAttr('alert0', 'visible', true);
    }
}

class FrmTabbedOne extends MForm
{
    public $tabbedForm;

    public function __construct($tabbedForm)
    {
        $module = MIOLO::getCurrentModule();
        $this->tabbedForm = $tabbedForm;
        parent::__construct(_M('Tab one', $module));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $options = array( 'Common', _M('Example', $module), 'SGA' );
        $selection = new MSelection('selModule', '', _M('Module', $module), $options);
        $selection->setAutoPostBack(true);

        $fields1 = array(
            $selection,
            new MTextField('field1', '', _M('Field @1', $module, '1'), 20),
        );
        $this->setFields($fields1);
        $buttons = Array(
            new MButton('btnPostOne', _M('Page one', $module)),
            new MButton('btnGoBack', '<< ' . _M('Go back', $module), $this->tabbedForm->goBack())
        );
        $this->setButtons($buttons);
    }

    public function btnPost_click()
    {
        $tab = $this->tabbedform;
        $tab->header .= '[OnClick em btnPost da pagina 1]';
    }
}

class FrmTabbedTwo extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Tab two', $module));
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $options = array( _M('Common', $module), _M('Example', $module), 'SGA' );
        $selection = new MSelection('selModule', '', _M('Module', $module), $options);
        $selection->setAutoPostBack(true);

        $fields2 = array(
            new MTextField('field2', '', _M('Field @1', $module, '2'), 20),
            new MTextField('field2a', '', _M('Field @1', $module, '2a'), 20)
        );
        $this->setFields($fields2);
        $buttons = Array(
            new MButton('btnPostTwo', _M('Page two', $module)),
        );
        $this->setButtons($buttons);
        $validators = array(
            new MRequiredValidator('field2')
        );
        $this->setValidators($validators);
    }

    public function btnPost_click()
    {
        $this->tabbedform->header .= '[OnClick em btnPost da pagina 2]';
    }
}

class FrmTabbedThree extends MForm
{
    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Tab three', $module));
        $chk = $this->getFieldAttr('chkBox', 'checked');
        $this->setFieldAttr('field3a', 'visible', $chk);
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $fields3 = array(
            new MTextField('field3', '', _M('Field @1', $module, '3'), 20),
            new MCheckBox('chkBox', 'value', _M('Choice', $module), true, _M('Field @1 visible', $module, '3a')),
            new MTextField('field3a', '', _M('Field @1', $module, '3a'), 20),
            new MHContainer('', array(
                new MText('tx', _M('Team formed by', $module) . ':'),
                new MTextField('eqp', '', '', 5),
                new MText('tx2', _M('programmers', $module)) )
            )
        );
        $this->setFields($fields3);
        $buttons = Array(
            new MButton('btnPostThree', _M('Page three', $module)),
        );
        $this->setButtons($buttons);
    }
}

class FrmTabbedFour extends MForm
{
    public function __construct()
    {
        parent::__construct(_M('Tab four', $module));
    }

    public function createFields()
    {
        $fields4 = array(
            new MTextLabel('tabfields', '', _M('Fields', $module))
        );

        $this->setFields($fields4);
    }
}
?>
