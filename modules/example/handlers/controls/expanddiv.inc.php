<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Expand Div', $module), $module, $self);

$form = $ui->getForm($module, 'frmExpandDiv');
$theme->insertContent($form);
?>
