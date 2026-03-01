<?php
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption(_M('Dialog', $module), $module, $self);
$form = $ui->getForm($module, 'frmDialog');
$theme->insertContent($form);
