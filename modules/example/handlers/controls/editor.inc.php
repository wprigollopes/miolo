<?php
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption(_M('Editor', $module), $module, $self);
$form = $ui->getForm($module, 'frmEditor');
$theme->insertContent($form);
?>
