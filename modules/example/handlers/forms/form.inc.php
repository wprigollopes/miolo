<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Form', $module, $self);
$form = $ui->getForm($module, 'frmForm');
$theme->insertContent($form);
?>