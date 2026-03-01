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
 * Class that represents a column of a table.
 *
 *
 *
 *
 */

class bColumnInfo
{
    /**
     * @var string 
     */
    public $schema = 'public';

    /**
     * @var string 
     */
    public $table;

    /**
     * @var string 
     */
    public $name;

    /**
     * @var string 
     */
    public $type;

    /**
     * @var string 
     */
    public $title;

    /**
     * @var string 
     */
    public $required;

    /**
     * @var string 
     */
    public $defaultValue;

    /**
     * @var string 
     */
    public $size;

    /**
     * @var character Indicates whether it is a foreign key (f) or primary key (p).
     */
    public $constraint;

    /**
     * @var string 
     */
    public $fkSchema;

    /**
     * @var string 
     */
    public $fkTable;

    /**
     * @var string 
     */
    public $fkColumn;

    /**
     * @var string 
     */
    public $possibleValues;

    /**
     * @var string 
     */
    public $editable;

    /**
     * @var string 
     */
    public $visible;

    /**
     * @var string 
     */
    public $filterable;

    /**
     * @var string 
     */
    public $showInGrid;

    /**
     * @var string 
     */
    public $parameters;

    /**
     * @var boolean 
     */
    public $chave;

    /**
     * @var string Name of the class attribute (type or business) by which the column is represented.
     */
    public $attribute;

    /**
     * @var string Field name in the form.
     */
    public $field;

    /**
     * Column type constants.
     */
    const TYPE_TEXT = 'character varying';
    const TYPE_CHAR = 'character';
    const TYPE_LONG_TEXT = 'text';
    const TYPE_INTEGER = 'integer';
    const TYPE_BIG_INTEGER = 'bigint';
    const TYPE_DECIMAL = 'real';
    const TYPE_LIST = 'list';
    const TYPE_DATE = 'date';
    const TYPE_TIMESTAMP = 'timestamp without time zone';
    const TYPE_TIME = 'time without time zone';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_DOUBLE = 'double precision';
    const TYPE_INTERVAL = 'interval';

    /**
     * @return array Lists the supported field types.
     */
    public static function listarTipos()
    {
        return array(
            self::TYPE_TEXT => _M('Texto'),
            self::TYPE_LONG_TEXT => _M('Texto longo'),
            self::TYPE_INTEGER => _M('Integer'),
            self::TYPE_DECIMAL => _M('Decimal'),
            self::TYPE_NUMERIC => _M('Numérico'),
            self::TYPE_LIST => _M('Lista'),
            self::TYPE_DATE => _M('Data'),
            self::TYPE_TIMESTAMP => _M('Timestamp'),
            self::TYPE_BOOLEAN => _M('Boolean')
        );
    }

    /**
     *
     * @return boolean
     */
    public function eChavePrimaria()
    {
        return $this->constraint == 'p';
    }
}
