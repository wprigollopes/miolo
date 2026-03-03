<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Database', $module, $self);
    $form = $ui->getForm($module,'frmDatabase');
    $theme->insertContent($form);
?>
