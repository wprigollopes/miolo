<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('Container', $module, $self);
    $form = $ui->getForm($module, 'frmContainer');
    $theme->insertContent($form);
