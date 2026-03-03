<?php
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption('Timestamp', $module, $self);
$form = $ui->getForm($module, 'frmTimestampField');
$theme->insertContent($form);
?>
