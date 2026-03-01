<?php

$navbar->addOption(_M('Custom Fields', $module), $module, $action);

$ui = $MIOLO->getUI();
$formSave = $ui->getForm($module, 'frmCustomFieldExample');

$theme->clearContent();
$theme->insertContent($formSave);

?>
