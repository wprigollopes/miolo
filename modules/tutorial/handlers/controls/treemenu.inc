<?

$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('Tree Menu', $module, $self);
$form = $ui->GetForm($module,'frmTreeMenu');
$theme->InsertContent($form);

?>
