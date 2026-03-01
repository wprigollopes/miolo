<?php
$navbar->addOption(_M('Expand Div', $module), $module, $action);

$ui = $MIOLO->getUI();
$formSave = $ui->getForm($module, 'frmExpandDiv');

$theme->clearContent();
$theme->insertContent($formSave);
?>
