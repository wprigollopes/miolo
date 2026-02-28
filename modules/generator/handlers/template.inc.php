<?php
$theme->clearContent();
$navbar->addOption(_M('Template', $module), $module, 'main');

$ui = $MIOLO->getUI();
$form = $ui->getForm($module,'frmTemplate');

$theme->insertContent($form);
?>
