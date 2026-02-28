<?php
$ui = $MIOLO->getUI();

$theme->clearContent();
$navbar->addOption('Tips (hints)', $module, $self);
$form = $ui->getForm($module, 'frmHints');

if ($theme->getContent() == '')
{
    $theme->insertContent($form);
}
?>
