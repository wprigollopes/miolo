<?php
$theme->clearContent();

$ui = $MIOLO->getUI();
$navbar->addOption(_M('Accordion', $module), $module, $self);
$form = $ui->getForm($module, 'frmCollapsible');
$theme->insertContent($form);
