<?php
    // Get access to the User Interface classes/methods.
    // We need getUI to be able to call getForm
    $ui = $MIOLO->getUI();

    // instantiate the form frmMain located in 
    // the modules/helloworld/helloworld
    $form = $ui->getForm('helloworld','frmMain');

    // set the $form as the theme content
    $theme->setContent($form);
