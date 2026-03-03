<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Image Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmImage');
$theme->insertContent($form);
?>