<?php

// import ViewSource Class
$MIOLO->import('modules::example::controls::viewsource');

// how to change the page Title in the browser window
$MIOLO->page->setTitle(_M('Miolo Web Controls', $module));

$ui = $MIOLO->getUI();

// adds an option to the navigation bar
$navbar->addOption( _M('Controls', $module), $module, 'main:controls' );

$theme->clearContent();

$close = $MIOLO->getActionURL( $module, 'main' );
$icon = $ui->getImage( '', 'button_edit.png' );

// creates a new panel
$ctlPanel = new MActionPanel( 'pnlcontrols', _M('Controls', $module), '', $close, $icon );
$ctlPanel->setIconType( 'small' );

// adds actions to the panel
$actions['blockcontrols'] = _M('Block Controls', $module);
$actions['panels'] = _M('Panel Controls', $module);
$actions['link'] = _M('Link Controls', $module);
$actions['image'] = _M('Image Controls', $module);
$actions['labels'] = _M('Label Controls', $module);
$actions['buttons'] = _M('Button Controls', $module);
$actions['input'] = _M('Input Controls', $module);
$actions['currency'] = _M('Currency Field', $module);
$actions['choices'] = _M('Choice Controls', $module);
$actions['lists'] = _M('List Controls', $module);
$actions['groups'] = _M('Group Controls', $module);
$actions['indexed'] = _M('Indexed Control', $module);
$actions['inputgrid'] = _M('InputGrid Control', $module);
$actions['box'] = _M('Box', $module);
$actions['grid1'] = _M('Simple Array Grid', $module);
$actions['grid2'] = _M('Array Grid', $module);
$actions['grid3'] = _M('Full Array Grid', $module);
$actions['grid5'] = _M('Two Grids', $module);
$actions['detailgrid'] = _M('Detail Grid', $module);
$actions['multitext3'] = _M('MultiText3', $module);
$actions['multitext4'] = _M('MultiText4', $module);
$actions['lookup'] = _M('Lookup', $module);
$actions['multiselectionfield'] = _M('MultiSelection', $module);
$actions['container'] = _M('Container', $module);
$actions['content'] = _M('Content', $module);
$actions['filecontent'] = _M('FileContent', $module);
$actions['prompt'] = _M('Prompt', $module);
$actions['themebox'] = _M('ThemeBox', $module);
$actions['simpletable'] = _M('SimpleTable', $module);
$actions['tableraw'] = _M('TableRaw', $module);
$actions['texttable'] = _M('TextTable', $module);
$actions['scrollablediv'] = _M('Scrollable Div', $module);
$actions['hint'] = _M('Tips (hints)', $module);
$actions['javascript'] = _M('Javascript', $module);
$actions['editor'] = _M('Editor', $module);
$actions['iframe'] = _M('IFrame', $module);
$actions['editmask'] = _M('Edit Masks', $module);
$actions['toolbar'] = _M('ToolBar', $module);
$actions['accordion'] = _M('Accordion', $module);
$actions['events'] = _M('Events', $module);
$actions['tabcontainer'] = _M('TabContainer', $module);
$actions['message'] = _M('Messages', $module);
$actions['captcha'] = _M('Captcha', $module);
$actions['expanddiv'] = _M('Expand Div', $module);
$actions['lightbox'] = _M('Lightbox', $module);
$actions['tabbedbasegroup'] = _M('Tabbed Base Group', $module);
$actions['timestampfield'] = _M('Timestamp', $module);
$actions['popup'] = _M('Popup', $module);
$actions['specialgrid'] = _M('Special Grid', $module);
$actions['upload'] = _M('Upload', $module);
$actions['subdetail'] = _M('SubDetail', $module);
$actions['gridlimitoffset'] = _M('Grid using LIMIT/OFFSET', $module);
$actions['duallist'] = _M('Dual List', $module);
$actions['dialog'] = _M('Dialog', $module);

asort($actions);

// Set the new label for recent controls
$actions['gridlimitoffset'] = array($actions['gridlimitoffset'], _M('New', $module));
$actions['duallist'] = array($actions['duallist'], _M('New', $module));
$actions['dialog'] = array($actions['dialog'], _M('New', $module));

foreach ( $actions as $action => $data )
{
    list($title, $new) = (array) $data;
    $ctlPanel->addAction($title, $ui->getImage('', 'button_edit.png'), $module, "controls:$action");

    if ( $new )
    {
        $new = new MSpan(NULL, $new);
        $new->addStyle('font-size', '8px');
        $new->addStyle('letter-spacing', '1px');
        $new->addStyle('margin-left', '4px');
        $new->addStyle('vertical-align', 'top');
        $new->addStyle('color', '#C0C000');

        $last = end($ctlPanel->getControls());
        $last->inner .= $new->generate();
    }
}

// appends the panel to the theme
$theme->appendContent($ctlPanel);

// invoke the next handler
if ( $a = $context->shiftAction() )
{
    $handled = $MIOLO->invokeHandler($module, 'controls/' . $a);
}

?>
