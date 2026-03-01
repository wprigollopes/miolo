<?php
    // Get access to the User Interface classes/methods.
    // We need getUI to be able to call getForm
    $ui = $MIOLO->GetUI();

    // instantiate the form frmMain located in 
    // the modules/helloworld/helloworld
    $form = $ui->GetForm('helloworld','frmMain');

    // set the $form as the theme content
    $theme->SetContent($form);
?>