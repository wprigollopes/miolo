<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Form', $module, $self);
    $form = $ui->GetForm($module, 'frmFormHV');
    $theme->InsertContent($form);
?>
