<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('TabContainer', $module, $self);
$form = $ui->getForm($module,'frmTabContainer');
$theme->insertContent($form);
?>
