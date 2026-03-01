<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Complex Layout CSS', $module, $self);
    $form = $ui->GetForm($module,'frmComplexCSS');
	$theme->InsertContent($form);
?>