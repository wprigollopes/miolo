<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('Validators', $module, $self);
    $form = $ui->getForm($module, 'frmValidators');
    $theme->insertContent($form);
