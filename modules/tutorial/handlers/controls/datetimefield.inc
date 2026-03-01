<?php
$theme->clearContent();
$navbar->addOption(_M('Date and time', $module), $module, $action);
$ui = $MIOLO->getUI();
$form = $ui->getForm($module, 'frmDateTimeField');
$theme->insertContent($form);
?>