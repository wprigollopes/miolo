<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('FileContent', $module, $self);
    $form = $ui->GetForm($module, 'frmFileContent');
    $theme->InsertContent($form);
?>
