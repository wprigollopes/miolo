<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Form', $module, $self);
    $form = $ui->GetForm($module, 'frmFormHV2');
    $theme->InsertContent($form);
?>
