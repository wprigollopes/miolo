<?php

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Context Menu', $module), $module, $self);
$form = $ui->getForm($module, 'frmContextMenu');
$theme->insertContent($form);
