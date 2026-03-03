<?php

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Menu', $module), $module, $self);
$form = $ui->getForm($module, 'frmMenuForm');
$theme->insertContent($form);

?>