<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Tables', $module, $self);
    $form = $ui->GetForm($module,'frmTables');
	$theme->InsertContent($form);

?>
