<?php

/**
 * Miolo code generator
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

global $MIOLO;
$MIOLO->uses('classes/SAGUGenerate.class.php', MIOLO::getCurrentModule());

class MIOLOGenerate
{
    public $gModule;
    public $dbConf;
    public $table;
    public $schema;
    public $template;
    public $vars;
    public $dbinfo;
    public $lookup;
    public $columns = array();
    public $attributes = array();
    public $attributesPK = array();
    public $attributesFK = array();
    public $pkeys = array();
    public $fkeys = array();
    public $substituteIds = array();
    public $author;
    public $vModule;
    private $className;
    private $typeName;
    private $SAGU;

    private $types = array(
        'integer' => 'integer',
        'text' => 'string',
        'date' => 'string',
        'boolean' => 'boolean',
        'double precision' => 'float',
        'real' => 'float',
        'numeric' => 'float',
        'time without time zone' => 'string',
        'timestamp without time zone' => 'string',
        'character' => 'character',
        'character varying' => 'string'
    );

    public function __construct($dbConf=NULL, $module=NULL, $schema=NULL, $table=NULL, $system=NULL, $host=NULL, $port=NULL, $name=NULL, $user=NULL, $password=NULL)
    {
        $MIOLO = MIOLO::getInstance();
        $this->gModule = $module;
        $this->dbConf = $dbConf;
        $this->table = $table;
        $this->schema = $schema;
        $this->lookup = false;
        $this->className = self::getClassName($table);
        $this->typeName = self::getClassName($table, $schema);

        if ( $dbConf || ( $table && $system && $host && $port && $name && $user ) )
        {
            $MIOLO->uses('classes/DBInfo.class.php', MIOLO::getCurrentModule());
            $this->dbinfo = new DBInfo($dbConf, NULL, $system, $host, $port, $name, $user, $password);
            $this->dbinfo->setSchema($schema);

            $this->columns = $this->dbinfo->getColumns($this->table);
            $this->pkeys = $this->dbinfo->getPKeys($this->table);
            $this->fkeys = $this->dbinfo->getFKeys($this->table);

            foreach ( $this->columns as $column )
            {
                $attribute = $column[0];

                if ( strpos($attribute, '_') == 4 )
                {
                    $attribute = substr($attribute, 5);
                }

                $this->attributes[] = self::generateLowerCamelCaseName($attribute);
            }

            foreach ( $this->pkeys as $pkey )
            {
                $attributePK = $pkey[0];

                if ( strpos($attributePK, '_') == 4 )
                {
                    $attributePK = substr($attributePK, 5);
                }

                $this->attributesPK[] = self::generateLowerCamelCaseName($attributePK);
            }

            foreach ( $this->fkeys as $fkey )
            {
                $attributesFK = array( );
                foreach ( $fkey as $index => $fkeyinfo )
                {
                    $attFK = $fkeyinfo;

                    if ( $index == 3 ) // from_column_name
                    {
                        $attFK = substr($attFK, 5);
                    }

                    $attributesFK[] = self::generateLowerCamelCaseName($attFK);
                }
                $this->attributesFK[] = $attributesFK;
            }
        }

        $this->SAGU = new SAGUGenerate($this);
    }

    public function generateClass()
    {
        if ( $this->substituteIds )
        {
            foreach  ( $this->attributes as $key => $attribute )
            {
                if ( $this->substituteIds[$attribute] )
                {
                    $this->attributes[$key] = $this->substituteIds[$attribute];
                }
            }
            foreach  ( $this->attributesPK as $key => $attribute )
            {
                if ( $this->substituteIds[$attribute] )
                {
                    $this->attributesPK[$key] = $this->substituteIds[$attribute];
                }
            }
            foreach  ( $this->attributesFK as $key => $attribute )
            {
                if ( $this->substituteIds[$attribute] )
                {
                    $this->attributesFK[$key] = $this->substituteIds[$attribute];
                }
            }
        }


        $empty = $this->findVars();
        $str = null;
        $i = null;

        foreach ( $empty as $var )
        {
            $str = null;
            $i = null;
            if ( $this->vars[$var] )
            {
                $this->generated = str_replace('%' . $var . '%', $this->vars[$var], $this->generated);
            }
            else
            {
                switch ( $var )
                {
                    case 'AUTHOR':
                        $this->generated = str_replace('%AUTHOR%', $this->author, $this->generated);
                        break;

                    case 'BUSINESS_ATTRIBUTES':
                        $this->generated = str_replace('%BUSINESS_ATTRIBUTES%', $this->generateBusinessAttributes(), $this->generated);
                        break;

                    case 'BUSINESS_ATTRIBUTES_METHODS':
                        $this->generated = str_replace('%BUSINESS_ATTRIBUTES_METHODS%', $this->generateBusinessAttributesMethods(), $this->generated);
                        break;

                    case 'BUSINESS_INSERT_COLUMNS':
                        $this->generated = str_replace('%BUSINESS_INSERT_COLUMNS%', $this->generateBusinessInsertColumns(), $this->generated);
                        break;

                    case 'BUSINESS_NAME':
                        $this->generated = str_replace('%BUSINESS_NAME%', lcfirst($this->className), $this->generated);
                        break;

                    case 'BUSINESS_PKEYS_PARAMETERS':
                        $parameters = $this->generatePKeysParameters();
                        $this->generated = str_replace('%BUSINESS_PKEYS_PARAMETERS%', $parameters, $this->generated);
                        break;

                    case 'BUSINESS_POPULATE_ATTRIBUTES':
                        $this->generated = str_replace('%BUSINESS_POPULATE_ATTRIBUTES%', $this->generateBusinessPopulateAttributes(), $this->generated);
                        break;

                    case 'BUSINESS_SEARCH_FILTERS':
                        $this->generated = str_replace('%BUSINESS_SEARCH_FILTERS%', $this->generateBusinessSearchFilters(), $this->generated);
                        break;

                    case 'BUSINESS_UPDATE_DATA':
                        $parameters = $this->generateDataParemeters();
                        $this->generated = str_replace('%BUSINESS_UPDATE_DATA%', $parameters, $this->generated);
                        break;

                    case 'CURRENT_DATE':
                        $this->generated = str_replace('%CURRENT_DATE%', date('d/m/Y'), $this->generated);
                        break;

                    case 'CURRENT_YEAR':
                        $this->generated = str_replace('%CURRENT_YEAR%', date('Y'), $this->generated);
                        break;

                    case 'DATABASE':
                        $conf = $this->dbConf ? $this->dbConf : $this->dbinfo->db->db;
                        $this->generated = str_replace('%DATABASE%', $conf, $this->generated);
                        break;

                    case 'FORM_CLEAR_FIELDS':
                        $this->generated = str_replace('%FORM_CLEAR_FIELDS%', $this->generateFormClearFields(), $this->generated);
                        break;

                    case 'FORM_DELETE_SETTER':
                        $this->generated = str_replace('%FORM_DELETE_SETTER%', $this->generateFormDeleteSetter(), $this->generated);
                        break;

                    case 'FORM_NAME':
                        $this->generated = str_replace('%FORM_NAME%', $this->className, $this->generated);
                        break;

                    case 'FORM_LABEL':
                        $this->generated = str_replace('%FORM_LABEL%', $this->className, $this->generated);
                        break;

                    case 'FORM_FIELDS':
                        $this->generated = str_replace('%FORM_FIELDS%', $this->generateFormFields(), $this->generated);
                        break;

                    case 'FORM_VALIDATORS':
                        $this->generated = str_replace('%FORM_VALIDATORS%', $this->generateFormValidators(), $this->generated);
                        break;

                    case 'FORM_FILTERS':
                        $this->generated = str_replace('%FORM_FILTERS%', $this->generateFormFilters(), $this->generated);
                        break;

                    case 'FORM_FILTERS_VALIDATORS':
                        $this->generated = str_replace('%FORM_FILTERS_VALIDATORS%', $this->generateFormValidators(true), $this->generated);
                        break;

                    case 'FORM_PRIMARY_KEYS':
                        $pkeys = '';
                        foreach ( $this->attributesPK as $pk )
                        {
                            $pkeys .= ", '$pk'";
                        }
                        $pkeys = substr($pkeys, 2);
                        $this->generated = str_replace('%FORM_PRIMARY_KEYS%', $pkeys, $this->generated);
                        break;

                    case 'FORM_PRIMARY_KEYS_VARS':
                        $pkeys = '';
                        foreach ( $this->attributesPK as $pk )
                        {
                            $pkeys .= ", \$$pk";
                        }
                        $pkeys = substr($pkeys, 2);
                        $this->generated = str_replace('%FORM_PRIMARY_KEYS_VARS%', $pkeys, $this->generated);
                        break;

                    case 'FORM_PRIMARY_KEYS_HIDDEN':
                        $this->generated = str_replace('%FORM_PRIMARY_KEYS_HIDDEN%', $this->generateHiddenPKFields(), $this->generated);
                        break;

                    case 'FORM_UPDATE_REQUEST':
                        $this->generated = str_replace('%FORM_UPDATE_REQUEST%', $this->generateFormUpdateRequest(), $this->generated);
                        break;

                    case 'FORM_UPDATE_SETTER':
                        $this->generated = str_replace('%FORM_UPDATE_SETTER%', $this->generateFormUpdateSetter(), $this->generated);
                        break;

                    case 'GRID_COLUMNS':
                        $this->generated = str_replace('%GRID_COLUMNS%', $this->generateGridColumns(), $this->generated);
                        break;

                    case 'LOOKUP_FIRST_FIELD':
                        $this->generated = str_replace('%LOOKUP_FIRST_FIELD%', $this->attributes[0], $this->generated);
                        break;

                    case 'LOOKUP_COLUMNS':
                        $this->generated = str_replace('%LOOKUP_COLUMNS%', $this->generateLookupColumns(), $this->generated);
                        break;

                    case 'MODULE':
                        $this->generated = str_replace('%MODULE%', $this->vModule, $this->generated);
                        break;

                    case 'MODULE_UCFIRST':
                        $this->generated = str_replace('%MODULE_UCFIRST%', ucfirst($this->vModule), $this->generated);
                        break;

                    case 'TABLE':
                        $table = $this->table;

                        if ( $this->schema && $this->schema != 'public' )
                        {
                            $table = "{$this->schema}.$table";
                        }

                        $this->generated = str_replace('%TABLE%', $table, $this->generated);
                        break;

                    case 'TABLE_INSERT_COLUMNS':
                        $this->generated = str_replace('%TABLE_INSERT_COLUMNS%', $this->generateTableInsertColumns(), $this->generated);
                        break;

                    case 'TABLE_UPDATE_COLUMNS':
                        $this->generated = str_replace('%TABLE_UPDATE_COLUMNS%', $this->generateTableUpdateColumns(), $this->generated);
                        break;

                    case 'TABLE_PRIMARY_KEYS':
                        $this->generated = str_replace('%TABLE_PRIMARY_KEYS%', $this->generateTablePrimaryKeys(), $this->generated);
                        break;

                    case 'TABLE_CLASS_NAME':
                        $this->generated = str_replace('%TABLE_CLASS_NAME%', $this->className, $this->generated);
                        break;

                    case 'TYPE_NAME':
                        $this->generated = str_replace('%TYPE_NAME%', $this->typeName, $this->generated);
                        break;

                    case 'WHERE_PRIMARY_KEYS':
                        $this->generated = str_replace('%WHERE_PRIMARY_KEYS%', $this->generateWherePrimaryKeys(), $this->generated);
                        break;

                    case 'TYPE_CHECK_PRIMARY_KEYS':
                        $this->generated = str_replace('%TYPE_CHECK_PRIMARY_KEYS%', $this->generateTypeCheckPrimaryKeys(), $this->generated);
                        break;

                    case 'TYPE_CLEAN_PRIMARY_KEYS':
                        $this->generated = str_replace('%TYPE_CLEAN_PRIMARY_KEYS%', $this->generateCleanPrimaryKeys(), $this->generated);
                        break;

                    case 'TYPE_CONSTRUCT_PARAMETERS':
                        $this->generated = str_replace('%TYPE_CONSTRUCT_PARAMETERS%', $this->generateTypeConstructParameters(), $this->generated);
                        break;

                    case 'TYPE_CONSTRUCT_CHECK_PKEYS':
                        $this->generated = str_replace('%TYPE_CONSTRUCT_CHECK_PKEYS%', $this->generateTypeConstructCheckPKeys(), $this->generated);
                        break;

                    case 'TYPE_CONSTRUCT_INIT_PKEYS':
                        $this->generated = str_replace('%TYPE_CONSTRUCT_INIT_PKEYS%', $this->generateTypeConstructInitPKeys(), $this->generated);
                        break;

                    case 'TYPE_PARAMETERS':
                        $this->generated = str_replace('%TYPE_PARAMETERS%', $this->generateTypeParameters(), $this->generated);
                        break;

                    case 'TYPE_INSERT_INITIALIZE':
                        $this->generated = str_replace('%TYPE_INSERT_INITIALIZE%', $this->generateInsertInitialize(), $this->generated);
                        break;

                    case 'TYPE_PRIMARY_KEYS':
                        $this->generated = str_replace('%TYPE_PRIMARY_KEYS%', $this->generateTypePrimaryKeys(), $this->generated);
                        break;

                    /* * * * * * * * * * * * * SAGU * * * * * * * * * * * * * */
                    default:
                        $this->generated = $this->SAGU->generate($this->generated, $var);
                }
            }
        }

        return $this->generated;
    }

    private function generateDataParemeters()
    {
        $parameters = array();

        foreach ( $this->attributes as $att )
        {
            if ( !in_array($att, $this->attributesPK) )
            {
                $parameters[] = "\$this->$att";
            }
        }

        return implode(",\n        ", $parameters);
    }
    
    public function generatePKeysParameters()
    {
        $parameters = array();

        foreach ( $this->attributesPK as $att )
        {
            $parameters[] = "\$this->$att";
        }

        return implode(",\n        ", $parameters);
    }

    private function generateFormClearFields()
    {
        $clear = '';
        foreach ( $this->attributes as $att )
        {
            $clear .= "\$this->getField('$att')->setValue('');\n";
        }
        return $clear;
    }

    private function generateFormDeleteSetter()
    {
        $setter = '';
        $i = 0;
        foreach ( $this->attributesPK as $att )
        {
            $Att = ucfirst($att);
            $setter .= "\$business->definir{$Att}(\$items[$i]);";
            $i++;
        }
        return $setter;
    }

    private function generateFormFields()
    {
        foreach ( $this->columns as $column )
        {
            $type = $column[1];
            $columnName = $column[0];
            $name = $this->substituteIds[$columnName];
            $value = '';
            $label = $columnName;
            $lookup = false;
            $maxlength = $column[5];

            if ( $this->fkeys )
            {
                foreach ( $this->fkeys as $fk )
                {
                    if ( $fk[3] == $columnName )
                    {
                        $lookup = true;

                        $fields .= $this->generateLookupTextField($name, $value, $label, $this->gModule, $fk[2]);
                        $fields .= "\$fields[] = \$$name;\n";
                    }
                }
            }
            if ( !$lookup )
            {
                $fields .= $this->generateFieldByType($name, $value, $label, $type, $maxlength);
            }
        }
        return $fields;
    }

    private function generateFormUpdateRequest()
    {
        $fields = array();
        $i = 0;
        foreach ( $this->attributesPK as $att )
        {
            $fields[] = "'$att' => \$items[$i]";
            $i++;
        }
        return implode(', ', $fields);
    }

    private function generateFormUpdateSetter()
    {
        $fields = '';
        $i = 0;
        foreach ( $this->attributes as $att )
        {
            $fields .= "\$this->{$att}->setValue(\$line[$i]);";
            $i++;
        }
        return $fields;
    }

    private function generateFormValidators($search=false)
    {
        $validators = '';
        foreach ( $this->columns as $column )
        {
            $type = $column[1];
            $columnName = $column[0];
            $name = $this->substituteIds[$columnName];
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

    private function generateHiddenPKFields()
    {
        $fields = '';

        // separates vars initializing from fields creation
        foreach ( $this->attributesPK as $pk )
        {
            $fields .= "\$$pk = MIOLO::_REQUEST('$pk');\n";
        }

        foreach ( $this->attributesPK as $pk )
        {
            $fields .= "\$fields[] = new MHiddenField('$pk', \$$pk);\n";
        }

        return $fields;
    }

    public function generateGridColumns()
    {
        $i = 0;
        $columns = '';
        $pks = '$primaryKeys = array( ';

        foreach ( $this->columns as $column )
        {
            $attribute = $this->substituteIds[$column[0]];

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

            if ( in_array($attribute, $this->attributesPK) )
            {
                $pks .= "'$attribute' => '%$i%',\n";
            }

            $i++;
        }

        $pks .= " );\n";

        return $columns . "\n" . $pks;
    }

    private function generateBusinessInsertColumns()
    {
        $columns = array();

        foreach ( $this->substituteIds as $column )
        {

            $columns[] = "\$this->$column";
        }

        return implode(', ', $columns);
    }


    private function generateTableInsertColumns()
    {
        $columns = array();

        foreach ( $this->columns as $column )
        {
            $columns[] = $column[0];
        }

        return implode(', ', $columns);
    }

    private function generateTableUpdateColumns()
    {
        $columns = array();
        $pks = array();
        foreach ( $this->pkeys as $pk )
        {
            $pks[] = $pk[0];
        }

        foreach ( $this->columns as $column )
        {
            if ( !in_array($column[0], $pks) )
            {
                $columns[] = $column[0];
            }
        }

        return implode(', ', $columns);
    }

    private function generateWherePrimaryKeys()
    {
        $pkeys = array();

        foreach ( $this->pkeys as $column )
        {
            $pkeys[] = $column[0];
        }

        return implode(' = ? AND ', $pkeys) . ' = ?';
    }

    private function generateLookupColumns()
    {
        $columns = '';

        foreach ( $this->attributes as $attribute )
        {
            $columns .= "\$columns[] = new MDataGridColumn('$attribute', _M('$attribute', \$module), 'left', true, NULL, true);\n";
        }

        return $columns;
    }

    private function generateTablePrimaryKeys()
    {
        $pkeys = array();

        foreach ( $this->pkeys as $column )
        {
            $pkeys[] = $column[0];
        }

        return implode(', ', $pkeys);
    }

    private function generateBusinessAttributes()
    {
        $atts = '';

        foreach ( $this->columns as $key => $column )
        {
            $name = $this->substituteIds[$column[0]];
            $type = $this->types[$column[1]] ? $this->types[$column[1]] : 'string';
            $description = $column[2];

            $atts .= "/**\n";
            $atts .= " * @var $type $description\n";
            $atts .= " */\n";
            $atts .= "private \$$name;";
        }

        return $atts;
    }

    private function generateBusinessAttributesMethods()
    {
        $atts = '';

        foreach ( $this->columns as $key => $column )
        {
            $name = $this->substituteIds[$column[0]];
            $Name = ucfirst($name);
            $type = $this->types[$column[1]] ? $this->types[$column[1]] : 'string';
            $description = $column[2];

            $atts .= "/**\n";
            $atts .= " * @param $type Definir $name\n";
            $atts .= " */\n";
            $atts .= "public function definir$Name(\$$name)\n";
            $atts .= "{\n";
            $atts .= "    \$this->$name = \$$name;\n";
            $atts .= "}\n";

            $atts .= "/**\n";
            $atts .= " * @return $type Obter $name\n";
            $atts .= " */\n";
            $atts .= "public function obter$Name()\n";
            $atts .= "{\n";
            $atts .= "    return \$this->$name;\n";
            $atts .= "}\n";
        }

        return $atts;
    }

    private function generateBusinessSearchFilters()
    {
        $atts = '';

        foreach ( $this->columns as $key => $column )
        {
            $name = $this->substituteIds[$column[0]];
            $type = $this->types[$column[1]] ? $this->types[$column[1]] : 'string';
            $description = $column[2];

            $atts .= "if ( \$filters->$name )\n";
            $atts .= "{\n";
            $atts .= "    \$msql->setWhere(\"$column[0] = '{\$filters->$name}'\");\n";
            $atts .= "}\n";
        }

        return $atts;
    }

    private function generateBusinessPopulateAttributes()
    {
        $atts = '';

        foreach ( $this->columns as $key => $column )
        {
            $name = $this->substituteIds[$column[0]];
            $atts .= "\$this->$name = \$dados->$name;\n";
        }

        return $atts;
    }

    private function generateTypeCheckPrimaryKeys()
    {
        $check = array();

        foreach ( $this->attributesPK as $pk )
        {
            $check[] = "strlen(\$this->$pk) == 0";
        }

        return implode(' || ', $check);
    }

    private function generateTypeConstructCheckPKeys()
    {
        $check = array();

        foreach ( $this->attributesPK as $pk )
        {
            $check[] = "(strlen(\$$pk) > 0)";
        }

        return implode(' && ', $check);
    }

    public function generateTypeParameters()
    {
        $parameters = array();

        foreach ( $this->attributes as $att )
        {
            $parameters[] = "\$this->$att";
        }

        return implode(', ', $parameters);
    }

    private function generateTypeConstructParameters()
    {
        $parameters = array();

        foreach ( $this->attributesPK as $att )
        {
            $parameters[] = "\$$att=NULL";
        }

        return implode(', ', $parameters);
    }

    

    private function generateInsertInitialize()
    {
        $args = '';
        $names = array();
        $sequential = false;

        foreach ( $this->pkeys as $pk )
        {
            // check if it is sequential
            if ( substr_compare($pk[2], 'nextval', 0) > 0 )
            {
                $name = $this->substituteIds[$pk[0]];
                $sequential = true;

                $names[] = "\$this->$name = \$$name;";
            }
        }

        if ( $sequential )
        {
            $args .= "if ( \$result )\n{\n";
            $args .= implode("\n", $names);
            $args .= "}\n";
        }

        return $args;
    }

    private function generateTypeConstructInitPKeys()
    {
        $names = array();

        foreach ( $this->attributesPK as $pk )
        {
            $names[] = "\$this->$pk = \$$pk;";
        }

        return implode("\n", $names);
    }

    private function generateTypePrimaryKeys()
    {
        $pkeys = array();

        foreach ( $this->attributesPK as $pk )
        {
            $pkeys[] = "\$this->$pk";
        }

        return implode(', ', $pkeys);
    }

    private function generateCleanPrimaryKeys()
    {
        $pkeys = array();

        foreach ( $this->attributesPK as $pk )
        {
            $pkeys[] = "\$this->$pk = null;";
        }

        return implode("\n    ", $pkeys);
    }

    private function generateFormFilters()
    {
        $fields = '';

        foreach ( $this->columns as $column )
        {
            $type = $column[1];
            $columnName = $column[0];
            $name = $this->substituteIds[$columnName];
            $value = '';
            $label = $columnName;
            $lookup = false;
            $maxlength = $column[5];

            if ( $this->fkeys )
            {
                foreach ( $this->fkeys as $fk )
                {
                    if ( $fk[3] == $columnName )
                    {
                        $lookup = true;
                        $fields .= $this->generateLookupTextField($name, $value, $label, $this->gModule, $fk[2]);
                        $fields .= "\$fields[] = \${$name};\n";
                    }
                }
            }
            if ( !$lookup )
            {
                $fields .= $this->generateFieldByType($name, $value, $label, $type);
            }
        }

        return $fields;
    }

    public function generateFieldByType($name, $value, $label, $type, $maxlength=NULL, $search=false)
    {
        $fields = '';
        $size = null;
        switch ( $type )
        {
            case 'text':
                if ( $maxlength )
                {
                    $fields .= "\$$name = new MMultilineField('$name', '$value', _M('$label', \$module));\n";
                    $fields .= "\${$name}->addAttribute('maxlength', '$maxlength');\n";
                    $fields .= "\$fields[] = \$$name;\n";
                }
                else
                {
                    $fields .= "\$fields[] = new MMultilineField('$name', '$value', _M('$label', \$module));\n";
                }

                break;

            case 'date':
                $size = 10;
                $fields .= "\$fields[] = new MCalendarField('$name', '$value', _M('$label', \$module), $size);\n";
                break;

            case 'boolean':
                if ( $search )
                {
                    $fields .= "\$fields[] = new MSelection('$name', '$value', _M('$label', \$module), SAGU::listYesNo(0));\n";
                }
                else
                {
                    $fields .= "\$fields[] = new MRadioButtonGroup('$name', _M('$label', \$module), SAGU::listYesNo(1), '$value', NULL, 'horizontal');";
                }
                break;

            case 'integer':
            case 'double precision':
            case 'real':
            case 'numeric':
            case 'time without time zone':
                $size = $size ? $size : 10;
            case 'timestamp without time zone':
            case 'character':
            case 'character varying':
            default:
                $size = $size ? $size : 20;
                if ( $maxlength )
                {
                    $fields .= "\$$name = new MTextField('$name', '$value', _M('$label', \$module), $size);\n";
                    $fields .= "\${$name}->addAttribute('maxlength', '$maxlength');\n";
                    $fields .= "\$fields[] = \$$name;\n";
                }
                else
                {
                    $fields .= "\$fields[] = new MTextField('$name', '$value', _M('$label', \$module), $size);\n";
                }
                break;
        }

        return $fields;
    }

    private function generateLookupTextField($name, $value, $label, $module, $item, $hint='')
    {
        return "\$fields[] = new MLookupContainer('$name', $value, _M('$label', \$module), '$module', '$item', true, '$hint');\n";
    }

    public function setSubstituteIds($columnIds, $formIds)
    {
        foreach ( $columnIds as $key => $columnId )
        {
            $this->substituteIds[$columnId] = $formIds[$key];
        }
    }

    public function setClassName($className)
    {
        $this->className = $className;
        $this->typeName = self::getClassName($className, $this->schema);
    }

    public function getConstArray()
    {
        return self::$const;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        $handle = fopen($this->template, "r");
        $this->generated = fread($handle, filesize($this->template));
    }

    public function setVars($vars)
    {
        $this->vars = $vars;
    }

    public function findVars()
    {
        if ( preg_match_all("/%([A-Z_]+)%/", $this->generated, $vars) > 0 )
        {
            $otherVars = $vars[1];
            $otherVars = array_unique($otherVars);
            return $otherVars;
        }
        else
        {
            return false;
        }
    }

    public static function generateUpperCamelCaseName($name)
    {
        $camelCased = '';
        foreach ( explode('_', $name) as $piece )
        {
            $camelCased .= ucfirst($piece);
        }
        return $camelCased;
    }

    public static function generateLowerCamelCaseName($name)
    {
        return lcfirst(self::generateUpperCamelCaseName($name));
    }

    public static function getClassName($table, $schema=NULL)
    {
        $className = self::generateUpperCamelCaseName($table);

        if ( $schema )
        {
            $className = self::generateUpperCamelCaseName($schema) . $className;
        }

        return $className;
    }
}
