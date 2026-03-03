<?php

global $MIOLO;
if ( !isset($MIOLO) || !$MIOLO || !$MIOLO->getConf('options.debug') )
{
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
    ini_set("log_errors","on");
    ini_set("error_log","../var/log/php_error.log");
}

#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# Support for gettext()
#---------------------------------------------------------------------
function setDomainLocale($domain)
{
    global $TEXTDOMAIN;
    $MIOLO = MIOLO::getInstance();
    $TEXTDOMAIN = $domain;
    
    $MIOLO->setConf("i18n.db_client_encoding", 'LATIN1');
    

    $lang = $MIOLO->getConf('i18n.language');
    $charset = $MIOLO->getConf('options.charset');
    $localedir = $MIOLO->getConf('i18n.locale');

    $locale = "$lang.$charset";
    $fallback1 = "$lang.utf8";
    $fallback2 = "$lang.UTF-8";

    /*
     * Try to set the language with the encoding, if it does not exist, try only the language and then, if nothing else 
     * worked, try the default UTF-8 locales. This is important to make MIOLO work with the configured language. The
     * encoding itself does not matter here, it is changed below to the one defined on the configuration file.
     */
    $locale = setlocale(LC_ALL, $locale, $lang, $fallback1, $fallback2);

    bind_textdomain_codeset($TEXTDOMAIN, $charset);
    bindtextdomain($TEXTDOMAIN, $localedir);
    textdomain($TEXTDOMAIN);

    // FIXME: check if this is necessary. Maybe it is for using MIOLO on console.
    if ( $TEXTDOMAIN == 'miolo' )
    {
        putenv("LC_ALL=$locale");
    }
    
}

if (function_exists('_') && class_exists('MIOLO', false) && MIOLO::getInstance() !== null)
{
    setDomainLocale('miolo');
}

#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# This function is a wrapper for the gettext support. In addition to
# the standard gettext() function, it provides support for up to three
# positional parameters represented by the placeholders <code>@1</code>,
# <code>@2</code> and <code>@3</code>.
#
# @example
# echo _M('@1 is a romance written by @2 in @3','foo','Tom Sawyer','Mark Twain','1890');
# // will result in:
# //   "Tom Sawyer is a romance written by Mark Twain in 1890"
#
# echo _M('@2 escreveu o romance @1 em @3','foo','Tom Sawyer','Mark Twain','1890');
# //
# // will result in:
# //   "Mark Twain escreveu o romance Tom Sawyer em 1890"
#---------------------------------------------------------------------
function _M($msg, $dom = 'miolo', $p1 = null, $p2 = null, $p3 = null)
{
    global $TEXTDOMAIN, $MIOLOCONF;

    if ($msg == '')
    {
        return $msg;
    }

    // Done this way to handle messages coming from miolo20 with accented characters #49910
    if ( mb_detect_encoding($msg.'x', 'UTF-8, ISO-8859-1') == 'ISO-8859-1' )
    {
        $msg = mb_convert_encoding($msg, 'UTF-8', 'ISO-8859-1');
    }

    if (function_exists('_'))
    {
        if ($dom != $TEXTDOMAIN)
        {
            setDomainLocale($dom);
        }
        $msg = _($msg);
    }

    if ($p1 !== null)
    {
        $msg = str_replace('@1', $p1, $msg);
    }

    if ($p2 !== null)
    {
        $msg = str_replace('@2', $p2, $msg);
    }

    if ($p3 !== null)
    {
        $msg = str_replace('@3', $p3, $msg);
    }

    return $msg;
}

/**
 * Compatibility autoloader for namespaced-to-global mappings, legacy short class
 * names, and MIOLO 2.0 integration.
 */
function miolo_compatibility_autoload($className)
{
    // Global names for namespaced classes. Loading the namespaced class triggers
    // class_alias() at the end of each file, creating the global name.
    static $namespaceMap = [
        'MIOLO'         => 'App\\MIOLO',
        'MService'      => 'App\\Services\\MService',
        'MContext'       => 'App\\Services\\MContext',
        'MRequest'      => 'App\\Services\\MRequest',
        'MResponse'     => 'App\\Services\\MResponse',
        'MSimpleXml'    => 'App\\Utils\\MSimpleXml',
        'MSimpleXML'    => 'App\\Utils\\MSimpleXml',
        'MConfigLoader' => 'App\\Utils\\MConfigLoader',
    ];

    if (isset($namespaceMap[$className])) {
        class_exists($namespaceMap[$className], true);
        return;
    }

    // MIOLO 2.0 module integration fallback
    if (class_exists('MIOLO', false)) {
        $MIOLO = MIOLO::getInstance();
        if ($MIOLO && strlen($MIOLO->getConf('options.miolo2modules'))) {
            if (method_exists('sAutoload', 'SAGUAutoload')) {
                $lower = strtolower($className);
                if (substr($lower, 0, 8) === 'business'
                    || in_array(substr($lower, 0, 3), ['bas', 'acd', 'fin', 'acp'])) {
                    sAutoload::SAGUAutoload($className, $MIOLO->getConf('options.miolo2modules'), true);
                }
            }
        }
    }
}

spl_autoload_register('miolo_compatibility_autoload');

function mdump($var)
{
    $MIOLO = MIOLO::getInstance();
    $MIOLO->trace(print_r($var,true));
}

function debug($var, $append = true)
{
    $file = '/tmp/var_dump';
    
    MUtil::MDEBUG($var, $append, $file);
    
    // Informa a linha e arquivo que esta chamando o debug
    $btr = debug_backtrace();
    $info = '>>> Arquivo: ' . $btr[0]['file'] . ' (linha: ' . $btr[0]['line'] . ')';
    MUtil::MDEBUG($info, true, $file);
    
    // Adiciona espacos
    MUtil::MDEBUG("\n\n", true, $file);
}

function btr($depth = 20)
{
    $exp = var_export(backtrace($depth), true);
    debug($exp);
}

function backtrace($depth = 20)
{
    $traces = debug_backtrace();
    $lines = array();
    
    for ($i=0; $i <= $depth; $i++)
    {
        $trace = $traces[$i];
        
        if ( $trace )
        {
            $lines[] = $trace['function'] . '()' . ' - ' . basename($trace['file']) . ':' . $trace['line'];
        }
    }
    
    return $lines;
}
