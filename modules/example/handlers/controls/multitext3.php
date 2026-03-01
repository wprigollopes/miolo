<?php
$theme->clearContent();
$ui = $MIOLO->getUI();

$navbar->addOption('MMultiTextField 3', $module, $self);

$form = $ui->getForm($module, 'frmMultiText3');
$theme->insertContent($form);
