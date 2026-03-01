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
 * read the "LICENCA.txt" file included with this software.
 *
 *
 * Class
 *
 *
 *
 *
 *
 **/
set_time_limit(0);

$MIOLO = MIOLO::getInstance();
$MIOLO->uses( 'classes/bBaseDeDados.class.php','base');

if ( !defined('DB_NAME') )
{
    define('DB_NAME', 'basic' );
}

class bCSVFileImporter
{
    /**
     * File name
     *
     * @var string
     */
    private $fileName;
    
    /**
     * File contents
     * 
     * @var string
     */
    private $fileContents;
    
    /**
     * Array of CSV contents split by line
     *
     * @var array
     */
    private $csvFileData = array();
    
    /**
     * First line of the CSV
     *
     * @var array
     */
    private $headerLine = array();
    
    /**
     * Delimiter used in the CSV file
     * 
     * @var string
     */
    private $delimiter = ';';
    
    /**
     * Indicates whether the import SQLs should be done within a transaction (BEGIN and COMMIT)
     *
     * @var boolean
     */
    private $checkTransaction = false;
    
    /**
     * Indicates that a ROLLBACK should be executed at the end of import,
     *  and undo all database inserts (useful for debugging)
     */
    private $executarRollback = false;
    
    /**
     * Indicates whether the validation has already been executed
     *
     * @var type 
     */
    private $hasChecked = false;
    
    /**
     * Indicates whether the file has already been loaded
     *
     * @var type 
     */
    private $hasLoadedFile = false;
    
    /**
     * CSV column objects that represent
     * 
     * @var array Array of bCSVColumn type
     */
    private $columns = array();
    
    /**
     * Array with errors occurred during validation
     *
     * @var array
     */
    private $errorLog = array();
    
    
    /**
     * Gets the current line being processed
     *
     * @var string
     */
    private $currentLine = array();
    
    
    /**
     * Temporary table name that stores CSV data
     * 
     * @var string
     */
    private $tmpTableName = 'tmpcsvimporter';
    
    /**
     * SQLs that should be executed before validation
     *
     * @var array
     */
    private $sqlsBefore = array();
    
    /**
     * SQLs that should be executed after validation
     *
     * @var array
     */
    private $sqlsAfter = array();
    
    
    /**
     * Limit number of records to be imported from the CSV,
     *  ordering from the first line onwards of the file.
     * 
     * @var int
     */
    private $limitRecords = 9999999;

    /**
     * Considers the first line as the CSV file header. Default FALSE.
     *
     * @var boolean
     */
    private $ignoreFirstLine = false;
    
    /**
     *
     * @var int
     */
    private $totalLines = 0;

    public function __construct()
    {
    }
    
    public function loadFile($fileName, $delimiter = null)
    {
        $this->setDelimiter($delimiter);
        $this->setFileName($fileName);
        
        $this->hasLoadedFile = true;
    }
    
    /**
     * Returns whether all CSV file content is valid (does not perform import)
     * 
     * @return boolean Returns TRUE if all validations pass
     */
    public function check()
    {
        if ( !$this->hasLoadedFile )
        {
            throw new Exception( _M('O arquivo ainda não foi carregado.') );
        }
        
        // Validates that columns have been defined
        if ( !$this->columns )
        {
            throw new Exception( _M('Devem ser definidas colunas para o arquivo CSV.') );
        }
        
        // Validates that defined columns exist in the CSV spreadsheet
        foreach ( $this->columns as $col )
        {
            if ( !in_array($col->getName(), $this->headerLine) )
            {
//                throw new Exception(_M('A coluna @1 nao esta presente no cabecalho do arquivo CSV.', null, $col->getName()));
            }
        }
        
        $countHeader = count($this->headerLine);
        $countCols = count($this->columns);
        if ( $countHeader != $countCols )
        {
            throw new Exception( _M('O numero de colunas definidas (@1) nao bate com o numero de colunas do arquivo CSV. (@2)', null, $countCols, $countHeader) );
        }
        
        // Checks for duplicate column names
        $headers = array_filter($this->headerLine);
        if ( count(array_unique($headers)) != count($headers) )
        {
//            throw new Exception( _M('Existem coluna(s) com nome repetido na planilha.') );
        }
        
        $this->createTempTable();
        $this->hasChecked = true;

        return count($this->errorLog) == 0;
    }
    
    public function getTotalLines()
    {
        return $this->totalLines;
    }

    public function setTotalLines($totalLines)
    {
        $this->totalLines = $totalLines;
    }
    
    /**
     * Imports the CSV file, only if it passed validation
     * 
     * @return boolean
     */
    public function import()
    {
        $ok = $this->check();
        
        if ( $ok )
        {
            if ( $this->getCheckTransaction() )
            {
                bBaseDeDados::iniciarTransacao();
            }
            
            $this->executarBeforeImport();

            $cols = array_merge(array('linha'), $this->generateCreateTableColumns(false));
            
            $query = bBaseDeDados::consultar("SELECT COUNT(*) FROM {$this->tmpTableName}");
            $this->setTotalLines($query[0][0]);
            
            $colsStr = implode(',', $cols);
            $rows = bBaseDeDados::consultar("SELECT {$colsStr} FROM {$this->tmpTableName} order by linha LIMIT {$this->limitRecords}");

            foreach ( $rows as $key => $row )
            {
                $line = new stdClass();
                
                foreach ( $cols as $key => $col )
                {
                    $value = $row[$key];
//                    $value = new BString($value, mb_detect_encoding($value));
//                    $value = $this->toASCII($value);
                    if ( in_array(strtolower($value), bCSVColumn::$booleanRangesAll) )
                    {
                        $value = strtolower($value);
//                        $value = $value->toLower();
                    }
//                    $value = $value->__toString();
                    $value = stripslashes($value);
                    $value = addslashes($value);
                    
                    $line->$col = $value;
                }

                $this->importLine($line);
            }
            
            $this->executarAfterImport();
            
            if ( $this->getCheckTransaction() )
            {
                $this->getExecuteRollback() ? bBaseDeDados::reverterTransacao() : bBaseDeDados::finalizarTransacao();
            }
        }
        else
        {
//            throw new Exception( _M('O arquivo CSV não está com os dados validados, verificar.') );
        }

        return $ok;
    }
    
    /**
     * Callback executed before performing the import
     */
    public function executarBeforeImport()
    {
    }

    /**
     * Callback executed after performing the import
     */
    public function executarAfterImport()
    {
    }
    
    /**
     * Iterates through each line of the CSV file
     *
     * @param type $data 
     */
    public function importLine($data)
    {
    }
    
    public function getCheckTransaction()
    {
        return $this->checkTransaction;
    }

    public function setCheckTransaction($checkTransaction)
    {
        $this->checkTransaction = $checkTransaction;
    }
    
    public function getFileName()
    {
        return $this->fileName;
    }

    private function setFileName($fileName)
    {
        if ( !file_exists($fileName) )
        {
            throw new Exception(_M('O arquivo @1 nao foi encontrado.', null, $fileName));
        }
        
        $this->fileName = $fileName;
        $this->fileContents = file_get_contents($fileName);
        $this->csvFileData = explode("\n", $this->fileContents);
        $this->headerLine = explode($this->delimiter, trim($this->csvFileData[0]));
        
        // TODO Apply other basic validations to check if CSV is correct
    }
    
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    public function setDelimiter($delimiter = null)
    {
        if ( strlen($delimiter) > 0 )
        {
            $this->delimiter = $delimiter;
        }
    }
    
    public function getErrorLog()
    {
        return $this->errorLog;
    }

    public function setErrorLog($errorLog)
    {
        $parsed = array();
        
        foreach ( (array) $errorLog as $line )
        {
            $parsed[ $line[0] ] = $line[1];
        }
        
        $this->errorLog = $parsed;
    }
    
    public function getCurrentLine()
    {
        return $this->currentLine;
    }

    public function setCurrentLine($currentLine)
    {
        $this->currentLine = $currentLine;
    }    
    
    public function getExecuteRollback()
    {
        return $this->executarRollback;
    }

    public function setExecuteRollback($executarRollback)
    {
        $this->executarRollback = $executarRollback;
    }
    
    public function getFileContents()
    {
        return $this->fileContents;
    }

    public function setFileContents($fileContents)
    {
        $this->fileContents = $fileContents;
    }

    public function getCsvFileData()
    {
        return $this->csvFileData;
    }

    public function setCsvFileData($csvFileData)
    {
        $this->csvFileData = $csvFileData;
    }

    public function getHeaderLine()
    {
        return $this->headerLine;
    }

    public function setHeaderLine($headerLine)
    {
        $this->headerLine = $headerLine;
    }

    public function getHasChecked()
    {
        return $this->hasChecked;
    }

    public function setHasChecked($hasChecked)
    {
        $this->hasChecked = $hasChecked;
    }

    public function getHasLoadedFile()
    {
        return $this->hasLoadedFile;
    }

    public function setHasLoadedFile($hasLoadedFile)
    {
        $this->hasLoadedFile = $hasLoadedFile;
    }

    public function getTmpTableName()
    {
        return $this->tmpTableName;
    }

    public function setTmpTableName($tmpTableName)
    {
        $this->tmpTableName = $tmpTableName;
    }
    
    public function getLimitRecords()
    {
        return $this->limitRecords;
    }

    public function setLimitRecords($limitRecords)
    {
        $this->limitRecords = $limitRecords;
    }

    public function getIgnoreFirstLine()
    {
        return $this->ignoreFirstLine;
    }

    public function setIgnoreFirstLine($ignoreFirstLine)
    {
        $this->ignoreFirstLine = $ignoreFirstLine;
    }

    public function getSqlsBefore()
    {
        return $this->sqlsBefore;
    }

    public function setSqlsBefore($sqlsBefore)
    {
        $this->sqlsBefore = $sqlsBefore;
    }

    public function getSqlsAfter()
    {
        return $this->sqlsAfter;
    }

    public function setSqlsAfter($sqlsAfter)
    {
        $this->sqlsAfter = $sqlsAfter;
    }

                
    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumns(array $columns)
    {
        foreach ( $columns as $col )
        {
            $this->addColumn($col);
        }
    }
    
    /**
     * Defines columns from a given array
     *
     * @param array $typeDefs
     * @param array $defs 
     */
    public function setColumnsArray(array $typeDefs, array $defs)
    {
        foreach ( $defs as $def )
        {
            $column = new bCSVColumn();
            foreach ( $typeDefs as $key => $func )
            {
                $method = 'set' . ucfirst($func);
                $value = $def[$key];
                
                if ( $value )
                {
                    $column->$method( $value );
                }
            }
            
            $this->addColumn($column);
        }
    }
    
    public function addColumn(bCSVColumn $column)
    {
        $column->_validateParams();
        
        $this->columns[] = $column;
    }
    
    /**
     * Generates a temporary table with the CSV data
     */
    private function createTempTable()
    {
        // Creates temporary table
        $colsStr = $this->generateCreateTableColumnsString(true);
        bBaseDeDados::executar("DROP TABLE IF EXISTS {$this->tmpTableName}");
        bBaseDeDados::executar("CREATE TEMP TABLE {$this->tmpTableName}({$colsStr}) WITH OIDS");

        // Imports CSV file directly via database
        bBaseDeDados::executar("COPY {$this->tmpTableName} FROM '{$this->fileName}' DELIMITERS '{$this->delimiter}' CSV");

        // Adds column with line number and errors
        bBaseDeDados::executar("ALTER TABLE {$this->tmpTableName} ADD linha SERIAL");
        bBaseDeDados::executar("ALTER TABLE {$this->tmpTableName} ADD erros TEXT");

        // Removes the first line from the file
        if ( $this->getIgnoreFirstLine() )
        {
            bBaseDeDados::executar("DELETE FROM {$this->tmpTableName} WHERE oid::int = (SELECT MIN(oid)::int FROM {$this->tmpTableName})");
        }

        // Sets the line number to match the CSV file line
        bBaseDeDados::executar("UPDATE {$this->tmpTableName} SET linha = ( oid::int - (SELECT MIN(oid)::int FROM {$this->tmpTableName}) + 1 )");

        // Deletes records beyond the limit
        bBaseDeDados::executar("DELETE FROM {$this->tmpTableName} WHERE linha > ({$this->limitRecords} + 1)");

        // Updates boolean values
        foreach ( $this->columns as $col )
        {
            $colName = $col->getName();
            bBaseDeDados::executar("UPDATE {$this->tmpTableName} SET {$colName} = TRIM({$colName})");

            if ( $col->getType() == bCSVColumn::TYPE_BOOLEAN )
            {
                $values = SAGU::quoteArrayStrings(bCSVColumn::$booleanRangesTrue);
                $values = implode(',', $values);
                bBaseDeDados::executar("UPDATE {$this->tmpTableName} SET {$colName} = 't' WHERE lower({$colName}) IN ({$values})");
                
                $values = SAGU::quoteArrayStrings(bCSVColumn::$booleanRangesFalse);
                $values = implode(',', $values);
                bBaseDeDados::executar("UPDATE {$this->tmpTableName} SET {$colName} = 'f' WHERE lower({$colName}) IN ({$values})");
            }
            
            // Performs value replacements
            $repVars = $col->getReplaceVars();
            foreach ( $repVars as $old => $new )
            {
                bBaseDeDados::executar("UPDATE {$this->tmpTableName} SET {$colName} = '{$new}' WHERE lower({$colName}) = lower('{$old}')");
            }
        }
        
        foreach ( $this->sqlsBefore as $sql )
        {
            bBaseDeDados::executar($sql);
        }
        
        // Applies validations
        $cases = $this->generateSQLValidators();
        $cases = implode(' || ', $cases);
        bBaseDeDados::executar("UPDATE {$this->tmpTableName} SET erros = {$cases}");
        bBaseDeDados::executar("UPDATE {$this->tmpTableName} SET erros = trim(both E'\n' FROM erros)");

        foreach ( $this->sqlsAfter as $sql )
        {
            bBaseDeDados::executar($sql);
        }
        
        // Collects validation errors
        $result = bBaseDeDados::consultar("SELECT linha,erros FROM {$this->tmpTableName} where erros <> '' order by linha LIMIT {$this->limitRecords}");
        $this->setErrorLog( $result );
    }
    
    /**
     * Generates columns for CREATE TABLE
     * 
     * @return array
     */
    public function generateCreateTableColumns($includeType = true)
    {
        // Generates columns for CREATE TABLE
        $cols = array();
        for ($i=0; $i < count($this->columns); $i++)
        {
            $colName = MUtil::NVL($this->columns[$i]->getName(), "col{$i}");
            $colName = trim($colName);
            $cols[] = $colName . ($includeType ? ' varchar' : null);
        }

        return $cols;
    }
    
    public function generateCreateTableColumnsString($includeType = null)
    {
        $cols = $this->generateCreateTableColumns($includeType);
        $colsStr = implode(',', $cols);
        
        return $colsStr;
    }
    
    
    /**
     * Generates SQL validation conditions for CSV lines.
     * Done via database for performance reasons.
     *
     * @return array
     */
    private function generateSQLValidators()
    {
        $col = new bCSVColumn();
        $cases = array();
        
        foreach ( $this->columns as $key => $col )
        {
            $col->setColPosition($key);
            $cases = array_merge($cases, $col->getValidateExpressions());
        }

        return $cases;
    }
    
    
    public function toASCII($string)
    {
        $value = new bString($string, 'utf-8');
        $value = $value->toASCII();
        
        return $value;
    }
    
    
}
