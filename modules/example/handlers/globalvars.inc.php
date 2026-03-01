<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Global Variables', $module), $module, $action);

$form = $ui->getForm($module, 'frmGlobalVars');
$theme->insertContent($form);
