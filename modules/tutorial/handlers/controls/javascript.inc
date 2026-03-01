<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Javascript', $module, $self);
    $form = $ui->GetForm($module, 'frmJavascript');
    $theme->InsertContent($form);
?>
