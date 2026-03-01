<?

$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('TableRaw', $module, $self);
$form = $ui->GetForm($module,'frmTableRaw');
    $theme->InsertContent($form);

?>
