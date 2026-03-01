<?php
$navbar->addOption('Grid', $module, $action);
$theme->clearContent();

$ui = $MIOLO->getUI();

$form = $ui->getForm($module, 'frmGrid2');
$theme->insertContent($form);
