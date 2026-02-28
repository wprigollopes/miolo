<?

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('TableRaw', $module, $self);
$form = $ui->getForm($module,'frmTableRaw');
    $theme->insertContent($form);

?>
