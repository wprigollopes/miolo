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
 * leia o arquivo “LICENCA.txt” disponível junto ao código deste software.
 *
 *
 * Formulário de gerenciamento de cadastri dinâmico.
 *
 *
 */
class frmCadastroDinamico extends bFormCadastro
{

    public function __construct($parametros)
    {
        parent::__construct(_M('Cadastro dinâmico', MIOLO::getCurrentModule()), $parametros);
    }

    public function definirCampos()
    {
        parent::definirCampos();
        $this->setTitle($this->tipo->obterComentarioDaTabela());
        
        if ( MUtil::isFirstAccessToForm() )
        {
            MSubDetail::clearData('tabelaReferenciada');
        }
        
        $campos[] = new MTextField('cadastrodinamicoid', NULL, _M('Código'), 10);
        $campos[] = new MTextField('identificador', NULL, _M('Identificador'), 50);
        $campos[] = new MTextField('referencia', NULL, _M('Referência'), 50);
        $campos[] = new MTextField('modulo_', NULL, _M('Módulo'), 20);
        
        // Validadores.
        $validador = array( );
        $validador[] = new MRequiredValidator('identificador', '', 50);
        $validador[] = new MRequiredValidator('referencia');
        $validador[] = new MRequiredValidator('modulo_', '', 20);

        $camposTabelaReferenciada = array();
        $camposTabelaReferenciada[] = new MTextField('referencia', NULL, _M('Referência'), T_DESCRICAO);
        
        $colunasTabelaReferenciada[] = new MGridColumn( _M('Tabela referênciada'), 'left', TRUE, NULL, TRUE, 'referencia' );
       
        $validadorTabelaReferenciada = array();
        $validadorTabelaReferenciada[] = new MRequiredValidator('referencia');
        
        $campos[] = $tabelaReferenciada = new MSubDetail('tabelaReferenciada', _M('Campos da busca dinâmica'), $colunasTabelaReferenciada, $camposTabelaReferenciada, array('remove') );
        $tabelaReferenciada->setValidators($validadorTabelaReferenciada);
        
        $this->addFields($campos);
        $this->setValidators($validador);
    }
    
}

?>