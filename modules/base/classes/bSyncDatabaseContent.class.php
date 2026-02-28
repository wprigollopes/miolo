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
 * Gnuteca is free software; you can redistribute it and/or modify it
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
 *
 *
 * 
 *
 **/
/**
 * Synchronizes table data with an xml
 */
class bSyncDatabaseContent
{
    /**
     * Loaded XML
     * @var SimpleXmlElement
     */
    private $xml;
    /**
     * Table name
     * @var string 
     */
    private $table;
    
    /**
     * Miolo module to syncronize
     * @var string
     */
    private $module = 'base';
    
    public function __construct( $table = null, $module =null )
    {
        if ( $table && $module )
        {
            $MIOLO = MIOLO::getInstance();
            $path = $MIOLO->getConf('home.miolo').'/modules/'.$module.'/syncdb/'.$table.'.xml';
            //$path = $MIOLO->getAbsolutePath('syncdb/'.$table.'.xml', $module);
            $this->setXmlPath( $path );
            $this->module = $module;
        }

	$this->compare = true;
    }
    
    /**
     * Sets whether to do the comparison
     * 
     * @param boolean $compare 
     */
    public function setCompare($compare)
    {
        $this->compare = $compare;
    }
    
    /**
     * Returns whether to do the comparison
     * 
     * @return type 
     */
    public function getCompare()
    {
        return $this->compare;
    }

    /**
     * Sets the access module 
     * 
     * @param string $module 
     */
    public function setModule($module)
    {
        $this->module = $module;
    }
    
    /**
     * Returns access module
     * @return string access module
     */
    public function getModule()
    {
        return $this->module;
    }
    
    /**
     * Returns an array with the base synchronization files of the specified module.
     * @param string $module
     * @return array 
     */
    public static function listSyncFiles($module)
    {
        $MIOLO = MIOLO::getInstance();
        $path = $MIOLO->getConf('home.miolo').'/modules/'.$module.'/syncdb/*.xml';
        //$path = $MIOLO->getAbsolutePath('syncdb', $module).'/*.xml'; MIOLO 2.5 only
        
        return glob($path);
    }
    
    /**
     * Sets the xml path to be interpreted
     * 
     * @param string $xmlPath 
     */
    public function setXmlPath($xmlPath)
    {
        if ( !$xmlPath )
        {
            throw new Exception ( new BString("É necessário informar um script XML.") );
        }
        
        //$this->table = str_replace('.xml', '', basename($xmlPath));
        $content = file_get_contents($xmlPath);
        
        if ( ! $content )
        {
            throw new Exception( new BString("Impossível obter conteúdo do arquivo '$xmlPath'.") );
        }

        $this->xml= new SimpleXMLElement($content);
        
        //gets the table name
        $this->table = $this->xml->getName();
    }
    
    /**
     * Synchronizes the table data
     * Does not remove any records.
     * 
     * @return stdClass 
     */
    public function syncronize()
    {
        $MIOLO = MIOLO::getInstance();
        $items = $this->xml->item;
        
        $updateCount = 0;
        $insertCount = 0;
        $deleteCount = 0;
        $result = new stdClass();
       
        $ok = bBaseDeDados::consultar( $this->mountSqlCount() );
        $result->countStart = $ok[0][0];
        $result->countXml = count($items);
        
        //performs updates
        if ( count( $items ) > 0 )
        {
            foreach ( $items as $line => $item )
            {
                $ok = $this->locateItem($item);

                if ( is_array( $ok ) )
                {
                     //Completely ignores the update
                    if ( trim($this->xml->ignoreOnUpdate[0]) != '*' )
                    {
                        $sql = $this->mountUpdateSql( $item );
                        $ok = bBaseDeDados::executar( $sql );
                    
                        $updateCount ++;
                    }
                }
                else
                {
                    $sql = $this->mountInsertSql($item);
                    $ok = bBaseDeDados::executar($sql);
                    $insertCount ++;
                }
            }
        }
        
        $deletes = $this->xml->delete;
        
        //proceeds with deletions
        if ( count( $deletes ) > 0 )
        {
            foreach ( $deletes as $line => $delete )
            {
                $ok = $this->locateItem($delete);
                
                if ( is_array( $ok ) )
                {
                    $ok = bBaseDeDados::executar( $this->mountDeleteSql($delete) );
                    $deleteCount++;
                }
            }
        }
        
        $result->updateCount = $updateCount;
        $result->insertCount = $insertCount;
        $result->deleteCount = $deleteCount;
        
        $ok = bBaseDeDados::consultar( $this->mountSqlCount() );
        $result->countEnd = $ok[0][0];
        
        //if there are differences between the xml and the final count, tries to locate extra records
        if ( $result->countEnd != $result->countXml )
        {
            $sqlListAll = $this->mountSqlSelectAll();
            
            $all = bBaseDeDados::consultar( $sqlListAll );
            $columns = bCatalogo::listarColunasDaTabela( $this->table );
            
            if ( is_array( $all) )
            {
                foreach ( $all as $line => $info )
                {
                    //converts to object
                    $info = $this->resultToObject($columns, $info);
                    
                    //builds location array
                    foreach ( $this->xml->locate as $l => $locate)
                    {
                        $locate = $locate.'';
                        $search[ $locate.''] = $info->{$locate.''};
                    }
                    
                    $achou = false;
                    
                    //searches one by one (may be slow)
                    if ( count( $items ) > 0 )
                    {
                        foreach ( $items as $line => $item )
                        {
                            $certo = false;
                            
                            foreach ($search as $word => $content )
                            {
                                if ( $content == $item->$word)
                                {
                                    $certo[] = true;
                                }
                            }
                            
                            if ( count($certo) == count( $search ) )
                            {
                                $achou = true;
                                break;
                            }
                        }
                    }

                    //if not found, adds to an array with the extra records
                    if ( !$achou)
                    {
                        $sobrando[] = $info;
                    }
                }
            }
            
            $result->extras = $sobrando;
        }
        
        return $result;
    }


    public function makeXMLfromResult($extras)
    {
        $xml = '';
    
        foreach ( (array)$extras as $line => $extra )
        {
            $xml .= "    <item>\n";
    
            foreach ( (array)$extra as $attribute => $value )
            {
                // These attributes should not appear in the sagu xml
                if( !in_array($attribute, array('datetime', 'ipaddress', 'username')) )
                {
                    //Adding CDATA support for <> / characters
                    if(preg_match('/(>|<|\/)/', $value))
                    {
                        $value = '<![CDATA[' . $value . ']]>';
                    }
                
                    $xml .= "        <$attribute>$value</$attribute>\n";
                }
            }
    
            $xml .= "    </item>\n";
        }

        return $xml;
    }

    
    
    /**
     * Tries to locate XML element in the database
     * 
     * @param XMLElement $item
     * @return array
     */
    public function locateItem( $item )
    {
        $locateString = $this->getLocateString( $item );
        
        $msql = new MSQL();
        $msql->setTables($this->table);
        $msql->setColumns('*');
        $msql->setWhere($locateString);

        return bBaseDeDados::consultar( $msql );
    }
    
    /** 
     * Converts a result to object
     * 
     * @param array $columns
     * @param array $info
     * 
     * @return stdClass 
     */
    protected function resultToObject($columns, $info)
    {
        $obj = new stdClass();
        
        foreach ( $columns as $l => $i )
        {
            $obj->{ $columns[$l] }= $info[$l];
        }
        
        return $obj;
    }
    
    /**
     * Returns the record location string
     * @param type $item 
     */
    public function getLocateString( $item )
    {
        //gets the record location parameter
        foreach ( $this->xml->locate as $l => $locate)
        {
            //defines locator item
            $itemLocator = addslashes( $item->$locate );
            
            //builds SQL location string
            if ( ! $itemLocator ) 
            {
                //special case for empty string
                $locateString[] = "( $locate = '$itemLocator' OR  $locate IS NULL ) ";
            }
            else
            {
                $locateString[] = "$locate = '$itemLocator'";
            }
        }
        
        $locateString = implode( ' AND ', $locateString );
        
        return $locateString;
    }

    /**
     * Gets the list of fields to be updated.
     * Considers the ignoreOnUpdate property of the xml
     *  
     * @param SimpleXmlElement $item
     * @return array 
     */
    public function getUpdateFields($item)
    {
        $fields = get_object_vars( $item );

        foreach ( $this->xml->ignoreOnUpdate as $l => $ignore)
        {
            unset( $fields[ $ignore.'' ]);
        }
        
        return array_keys( $fields );
    }

    /**
     * Builds update SQL for an item
     * 
     * @param SimpleXmlElement $item
     * @return string
     */
    public function mountUpdateSql( $item )
    {
        $dataString = array();
        $fields =  $this->getUpdateFields( $item );
                    
        if ( is_array( $fields ) )
        {
            foreach ( $fields as $line => $field )
            {
                $fieldContent = addslashes( $item->$field );
                $dataString[] = " $field = '$fieldContent' ";
            }

            $dataString = implode(',', $dataString);
            
            $locateString = $this->getLocateString($item);
            $sqlUpdate = "UPDATE {$this->table} SET $dataString WHERE $locateString";
            
            return $sqlUpdate;
        }
    }
    
    public function mountDeleteSql( $item )
    {
        $locateString = $this->getLocateString($item);
        $sqlDelete = "DELETE FROM {$this->table} WHERE $locateString;";
        return $sqlDelete;
    }
    
    public function mountInsertSql($item)
    {
        $dataString = array();
        
        $fields = array_keys( get_object_vars( $item ) );
        
        if ( is_array( $fields ) )
        {
            $dataString = implode(',', $dataString);
            $f = implode(',', $fields);
            $values = array_values( get_object_vars( $item ) );
            
            foreach ( $values as $l => $value )
            {
                $values[$l] = addslashes($value);
            }
            
            $values = implode("','",$values);
            
            $locateString = $this->getLocateString($item);
            $sqlInsert = "INSERT INTO {$this->table} ( $f ) VALUES ( '$values' );";
            
            return $sqlInsert;
        }
    }
    
    /**
     * Builds SQL statement for counting.
     * 
     * @return MSQL Object to count the number of records.
     */
    public function mountSqlCount()
    {
        $msql = new MSQL();
        $msql->setColumns('count(*)');
        $msql->setTables($this->table);
        
        return $msql;
    }
    
    /**
     * Builds SQL statement selecting all records
     * 
     * @return string
     */
    public function mountSqlSelectAll()
    {
        $column = implode(', ', bCatalogo::listarColunasDaTabela($this->table) );
        
        $msql = new MSQL();
        $msql->setTables($this->table);
        $msql->setColumns($column);
        
        return $msql;
        
    }
}
?>
