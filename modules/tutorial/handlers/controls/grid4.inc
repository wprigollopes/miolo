<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('ActiveGrid', $module, $self);
    $grid = $ui->GetForm($module,'frmGrid4');
    if ($theme->GetContent()  == '')
    {
      $theme->InsertContent($grid);
    }
?>
