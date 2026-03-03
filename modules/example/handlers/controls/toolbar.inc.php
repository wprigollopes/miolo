<?php

    // adds an option to the navigation bar
    $navbar->addOption( 'ToolBar', $module, $action );

    // get access to the User Interface
    $ui = $MIOLO->getUI();

    // instanciates a new frmToolBar form
    // wich is located in <modules>/$module/forms
    $formSave = $ui->getForm( $module, 'frmToolBar' );

    // clear the content of the theme
    $theme->clearContent();

    // verify if the content is empty
    // ok, ok... this is not necessary... is only to show you the method... ;-)
    if ( $theme->getContent()  == '' )
    {
        // insert the form into the theme's content
        $theme->insertContent( $formSave );
    }

?>
