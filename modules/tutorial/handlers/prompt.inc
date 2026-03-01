<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Prompt', $module, $self);
    $form = $ui->GetForm($module,'frmPrompt');
    $theme->InsertContent($form);

?>
