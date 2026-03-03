<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\MIOLO;

$MIOLO = MIOLO::getInstance();
$MIOLO->handlerRequest();
