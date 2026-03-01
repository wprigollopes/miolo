<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Exceptions', $module, 'main:exceptions');
    $formSave = $ui->GetForm($module,'frmExceptions');
	if ($theme->GetContent()  == '')
	{
		$theme->InsertContent($formSave);
	}
?>
