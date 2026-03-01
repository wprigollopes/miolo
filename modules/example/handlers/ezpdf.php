<?
    $ui = $MIOLO->getUI();
    $theme->clearContent();
    $navbar->addOption('ezPDF', $module, $self);
    $form = $ui->getForm($module,'frmEzPDF');
    if ($theme->getContent()  == '')
    {
      $theme->insertContent($form);
    }
