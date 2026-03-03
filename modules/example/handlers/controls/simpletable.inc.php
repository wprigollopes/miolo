<?

$theme->clearContent();
$ui = $MIOLO->getUI();
$navbar->addOption('SimpleTable', $module, $self);
$form = $ui->getForm($module,'frmSimpleTable');
    $theme->insertContent($form);

?>
