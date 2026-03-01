<?php
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption('Special Grid', $module, $self);
$form = $ui->getForm($module, 'frmSpecialGrid');
$theme->insertContent($form);
