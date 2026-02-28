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
 * Dynamic search form, the search must be previously registered.
 * 
 *
 */
$MIOLO->uses('tipos/cadastroDinamico.class.php', 'base');
class frmDinamicoBusca extends bFormBusca
{
    /**
     * @var array Array of objects with the search columns.
     */
    protected $colunas;
    
    public function __construct($parametros, $titulo=NULL)
    {

        $MIOLO = MIOLO::getInstance();
        $MIOLO->uses('tipos/buscaDinamica.class.php', 'base');
        
        // Gets the table columns.
        $this->colunas = buscaDinamica::buscarDadosDasColunas($parametros['modulo'], MIOLO::_REQUEST('chave'));

        parent::__construct(_M($titulo, MIOLO::getCurrentModule()), $parametros);
    }

    /**
     * Overridden method to define the dynamic search fields.
     */
    public function definirCampos($montarCampos=TRUE)
    {
        $jsCode = " function verificaCamposBuscaAvancada(event)
                    {
                        // If the event is tab(9), checks if the advanced search fields are being displayed
                        // if not, sets the focus to the search button
                        if ( event.keyCode == 9 )
                        {
                            // mExpandDivButton mExpandDivButtonCollapsed class of the collapsed div
                            // mExpandDivButton mExpandDivButtonExpanded class of the expanded div
                            
                            if ( document.getElementsByClassName('mExpandDivButton mExpandDivButtonCollapsed').length > 0 )
                            {
                                document.getElementById('botaoLimpar').focus();
                            }
                        }
                    } ";
        
        $this->addJsCode($jsCode);
        
        parent::definirCampos();
        
        $campos = array();
        
        if ( $montarCampos )
        {
            $campos[] = $generico = new MTextField('generico', NULL, _M('Todos os campos'), 150);
            $generico->addAttribute('onkeydown', 'javascript:verificaCamposBuscaAvancada(event);');
            $generico->addStyle('width', '60%');
            
            $this->adicionarEventoEnter('generico');
        }
        
        // If there are defined columns, builds the form dynamically.
        if ( count($this->colunas) > 0 )
        {
            // Searches for tables related to the dynamic type and sets them on the type.
            if ( cadastroDinamico::verificarIdentificador($this->modulo, MIOLO::_REQUEST('chave')) )
            {
                $cadastroDinamico = bTipo::instanciarTipo('cadastroDinamico', 'base');
                $cadastroDinamico->popularPorIdentificador($this->modulo, MIOLO::_REQUEST('chave'));
                $this->tipo->definirTiposRelacionados( $cadastroDinamico->obterTabelasRelacionadas() );
            }

            // Gets the fields, columns and keys of the search.
            list($camposBuscaDinamica, $colunas, $chaves) = $this->gerarFiltrosEColunas();
            
            // Checks if there are configured filters.
            if ( count($camposBuscaDinamica) > 0 && $montarCampos )
            {
                foreach( $camposBuscaDinamica as $campoBusca )
                {
                    $this->adicionarEventoEnter($campoBusca->name);
                }
                $camposBuscaDinamica = new MFormContainer(NULL, $camposBuscaDinamica);

                $filtrosBusca = new MBaseGroup(self::FILTROS_ID, NULL, array( $camposBuscaDinamica ), MFormControl::LAYOUT_VERTICAL);
                $filtrosBusca = str_replace("\n", '', $filtrosBusca->generate());

                $label = new MLabel(_M('Busca avançada'));
                $filtrosBusca = new MExpandDiv('', $label->generate() . $filtrosBusca);

                $campos = array_merge($campos, array( $filtrosBusca ));
            }
            
        }
        else
        {
            throw new Exception(_M('Não foi encontrada uma busca dinâmica para este formulário'));
        }
        
        $this->adicionarFiltros($campos);

        $this->criarGrid($colunas, TRUE, $chaves);
    }
    
    /**
     * Generates the filters and columns.
     *
     * @return array Array with the filters, the grid column and the keys to be passed to the edit form.
     */
    public function gerarFiltrosEColunas()
    {
        $filtros = array();
        $colunasGrid = array();
        $chaves = array();
        $i = 0;

        foreach ( $this->colunas as $coluna )
        {
            // Generates the filter and the column.
            list($filtro, $colunaGrid) = $this->gerarFiltroEColuna($coluna);

            if ( $filtro )
            {
                $filtros[] = $filtro;
            }

            $colunasGrid[] = $colunaGrid;
            
            // If it is a primary key, the column value must be passed to the edit form.
            if ( $coluna->restricao == 'p' || $coluna->chave == DB_TRUE )
            {
                $chaves[$coluna->nome] = "%$i%";
            }

            $i++;
        }

        return array( $filtros, $colunasGrid, $chaves );
    }

    /**
     * Generates form filter objects and grid column.
     *
     * @param SInfoColuna $coluna Object with the column data.
     * @return array Array with the filter component created according to the column type and a MGridColumn instance.
     */
    public function gerarFiltroEColuna(bInfoColuna $coluna)
    {
        $filtro = NULL;

        $valor = $this->obterValorDoFiltro($coluna);
        $rotulo = _M($coluna->titulo);

        if ( $coluna->filtravel == DB_TRUE )
        {
            switch ( $coluna->tipo )
            {
                case bInfoColuna::TIPO_BOOLEAN:
                    $filtro = new MSelection($coluna->campo, $valor, $rotulo, bBooleano::obterVetorSimNao());
                    break;

                case bInfoColuna::TIPO_DATA:
                    $filtro = new MCalendarField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;
                
                case bInfoColuna::TIPO_TIMESTAMP:
                    $filtro = new MCalendarField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;

                case bInfoColuna::TIPO_DECIMAL:
                    $filtro = new MFloatField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;
                
                case bInfoColuna::TIPO_NUMERIC:
                    $filtro = new MFloatField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;

                case bInfoColuna::TIPO_INTEIRO:
                    $filtro = new MIntegerField($coluna->campo, $valor, $rotulo, T_CODIGO);
                    $validator = new MIntegerValidator($coluna->campo, $rotulo);
                    break;

                case bInfoColuna::TIPO_LISTA:
                    
                    // Checks if possible values exist, otherwise gets them from the database.
                    if ( strlen($coluna->valoresPossiveis) )
                    {
                        $possibleValues = explode("\n", trim($coluna->valoresPossiveis));

                        // Uses the values as keys
                        $possibleValues = array_combine($possibleValues, $possibleValues);
                    }
                    else
                    {
                        $tipoChaveEstrangeira = bTipo::instanciarTipo($coluna->tabela, $this->modulo);
            
                        // Builds a MSelection field with the table values.
                        if ( $tipoChaveEstrangeira instanceof bTipo )
                        {
                            $possibleValues = $tipoChaveEstrangeira->buscarParaSelection();
                        }
                    }

                    $filtro = new MSelection($coluna->campo, $valor, $rotulo, $possibleValues);
                    break;

                case bInfoColuna::TIPO_TEXTO_LONGO:
                case bInfoColuna::TIPO_TEXTO:
                    $filtro = new MTextField($coluna->campo, $valor, $rotulo, T_DESCRICAO);
                    break;
            }
            
            if ( $filtro )
            {
                if ( !$coluna->editavel == DB_TRUE )
                {
                    $filtro->setReadOnly(true);
                    $validator = NULL;
                }

                if ( !$coluna->visivel == DB_TRUE )
                {
                    $filtro->addBoxStyle('display', 'none');
                    $validator = NULL;
                }
            }

            if ( $validator != NULL )
            {
                $this->addValidator($validator);
            }
        }
        
        $alinhamento = $this->obterAlinhamentoPadrao($coluna);

        // Generates the column for the Grid.
        if ( $coluna->tipo == bInfoColuna::TIPO_BOOLEAN )
        {
            $colunaGrid = new MGridColumn($rotulo, $alinhamento, true, NULL, $coluna->exibirNaGrid == DB_TRUE, bBooleano::obterVetorSimNao(), TRUE);
        }
        else if ( $coluna->tipo == bInfoColuna::TIPO_NUMERIC ) 
        {
            $colunaGrid = new MGridColumn($rotulo, $alinhamento, true, NULL, $coluna->exibirNaGrid == DB_TRUE, NULL, TRUE, '', TRUE);
        }
        else
        {
            $colunaGrid = new MGridColumn($rotulo, $alinhamento, true, NULL, $coluna->exibirNaGrid == DB_TRUE, NULL, TRUE);
        }
        
        return array( $filtro, $colunaGrid );
    }

    public function obterValorDoFiltro(bInfoColuna $coluna)
    {
        // Checks if there is a value in REQUEST, to maintain filters when changing pages - #56499
        $valor = $coluna->valorPadrao;
        $valorNoRequest = MIOLO::_REQUEST($coluna->campo);

        if ($valor <> $valorNoRequest && MUtil::getBooleanValue(MIOLO::_REQUEST('__ISAJAXEVENT')))
        {
            $valor = $valorNoRequest;

            // If not clicking on Search, adds JS to keep the filters open
            if (MIOLO::_REQUEST('__mainForm__EVENTTARGETVALUE') != 'botaoBuscar_click')
            {
                $this->addJsCode('setTimeout(function() { $(".mExpandDivButton").click(); }, 500);');
            }
        }

        return $valor;
    }

    /**
     *
     * @return string
     */
    public function obterAlinhamentoPadrao(bInfoColuna $coluna)
    {
        $alinhamentos = array(
            bInfoColuna::TIPO_BOOLEAN => 'center',
            bInfoColuna::TIPO_DATA => 'center',
            bInfoColuna::TIPO_TIMESTAMP => 'center',
            bInfoColuna::TIPO_DECIMAL => 'right',
            bInfoColuna::TIPO_NUMERIC => 'right',
            bInfoColuna::TIPO_INTEIRO => 'right',
        );
        
        return MUtil::NVL($alinhamentos[$coluna->tipo], 'left');
    }
    
    /**
     * Overridden method to handle the dynamic search.
     */
    public function botaoBuscar_click()
    {
        if ( is_array($this->colunas) )
        {
            $sqlConsulta = $this->obterObjetoConsulta();
            
            $this->grid->setQuery($sqlConsulta, $this->modulo);
            
            // Unchecks the checkboxes in the grid.
            $this->page->onload("mspecialgrid.uncheckAll('bSearchGrid')");
        }
        
        $this->setResponse(array( $this->grid, $this->menu ), self::GRID_DIV);
    }
    
    /**
     * Overridden method to define the dynamic search SQL.
     *
     * @param array $colunas Array with MGridColumn instances.
     */
    protected function criarGrid($colunas, $mostrarCheckBoxes=TRUE, $chaves)
    {
        // Gets the search SQL.
        $sqlConsulta = $this->obterObjetoConsulta();
        
        $this->grid = new MSpecialGrid(NULL, $colunas, 'bSearchGrid', 15, $mostrarCheckBoxes, $chaves);
        $this->grid->setGridMostraTotalNumeric(SAGU::getParameter('BASIC', 'GRID_MOSTRA_TOTAL_NUMERIC') == DB_TRUE);
        $this->grid->showExport = true;
        $this->grid->setQuery($sqlConsulta, $this->modulo);
        $this->grid->setRowMethod(__CLASS__, 'myRowMethod');
        
        // Creates the context menu.
        $this->criarMenuDeContexto();

        parent::addField(new MDiv(self::GRID_DIV, array( $this->grid, $this->menu )));
    }
    
    public function myRowMethod($i, $row, $actions, $columns)
    {
        $MIOLO = MIOLO::getInstance();
        foreach ($actions as $cod => $act)
        {
            $href = explode(' ',$act->href);

            $act->href = "mspecialgrid.uncheckAll('bSearchGrid');"." document.getElementById('selectbSearchGrid[".$i."]').click(); ".SAGU::NVL($href[2],$href[0]);
        }        
    }
    
    /**
     * @return MSQL
     */
    protected function obterObjetoConsulta()
    {
        return $this->tipo->buscarNaReferencia($this->colunas, $this->getData());
    }
    
    /**
     * Overridden method to not add MBaseGroup to the search filters.
     *
     * @param array $filtros Array of filter fields.
     */
    protected function adicionarFiltros($filtros)
    {
        $filtros[] = $this->obterBotoes();

        $this->addFields($filtros);
    }
    
    protected function adicionarEventoEnter($campo)
    {
        // Search when pressing 'Enter'
        $eventoBusca = MUtil::getAjaxAction('botaoBuscar_click');
        $this->page->onload("
        handleEnterSearch = 
            dojo.connect(dojo.byId('$campo'),
            'onkeypress',
            function (event) {
            if ( event.keyCode == dojo.keys.ENTER )
            {
                event.preventDefault();
                dojo.disconnect(handleEnterSearch);
                {$eventoBusca};
            }
        });");
    }

}

?>