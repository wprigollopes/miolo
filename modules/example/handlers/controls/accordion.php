<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Accordion', $module, $self);
$form = $ui->getForm($module,'frmAccordion');
$theme->insertContent($form);
