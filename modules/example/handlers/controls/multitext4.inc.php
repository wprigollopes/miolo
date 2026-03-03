<?php
$theme->clearContent();
$ui = $MIOLO->getUI();

$navbar->addOption('MMultiTextField 4', $module, $self);

$form = $ui->getForm($module, 'frmMultiText4');
$theme->insertContent($form);
?>
