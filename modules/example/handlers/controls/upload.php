<?php
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption(_M('Upload', $module), $module, $self);
$form = $ui->getForm($module, 'frmUpload');
$theme->insertContent($form);
