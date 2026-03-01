<?php

    // add an option to the navigation bar
    $navbar->addOption( 'Two Grids', $module, $action );

    // get access to the User Interface functions
    $ui = $MIOLO->getUI();

    // create 2 grids, from gridList5.class located in grids/gridList5.class
    $grid1 = $ui->getGrid( $module, 'gridList5', 'gridOne' );
    $grid2 = $ui->getGrid( $module, 'gridList5', 'gridTwo' );

    // clear the theme content
    $theme->clearContent();

    // insert the $grid1 into the theme content
    $theme->appendContent( $grid1 );

    // insert a space between the grids
    $theme->appendContent( new MSpacer( '15px' ) );

    // insert the $grid2 into the theme content
    $theme->appendContent( $grid2 );
?>
