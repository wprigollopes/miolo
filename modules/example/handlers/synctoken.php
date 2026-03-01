<?

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('Sync Token', $module, $self);
$form = $ui->getForm($module,'frmSyncToken');
    $theme->insertContent($form);
