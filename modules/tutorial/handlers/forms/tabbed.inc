<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Tabbed', $module, $self);
    $form = $ui->GetForm($module,'frmTabbed');
    if ($theme->GetContent()  == '')
    {
      $theme->InsertContent($form);
    }

?>
