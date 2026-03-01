<?php
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption(_M('SubDetail', $module), $module, $self);
$form = $ui->getForm($module, 'frmSubDetail');
$theme->insertContent($form);
