<?
    $theme->ClearContent();

    $ui = $MIOLO->GetUI();
    $navbar->AddOption('HtmlArea', $module, $self);
    $form = $ui->GetForm($module, 'frmHtmlArea');
    $theme->InsertContent($form);
?>
