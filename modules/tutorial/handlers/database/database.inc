<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Database', $module, $self);
    $form = $ui->GetForm($module,'frmDatabase');
    $theme->InsertContent($form);
?>
