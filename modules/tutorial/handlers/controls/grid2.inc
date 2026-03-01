<?php

// adds an option to the navigation bar
$navbar->addOption( 'Grid', $module, $action );

// get access to the User Interface functions
$ui = $MIOLO->getUI();

// instantiates a new grid "gridList2" located in grids/gridList2.class
$grid = $ui->getGrid( $module, 'gridList2' );

// clear the content of the theme
$theme->clearContent();

// if the content is clean...
if ( $theme->getContent()  == '' )
{
    // populate the theme's content with the grid
    $theme->insertContent( $grid );
}

?>
