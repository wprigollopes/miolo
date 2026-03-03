<?php
    $theme->clearContent();

    $navbar->addOption('WebServices', $module, $self);
    
    $ui = $MIOLO->getUI();
    $form = $ui->getForm($module, 'frmWebServices');
    
    $theme->insertContent($form);
?>