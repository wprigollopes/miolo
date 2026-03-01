<?php
$theme->clearContent();
$navbar->addOption(_M('Generate', $module), $module, 'main');

$ui = $MIOLO->getUI();
$form = $ui->getForm($module,'frmGenerate');

$theme->insertContent($form);
