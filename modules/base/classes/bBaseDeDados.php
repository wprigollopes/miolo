<?php

/**
 * <--- Copyright 2012 Solis - Cooperativa de Soluções Livres Ltda.
 *
 * This file is part of the Base program.
 *
 * Base is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation (FSF); version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License/GPL
 * for more details.
 *
 * You should have received a copy of the GNU General Public License, under
 * the title "LICENCA.txt", along with this program. If not, visit the
 * Brazilian Public Software Portal at www.softwarepublico.gov.br or write
 * to the Free Software Foundation (FSF) Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301, USA --->
 *
 * Database handler class.
 *          
 * @author Jader Osvino Fiegenbaum [jader@solis.coop.br]
 * @author Eduardo Bonfandini [eduardo@solis.coop.br]
 *
 * @since
 * Class created on 25/06/2012
 */
class bBaseDeDados
{
    /**
     * @var MBusiness MBusiness object instance.
     */
    protected static $instancia = NULL;
    
    /**
     * @var string Last SQL statement executed in the database.
     */
    protected static $lastQuery;

    /**
     * @var bool Checks if the database user has already been changed.
     */
    private static $changedDatabase = false;

    /**
     * Static method to get the MBusiness object instance.
     *
     * @return MBusiness MBusiness instance.
     */
    public static function obterInstancia($base = DB_NAME)
    {
        // Gets the instance when it has not been defined, or when the defined database differs from the parameter.
        if ( is_null(self::$instancia) || self::$instancia->_database != $base )
        {
            if ( $base == NULL )
            {
                $base = DB_NAME;
            }
            
            self::$instancia = new MBusiness($base);
            
            // Sets date style to Brazilian format.
            self::$instancia->execute("SET DateStyle TO 'SQL, DMY';");
        }

        self::checkDatabaseUser();

        return self::$instancia;
    }

    /**
     * Changes the database connection user to the miolo_user
     * Used for purposes such as multi-unit, record auditing.
     */
    public static function checkDatabaseUser()
    {
        if ( ! self::$changedDatabase )
        {
            $MIOLO = MIOLO::getInstance();

            $login = null;

            // The console_user is set when running sync via terminal
            if ( !strlen($MIOLO->getConf('usuario_console')) > 0 )
            {
                $login = $MIOLO->getLogin()->id;
            }

            if ( strlen($login) > 0 )
            {
                self::$changedDatabase = true;

                self::insertDbUser($login);

                self::changeConnectionUser($login);
            }
        }
    }

    /*
     * Inserts user in postgres
     */
    public static function insertDbUser($login)
    {
        $MIOLO = MIOLO::getInstance();

        if ( $MIOLO->getSession()->getValue('existsDbUser') == $login )
        {
            return true;
        }

        $dbPass = $MIOLO->getConf('db.basic.password');
        try
        {
            $msqlConsulta = new MSQL("insert_miolo_user_passwords('{$login}','{$dbPass}')");
            $result = self::consultar($msqlConsulta);
        }
        catch ( Exception $e )
        {
            //Do nothing, just to handle the error
        }

        // Limits the query execution time for the user
        $tempoMaximoParaQueries = SAGU::getParameter("BASIC","TEMPO_MAXIMO_PARA_QUERIES");
        $tempoCalculado = SAGU::calcNumber("{$tempoMaximoParaQueries}::int * 60000");

        if ( $tempoCalculado > 0 )
        {
            self::executar("ALTER ROLE \"{$login}\" SET statement_timeout = $tempoCalculado;");
        }

        if ( strlen($result[0][0]) > 0 )
        {
            $MIOLO->getSession()->setValue('existsDbUser', $login);
            return true;
        }

        return false;
    }

    /**
     * Changes the MIOLO connection login.
     * Use this method with caution.
     */
    public static function changeConnectionUser($user)
    {
        self::$instancia = null;

        $MIOLO = MIOLO::getInstance();
        $MIOLO->setConf('db.' . self::obterDbPadrao() . '.user', $user);
        $MIOLO->clearDbConf( self::obterDbPadrao() );

        self::obterInstancia();
    }

    /**
     * Gets the default database configuration
     *
     * @return string
     */
    public static function obterDbPadrao()
    {
        return 'base';
    }

    /**
     * Executes a SQL query on the database.
     *
     * @param MSQL $msql Query object to be executed on the database.
     * @param array $parametros SQL query parameters.
     * @param string $base Database where the query will be executed.
     * @return array of array With the query result.
     */
    public static function consultar(MSQL $msql, $parametros = NULL, $base = NULL)
    {                
        // Converts MSQL object into SQL query string.
        if ( strlen($msql->command) )
        {
            // Gets the SQL command in cases where createFrom command was used.
            $sql = $msql->command;
        }
        else
        {
            $sql = $msql->select($parametros);
        }
        // Does not execute the function if no statement exists.
        if ( !$sql )
        {
            return FALSE;
        }

        // Converts the SQL statement string to the correct encoding.
        $sql = BString::construct($sql)->__toString();
        
        // Stores the last query for error logging.
        self::$lastQuery = $sql;
       
        // Executes the statement on the database.
        $resultado = self::obterInstancia($base)->_db->query($sql);

        // Maintains compatibility with MIOLO 2.
        if ( $resultado instanceof PostgresQuery )
        {
            return $resultado->result;
        }
        else
        {
            return $resultado;
        }
    }
    
    /**
     * Executes a SQL query on the database.
     *
     * @param array $sql Array of queries to be executed on the database.
     * @return array of array With the query result.
     */
    public static function consultarBloco(array $sqls, $base = NULL)
    {
        // Converts the array of SQLs into a string.
        $sql = $sqls;
        if ( is_array($sql) )
        {
            $sql = implode(";\n", $sql);
        }

        // Converts the SQL statement string to the correct encoding.
        $sql = BString::construct($sql)->__toString();

        // Stores the last query for error logging.
        self::$lastQuery = $sql;

        // Executes the statement on the database.
        $resultado = self::obterInstancia($base)->_db->query($sql);

        // Maintains compatibility with MIOLO 2.
        if ( $resultado instanceof PostgresQuery )
        {
            return $resultado->result;
        }
        else
        {
            return $resultado;
        }
    }

    /**
     * Executes a SQL statement on the database.
     *
     * @param string $sql SQL statement to be executed.
     * @return boolean Returns true if executed successfully.
     */
    public static function executar($sql, $base = NULL)
    {
        // Does not execute the method when no SQL exists.
        if ( !strlen($sql) )
        {
            return FALSE;
        }
        
        // Converts the SQL statement string to the correct encoding.
        $sql = BString::construct($sql)->__toString();
        self::$lastQuery = $sql;

        return self::obterInstancia($base)->_db->execute($sql);
    }
    
    /**
     * Executes a block of SQL statements on the database.
     *
     * @param array $sql Array with SQL statements to be executed.
     * @return boolean Returns true if executed successfully.
     */
    public static function executarBloco(array $sqls, $base = NULL)
    {
        // Converts array to string for conversion.
        //$sqls = implode("\n", $sqls);
        
        // Converts the SQL statement string to the correct encoding.
        //$sqls = BString::construct($sqls)->__toString();
        //self::$lastQuery = $sql;
        
        foreach ( $sqls as $sql )
        {
            self::executar($sql, $base);
        }
        
       // $sqls = explode("\n", $sqls);
        //return self::obterInstancia($base)->_db->execute($sql);
    }
    
    /**
     * Static method to perform an insert on the database.
     *
     * @param MSQL $msql MSQL object with the SQL insert.
     * @param array $valores Values for SQL insert.
     * @param string Database where the values will be inserted.
     * @return array Values inserted in the database.
     */
    public static function inserir( MSQL $msql, $valores, $base = NULL)
    {
        // Converts MSQL object into SQL string.
        $sql = $msql->insert($valores) . ' RETURNING *';

        // Converts the SQL statement string to the correct encoding.
        $sql = BString::construct($sql)->__toString();
        self::$lastQuery = $sql;

        $retorno = self::obterInstancia($base)->_db->query($sql);

        return is_array($retorno) ? $retorno[0] : $retorno->result[0];
    }

    /**
     * Gets the last database error.
     *
     * @return string Error message.
     */
    public static function obterUltimoErro()
    {
        return pg_last_error();
    }

    /**
     * Gets the last SQL executed.
     *
     * @return string Last SQL statement executed on the database.
     */
    public static function obterUltimaInstrucao()
    {
        return self::$lastQuery;
    }

    /**
     * Static method to begin a database transaction.
     */
    public static function iniciarTransacao()
    {
        self::executar('BEGIN');
    }

    /**
     * Static method to commit the current database transaction.
     */
    public static function finalizarTransacao()
    {
        self::executar('COMMIT');
    }

    /**
     * Static method to rollback the current database transaction.
     */
    public static function reverterTransacao()
    {
        self::executar('ROLLBACK');
    }
    
    public static function obterParametro($module, $parameter)
    {
        $MIOLO = MIOLO::getInstance();

        try
        {
            // If parameter is not defined yet, get its value from the database
            if ( !defined($parameter) )
            {
                // If $parameter contains no value, get value from basConfig
                
                $msql = new MSQL();
                $msql->setColumns('getParameter(?, ?)');
                $msql->addParameter($module);
                $msql->addParameter($parameter);
                                
                $sql = 'SELECT getParameter(?, ?)';

                $params = array( );
                $params[] = $module;
                $params[] = $parameter;

                $db = $MIOLO->getDatabase('base');
                $result = bBaseDeDados::consultar($msql); //($sql, $params);
                if ( count($result) == 0 )
                {
                    throw new Exception(_M('O parâmetro @1 não existe no módulo @2.', 'basic', $parameter, $module));
                }

                // Define this parameter globally so that it can be used later without going
                // to the database again.
                define($parameter, $result[0][0]);
            }

            return constant($parameter);
        }
        catch ( Exception $e )
        {
            $MIOLO->error($e->getMessage());
        }
    }
    
    /**
     * Gets the last inserted ID based on the given table name.
     * 
     *
     * @param string $tableName
     * @return int
     */
    public static function obterUltimoIdInserido($tableName)
    {
        $max = null;
        $pkey = self::obterChavePrimaria($tableName);
        
        if ( strlen($pkey) > 0 )
        {
            $sql = "SELECT MAX({$pkey}) FROM {$tableName}";
            $msql = new MSQL();
            $msql->createFrom($sql);
            
            $result = self::consultar($msql);
            $max = $result[0][0];
        }
        
        return $max;
    }
    
    /**
     * Returns the primary key column name of the given table.
     *
     * Supports tables that use a specific schema (e.g.: bas.systemtask)
     *  and tables with the default public schema (e.g.: acdschedule).
     *
     * @param string $tableName 
     * @return string Ex.: scheduleid
     */
    public static function obterChavePrimaria($tableName)
    {
        $tableName = str_replace('.', '', $tableName);
        $prefix = substr($tableName, 0, 3);
        $tableNameAlt = substr($tableName, 3);

        $sql = "SELECT               
                pg_attribute.attname, 
                format_type(pg_attribute.atttypid, pg_attribute.atttypmod) 
                FROM pg_index, pg_class, pg_attribute 
                WHERE 
                pg_class.oid = '{$tableName}'::regclass AND
                indrelid = pg_class.oid AND
                pg_attribute.attrelid = pg_class.oid AND 
                pg_attribute.attnum = any(pg_index.indkey)
                AND indisprimary";
        
        $msql = new MSQL();
        $msql->createFrom($sql);
        
        $result = self::consultar($msql, array($tableName));
        
        return $result[0][0];
    }
}
