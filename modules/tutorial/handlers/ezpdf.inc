<?
    $ui = $MIOLO->GetUI();
    $theme->ClearContent();
    $navbar->AddOption('ezPDF', $module, $self);
    $form = $ui->GetForm($module,'frmEzPDF');
    if ($theme->GetContent()  == '')
    {
      $theme->InsertContent($form);
    }
?>
