<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Validators', $module, $self);
    $form = $ui->GetForm($module, 'frmValidators');
    $theme->InsertContent($form);
?>
