<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('List Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmLists');
$theme->insertContent($form);
