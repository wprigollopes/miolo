<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('ISR', $module, $self);
    $form = $ui->GetForm($module,'frmISR');
    $theme->InsertContent($form);
?>
