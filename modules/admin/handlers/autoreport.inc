<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Automatic Report Generation', $module), $module, $action);
$form = $ui->getForm($module, 'frmAutoReport');
$theme->insertContent($form);
?>