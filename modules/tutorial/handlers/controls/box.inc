<?php
    // add an option to the navigation bar
    $navbar->addOption( 'Box', $module, $action );
    // inserts a space in the theme
    $theme->breakContent();

    // create a link to the conto
    $close = $MIOLO->getActionURL( $module,'main:controls' );
    // create a box container
    $box = new MBox( 'Title for Example Box', $close );
    //add a MLabel into the box
    $box->addControl( new MLabel('A MLabel control with dummy content...') );

    // gets access to the User Interface functions
    $ui = $MIOLO->getUI();
    // add an image button to the box
    // get an image located in the html/images dir
    // the first parameter indicates the module or miolo's html/images by default
    $img = $ui->getImage( '', 'button_edit.png' );
    $imgButton = new MImageButton( 'imgButton', 'A label for imagebutton', $close, $img );
    $box->addControl( $imgButton );

    // add the box in the top of the theme contet (before other content) 
    $theme->insertContent( $box );

    // create a link to view the source of this file
    $src = new ViewSource( __FILE__ );
    $theme->appendContent( $src );
?>
