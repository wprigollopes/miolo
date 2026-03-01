<?

$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('Form', $module, $self);
$form = $ui->GetForm($module,'frmControles');
    $theme->InsertContent($form);

?>
