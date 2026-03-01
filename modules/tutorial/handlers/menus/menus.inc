<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Menu', $module, $self);
    $form = $ui->GetForm($module,'frmMenuForm');
	$theme->InsertContent($form);
?>