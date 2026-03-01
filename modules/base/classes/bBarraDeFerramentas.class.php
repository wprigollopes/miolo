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
 * Class that builds the toolbar.
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 * @author Jader Osvino Fiegenbaum [jader@solis.coop.br]
 *
 * @since
 * Class created on 26/06/2012
 */
class bBarraDeFerramentas extends MBaseGroup
{
    /**
     * Button name constants.
     */
    const BOTAO_INSERIR = 'bfInserir';
    const BOTAO_EDITAR = 'bfEditar';
    const BOTAO_SALVAR = 'bfSalvar';
    const BOTAO_REMOVER = 'bfRemover';
    const BOTAO_BUSCAR = 'bfBuscar';
    const BOTAO_IMPRIMIR = 'bfImprimir';
    const BOTAO_REDEFINIR = 'bfRedefinir';
    const BOTAO_SAIR = 'bfSair';

    /**
     * @var array Toolbar buttons.
     */
    protected $botoes;

    /**
     * @var string Default button display type.
     */
    private $tipo = MToolbar::TYPE_ICON_TEXT;

    /**
     * Customized toolbar for FermiLab.
     */
    public function __construct()
    {
        //ID to make the toolbar vertical (CSS classes from the modern theme)
        parent::__construct('toolbar', '');

        $modulo = MIOLO::getCurrentModule();
        $chave = MIOLO::_REQUEST('chave');
        $formId = $this->page->getFormId();
        $url = $this->manager->getActionURL($modulo, null, null, array( 'chave' => $chave ));


        // Insert button
        $paramaters = array(
            'chave' => $chave,
            'funcao' => FUNCAO_INSERIR
        );

        $eventoURL = $this->manager->getActionURL($modulo, null, null, $paramaters);

        $this->adicionarBotaoPadrao(self::BOTAO_INSERIR, _M('Novo'), $eventoURL, _M('Clique para inserir um novo registro', $modulo), 'new-20x20.png', 'new-disabled-20x20.png');
        
        
        // Edit button
        $evento = self::BOTAO_EDITAR . ':click';
        $eventoURL = "javascript:miolo.doAjax('$evento','','{$formId}');";
        $this->adicionarBotaoPadrao(self::BOTAO_EDITAR, _M('Editar'), $eventoURL, _M('Clique para editar o registro selecionado', $modulo), 'bf-editar-on.png', 'bf-editar-off.png');

        
        // Save button
        $evento = self::BOTAO_SALVAR . ':click';
        $eventoURL = "javascript:miolo.doAjax('botaoSalvar_click','','__mainForm'); return false;";
//        $eventoURL = "javascript:miolo.doAjax('$evento','','{$formId}');";
        $this->adicionarBotaoPadrao(self::BOTAO_SALVAR, _M('Salvar'), $eventoURL, _M('Clique para salvar', $modulo), 'save-20x20.png', 'save-disabled-20x20.png');

        // Remove button
        $evento = self::BOTAO_REMOVER . ':click';
        $eventoURL = "javascript:miolo.doAjax('$evento','','{$formId}');";
        $this->adicionarBotaoPadrao(self::BOTAO_REMOVER, _M('Deletar'), $eventoURL, _M('Clique para remover o registro selecionado', $modulo), 'delete-20x20.png', 'delete-disabled-20x20.png');


        // Search button
        $paramaters = array(
            'chave' => $chave,
            'funcao' => FUNCAO_BUSCAR
        );

        $eventoURL = $this->manager->getActionURL($modulo, null, null, $paramaters);
        $this->adicionarBotaoPadrao(self::BOTAO_BUSCAR, _M('Procurar'), $eventoURL, _M('Clique para ir a página de busca', $modulo), 'search-20x20.png', 'search-disabled-20x20.png');


        // Exit button
        $funcao = MIOLO::_REQUEST('funcao');
        
        // Redirect to home.
        $eventoURL = $this->manager->getConf('home.url');      
        $this->adicionarBotaoPadrao(self::BOTAO_SAIR, _M('Fechar'), $eventoURL, _M('Clique para sair do formulário', $modulo), 'exit-20x20.png', 'exit-disabled-20x20.png');

        $this->setShowChildLabel(false);
    }

    /**
     * Adds a button in the standard class format.
     *
     * @param string $nome Button identifier.
     * @param string $titulo Label.
     * @param string $evento Event.
     * @param string $dica Tooltip.
     * @param string $imagemAtivo Image name when active.
     * @param string $imagemInativo Image name when inactive.
     */
    public function adicionarBotaoPadrao($nome, $titulo, $evento, $dica, $imagemAtivo, $imagemInativo)
    {
        $tema = $this->manager->theme->id;
        $UI = $this->manager->getUI();

        $imagemAtivo = $UI->getImageTheme($tema, $imagemAtivo);
        $imagemInativo = $UI->getImageTheme($tema, $imagemInativo);
        $this->botoes[$nome] = new MToolBarButton($nome, $titulo, $evento, $dica, true, $imagemAtivo, $imagemInativo, NULL, $this->tipo);
    }

    /**
     * Adds a custom button
     *
     * @param string $nome MToolbarButton identifier.
     * @param string $titulo Button title.
     * @param string $url Button action URL.
     * @param string $jsSugestao Button tooltip.
     * @param boolean $ativo Button status.
     * @param string $ativoImagem Active button image URL.
     * @param string $desativadoImagem Disabled button image URL.
     * @param string $method @deprecated
     * @param string $tipo Button type, which can be: MToolBar::TYPE_ICON_ONLY, MToolBar::TYPE_ICON_TEXT or MToolBar::TYPE_TEXT_ONLY.
     */
    public function addButton($nome, $titulo, $url, $jsSugestao, $ativo, $ativoImagem, $desativadoImagem, $tipo=MToolBar::TYPE_ICON_ONLY)
    {
        $this->botoes[$name] = new MToolBarButton($nome, $titulo, $url, $jsSugestao, $ativo, $ativoImagem, $desativadoImagem, NULL, $tipo);
    }

    /**
     * Method to show buttons.
     *
     * @param array $names Button identifiers.
     */
    public function showButtons(array $names)
    {
        foreach ( $names as $name )
        {
            $this->showButton($name);
        }
    }

    /**
     * Show a button.
     *
     * @param string $name Button name.
     */
    public function showButton($name)
    {
        $this->botoes[$name]->show();
    }

    /**
     * Hide buttons.
     *
     * @param array $names Button names.
     */
    public function hideButtons(array $names)
    {
        foreach ( $names as $name )
        {
            $this->hideButton($name);
        }
    }

    /**
     * Hide a button.
     *
     * @param string $name Button name.
     */
    public function hideButton($name)
    {
        $this->botoes[$name]->hide();
    }

    /**
     * Enable buttons.
     *
     * @param array $names Button names.
     */
    public function enableButtons(array $names)
    {
        foreach ( $names as $name )
        {
            $this->enableButton($name);
        }
    }

    /**
     * Enable a button.
     *
     * @param string $name Button name.
     */
    public function enableButton($name)
    {
        $this->botoes[$name]->enable();
    }

    /**
     * Disable buttons.
     *
     * @param array $names Button names.
     */
    public function disableButtons(array $names)
    {
        foreach ( $names as $name )
        {
            $this->disableButton($name);
        }
    }

    /**
     * Disable one button.
     *
     * @param string $name Button name.
     */
    public function disableButton($name)
    {
        $this->botoes[$name]->disable();
    }

    /**
     * Set button type.
     * 
     * @param string $type Button type: MToolBar::TYPE_ICON_ONLY, MToolBar::TYPE_ICON_TEXT or MToolBar::TYPE_TEXT_ONLY.
     */
    public function setType($type=MToolBar::TYPE_ICON_ONLY)
    {
        foreach ( $this->botoes as $tbb )
        {
            $tbb->setType($type);
        }
    }

    /**
     * Add custom control to toolbar
     *
     * @param object $control MControl instance.
     * @param string $name Control name.
     */
    public function addControl($control, $name=NULL)
    {
        parent::addControl($control);

        if ( $name )
        {
            $this->botoes[$name] = $control;
        }
    }

    /**
     * Generate inner content.
     */
    public function generateInner()
    {
        parent::__construct($this->name, '', $this->botoes);

        parent::generateInner();
    }
}

?>
