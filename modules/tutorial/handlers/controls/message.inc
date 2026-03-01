<?php
$navbar->addOption(_M('Message', $module), $module, $action);

$ui = $MIOLO->getUI();
$formSave = $ui->getForm($module, 'frmMessage');

$theme->clearContent();
$theme->insertContent($formSave);
?>
