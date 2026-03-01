<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Extensions', $module, 'main:extensions');
    $formSave = $ui->GetForm($module,'frmExtensions');
	if ($theme->GetContent()  == '')
	{
		$theme->InsertContent($formSave);
	}
?>