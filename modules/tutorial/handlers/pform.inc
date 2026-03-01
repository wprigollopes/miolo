<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Controles', $module, $self);
    $form = $ui->GetForm($module,'frmPForm');
	$theme->InsertContent($form);

?>
