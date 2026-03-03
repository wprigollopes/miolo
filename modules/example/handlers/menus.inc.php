<?php

$ui = $MIOLO->getUI();
$navbar->addOption('Menus', $module, 'main:menus');
$theme->clearContent();
$close = $MIOLO->getActionURL($module, 'main');
$icon = $ui->getImage('', 'button_edit.png');
$frmPanel = new MActionPanel('pnlMenus', _M('Menus', $module), '', $close, $icon);

$actions = array( );
$actions['contextmenu'] = _M('Context Menu', $module);
$actions['menu'] = _M('Menu', $module);
$actions['staticmenu'] = _M('Static Menu', $module);
$actions['treemenu'] = _M('Tree Menu', $module);

asort($actions);

foreach ( $actions as $action => $title )
{
    $frmPanel->addAction($title, $ui->getImage('', 'button_edit.png'), $module, "menus:$action");
}

$theme->appendContent($frmPanel);
$handled = $MIOLO->invokeHandler($module, 'menus/' . $context->shiftAction());

?>
