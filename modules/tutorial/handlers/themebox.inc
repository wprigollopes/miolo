<?

$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('ThemBox', $module, $self);
$form = $ui->GetForm($module,'frmThemeBox');
    $theme->InsertContent($form);

?>
