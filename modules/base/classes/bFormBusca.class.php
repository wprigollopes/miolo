<?php

/**
 * <--- Copyright 2012 Solis - Cooperativa de Soluções Livres Ltda.
 *
 * This file is part of the Base program.
 *
 * Fermilab is free software; you can redistribute it and/or modify it
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
 * Generic search form for Base.
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 * @author Jader Osvino Fiegenbaum [jader@solis.coop.br]
 *
 * @since
 * Class created on 27/06/2012
 */
class bFormBusca extends bForm
{
    const GRID_DIV = 'labSearchGridDiv';
    const FILTROS_ID = 'labSearch_filtros';

    /**
     * @var MSpecialGrid Search form grid.
     */
    protected $grid;

    /**
     * @var MContextMenu Grid menu.
     */
    protected $menu;
    
    /**
     * @var boolean
     */
    protected $botaoEditar = true;

    /**
     * @var boolean
     */
    protected $botaoRemover = true;
    
    /**
     * @var boolean
     */
    protected $botaoExplorar = true;
    
    /**
     * @var boolean
     */
    protected $botaoNovo = true;

    /**
     * Method for creating fields specific to search forms.
     * 
     * @param boolean $barraDeFerramentas Boolean flag to show or hide the toolbar.
     */
    public function definirCampos($barraDeFerramentas=TRUE)
    {
        parent::definirCampos($barraDeFerramentas);

        if ( $this->barraDeFerramentas )
        {
            $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_BUSCAR);
            $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_SALVAR);
            
            if ( !$this->botaoEditar )
            {
                $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_EDITAR);
            }
            
            if ( !$this->botaoRemover )
            {
                $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_REMOVER);
            }
            
            if ( !$this->botaoNovo )
            {
                $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_INSERIR);
            }
        }

        // Disconnects the change verification event on labSaveForm forms
        $this->page->onload('dojo.disconnect(window.labSaveFormVerificador);');
    }

    /**
     * Gets search form buttons.
     * 
     * @return MDiv MDiv component with the search form buttons. 
     */
    protected function obterBotoes()
    {
        $botoes = array();
        
        $imagem = $this->manager->getUI()->getImageTheme(NULL, 'toolbar-reset.png');
        $botoes[] = new MButton('botaoLimpar', _M('Limpar'), ':bfLimpar_click', $imagem);
        
        // FIXME Evaluate the search button, check the name and image
        $imagem = $this->manager->getUI()->getImageTheme(NULL, 'toolbar-search.png');
        $botoes[] = new MButton('botaoBuscar', _M('Buscar'), ':botaoBuscar_click', $imagem);
       
        // Adds search button to the form
        return MUtil::centralizedDiv($botoes);
    }
    /**
     * Add filters in a container.
     *
     * @param array $filtros Array with filters.
     */
    protected function adicionarFiltros($filtros)
    {
        $filtros[] = $this->obterBotoes();
        
        $controles = new MFormContainer(NULL, $filtros);
        $basegroup = new MBaseGroup(self::FILTROS_ID, _M('Filtros'), array( $controles ), MFormControl::LAYOUT_VERTICAL);

        $this->addFields(array( $basegroup ));
    }

    /**
     * Creates the grid, defining its main query.
     *
     * @param array $colunas Array with MGridColumn instances.
     */
    protected function criarGrid($colunas, $mostrarCheckBoxes=TRUE)
    {
        $chaves = array();

        $i = 0;
        
        foreach ( $this->tipo->obterChavesPrimarias() as $chave )
        {
            $chaves[$chave] = "%$i%";
            $i++;
        }
               
        $this->grid = new MSpecialGrid(NULL, $colunas, 'labSearchGrid', 15, $mostrarCheckBoxes, $chaves);
        $this->grid->setQuery($this->tipo->obterConsulta($this->getData()), $this->modulo);
                
        // Creates the context menu.
        $this->criarMenuDeContexto();

        parent::addField(new MDiv(self::GRID_DIV, array( $this->grid, $this->menu )));
    }
    
        /**
     * Returns an associative array with the data checked in the grid checkboxes.
     *
     * @return array
     */
    protected function obterDadosSelecionados()
    {
        if ( !$this->grid )
        {
            $this->definirCampos();
        }
        
        return (array) $this->grid->getSelectedData();
    }
    
    /**
     * Returns list with the codes (primary key) selected in the grid.
     *
     * @return array
     */
    protected function obterIdsSelecionados()
    {
        $ids = array();
        
        foreach ( $this->obterDadosSelecionados() as $subReg )
        {
            foreach ( $subReg as $chave => $codigo )
            {
                $ids[] = $codigo;
            }
        }
        
        return $ids;
    }
    
    /**
     * Returns a single selected id.
     * 
     * WARNING:
     *  Only use this function when your action allows only one record to be selected.
     *  If the user has selected more than one record in the grid or none, an exception will be thrown.
     * 
     * @return int 
     */
    protected function obterIdSelecionado()
    {
        $ids = $this->obterIdsSelecionados();
        $total = count($ids);

        if ( $total > 1 )
        {
            throw new Exception(_M('Você deve selecionar apenas um registro.'));
        }
        else if ( $total == 0 )
        {
            throw new Exception(_M('Você deve selecionar um registro.'));
        }
        
        return $ids[0];
    }
    
    /**
     * Protected method to create the context menu.
     * 
     * @return MContextMenu Context menu. 
     */
    protected function criarMenuDeContexto()
    {
        $module = MIOLO::getCurrentModule();
        $this->menu = new MContextMenu('bSearchGridMenu', MContextMenu::TYPE_JS);

        if ( $this->botaoEditar )
        {
            $this->menu->addCustomItem(_M('Editar'), $this->manager->getUI()->getAjax('bfEditar:click'), MContextMenu::ICON_EDIT);
            if($module == SModules::MODULE_CONTASPAGAR)
            {
                $this->grid->addActionUpdate($this->manager->getUI()->getAjax('bfEditar:click'));
            }
        }
                
        if ( $this->botaoExplorar )
        {
            $this->menu->addCustomItem(_M('Explorar'), $this->manager->getUI()->getAjax('bfExplorar:click'), MContextMenu::ICON_VIEW);
            if($module == SModules::MODULE_CONTASPAGAR)
            {
                $this->grid->addActionIcon(_M('Explorar'), 'view', $this->manager->getUI()->getAjax('bfExplorar:click'));
            }
        }
        
        if ( $this->botaoRemover )
        {
            $this->menu->addCustomItem(_M('Excluir'), $this->manager->getUI()->getAjax('bfRemover:click'), MContextMenu::ICON_REMOVE);
            if($module == SModules::MODULE_CONTASPAGAR)
            {
                $this->grid->addActionIcon(_M('Excluir'), 'delete', $this->manager->getUI()->getAjax('bfRemover:click'));
            }
        }
                
        // Checks if the table has auditing to build the query action
        if ( $this->verificaAuditoria() )
        {
            $this->menu->addCustomItem(_M('Auditorias'), $this->manager->getUI()->getAjax('bfAuditoria:click'), MContextMenu::ICON_AUDITORIA);
            if($module == SModules::MODULE_CONTASPAGAR)
            {
                $this->grid->addActionIcon(_M('Auditorias'), 'auditoria', $this->manager->getUI()->getAjax('bfAuditoria:click'));
            }
        }

        $this->menu->setTarget($this->grid);
                    
        return $this->menu;
    }
    
    /**
     * Checks if the table used by the grid is being audited and
     * if the user has permission to access the audit process
     * and if the audit table configuration parameter is filled
     * 
     * @return boolean
     */
    public function verificaAuditoria()
    {
        $MIOLO = MIOLO::getInstance();
        $MIOLO->uses('classes/auditoria.class', 'basic');
        $tabelaAuditada = false;
        
        if ( $this->tipo->getTabela() )
        {
            //Checks if the table is being audited (trigger)
            $ok = auditoria::verificaAuditoria(auditoria::obtemSchemaDaTabela($this->tipo->getTabela()), $this->tipo->getTabela());
            
            //Checks if auditing is enabled
            $auditoria = SAGU::getParameter('BASIC', 'MIOLO_AUDIT_DATABASE');
            
            //Checks if the logged-in person has permission on the audit screen
            $permissao = $MIOLO->checkAccess('FrmAuditoria', A_ACCESS);
            
            if ( ($ok == DB_TRUE) && (strlen($auditoria) > 0) && ($permissao == DB_TRUE) )
            {
                $tabelaAuditada = true;
            }
        }
        
        return $tabelaAuditada;
    }

    /**
     * Method that updates the grid content according to the specified filters.
     */
    public function botaoBuscar_click()
    {
        $filtros = $this->getData();
        
        // Whenever searching, force the first page, as there may be
        // no records and then the OFFSET overflows - #39640
        $this->grid->pageNumber = 1;
        $this->page->setViewState('pn_page', 1, $this->grid->name);
        
        $this->grid->setQuery($this->tipo->obterConsulta($filtros), $this->modulo);
        
        // Unchecks the checkboxes in the grid.
        $this->page->onload("mspecialgrid.uncheckAll('bSearchGrid')");
        $this->setResponse(array( $this->grid, $this->menu ), self::GRID_DIV);
    }

    /**
     * Method that redirects the user to the edit screen.
     */
    public function bfEditar_click()
    {
        $selecionados = $this->grid->getSelectedData();
        $numSelecionados = count($selecionados);
        
        if ( $numSelecionados > 1 )
        {
            new MMessageWarning(_M('Você deve selecionar apenas um registro.'));
        }
        elseif ( $numSelecionados == 0 )
        {
            new MMessageWarning(_M('Você deve selecionar um registro.'));
        }
        else
        {
            $args = array(
                'chave' => MIOLO::_REQUEST('chave'),
                'funcao' => FUNCAO_EDITAR,
            );

            foreach ( current($selecionados) as $chave => $valor )
            {
                $args[$chave] = $valor;
            }

            $url = $this->manager->getActionURL($this->modulo, $this->manager->getCurrentAction(), '', $args);
            $this->page->redirect($url);
        }
    }
    
    /**
     * Method that redirects the user to the exploration screen.
     */
    public function bfExplorar_click()
    {
        $selecionados = $this->grid->getSelectedData();
        $numSelecionados = count($selecionados);
        
        if ( $numSelecionados > 1 )
        {
            new MMessageWarning(_M('Você deve selecionar apenas um registro.'));
        }
        elseif ( $numSelecionados == 0 )
        {
            new MMessageWarning(_M('Você deve selecionar um registro.'));
        }
        else
        {
            $args = array(
                'chave' => MIOLO::_REQUEST('chave'),
                'funcao' => FUNCAO_EXPLORAR,
            );

            foreach ( current($selecionados) as $chave => $valor )
            {
                $args[$chave] = $valor;
            }

            $url = $this->manager->getActionURL($this->modulo, $this->manager->getCurrentAction(), '', $args);
            $this->page->redirect($url);
        }
    }
    
    /**
     * Method that redirects the user to the audit query screen.
     */
    public function bfAuditoria_click()
    {
        $MIOLO = MIOLO::getInstance();
        $MIOLO->uses('classes/auditoria.class', 'basic');
                
        $selected = $this->page->request('selectbSearchGrid'); // labSearchGrid
        $selecionados = MSpecialGrid::getSelectedAsIndexedArray($selected);
        $numSelecionados = count($selecionados);
        
        if ( $numSelecionados > 1 )
        {
            new MMessageWarning(_M('Você deve selecionar apenas um registro.'));
        }
        elseif ( $numSelecionados == 0 )
        {
            new MMessageWarning(_M('Você deve selecionar um registro.'));
        }
        else
        {
            foreach ( current($selecionados) as $chave => $valor )
            {
                $pkey[$chave] = $valor;
            }

            $optsAuditFunction['function'] = SForm::FUNCTION_SEARCH;
            $optsAuditEvent['event'] = 'localizar_click';
            $optsEdit = array_merge($optsAuditFunction, $pkey, $optsAuditEvent);

            $optsEdit['table_name'] = strtolower($this->tipo->getTabela());
            $optsEdit['schema_name'] = strtolower(auditoria::obtemSchemaDaTabela($this->tipo->getTabela()));

            $url = $this->manager->getActionURL('basic', 'main:config:auditoria', '', $optsEdit);
            $this->page->window($url);
        }
    }
    
    /**
     * Method to clear the search form.
     */
    public function bfLimpar_click()
    {
        $parametros = array (
            'chave' => MIOLO::_REQUEST('chave'),
            'funcao' => $this->funcao
        );
        
        $url = $this->manager->getActionURL($this->modulo, $this->manager->getCurrentAction(), '', $parametros);
        $this->page->redirect( $url );
    }

    /**
     * Method that displays a message to confirm record deletion.
     */
    public function bfRemover_click()
    {
        $selecionados = $this->grid->getSelectedData();
        $numSelecionados = count($selecionados);
        
        if ( $numSelecionados == 0 )
        {
            new MMessageWarning(_M('Você deve selecionar um registro.'));
        }
        else
        {
            if ( $numSelecionados > 1 )
            {
                $mensagem = _M('Tem certeza que deseja excluir os registros?');
            }
            else
            {
                $mensagem = _M('Tem certeza que deseja excluir o registro?');
            }
            
            MPopup::confirm($mensagem, _M('Confirmação da exclusão'), ':confirmarExclusao');
        }
    }

    /**
     * Method that deletes the selected record. Called after the confirmation message.
     */
    public function confirmarExclusao()
    {
        $selecionados = $this->grid->getSelectedData();
        
        $numSelecionados = count($selecionados);

        if ( $numSelecionados > 1 )
        {
            $mensagem = _M('Registro removido com sucesso.');
        }
        else
        {
            $mensagem = _M('Registros removidos com sucesso.');
        }

        // Begins a database transaction.
        bBaseDeDados::iniciarTransacao();

        $remocao = array();

        foreach ( $selecionados as $selecionado )
        {
           $dados = (object) $selecionado;
           $this->tipo->definir($dados);
           $remocao[] = $this->tipo->excluir();
        }

        // Tests if any of the deletions failed.
        if ( in_array(false, $remocao) )
        {
            // Rolls back the database transaction.
            bBaseDeDados::reverterTransacao();
        }
        else
        {
            // Commits the current database transaction.
            bBaseDeDados::finalizarTransacao();
        }

        // Removes the popup.
        MPopup::remove();

        new MMessage($mensagem, MMessage::TYPE_SUCCESS);

        // Performs the search again.
        $this->botaoBuscar_click();
    }
}

?>
