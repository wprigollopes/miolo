<?php

class frmMultiText2 extends MForm
{
    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $page = $MIOLO->getPage();

        parent::__construct( _M('@1 Example', MIOLO::getCurrentModule(), 'MultiTextField2') );

        // define array for combobox
        $options_arr1 = array(
            'op1' => _M('Option @1', $module, '1'),
            'op2' => _M('Option @1', $module, '2'),
            'op3' => _M('Option @1', $module, '3'),
            'op4' => _M('Option @1', $module, '4'),
            'op5' => _M('Option @1', $module, '5')
        );
        // options for combobox
        $options_arr2 = array(
            _M('Dog', $module),
            _M('Cat', $module),
            _M('Bird', $module),
            _M('Car', $module),
            _M('Plane', $module)
        );

        // array for mt fields
        $f = array( array( _M('ID', $module), _M('Label', $module), _M('Name', $module) ) );

        // define array to be used as fields of multitextfieds
        $f1 = array(
            array( 'ida', _M('Label @1', $module, 'A'), _M('Name @1', $module, 'A'),$options_arr1 ),
            array( 'idb', _M('Label @1', $module, 'B'), _M('Name @1', $module, 'B') ),
            array( 'idc', _M('Label @1', $module, 'C'), _M('Name @1', $module, 'C'), $options_arr2 ),
            array( 'idd', _M('Label @1', $module, 'D'), _M('Name @1', $module, 'D') ),
            array( 'ide', _M('Label @1', $module, 'E'), _M('Name @1', $module, 'E'), $options_arr1 )
        );

        // define array of fields
        $fields = array(
            new MLabel( _M('Example @1: Horizontal Layout', $module, '1') ),
            new MMultiTextField2( 'mt', null, 'Example 1', $f, 200, true, 'horizontal' ),
            new MLabel( _M('Example @1: Horizontal Layout', $module, '2') ),
            new MMultiTextField2( 'mt1', null, '', $f1, 200, true, 'horizontal' ),
            new MLabel( _M('Example @1: Vertical Layout', $module, '1') ),
            new MMultiTextField2( 'mt2', null, '', $f1, 300, true, 'vertical' ),
            new MLabel( _M('Example @1: Vertical Layout', $module, '2') ),
            new MMultiTextField2( 'mt3', null, '', $f1, 300, true, 'vertical2' )
        );

        // set the fields into the form
        $this->setFields( $fields );
        // set some fields attributes
        $this->setFieldAttr( 'mt', 'info', _M('Object info', $module) );
        $this->setFieldAttr( 'mt2', 'info', _M('Object info', $module) );
        $this->setFieldAttr( 'text', 'color', 'blue' );
        // define array of buttons
        $buttons = array( new MButton( 'btnPost', _M('Send') ) );
        // set the buttons on the form
        $this->setButtons( $buttons );

        // verify if the page was send
        if ( $page->isPostBack() )
        {
            // call the eventHandler
            $this->eventHandler();
        }
    }

    public function btnPost_click()
    {
        $module = MIOLO::getCurrentModule();
        // create a tableraw field to display the value of the $mt object
        $value = $this->mt->getCodeValue();
        $this->addField( new MTableRaw( _M('@1 field value ', $module, 'mt'), $value) );

        // create a tableraw field to display the value of the $mt1 object
        $value = $this->mt1->getCodeValue();
        $this->addField( new MTableRaw( _M('@1 field value ', $module, 'mt1'), $value) );

        // create a tableraw field to display the value of the $mt2 object
        $value = $this->mt2->getCodeValue();
        $this->addField( new MTableRaw( _M('@1 field value ', $module, 'mt2'), $value) );

        // create a tableraw field to display the value of the $mt3 object
        $value = $this->mt3->getCodeValue();
        $this->addField( new MTableRaw( _M('@1 field value ', $module, 'mt3'), $value) );
    }
}
