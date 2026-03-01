<?php

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Dual List', $module), $module, $self);

$form = $ui->getForm($module, 'frmDualList');
$theme->insertContent($form);
