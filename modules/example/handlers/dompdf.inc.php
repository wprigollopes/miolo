<?
    $ui = $MIOLO->getUI();
    $theme->clearContent();
    $navbar->addOption('DOMPDF', $module, $self);
    $form = $ui->getForm($module,'frmDOMPDF');
    if ($theme->getContent()  == '')
    {
      $theme->insertContent($form);
    }
?>
