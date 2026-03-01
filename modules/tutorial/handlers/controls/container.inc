<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Container', $module, $self);
    $form = $ui->GetForm($module, 'frmContainer');
    $theme->InsertContent($form);
?>
