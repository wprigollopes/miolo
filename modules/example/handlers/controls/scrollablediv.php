<?
    $ui = $MIOLO->getUI();
    $theme->clearContent();
    $navbar->addOption('Scrollable Div', $module, $self);
    $form = $ui->getForm($module,'frmScrollableDiv');
    if ($theme->getContent()  == '')
    {
      $theme->insertContent($form);
    }
