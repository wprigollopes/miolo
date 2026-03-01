<?php
$MIOLO->history->pop();
$lookup = new MLookup();

$MIOLO->conf->loadConf($lookup->module);

$file = $MIOLO->getModulePath($lookup->module, 'db/businessAdminLookup.php');
if ( file_exists( $file ) )
{
    $ok = $MIOLO->uses('/db/businessAdminLookup.php', $lookup->module);
}
else
{
    $ok = $MIOLO->uses('/classes/businessAdminLookup.php', $lookup->module);
}

$MIOLO->assert($ok, _M('File modules/@1/db/businessAdminLookup.php not found!<br>'.
                       'This file must implement Business@1Lookup class '.
                       'which must have a method called Lookup@2.',
                       'miolo',$lookup->module, $lookup->item));

$page->addScript('m_lookup.js');
$page->setTitle(_M('Search Window'));

$businessClass = "Business{$lookup->module}Lookup";
$lookupMethod = $lookup->autocomplete ? "AutoComplete{$lookup->item}" : "Lookup{$lookup->item}";

$object = new $businessClass();
$object->$lookupMethod($lookup);

$lookup->setContent();
