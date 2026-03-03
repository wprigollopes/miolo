<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Form HV', $module, $self);
$form = $ui->getForm($module, 'frmFormHV');
$theme->insertContent($form);
?>
