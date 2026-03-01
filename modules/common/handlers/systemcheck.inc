<?php
$theme->clearContent();

$navbar->addOption(_M('System Check', $module), $module, $action);

$ui = $MIOLO->getUI();
$form = $ui->getForm($module, 'frmSystemCheck');

$theme->clearContent();
$theme->insertContent($form);
?>
