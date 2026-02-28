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
 * read the "LICENCA.txt" file included with this software. e
 * 
 * 
 *
 *
 *
 *
 *
 **/
 
class bEscolha extends MRowContainer
{
    private $codigo;
    private $descricao;
    
    public function __construct($nome, $chave, $modulo, $valor=NULL, $rotulo=NULL, $somenteLeitura=FALSE, $camposDaTabela=NULL, $validator = NULL) 
    {
        $MIOLO = MIOLO::getInstance();
        
        // Tests if the component has a name.
        if ( strlen($nome) == 0 )
        {
            throw new Exception(_M("É necessário definir um nome para o componente bEscolha."));
        }

        // Tests if the component has a key.
        if ( strlen($chave) == 0 )
        {
            throw new Exception(_M("É necessário definir uma chave para o componente bEscolha."));
        }
        
        // Tests if the component has a module.
        if ( strlen($modulo) == 0 )
        {
            throw new Exception(_M("É necessário definir um módulo para o componente bEscolha."));
        }

	if(!$camposDaTabela)
	{
            $camposDaTabela = $nome.', ' . $this->obterColunaDescricaoDaTabela($chave);
	}
        
        // Defines the default fields of the component.
        $campos = array();
        $campos[] = $this->codigo = new MTextField($nome, $valor, $rotulo, 5, NULL, NULL, $somenteLeitura);
        $this->codigo->addAttribute('onchange', "miolo.doAjax('obterRegistro',this.id + '|' + this.value + '|' + '$chave' + '|' + '$modulo' + '|' + '$camposDaTabela','{$MIOLO->page->getFormId()}');");
        
        $campos[] = $this->descricao = new bEscolhaTextField($nome . 'Descricao', NULL, NULL, 50, NULL, NULL, $somenteLeitura);
        $this->descricao->setChave($chave);
        $this->descricao->setModulo($modulo);
        $this->descricao->setCamposDaTabela($camposDaTabela);
        $this->descricao->addAttribute('onkeydown', "return bEscolha.onkeyUpEscolha(event, this, this.id, '{$chave}', '{$modulo}', '{$camposDaTabela}')" );
        $this->descricao->addAttribute('ondblclick',"return bEscolha.onDoubleClick(event, this.id, '{$chave}', '{$modulo}', '{$camposDaTabela}')");
        
        // Sets the validator on the code
        if ( !is_null($validator) )
        {
            $this->codigo->validator = $validator;
        }
        
        parent::__construct(NULL, $campos);
        $this->page->addScript('bEscolha.js', 'base');
        
        // Line necessary to fetch the description value when the field is read-only or when the form is in edit mode.
        $MIOLO->page->onload("var elem = dojo.byId('$nome'); if(elem != null){elem.onchange();}");
    }
    
    /**
     * Method to set the component as read-only.
     * 
     * @param boolean $somenteLeitura If true, sets the component controls to read-only mode.
     */
    public function setReadOnly($somenteLeitura)
    {
        $controles = $this->getControls();
        
        foreach ( $controles as $componente )
        {
            $componente->setReadOnly($somenteLeitura);
        }        
    }
    
    /**
     * Method to add events to the component.
     * 
     * @param String $evento Event to be added to the component.
     * @param String $valor Event value.
     */
    public function addEvent($evento, $valor)
    {
        $this->codigo->addEvent($evento, $valor);
        
        /**
         * We had to remove this because it was causing problems when the
         * component had an event that overrides a default bEscolha event,
         * for example: "onChange".
         * 
         * We could not identify why this was done, but by
         * removing this line everything worked again. There would also be no reason
         * to add the event to the description as well, since the description will pass
         * values to the code and the code will perform events normally.
         * 
         * Ticket #40853
         */
//        $this->descricao->addEvent($evento, $valor);
    }
    
    /**
     * Method to add attributes to the component.
     *  
     * @param String $atributo Attribute to be added to the component.
     * @param String $valor Attribute value.
     */
    public function addAttribute($atributo, $valor)
    {
        $this->codigo->addAttribute($atributo, $valor);
    }
   
    /**
     *  Method used to auto-complete the description.
     * 
     * @param stdClass $parametros Ajax arguments.
     */
    public function obterRegistro($parametros)
    {
        $MIOLO = MIOLO::getInstance();
        
        // Gets component id and value.
        $parametros = explode('|', $parametros);        
        $idComponente = $parametros[0];
        $valor = $parametros[1];
        $chave = $parametros[2];
        $modulo = $parametros[3];
        $campos = explode(',', $parametros[4]);
        $campoChave = $campos[0];
        
        // To ensure proper functioning with dynamic bTipo, trim the column names.
        foreach($campos as $key => $campo)
        {
            $campos[$key] = trim($campos[$key]);
        }

        define(DB_NAME, $modulo);
        
        $tipo = bTipo::instanciarTipo($chave, $modulo);

        // Protection to avoid database error
        if ( $tipo->colunaTipoNumerico( $campoChave ) && !is_numeric($valor) )
        {
            $valor = null;
        }
        
        $resultado = $tipo->buscarParaAutoCompletarEscolha($valor, $campos);

        if ( is_array($resultado) )
        {
            $descricao = $resultado[0][1];
        }
            
        $idDescricao = $idComponente . 'Descricao';
        // If the field is in a component that repeats it (like MGrider), its id comes with brackets because it is an array.
        if ( substr_count($idComponente, '[') > 0 )
        {
            $posColchete = strpos($idComponente, '[');
            $idDescricao = substr($idComponente, 0, $posColchete) . 'Descricao' . substr($idComponente, $posColchete);
        }
        
        $MIOLO->page->onload("dojo.byId('{$idDescricao}').value = '{$descricao}'");
        
        // Ajax response.
        $MIOLO->page->ajax->setResponse(NULL, 'responseDiv');
    }
    
    /**
     * Function called after data entry.
     * 
     * @param stdClass $args Ajax arguments.
     */
    public function onkeyUpEscolha($parametros)
    {
        $MIOLO = MIOLO::getInstance();
        
        // Gets component id and value.
        $parametros = explode("|", $parametros);

        $idComponente = $parametros[0];
        $idComponente = str_replace(array("mostrarOpcoes", "Descricao"), "", $idComponente);

        $valor = $parametros[1];
        $chave = $parametros[2];
        $modulo = $parametros[3];
        $campos = explode(',', $parametros[4]);
        // To ensure proper functioning with dynamic bTipo, trim the column names.
        foreach($campos as $key => $campo)
        {
            $campos[$key] = trim($campos[$key]);
        }

        // Gets the records.
        define(DB_NAME, $modulo);

        $tipo = bTipo::instanciarTipo($chave, $modulo);
        // TODO: Limit of 50 hardcoded. In the future make it an attribute.
        $resultado = $tipo->buscarParaEscolha($valor, $campos, 50);

        $idDescricao = $idComponente . 'Descricao';
        $idItem = $idComponente . 'Item';
        $idTable = $idComponente . 'Table';
        $idDiv = $idComponente . 'Div';
        // If the field is in a component that repeats it (like MGrider), its id comes with brackets because it is an array.
        if ( substr_count($idComponente, '[') > 0 )
        {
            $posColchete = strpos($idComponente, '[');
            
            $idDescricao = substr($idComponente, 0, $posColchete) . 'Descricao' . substr($idComponente, $posColchete);
            $idItem = substr($idComponente, 0, $posColchete) . 'Item' . substr($idComponente, $posColchete);
            $idTable = substr($idComponente, 0, $posColchete) . 'Table' . substr($idComponente, $posColchete);
            $idDiv = substr($idComponente, 0, $posColchete) . 'Div' . substr($idComponente, $posColchete);
        }
        
        $table = NULL;
        
        if ( count($resultado) )
        {
            // Creates a table with the data.
            $table = new MTableRaw();
            
            foreach ( $resultado as $i => $info)
            {
                // Value processing.
                $codigo = str_replace("'", "\'", $info[1]);
                $descricao = str_replace("'", "\'", $info[0]);
                
                // onClick events
                $onClick = 'dojo.byId(\''.$idComponente.'\').value = \''.$codigo.'\'; 
                            dojo.byId(\''.$idDescricao.'\').value = \''.$descricao.'\'; 
                            dojo.byId(\'divResposta' . $idDescricao . '\').style.display=\'none\'; 
                            dojo.destroy(\''. $idTable . '\');
                            dojo.byId(\''.$idDescricao.'\').dispatchEvent(new Event(\'change\'));
                            dojo.byId(\''.$idComponente.'\').dispatchEvent(new Event(\'change\'));';
                
                $onMouseOver = 'bEscolha.deselecionarItem(this);';
                
                $table->setCellAttribute($i, 0, 'onClick', $onClick);
                $table->setCellAttribute($i, 0, 'id', "{$idItem}{$i}");
                $table->setCellAttribute($i, 0, 'style', 'padding:0');

                // Hides 2nd column.
                $table->setCellAttribute($i, 1, 'style', 'display:none');

                //$table->setRowAttribute($i, 'onClick', $onClick);
                
                $resultado[$i] = $info;
            }

            //$table->addAttribute('onMouseOver', $onMouseOver);

            // Sets the table data.
            $table->setData($resultado);
            
            $table->setAlternate( true );
            $table->addAttribute('style','width:100%; position:relative;');
            $table->addAttribute('id',$idTable);
            $table->addAttribute('onmouseout', 'dojo.byId(\'divResposta' . $idDescricao . '\').style.display=\'none\';');
            $table->addAttribute('onmouseover', 'dojo.byId(\'divResposta' . $idDescricao . '\').style.display=\'block\';');
            
            if ( !$resultado )
            {
                $MIOLO->page->onload("dojo.byId('divResposta' . $idDescricao).style.display='none';");
            }
        }
        
        $div = new MDiv($idDiv, $table);
        $div->addStyle('width', '100%');
        $div->addStyle('position', 'absolute');
        $div->addStyle('overflow-x', 'hidden');
        $div->addStyle('max-height', '150px');
        
        $MIOLO->ajax->setResponse( $div, 'divResposta' . $idDescricao);
        if ( $resultado )
        {
            $MIOLO->page->onload("dojo.byId('divResposta{$idDescricao}').style.display='block';");
        }
        
        // Sets focus on the inner DIV.
    $MIOLO->page->onload("dojo.byId('{$idDiv}').focus();");
    }
    
    /**
     * Gets the description column of a given table
     * 
     * @param String $tabela Table name
     * @return String Column that represents the description of a given table
     */
    public function obterColunaDescricaoDaTabela($tabela)
    {
        $colunas = bCatalogo::obterColunasDaTabela("public", $tabela);
        
        $colunaDescricao = "descricao";
        
        foreach( $colunas as $coluna )
        {
            if( in_array($coluna->name, array("descricao", "description")) )
            {
                $colunaDescricao = $coluna->name;
            }
            
        }
        
        return $colunaDescricao;
    }
}

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
 * read the "LICENCA.txt" file included with this software. e
 * 
 * 
 *
 *
 *
 *
 *
 **/
class bEscolhaTextField extends MTextField
{
    
    private $chave;
    private $modulo;
    private $camposDaTabela;
    
    public function getChave() {
        return $this->chave;
    }

    public function setChave($chave) {
        $this->chave = $chave;
    }

    public function getModulo() {
        return $this->modulo;
    }

    public function setModulo($modulo) {
        $this->modulo = $modulo;
    }

    public function getCamposDaTabela() {
        return $this->camposDaTabela;
    }

    public function setCamposDaTabela($camposDaTabela) {
        $this->camposDaTabela = $camposDaTabela;
    }

    /**
     * Overridden method to change the behavior when generating it.
     */
    public function generateInner()
    {
        parent::generateInner();

        if ( !$this->readonly )
        {
            $MIOLO = MIOLO::getInstance();

            $divEscolha = new MDiv('divResposta' . $this->name);
            $divEscolha->addStyle('position', 'relative');
            $divEscolha->addStyle('right', '-107px');
            $divEscolha->addStyle('width', '310px');
            $divEscolha->addStyle('display', 'none');
            $divEscolha->addStyle('max-height', '200px');

            // Link
            //$componente = str_replace("Descricao", "", $this->name);
            $link = new MImageLink("mostrarOpcoes{$this->name}", NULL, "javascript:miolo.doAjax('onkeyUpEscolha', this.id + '|%' + '|' + '{$this->getChave()}' + '|' + '{$this->getModulo()}' + '|' + '{$this->getCamposDaTabela()}', '{$MIOLO->page->getFormId()}');", $imagem = $MIOLO->getUI()->getImage('base', 'arrow-down.png'));
            $link->image->addStyle('vertical-align', 'middle');

            $divComponente = new MSpan('', $this->inner . $link->generate() . $divEscolha->generate());
            $this->inner = $divComponente->generate();
        }
    }
}

// Component EventHandler.
$evento = MIOLO::_REQUEST("{$MIOLO->page->getFormId()}__EVENTTARGETVALUE");

// Gets the ajax parameters.
$args = MIOLO::_REQUEST("{$MIOLO->page->getFormId()}__EVENTARGUMENT");

// List of possible events to be executed on the component.
$eventosPossiveis = array( 'obterRegistro', 'onkeyUpEscolha' );

// Calls the ajax method.
if ( in_array($evento, $eventosPossiveis) )
{
    bEscolha::$evento($args);
}

?>
