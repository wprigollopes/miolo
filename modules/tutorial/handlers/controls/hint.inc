<?
    $ui = $MIOLO->GetUI();

    $theme->ClearContent();
    $navbar->AddOption('Tips (hints)', $module, $self);
    $form = $ui->GetForm($module, 'frmHints');

    if ($theme->GetContent() == '')
    {
        $theme->InsertContent($form);
    }
?>
