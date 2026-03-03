<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('ISR', $module, $self);
    $form = $ui->getForm($module,'frmISR');
    $theme->insertContent($form);
?>
