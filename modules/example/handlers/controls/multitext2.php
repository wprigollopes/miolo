<?php
$navbar->addOption('MNMultiTextField2', $module, $action);
$theme->clearContent();

$ui = $MIOLO->getUI();

$form = $ui->getForm($module, 'frmMultiText2');
$theme->insertContent($form);

$src = new ViewSource(__FILE__);
$theme->appendContent($src);
