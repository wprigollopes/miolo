<?php

// add an option to the navigation bar
$navbar->addOption('Tutorial', $module, 'main');

$theme->clearContent();

// generates a link to put no the panel's close button
$close = $MIOLO->getActionURL( 'admin', 'login' );

// obtains access to the UIfunctions (needed to able to use getImage
$ui = $MIOLO->getUI();

// create a new panel
$tutPanel = new MActionPanel( 'pnlTutorial', 'Tutorial', '', $close, $ui->GetImage($module,'tutorial-16x16.png') );

// add actions to the panel
$tutPanel->addAction( _M('Global Variables'), $ui->getImage( $module, 'globals.png' ), $module, 'main:varglobais' );
$tutPanel->addAction( _M('Controls'), $ui->getImage( $module,'controls.png' ), $module, 'main:controls' );
$tutPanel->addAction( _M('Validators'), $ui->getImage( $module,'validators.png' ), $module, 'main:validators' );
$tutPanel->addAction( _M('ezPDF'), $ui->getImage( $module,'pdf.png' ), $module, 'main:ezpdf' );
$tutPanel->addAction( _M('Forms'), $ui->getImage( $module,'forms.png' ), $module, 'main:forms' );
$tutPanel->addAction( _M('Menus'), $ui->getImage( $module,'forms.png' ), $module, 'main:menus' );
$tutPanel->addAction( _M('Database'), $ui->getImage( $module,'database.png' ), $module, 'main:database' );
$tutPanel->addAction( _M('Persistence'), $ui->getImage( $module,'database.png' ), $module, 'main:persistence' );
$tutPanel->addAction( _M('Sessions'), $ui->getImage( $module,'session.png' ), $module, 'main:session' );
$tutPanel->addAction( _M('Save File'), $ui->getImage( $module,'save.png' ), $module, 'main:save' );
$tutPanel->addAction( _M('Exceptions'), $ui->getImage( $module,'forms.png' ), $module, 'main:exceptions' );
$tutPanel->addAction( _M('Events'), $ui->getImage( $module,'forms.png' ), $module, 'main:events' );
$tutPanel->addAction( _M('Extensions'), $ui->getImage( $module,'forms.png' ), $module, 'main:extensions' );
$tutPanel->addAction( _M('Date & Time'), $ui->getImage( $module,'datetime.png' ), $module, 'main:datetime' );
$tutPanel->addAction( _M('ISR'), $ui->getImage( $module,'forms.png' ), $module, 'main:isr' );
$tutPanel->addAction( _M('Login Needed'), $ui->getImage( $module,'forms.png' ), $module, 'main:loginneeded' );
$tutPanel->addAction( _M('AJAX'), $ui->getImage( $module,'forms.png' ), $module, 'main:ajax' );
$tutPanel->addAction( _M('AJAX Validation'), $ui->getImage( $module,'forms.png' ), $module, 'main:ajaxvalidation' );

// append the content into the theme content
$theme->appendContent( $tutPanel );

//if ($MIOLO->GetConf('options.mainmenu') == '2')
//{
//$MIOLO->GetSession()->setValue( 'num_mainmenu', 0 );

// Inlude the main menu definitions
include_once( $MIOLO->getConf('home.modules') .'/main_menu.inc' );

//}

$MIOLO->uses('ui/controls/viewsource.class', $module);

// invoke the next handler 
// btw: the handlers are separetad by : in the $action
// so, if you accessed 'main:controls', shiftAction will return 'controls'
// as the next handler to be invoked
if ( $a = $context->shiftAction() )
{
   $handled = $MIOLO->invokeHandler( $module, $a );
}

?>
