<?php
// Demo of how to create messages
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Using Messages', $module, $self);
$form = $ui->getForm($module,'frmMessageDemo');
$theme->insertContent($form);
