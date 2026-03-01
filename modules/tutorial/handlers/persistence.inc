<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Persistence', $module, $self);
    $form = $ui->GetForm('tutorial','frmPersistence');
    $theme->InsertContent($form);
?>
