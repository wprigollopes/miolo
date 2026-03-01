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
 * Generic form for editing, inserting and exploring in Base.
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 * @author Jader Osvino Fiegenbaum [jader@solis.coop.br]
 *
 * @since
 * Class created on 27/06/2012
 */
class bFormRegistration extends bForm
{
    /**
     * Method for creating fields specific to edit and insert forms.
     * 
     * @param boolean $barraDeFerramentas Boolean flag to show or hide the toolbar.
     */
    public function buildFields($barraDeFerramentas=TRUE)
    {
        parent::buildFields($barraDeFerramentas);

        if ( $this->barraDeFerramentas )
        {
            $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_INSERIR);
            $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_EDITAR);
            $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_REMOVER);
            
            if ( $this->funcao == FUNCAO_EXPLORAR )
            {
                $this->barraDeFerramentas->disableButton(bBarraDeFerramentas::BOTAO_SALVAR);
            }
        }

        if ( MUtil::isFirstAccessToForm() )
        {
            $this->addField(new MHiddenField('labSaveFormModificado', NULL));
            $this->page->onload("window.labSaveFormVerificador = dojo.connect(document, 'onchange', function (e) { dojo.byId('labSaveFormModificado').value = 'true'; dojo.disconnect(window.labSaveFormVerificador); } );");
        }
    }

    /**
     * Overridden addFields method to change field display according to the current function.
     *
     * @param array $fields Component instances.
     */
    public function addFields($campos, $informarValorChavePrimaria = FALSE)
    {
        if ( !$informarValorChavePrimaria )
        {
            if ( $this->tipo )
            {
                $chavesPrimarias = $this->tipo->obterChavesPrimarias();
            }
            
            if ( $this->funcao == FUNCAO_INSERIR )
            {
                // Removes the field from the screen when it has a sequential primary key. E.g.: code.
                if ( strlen($chavesPrimarias['sequencial']) )
                {
                    foreach ( $campos as $chave => $campo )
                    {
                        if ( in_array($campo->name, $chavesPrimarias) )
                        {
                            unset($campos[$chave]);
                        }
                    }
                }
            }
            elseif ( $this->funcao == FUNCAO_EDITAR )
            {
                foreach ( $campos as $campo )
                {
                    // Sets only the primary key fields as read-only.
                    if ( in_array($campo->name, $chavesPrimarias) && method_exists($campo, 'setReadOnly') )
                    {
                        $campo->setReadOnly(TRUE);
                    }
                }
            }
            elseif ( $this->funcao == FUNCAO_EXPLORAR )
            {
                // Sets all fields as read-only.
                foreach ( $campos as $campo )
                {
                    $campo->setReadOnly(TRUE);
                }
            }
        }

        // Gets and adds custom fields to the form.
        list($identifier, $customizedId) = $this->obterInformacoesDoFormulario();
        $camposPersonalizados = $this->getCustomFields($identifier, $customizedId);
        $campos = array_merge($campos, $camposPersonalizados);

        if (  $this->funcao != FUNCAO_EXPLORAR )
        {
            $campos[] = $this->obterBotoesPadrao();
        }
        
        $campos[] = new MDiv('', '<br/><br/><br/><br/><br/><br/>');

        $this->verificarDadosDoDicionarioDeCampos($campos);

        parent::addFields($campos);
    }

    /**
     * Gets form information, to obtain custom fields.
     *
     * @return array
     */
    public function obterInformacoesDoFormulario()
    {
        $identifier = 'frm' . ((strlen(MIOLO::_REQUEST('chave')) > 0) ? MIOLO::_REQUEST('chave') : $this->tipo->getTabela());
        $primaryKey = $this->tipo->obterChavesPrimarias();
        $customizedId = MIOLO::_REQUEST($primaryKey['sequencial']); // Will always use the sequential primary key.

        return array($identifier, $customizedId, $primaryKey['sequencial']);
    }

    public function verificarDadosDoDicionarioDeCampos($campos)
    {
        $funcao = MIOLO::_REQUEST('funcao');
        foreach ($campos as $chaveCampo => $campo)
        {
            $dicionarioDeCampos = null;
            if (is_object($this->tipo))
            {
                $dicionarioDeCampos = MCustomField26::getFieldByTableColumn('public.'.$this->tipo->getTabela(), $chaveCampo);
            }
            if ($dicionarioDeCampos != null)
            {
                // Commenting out because there is no option to register a non-editable field in the field dictionary, for now
//                if ( !$dicionarioDeCampos->isEditable() )
//                {
//                    $campo->setReadOnly(true);
//                    $validador = NULL;
//                }

                if (strlen($dicionarioDeCampos->defaultValue) > 0 && $funcao == 'inserir')
                {
                    $campo->value = $dicionarioDeCampos->defaultValue;
                }

                if ( !$dicionarioDeCampos->isVisivel() )
                {
                    $campo->visible = false;
                    $validador = NULL;
                }
            }
        }
        return $campos;
    }

    
    /**
     * Gets the default buttons for the registration form.
     * 
     * @param boolean $botaoCancelar Add cancel button.
     * @param boolean $botaoSalvar Add save button.
     * @return MDiv containing the buttons. 
     */
    protected function obterBotoesPadrao($botaoCancelar=TRUE, $botaoSalvar=TRUE)
    {
        $botoes = array();

        if ( $botaoCancelar )
        {
            // Cancel button.
            $imagem = $this->manager->getUI()->getImageTheme(NULL, 'botao_cancelar.png');
            $botoes[] = new MButton('botaoCancelar', _M('Cancelar'), NULL, $imagem);
        }
        
        if ( $botaoSalvar )
        {
            // Save button.
            $imagem = $this->manager->getUI()->getImageTheme(NULL, 'botao_salvar.png');
            $botoes[] = new MButton('botaoSalvar', _M('Salvar'), ':botaoSalvar_click', $imagem);
        }
        
        // Adds search button to the form
        return MUtil::centralizedDiv($botoes);
    }

    /**
     * Method to save form data, called when clicking the "Save" button.
     */
    public function botaoSalvar_click()
    {
        if ( $this->validate() )
        {    
            $this->tipo->definir($this->getData());

            // Sets the form function.
            $this->tipo->definirFuncao(MIOLO::_REQUEST('funcao'));
            
            try
            {
                SDatabase::beginTransaction();

                $resultado = $this->tipo->salvar();

                // Saves the custom field values.
                list($identifier, $customizedId, $primaryKey) = $this->obterInformacoesDoFormulario();
                $this->saveCustomFields($this->tipo->$primaryKey, $this->getData(), $identifier);

                SDatabase::commit();
            }
            catch ( Exception $e )
            {
                SDatabase::rollback();
                throw new Exception($e->getMessage());
            }

            if ( $this->funcao == FUNCAO_INSERIR )
            {
                if ( $resultado )
                {
                    $this->limparIndicacaoDeFormModificado();
                    new MMessageSuccess(_M('Registro inserido com sucesso.'), FALSE);
                    
                    $parametros = array (
                        'chave' => MIOLO::_REQUEST('chave'),
                        'funcao' => $this->funcao
                    );
                    
                    $url = $this->manager->getActionURL($this->modulo, $this->manager->getCurrentAction(), '', $parametros);
                    $this->page->redirect( $url );
                    
                }
                else
                {
                    new MMessageWarning(_M('Não foi possível inserir o registro.'));
                }
            }
            elseif ( $this->funcao == FUNCAO_EDITAR )
            {
                if ( $resultado )
                {
                    $this->limparIndicacaoDeFormModificado();
                    new MMessageSuccess(_M('Registro editado com sucesso.'));
                    
                    // Returns to the search screen
                    $parametros = array (
                        'chave' => MIOLO::_REQUEST('chave'),
                        'funcao' => FUNCAO_BUSCAR
                    );
                    
                    $url = $this->manager->getActionURL($this->modulo, $this->manager->getCurrentAction(), '', $parametros);
                    $this->page->redirect($url);
                }
                else
                {
                    new MMessageWarning(_M('Não foi possível editar o registro.'));
                }
            }
        }
        else
        {
            new MMessage(_M('Verifique os dados informados.'), MMessage::TYPE_WARNING);
        }
    }

    /**
     * Method to cancel data entry in the form. If any field value was changed, asks the
     * user if they really want to cancel.
     */
    public function botaoCancelar_click()
    {
        if ( MIOLO::_REQUEST('labSaveFormModificado') )
        {
            MPopup::confirm(
                _M('Os dados do formulário foram modificados. Tem certeza que deseja cancelar?'),
                _M('Cancelar'),
                ':cancelar'
            );
        }
        else
        {
            $this->cancelar();
        }
    }

    /**
     * Cancels data editing/insertion, redirecting the user to the search screen.
     */
    public function cancelar()
    {
        $args = array(
            'chave' => MIOLO::_REQUEST('chave'),
            'funcao' => FUNCAO_BUSCAR,
        );

        $this->page->redirect($this->manager->getActionURL($this->modulo, 'main', NULL, $args));
    }

    /**
     * Clears the field that indicates the form was modified.
     */
    public function limparIndicacaoDeFormModificado()
    {
        bJavascript::definirValor('labSaveFormModificado', '');
    }

    /**
     * Method called to load form field data when editing.
     */
    public function onLoad()
    {
        if ( ( $this->funcao == FUNCAO_EDITAR || $this->funcao == FUNCAO_EXPLORAR) && MUtil::isFirstAccessToForm() )
        {
            // Gets the primary key values passed by URL.
            $chavesPrimarias = $this->tipo->obterChavesPrimarias();

            $dados = new stdClass();

            foreach ( $chavesPrimarias as $chavesPrimaria )
            {
                $dados->$chavesPrimaria = MIOLO::_REQUEST($chavesPrimaria);
            }

            $this->tipo->definir($dados);
            $this->tipo->popular();
            
            // Gets the type data.
            $dados = $this->tipo->obter();

            // Gets the data of all related types.
            $dadosDosTiposRelacionados = $this->tipo->obterDadosTiposRelacionados();
            
            // Iterates through the fields to check for field ids that are reserved words.
            foreach ( $this->fields as $campo )
            {
                // Gets the field name.
                $nome = $campo->getName();
                
                if ( substr($nome, strlen($nome) -1, 1) == '_' )
                {
                    $indice = substr($nome, 0, strlen($nome) -1);
                    $dados->$nome = $dados->$indice;
                }
                
                // Loads the MSubDetail data.
                if ( $campo instanceof MSubDetail )
                {
                    // Gets the MSubDetail id.
                    $idSubDetail = $campo->getName();
                    
                    // Gets the type data.
                    $dadosSubDetail = $dadosDosTiposRelacionados[$idSubDetail];
                    
                    // Sets the data in the MSubDetail.
                    MSubDetail::setData($dadosSubDetail, $idSubDetail);
                }
            }

            // Sets the data in the form.
            $this->setData($dados);
        }
    }
    
    /**
     * Method called when editing a MSubDetail record.
     * 
     * @param string $args Ajax arguments. 
     */
    public static function editFromTable($args)
    {
        MSubDetail::editFromTable($args);
    }
   
    /**
     * Method called when moving a record down in the MSubDetail.
     * 
     * @param string $args Ajax arguments. 
     */
    public static function downFromTable($data)
    {
        MSubDetail::downFromTable($data);
    }
    
    /**
     * Method called when moving a record up in the MSubDetail.
     * 
     * @param string $args Ajax arguments. 
     */
    public static function upFromTable($data)
    {
        MSubDetail::upFromTable($data);
    }
    
    /**
     * Method called when clearing a MSubDetail.
     * 
     * @param string $args Ajax arguments. 
     */
    public static function clearTableFields($args)
    {
        MSubDetail::clearTableFields($args);
    }
    
    /**
     * Method called when adding a MSubDetail record.
     * 
     * @param string $args Ajax arguments. 
     */
    public static function addToTable($data)
    {
        MSubDetail::addToTable($data);
    }
    
    /**
     * Method called when removing a MSubDetail record.
     * 
     * @param string $args Ajax arguments. 
     */
    public static function removeFromTable($args)
    {
        MSubDetail::removeFromTable($args);
    }
       
}
