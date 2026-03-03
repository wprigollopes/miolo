<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('ThemeBox', $module, $self);
$form = $ui->getForm($module,'frmThemeBox');
$theme->insertContent($form);
?>
