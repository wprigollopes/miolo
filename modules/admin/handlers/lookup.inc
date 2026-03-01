<?php
$MIOLO->history->pop();
$lookup = new MLookup();

$text   = MIOLO::_Request('selText');
if ( $text && $text != 'undefined')
{
    $lookup->setSelectionText( $text );
}
$MIOLO->conf->loadConf( $lookup->module );
$MIOLO->getTheme( $MIOLO->GetConf('theme.lookup') );

$file = $MIOLO->GetModulePath($lookup->module,'db/lookup.class');
if ( file_exists( $file ) )
{
   $ok = $MIOLO->Uses('/db/lookup.class',$lookup->module);
}
else
{
   $ok = $MIOLO->Uses('/classes/lookup.class',$lookup->module);
}
$MIOLO->Assert($ok,_M('File modules/@1/classes/lookup.class not found.', 'admin') . '<br/>' .
                   _M('This file must implement Business@1Lookup class', 'admin', $lookup->module) .
                   _M('containing the Lookup@1 method.','admin', $lookup->item) );
$page->AddScript('m_lookup.js');
$page->SetTitle(_M('Query window'));
$businessClass = "Business{$lookup->module}Lookup";
$lookupMethod = "Lookup{$lookup->item}";

$object = new $businessClass();
$object->$lookupMethod($lookup);
$page->SetAction($lookup->href);

$theme = $MIOLO->GetTheme($MIOLO->GetConf('theme.lookup'),'lookup');
$theme->SetContent($lookup->generate( ));
$theme->SetLayout('lookup');
?>
