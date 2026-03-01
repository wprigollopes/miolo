<?php

// adds an option to the navigation bar
$navbar->addOption( 'Button Controls', $module, $action );

// get access to the UI functions
$ui = $MIOLO->getUI();

// get an image located in the html/images dir
// the first parameter indicates the module or miolo's html/images by default
$img = $ui->getImage( '', 'button_edit.png' );

$theme->breakContent();

// create a panel
$panel = new MPanel( 'pnlDummy', 'Button Controls' );

// add some buttons
$btnForm1 = new MButton( 'btnForm1', 'Submit', 'submit' );
$btnForm2 = new MButton( 'btnForm2', 'Reset', 'reset' );
$btnForm3 = new MButton( 'btnForm3', 'Print', 'print' );
$btnForm4 = new MButton( 'btnForm4', 'Return', 'return' );
$btnForm7 = new MButton( 'btnForm7', 'Javascript', "alert('Javascript!')" );
$btnForm5 = new MButton( 'btnForm5', 'Goto', 'http://www.miolo.org.br' );

$btnForm6 = new MButton( 'btnForm6', 'Image', 'submit' );
$btnForm6->setImage( $img );
$btnForm6->setAction( MIOLO::getCurrentURL() );

$lnkButton = new MLinkButton( 'lnkButton', 'A label for linkbutton', MIOLO::getCurrentURL() );

$imgButton = new MImageButton( 'imgButton', 'A label for imagebutton', MIOLO::getCurrentURL(), $img );

// setting css class to the button
$inpButton = new MInputButton( 'inpButton', 'Input Button', MIOLO::getCurrentURL() );
$inpButton->setClass( 'm-button-find' );

$controls = array( new MLabel( 'Submit Button: '), $btnForm1, new MSeparator(),
                   new MLabel( 'Reset Button: '), $btnForm2, new MSeparator(),
                   new MLabel( 'Print Button: '), $btnForm3, new MSeparator(),
                   new MLabel( 'Return Button: '), $btnForm4, new MSeparator(),
                   new MLabel( 'URL Button: '), $btnForm5, new MSeparator(),
                   new MLabel( 'Image Button: '), $btnForm6, new MSeparator(),
                   new MLabel( 'Javascript Button: '), $btnForm7, new MSeparator(),
                   new MLabel( 'Link Button: '), $lnkButton, new MSeparator(),
                   new MLabel( 'Image Button: '), $imgButton, new MSeparator(),
                   new MLabel( 'Input Button (ex. find): '), $inpButton, new MSeparator(),
                  );

$panel->addControl( $controls, '', 'left' );
$theme->insertContent( $panel );

// create a link to view the source of this file
$src = new ViewSource( __FILE__ );
$theme->appendContent( $src );

?>