<?php
$navbar->addOption(_M('Panel Controls', $module), $module, $self);

// space between the theme elements
$theme->breakContent();

//create a new panel
$panel = new MPanel('pnlDummy', _M('Panel Controls', $module));

$panel2 = new MPanel('pnlDummy2', _M('Inner Panel', $module));
$label[] = new MLabel(_M('This is a MPanel inside another MPanel', $module));
$panel2->addControl($label, '100%', 'clear');

$controls = array( new MLabel(_M('This is a MLabel inside a MPanel', $module)), $panel2 );

$panel->addControl($controls, '100%', 'clear');

// create a link to view the source of this file
$src = new ViewSource(__FILE__);

$theme->insertContent($panel);
$theme->appendContent($src);
