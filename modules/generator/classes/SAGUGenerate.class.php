<?php

/**
 * SAGU code generator
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/06/17
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2011 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 *
 * \b History: \n
 * See history in CVS repository: http://www.miolo.org.br
 *
 */
class SAGUGenerate
{
    /**
     * @var object MIOLOGenerate instance
     */
    private $mg;

    public function __construct($MIOLOGenerate)
    {
        $this->mg = $MIOLOGenerate;
    }

    public function generate($generated, $var)
    {
        switch ( $var )
        {
            case 'SAGU_FORM_FIELDS':
                $generated = str_replace('%SAGU_FORM_FIELDS%', $this->generateFormFields(), $generated);
                break;

            case 'SAGU_FORM_VALIDATORS':
                $generated = str_replace('%SAGU_FORM_VALIDATORS%', $this->generateFormValidators(), $generated);
                break;

            case 'SAGU_FORM_FILTERS':
                $generated = str_replace('%SAGU_FORM_FILTERS%', $this->generateFormFilters(), $generated);
                break;

            case 'SAGU_FORM_FILTERS_VALIDATORS':
                $generated = str_replace('%SAGU_FORM_FILTERS_VALIDATORS%', $this->generateFormValidators(true), $generated);
                break;

            case 'SAGU_GRID_COLUMNS':
                $generated = str_replace('%SAGU_GRID_COLUMNS%', $this->generateGridColumns(), $generated);
                break;

            case 'SAGU_INSERT_COLUMNS':
                $generated = str_replace('%SAGU_INSERT_COLUMNS%', $this->generateInsertColumns(), $generated);
                break;

            case 'SAGU_LOOKUP_COLUMNS':
                $generated = str_replace('%SAGU_LOOKUP_COLUMNS%', $this->generateLookupColumns(), $generated);
                break;

            case 'SAGU_LOOKUP_FILTERS':
                $generated = str_replace('%SAGU_LOOKUP_FILTERS%', $this->generateFormFields(true), $generated);
                break;

            case 'SAGU_LOOKUP_SQL_FILTERS':
                $generated = str_replace('%SAGU_LOOKUP_SQL_FILTERS%', $this->generateSqlFilters(true), $generated);
                break;

            case 'SAGU_POPULATE_COLUMNS':
                $generated = str_replace('%SAGU_POPULATE_COLUMNS%', $this->generateTableColumns(true), $generated);
                break;

            case 'SAGU_SQL_FILTERS':
                $generated = str_replace('%SAGU_SQL_FILTERS%', $this->generateSqlFilters(), $generated);
                break;

            case 'SAGU_TABLE_COLUMNS':
                $generated = str_replace('%SAGU_TABLE_COLUMNS%', $this->generateTableColumns(), $generated);
                break;

            case 'SAGU_TYPE_ATTRIBUTES':
                $generated = str_replace('%SAGU_TYPE_ATTRIBUTES%', $this->generateTypeAttributes(), $generated);
                break;

            case 'SAGU_TYPE_INSERT_ARGS':
                $generated = str_replace('%SAGU_TYPE_INSERT_ARGS%', $this->generateInsertArgs(), $generated);
                break;

            case 'SAGU_TYPE_INSERT_VALUES':
                $generated = str_replace('%SAGU_TYPE_INSERT_VALUES%', $this->generateInsertValues(), $generated);
                break;

            case 'SAGU_TYPE_UPDATE_PARAMETERS':
                $parameters = $this->generateDataParemeters();
                $parameters .= ', ' . $this->mg->generatePKeysParameters();

                $generated = str_replace('%SAGU_TYPE_UPDATE_PARAMETERS%', $parameters, $generated);
                break;

            case 'SAGU_TYPE_UPDATE_SET':
                $generated = str_replace('%SAGU_TYPE_UPDATE_SET%', $this->generateUpdateSet(), $generated);
                break;
        }

        return $generated;
    }

    private function generateDataParemeters()
    {
        $parameters = array();

        foreach ( $this->mg->attributes as $att )
        {
            if ( $att == 'username' ||
                 $att == 'datetime' ||
                 $att == 'ipaddress' )
            {
                continue;
            }

            if ( !in_array($att, $this->mg->attributesPK) )
            {
                $parameters[] = "\$this->$att";
            }
        }

        return implode(",\n        ", $parameters);
    }

    public function generateFieldByType($name, $value, $label, $type, $maxlength, $hint, $lookupForm=false)
    {
        $fields = '';
        $size = self::getFieldSize($type, $lookupForm);

        if ( !$lookupForm )
        {
            $value = "\$this->getFormValue('$name', \$data->$name)";
        }

        switch ( $type )
        {
            case 'text':
                $rows = "SAGU::getParameter('BASIC', 'FIELD_MULTILINE_NUM_ROWS')";
                $cols = "SAGU::getParameter('BASIC', 'FIELD_MULTILINE_NUM_COLS')";

                if ( $maxlength )
                {
                    $fields .= "\$fields[] = \$$name = new MMultilineField('$name', $value, _M('$label', \$module), $size, $rows, $cols);\n";
                    $fields .= "\${$name}->addAttribute('maxlength', '$maxlength');\n";
                }
                else
                {
                    $fields .= "\$fields[] = \$$name = new MMultilineField('$name', $value, _M('$label', \$module), $size, $rows, $cols);\n";
                }
                break;

            case 'date':
                $fields .= "\$fields[] = \$$name = new MCalendarField('$name', $value, _M('$label', \$module), $size);\n";
                break;

            case 'boolean':
                if ( $search )
                {
                    $fields .= "\$fields[] = \$$name = new MSelection('$name', $value, _M('$label', \$module), SAGU::listYesNo(0));\n";
                }
                else
                {
                    $fields .= "\$fields[] = \$$name = new MRadioButtonGroup('$name', _M('$label', \$module), SAGU::listYesNo(1), $value, NULL, 'horizontal');";
                }
                break;

            case 'integer':
            case 'double precision':
            case 'real':
            case 'numeric':
            case 'time without time zone':
            case 'timestamp without time zone':
            case 'character':
            case 'character varying':
            default:
                if ( $maxlength )
                {
                    $fields .= "\$fields[] = \$$name = new MTextField('$name', $value, _M('$label', \$module), $size);\n";
                    $fields .= "\${$name}->addAttribute('maxlength', '$maxlength');\n";
                }
                else
                {
                    $fields .= "\$fields[] = \$$name = new MTextField('$name', $value, _M('$label', \$module), $size);\n";
                }
                break;
        }

        $hint = $hint ? lcfirst($hint) : lcfirst($label);
        $fields .= "\${$name}->setJsHint(_M('Informe o $hint', \$module));\n";

        return $fields;
    }

    public function generateFormFields($lookupForm=false)
    {
        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $type = $column[1];
            $columnName = $column[0];
            $name = $this->mg->substituteIds[$columnName];
            $value = "''";
            $label = $column[2] ? ucfirst($column[2]) : $name;
            $fieldIsLookup = false;
            $maxlength = $column[5];
            $hint = $column[2];

            if ( $this->mg->fkeys )
            {
                foreach ( $this->mg->fkeys as $fk )
                {
                    if ( $fk[3] == $columnName )
                    {
                        $fieldIsLookup = true;

                        list($module, $item) = self::getModuleItemForLookup($fk[1], $fk[2]);
                        $fields .= $this->generateSLookupContainer($name, $value, $label, $module, $item, $hint);
                    }
                }
            }
            if ( !$fieldIsLookup )
            {
                $fields .= $this->generateFieldByType($name, $value, $label, $type, $maxlength, $hint, $lookupForm);
            }
        }
        return $fields;
    }

    public function generateFormFilters()
    {
        $fields = '';

        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $type = $column[1];
            $columnName = $column[0];
            $name = $this->mg->substituteIds[$columnName];
            $value = "''";
            $label = $column[2] ? ucfirst($column[2]) : $name;
            $fieldIsLookup = false;
            $maxlength = $column[5];
            $hint = $column[2];

            if ( $this->mg->fkeys )
            {
                foreach ( $this->mg->fkeys as $fk )
                {
                    if ( $fk[3] == $columnName )
                    {
                        $fieldIsLookup = true;

                        list($module, $item) = self::getModuleItemForLookup($fk[1], $fk[2]);
                        $fields .= $this->generateSLookupContainer($name . 'S', $value, $label, $module, $item, $hint);
                    }
                }
            }
            if ( !$fieldIsLookup )
            {
                $fields .= $this->generateFieldByType($name . 'S', $value, $label, $type, $maxlength, $hint);
            }
        }

        return $fields;
    }

    public function generateFormValidators($search=false)
    {
        $validators = '';
        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $type = $column[1];
            $columnName = $column[0];
            $name = $this->mg->substituteIds[$columnName];
            $value = '';
            $label = $columnName;
            $lookup = false;

            // search form does not have required validators
            if ( !$search )
            {
                $required = $column[3] == 't' ? 'required' : 'optional';
            }
            else
            {
                $name = $name . 'S';
                $required = 'optional';
            }

            switch ( $type )
            {
                case 'integer':
                    $validators .= "\$validators[] = new MIntegerValidator('$name', '', '$required');\n";
                    break;

                case 'text':
                case 'date':
                case 'boolean':
                case 'double precision':
                case 'real':
                case 'numeric':
                case 'time without time zone':
                case 'timestamp without time zone':
                case 'character':
                case 'character varying':
                default:
                    if ( $required == 'required' )
                    {
                        $validators .= "\$validators[] = new MRequiredValidator('$name');\n";
                    }
                    break;
            }
        }

        return $validators;
    }

    public function generateGridColumns()
    {
        $i = 0;
        $columns = '';
        $pks = '$primaryKeys = array( ';

        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $attribute = $this->mg->substituteIds[$column[0]];

            switch ( $column[1] )
            {
                case 'integer':
                    $align = 'right';
                    break;

                case 'boolean':
                    $align = 'center';
                    break;

                default:
                    $align = 'left';
                    break;
            }

            $columns .= "\$columns[] = new MGridColumn(_M('$attribute', \$module), '$align', true, NULL, true, NULL, true);\n";

            if ( in_array($attribute, $this->mg->attributesPK) )
            {
                $pks .= "'$attribute' => '%$i%',\n";
            }

            $i++;
        }

        $pks .= " );\n";

        return $columns . "\n" . $pks;
    }

    public function generateInsertArgs()
    {
        $args = '';
        $names = array( );
        $sequential = false;

        foreach ( $this->mg->pkeys as $pk )
        {
            // check if it is sequential
            if ( substr_compare($pk[2], 'nextval', 0) > 0 )
            {
                $name = $this->mg->substituteIds[$pk[0]];
                $sequential = true;

                $args .= "\$sqlPK = \"SELECT $pk[2]\";";
                $args .= '$result = SDatabase::query($sqlPK);';
                $args .= "\$$name = \$result[0][0];";

                $names[] = "\$$name";
            }
        }

        $args .= "\$params = array(\n";
        if ( !$sequential )
        {
            $args .= $this->mg->generateTypeParameters();
        }
        else
        {
            foreach ( $this->mg->attributes as $att )
            {
                if ( $att == 'username' ||
                     $att == 'datetime' ||
                     $att == 'ipaddress' )
                {
                    continue;
                }

                if ( !in_array($att, $this->mg->attributesPK) )
                {
                    $names[] = "\$this->$att";
                }
            }
            $args .= implode(",\n    ", $names);
        }
        $args .= ");\n";

        return $args;
    }

    private function generateInsertColumns()
    {
        $columns = array();

        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $columns[] = $column[0];
        }

        return implode(', ', $columns);
    }

    public function generateInsertValues()
    {
        $insert = array( );

        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $value = '?';

            switch ( $column[1] )
            {
                case 'date':
                    $value = "TO_DATE(?, \'' . SAGU::getParameter('BASIC', 'MASK_DATE') . '\')";
                    break;

                case 'timestamp with time zone':
                case 'timestamp without time zone':
                    $value = "TO_TIMESTAMP(?, \'' . SAGU::getParameter('BASIC', 'MASK_TIMESTAMP') . '\')";
                    break;

                case 'time':
                    $value = "TO_TIMESTAMP(?, \'' . SAGU::getParameter('BASIC', 'MASK_TIME') . '\')";
                    break;
            }

            $insert[] = $value;
        }

        return implode(', ', $insert);
    }

    private function generateLookupColumns()
    {
        $columns = '';

        foreach ( $this->mg->attributes as $attribute )
        {
            if ( $attribute == 'username' ||
                 $attribute == 'datetime' ||
                 $attribute == 'ipaddress' )
            {
                continue;
            }

            $columns .= "\$columns[] = new MDataGridColumn('$attribute', _M('$attribute', \$module), 'left', true, NULL, true);\n";
        }

        return $columns;
    }

    public function generateSLookupContainer($name, $value, $label, $module, $item, $hint='')
    {
        $lookup = "\$opts = array(\n";
        $lookup .= "    'label' => _M('$label', \$module),\n";
        $lookup .= "    'item' => '$item',\n";
        $lookup .= "    'module' => '$module',\n";
        $lookup .= "    'related' => array( '{$name}Description' ),\n";
        if ( $hint )
        {
            $lookup .= "    'hint' => _M('$hint', \$module),\n";
        }
        $lookup .= ");\n";
        $lookup .= "\$fields[] = new SLookupContainer('$name', \$data->$name, \$opts);\n";

        return $lookup;
    }

    public function generateSqlFilters($lookup=false)
    {
        $filters = '';

        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $name = $this->mg->substituteIds[$column[0]];
            $where = '';
            $percent = '';
            $columnName = $column[0];

            switch ( $column[1] )
            {
                case 'date':
                    $where = "$columnName = TO_DATE(?, \'' . SAGU::getParameter('BASIC', 'MASK_DATE') . '\')";
                    break;

                case 'timestamp with time zone':
                case 'timestamp without time zone':
                    $where = "$columnName = TO_TIMESTAMP(?, \'' . SAGU::getParameter('BASIC', 'MASK_TIMESTAMP') . '\')";
                    break;

                case 'time':
                    $where = "$columnName = TO_TIMESTAMP(?, \'' . SAGU::getParameter('BASIC', 'MASK_TIME') . '\')";
                    break;

                case 'text':
                case 'character varying':
                case 'varchar':
                    $where = "TO_ASCII($columnName) ILIKE TO_ASCII(?) ";
                    $percent = " . '%'";
                    break;

                default:
                    $where = "$columnName = ?";
                    break;
            }

            if ( !$lookup )
            {
                $name = "filters->$name";
            }

            $filters .= "
            if ( strlen(\$$name) > 0 )
            {
                \$where .= ' AND $where';
                \$params[] = \$$name$percent;
            }";
        }

        return $filters;
    }

    public function generateTableColumns($isPopulate=false)
    {
        $columns = array( );

        foreach ( $this->mg->columns as $column )
        {
            if ( !$isPopulate && ($column[0] == 'username' ||
                                  $column[0] == 'datetime' ||
                                  $column[0] == 'ipaddress') )
            {
                continue;
            }

            $columnName = $column[0];

            switch ( $column[1] )
            {
                case 'date':
                    $columns[] = "TO_CHAR($columnName, \'' . SAGU::getParameter('BASIC', 'MASK_DATE') . '\')";
                    break;

                case 'timestamp with time zone':
                case 'timestamp without time zone':
                    $columns[] = "TO_CHAR($columnName, \'' . SAGU::getParameter('BASIC', 'MASK_TIMESTAMP') . '\')";
                    break;

                case 'time':
                    $columns[] = "TO_CHAR($columnName, \'' . SAGU::getParameter('BASIC', 'MASK_TIME') . '\')";
                    break;

                default:
                    $columns[] = $columnName;
                    break;
            }
        }

        // spaces to let the code less ugly
        return implode(",\n                       ", $columns);
    }

    private function generateTypeAttributes()
    {
        $atts = '';

        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $name = $this->mg->substituteIds[$column[0]];
            $type = $column[1];
            $description = $column[2];

            $atts .= "/**\n";
            $atts .= " * @AttributeType $type\n";
            $atts .= " * $description\n";
            $atts .= " */\n";
            $atts .= "protected \$$name;";
        }

        return $atts;
    }

    public function generateUpdateSet()
    {
        $update = array( );

        foreach ( $this->mg->columns as $column )
        {
            if ( $column[0] == 'username' ||
                 $column[0] == 'datetime' ||
                 $column[0] == 'ipaddress' )
            {
                continue;
            }

            $set = '';
            $name = $this->mg->substituteIds[$column[0]];
            $columnName = $column[0];

            if ( in_array($name, $this->mg->attributesPK) )
            {
                continue;
            }

            switch ( $column[1] )
            {
                case 'date':
                    $set = "$columnName = TO_DATE(?, \'' . SAGU::getParameter('BASIC', 'MASK_DATE') . '\')";
                    break;

                case 'timestamp with time zone':
                case 'timestamp without time zone':
                    $set = "$columnName = TO_TIMESTAMP(?, \'' . SAGU::getParameter('BASIC', 'MASK_TIMESTAMP') . '\')";
                    break;

                case 'time':
                    $set = "$columnName = TO_TIMESTAMP(?, \'' . SAGU::getParameter('BASIC', 'MASK_TIME') . '\')";
                    break;

                default:
                    $set = "$columnName = ?";
                    break;
            }

            $update[] = $set;
        }

        return implode(",\n                       ", $update);
    }

    /* STATIC METHODS */

    /**
     *
     * @param type $schema
     * @param type $table
     * @return type 
     */
    public static function getModuleItemForLookup($schema, $table)
    {
        $module = '';
        $item = '';

        $modules = array(
            'acc' => 'accountancy',
            'acd' => 'academic',
            'adm' => 'admin',
            'bas' => 'basic',
            'ccp' => 'controlCopies',
            'fin' => 'finance',
            'hur' => 'humanResources',
            'ins' => 'institutional',
            'res' => 'residency',
            'rsh' => 'research',
            'spr' => 'selectiveProcess',
            'tra' => 'training',
        );

        if ( $schema == 'public' )
        {
            $module = $modules[substr($table, 0, 3)];
            $item = MIOLOGenerate::getClassName(substr($table, 3));
        }
        else
        {
            $module = $modules[$schema];
            $item = MIOLOGenerate::getClassName($table);
        }

        return array( $module, $item );
    }

    public static function getFieldSize($type, $lookup=false)
    {
        switch ( $type )
        {
            case 'time':
            case 'time with time zone':
            case 'time without time zone':
            case 'timestamp with time zone':
            case 'timestamp without time zone':
                $size = $lookup ?
                        "SAGU::getParameter('BASIC', 'FIELD_TIME_LOOKUP_SIZE')" :
                        "SAGU::getParameter('BASIC', 'FIELD_TIME_SIZE')";
                break;

            case 'date':
                $size = $lookup ?
                        "SAGU::getParameter('BASIC', 'FIELD_DATE_LOOKUP_SIZE')" :
                        "SAGU::getParameter('BASIC', 'FIELD_DATE_SIZE')";
                break;

            case 'inet':
            case 'text':
            case 'character':
            case 'character varying':
                $size = $lookup ?
                        "SAGU::getParameter('BASIC', 'FIELD_DESCRIPTION_LOOKUP_SIZE')" :
                        "SAGU::getParameter('BASIC', 'FIELD_DESCRIPTION_SIZE')";
                break;

            case 'integer':
                $size = $lookup ?
                        "SAGU::getParameter('BASIC', 'FIELD_ID_LOOKUP_SIZE')" :
                        "SAGU::getParameter('BASIC', 'FIELD_ID_SIZE')";
                break;

            case 'double precision':
            case 'numeric':
            case 'real':
                $size = "SAGU::getParameter('BASIC', 'FIELD_MONETARY_SIZE')";
                break;

            case 'timestamp':
                $size = "SAGU::getParameter('BASIC', 'FIELD_EDITOR_SIZE')";
                break;
        }

        return $size;
    }
}

?>