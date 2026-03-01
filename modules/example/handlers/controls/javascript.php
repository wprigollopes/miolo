<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('Javascript', $module, $self);
    $form = $ui->getForm($module, 'frmJavascript');
    $theme->insertContent($form);
