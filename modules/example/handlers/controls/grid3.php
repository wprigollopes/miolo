<?php
$navbar->addOption('Grid', $module, $action);
$theme->clearContent();

$ui = $MIOLO->getUI();

$form = $ui->getForm($module, 'frmGrid3');
$theme->insertContent($form);
