<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('AJAX Validation', $module), $module, $self);
$form = $ui->getForm($module, 'frmAJAXValidation');
?>
