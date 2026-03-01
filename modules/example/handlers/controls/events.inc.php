<?

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('DOM Events', $module, $self);
$form = $ui->getForm($module,'frmEvents');
    $theme->insertContent($form);
