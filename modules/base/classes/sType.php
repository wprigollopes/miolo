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
 * Type manager class
 *
 *
 *
 *
 */

$MIOLO->uses('tipos/basLog.php', 'base');

class SType extends BasLog
{
    /**
     * Reserved MSubDetail attribute
     *
     * @var <type>
     */
    protected $removeData;

    /**
     * Attribute that stores items already populated on-demand,
     * useful for use in the type's __get() method.
     */
    protected $_checkedPopulate = array();
    
    private $_aliases = array();

    
    public function __get($name)
    {
        $name = $this->checkName($name);

        return $this->$name;
    }


    public function __set($name, $value)
    {
        $name = $this->checkName($name);
        
        $this->$name = $value;
    }
    
    
    /**
     *
     * @param type $name
     * @return type 
     */
    private function checkName($name)
    {
        // Alias
        $alias = $this->_aliases[$name];
        if ( strlen($alias) > 0 )
        {
            $name = $alias;
        }
        
        return $name;
    }


    public function getObjectVars()
    {
        return get_object_vars($this);
    }
    
    
    /**
     * Defines a pseudo attribute, which should be "redirected" to another original attribute.
     * Every time it is set (Object->aliasAttribute = 'value') the value will be set on the original attribute.
     * Every time it is retrieved (Object->aliasAttribute) the value of the original attribute will be returned.
     * Useful for cases where subdetail attributes have different names than the SType attribute.
     *
     * Remember that the pseudo attribute must also be declared as protected in the respective SType class.
     *
     * Practical usage example in the modules/training/types/TraTeam.class class
     *
     * @param type $alias Pseudo attribute name
     * @param type $attribute Source attribute, whose value should be set and retrieved
     */
    public function addAlias($alias, $attribute)
    {
        $this->_aliases[$alias] = $attribute;
    }


    /**
     * Checks whether data should be populated on demand.
     * Useful for use in the type's __get().
     *
     * WARNING: Once this function is used, the passed $name will be marked as "already populated"
     *
     * @param string $name
     */
    protected function needCheckPopulate($name)
    {
        $inArray = in_array($name, $this->_checkedPopulate);

        if ( !$inArray )
        {
            $this->_checkedPopulate[] = $name;
        }

        return ! $inArray;
    }
    
    /**
     * Gets the table name, based on the class name by default.
     *
     * @return string
     */
    public function getTableName()
    {
        return get_class($this);
    }
    
    /**
     * Gets the last inserted id
     */
    public function getLastInsertId()
    {
        return SDatabase::getLastInsertId($this->getTableName());
    }
    
    /**
     * Gets the primary key name
     */
    public function getPrimaryKey()
    {
        return SDatabase::getPrimaryKey($this->getTableName());
    }
}
