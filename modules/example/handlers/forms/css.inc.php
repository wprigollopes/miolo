<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Relative CSS Form', $module), $module, $self);
$form = $ui->getForm($module, 'frmCSSForm');
$theme->insertContent($form);
