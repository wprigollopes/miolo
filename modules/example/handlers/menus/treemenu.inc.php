<?php

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Tree Menu', $module), $module, $self);
$form = $ui->getForm($module, 'frmTreeMenu');
$theme->insertContent($form);
