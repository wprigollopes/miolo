<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Lookup', $module, $self);
    $form = $ui->getForm($module,'frmLookup');
    $theme->insertContent($form);
