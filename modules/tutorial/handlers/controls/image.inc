<?php

// give access to UI, for instance, GetImage method
$ui = $MIOLO->getUI();

// add option to the navigation bar
$navbar->addOption( 'Image Controls', $module, $action );

// insert space between theme elements
$theme->breakContent();

// create a new pane.
$panel = new MPanel( 'pnlName', 'Image Controls' );

// create some images

// get an image located in the html/images dir
// the first parameter indicates the module or miolo's html/images by default
$imgForm      = new MImage( 'ifrmName', 'Label of imageform', $ui->getImage('','attention.png') );
$imgFormLabel = new MImageFormLabel( 'ifrmName1', 'Label of imageformlabel', $ui->getImage('','information.png') );

$controls = array( new MLabel("ImageForm: "), $imgForm, new MSeparator(),
                   new MLabel("ImageFormLabel: "), $imgFormLabel, new MSeparator()
                  );

// add the controls to the panel
$panel->addControl( $controls,'','clear' );

// insert panel into the theme
$theme->insertContent($panel);

// create a link to view the source of this file
$src = new ViewSource( __FILE__ );
$theme->appendContent( $src );

?>