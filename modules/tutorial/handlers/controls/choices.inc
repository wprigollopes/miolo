<?php

$navbar->addOption('Choice Controls', $module, $action);

//$ui = $MIOLO->GetUI();

$theme->breakContent();

// create the panel
$panel  = new MPanel( 'pnlDummy', _M('Choice Controls') );

// create a check boxes
$chkBox  = new MCheckBox( 'chkBox', 'value', 'Label', true, _M('A text for CheckBox') );
$chkBox1 = new MCheckBox( 'chkBox1', 'value1', 'Label', false, _M('Another CheckBox') );
$chkBox1 -> setChecked( true );

// create a radio buttons
$radio  = new MRadioButton( 'radio', 'value', 'Label', true, _M('A text for RadioButton') );
$radio1 = new MRadioButton( 'radio', 'value1', 'Label2', false, _M('Another RadioButton') );

$controls = array( new MLabel('CheckBoxes: '), $chkBox, $chkBox1,
                   new MSeparator(),
                   new MLabel('Radio Buttons: '), $radio, $radio1,
                   new MSeparator()
                  );

$panel->addControl( $controls,'','clear' );
$theme->insertContent( $panel );

// create a link to view the source of this file
$src = new ViewSource( __FILE__ );
$theme->appendContent( $src );

?>