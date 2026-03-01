<?php
require_once __DIR__ . '/../vendor/autoload.php';

header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0


$theme_layout = 'lookup';
include_once '../etc/miolo/miolo.conf';

// capture some statistics
$MIOLO->trace("HTTP_REFERER='" . $_SERVER['HTTP_REFERER'] . "'");
$MIOLO->trace("HTTP_USER_AGENT='".$_SERVER['HTTP_USER_AGENT']."'");

$MIOLO->uses('lookup.php');
$MIOLO->uses('history.php');
$MIOLO->uses('database/sql.php');
$MIOLO->uses('ui/controls/basecontrols.php');
$MIOLO->uses('ui/controls/webcontrols.php');
$MIOLO->uses('ui/controls/labelcontrols.php');
$MIOLO->uses('ui/controls/buttoncontrols.php');
$MIOLO->uses('ui/controls/fieldcontrols.php');
$MIOLO->uses('ui/controls/listcontrols.php');
$MIOLO->uses('ui/controls/gridcontrols.php');

$MIOLO->uses('ui/controls/pagenavigator.php');
$MIOLO->uses('ui/controls/range.php');
$MIOLO->uses('ui/controls/gridnavigator.php');
$MIOLO->uses("ui/controls/datagrid.php");

$lookup = new Lookup();

if (!preg_match('/^[a-zA-Z0-9_]+$/', $lookup->module)) {
    http_response_code(400);
    die('Invalid module name');
}

$ok = $MIOLO->uses('/classes/lookup.php',$lookup->module);

$MIOLO->assert($ok,_M('File modules/@1/db/lookup.php not found!<br>'.
                      'This file must implement Business@1Lookup class '.
                      'which must have a method called Lookup@2.',
                      'miolo',$lookup->module, $lookup->item));

$page->addScript('m_lookup.js');
$page->setTitle('Janela de Pesquisa');
//$filter = $MIOLO->_REQUEST('filter');



//$lookup->setForm(new Form('Filter'));

//if ($fvalue)
//{
//   $lookup->filterValue = $fvalue;
//}

$className = 'Business' . $lookup->module . 'Lookup';
if (!preg_match('/^[a-zA-Z0-9_]+$/', $lookup->module) || !class_exists($className)) {
    http_response_code(400);
    die('Invalid module');
}
$object = new $className();

$method = 'lookup' . $lookup->item;
if (!preg_match('/^[a-zA-Z0-9_]+$/', $lookup->item) || !method_exists($object, $method)) {
    http_response_code(400);
    die('Invalid item');
}
$object->$method($lookup);

//$filterForm = & $lookup->getForm();

//$filterFields = $lookup->getFilterFields();
//if ( ! $filterFields )
//{
//    $filterFields = new TextField('filter','Filtro',$filtro,30);
//}
//$filterForm->addButton(new FormButton('btnPost', 'Pesquisar'));
//$filterForm->addButton(new FormButton('btnClose', 'Fechar', 'window.close()'));



//foreach( $filterFields as $f )
//{
//   $url .= "&$f="   . urlencode($MIOLO->_REQUEST($f));
//}

$page->setAction($url);

//$listing = new DataGrid2(
//    $lookup->query,
//    $lookup->columns,
//    $url,
//    $lookup->getPageLength(),
//    $lookup->keyColumn
//);

//foreach( $filterFields as $f )
//{
//    $listing->addFilterText($f->name,$f->label,$f->value, $f->name);
//}
//$listing->setFilter(true);

//$listing->setTitle($lookup->listingTitle);
//$content = array($lookup->getForm(),$listing);
$theme->setContent($lookup->grid);

//$MIOLO->generateTheme('lookup');

$page->generate();
