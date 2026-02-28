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
 * Dynamic search management form.
 *
 *
 */
class frmBuscaDinamica extends bFormCadastro
{

    public function __construct($parametros)
    {
        parent::__construct(_M('Busca dinâmica', MIOLO::getCurrentModule()), $parametros);
    }

    public function definirCampos()
    {
        if ( MUtil::isFirstAccessToForm() )
        {
            MSubDetail::clearData('campoBuscaDinamica');
        }
        
        parent::definirCampos();
        
        $campos = array();
        $campos[] = new MTextField('buscadinamicaid', NULL, _M('Código'), 10);
        $campos[] = new MTextField('identificador', NULL, _M('Identificador'), 50);
        $campos[] = new MTextField('modulo_', NULL, _M('Módulo'), 20);
        
        $camposBusca = array();
        $camposBusca[] = $campoBuscaDinamicaId = new MTextField('campobuscadinamicaid');
        $campoBuscaDinamicaId->addStyle('display', 'none');
        
        $camposBusca[] = new MDiv('containerReferencias',  $this->gerarCamposDeReferencia(NULL, NULL, TRUE));
        $camposBusca[] = new MDiv('dadosDoCampo', $this->obterDadosDoCampo(NULL, bInfoColuna::TIPO_TEXTO, NULL, TRUE));
        
        $camposBusca[] = new MMultilineField('valorespossiveis', $this->getFormValue('valoresPossiveis', $data->valoresPossiveis), _M('Valores possíveis', $module), T_DESCRICAO, 5, 50);
        $camposBusca[] = new MIntegerField('posicao', '0', _M('Posição', $module), T_CODIGO);
        $camposBusca[] = new MTextField('valorpadrao', $valorPadrao, _M('Valor padrão', $module), T_DESCRICAO);

        $camposBusca[] = $editavel = new MSelection('editavel', DB_TRUE, _M('Editável'), bBooleano::obterVetorSimNao());
        $editavel->setJsHint(_M('Informe se campo deve ser editável'));

        $camposBusca[] = $visivel = new MSelection('visivel', DB_TRUE, _M('Visível'), bBooleano::obterVetorSimNao());
        $visivel->setJsHint(_M('Informe se campo deve ser visível'));

        $camposBusca[] = $filtravel = new MSelection('filtravel', DB_TRUE, _M('Filtrável'), bBooleano::obterVetorSimNao());
        $filtravel->setJsHint(_M('Informe se o campo deve ser utilizado como filtro'));

        $camposBusca[] = $exibirNaGrid = new MSelection('exibirnagrid', DB_TRUE, _M('Exibir na grid'), bBooleano::obterVetorSimNao());
        $exibirNaGrid->setJsHint(_M('Informe se o campo deve ser exibido na grid', $module));

        $camposBusca[] = new MSelection('chave', DB_TRUE, _M('É chave?'), bBooleano::obterVetorSimNao());
        
        $colunasBusca = array();
        
        /*$colunasBusca[] = new MGridColumn( _M('Esquema referenciado'), 'left', true, null, false, 'referenciaEsquema' );
        $colunasBusca[] = new MGridColumn( _M('Tabela referenciada'), 'left', true, null, false, 'referenciaTabela' );
        $colunasBusca[] = new MGridColumn( _M('Coluna referenciada'), 'left', true, null, false, 'referenciaColuna' );*/
        $colunasBusca[] = new MGridColumn( _M('Código do campo de busca dinâmica'), 'left', TRUE, NULL, FALSE, 'campobuscadinamicaid' );
        $colunasBusca[] = new MGridColumn( _M('Referência'), 'left', TRUE, NULL, TRUE, 'referencia' );
        $colunasBusca[] = new MGridColumn( _M('Tipo'), 'left', TRUE, NULL, TRUE, 'tipo' );
        $colunasBusca[] = new MGridColumn( _M('Nome'), 'left', TRUE, NULL, TRUE, 'nome' );
        $colunasBusca[] = new MGridColumn( _M('Valores possíveis'), 'left', TRUE, NULL, TRUE, 'valorespossiveis' );
        $colunasBusca[] = new MGridColumn( _M('Posição'), 'left', TRUE, NULL, TRUE, 'posicao' );
        $colunasBusca[] = new MGridColumn( _M('Valor padrão'), 'left', TRUE, NULL, TRUE, 'valorpadrao' );
        $colunasBusca[] = new MGridColumn( _M('Editável'), 'left', TRUE, NULL, TRUE, 'editavel' );
        $colunasBusca[] = new MGridColumn( _M('Visível'), 'left', TRUE, NULL, TRUE, 'visivel' );
        $colunasBusca[] = new MGridColumn( _M('Filtrável'), 'left', TRUE, NULL, TRUE, 'filtravel' );
        $colunasBusca[] = new MGridColumn( _M('Exibir na grid'), 'left', TRUE, NULL, TRUE, 'exibirnagrid' );
        $colunasBusca[] = new MGridColumn( _M('É chave?'), 'left', TRUE, NULL, TRUE, 'chave' );
        $colunasBusca[] = new MGridColumn( _M('Parâmetros'), 'left', TRUE, NULL, TRUE, 'parametrosModulo' );
        $colunasBusca[] = new MGridColumn( _M('Parâmetros'), 'left', TRUE, NULL, TRUE, 'item' );
       
        $validadorBusca = array();
        $validadorBusca[] = new MRequiredValidator('referencia');
        $validadorBusca[] = new MRequiredValidator('nome', '', 100);
        
        $campos[] = $campoBuscaDinamica = new MSubDetail('campoBuscaDinamica', _M('Campos da busca dinâmica'));
        $campoBuscaDinamica->setFields( $camposBusca );
        $campoBuscaDinamica->setColumns($colunasBusca);
        
        // Validators.
        $validador = array( );
        $validador[] = new MRequiredValidator('identificador', '', 50);
        $validador[] = new MRequiredValidator('modulo_', '', 20);

        $this->addFields($campos);
        $this->setValidators($validador);
    }
    
    /**
     * Updates the reference fields according to the provided data.
     *
     * @param object $args Post data.
     * @return MFormContainer Combos formatted on the same line.
     */
    public function atualizarReferencias($args)
    {
        // Gets the selected schema.
        $esquema = $args->campoBuscaDinamica_referenciaEsquema;
        
        // Gets the selected table.
        $tabela = $args->campoBuscaDinamica_referenciaTabela;
        
        // Gets the reference fields.
        $campos = $this->gerarCamposDeReferencia($esquema, $tabela);
        
        $this->setResponse($campos, 'campoBuscaDinamica_containerReferencias');
    }
    
    /**
     * Generates fields for filling in the reference, providing schema, table and column combos.
     *
     * @param string $esquema Filters the tables by the defined schema.
     * @param string $tabela Filters the columns by the defined table.
     * @return MFormContainer Combos formatted on the same line.
     */
    public function gerarCamposDeReferencia($esquema='public', $tabela='', $createFields = FALSE)
    {
        $esquemas = bCatalogo::listarEsquemas();
        
        $id = $createFields ? 'referenciaEsquema' : 'campoBuscaDinamica_referenciaEsquema';
        $campos[] = $refEsquema = new MSelection($id, $esquema, _M('Coluna de referência'), $esquemas);
        $refEsquema->addAttribute('onchange', MUtil::getAjaxAction('atualizarReferencias'));
        
        $tabelas = bCatalogo::listarTabelas($esquema);
        
        $tabelasArray = array();
        $tabelasArray[''] = '--' . _M('Selecione uma tabela') . '--';
        
        if ( is_array($tabelas) )
        {
            foreach ( $tabelas as $objetoTabela )
            {
                $tabelasArray[$objetoTabela->tablename] = $objetoTabela->tablename;
            }
        }
        
        $id = $createFields ? 'referenciaTabela' : 'campoBuscaDinamica_referenciaTabela';
        $campos[] = $refTabela = new MSelection($id, $tabela, NULL, $tabelasArray);
        $refTabela->addAttribute('onchange', MUtil::getAjaxAction('atualizarReferencias'));

        $id = $createFields ? 'referenciaColuna' : 'campoBuscaDinamica_referenciaColuna';
        
        if ( $tabela )
        {
            //FIXME: consider schema as well.
            $colunas = bCatalogo::listarColunasDaTabela($tabela);
            
            $colunasArray = array();
            $colunasArray[''] = '--' . _M('Selecione uma coluna') . '--';
            
            if ( is_array($colunas) )
            {
                foreach ( $colunas as $coluna )
                {
                    $colunasArray[$coluna] = $coluna;
                }
            }
            
            $campos[] = $refColuna = new MSelection($id, '', NULL, $colunasArray);
            $refColuna->addAttribute('onchange', MUtil::getAjaxAction('atualizarDadosDoCampo'));
        }
        else
        {
            $colunas = array( '' => '--' . _M('Selecione uma coluna') . '--' );
            $campos[] = $refColuna = new MSelection($id, '', NULL, array());
            $refColuna->options = $colunas;
        }

        return new MRowContainer(NULL, $campos);
    }
       
    /**
     * Updates basic field data.
     *
     * @param object $args Post data.
     * @return array Fields to be rendered.
     */
    public function atualizarDadosDoCampo($args)
    {
        $infoColuna = bCatalogo::buscarDadosDaColuna($args->campoBuscaDinamica_referenciaColuna, $args->campoBuscaDinamica_referenciaTabela, $args->campoBuscaDinamica_referenciaEsquema);
       
        $dadosDoCampo = $this->obterDadosDoCampo(NULL, $infoColuna->tipo, $args->campoBuscaDinamica_referenciaEsquema.'.'.$args->campoBuscaDinamica_referenciaTabela.'.'.$args->campoBuscaDinamica_referenciaColuna);

        $this->setResponse($dadosDoCampo, 'campoBuscaDinamica_dadosDoCampo');
    }
    
    /**
     * Generates name, type and default value fields.
     *
     * @param string $nomePadrao Default value for the name field.
     * @param string $tipoPadrao Default value for the type field.
     * @param string $referencia Default value for the reference.
     * @return MFormContainer Name, type and default value fields aligned vertically.
     */
    public function obterDadosDoCampo($nomePadrao='', $tipoPadrao=bInfoColuna::TIPO_TEXTO, $referencia='', $createFields=FALSE)
    {
        $campos = array();
        $id = $createFields ? 'nome' : 'campoBuscaDinamica_nome';
        $campos[] = $nome = new MTextField($id, $nomePadrao, _M('Nome'), T_DESCRICAO);
        $nome->addAttribute('maxlength', '100');

        $id = $createFields ? 'tipo' : 'campoBuscaDinamica_tipo';
        $campos[] = $tipo = new MSelection($id, $tipoPadrao, _M('Tipo'), bInfoColuna::listarTipos());

        $camposEscondidos = array();
        
        $id = $createFields ? 'parametrosModulo' : 'campoBuscaDinamica_parametrosModulo';
        $camposEscondidos[] = $parametros = new MTextField($id);
        $parametros->addStyle('display', 'none');
        
        $id = $createFields ? 'item' : 'campoBuscaDinamica_item';
        $camposEscondidos[] = $item = new MTextField($id);
        $item->addStyle('display', 'none');
        
        $id = $createFields ? 'dadosDosParametros' : 'campoBuscaDinamica_dadosDosParametros';
        $campos[] = $div = new MDiv($id, $camposEscondidos);
        
        $id = $createFields ? 'referencia' : 'campoBuscaDinamica_referencia';
        $campos[] = $buscaReferencia = new MTextField($id, $referencia);
        $buscaReferencia->addStyle('display', 'none');
        
        return new MFormContainer(NULL, $campos);
    }
    
}

?>