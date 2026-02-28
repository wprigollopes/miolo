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
 * Base main form.
 *
 *
 */
class bForm extends MForm
{
    /**
     * @var bBarraDeFerramentas System toolbar.
     */
    protected $barraDeFerramentas;

    /**
     * @var string Module name.
     */
    protected $modulo;
    
    /**
     * @var string Form function. 
     */
    protected $funcao;
    
    /**
     * @var bType Type instance. 
     */
    protected $tipo;
    
    /**
     * @var boolean Flag that defines whether event handler will be executed 
     */
    public static $fazerEventHandler;
    
    /**
     * Form constructor method.
     * 
     * @param string $titulo Form title.
     * @param aray $parametros Parameters sent by the handler.
     */
    public function __construct($titulo = null, $parametros = null)
    {
        try
        {
            $this->modulo = $parametros['modulo'];
            $this->funcao = $parametros['funcao'];

	    if($parametros['tipo'])
	    {
            	$this->instantiateType($parametros['tipo']);
            
                if ( strlen($titulo) == 0 )
        	{
                    $titulo = $this->tipo->obterComentarioDaTabela();
	        }
            }

            parent::__construct($titulo);

            if ( $this->barraDeFerramentas )
            {
                // Class that provides the necessary spacing for the vertical toolbar.
                $this->setClass('verticalToolbarPadding');
            }
                
            if ( !self::$fazerEventHandler )
            {
    	        $this->eventHandler();
                self::$fazerEventHandler = true;
            }

            // TODO: remove me
            $barraDeNavegacao = $this->manager->getTheme()->getElement('navigation');
            $barraDeNavegacao->addOption($titulo, $this->modulo, 'main');

            $this->setJsValidationEnabled(false);
            $this->setShowPostButton(false);
        }
        catch ( MDatabaseException $e )
        {
            $this->displayError($e->getMessage());
        }
        catch ( MValidationException $e )
        {
            // Gets the validator messages.
            $mensagens = $e->getMessages();
            $javascript = 'mvalidator.removeAllErrors();';
            
            if ( is_array($mensagens) )
            {
                foreach ( $mensagens as $campo => $mensagem )
                {
                    $javascript .= "mvalidator.addErrorToField('$mensagem', '$campo');";
                }
            }
            
            // Adds the messages next to the fields that are invalid.
            $this->page->onload($javascript);
            
            // Ajax response so that validation is ajax.
            $this->setResponse(NULL, 'responseDiv');
        }
        catch ( Exception $e )
        {
//            MUtil::debug($e->getMessage());
            $this->displayError($e->getMessage());
        }
    }

    /**
     * Method for creating form fields.
     * 
     * @param boolean $barraDeFerramentas Boolean flag to show or hide the toolbar.
     */
    public function createFields($barraDeFerramentas = true)
    {
        parent::createFields();
        
        // Events that trigger the field definition.
        $eventos[] = 'botaoBuscar_click';
        $eventos[] = 'tbBtnNew:click';
        $eventos[] = 'bfEditar:click';
        $eventos[] = 'bfRemover:click';
        $eventos[] = 'bfExplorar:click';
        $eventos[] = 'bfCancelar:click';
        $eventos[] = 'botaoSalvar_click';
        $eventos[] = 'confirmarExclusao';
        $eventos[] = 'confirmarExclusao';
        
        // Only calls the main fields when necessary.
        if ( MUtil::isFirstAccessToForm() || in_array( MIOLO::_REQUEST(MUtil::getDefaultEvent()), $eventos ) || substr(MIOLO::_REQUEST(MUtil::getDefaultEvent()), 0, 11) == 'mlinkbutton' )
        {
            try
            {
                $this->buildFields(false);
            }
            catch (Exception $e)
            {
                new MMessageWarning($e->getMessage());
            }
        }
    }
    
    public function buildFields($barraDeFerramentas)
    {
        $fields = array();
        
        if ( $barraDeFerramentas )
        {
            $fields[] = $this->barraDeFerramentas = new bBarraDeFerramentas();
        }
        
        $fields[] = MMessage::getMessageContainer();
        $fields[] = MPopup::getPopupContainer();
        $fields[] = new MDiv('responseDiv', NULL);
        $fields[] = MDialog::getDefaultContainer();

        parent::addFields($fields);
    }
    
    /**
     * Overridden method to get the first form field and set focus on it.
     * 
     * @param array Array of fields.
     */
    public function addFields($campos)
    {
        parent::addFields($campos);
        
        $primeiroCampoVisivel = $this->getFirstVisibleField($campos);

        if ( $primeiroCampoVisivel && MUtil::isFirstAccessToForm() )
        { 
             bJavascript::definirFoco($primeiroCampoVisivel, FALSE);
        }
    }

    /**
     * Creates an instance of the specified type and sets it in the form's type attribute.
     *
     * @param string $nomeDoTipo Type name.
     * @return labType Type instance.
     */
    public function instantiateType($nomeDoTipo)
    {
        if ( !$nomeDoTipo )
        {
            return;
        }
               
        
        $this->tipo = bType::instantiateType($nomeDoTipo, $this->modulo);
    }
    
    /**
     * Method to display errors on screen.
     * 
     * @param string $erro Error message.
     */
    public function displayError( $erro )
    {
        // First access to the page.
        if ( MUtil::isFirstAccessToPage() )
        {
            $this->error( $erro );
        }
        else
        {
            // Tests if the error message has more than one line; if so, adds the message in an expanded DIV.
            if ( count(explode("<br />", $erro)) > 1 )
            {
                $divErro = new MExpandDiv( NULL, $erro );
            }
            else
            {
                $divErro = new MDiv( NULL, $erro );
            }
            
            MPopup::remove();
            new MMessageWarning($divErro, MMessage::TYPE_ERROR);
        }
    }
    
    /**
     * Method that gets the id of the first visible input field (recursive).
     *
     * @param array Array with MIOLO components.
     * @return string Id of the first visible field.
     *
     */
    public function getFirstVisibleField($campos)
    {
        foreach ( (array) $campos as $campo )
        {
            if ( $campo instanceof MContainer )
            {
                $campoVisivel = $this->getFirstVisibleField($campo->getControls());
                
                if ( $campoVisivel )
                {
                    return $campoVisivel;
                }
            }
            elseif ( $campo instanceof MDiv )
            {
                $campoVisivel = $this->getFirstVisibleField($campo->getInner());
                
                if ( $campoVisivel )
                {
                    return $campoVisivel;
                }
            }
            elseif ( $campo instanceof MHiddenField || $campo->readonly || $campo instanceof MButton )
            {
                continue;
            }
            elseif ( $campo instanceof MInputControl )
            {
                return $campo->getName();
            }
        }
    }
    
    /**
     * Overridden method to handle fields whose id is a system reserved word. E.g.: name.
     * 
     * @return FormData Object with form values. 
     */
    public function getData()
    {
        $dados = $this->getAjaxData();
        
        // Iterates through the FormData values adjusting values whose index ends with '_'.
        foreach ( $dados as $indice => $dado )
        {
            if ( substr($indice, strlen($indice) -1, 1) == '_' )
            {
                // Removes the '_' at the end of the index.
                $novoIndice = substr($indice, 0, strlen($indice) -1);
                $dados->$novoIndice = $dado;
            }
        }
        
        // Support for the MSubDetail component.
        foreach ( $this->fields as $campo )
        {
            if ( $campo instanceof MSubDetail )
            {
                $idSubDetail = $campo->getName();
                $dados->$idSubDetail = MSubDetail::getData($idSubDetail);
            }
            
            if ( $campo instanceof MCheckBox )
            {
                $dados->{$campo->name} = $dados->{$campo->name} == DB_TRUE ? DB_TRUE : DB_FALSE;
            }
        }
        
        return $dados;
    }
    
    public function setNullResponseDiv()
    {
        $this->setResponse(NULL, 'responseDiv');
    }

    /**
     * Gets the custom field related to a column and table
     *
     * @param String $coluna Column related to the column
     * @param String $tabela Table related to the field
     * @param array $parametros List of attributes to override on the obtained field
     * @return Array List of field and validator
     */
    public function getTableField($coluna, $tabela, $parametros = array())
    {
        $tabela = strtolower($tabela);
        $coluna = strtolower($coluna);

        try
        {
            list( $field, $validator, $campoMensagem ) = SDicionarioDeCampos::getTableField($coluna, $tabela, $parametros);

            if (!is_array($parametros['chavePrimaria']) && count($this->pkey) > 0)
            {
                $parametros['chavePrimaria'] = array();
                foreach ($this->pkey as $pk)
                {
                    $parametros['chavePrimaria'][$pk] = MIOLO::_REQUEST($pk);
                }
            }

            if ( is_array($parametros['chavePrimaria']) && MIOLO::_REQUEST('function') == SForm::FUNCTION_UPDATE )
            {
                $field->value = SDicionarioDeCampos::obtemValorDoCampo($coluna, $tabela, $parametros['chavePrimaria']);
            }
        }
        catch( Exception $e )
        {
            SAGU::error($e->getMessage(), MIOLO::getInstance()->GetActionURL());
        }
        return array($field, $validator, $campoMensagem);
    }

}