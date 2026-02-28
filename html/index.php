<?php
require_once __DIR__ . '/../vendor/autoload.php';

// FIXME: use this only when debugging
// ensure no caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                                    // HTTP/1.0
header("X-Robots-Tag: noindex, nofollow", true); //Dissalow index

//var_dump($_POST);

ini_set('display_errors', '1');

require_once '../classes/miolo.class.php';
$MIOLO = MIOLO::getInstance();
/* @var *MIOLO MIOLO */
$MIOLO->handlerRequest();

?>

