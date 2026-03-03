<?php

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Static Menu', $module), $module, $self);
$form = $ui->getForm($module, 'frmStaticMenu');
$theme->insertContent($form);

?>