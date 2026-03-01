<?php

// import ViewSource Class
$MIOLO->Import('modules::tutorial::controls::viewsource');

$ui = $MIOLO->GetUI();

// adds an option to the navigation bar
$navbar->AddOption( _M('Controls'), $module, 'main:controls' );

$theme->ClearContent();

$close = $MIOLO->GetActionURL( $module, 'main' );
$icon = $ui->GetImage( '', 'button_edit.png' );

// creates a new panel
$ctlPanel = new MActionPanel( 'pnlcontrols', _M('Controls'), '', $close, $icon );
$ctlPanel->SetIconType( 'small' );

// adds actions to the panel
$ctlPanel->AddAction( 'Block Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:blockcontrols' );
$ctlPanel->AddAction( 'Panel Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:panels' );
$ctlPanel->AddAction( 'Link Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:link' );
$ctlPanel->AddAction( 'Image Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:image' );
$ctlPanel->AddAction( 'Label Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:labels' );
$ctlPanel->AddAction( 'Button Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:buttons' );
$ctlPanel->AddAction( 'Input Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:input' );
$ctlPanel->AddAction( 'Currency Field', $ui->GetImage('','button_edit.png'), $module, 'main:controls:currency' );
$ctlPanel->AddAction( 'Choice Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:choices' );
$ctlPanel->AddAction( 'List Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:lists' );
$ctlPanel->AddAction( 'Group Controls', $ui->GetImage('','button_edit.png'), $module, 'main:controls:groups' );
$ctlPanel->AddAction( 'Indexed Control', $ui->GetImage('','button_edit.png'), $module, 'main:controls:indexed' );
$ctlPanel->AddAction( 'InputGrid Control', $ui->GetImage('','button_edit.png'), $module, 'main:controls:inputgrid' );
$ctlPanel->AddAction( 'Box', $ui->GetImage('','button_edit.png'), $module, 'main:controls:box' );
$ctlPanel->AddAction( 'Simple Array Grid', $ui->GetImage('','button_edit.png'), $module, 'main:controls:grid1' );
$ctlPanel->AddAction( 'Array Grid', $ui->GetImage('','button_edit.png'), $module, 'main:controls:grid2' );
$ctlPanel->AddAction( 'Full Array Grid', $ui->GetImage('','button_edit.png'), $module, 'main:controls:grid3' );
$ctlPanel->AddAction( 'Two Grids', $ui->GetImage('','button_edit.png'), $module, 'main:controls:grid5' );
$ctlPanel->AddAction( 'MultiText', $ui->GetImage('','button_edit.png'), $module, 'main:controls:multitext' );
$ctlPanel->AddAction( 'Lookup', $ui->GetImage('','button_edit.png'), $module, 'main:controls:lookup' );
$ctlPanel->AddAction( 'MultiSelection', $ui->GetImage('','button_edit.png'), $module, 'main:controls:multiselectionfield' );
$ctlPanel->AddAction( 'Container', $ui->GetImage('','button_edit.png'), $module, 'main:controls:container' );
$ctlPanel->AddAction( 'Content', $ui->GetImage('','button_edit.png'), $module, 'main:controls:content' );
$ctlPanel->AddAction( 'FileContent', $ui->GetImage('','button_edit.png'), $module, 'main:controls:filecontent' );
$ctlPanel->AddAction( 'Prompt', $ui->GetImage('','button_edit.png'), $module, 'main:controls:prompt' );
$ctlPanel->AddAction( 'ThemeBox', $ui->GetImage('','button_edit.png'), $module, 'main:controls:themebox' );
$ctlPanel->AddAction( 'SimpleTable', $ui->GetImage('','button_edit.png'), $module, 'main:controls:simpletable' );
$ctlPanel->AddAction( 'TableRaw', $ui->GetImage('','button_edit.png'), $module, 'main:controls:tableraw' );
$ctlPanel->AddAction( 'Scrollable Div', $ui->GetImage('','button_edit.png'), $module, 'main:controls:scrollablediv' );
$ctlPanel->AddAction( 'Tips (hints)', $ui->GetImage('','button_edit.png'), $module, 'main:controls:hint' );
$ctlPanel->AddAction( 'Javascript', $ui->GetImage('','button_edit.png'), $module, 'main:controls:javascript' );
$ctlPanel->AddAction( 'TreeMenu', $ui->GetImage('','button_edit.png'), $module, 'main:controls:treemenu' );
$ctlPanel->AddAction( 'HtmlArea', $ui->GetImage('','button_edit.png'), $module, 'main:controls:htmlarea' );
$ctlPanel->AddAction( 'IFrame', $ui->GetImage('','button_edit.png'), $module, 'main:controls:iframe' );
$ctlPanel->AddAction( 'ActiveGrid', $ui->GetImage('','button_edit.png'), $module, 'main:controls:grid4' );
$ctlPanel->AddAction( 'ActiveLookup', $ui->GetImage('','button_edit.png'), $module, 'main:controls:activelookup' );
$ctlPanel->AddAction( 'ToolBar', $ui->GetImage('','button_edit.png'), $module, 'main:controls:toolbar' );
$ctlPanel->AddAction( 'SubDetail', $ui->GetImage('','button_edit.png'), $module, 'main:controls:subdetail' );
$ctlPanel->AddAction( 'Editor', $ui->GetImage('','button_edit.png'), $module, 'main:controls:editor' );
$ctlPanel->AddAction(_M('Message', $module), $ui->GetImage('','button_edit.png'), $module, 'main:controls:message');
$ctlPanel->AddAction(_M('Popup', $module), $ui->GetImage('','button_edit.png'), $module, 'main:controls:popup');
$ctlPanel->AddAction(_M('Tabs', $module), $ui->GetImage('','button_edit.png'), $module, 'main:controls:tabbedbasegroup');
$ctlPanel->AddAction(_M('Event Calendar', $module), $ui->GetImage('','button_edit.png'), $module, 'main:controls:eventcalendar');
$ctlPanel->AddAction(_M('Expand Div', $module), $ui->GetImage('','button_edit.png'), $module, 'main:controls:expanddiv');
$ctlPanel->AddAction(_M('Date and time', $module), $ui->GetImage('','button_edit.png'), $module, 'main:controls:datetimefield');
$ctlPanel->AddAction(_M('Chart', $module), $ui->GetImage('', 'button_edit.png'), $module, 'main:controls:chart');

// appends the panel to the theme
$theme->AppendContent($ctlPanel);

// invoke the next handler
$handled = $MIOLO->InvokeHandler($module, 'controls/' . $context->ShiftAction());

// create a link to view the source of this file
//$src = new ViewSource(__FILE__);
//$theme->appendContent($src);

?>
