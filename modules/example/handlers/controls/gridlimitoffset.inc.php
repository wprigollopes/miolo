<?php
$navbar->addOption(_M('Grid using LIMIT/OFFSET', $module), $module, $action);
$theme->clearContent();

$ui = $MIOLO->getUI();

$form = $ui->getForm($module, 'frmGridLimitOffset');
$theme->insertContent($form);
?>
