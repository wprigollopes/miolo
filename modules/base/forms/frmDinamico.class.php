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
class frmDinamico extends bFormCadastro
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
    private $ordemDosCampos = null;
    
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
    public function definirCampos($montarCampos=TRUE, $barraDeFerramentas=TRUE)
    {
        parent::definirCampos($barraDeFerramentas);
        
        if ( $montarCampos )
        {
            // Gets the fields and validators for the form.
            $camposEValidadores = $this->gerarCampos();

            $campos = $camposEValidadores[0];
            
            // Gets the MSubDetail components
            $camposSubDetail = $this->gerarCamposSubDetail();
            
            // Merges the MSubDetail components with the remaining fields.
            if ( is_array($camposSubDetail) )
            {
                $campos = array_merge($campos, $camposSubDetail);
            }
            
            // Performs field ordering if necessary.
            if ( $this->ordemDosCampos )
            {
                $camposDesordenados = $campos;
                $campos = array();
                
                foreach ( $this->ordemDosCampos as $nomeCampo )
                {
                    $campos[$nomeCampo] = $camposDesordenados[$nomeCampo];
                }
            }
 
            $this->addFields($campos);
            $this->setValidators($camposEValidadores[1]);
        }
    }
    
    /**
     * Generates the filters and columns.
     *
     * @return array Array with the filters, the grid column and the keys to be passed to the edit form.
     */
    protected function gerarCampos()
    {
        // Gets the table columns.
        $colunas = $this->tipo->obterEstruturaDaTabela();
        
        $campos = array();
        $validadores = array();

        foreach ( $colunas as $coluna )
        {
            if ( !in_array($coluna->nome, $this->restrictColumns) )
            {
                // Generates the field and validator for the column.
                list($campo, $validador) = $this->gerarCampo($coluna);

                if ( $campo )
                {
                    $campos[$coluna->nome] = $campo;

                    if ( $validador )
                    {
                        $validadores[$coluna->nome] = $validador;
                    }
                }
            }
        }

        return array( $campos, $validadores );
    }
    
    /**
     * Returns the fields and validators passed in the list in order.
     * Alternative method created to avoid the problem of a field being automatically added to the form when added to the database, which could cause bugs.
     *
     * @return array
     */
    protected function gerarCamposEspecificos(array $lista)
    {
        list($campos, $validadores) = $this->gerarCampos();
     
        $campos = array_merge($campos, $this->gerarCamposSubDetail());
        $camposRet = array();
        $validadoresRet = array();
        
        foreach ( $lista as $campo )
        {
            $camposRet[$campo] = $campos[$campo];
            
            if ( isset($validadores[$campo]) )
            {
                $validadoresRet[$campo] = $validadores[$campo];
            }
        }
        
        return array($camposRet, $validadoresRet);
    }

    /**
     * Generates the form field object.
     *
     * @param bInfoColuna $coluna Object with the column data.
     * @return array Array with the field component created according to the column type and validator.
     */
    protected function gerarCampo(bInfoColuna $coluna)
    {
        $campo = NULL;

        $idColuna = explode('__', $coluna->campo);
        $coluna->campo = end($idColuna);
        
        $atributosReservados = array_keys(get_object_vars($this));
        
        // If the field id is already being used in the form, concatenates '_' at the end of the id.
        if ( in_array($coluna->campo, $atributosReservados) )
        {
            $coluna->campo .= '_';
        }
        
        if ( substr($coluna->valorPadrao, 0, 7) != 'nextval')
        {
            $valor = $coluna->valorPadrao;
        }

        $rotulo = _M($coluna->titulo, $this->modulo);

        if ( $coluna->obrigatorio == DB_TRUE )
        {
            $validador = new MRequiredValidator($coluna->campo, $coluna->titulo, $coluna->tamanho);
        }

        // Checks if field is a foreign key.
        if ( strlen($coluna->fkTabela) )
        {
            $campo = new bEscolha($coluna->campo, $coluna->fkTabela, $this->modulo, NULL, $coluna->titulo );
        }
        
        if ( !$campo )
        {
            switch ( $coluna->tipo )
            {
                case bInfoColuna::TIPO_BOOLEAN:
                    if ( $valor === NULL )
                    {
                        $valor = DB_FALSE;
                    }
                    
                    $campo = new MSelection($coluna->campo, $valor, $rotulo, bBooleano::obterVetorSimNao(), NULL, '', '', FALSE);
                    $validador = NULL;
                    break;

                case bInfoColuna::TIPO_DATA:
                    $campo = new MCalendarField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;
                
                case bInfoColuna::TIPO_TIMESTAMP:
                    // FIXME: add the MTimestampField component after resolution of #15440.
                    $campo = new MTimestampField($coluna->campo, NULL, $rotulo);
                    
                    break;

                case bInfoColuna::TIPO_DECIMAL:
                    $campo = new MFloatField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;
                
                case bInfoColuna::TIPO_NUMERIC:
                    $campo = new MFloatField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;

                case bInfoColuna::TIPO_INTEIRO:
                case bInfoColuna::TIPO_INTEIRO_LONGO:
                    $campo = new MIntegerField($coluna->campo, $valor, $rotulo, T_CODIGO);
                    $validador = new MIntegerValidator($coluna->campo, $rotulo);

                    if ( $coluna->obrigatorio == DB_TRUE )
                    {
                        $validador->type = 'required';
                    }

                    break;

                case bInfoColuna::TIPO_TEXTO_LONGO:
                    $campo = new MMultiLineField($coluna->campo, $valor, $rotulo, NULL, T_VERTICAL_TEXTO, T_HORIZONTAL_TEXTO);
                    break;
                    
                case bInfoColuna::TIPO_TEXTO:
                default:
                    $campo = new MTextField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;
            }
        }

        if ( $coluna->restricao == 'p' && substr($coluna->valorPadrao, 0, 7) == 'nextval' )
        {
            $validador = NULL;
        }

        return array( $campo, $validador );
    }
    
    /**
     * Generates the MSubDetail components related to the form.
     *
     * @return array Array with MSubDetail components.
     */
    protected function gerarCamposSubDetail()
    {
        // Searches for tables related to the dynamic type and sets them on the type.
        if ( cadastroDinamico::verificarIdentificador($this->modulo, MIOLO::_REQUEST('chave')) )
        {            
            $cadastroDinamico = bTipo::instanciarTipo('cadastroDinamico', 'base');
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
                $tipoObjeto = bTipo::instanciarTipo($tipo, $this->modulo);
                $estruturaTabela = $tipoObjeto->obterEstruturaDaTabela();

                if ( is_array($estruturaTabela) )
                {
                    $campos = array();
                    $validadores = array();
                    $colunas = array();

                    foreach ( $estruturaTabela as $campoId => $campo )
                    {
                        if ( !in_array($campo->nome, $this->restrictColumns) )
                        {
                            $campo instanceof bInfoColuna;
                            $chaveRelacionada = in_array($campo->nome, $chavesPrimarias);

                            // Checks if the field has the same id as the form's primary key, if so, does not build the field and column.
    //                        if ( !($campo->restricao == 'p' && substr($campo->valorPadrao, 0, 7) == 'nextval' ) )

                            if ( !$chaveRelacionada )
                            {
                                list($campos[$campoId], $validadores[$campoId]) =  $this->gerarCampo($campo);

                                // Hides primary key in the subdetail
                                if ( $campo->eChavePrimaria() )
                                {
                                    $campos[$campoId] = new MTextField($campoId, $campos[$campoId]->value);
                                    $campos[$campoId]->addBoxStyle('display', 'none');
                                }

                                // Sets the alignment of the subdetail grid column.
                                if ( in_array($campo->tipo, array(bInfoColuna::TIPO_BOOLEAN, bInfoColuna::TIPO_DATA, bInfoColuna::TIPO_TIMESTAMP)) )
                                {
                                    $alinhamento = 'center';
                                }
                                elseif ( in_array($campo->tipo, array(bInfoColuna::TIPO_TEXTO_LONGO, bInfoColuna::TIPO_TEXTO, bInfoColuna::TIPO_INTEIRO, bInfoColuna::TIPO_NUMERIC) ) )
                                {
                                    $alinhamento = 'right';
                                }
                                else
                                {
                                    $alinhamento = 'left';
                                }

                                if ( !$chaveRelacionada && !$campo->eChavePrimaria() )
                                {
                                    $colunas[$campoId] = new MGridColumn( $campo->titulo, $alinhamento, true, null, true, $campoId );

                                    // Only needs to be done when not inserting
                                    $relacionamentos = $tipoObjeto->obterRelacionamentos(); 

                                    foreach( $relacionamentos as $relacionamento )
                                    {
                                        if ( $campoId == $relacionamento->atributo )
                                        {
                                            $tipoRelacionado = bTipo::instanciarTipo($relacionamento->tabela_ref);
                                            $chavesPKRelacionado = $tipoRelacionado->obterChavesPrimarias();

                                            // If there are no related PKs, fetches values for direct replacement in the column
                                            if ( !(count($chavesPKRelacionado) > 0) )
                                            {
                                                $valores = $tipoRelacionado->obterArrayAssociativo();
                                                $colunas[$campoId]->setReplace($campoId, $valores);
                                            }

                                            // If this attribute exists, a bEscolha is built, and consequently
                                            // a new column is created for this value
                                            if ( strlen($campo->fkTabela) && 
                                                 count($chavesPKRelacionado) > 0 && 
                                                 strlen($tipoRelacionado->obterColunaDescritiva()) > 0 )
                                            {
                                                // Only fetch related values, not all
                                                $colunas[$campoId . 'Descricao'] = new MGridColumn( $campo->titulo, 'left', true, null, true, $campoId . 'Descricao');

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
                    $campoSubDetail->setFields( $campos );
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
    protected function definirOrdemDosCampos(array $ordemDosCampos)
    {
        $this->ordemDosCampos = $ordemDosCampos;
    }
    
    /**
     * Gets the field order.
     *
     * @return array Array with the field order.
     */
    protected function obterOrdemDosCampos()
    {
        return $this->ordemDosCampos;
    }    
    
                    }
                    
?>