<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Complex Layout', $module), $module, $self);
$form = $ui->getForm($module, 'frmComplex');
$theme->insertContent($form);
?>