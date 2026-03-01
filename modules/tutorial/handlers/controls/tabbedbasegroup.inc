<?php
$navbar->addOption(_M('Tabs', $module), $module, $action);

$ui = $MIOLO->getUI();
$formSave = $ui->getForm($module, 'frmTabbedBaseGroup');

$theme->clearContent();
$theme->insertContent($formSave);
?>
