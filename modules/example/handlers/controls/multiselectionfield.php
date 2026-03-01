<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('MultiSelectionField', $module, $self);

$form = $ui->getForm($module, 'frmMultiSelectionField');
$theme->insertContent($form);
