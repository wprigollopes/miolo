<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Dialog Prompt', $module, $self);
    $form = $ui->getForm($module,'frmDlgPrompt');
    $theme->insertContent($form);

?>
