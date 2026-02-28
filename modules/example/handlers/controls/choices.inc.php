<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Choice Controls', $module), $module, $action);

$form = $ui->getForm($module, 'frmChoices');
$theme->insertContent($form);
?>