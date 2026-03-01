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
 **/
class bSyncDatabaseFunction implements bSync
{
    protected $file;
    protected $syncModule;
    
    public function __construct( $file , $module )
    {
        if ( !$file )
        {
            throw new Exception( _M('É necessário informar um arquivo para sincronização de funções.') );
        }
        
        $this->file = $file;
        
        if ( !$module )
        {
            throw new Exception( _M('É necessário informar um modulo para sincronização de funções.') );
        }
        
        $this->module =$module;
        
        //creates the standard drop_function_if_exists function
        $this->createDropFunction();
    }
    
    public function syncronize()
    {
        $content = file_get_contents ( $this->file );
        
        if ( ! $content )
        {
            return false;
        }
        
        $fileFunctions = $this->getSqlFunctions($content);
        $dbFunctions = bCatalogo::listarFuncoes();
        $result = new stdClass();
        $result->start = count( $dbFunctions ) -1; //desconsidera drop functions
        $result->file = count( $fileFunctions );
        
        //make the real syncronization
        $sqlCommands = explode('CREATE OR REPLACE ', $content );
        
        foreach ( $sqlCommands as $line => $sql )
        {
            //needed to run the functions and show the error correctly
            if ( $sql )
            {
                $sql = 'CREATE OR REPLACE ' . $sql;
                bBaseDeDados::executar( $sql );
            }
        }
        
        $finalDbFunctions = bCatalogo::listarFuncoes();
        
        $result->final = count( $finalDbFunctions ) -1 ; //desconsidera drop functions
        
        //aligns functions by name for easy location
        foreach ( $fileFunctions as $line => $info )
        {
            $functionsF[ $info->function ] = $info->params ? $info->params :  ' ';
        }
        
        $sqlResult ='';
        
        foreach ( $finalDbFunctions as $line => $info )
        {
            //registers SQL for generating missing functions
            if ( !$functionsF[ $info->function ]  )
            {
                $functions[ $info->function ][0] = $info->function;
                
                //"extra" functions
                if ( $info->function != 'plpgsql_call_handler'
                     && $info->function != 'plpgsql_validator'
                     && $info->function != 'drop_function_if_exists'
                   )
                {
                    $source = bCatalogo::obterCodigoFonteDaFuncao( $info->function );
                    $source = $source[0][0];
                    $sqlResult .= $source ."\n";
                }
            }
        }
        
        //functions that come "extra" in the select
        unset($functions['plpgsql_call_handler']);
        unset($functions['plpgsql_validator']);
        //function created by the class itself
        unset($functions['drop_function_if_exists']);
        
        
        $result->missing = $functions;
        $result->sql = $sqlResult;
       
        return $result;
    }
    
    /**
     * Parses the SQL file getting the list of functions
     * 
     * @param type $content SQL file content
     * @return array of stdClass
     * 
     */
    protected function getSqlFunctions( $content )
    {
        $regexp = "/CREATE OR REPLACE FUNCTION (.*)\((.*)\)/";
        
        preg_match_all($regexp, $content, $matches);
        
        $functions = $matches[1];
        $parameters = $matches[2];
        
        foreach ( $functions as $line => $info)
        {
            $obj = new stdClass();
            $obj->function = strtolower( $info );
            $obj->params = $parameters[$line];
            $result[] = $obj;
        }
        
        return $result;
    }
    
    /**
     * Returns an array with the base synchronization files of the specified module.
     * @param string $module
     * @return array 
     */
    public static function listSyncFiles($module)
    {
        $MIOLO = MIOLO::getInstance();
        $path = $MIOLO->getConf('home.miolo').'/modules/'.$module.'/syncdb/functions.sql';
       
        return glob($path);
    }

    /**
     * Creates a standard function in the database that serves to drop functions only
     * if they exist
     */
    public function createDropFunction()
    {
        $sql ="
            CREATE OR REPLACE FUNCTION drop_function_if_exists( name varchar )
            RETURNS void as \$BODY\$
            DECLARE
            v_sql varchar;
            BEGIN
                FOR v_sql IN SELECT 'DROP FUNCTION '|| proname || '(' ||
                    (
                    SELECT array_to_string( array_agg(coalesce(varname,'') || ' ' ||
                                        ( SELECT coalesce(typname,'') 
                                            FROM pg_type
                                            WHERE oid::varchar = type::varchar 
                                        ) ) , ' , ' )
                                        FROM (    SELECT  unnest( coalesce(proargnames,ARRAY[''] ) ) as varname,
                                                        regexp_split_to_table( proargtypes::varchar , E'\\ +') as type
                                                FROM  pg_proc A
                                                WHERE lower(A.proname) = lower(name)
                                                and pg_proc.oid = oid                                               
                                        ) as foo ) || ');'                                              
            FROM pg_proc                                                                                             
            WHERE lower(proname) = lower( name )
            LOOP
                    EXECUTE v_sql;
                END LOOP;
            END
            \$BODY\$
            LANGUAGE 'plpgsql';


            CREATE OR REPLACE FUNCTION drop_function_if_exists( name varchar, param varchar )
            RETURNS void as \$BODY\$
            BEGIN
                PERFORM drop_function_if_exists(lower(name));
            END
            \$BODY\$
            LANGUAGE 'plpgsql';
        ";
        
        bBaseDeDados::executar( $sql );
    }
   
}
