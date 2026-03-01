<?php

$navbar->AddOption(_M('Group Controls'), $module, $action);

$ui = $MIOLO->GetUI();

$panel = new MPanel('pnlDummy', 'Group Controls');

// creates some options
$opt1 = new MOption('opt1', 'value1', 'label1', false, 'id1');
$opt2 = new MOption('opt2', 'value2', 'label2', false, 'id2');
$opt3 = new MOption('opt3', 'value3', 'label3', false, 'id3');
$opt4 = new MOption('opt4', 'value4', 'label4', false, 'id4');

$aOptions = array( $opt1, $opt2, $opt3, $opt4 );

// array of arrays
$aOptionsLabelValue = array( array( 'optA', 'valueA' ),
    array( 'optB', 'valueB' ),
    array( 'optC', 'valueC' )
);
// value=>label arrays
$aOptionsValueLabel = array(
    'valueA' => 'optA',
    'valueB' => 'optB',
    'valueC' => 'optC'
);

$aOptionsSingle = array( 'optA', 'optB', 'optC', 'optD', 'optE', 'optF' );

$aOptionsControl = array( new MCheckBox('chkBox1', 'value1', 'XXXXXXX', false, 'Check Box 1'),
    new MCheckBox('chkBox2', 'value2', 'Label2', false, 'Check Box 2'),
    new MCheckBox('chkBox3', 'value3', 'Label3', false, 'Check Box 3')
);

$aLinkGroup = array( new MLinkButton('lnkButton1', 'link 1', $action),
    new MLinkButton('lnkButton2', 'link 2', $action),
    new MLinkButton('lnkButton3', 'link 3', $action),
    new MLinkButton('lnkButton4', 'link 4', $action),
    new MLinkButton('lnkButton5', 'link 5', $action)
);

$div2 = new MDiv('', $aLinkGroup);
$div2->addStyle('backgroundColor', '#999900');
$div2->addStyle('width', '100px');
$div2->addStyle('color', '#FFF');

// new div with $div2 as content
$div3 = new MDiv('', $div2);
$div3->addStyle('backgroundColor', '#DDD');
$div3->addStyle('width', '300px');
$div3->addStyle('padding', '5px');
$div3->addStyle('float', 'left');

$div3 = new MDiv('', $div2);
$div3->addStyle('backgroundColor', '#DDD');
$div3->addStyle('width', '300px');
$div3->addStyle('padding', '5px');
$div3->addStyle('float', 'left');



$aControlGroup = array( new MLinkButton('lnkButton', 'linkbutton', $action),
    new MLabel('A piece of text'),
    new MImage('ifrDummy', 'Label of imageform', $ui->GetImage('', 'attention.png')),
    new MButton('btnForm1', 'Submit', 'submit'),
    $div3
);

// this groupcontrols classes are located in <miolo2>/classes/ui/controls/groupcontrols.class
// take a look there for further details :-)
$chkGroup1 = new MCheckBoxGroup('chkGroup1', 'Group Caption Label', $aOptions, '', 'horizontal', 'css');
$chkGroup2 = new MCheckBoxGroup('chkGroup2', 'Group Caption Label', $aOptionsLabelValue, '', 'horizontal', 'css');
$chkGroup3 = new MCheckBoxGroup('chkGroup3', 'Group Caption Label', $aOptionsSingle, '', 'horizontal', 'css');
$chkGroup4 = new MCheckBoxGroup('chkGroup4', 'Group Caption Label', $aOptionsControl, '', 'horizontal', 'css');
$radioGroup1 = new MRadioButtonGroup('radioGroup1', 'Group Caption Label', $aOptions, 'value3', '', 'horizontal', 'css');
$radioGroup2 = new MRadioButtonGroup('radioGroup2', 'Group Caption Label', $aOptionsLabelValue, 'valueB', '', 'horizontal', 'css');
$radioGroup3 = new MRadioButtonGroup('radioGroup3', 'Group Caption Label', $aOptionsValueLabel, 'valueA', '', 'horizontal', 'css');
$radioGroup4 = new MRadioButtonGroup('radioGroup4', 'Group Caption Label', $aOptionsSingle, 'optD', '', 'horizontal', 'css');
$linkGroupH = new MLinkButtonGroup('linkGroupH', 'Group Caption Label', $aLinkGroup, 'horizontal', 'css');
$linkGroupV = new MLinkButtonGroup('linkGroupV', 'Group Caption Label', $aLinkGroup, 'vertical', 'css');
$controlGroup = new MBaseGroup('controlGroup', 'label', $aControlGroup, 'horizontal', 'css');

$controls = array(
    new MLabel("CheckBox Group (Option Array): "), new MSpacer(), $chkGroup1, new MSeparator(),
    new MLabel("CheckBox Group (Key/Value Array): "), new MSpacer(), $chkGroup2, new MSeparator(),
    new MLabel("CheckBox Group (Single Array): "), new MSpacer(), $chkGroup3, new MSeparator(),
    new MLabel("CheckBox Group (Controls Array): "), new MSpacer(), $chkGroup4, new MSeparator(),
    new MLabel("RadioButton Group (Option Array):<br> "), new MSpacer(), $radioGroup1, new MSeparator(),
    new MLabel("RadioButton Group (Label, Value Array):<br> "), new MSpacer(), $radioGroup2, new MSeparator(),
    new MLabel("RadioButton Group (Value => Label Array):<br> "), new MSpacer(), $radioGroup3, new MSeparator(),
    new MLabel("RadioButton Group (Single Array):<br> "), new MSpacer(), $radioGroup4, new MSeparator(),
    new MLabel("LinkButton Group (Horizontal): "), new MSpacer(), $linkGroupH, new MSeparator(),
    new MLabel("LinkButton Group (Vertical):<br> "), new MSpacer(), $linkGroupV, new MSeparator(),
    new MLabel("A generic Control Group: "), new MSpacer(), $controlGroup, new MSeparator()
);

$panel->AddControl($controls);

$theme->BreakContent();
$theme->InsertContent($panel);

// create a link to view the source of this file
$src = new ViewSource(__FILE__);
$theme->appendContent($src);

?>
