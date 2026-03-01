<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Session', $module, $self);
    $formSession = $ui->GetForm($module, 'frmSession');
    $theme->InsertContent($formSession);
?>
