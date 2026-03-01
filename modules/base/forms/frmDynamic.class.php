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
 * Dynamic registration form
 *
 *
 */

$MIOLO->uses('tipos/cadastroDinamico.class.php', 'base');
class frmDynamic extends bFormRegistration
{
    /**
     * Restricts columns that should not have fields generated in the form.
     *
     * @var type
     */
    public $restrictColumns = array(
        "username", 
        "datetime", 
        "ipaddress",
        "datetimecriacaoregistro",
        "usernamecriacaoregistro",
        "superuser"
    );
            
    /**
     * @var array Array of objects with the table columns.
     */
    protected $colunas = null;
    
    /**
     * @var array Array with the field names in order.
     */
    private $fieldOrder = null;
    
    public function __construct($parametros, $titulo=NULL)
    {
        parent::__construct(_M($titulo, MIOLO::getCurrentModule()), $parametros);
    }

    /**
     * Overridden method to define dynamic fields.
     *
     * @param boolean $montarCampos True if dynamic fields need to be built.
     * @param boolean $barraDeFerramentas True if the toolbar needs to be built.
     */
    public function buildFields($montarCampos=TRUE, $barraDeFerramentas=TRUE)
    {
        parent::buildFields($barraDeFerramentas);
        
        if ( $montarCampos )
        {
            // Gets the fields and validators for the form.
            $fieldsAndValidators = $this->generateFields();

            $fields = $fieldsAndValidators[0];

            // Gets the MSubDetail components
            $camposSubDetail = $this->generateSubDetailFields();

            // Merges the MSubDetail components with the remaining fields.
            if ( is_array($camposSubDetail) )
            {
                $fields = array_merge($fields, $camposSubDetail);
            }

            // Performs field ordering if necessary.
            if ( $this->fieldOrder )
            {
                $unsortedFields = $fields;
                $fields = array();

                foreach ( $this->fieldOrder as $fieldName )
                {
                    $fields[$fieldName] = $unsortedFields[$fieldName];
                }
            }

            $this->addFields($fields);
            $this->setValidators($fieldsAndValidators[1]);
        }
    }
    
    /**
     * Generates the filters and columns.
     *
     * @return array Array with the filters, the grid column and the keys to be passed to the edit form.
     */
    protected function generateFields()
    {
        // Gets the table columns.
        $colunas = $this->tipo->obterEstruturaDaTabela();
        
        $fields = array();
        $validadores = array();

        foreach ( $colunas as $coluna )
        {
            if ( !in_array($coluna->name, $this->restrictColumns) )
            {
                // Generates the field and validator for the column.
                list($field, $validador) = $this->generateField($coluna);

                if ( $field )
                {
                    $fields[$coluna->name] = $field;

                    if ( $validador )
                    {
                        $validadores[$coluna->name] = $validador;
                    }
                }
            }
        }

        return array( $fields, $validadores );
    }
    
    /**
     * Returns the fields and validators passed in the list in order.
     * Alternative method created to avoid the problem of a field being automatically added to the form when added to the database, which could cause bugs.
     *
     * @return array
     */
    protected function generateSpecificFields(array $lista)
    {
        list($fields, $validadores) = $this->generateFields();

        $fields = array_merge($fields, $this->generateSubDetailFields());
        $camposRet = array();
        $validadoresRet = array();

        foreach ( $lista as $field )
        {
            $camposRet[$field] = $fields[$field];

            if ( isset($validadores[$field]) )
            {
                $validadoresRet[$field] = $validadores[$field];
            }
        }

        return array($camposRet, $validadoresRet);
    }

    /**
     * Generates the form field object.
     *
     * @param bColumnInfo $coluna Object with the column data.
     * @return array Array with the field component created according to the column type and validator.
     */
    protected function generateField(bColumnInfo $coluna)
    {
        $field = NULL;

        $idColuna = explode('__', $coluna->field);
        $coluna->field = end($idColuna);

        $atributosReservados = array_keys(get_object_vars($this));

        // If the field id is already being used in the form, concatenates '_' at the end of the id.
        if ( in_array($coluna->field, $atributosReservados) )
        {
            $coluna->field .= '_';
        }

        if ( substr($coluna->defaultValue, 0, 7) != 'nextval')
        {
            $valor = $coluna->defaultValue;
        }

        $rotulo = _M($coluna->title, $this->modulo);

        if ( $coluna->required == DB_TRUE )
        {
            $validador = new MRequiredValidator($coluna->field, $coluna->title, $coluna->size);
        }

        // Checks if field is a foreign key.
        if ( strlen($coluna->fkTable) )
        {
            $field = new bChoice($coluna->field, $coluna->fkTable, $this->modulo, NULL, $coluna->title );
        }

        if ( !$field )
        {
            switch ( $coluna->type )
            {
                case bColumnInfo::TYPE_BOOLEAN:
                    if ( $valor === NULL )
                    {
                        $valor = DB_FALSE;
                    }

                    $field = new MSelection($coluna->field, $valor, $rotulo, bBooleano::obterVetorSimNao(), NULL, '', '', FALSE);
                    $validador = NULL;
                    break;

                case bColumnInfo::TYPE_DATE:
                    $field = new MCalendarField($coluna->field, $valor, $rotulo, T_DESCRICAO);
                    break;

                case bColumnInfo::TYPE_TIMESTAMP:
                    // FIXME: add the MTimestampField component after resolution of #15440.
                    $field = new MTimestampField($coluna->field, NULL, $rotulo);

                    break;

                case bColumnInfo::TYPE_DECIMAL:
                    $field = new MFloatField($coluna->field, $valor, $rotulo, T_DESCRICAO);
                    break;

                case bColumnInfo::TYPE_NUMERIC:
                    $field = new MFloatField($coluna->field, $valor, $rotulo, T_DESCRICAO);
                    break;

                case bColumnInfo::TYPE_INTEGER:
                case bColumnInfo::TYPE_BIG_INTEGER:
                    $field = new MIntegerField($coluna->field, $valor, $rotulo, T_CODIGO);
                    $validador = new MIntegerValidator($coluna->field, $rotulo);

                    if ( $coluna->required == DB_TRUE )
                    {
                        $validador->type = 'required';
                    }

                    break;

                case bColumnInfo::TYPE_LONG_TEXT:
                    $field = new MMultiLineField($coluna->field, $valor, $rotulo, NULL, T_VERTICAL_TEXTO, T_HORIZONTAL_TEXTO);
                    break;

                case bColumnInfo::TYPE_TEXT:
                default:
                    $field = new MTextField($coluna->field, $valor, $rotulo, T_DESCRICAO);
                    break;
            }
        }

        if ( $coluna->constraint == 'p' && substr($coluna->defaultValue, 0, 7) == 'nextval' )
        {
            $validador = NULL;
        }

        return array( $field, $validador );
    }
    
    /**
     * Generates the MSubDetail components related to the form.
     *
     * @return array Array with MSubDetail components.
     */
    protected function generateSubDetailFields()
    {
        // Searches for tables related to the dynamic type and sets them on the type.
        if ( cadastroDinamico::verificarIdentificador($this->modulo, MIOLO::_REQUEST('chave')) )
        {            
            $cadastroDinamico = bType::instantiateType('cadastroDinamico', 'base');
            $cadastroDinamico->popularPorIdentificador($this->modulo, MIOLO::_REQUEST('chave'));
            $this->tipo->definirTiposRelacionados( $cadastroDinamico->obterTabelasRelacionadas() );
        }
        
        // Gets the names of the types that are related to the main type.
        $tiposRelacionados = $this->tipo->obterTiposRelacionados();
        $chavesPrimarias = $this->tipo->obterChavesPrimarias();
        
        if ( !is_array($tiposRelacionados) )
        {
            return NULL;
        }
        else
        {
            $subDetail = array();
            
            foreach ( $tiposRelacionados as $tipo )
            {
                $tipoObjeto = bType::instantiateType($tipo, $this->modulo);
                $estruturaTabela = $tipoObjeto->obterEstruturaDaTabela();

                if ( is_array($estruturaTabela) )
                {
                    $fields = array();
                    $validadores = array();
                    $colunas = array();

                    foreach ( $estruturaTabela as $campoId => $field )
                    {
                        if ( !in_array($field->name, $this->restrictColumns) )
                        {
                            $field instanceof bColumnInfo;
                            $chaveRelacionada = in_array($field->name, $chavesPrimarias);

                            // Checks if the field has the same id as the form's primary key, if so, does not build the field and column.
    //                        if ( !($field->constraint == 'p' && substr($field->defaultValue, 0, 7) == 'nextval' ) )

                            if ( !$chaveRelacionada )
                            {
                                list($fields[$campoId], $validadores[$campoId]) =  $this->generateField($field);

                                // Hides primary key in the subdetail
                                if ( $field->eChavePrimaria() )
                                {
                                    $fields[$campoId] = new MTextField($campoId, $fields[$campoId]->value);
                                    $fields[$campoId]->addBoxStyle('display', 'none');
                                }

                                // Sets the alignment of the subdetail grid column.
                                if ( in_array($field->type, array(bColumnInfo::TYPE_BOOLEAN, bColumnInfo::TYPE_DATE, bColumnInfo::TYPE_TIMESTAMP)) )
                                {
                                    $alinhamento = 'center';
                                }
                                elseif ( in_array($field->type, array(bColumnInfo::TYPE_LONG_TEXT, bColumnInfo::TYPE_TEXT, bColumnInfo::TYPE_INTEGER, bColumnInfo::TYPE_NUMERIC) ) )
                                {
                                    $alinhamento = 'right';
                                }
                                else
                                {
                                    $alinhamento = 'left';
                                }

                                if ( !$chaveRelacionada && !$field->eChavePrimaria() )
                                {
                                    $colunas[$campoId] = new MGridColumn( $field->title, $alinhamento, true, null, true, $campoId );

                                    // Only needs to be done when not inserting
                                    $relacionamentos = $tipoObjeto->obterRelacionamentos();

                                    foreach( $relacionamentos as $relacionamento )
                                    {
                                        if ( $campoId == $relacionamento->atributo )
                                        {
                                            $tipoRelacionado = bType::instantiateType($relacionamento->tabela_ref);
                                            $chavesPKRelacionado = $tipoRelacionado->obterChavesPrimarias();

                                            // If there are no related PKs, fetches values for direct replacement in the column
                                            if ( !(count($chavesPKRelacionado) > 0) )
                                            {
                                                $valores = $tipoRelacionado->obterArrayAssociativo();
                                                $colunas[$campoId]->setReplace($campoId, $valores);
                                            }

                                            // If this attribute exists, a bChoice is built, and consequently
                                            // a new column is created for this value
                                            if ( strlen($field->fkTable) &&
                                                 count($chavesPKRelacionado) > 0 &&
                                                 strlen($tipoRelacionado->obterColunaDescritiva()) > 0 )
                                            {
                                                // Only fetch related values, not all
                                                $colunas[$campoId . 'Descricao'] = new MGridColumn( $field->title, 'left', true, null, true, $campoId . 'Descricao');

    //                                          $colunas[$campoId]->setTitle("Código " . strtolower($colunas[$campoId]->getTitle()));
                                                $colunas[$campoId]->visible = false;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // setValidators needs to be done before setFields - ticket #41291
                    $subDetail[$tipo] = $campoSubDetail = new MSubDetail($tipo, $tipoObjeto->obterComentarioDaTabela());
                    $campoSubDetail->setValidators( $validadores );
                    $campoSubDetail->setFields( $fields );
                    $campoSubDetail->setColumns($colunas);
                    
                    // Clears the subdetail.
                    if ( MUtil::isFirstAccessToForm() )
                    {
                        MSubDetail::clearData($tipo);
                    }
                }
            }
            
            return $subDetail;
        }
    }
    
    /**
     * Defines the order in which fields will appear in the form.
     *
     * @param array $ordemDosCampos Array with the field order.
     */
    protected function setFieldOrder(array $fieldOrder)
    {
        $this->fieldOrder = $fieldOrder;
    }
    
    /**
     * Gets the field order.
     *
     * @return array Array with the field order.
     */
    protected function getFieldOrder()
    {
        return $this->fieldOrder;
    }    
    
                    }
