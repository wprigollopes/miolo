<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Button Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmButtons');
$theme->insertContent($form);
