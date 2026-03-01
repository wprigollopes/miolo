<?php

global $module;

if ( !isset($module) )
{
    $module = $MIOLO->getConf('options.startup');
}

$theme->clearContent();
$ui = $MIOLO->getUI();

$panel = new MActionPanel('panel', _M('Mobile Example', $module));
$panel->addAction(_M('Accordion', $module), $ui->getImage('example', 'controls.png'), $module, 'main:collapsible');
$theme->appendContent($panel);

if ( $MIOLO->getConf('theme.main') != 'mobile' )
{
    $message = new MLabel(_M('Altere para "mobile" a configuração do tema no miolo.conf para visualizar esse módulo corretamente.', $module));
    $popup = new MPopup('popup', array( $message ), _M('Atenção', $module));
    
    $theme->appendContent($popup);
}

$shiftAction = $context->shiftAction();

if ( $shiftAction )
{
    $MIOLO->invokeHandler($module, $shiftAction);
}
