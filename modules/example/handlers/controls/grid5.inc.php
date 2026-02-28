<?php
$navbar->addOption(_M('Two Grids', $module), $module, $action);
$theme->clearContent();

$ui = $MIOLO->getUI();

$form = $ui->getForm($module, 'frmGrid5');
$theme->insertContent($form);
?>
