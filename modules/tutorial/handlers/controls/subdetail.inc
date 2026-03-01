<?php
$navbar->addOption(_M('SubDetail', $module), $module, $action);

$ui = $MIOLO->getUI();
$formSave = $ui->getForm($module, 'frmSubDetail');

$theme->clearContent();
$theme->insertContent($formSave);
?>
