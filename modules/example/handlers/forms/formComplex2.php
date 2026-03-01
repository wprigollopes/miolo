<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Complex CSS Layout', $module), $module, $self);
$form = $ui->getForm($module, 'frmComplexCSS');
$theme->insertContent($form);
