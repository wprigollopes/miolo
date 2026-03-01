<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('MDbal', $module, $self);
$form = $ui->getForm($module, 'frmDbal');
$theme->insertContent($form);
?>
