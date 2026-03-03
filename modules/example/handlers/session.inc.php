<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('Session', $module, $self);
    $formSession = $ui->getForm($module, 'frmSession');
    $theme->insertContent($formSession);
?>
