<?php
// add a option in the navigation bar
$navbar->addOption( 'Block Controls', $module, $action );

//$ui = $MIOLO->GetUI();

// insert a break between theme elements
$theme->breakContent();

// create a new panel
$panel = new MPanel( 'pnlDummy', 'Block Controls' );

// new span
$span  = new MSpan( '', 'Span Content' );
$span->addStyle( 'color', 'blue' );

// div with 
$div = new MDiv( '', 'A DIV with a few text' );
$div->addStyle( 'float', 'right' );
$div->addStyle( 'backgroundColor', '#ACC' );

// new div wich'll be used as internal content in div3
$div2 = new MDiv( '', 'Internal' );
$div2->addStyle( 'backgroundColor','#999900' );
$div2->addStyle( 'width','100px' );
$div2->addStyle( 'color','#FFF' );

// new div with $div2 as content
$div3 = new MDiv( '', $div2 );
$div3->addStyle( 'backgroundColor','#DDD' );
$div3->addStyle( 'width','300px' );
$div3->addStyle( 'padding','5px' );
$div3->addStyle( 'float','left' );

        $fields = array( new CheckBox( 'chkBox', 'value', 'CheckBox', true, 'Text' ),
                         new RadioButton( 'radioButton', 'value', 'RadioButton', true, 'Text' ),
                         new ComboBox( 'combo', '', 'ComboBox', array('yes','no'), false, '', 10 ),
                         new Selection( 'select1', 'b', 'Select', array('yes','no'), false )
                        );

$div4 = new MDiv( '', $fields );

$controls = array( new MText('', 'A Span with style: '), $span, new MSeparator(),
                   new MText('', 'A Div with float:right and bgcolor: '), $div, new MSeparator(),
                   new MText('', 'A nested Div: '), $div3, $div4
                  );

$panel->addControl( $controls, '', 'clear' );

// insert the $panel to the theme
$theme->insertContent( $panel );

// create a link to view the source of the file
$src = new ViewSource( __FILE__ );

$theme->appendContent( $src );

?>