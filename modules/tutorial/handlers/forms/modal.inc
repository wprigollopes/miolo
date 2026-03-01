<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Modal Form', $module, $self);
    $form = $ui->GetForm($module,'frmModalForm');
	$theme->InsertContent($form);
?>