<?php

// add an option to the navigation bar
$navbar->addOption( _M('Currency Field'), $module, $action );

// access UI functions (necessary to br able to call getForm method)
$ui = $MIOLO->getUI();

// instanciates the form in tutorial/forms/frmCurrency.class
$form = $ui->getForm( $module, 'frmCurrency' );

// clear the theme content
$theme->clearContent();
// inserts the form into the content
$theme->insertContent( $form );

?>
