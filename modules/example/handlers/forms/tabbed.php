<?php
$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Tabbed', $module, $self);
$form = $ui->getForm($module, 'frmTabbed');
if ( $theme->getContent() == '' )
{
    $theme->insertContent($form);
}
