<?php

    $home = 'main:admin';
    
    $navbar->AddOption('Usuários',$module,$self);
    
    $ui = $MIOLO->GetUI();
    
    $form = $ui->GetForm($module,'frmUser');
    $theme->ClearContent();
    $theme->InsertContent($form);    

?>