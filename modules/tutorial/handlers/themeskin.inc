<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Theme Skin', $module, 'main:themeskin');
    $formSave = $ui->GetForm($module,'frmThemeSkin');
	if ($theme->GetContent()  == '')
	{
		$theme->InsertContent($formSave);
	}
?>
