<?
    $theme->clearContent();

    $ui = $MIOLO->getUI();
    $navbar->addOption('EditMasks', $module, $self);
    $form = $ui->getForm($module, 'frmEditMask');
    $theme->insertContent($form);
?>
