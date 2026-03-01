<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Prompt', $module, $self);
    $form = $ui->GetForm($module,'frmPrompt');
    if ($theme->GetContent() != '')
       $theme->AppendContent($form);
    else
       $theme->InsertContent($form);

?>
