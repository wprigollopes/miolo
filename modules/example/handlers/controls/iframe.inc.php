<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('IFrame', $module, $self);
    $form = $ui->getForm($module, 'frmIFrame');
    $theme->insertContent($form);
?>
