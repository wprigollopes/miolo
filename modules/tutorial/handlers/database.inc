<?php

$ui = $MIOLO->getUI();
$navbar->addOption(_M('Database', $module), $module, 'main:database');
$theme->clearContent();
$theme->insertContent(new MContentHeader('Tutorial', _M('Database', $module)));
$close = $MIOLO->getActionURL($module, 'main');
$icon = $ui->getImage('', 'button_edit.png');
$frmPanel = new ActionPanel('pnlDatabase', _M('Database', $module), '', $close, $icon);
$frmPanel->addAction('MDatabase', $ui->getImage('', 'button_edit.png'), $module, 'main:database:database');
$frmPanel->addAction('MDbal', $ui->getImage('', 'button_edit.png'), $module, 'main:database:dbal');
$frmPanel->addAction(_M('Custom Fields', $module), $ui->getImage('', 'button_edit.png'), $module, 'main:database:customfieldexample');
$theme->appendContent($frmPanel);
$handled = $MIOLO->invokeHandler($module, 'database/' . $context->shiftAction());

?>
