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
 *
 **/
class bCSVColumn
{
    const TYPE_STRING = 1;
    const TYPE_INT = 2;
    const TYPE_DATE = 3;
    const TYPE_DOUBLE = 4;
    const TYPE_BOOLEAN = 5;
    const TYPE_SEX = 6;
    const TYPE_YEAR = 7;
    const TYPE_CHAR = 8;
    const TYPE_ESTADO = 9;
    
    // Boolean type fields
    public static $booleanRangesAll = array('t', 's', 'sim',
                                            'f', 'n', 'nao');
    public static $booleanRangesTrue = array('t', 's', 'sim');
    public static $booleanRangesFalse = array('f', 'n', 'nao');
    
    // Sex/gender type fields
    public static $sexRanges = array('F','M');
    
    /**
     * Column name in the CSV file
     * 
     * @var string
     */
    private $name;
    
    /**
     * Defines a label for friendly display in messages for this column
     *
     * @var string 
     */
    private $label;
    
    /**
     * Indicates whether the column is required in the CSV file
     *
     * @var boolean 
     */
    private $isRequired = false;
    
    /**
     * Indicates whether it must be unique
     *
     * @var boolean
     */
    private $isUnique = false;
    
    /**
     * Column data type
     *
     * @var string
     */
    private $type;
    
    /**
     * Array with values that should be replaced
     *
     * @var array 
     */
    private $replaceVars = array();
    
    /**
     * Table and column name in the database (example: Basphysicalperson.name)
     * 
     * @var string
     */
    private $databaseColumn;
    
    /**
     * Defines the minimum character limit
     *
     * @var int
     */
    private $minLength = 0;
    
    /**
     * Defines the maximum character limit
     *
     * @var int
     */
    private $maxLength = 1000;
    
    /**
     * Restricts this CSV field to only have these values.
     * Example: array('M', 'F') - validates that the CSV field can only have these values.
     *
     * @var string
     */
    private $restrictValues = array();
    
    /**
     * Start range of the interval
     *
     * @var int
     */
    private $rangeStart = null;
    
    /**
     * End range of the interval
     *
     * @var int
     */
    private $rangeEnd = null;
    
    
    /**
     * Position of this column in the bCSVFileImporter array
     *
     * @var int
     */
    private $colPosition = null;
    
    
    public function __construct()
    {
        $this->setType(self::TYPE_STRING);
    }
    
    /**
     * Checks if the provided data is sufficient to read the CSV file
     *
     * @throws Exception 
     */
    public function _validateParams()
    {
        if ( strlen($this->name) <= 0 )
        {
            throw new Exception( _M('Não foi definido um nome para a coluna.') );
        }
        
        if ( strlen($this->label) <= 0 )
        {
            throw new Exception( _M('Não foi definido um label para a coluna.') );
        }
    }
    
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getIsRequired()
    {
        return $this->isRequired;
    }

    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;
    }

    public function getIsUnique()
    {
        return $this->isUnique;
    }

    public function setIsUnique($isUnique)
    {
        $this->isUnique = $isUnique;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if ( strlen($type) > 0 )
        {
            // Restricts boolean values
            if ( $type == self::TYPE_BOOLEAN )
            {
                $this->setRestrictValues(self::$booleanRangesAll);
            }
            else if ( $type == self::TYPE_SEX )
            {
                $this->setRestrictValues(self::$sexRanges);
            }
            else if ( $type == self::TYPE_YEAR )
            {
                $this->setMinAndMaxLength(4);
                $this->setRanges(1000, 5000);
            }
            else if ( $type == self::TYPE_CHAR )
            {
                $this->setMinAndMaxLength(1);
            }
            else if ( $type == self::TYPE_ESTADO )
            {
                $this->setMinAndMaxLength(2);
                $this->setRestrictValues(array('AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'));
            }

            $this->type = $type;
        }
    }


    public function getDatabaseColumn()
    {
        return $this->databaseColumn;
    }

    public function setDatabaseColumn($databaseColumn)
    {
        $this->databaseColumn = $databaseColumn;
    }

    public function getMinLength()
    {
        return $this->minLength;
    }

    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;
    }

    public function getMaxLength()
    {
        return $this->maxLength;
    }

    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
    }
    
    public function setMinAndMaxLength($minMaxLength)
    {
        $this->minLength = $minMaxLength;
        $this->maxLength = $minMaxLength;
    }
    
    public function getRestrictValues()
    {
        return $this->restrictValues;
    }

    public function setRestrictValues($restrictValues)
    {
        $this->restrictValues = $restrictValues;
    }
    
    public function getRangeStart()
    {
        return $this->rangeStart;
    }

    public function getRangeEnd()
    {
        return $this->rangeEnd;
    }

    /**
     * Defines the allowed range limit for the field value
     * 
     * @param int $rangeStart
     * @param int $rangeEnd
     */
    public function setRanges($rangeStart, $rangeEnd)
    {
        $this->rangeStart = $rangeStart;
        $this->rangeEnd = $rangeEnd;
    }
        
    public function getReplaceVars()
    {
        return $this->replaceVars;
    }

    public function setReplaceVars($replaceVars)
    {
        if ( $replaceVars )
        {
            $this->replaceVars = $replaceVars;
        }
    }
    
    public function getColPosition()
    {
        return $this->colPosition;
    }

    public function setColPosition($colPosition)
    {
        $this->colPosition = $colPosition;
    }
        
    /**
     * Gets SQL validation expressions
     * 
     * @return array
     */
    public function getValidateExpressions()
    {
        $cases = array();
        
        $colName = $this->getName();
        $colLabel = '"' . $this->getLabel() . '"';
        $minLength = $this->getMinLength();
        $maxLength = $this->getMaxLength();

        // Required field validation
        if ( $this->getIsRequired() )
        {
            $cases[] = "(CASE WHEN {$colName} = '' OR {$colName} IS NULL THEN 'O campo {$colLabel} e requerido.\n' ELSE '' END)";
        }

        // Character limit validation
        $cases[] = "(CASE WHEN ( CHAR_LENGTH({$colName}) > 0 ) AND ( CHAR_LENGTH({$colName}) NOT BETWEEN {$minLength} AND {$maxLength} ) THEN 'O campo {$colLabel} deve possuir entre {$minLength} e {$maxLength} caracteres.\n' ELSE '' END)";

        // Restricted values validation
        if ( count($this->restrictValues) > 0 )
        {
            $restrict = SAGU::quoteArrayStrings($this->restrictValues, null, 'strtolower');
            $restrict = implode(',', $restrict);
            $restrictDisplay = str_replace("'", "\"", $restrict);
            $cases[] = "(CASE WHEN CHAR_LENGTH({$colName}) > 0 AND (lower({$colName}) NOT IN ({$restrict})) THEN 'O campo {$colLabel} esta deve possuir apenas um dos seguintes valores: {$restrictDisplay}.\n' ELSE '' END)";
        }
        
        // Range validation
        if ( strlen($this->rangeStart) > 0 && strlen($this->rangeEnd) > 0 )
        {
            $cases[] = "( CASE WHEN CHAR_LENGTH({$colName}) > 0 AND ({$colName}::int NOT BETWEEN {$this->rangeStart}::int AND {$this->rangeEnd}::int) THEN 'O campo {$colLabel} deve estar no intervalo entre {$this->rangeStart} e {$this->rangeEnd}.\n' ELSE '' END)";
        }

        switch ( $this->getType() )
        {
            // Integer validation
            case bCSVColumn::TYPE_INT:
                $cases[] = "(CASE WHEN ( CHAR_LENGTH({$colName}) > 0 ) AND ( NOT({$colName} ~ '^([0-9]+)$') ) THEN 'O campo {$colLabel} deve ser numerico.\n' ELSE '' END)";
                break;

            // Date validation

            // CPF validation
        }
        
        return $cases;
    }
}
?>
