<?php
$navbar->addOption('Grid', $module, $action);
$theme->clearContent();

$ui = $MIOLO->getUI();

$form = $ui->getForm($module, 'frmGrid1');
$theme->insertContent($form);
?>
