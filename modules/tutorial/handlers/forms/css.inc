<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Relative CSS Form', $module, $self);
    $form = $ui->GetForm($module,'frmCSSForm');
	$theme->InsertContent($form);
?>