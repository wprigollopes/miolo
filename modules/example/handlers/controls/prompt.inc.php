<?
    $theme->clearContent();
    $ui = $MIOLO->getUI();
    $navbar->addOption('Prompt', $module, $self);
    $form = $ui->getForm($module,'frmPrompt');
    if ($theme->getContent() != '')
       $theme->appendContent($form);
    else
       $theme->insertContent($form);

?>
