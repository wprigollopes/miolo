<?php
$navbar->addOption(_M('Editor', $module), $module, $action);

$ui = $MIOLO->getUI();
$formSave = $ui->getForm($module, 'frmEditor');

$theme->clearContent();
$theme->insertContent($formSave);
?>
