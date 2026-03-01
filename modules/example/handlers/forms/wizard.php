<?
    $ui = $MIOLO->getUI();
    $navbar->addOption('Wizard Sample', $module, $self);
// a simple example of a wizard built with modal dialogs
    $step = $item;
    switch ($step)
    {
         case '1': $form = $ui->getForm($module,'frmWizard1','','wizard');
                   break;
         case '2': $form = $ui->getForm($module,'frmWizard2','','wizard');
                   break;
         case '3': $form = $ui->getForm($module,'frmWizard3','','wizard');
                   break;
    }
// Dialogs are showed at 'dialog' container of theme
    $theme->setContent($form);
