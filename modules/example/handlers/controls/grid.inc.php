<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Grid', $module, $self);
    $listing = $ui->getListing($module,'lstGrid');
    if ($theme->getContent()  == '')
    {
      $theme->insertContent($listing);
    }
?>
