<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Group Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmGroups');
$theme->insertContent($form);
