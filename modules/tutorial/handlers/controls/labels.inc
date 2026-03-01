<?php

//$ui = $MIOLO->GetUI();

//add an option to the navigation bar
$navbar->addOption( 'Label Controls', $module, $action );

// insert a space between the theme elements
$theme->breakContent();

// create new panel
$panel = new MPanel( 'pnlDummy', 'Label Controls' );

// create some label samples
$comment   = new MPageComment( "Invisible. You'll see this text as a commento in the generated html page." );
$separator = new MSeparator( "I'm a separator with some usefull text ;-)" );

$label = new MLabel( 'A text for label');
$text  = new MText( 'txtName', 'A text for the control', 'blue' );

$header1 = new MTextHeader( 'hdrName1', '1', 'A header H1','green' );
$header2 = new MTextHeader( 'hdrName2', '2', 'A header H2','yellow' );
$textLabel = new MTextLabel( 'txtLabel', 'A text for the control', 'A label', 'red' );

$controls  = array( new MLabel( "PageComment: it's invisible... "), $comment, new MSeparator(),
                    $separator,
                    new MLabel( 'MLabel: ' ), $label, new MSeparator(),
                    new MLabel( 'MText: ' ), $text, new MSeparator(),
                    new MLabel( 'MTextHeader 1: ' ), $header1, new MSeparator(),
                    new MLabel( 'MTextHeader 2: ' ), $header2, new MSeparator(),
                    new MLabel( 'MTextLabel: ' ), $textLabel, new MSeparator()
                   );

// add the controls to the panel
$panel->addControl( $controls, '', 'left' );
$theme->insertContent( $panel );

// create a link to view the source of this file
$src = new ViewSource( __FILE__ );
$theme->appendContent( $src );

?>