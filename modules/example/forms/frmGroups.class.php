<?php

class frmGroups extends controlForm
{
    public function __construct()
    {
        parent::__construct(_M('Group Controls', MIOLO::getCurrentModule()), __FILE__);
    }

    public function createFields()
    {
        parent::createFields();
        $MIOLO = MIOLO::getInstance();
        $module = $MIOLO->getCurrentModule();

        // creates some options
        $opt1 = new MOption('opt1', 'value1', _M('Label', $module). ' 1', false, 'id1');
        $opt2 = new MOption('opt2', 'value2', _M('Label', $module). ' 2', false, 'id2');
        $opt3 = new MOption('opt3', 'value3', _M('Label', $module). ' 3', false, 'id3');
        $opt4 = new MOption('opt4', 'value4', _M('Label', $module). ' 4', false, 'id4');

        $aOptions = array( $opt1, $opt2, $opt3, $opt4 );

        // array of arrays
        $aOptionsLabelValue = array(
            array( 'optA', 'valueA' ),
            array( 'optB', 'valueB' ),
            array( 'optC', 'valueC' )
        );

        $aOptionsSingle = array( 'optA', 'optB', 'optC', 'optD', 'optE', 'optF' );

        $aOptionsControl = array(
            new MCheckBox('chkBox1', 'value1', 'XXXXXXX', false, 'Check Box 1'),
            new MCheckBox('chkBox2', 'value2', 'Label2', false, 'Check Box 2'),
            new MCheckBox('chkBox3', 'value3', 'Label3', false, 'Check Box 3')
        );

        $action = $MIOLO->getCurrentURL();
        $aLinkGroup = array(
            new MLinkButton('lnkButton1', 'link 1', $action),
            new MLinkButton('lnkButton2', 'link 2', $action),
            new MLinkButton('lnkButton3', 'link 3', $action),
            new MLinkButton('lnkButton4', 'link 4', $action),
            new MLinkButton('lnkButton5', 'link 5', $action)
        );

        $div2 = new MDiv('', $aLinkGroup);
        $div2->addStyle('background-color', '#999900');
        $div2->addStyle('width', '100px');
        $div2->addStyle('color', '#FFF');

        // new div with $div2 as content
        $div3 = new MDiv('', $div2);
        $div3->addStyle('background-color', '#DDD');
        $div3->addStyle('width', '300px');
        $div3->addStyle('padding', '5px');
        $div3->addStyle('float', 'left');

        $div3 = new MDiv('', $div2);
        $div3->addStyle('background-color', '#DDD');
        $div3->addStyle('width', '300px');
        $div3->addStyle('padding', '5px');
        $div3->addStyle('float', 'left');

        $aControlGroup = array(
            new MLinkButton('lnkButton', 'linkbutton', $action),
            new MLabel('A piece of text'),
            new MImage('ifrDummy', 'Label of imageform', $MIOLO->getUI()->getImage('', 'attention2.gif')),
            new MButton('btnForm1', 'Submit', 'submit'),
            $div3
        );

        // this groupcontrols classes are located in <miolo2>/classes/ui/controls/groupcontrols.class
        // take a look there for further details :-)
        $chkGroup1 = new MCheckBoxGroup('chkGroup1', "MCheckBoxGroup (". _M('using', $module) ." MOption Array)", $aOptions, '', 'horizontal', 'css');
        $chkGroup2 = new MCheckBoxGroup('chkGroup2', "MCheckBoxGroup (". _M('using', $module) ." Key/Value Array)", $aOptionsLabelValue, '', 'horizontal', 'css');
        $chkGroup3 = new MCheckBoxGroup('chkGroup3', "MCheckBoxGroup (". _M('using', $module) ." Single Array)", $aOptionsSingle, '', 'horizontal', 'css');
        $chkGroup4 = new MCheckBoxGroup('chkGroup4', "MCheckBoxGroup (". _M('using', $module) ." MCheckBox Array)", $aOptionsControl, '', 'horizontal', 'css');
        $radioGroup1 = new MRadioButtonGroup('radioGroup1', "MRadioButtonGroup (". _M('using', $module) ." MOption)", $aOptions, 'value3', '', 'horizontal', 'css');
        $radioGroup2 = new MRadioButtonGroup('radioGroup2', "MRadioButtonGroup (". _M('using array', $module) .")", $aOptionsLabelValue, 'valueB', '', 'horizontal', 'css');
        $radioGroup3 = new MRadioButtonGroup('radioGroup3', "MRadioButtonGroup (". _M('using single array and', $module) ." AutoPostBack)", $aOptionsSingle, 'optD', '', 'horizontal', 'css');
        //$radioGroup3->setAutoPostBack(true);
        $linkGroupH = new MLinkButtonGroup('linkGroupH', "MLinkButtonGroup ". _M('using', $module) ." MLinkButton (Horizontal)", $aLinkGroup, 'horizontal', 'css');
        $linkGroupV = new MLinkButtonGroup('linkGroupV', "MLinkButtonGroup ". _M('using', $module) ." MLinkButton (Vertical)", $aLinkGroup, 'vertical', 'css');
        $controlGroup = new MBaseGroup('controlGroup', "MBaseGroup ". _M('using array control', $module), $aControlGroup, 'horizontal', 'css');

        $fields = array(
            new MSpacer(), $chkGroup1,
            new MSpacer(), $chkGroup2,
            new MSpacer(), $chkGroup3,
            new MSpacer(), $chkGroup4,
            new MSpacer(), $radioGroup1,
            new MSpacer(), $radioGroup2,
            new MSpacer(), $radioGroup3,
            new MSpacer(), $linkGroupH,
            new MSpacer(), $linkGroupV,
            new MSpacer(), $controlGroup
        );

        $this->addFields($fields);
    }
}

?>