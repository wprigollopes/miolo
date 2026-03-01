<?

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('TextTable', $module, $self);
$form = $ui->getForm($module,'frmTextTable');
    $theme->insertContent($form);
