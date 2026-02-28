<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Grider', $module), $module, $self);
$form = $ui->getForm($module, 'frmGrider');
$theme->insertContent($form);
?>