<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('Content', $module, $self);
    $form = $ui->getForm($module, 'frmContent');
    $theme->insertContent($form);
