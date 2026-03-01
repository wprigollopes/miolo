<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Lookup', $module, $self);
    $form = $ui->GetForm($module,'frmLookup');
    $theme->InsertContent($form);
?>
