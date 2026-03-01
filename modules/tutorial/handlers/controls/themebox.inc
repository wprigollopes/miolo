<?
$theme->ClearContent();
$ui = $MIOLO->GetUI();
$navbar->AddOption('ThemeBox', $module, $self);
$form = $ui->GetForm($module,'frmThemeBox');
$theme->InsertContent($form);
?>
