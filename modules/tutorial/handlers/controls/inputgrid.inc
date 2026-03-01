<?php

    // adds an option to the navigation menu bar
    $navbar->addOption( 'InputGrid', $module, $action );

    // get access to UI functions
    $ui = $MIOLO->getUI();

    // instantiates frmInputGrid, located in $module/forms
    $form = $ui->getForm( $module, 'frmInputGrid' );

    // clear the content of the theme
    $theme->clearContent();

    // if no content, insert the form as content.
    // ok, ok... we cleaned the content, this 'if' is just to show 
    // the existence getContent method...  :-)
    if ( $theme->getContent() == '' )
    {
        // insert the form into the theme content
        $theme->insertContent( $form );
    }

?>
