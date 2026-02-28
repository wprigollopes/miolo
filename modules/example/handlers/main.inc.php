<?php

// set the home of navigation bar
$navbar->setHome($module);
$navbar->setLabelHome(_M('Example', $module));
$navbar->addOption(_M('Main', $module), $module, 'main');

// clear the content area of theme
$theme->clearContent();

// create a new panel,
$tutPanel = new MActionPanel('pnlexample', _M('Example', $module));

// obtains access to the UIfunctions (needed to able to use getImage
$ui = $MIOLO->getUI();

// add actions to the panel
$tutPanel->addAction(_M('Global Variables', $module), $ui->getImage($module, 'globals.png'), $module, 'main:globalvars');
$tutPanel->addAction(_M('Controls', $module), $ui->getImage($module, 'controls.png'), $module, 'main:controls');
$tutPanel->addAction(_M('Validators', $module), $ui->getImage($module, 'validators.png'), $module, 'main:validators');
$tutPanel->addAction('ezPDF', $ui->getImage($module, 'pdf.png'), $module, 'main:ezpdf');
$tutPanel->addAction('Jasper', $ui->getImage($module, 'pdf.png'), $module, 'main:jasper');
$tutPanel->addAction('DOMPDF', $ui->getImage($module, 'pdf.png'), $module, 'main:dompdf');
$tutPanel->addAction(_M('Forms', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms');
$tutPanel->addAction(_M('Menus', $module), $ui->getImage($module, 'forms.png'), $module, 'main:menus');
$tutPanel->addAction(_M('Database', $module), $ui->getImage($module, 'database.png'), $module, 'main:database');
$tutPanel->addAction(_M('Persistence', $module), $ui->getImage($module, 'database.png'), 'persistence', 'main');
$tutPanel->addAction(_M('Sessions', $module), $ui->getImage($module, 'session.png'), $module, 'main:session');
$tutPanel->addAction(_M('Save File', $module), $ui->getImage($module, 'save.png'), $module, 'main:save');
$tutPanel->addAction(_M('Exceptions', $module), $ui->getImage($module, 'forms.png'), $module, 'main:exceptions');
$tutPanel->addAction(_M('Events', $module), $ui->getImage($module, 'forms.png'), $module, 'main:events');
$tutPanel->addAction(_M('Date & Time', $module), $ui->getImage($module, 'datetime.png'), $module, 'main:datetime');
$tutPanel->addAction(_M('Login Needed', $module), $ui->getImage($module, 'forms.png'), $module, 'main:loginneeded');
$tutPanel->addAction('AJAX', $ui->getImage($module, 'forms.png'), $module, 'main:ajax');
$tutPanel->addAction(_M('Flow: forward', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forward1');
$tutPanel->addAction(_M('Flow: insert', $module), $ui->getImage($module, 'forms.gif'), $module, 'main:insert1');
$tutPanel->addAction(_M('Windows', $module), $ui->getImage($module, 'forms.png'), $module, 'main:windows');
$tutPanel->addAction(_M('Drag&Drop', $module), $ui->getImage($module, 'forms.png'), $module, 'main:dragdrop');
$tutPanel->addAction(_M('Sync Token', $module), $ui->getImage($module, 'forms.png'), $module, 'main:synctoken');
$tutPanel->addAction('WebServices', $ui->getImage($module, 'forms.png'), $module, 'main:webservices');

$theme->appendContent($tutPanel);

$MIOLO->uses('ui/controls/viewsource.class.php', $module);

$shiftAction = $context->shiftAction();

if ( $shiftAction )
{
    $handled = $MIOLO->invokeHandler($module, $shiftAction);
}

?>