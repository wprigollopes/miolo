<?php
$ui = $MIOLO->getUI();
$navbar->addOption(_M('Forms', $module), $module, 'main:forms');
$theme->clearContent();
$close = $MIOLO->getActionURL($module, 'main');
$icon = $ui->getImage('', 'button_edit.png');

$frmPanel = new MActionPanel('pnlForms', _M('Forms', $module), '', $close, $icon);
$frmPanel->addAction(_M('Form', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:form');
$frmPanel->addAction(_M('Form Zebra AutoPost', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:formzebra');
$frmPanel->addAction(_M('Form HV Visibility', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:formHV');
$frmPanel->addAction(_M('Form-HV Containers', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:formHV2');
$frmPanel->addAction(_M('Complex Layout', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:formComplex');
$frmPanel->addAction(_M('Tabbed Form', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:tabbed');
$frmPanel->addAction(_M('Relative CSS Form', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:css');
$frmPanel->addAction(_M('Complex Layout CSS', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:formComplex2');
$frmPanel->addAction(_M('Step by Step', $module), $ui->getImage($module, 'forms.png'), $module, 'main:forms:stepbystep');

$theme->appendContent($frmPanel);
$handled = $MIOLO->invokeHandler($module, 'forms/' . $context->shiftAction());
?>
