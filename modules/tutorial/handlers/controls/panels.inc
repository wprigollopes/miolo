<?php

//$ui = $MIOLO->GetUI();
$navbar->addOption('Panel Controls', $module, $self);

// space between the theme elements
$theme->breakContent();

//create a new panel
$panel  = new MPanel('pnlDummy','Panel Controls');

$panel2 = new MPanel('pnlDummy2','Inner Panel');

$panel2->addControl( new MLabel("I'm a MLabel inside a MPanel 2!") );

$controls = array( new MLabel("Panel 1: Uh! I'm a MLabel inside a MPanel 1!"),
                   $panel2 );

$panel->addControl($controls,'100%','clear');

// create a link to view the source of this file
$src = new ViewSource(__FILE__);

$theme->insertContent($panel);
$theme->appendContent($src);

?>