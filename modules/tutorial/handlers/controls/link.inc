<?php

// give access to UI, for instance, GetImage method
$ui = $MIOLO->GetUI();

// add option to the navigation menu
$navbar->addOption('Link Controls', $module, $action);

// space between theme elements
$theme->breakContent();

// create a panel
$panel = new MPanel('pnlName','Link Controls');

// some link examples
$link = new MLink('ilnkName', 'Label of hyperlink', MIOLO::getCurrentURL(), 'Text for the hyperlink');
$link->setOnClick("javascript:alert('Outch! You clicked me... ;-)')");

$imgLink      = new MImageLink('ilnkName1', 'Label of imagelink', MIOLO::getCurrentURL(), $ui->getImage('', 'button_edit.png') );
$imgLinkLabel = new MImageLinkLabel('ilnkName2', 'Label of imagelinklabel', MIOLO::getCurrentURL(), $ui->getImage('', 'button_edit.png') );

$controls = array( new MLabel("Hyperlink: "), $link, new MSeparator(),
                   new MLabel("ImageLink: "), $imgLink, new MSeparator(),
                   new MLabel("ImageLinkLabel: "), $imgLinkLabel, new MSeparator()
                  );

// add controls to the panel
$panel->addControl($controls, '', 'left');

// put the panel in the theme
$theme->insertContent($panel);

// create a link to view the source of this file
$src = new ViewSource(__FILE__);
$theme->appendContent($src);

?>