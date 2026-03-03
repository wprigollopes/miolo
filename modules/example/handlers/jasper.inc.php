<?
    $ui = $MIOLO->getUI();
    $theme->clearContent();
    $navbar->addOption('Jasper', $module, $self);
    $form = $ui->getForm($module,'frmJasper');
    if ($theme->getContent()  == '')
    {
      $theme->insertContent($form);
    }
?>
