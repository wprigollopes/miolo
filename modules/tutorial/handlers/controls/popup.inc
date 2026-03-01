<?php
$theme->clearContent();

$navbar->addOption(_M('Popup', $module), $module, $action);

$ui = $MIOLO->getUI();
$form = $ui->getForm($module, 'frmPopup');
?>
