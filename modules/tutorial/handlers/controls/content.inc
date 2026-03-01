<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Content', $module, $self);
    $form = $ui->GetForm($module, 'frmContent');
    $theme->InsertContent($form);
?>
