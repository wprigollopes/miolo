<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('FileContent', $module, $self);
    $form = $ui->getForm($module, 'frmFileContent');
    $theme->insertContent($form);
