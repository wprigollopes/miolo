<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('IFrame', $module, $self);
    $form = $ui->GetForm($module, 'frmIFrame');
    $theme->InsertContent($form);
?>
