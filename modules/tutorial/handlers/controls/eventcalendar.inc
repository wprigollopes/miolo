<?php
$navbar->addOption(_M('Event Calendar', $module), $module, $action);

$ui = $MIOLO->getUI();
$formSave = $ui->getForm($module, 'frmEventCalendar');

$theme->clearContent();
$theme->insertContent($formSave);
?>
