<?php

/**
 * Copyright 2005-2017 de Solis Soluções Livres Ltda.
 *
 * Este arquivo é parte do programa SolisGE/Sagu.
 *
 * O SolisGE/Sagu é um software de propriedade da SOLIS, sendo desenvolvido
 * e mantido exclusivamente por esta empresa.
 *
 * A licença de uso está disponível mediante aquisição exclusiva junto à
 * SOLIS. A licença é concedida sem caráter de exclusividade ao licenciado.
 * Os direitos de uso são perpétuos.
 *
 * Embora os códigos fontes sejam fornecidos, o software é de propriedade
 * da SOLIS, não sendo permitido ao adquirente da licença a sua revenda,
 * empréstimo ou cessão (onerosa ou não) à terceiros. Também não é permitido,
 * a qualquer título e tempo, promover no software qualquer tipo de alienação,
 * reprodução, distribuição, divulgação, registro, licenciamento, transferência
 * ou qualquer outro ato que prejudique ou comprometa os direitos de propriedade
 * de software, o nome e a imagem da sua proprietária e do próprio software,
 * além de configurar concorrência à SOLIS.
 *
 * O licenciado, com o acesso ao código fonte do software, terá o direito de
 * promover mudanças no respectivo código. No entanto, nas situações em que ele
 * contar com o suporte oficial prestado pela SOLIS, não poderá promover mudanças
 * no código fonte, sob pena de perda do referido suporte.
 *
 * Para conhecer em detalhes o Termo de Licenciamento do Software SolisGE/Sagu
 * leia o arquivo “LICENCA.txt” disponível junto ao código deste software. e
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
        
        // Testa se componente possui nome.
        if ( strlen($nome) == 0 )
        {
            throw new Exception(_M("É necessário definir um nome para o componente bEscolha."));
        }

        // Testa se componente possui a chave.
        if ( strlen($chave) == 0 )
        {
            throw new Exception(_M("É necessário definir uma chave para o componente bEscolha."));
        }
        
        // Testa se componente possui módulo.
        if ( strlen($modulo) == 0 )
        {
            throw new Exception(_M("É necessário definir um módulo para o componente bEscolha."));
        }

	if(!$camposDaTabela)
	{
            $camposDaTabela = $nome.', ' . $this->obterColunaDescricaoDaTabela($chave);
	}
        
        // Define campos padrão do componente.
        $campos = array();
        $campos[] = $this->codigo = new MTextField($nome, $valor, $rotulo, 5, NULL, NULL, $somenteLeitura);
        $this->codigo->addAttribute('onchange', "miolo.doAjax('obterRegistro',this.id + '|' + this.value + '|' + '$chave' + '|' + '$modulo' + '|' + '$camposDaTabela','{$MIOLO->page->getFormId()}');");
        
        $campos[] = $this->descricao = new bEscolhaTextField($nome . 'Descricao', NULL, NULL, 50, NULL, NULL, $somenteLeitura);
        $this->descricao->setChave($chave);
        $this->descricao->setModulo($modulo);
        $this->descricao->setCamposDaTabela($camposDaTabela);
        $this->descricao->addAttribute('onkeydown', "return bEscolha.onkeyUpEscolha(event, this, this.id, '{$chave}', '{$modulo}', '{$camposDaTabela}')" );
        $this->descricao->addAttribute('ondblclick',"return bEscolha.onDoubleClick(event, this.id, '{$chave}', '{$modulo}', '{$camposDaTabela}')");
        
        // Seta o validador no código
        if ( !is_null($validator) )
        {
            $this->codigo->validator = $validator;
        }
        
        parent::__construct(NULL, $campos);
        $this->page->addScript('bEscolha.js', 'base');
        
        // Linha necessária para buscar o valor da descrição quando o campo é read-only ou quando o form estiver em edição.
        $MIOLO->page->onload("var elem = dojo.byId('$nome'); if(elem != null){elem.onchange();}");
    }
    
    /**
     * Método para definir o componente como somente leitura.
     * 
     * @param boolean $somenteLeitura Caso verdadeiro, deixa os controles do componente em modo somente leitura.
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
     * Método para adicionar eventos ao componente.
     * 
     * @param String $evento Evento que será adicionado ao componente.
     * @param String $valor Valor do evento.
     */
    public function addEvent($evento, $valor)
    {
        $this->codigo->addEvent($evento, $valor);
        
        /**
         * Tivemos que tirar isso fora, porque estava dando problemas quando o
         * componente tinha algum evento que sobrescreve um evento padrão do 
         * bEscolha, exemplo: "onChange".
         * 
         * Não conseguimos identificar o porquê é que isso foi feito, mas ao
         * eliminar essa linha tudo voltou a funcionar. Também não teria porque
         * adicionar o evento também na descrição, pois a descrição vai jogar
         * valores pro código e o código vai realizar eventos normalmente.
         * 
         * Ticket #40853
         */
//        $this->descricao->addEvent($evento, $valor);
    }
    
    /**
     * Método para adicionar atríbutos ao componente.
     *  
     * @param String $atributo Atríbuto que será adicionado ao componente.
     * @param String $valor Valor do atríbuto.
     */
    public function addAttribute($atributo, $valor)
    {
        $this->codigo->addAttribute($atributo, $valor);
    }
   
    /**
     *  Método utilizado para auto-completar a descrição.
     * 
     * @param stdClass $parametros Argumentos do Ajax.
     */
    public function obterRegistro($parametros)
    {
        $MIOLO = MIOLO::getInstance();
        
        // Obtém id do componente e valor.
        $parametros = explode('|', $parametros);        
        $idComponente = $parametros[0];
        $valor = $parametros[1];
        $chave = $parametros[2];
        $modulo = $parametros[3];
        $campos = explode(',', $parametros[4]);
        $campoChave = $campos[0];
        
        // Para garantir o funcionamento com o bTipo dinamico, efetuar um trim nos nomes das colunas.
        foreach($campos as $key => $campo)
        {
            $campos[$key] = trim($campos[$key]);
        }

        define(DB_NAME, $modulo);
        
        $tipo = bTipo::instanciarTipo($chave, $modulo);

        // Protecao para evitar erro de base de dados
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
        // Se o campo estiver em um componente que o repete (como o MGrider), o seu id vem com colchetes por ser um array.
        if ( substr_count($idComponente, '[') > 0 )
        {
            $posColchete = strpos($idComponente, '[');
            $idDescricao = substr($idComponente, 0, $posColchete) . 'Descricao' . substr($idComponente, $posColchete);
        }
        
        $MIOLO->page->onload("dojo.byId('{$idDescricao}').value = '{$descricao}'");
        
        // Response ajax.
        $MIOLO->page->ajax->setResponse(NULL, 'responseDiv');
    }
    
    /**
     * Função chamada após a digitação dos dados.
     * 
     * @param stdClass $args Argumentos do Ajax.
     */
    public function onkeyUpEscolha($parametros)
    {
        $MIOLO = MIOLO::getInstance();
        
        // Obtém id do componente e valor.
        $parametros = explode("|", $parametros);

        $idComponente = $parametros[0];
        $idComponente = str_replace(array("mostrarOpcoes", "Descricao"), "", $idComponente);

        $valor = $parametros[1];
        $chave = $parametros[2];
        $modulo = $parametros[3];
        $campos = explode(',', $parametros[4]);
        // Para garantir o funcionamento com o bTipo dinamico, efetuar um trim nos nomes das colunas.
        foreach($campos as $key => $campo)
        {
            $campos[$key] = trim($campos[$key]);
        }

        // Obtém os registros.
        define(DB_NAME, $modulo);

        $tipo = bTipo::instanciarTipo($chave, $modulo);
        // TODO: Limite de 50 fixo no código. No futuro fazer um atributo.
        $resultado = $tipo->buscarParaEscolha($valor, $campos, 50);

        $idDescricao = $idComponente . 'Descricao';
        $idItem = $idComponente . 'Item';
        $idTable = $idComponente . 'Table';
        $idDiv = $idComponente . 'Div';
        // Se o campo estiver em um componente que o repete (como o MGrider), o seu id vem com colchetes por ser um array.
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
            // Cria uma tabela com os dados.
            $table = new MTableRaw();
            
            foreach ( $resultado as $i => $info)
            {
                // Tratamento dos valores.
                $codigo = str_replace("'", "\'", $info[1]);
                $descricao = str_replace("'", "\'", $info[0]);
                
                // Eventos do onClick
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

                // Esconde 2º coluna.
                $table->setCellAttribute($i, 1, 'style', 'display:none');

                //$table->setRowAttribute($i, 'onClick', $onClick);
                
                $resultado[$i] = $info;
            }

            //$table->addAttribute('onMouseOver', $onMouseOver);

            // Define os dados da tabela.
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
        
        // Coloca foco na DIV interna.
    $MIOLO->page->onload("dojo.byId('{$idDiv}').focus();");
    }
    
    /**
     * Obtém a coluna de descrição de uma data tabela
     * 
     * @param String $tabela Nome da tabela
     * @return String Coluna que representa a descrição de uma dada tabela
     */
    public function obterColunaDescricaoDaTabela($tabela)
    {
        $colunas = bCatalogo::obterColunasDaTabela("public", $tabela);
        
        $colunaDescricao = "descricao";
        
        foreach( $colunas as $coluna )
        {
            if( in_array($coluna->nome, array("descricao", "description")) )
            {
                $colunaDescricao = $coluna->nome;
            }
            
        }
        
        return $colunaDescricao;
    }
}

/**
 * Copyright 2005-2017 de Solis Soluções Livres Ltda.
 *
 * Este arquivo é parte do programa SolisGE/Sagu.
 *
 * O SolisGE/Sagu é um software de propriedade da SOLIS, sendo desenvolvido
 * e mantido exclusivamente por esta empresa.
 *
 * A licença de uso está disponível mediante aquisição exclusiva junto à
 * SOLIS. A licença é concedida sem caráter de exclusividade ao licenciado.
 * Os direitos de uso são perpétuos.
 *
 * Embora os códigos fontes sejam fornecidos, o software é de propriedade
 * da SOLIS, não sendo permitido ao adquirente da licença a sua revenda,
 * empréstimo ou cessão (onerosa ou não) à terceiros. Também não é permitido,
 * a qualquer título e tempo, promover no software qualquer tipo de alienação,
 * reprodução, distribuição, divulgação, registro, licenciamento, transferência
 * ou qualquer outro ato que prejudique ou comprometa os direitos de propriedade
 * de software, o nome e a imagem da sua proprietária e do próprio software,
 * além de configurar concorrência à SOLIS.
 *
 * O licenciado, com o acesso ao código fonte do software, terá o direito de
 * promover mudanças no respectivo código. No entanto, nas situações em que ele
 * contar com o suporte oficial prestado pela SOLIS, não poderá promover mudanças
 * no código fonte, sob pena de perda do referido suporte.
 *
 * Para conhecer em detalhes o Termo de Licenciamento do Software SolisGE/Sagu
 * leia o arquivo “LICENCA.txt” disponível junto ao código deste software. e
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
     * Método reescrito com objetivo de alterar o comportamemto ao gerá-lo.
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

// EventHandler do componente.
$evento = MIOLO::_REQUEST("{$MIOLO->page->getFormId()}__EVENTTARGETVALUE");

// Obtém os parâmetros do ajax.
$args = MIOLO::_REQUEST("{$MIOLO->page->getFormId()}__EVENTARGUMENT");

// Lista de eventos possíveis de serem executados no componente.
$eventosPossiveis = array( 'obterRegistro', 'onkeyUpEscolha' );

// Chama o método ajax.
if ( in_array($evento, $eventosPossiveis) )
{
    bEscolha::$evento($args);
}

?>
