<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Box', $module), $module, $action);

$form = $ui->getForm($module, 'frmBox');
$theme->insertContent($form);
