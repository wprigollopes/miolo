<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('LiveLookup', $module, $self);
    $form = $ui->getForm($module,'frmLiveLookup');
    $theme->insertContent($form);
?>
