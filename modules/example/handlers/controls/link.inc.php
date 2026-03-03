<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Link Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmLink');
$theme->insertContent($form);
?>