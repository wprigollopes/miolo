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
 * Formulário de busca de cadastro dinâmico.
 *
 */

class frmBuscaDinamicaBusca extends bFormBusca
{
    public function __construct($parametros)
    {
        parent::__construct(_M('Busca de busca dinâmica', MIOLO::getCurrentModule()), $parametros);
    }

    public function definirCampos()
    {
        parent::definirCampos();

        $filtros = array();
        $colunas = array();

        $filtros[] = new MTextField('buscaDinamicaId', NULL, _M('Código'), 10);
        $filtros[] = new MTextField('identificador', NULL, _M('Identificador'), 50);
        $filtros[] = new MTextField('modulo_', NULL, _M('Módulo'), 20);
        
        $this->adicionarFiltros($filtros);

        $colunas[] = new MGridColumn(_M('Código', $this->modulo));
        $colunas[] = new MGridColumn(_M('Identificador', $this->modulo));
        $colunas[] = new MGridColumn(_M('Módulo', $this->modulo));
        
        $this->criarGrid($colunas);
    }
}

?>
