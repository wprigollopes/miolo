<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Positioning CSS Form', $module, $self);
    $form = $ui->GetForm($module,'frmPosForm0');
	$theme->InsertContent($form);
?>