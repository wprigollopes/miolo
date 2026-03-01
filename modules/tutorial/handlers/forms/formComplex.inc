<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Complex Layout', $module, $self);
    $form = $ui->GetForm($module,'frmComplex');
	$theme->InsertContent($form);
?>