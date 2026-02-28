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
class bSyncDatabase extends SimpleXMLElement implements bSync
{

    /**
     * Sets the access module 
     * 
     * @param string $module 
     */
    public function setModule($module)
    {
        $this->addAttribute('module', $module);
    }

    /**
     * Returns access module
     * @return string access module
     */
    public function getModule()
    {
        return $this->getAttribute('module') . '';
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
        $path = $MIOLO->getConf('home.miolo') . '/modules/' . $module . '/syncdb/*.xmi';

        return glob($path);
    }

    /**
     * Remove a attribute
     *
     * @param string $attribute name of attribute
     */
    protected function removeAttribute($attribute)
    {
        unset($this->attributes()->$attribute);
    }

    /**
     * Define an attribute, differs from addAttribute.
     * Define overwrite existent attribute
     *
     * @param string $attribute attribute to set
     * @param string $value value to set
     * @param string $namespace the namespace of attribute
     *
     * @example  $this->addAttribute("xlink:href", $filename, 'http://www.w3.org/1999/xlink');
     */
    protected function setAttribute($attribute, $value, $namespace = null)
    {
        $this->removeAttribute($attribute);
        $this->addAttribute($attribute, $value, $namespace);
    }

    /**
     * Return a value of a attribute. Support namespaces using namespace:attribute
     *
     * @param string $attribute
     * @return string return the value of passed attribute
     * @example $svg->g->image->getAttribute('xlink:href')
     */
    protected function getAttribute($attribute)
    {
        $explode = explode(":", $attribute);

        if ( count($explode) > 1 )
        {
            $attributes = $this->attributes($explode[0], true);

            //if the attribute exits with namespace return it
            if ( $attributes[$explode[1]] )
            {
                return $attributes[$explode[1]];
            }
            else
            {
                //otherwize will return the attribute without namespaces
                $attribute = $explode[1];
            }
        }

        if ( @$this && @$attribute && @$this->attributes() )
        {
            return $this->attributes()->$attribute . '';
        }
    }

    /**
     * Returns the XMI version
     * 
     * @return string
     */
    public function getXmiVersion()
    {
        return $this->getAttribute('xmi:version') . '';
    }

    /**
     * Returns a property of the XMI
     * 
     * @param string $propertyName
     * @return string 
     */
    protected function getProperty($propertyName)
    {
        $xmi = $this->children('xmi', true);
        $Extension = $xmi->Extension;
        $projectProperties = $Extension->children();
        $projectProperty = $projectProperties->children();

        foreach ( $projectProperty as $line => $property )
        {
            if ( $property->getAttribute('name') . '' == $propertyName )
            {
                return $property->getAttribute('value') . '';
            }
        }
    }

    /**
     * Returns the company that generated the XMI
     * 
     * @return string
     */
    public function getCompany()
    {
        return $this->getProperty('company');
    }

    /**
     * Returns the XMI author
     * 
     * @return string
     */
    public function getAuthor()
    {
        return $this->getProperty('author');
    }

    /**
     * Returns the XMI description
     * 
     * @return string html
     */
    public function getDescription()
    {
        return $this->getProperty('description');
    }

    /**
     * Returns an array of messages generated by the system
     * 
     * @return array
     */
    public function getMessages()
    {
        return array_filter(explode("\n", $this->getAttribute('messages') . ''));
    }

    /**
     * Adds a message to the system
     * 
     * @param string $msg 
     */
    public function addMessage($msg)
    {
        $messages = $this->getAttribute('messages') . '';
        $messages .= new BString($msg) . "\n";

        $this->setAttribute('messages', $messages);
    }

    /**
     * Synchronizes triggers, constraints and foreign keys in a second pass.
     * Usually after function creation.
     * 
     * @param array $tablesByI array with tables ordered by the VPP id
     * 
     */
    public function syncronizeTriggersAndContraints($tablesById)
    {
        $MIOLO = MIOLO::getInstance();
        $uml = $this->children('uml', true);

        //Initializes l
        $l = null;

        //iterates through all UML content
        foreach ( $uml as $line => $xmlElement )
        {
            $extension = $xmlElement->children('xmi', true);
            $extension = $extension->children();

            $ownedMember = $extension->vpumlChildModels->ownedMember;

            foreach ( $ownedMember as $l => $item )
            {
                $xmiType = $item->getAttribute('xmi:type');

                if ( $xmiType == 'dbTriggerContainer' ) //trigger
                {
                    //triggers
                    $this->parseTriggers($db, $item);
                }
                else if ( $xmiType == 'dbForeignKey' )
                {
                    //foreign keys
                    $this->parseForeignKey($db, $item, $tablesById);
                }
            }
        }

        //previously built checks
        if ( is_array($tablesById) )
        {
            foreach ( $tablesById as $tableId => $tableInfo )
            {
                if ( $tableInfo->sqlCheck )
                {
                    bBaseDeDados::executarBloco($tableInfo->sqlCheck);
                }
            }
        }
    }

    /**
     * Performs the synchronization
     */
    public function syncronize()
    {
        $MIOLO = MIOLO::getInstance();

        if ( $this->getXmiVersion() != '2.1' )
        {
            throw new Exception(new bString('A versão deste XMI é ' . $this->getXmiVersion() . ',mas a única versão suportada é a 2.1.'));
        }

        $this->addMessage('Empresa: ' . $this->getCompany());
        $this->addMessage('Autor: ' . $this->getAuthor());

        $uml = $this->children('uml', true);

        $this->addMessage('Projeto: ' . $uml->getAttribute('name'));

        //array of tables indexed by id
        $tablesById = array( );

        //iterates through all UML content
        foreach ( $uml as $line => $xmlElement )
        {
            $extension = $xmlElement->children('xmi', true);
            $extension = $extension->children();

            $ownedMember = $extension->vpumlChildModels->ownedMember;

            if ( is_object($ownedMember) )
            {
                foreach ( $ownedMember as $l => $item )
                {
                    $xmiType = $item->getAttribute('xmi:type');

                    if ( $xmiType == 'procedureContainer' ) //functions, managed by functions.sql
                    {
                        
                    }
                    else if ( $xmiType == 'dbTriggerContainer' ) //trigger
                    {
                        
                    }
                    else if ( $xmiType == 'dbTable' )
                    {
                        $tableInfo = $this->parseTable($db, $item);
                        $tablesById[$tableInfo->id] = $tableInfo;
                    }
                    else if ( $xmiType == 'dbForeignKey' ) // Logic implemented in another function.
                    {
                        
                    }
                    else if ( $xmiType == 'anchor' )
                    {
                        
                    }
                }
            }
        }

        return $tablesById;
    }

    /**
     * Synchronizes triggers
     * 
     * @param Object $db query execution object
     * @param XMLElement $item xml object
     */
    public function parseTriggers($db, $item)
    {
        $triggers = $item->ownedMember;

        foreach ( $triggers as $line => $trigger )
        {
            $triggerName = $trigger->getAttribute('name') . '';

            //determines whether or not to apply the trigger
            //if it starts with RI, these are postgres triggers, no need to modify them
            $doIt = strpos($triggerName, 'RI') !== 0;

            if ( $doIt )
            {
                $childs = $trigger->children('xmi', true);
                $extension = $childs->Extension;

                $childs = $extension->children();
                $createStatement = $childs->createStatement;

                //gets the trigger creation string
                $value = $createStatement->getAttribute('value') . '';

                $posIni = stripos($value, ' on ');
                $posEnd = stripos($value, ' for ');

                //extracts only the table name by determining the position of ON and FOR
                $table = trim(substr($value, $posIni + 3, $posEnd - $posIni - 3));

                //does not filter by schema because it is not available at this point
                $triggers = bCatalogo::listarGatilhos(null, $triggerName, $table);
                $trigre = $triggers[$triggerName];

                //only removes if it exists in the database
                if ( $trigre )
                {
                    //drops the trigger
                    bBaseDeDados::executar("DROP TRIGGER $triggerName ON $table;");
                }

                //creates the trigger
                bBaseDeDados::executar($value);
            }
        }
    }

    /**
     * Does what is necessary to synchronize a foreign key
     * 
     * @param XmlElement $xmlElement 
     */
    protected function parseForeignKey($db, $xmlElement, $tablesById)
    {
        $id = $xmlElement->getAttribute('xmi:id') . '';
        $name = strtolower($xmlElement->getAttribute('name') . '');
        $from = $xmlElement->getAttribute('from') . '';
        $from = $tablesById[$from];
        $fromName = $from->name;
        $to = $xmlElement->getAttribute('to') . '';
        $to = $tablesById[$to];
        $toName = $to->name;

        if ( !$toName || !$fromName )
        {
            return;
        }

        $fkDb = bCatalogo::obterChavesEstrangeiras($to->schema, $toName);

        /* $fromMultiplicity = $xmlElement->getAttribute('fromMultiplicity') . '';
          $toMultiplicity = $xmlElement->getAttribute('toMultiplicity') . '';

          if ( $fromMultiplicity == '1' && $toMultiplicity == '0..*' )
          {

          } */

        $myToColumn = $to->foreignKeys[$id];
        $toColumn = array( );
        $fromColumn = array( );

        if ( is_array($myToColumn) )
        {
            foreach ( $myToColumn as $line => $info )
            {
                $toColumn[] = $info->columnName;

                if ( $info instanceof bSyncDatabase )
                {
                    $fromColumnId = $info->getAttribute('refColumn');
                    $fromColumn[] = $from->indexedColumns[$fromColumnId];
                }
            }
        }

        $toColumn = implode(',', $toColumn);
        $fromColumn = implode(',', $fromColumn);

        $create = false;
        $drop = false;

        $fkeyDb = $fkDb[$name];

        if ( $fkeyDb )
        {
            if ( $fkeyDb->schema != $to->schema
                    || strtolower($fkeyDb->table) != strtolower($to->name)
                    || strtolower($fkeyDb->column) != strtolower($toColumn)
                    || strtolower($fkeyDb->schemaRef) != strtolower($from->schema)
                    || strtolower($fkeyDb->tableRef) != strtolower($from->name)
                    || strtolower($fkeyDb->columnRef) != strtolower($fromColumn)
            )
            {
                $create = true;
                $drop = true;
            }
        }
        else
        {
            $create = true;
        }

        if ( $create && $toColumn && $fromColumn )
        {
            if ( $drop )
            {
                $sql = "ALTER TABLE {$toName} DROP CONSTRAINT $name;";
                bBaseDeDados::executar($sql);
            }

            $fromColumn = $this->reservedColumnNames($fromColumn);

            $this->addMessage("{$to->schema}.{$toName}: criando chave estrangeira '$name'.");

            $sql = "ALTER TABLE {$toName} ADD CONSTRAINT $name FOREIGN KEY ($toColumn) REFERENCES {$fromName} ( $fromColumn );";
            bBaseDeDados::executar($sql);
        }
    }

    /**
     * Does what is necessary for table synchronization
     * 
     * @param type $xmlElement 
     */
    protected function parseTable($db, $xmlElement)
    {
        $schema = $xmlElement->getAttribute('schema');
        $schema = $schema ? $schema : 'public'; //default schema
        $tableName = $xmlElement->getAttribute('name') . '';
        $tableNameWithSchema = $schema . '.' . $tableName;
        $tableId = $xmlElement->getAttribute('xmi:id') . '';

        //checks if table exists
        $verificarExistenciaDaTabela = bCatalogo::verificarExistenciaDaTabela($schema, $tableName);

        //if it does not exist, creates basic table creation script
        if ( !$verificarExistenciaDaTabela )
        {
            bBaseDeDados::executar($this->mountCreateTableSql($xmlElement));
        }

        $columsDb = bCatalogo::listarColunasDaTabela($tableName); //FIXME missing schema parameter
        $columnDbData = bCatalogo::obterColunasDaTabela($schema, $tableName, null);
        $columns = $this->getColumns($xmlElement);

        //builds an array with primary keys
        $primaryKeysDb = null;
        //array of columns indexed by their id
        $indexedColumns = null;
        //foreign keys of this table
        $foreignKeys = null;

        //comparing field by field
        foreach ( $columns as $line => $column )
        {
            $columnName = strtolower($column->getAttribute('name'));
            $columnNameReserved = $this->reservedColumnNames($columnName);
            $columnDb = $columnDbData[$columnName];
            $indexedColumns[$column->getAttribute('xmi:id') . ''] = strtolower($column->getAttribute('name') . '');

            //if it is a primary key field in the database, adds to the array
           // if ( MUtil::getBooleanValue($columnDb->primaryKey) == true )
            if ( $columnDb->restricao == 'p' )
            {
                $primaryKeysDb[] = $this->reservedColumnNames($columnDb->name);
            }

            //stores information for later foreign key creation
            $foreignKeyConstraints = $column->foreignKeyConstraints->ownedMember;

            $foreignKeyId = $foreignKeyConstraints->getAttribute('foreignKey');

            //only adds to the relation if it has an id
            if ( $foreignKeyId )
            {
                $foreignKeyConstraints->columnName = $columnName;
                $foreignKeyConstraints->columnId = $column->getAttribute('xmi:id') . '';
                $foreignKeys[$foreignKeyId][] = $foreignKeyConstraints;
            }

            if ( $verificarExistenciaDaTabela )
            {
                //checks if the xml column exists in the database
                if ( array_search($columnName, $columsDb) !== false )
                {
                    //type verification
                    $typeXml = $this->xmlTypeToDB($this->getColumnType($column, false));
                    $typeDB = strtolower($columnDb->tipo);
                    $lengthXml = strtolower($column->getAttribute('length'));
                    $lengthDB = strtolower($columnDb->tamanho);

                    $doDiff = true;

                    //-5 means no limit for this select, therefore no need to apply size correction.
                    if ( $lengthDB == '-5' && $typeXml == 'varchar' && $typeDB == 'varchar' )
                    {
                        $doDiff = false;
                    }

                    if ( !$lengthXml )
                    {
                        $lengthXml = 255; //VPP default value
                    }

                    //checks need for field change, only changes the size if the database one is smaller
                    if ( $doDiff && ( $typeDB != $typeXml || ( ( $lengthXml > $lengthDB ) && $typeXml == 'varchar' ) ) )
                    {
                        //special case for varchar
                        if ( $typeXml == 'varchar' )
                        {
                            $typeDB .='(' . $lengthDB . ')';
                            $typeXml .='(' . $lengthXml . ')';
                        }

                        $this->addMessage("{$tableNameWithSchema}.{$columnName}: alterando tipo de '$typeDB' para '$typeXml'.");

                        //changes the column type trying to force type conversion
                        bBaseDeDados::executar("ALTER TABLE $tableNameWithSchema ALTER $columnNameReserved TYPE $typeXml USING \"$columnName\"::$typeXml;");
                    }
                }
                else //if the field does not exist, creates it
                {
                    $type = $this->getColumnType($column, true);
                    $this->addMessage("{$tableNameWithSchema}.{$columnName}: criando campo como '$type'.");
                    bBaseDeDados::executar("ALTER TABLE $tableName ADD COLUMN $columnNameReserved $type;");
                }
            }

            //from this point on, assumes the field exists

            $uniqueDb = $columnDb->restricao == 'u';
            $uniqueXmi = $column->getAttribute('unique') == 'true';

            //they are different, needs database modification, but only if it is not a primary key
            // if ( $uniqueDb != $uniqueXmi && MUtil::getBooleanValue($columnDb->primaryKey) == false )
            if ( $uniqueDb != $uniqueXmi && $columnDb->restricao != 'p' )
            {
                //exists in database but not in XMI, drops it
                if ( $uniqueDb && !$uniqueXmi )
                {
                    $checkName = "{$tableName}_{$columnName}_key";

                    //only drops the constraint if it actually exists
                    if ( bCatalogo::obterChecagens($schema, $tablename, $checkName) )
                    {
                        $this->addMessage("$tableNameWithSchema.$columnName: removendo unique.");
                        bBaseDeDados::executar("ALTER TABLE ONLY $tableNameWithSchema DROP CONSTRAINT $checkName;");
                    }
                }
                //exists in XMI but not in database, adds it
                else
                {
                    $this->addMessage("$tableNameWithSchema.$columnName: adicionando unique.");
                    bBaseDeDados::executar("ALTER TABLE ONLY $tableNameWithSchema ADD CONSTRAINT {$tableName}_{$columnName}_key UNIQUE ($columnName);");
                }
            }

            //DEFAULT VALUE
            $defaultValue = $column->getAttribute('defaultValue') . '';

            $idGenerator = strtolower($column->getAttribute('idGenerator') . '');
            $idGeneratorKey = $column->getAttribute('idGeneratorKey') . '';

            //gives priority to sequence as default value
            if ( $idGenerator == 'sequence' )
            {
                //if no sequence name exists, sets the default
                if ( !$idGeneratorKey )
                {
                    $idGeneratorKey = 'seq_' . $columnName;
                }

                bCatalogo::criarSequenciaQuandoPossivel($idGeneratorKey);

                bBaseDeDados::executar("ALTER TABLE $tableNameWithSchema ALTER COLUMN $columnNameReserved SET DEFAULT nextval('{$idGeneratorKey}'::regclass);");
            }
            else if ( $defaultValue )
            {
                //if there is a nextval in the default value, try to get the sequence name and create it
                if ( stripos(trim($defaultValue), 'nextval') === 0 )
                {
                    $sequence = explode("('", trim($defaultValue));
                    $sequence = substr($sequence[1], 0, strlen($sequence[1]) - 2);

                    bCatalogo::criarSequenciaQuandoPossivel($sequence);
                }
                //can ALWAYS be applied, fortunately
                bBaseDeDados::executar("ALTER TABLE $tableNameWithSchema ALTER $columnNameReserved SET DEFAULT $defaultValue;");
            }

            //verification of null value possibility
            $notNullXml = $column->getAttribute('nullable') == 'true';
            $notNullDB = $columnDb->obrigatorio == DB_FALSE;

            //checks need to remove or add not null
            if ( $notNullDB != $notNullXml )
            {
                bBaseDeDados::executar($this->mountSqlNotNull($column, $tableNameWithSchema));
            }

            //always updates column comment
            $columnComment = $this->getComment($column);

            bBaseDeDados::executar("COMMENT ON COLUMN $tableNameWithSchema.$columnName IS '$columnComment';\n");
        }

        //from this point on, it is assumed the table is created and fields are synchronized
        //constraints and checks
        //stores to apply later
        $sqlCheck = ($this->getTableConstraints($xmlElement, $schema, $tableName, $db));

        //primary keys
        $sqlsPrimaryKeys = $this->getSqlPrimaryKey($xmlElement, $schema, $tableName, $primaryKeysDb);

        if ( is_array($sqlsPrimaryKeys) )
        {
            bBaseDeDados::executarBloco($sqlsPrimaryKeys);
        }

        //indexes
        $sqlsIndices = $this->getSqlIndex($xmlElement, $schema, $tableName, $indexedColumns, $db);

        if ( is_array($sqlsIndices) )
        {
            bBaseDeDados::executarBloco($sqlsIndices);
        }

        //table comment
        $columnComment = $this->getComment($xmlElement) . '';

        if ( $columnComment )
        {
            bBaseDeDados::executar("COMMENT ON TABLE $tableNameWithSchema IS '" . $columnComment . "';");
        }

        $this->sincronizarHeranca($xmlElement, $schema, $tableName);

        $tableInfo = new stdClass();
        $tableInfo->name = $tableName;
        $tableInfo->schema = $schema;
        $tableInfo->id = $tableId;
        $tableInfo->foreignKeys = $foreignKeys;
        $tableInfo->indexedColumns = $indexedColumns;
        $tableInfo->sqlCheck = $sqlCheck;

        return $tableInfo;
    }

    public function sincronizarHeranca($xmlElement, $schema, $tableName)
    {
        $ddlClauses = $xmlElement->getAttribute('ddlClauses');

        if ( $ddlClauses )
        {
            $ddlClauses = explode('(', $ddlClauses);

            if ( trim(strtolower($ddlClauses[0])) == 'inherits' )
            {
                $tabelaXml = strtolower(str_replace(')', '', $ddlClauses[1]));
                $inherit = bCatalogo::obterHeranca($schema, $tableName);
                $tabelaDB = $inherit->table;

                if ( $tabelaXml && $tabelaDB )
                {
                    if ( $tabelaXml != $tabelaDB )
                    {
                        throw new Exception("Inconsistência na herança de na tabela '$schema.$tableName', está apontando '$tabelaXml' no xml e '$tabelaDB' no banco.");
                    }
                }
                else if ( $tabelaXml && !$tabelaDB )
                {
                    $this->addMessage("Adicionando herança '$tabelaXml' na tabela '$schema.$tableName'.");

                    //FIXME make generic
                    if ( $tabelaXml == 'baslog' )
                    {
                        bCatalogo::adicionarColuna($schema, $tableName, 'username', 'varchar(20)');
                        bCatalogo::adicionarColuna($schema, $tableName, 'datetime', 'timestamptz');
                        bCatalogo::adicionarColuna($schema, $tableName, 'ipaddress', 'inet');
                        bBaseDeDados::executar("ALTER TABLE basconfig ALTER username SET default 'curent_user';");
                        bBaseDeDados::executar("ALTER TABLE basconfig ALTER datetime SET default 'now()';");
                    }

                    bBaseDeDados::executar("ALTER TABLE $schema.$tableName INHERIT $tabelaXml;");
                }
                else if ( $tabelaDB && !$tabelaXml )
                {
                    $this->addMessage("Herança '$tabelaDb' sobrando na tabela '$shema.$tableNama'.");
                }
            }
        }
    }

    /**
     * Lists the xml fields
     * 
     * @param array $xmlTableElement 
     */
    public function listColumns($xmlTableElement)
    {
        $childs = $xmlTableElement->children();
        $columns = $childs[0]->ownedMember;

        foreach ( $columns as $line => $column )
        {
            $xmiType = $column->getAttribute('xmi:type');

            if ( $xmiType == 'dbColumn' )
            {
                $result[] = $column->getAttribute('name');
            }
        }

        return $result;
    }

    /**
     * Returns an array of column stdClass objects
     * 
     * @param XmlElement $xmlTableElement
     * @return XMlElement 
     */
    public function getColumns($xmlTableElement)
    {
        $childs = $xmlTableElement->children();
        $ownedMember = $childs[0]->ownedMember;

        foreach ( $ownedMember as $line => $column )
        {
            if ( $column->getAttribute('xmi:type') == 'dbColumn' )
            {
                $columns[] = $column;
            }
        }

        return $columns;
    }

    /**
     * Applies XMI indexes to the database, if necessary.
     * Does not delete additional indexes that exist in the database.
     * 
     * @param XmlElement $xmlTableElement
     * @param string $schema schema
     * @param string $table table
     * @param bDatabase $db database execution object
     * 
     * @return XMlElement 
     */
    public function getSqlIndex($xmlTableElement, $schema, $table, $indexedColumns, $db)
    {
        $childs = $xmlTableElement->children();
        $ownedMember = $childs[0]->ownedMember;

        //extracts only the indexes
        foreach ( $ownedMember as $line => $index )
        {
            if ( $index->getAttribute('xmi:type') == 'dbIndex' )
            {
                $indexs[] = $index;
            }
        }

        //gets the database indexes (array indexed by index name)
        $indexesDb = bCatalogo::obterIndices($schema, $table);

        if ( is_array($indexs) )
        {
            foreach ( $indexs as $line => $index )
            {
                $indexColumns = $index->columns->column;
                $indexName = strtolower($index->getAttribute('name') . '');

                $realColumnNames = null;

                //searches for the real column names, since VPP only stores its internal id
                foreach ( $indexColumns as $line => $indexColumn )
                {
                    $idRef = $indexColumn->getAttribute('xmi:idref') . '';
                    //real column names
                    $realColumnNames[] = strtolower($indexedColumns[$idRef]);
                }

                //searches for this index in the database indexes
                $indexDb = $indexesDb[$indexName];

                //by default, does not create or delete indexes
                $create = false;
                $drop = false;

                if ( $indexDb )
                {
                    //if it exists but the field relation is different, determines deletion and creation
                    if ( $realColumnNames != $indexDb->columns )
                    {
                        $drop = true;
                        $create = true;
                    }
                }
                else //if the index does not exist, determines its creation
                {
                    $create = true;
                }

                //removal
                if ( $drop )
                {
                    $this->addMessage("$schema.$table: removendo índice '$indexName'.");
                    $sql[] = "DROP INDEX $indexName;";
                }

                //creation
                if ( $create )
                {
                    $sql[] = "DROP INDEX IF EXISTS  $indexName;";
                    $realColumnNames = implode(', ', $realColumnNames);
                    $this->addMessage("$schema.$table: criando índice '$indexName' nas coluna(s) '$realColumnNames'.");
                    $sql[] = "CREATE INDEX $indexName ON $schema.$table ($realColumnNames);";
                }
            }
        }

        return $sql;
    }

    /**
     * Applies constraint to the table
     * 
     * @param XmlElement $xmlTableElement xml element
     * @param string $schema schema
     * @param string $tableName table name
     * @param object $db object for executing statements, needed to get the database checks
     * @return string 
     */
    public function getTableConstraints($xmlTableElement, $schema, $tableName, $db)
    {
        //selects the constraints in the xmi
        $childs = $xmlTableElement->children();
        $constraints = $childs[1]->ownedMember;

        //gets the checks from the database
        $dbChecks = bCatalogo::obterChecagens($schema, $tableName);

        if ( is_array($constraints) )
        {
            //iterates through constraints checking need for insertion/update
            foreach ( $constraints as $line => $constraint )
            {
                $cName = $constraint->getAttribute('name');

                $checkConstraint = $constraint->getAttribute('checkConstraint');
                $type = $constraint->getAttribute('xmi:type');

                //auxiliary variables
                $found = false;
                $foundedCheck = '';
                $drop = false;

                //tries to locate the xmi checks in the database
                if ( is_array($dbChecks) )
                {
                    foreach ( $dbChecks as $line => $check )
                    {
                        //if it already exists, determines that alter must be applied
                        if ( strtolower($check->name) == strtolower($cName) )
                        {
                            //reports that it found it and what it found
                            $found = true;
                            $foundedCheck = $check->check;
                        }
                    }
                }

                //if found, tries to verify if the check was modified
                if ( $found && $foundedCheck != $checkConstraint )
                {
                    $found = false;
                    $drop = true;
                }

                //only inserts the check if it is the right type and it does not exist in the database
                if ( $type == 'dbCheckConstraint' && !$found )
                {
                    //if different, drops so it can be inserted
                    if ( $drop )
                    {
                        $sql[] = "ALTER TABLE $schema.$tableName DROP CONSTRAINT $cName;";
                    }

                    $this->addMessage("$schema.$tableName: atualizando check '$cName'.");
                    $sql[] = "ALTER TABLE $schema.$tableName ADD CONSTRAINT $cName CHECK ( $checkConstraint );";
                }
            }
        }

        return $sql;
    }

    /**
     * Gets the SQL for primary key application
     * 
     * @param type $xmlTableElement 
     * @return string $sql
     */
    public function getSqlPrimaryKey($xmlTableElement, $schema, $tableName, $primaryKeysDb = null)
    {
        $columns = $this->getColumns($xmlTableElement);

        //iterates through columns listing the primary keys
        foreach ( $columns as $line => $column )
        {
            $xmiType = $column->getAttribute('xmi:type');

            if ( $xmiType == 'dbColumn' )
            {
                if ( $column->getAttribute('primaryKey') == 'true' )
                {
                    $primaryKeys[] = $this->reservedColumnNames($column->getAttribute('name') . '');
                }
            }
        }
        
        

        //generates primary key constraint
        if ( is_array($primaryKeys) && $primaryKeys != $primaryKeysDb )
        {
            $primaryKeyConstraintName = $xmlTableElement->getAttribute('primaryKeyConstraintName');

            //if no name is defined, creates the default
            if ( !$primaryKeyConstraintName )
            {
                $primaryKeyConstraintName = "{$tableName}_pkey";
            }

            $primaryString = trim(implode(',', $primaryKeys));

            //if it already exists in the database, needs to be dropped
            if ( $primaryKeysDb )
            {
                //can be cascade; when it reaches the other tables it will create the relations
                $sql[] = "ALTER TABLE ONLY $schema.$tableName DROP CONSTRAINT $primaryKeyConstraintName CASCADE;";
            }

            //safety check
            if ( $primaryString )
            {
                $this->addMessage("$schema.$tableName: adicionando chave primária '$primaryKeyConstraintName'");
                $sql[] = "ALTER TABLE ONLY $schema.$tableName ADD CONSTRAINT $primaryKeyConstraintName PRIMARY KEY ( $primaryString );";
            }
        }

        return $sql;
    }

    /**
     * Builds table creation SQL
     * 
     * @param XmlElement $xmlElement
     * @return string
     */
    protected function mountCreateTableSql($xmlTableElement)
    {
        $tableName = $xmlTableElement->getAttribute('name');
        $schema = $xmlTableElement->getAttribute('schema');
        $schema = $schema ? $schema : 'public'; //default schema

        $this->addMessage("$schema.$tableName: criando tabela.");

        $columns = $this->getColumns($xmlTableElement);

        foreach ( $columns as $line => $column )
        {
            $xmiType = $column->getAttribute('xmi:type');

            if ( $xmiType == 'dbColumn' )
            {
                $columnsSql[] = $this->reservedColumnNames($column->getAttribute('name')) . ' ' . $this->getColumnType($column, true);
            }
        }

        //table creation clauses, used for example for inheritance
        $ddlClauses = $xmlTableElement->getAttribute('ddlClauses');

        return 'CREATE TABLE ' . $tableName . " ( \n" . implode(', ', $columnsSql) . " ) $ddlClauses;\n\n";
    }

    /**
     * Gets table or column comment
     * 
     * @param XmlElement $xmlElement
     * @return string 
     */
    public function getComment($xmlElement)
    {
        $xmi = $xmlElement->children('xmi', true);
        $xmi = $xmi[0];

        if ( $xmi )
        {
            $documentation = $xmi->children()->documentation;
            return $documentation->getAttribute('body');
        }

        return '';
    }

    /**
     * Gets the column type.
     * 
     * VPP has two columns with the type: typeName and type.
     * 
     * Type is the most correct value, but it is an integer, therefore this
     * function was created to choose the right type.
     * 
     * @param string $column 
     */
    public function getColumnType($column, $formated = false)
    {
        //if there is a user type, returns it and done
        $userType = $column->columnUserTypes->ownedMember->getAttribute('type') . '';

        if ( $userType != '' && strtolower($userType) != 'serial' )
        {
            //if not formatted, removes the size for the varchar case
            if ( $formated == false )
            {
                $userType = explode('(', $userType);
                $userType = $userType[0];
            }

            return $userType;
        }

        $typeName = $column->getAttribute('typeName');
        $length = $column->getAttribute('length');
        $typeInt = $column->getAttribute('type');

        //array that maps typeName to type
        $types = array( );
        $types[1] = 'bool';
        $types[4] = 'float4';
        $types[6] = 'double precision';
        $types[9] = 'integer';
        $types[16] = 'bigint';
        $types[17] = 'date';
        $types[22] = 'timestamp';
        $types[27] = 'varchar';
        $types[34] = 'text';
        $types[42] = 'char';

        //special conditions for VPP
        $type = $types[$typeInt];

        //if not found by type code, tries to get by name
        //this is done because sometimes VPP generates the wrong typeName
        if ( !$type )
        {
            $type = $typeName;
        }

        if ( $type == 'varchar' && $formated )
        {
            $length = $length ? $length : 255; //VPP default value
            $type .= '(' . $length . ')';
        }

        return strtolower($type);
    }

    /**
     * Converts a type from xml to the database
     * 
     * @param string $xmlType
     * 
     * @return string 
     */
    protected function xmlTypeToDB($xmlType)
    {
        $xmlType = strtolower($xmlType);

        //from-to mapping array
        $dePara['integer'] = 'int4';
        $dePara['bigint'] = 'int8';
        $dePara['char'] = 'bpchar';
        $dePara['double precision'] = 'float8';
        $dePara['timestamp with time zone'] = 'timestamptz';

        $type = $dePara[$xmlType];

        if ( !$type )
        {
            $type = $xmlType;
        }

        return $type;
    }

    /**
     * Builds SQLs for adding/removing not null
     * 
     * @param xmlElement $column
     * @param string $name table name
     * @return string sql
     */
    public function mountSqlNotNull($column, $tablename)
    {
        $columnName = $column->getAttribute('name') . '';
        $messageString = $column->getAttribute('nullable') == 'true' ? 'Removendo' : 'Adicionando';
        $this->addMessage("{$tablename}.{$columnName}: $messageString NOT NULL.");

        $nullable = $column->getAttribute('nullable') == 'true';
        $nullableString = $nullable ? 'DROP' : 'SET';

        $value = false;
        $type = $this->getColumnType($column);

        //avoids errors when applying not null
        if ( $type == 'varchar' || $type == 'text' )
        {
            $value = '';
        }
        else if ( $type == 'integer' || $type == 'bool' )
        {
            $value = '0';
        }

        $defaultValue = $column->getAttribute('defaultValue') . '';

        //if there is a default value, applies it
        //does not get default value if it has a "nextval" in it
        if ( ($defaultValue || $defaultValue == '0' ) && stripos($defaultValue, 'nextval') === false )
        {
            $value = $defaultValue;
        }

        //only adds the update when needed, only when setting
        if ( $value !== false && $nullableString == 'SET' )
        {
            $columnName = $this->reservedColumnNames($column->getAttribute('name'));
            $value = ($value == "'gnuteca'")? 'gnuteca3':$value;
            $sql = "UPDATE $tablename SET {$columnName} = '$value' WHERE {$columnName} IS NULL;\n";
        }

        $columName = $this->reservedColumnNames($column->getAttribute('name'));

        return "$sql ALTER TABLE $tablename ALTER $columName $nullableString NOT NULL;";
    }

    /**
     * Handles column name for reserved names
     * 
     * @param string $columName column name
     * @return string treated column name
     */
    public function reservedColumnNames($columName)
    {
        if ( strtolower($columName) == 'column' )
        {
            $columName = '"' . $columName . '"';
        }

        return trim(strtolower($columName));
    }
}

?>
