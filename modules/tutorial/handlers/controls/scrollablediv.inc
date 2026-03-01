<?
    $ui = $MIOLO->GetUI();
    $theme->ClearContent();
    $navbar->AddOption('Scrollable Div', $module, $self);
    $form = $ui->GetForm($module,'frmScrollableDiv');
    if ($theme->GetContent()  == '')
    {
      $theme->InsertContent($form);
    }
?>
