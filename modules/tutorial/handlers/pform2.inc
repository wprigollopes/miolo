<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Controles', $module, $self);
    $form = $ui->GetForm($module,'frmPForm2');
	$theme->InsertContent($form);

?>
