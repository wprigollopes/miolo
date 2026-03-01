<?php

// adds an option to the navigation bar
$navbar->addOption( 'Grid', $module, $action );

// get access to the User Interface functions
$ui = $MIOLO->getUI();

// instantiates a new grid "gridList3" located in grids/gridList3.class
$grid = $ui->getGrid( $module, 'gridList3' );

// clear the theme's content
$theme->clearContent();

// insert the $grid in the theme's content
$theme->appendContent( $grid );

?>
