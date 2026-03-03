<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Label Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmLabels');
$theme->insertContent($form);
?>