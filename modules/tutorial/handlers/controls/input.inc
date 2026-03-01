<?php

$navbar->addOption(_M('Input Controls', $module), $module, $action);

$ui = $MIOLO->getUI();
$formSave = $ui->getForm($module, 'frmInput');

$theme->clearContent();
$theme->insertContent($formSave);

?>