<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Form Zebra', $module, $self);
$form = $ui->getForm($module, 'frmFormZebra');
$theme->insertContent($form);
