<?php
$MIOLO->history->pop();
$lookup = new MLookup($module);
$file = $MIOLO->getModulePath($lookup->module,'classes/lookup.class');
if ( file_exists( $file ) )
{
   $ok = $MIOLO->uses('/classes/lookup.class',$lookup->module);
}
$MIOLO->assert($ok,_M('File not found', $lookup->module));
$page->addScript('m_lookup.js');
$page->setTitle(_M('Lookup Window'), MIOLO::getCurrentModule());
$businessClass = "Business{$lookup->module}Lookup";
$lookupMethod = "Lookup{$lookup->item}";
$object = new $businessClass();
$object->$lookupMethod($lookup);
$page->setAction($lookup->href);
$theme->setContent($lookup->grid);
$theme->setLayout('lookup');
