<?php

// add an option to the navigation bar
$navbar->addOption('Locadora', $module, 'main');

$theme->clearContent();

// generates a link to put no the panel's close button
$close = $MIOLO->getActionURL( 'admin', 'login' );

// create a new panel
$locPanel = new MActionPanel( 'pnlLocadora', 'Locadora', '', $close );

// obtains access to the UIfunctions (needed to able to use getImage
$ui = $MIOLO->getUI();

// add actions to the panel
$locPanel->addAction( _M('VeÃ­culos'), $ui->getImage( $module, 'car.png' ), $module, 'main:veiculos' );
$locPanel->addAction( _M('LocaÃ§Ãµes'), $ui->getImage( $module, 'document.png' ), $module, 'main:locacoes' );

// append the content into the theme content
$theme->appendContent( $locPanel );

$MIOLO->getSession()->setValue( 'num_mainmenu', 0 );

$locMenu  = $theme->getMenu('locadora');
$locMenu->setTitle( _M('Locadora') );
$locMenu->addOption( _M('Veiculos'), $module, 'main:veiculos');
$locMenu->addOption( _M('LocaÃ§Ãµes'), $module, 'main:locacoes');

$MIOLO->uses('ui/controls/viewsource.class', 'tutorial');

// invoke the next handler 
// btw: the handlers are separetad by : in the $action
// so, if you accessed 'main:controls', shiftAction will return 'controls'
// as the next handler to be invoked
if ( $a = $context->shiftAction() )
{
   $handled = $MIOLO->invokeHandler( $module, $a );
}

?>