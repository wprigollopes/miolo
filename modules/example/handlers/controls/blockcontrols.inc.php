<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Block Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmBlockControls');
$theme->insertContent($form);
?>