<?php
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('LookupForm', $module, $self);
    $form = $ui->GetForm($module,'frmLookupForm');
	$theme->InsertContent($form);
?>
