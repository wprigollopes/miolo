<?php
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption('Popup', $module, $self);
$form = $ui->getForm($module, 'frmPopup');
$theme->insertContent($form);
