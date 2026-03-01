<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('ActiveLookup', $module, $self);
    $form = $ui->GetForm($module,'frmActiveLookup');
    $theme->InsertContent($form);
?>
