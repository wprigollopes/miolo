<?php

// adds an option to the navigation bar
$navbar->addOption( 'Grid', $module, $action );

// get access to the User Interface functions
$ui = $MIOLO->getUI();

// instantiates a new grid "gridList1"located in grids/gridList1.class
$grid = $ui->getGrid( $module, 'gridList1' );

// clear the theme content
$theme->clearContent();

if ( $theme->getContent()  == '' )
{
    // populate the theme's content with the grid
    $theme->insertContent( $grid );
}

?>
