<?

$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('Form', $module, $self);
$form = $ui->GetForm($module,'frmTipoDocumento_ely');
if ($theme->GetContent() == '')
    $theme->SetContent($form);

?>
