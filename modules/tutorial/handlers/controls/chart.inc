<?php
$navbar->addOption(_M('Chart', $module), $module, $action);

$ui = $MIOLO->getUI();
$form = $ui->getForm($module, 'frmChart');

$theme->clearContent();
$theme->insertContent($form);

?>