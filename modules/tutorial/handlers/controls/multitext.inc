<?php

// add an option to the navigation bar
$navbar->addOption('MultiText', $module, $action);

// clear the content of the page (theme)
$theme->clearContent();

// obtaion access to the User Interface classes
$ui = $MIOLO->getUI();

// instanciates a new form, defined in tutorial/forms/frmMultiText.class
$form = $ui->getForm($module, 'frmMultiText');

// put the form into the theme
$theme->insertContent($form);

// create a link to view the source of this file
$src = new ViewSource(__FILE__);
$theme->appendContent($src);

?>