<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Form', $module, $self);
    $form = $ui->GetForm($module,'frmFormZebra');
	$theme->InsertContent($form);
?>