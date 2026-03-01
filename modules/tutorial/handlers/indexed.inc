<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Indexed', $module, $self);
    $form = $ui->GetForm($module,'FrmIndexed');
    if ($theme->GetContent()  == '')
    {
      $theme->InsertContent($form);
    }

?>
