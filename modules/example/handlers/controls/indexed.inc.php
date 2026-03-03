<?php

    // adds an option to the navigation menu bar
    $navbar->addOption( 'Indexed Controls', $module, $action );

    // get access to UI methods (GetForm)
    $ui = $MIOLO->getUI();

    // instantiates frmIndex, located in $module/forms
    $form = $ui->getForm( $module,'frmIndexed' );

    // clear the theme content
    $theme->clearContent();

    // if no content, insert the form as content.
    // ok, ok... we cleaned the content, this if is just to show 
    // the existence getContent method...  :-)
    if ( $theme->getContent()  == '' )
    {
        // insert the form into the theme content
        $theme->insertContent($form);
    }

?>
