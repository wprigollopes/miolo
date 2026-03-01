<?php

/**
 * Copyright 2005-2017 Solis Soluções Livres Ltda.
 *
 * This file is part of the SolisGE/Sagu program.
 *
 * SolisGE/Sagu is proprietary software of SOLIS, developed and maintained
 * exclusively by this company.
 *
 * The usage license is available through exclusive acquisition from SOLIS.
 * The license is granted on a non-exclusive basis to the licensee.
 * Usage rights are perpetual.
 *
 * Although source code is provided, the software is the property of SOLIS.
 * The licensee is not permitted to resell, lend, or transfer (whether for
 * payment or not) the license to third parties. It is also not permitted,
 * at any time or for any reason, to perform any alienation, reproduction,
 * distribution, disclosure, registration, licensing, transfer, or any other
 * act that may harm or compromise the software property rights, the name
 * and image of its owner and the software itself, or that constitutes
 * competition with SOLIS.
 *
 * The licensee, with access to the software source code, shall have the
 * right to make changes to the respective code. However, in situations
 * where the licensee relies on official support provided by SOLIS, changes
 * to the source code are not permitted, under penalty of losing said support.
 *
 * For detailed information about the SolisGE/Sagu Software Licensing Terms,
 * read the "LICENCA.txt" file included with this software. e
 * 
 * 
 *
 *
 * 
 *
 * */
class bSyncDatabaseView implements bSync
{
    protected $file;
    protected $syncModule;

    public function __construct($file, $module)
    {
        if ( !$file )
        {
            throw new Exception(_M('É necessário informar um arquivo para sincronização de visões.'));
        }

        $this->file = $file;

        if ( !$module )
        {
            throw new Exception(_M('É necessário informar um modulo para sincronização de visões.'));
        }

        $this->module = $module;
    }

    /**
     * Synchronizes the file with the database
     * 
     * @return stdClass
     */
    public function syncronize()
    {
        $content = file_get_contents($this->file);

        if ( !$content )
        {
            return false;
        }

        //lists views in the file and in the database
        $fileViews = $this->getViews($content);
        $dbViews = bCatalogo::listarVisoes('public');

        //sets counters in the result
        $result = new stdClass();
        $result->file = count($fileViews);
        $result->start = count($dbViews);

        //explodes the content to execute one by one
        $sqlCommands = explode('CREATE OR REPLACE VIEW', $content);
        //filters array based on blank lines
        $sqlCommands = array_values(array_filter($sqlCommands));

        //passes instructions one at a time to show errors correctly
        foreach ( $sqlCommands as $line => $sql )
        {
            if ( $sql )
            {
                //drops and recreates the view
                $sql = 'DROP VIEW IF EXISTS ' . $fileViews[$line] . ";\n" . 'CREATE OR REPLACE VIEW' . $sql;
                bBaseDeDados::executar($sql);
            }
        }

        //gets updated list
        $finalDbViews = bCatalogo::listarVisoes();

        //marks in the counter
        $result->final = count($finalDbViews);

        $sqlResult = '';

        //searches for extra views in the database
        foreach ( $finalDbViews as $line => $view )
        {
            if ( !in_array($view->name, $fileViews) )
            {
                $missing[] = $view->name;
                $sqlResult .= 'CREATE OR REPLACE VIEW ' . $view->name . ' AS ' . $view->source . "\n\n\n";
            }
        }

        //missing
        $result->missing = $missing;
        //SQL to include in views.sql
        $result->sql = $sqlResult;

        return $result;
    }

    /**
     * Parses the SQL file getting the list of views
     * 
     * @param string $content SQL file content
     * @return array of stdClass
     * 
     */
    protected function getViews($content)
    {
        preg_match_all("/CREATE OR REPLACE VIEW (.*) AS/", $content, $matches);

        return $matches[1];
    }

    /**
     * Returns an array with the base synchronization files of the specified module.
     * 
     * @param string $module
     * @return array 
     */
    public static function listSyncFiles($module)
    {
        $MIOLO = MIOLO::getInstance();
        $path = $MIOLO->getConf('home.miolo') . '/modules/' . $module . '/syncdb/views.sql';

        return glob($path);
    }
}
