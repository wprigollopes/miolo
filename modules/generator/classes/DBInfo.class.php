<?php

/**
 * Class for getting database information, used by MIOLOGenerate
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 * @author Fernando Kochhann [fernando@solis.coop.br]
 *
 * @version $id$
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/02/09
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b CopyRight: \n
 * Copyright (c) 2011 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 *
 * \b History: \n
 * See history in CVS repository: http://www.miolo.org.br
 *
 */
class DBInfo
{
    public $MIOLO;
    public $dbConf;
    public $db;
    public $system;
    private $schema;

    public function __construct($dbConf=NULL, $module=NULL, $system=NULL, $host=NULL, $port='5432', $name=NULL, $user=NULL, $password=NULL)
    {
        $this->MIOLO = MIOLO::getInstance();

        if ( $dbConf )
        {
            $this->dbConf = $dbConf;
            $this->db = $this->MIOLO->getDatabase($this->dbConf);
            $this->system = $this->MIOLO->getConf('db.' . $dbConf . '.system');
            $this->module = $module;
        }
        else
        {
            $this->db = new MDatabase(NULL, $system, "$host:$port", $name, $user, $password, NULL);
            $this->system = $system;
        }
    }

    public function setSchema($schema)
    {
        $this->schema = $schema;
    }

    public function getSchemas()
    {
        if ( $this->system == 'postgres' )
        {
            $schemas = $this->getPgSchemas();
        }
        return $schemas;
    }

    public function getTables()
    {
        switch ( $this->system )
        {
            case 'postgres':
                $tables = $this->getPgTables();
                break;

            case 'sqlite':
                $tables = $this->getSqliteTables();
                break;
        }
        return $tables;
    }

    public function getTableDescription()
    {
        if ( $this->system == 'postgres' )
        {
            $tableDescription = $this->getPgTableDescription();
        }
        return $tableDescription;
    }

    public function getColumns($table)
    {
        if ( $this->system == 'postgres' )
        {
            $columns = $this->getPgColumns($table);
        }
        return $columns;
    }

    public function getPKeys($table)
    {
        if ( $this->system == 'postgres' )
        {
            $pkeys = $this->getPgPKeys($table);
        }
        return $pkeys;
    }

    public function getFKeys($table)
    {
        if ( $this->system == 'postgres' )
        {
            $fkeys = $this->getPgFKeys($table);
        }
        return $fkeys;
    }

    /**
     * Sqlite methods
     */
    private function getSqliteTables()
    {
        $sql = "SELECT name
                  FROM sqlite_master
                 WHERE type = 'table'
              ORDER BY name";

        return $this->db->query($sql)->result;
    }

    /**
     * Postgres methods
     */
    private function getPgSchemas()
    {
        $sql = 'SELECT nspname FROM pg_namespace;';
        return $this->db->query($sql)->result;
    }

    private function getPgTables()
    {
        $sql = "SELECT c.relname
                  FROM pg_class c, pg_namespace n
                 WHERE c.relnamespace=n.oid
                   AND c.relkind = 'r'                   -- not indices, views, etc
                   AND n.nspname NOT LIKE 'pg_%'         -- not catalogs
                   AND n.nspname != 'information_schema' ";

        if ( $this->schema )
        {
            $sql .= " AND n.nspname ILIKE '{$this->schema}'";
        }

        $sql .= " ORDER BY relname;";

        return $this->db->query($sql)->result;
    }

    private function getPgTableDescription()
    {
        $sql = "SELECT d.description
                  FROM pg_class c
            INNER JOIN pg_namespace n
                    ON c.relnamespace = n.oid
             LEFT JOIN pg_description AS d
                    ON ( d.objoid = c.oid AND d.objsubid = 0 )
                 WHERE c.relkind = 'r'                   -- not indices, views, etc
                   AND n.nspname NOT LIKE 'pg_%'         -- not catalogs
                   AND n.nspname != 'information_schema'
                   AND c.relname = '$this->table' ";

        if ( $this->schema )
        {
            $sql .= " AND n.nspname ILIKE '{$this->schema}'";
        }

        $sql .= " ORDER BY relname;";

        return $this->db->query($sql)->result[0][0];
    }

    private function getPgColumns($table)
    {
        $sql = "SELECT a.attname,
                       format_type(t.oid, null) as typname,
                       d.description,
                       a.attnotnull,
                       pg_get_expr(def.adbin,def.adrelid),
                       CASE WHEN atttypmod > 4 THEN ( atttypmod - 4 ) ELSE NULL END,
                       n.nspname
                  FROM pg_class c
            INNER JOIN pg_namespace AS n ON ( n.oid = c.relnamespace )
            INNER JOIN pg_attribute AS a ON ( a.attrelid = c.oid )
            INNER JOIN pg_type AS t ON ( a.atttypid = t.oid )
             LEFT JOIN pg_attrdef AS def ON ( def.adrelid = c.oid AND a.attnum = def.adnum )
             LEFT JOIN pg_description AS d ON ( d.objoid = c.oid AND d.objsubid = a.attnum )
                 WHERE c.relkind = 'r'                   -- no indices
                   AND n.nspname NOT LIKE 'pg\\_%'       -- no catalogs
                   AND n.nspname != 'information_schema' -- no information_schema
                   AND a.attnum > 0                      -- no system att's
                   AND NOT a.attisdropped                -- no dropped columns
                   AND c.relname = '{$table}' ";

        if ( $this->schema )
        {
            $sql .= " AND n.nspname ILIKE '{$this->schema}'";
        }

        return $this->db->query($sql)->result;
    }

    public function getPgFKeys($table)
    {
        $sql = "SELECT DISTINCT c.relname              AS from_table_name,
                                toSchemaName.nspname   AS to_schema_name,
                                toTable.relname        AS to_table_name,
                                fk_col.attname         AS from_column_name,
                                fk_col2.attname        AS to_column_name,
                                n.nspname              AS from_schema_name
                           FROM pg_catalog.pg_class AS c
                      LEFT JOIN pg_namespace AS n 
                             ON (n.oid = c.relnamespace)
                     INNER JOIN pg_catalog.pg_constraint AS rel
                             ON (c.oid=rel.conrelid)
                      LEFT JOIN pg_catalog.pg_class AS toTable
                             ON (toTable.oid = rel.confrelid)
                      LEFT JOIN pg_namespace AS toSchemaName
                             ON (toSchemaName.oid = toTable.relnamespace)
                      LEFT JOIN pg_catalog.pg_attribute AS fk_col
                             ON fk_col.attrelid = rel.conrelid AND (conkey @> ARRAY[ fk_col.attnum ] AND position(fk_col.attnum::text in array_to_string(conkey, ' ')) <>0 )
                      LEFT JOIN pg_catalog.pg_attribute AS fk_col2
                             ON fk_col2.attrelid = rel.confrelid AND (conkey @> ARRAY[ fk_col.attnum ] AND position(fk_col2.attnum::text in array_to_string(confkey, ' ')) <>0 )
                          WHERE rel.contype='f'
                            AND c.relname = '" . $table . "' ";

        if ( $this->schema )
        {
            $sql .= " AND n.nspname ILIKE '{$this->schema}'";
        }

        $sql .= "      ORDER BY --from_schema_name,
                                from_table_name;";

        return $this->db->query($sql)->result;
    }

    public function getPgPKeys($table)
    {
        $sql = "SELECT DISTINCT fk_col.attname AS column_name, 
                                format_type(t.oid, null) as typname,
                                pg_get_expr(def.adbin,def.adrelid),
                                n.nspname
                           FROM pg_catalog.pg_class AS c
                     INNER JOIN pg_catalog.pg_constraint AS rel
                             ON (c.oid = rel.conrelid)
                      LEFT JOIN pg_catalog.pg_attribute AS fk_col
                             ON fk_col.attrelid = rel.conrelid AND (position(fk_col.attnum::text in array_to_string(conkey, ' ')) <>0 )
                     INNER JOIN pg_type AS t ON (fk_col.atttypid = t.oid)
                      LEFT JOIN pg_attrdef AS def ON (def.adrelid = c.oid AND fk_col.attnum = def.adnum)
                      LEFT JOIN pg_namespace AS n ON (n.oid = c.relnamespace)
                          WHERE rel.contype='p'
                            AND c.relname = '" . $table . "' ";

        if ( $this->schema )
        {
            $sql .= " AND n.nspname ILIKE '{$this->schema}'";
        }

        return $this->db->query($sql)->result;
    }
}

?>