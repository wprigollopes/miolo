<?php
$navbar->setHome($module);
$navbar->setLabelHome(_M('Miolo Generator', $module));
$navbar->addOption(_M('Main', $module), $module, 'main');

$theme->clearContent();

$tutPanel = new MActionPanel('pnlgenerator', _M('Miolo Generator', $module));

$ui = $MIOLO->getUI();

$tutPanel->addAction(_M('Generate', $module), $ui->getImage($module, 'generator-32x32.png'), $module, 'main:generate');
$tutPanel->addAction(_M('Templates', $module), $ui->getImage($module, 'template-32x32.png'), $module, 'main:template');

$theme->appendContent($tutPanel);

$shiftAction = $context->shiftAction();

if ( $shiftAction )
{
    $handled = $MIOLO->invokeHandler($module, $shiftAction);
}
?>
