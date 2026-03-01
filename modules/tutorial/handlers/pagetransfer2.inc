<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Page Transfer 2', $module, $self);
    $form = $ui->GetForm($module,'frmPageTransfer2');
    $theme->InsertContent($form);
?>
