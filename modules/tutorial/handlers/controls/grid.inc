<?
    $theme->ClearContent();
    $ui = $MIOLO->GetUI();
    $navbar->AddOption('Grid', $module, $self);
    $listing = $ui->GetListing($module,'lstGrid');
    if ($theme->GetContent()  == '')
    {
      $theme->InsertContent($listing);
    }
?>
