<?

$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('SimpleTable', $module, $self);
$form = $ui->GetForm($module,'frmSimpleTable');
    $theme->InsertContent($form);

?>
