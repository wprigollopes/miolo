<?php

    // adds an option to the navigation bar
    $navbar->addOption( 'InputGrid', $module, $action );

    // clears the theme content
    $theme->clearContent();

    // access the User Interface functions
    $ui = $MIOLO->getUI();

    // instanciates frmInputGrid, defined in 
    // $module/forms/frmInputGrid.class
    $form = $ui->getForm($module,'frmInputGrid');

    // verify if there is no content in theme
    if ( $theme->getContent()  == '' )
    {
        // insert the form into the theme's content
        $theme->insertContent( $form );
    }

?>
