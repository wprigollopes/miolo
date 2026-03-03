<?php

// adds an option to the navigation bar
$navbar->addOption(_M('Detail Grid'), $module, $action);

// clear the theme's content
$theme->clearContent();

// get access to the User Interface functions
$ui = $MIOLO->getUI();

// instantiates a new grid "grdDetailGrid" located in grids/grdDetailGrid.class
$grid = $ui->getGrid($module, 'grdDetailGrid');

?>