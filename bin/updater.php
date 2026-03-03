#!/usr/bin/php
<?php

if ( $argc < 2 )
{
    $message = "Usage: {$argv[0]} <update XML file>\n";
    die($message);
}

$updateDataFile = realpath($argv[1]);

$pathInfo = pathinfo($argv[0]);

$BIN_PATH = realpath($pathInfo['dirname']);
$MIOLO_PATH = realpath($BIN_PATH . '/..');

// Backup miolo.php
$confBackup = "$BIN_PATH/updater/backup/miolo.php";
$cmd = "cp $MIOLO_PATH/etc/miolo.php $confBackup";
exec($cmd, $output, $return);

if ( $return !== 0 )
{
    die("Não foi possível realizar o backup do miolo.php.\n");
}

// Set server as in maintenance (down)
$conf = require $confBackup;
if ( file_exists("$MIOLO_PATH/.down") )
{
    die("Uma outra atualização está em andamento.\n");
}
else
{
    file_put_contents("$MIOLO_PATH/.down", $conf['theme']['main']);
    $GLOBALS['MIOLO_UPDATER'] = 'updating';
}

// Load miolo.php
$tempConf = require $MIOLO_PATH . '/etc/miolo.php';

// Set modern theme to avoid unnecessary database calls
$tempConf['theme']['main'] = 'modern';
$tempConf['theme']['lookup'] = 'modern';

file_put_contents(
    "$MIOLO_PATH/etc/miolo.php",
    "<?php\n\nreturn " . var_export($tempConf, true) . ";\n"
);


$path = realpath($MIOLO_PATH . '/classes/utils');

chdir($path);
require_once 'mIOLOUpdater.php';
chdir($BIN_PATH);

$mu = new MIOLOUpdater($updateDataFile);
$mu->update();
