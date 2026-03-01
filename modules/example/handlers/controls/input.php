<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Input Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmInput');
$theme->insertContent($form);
