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
 * Classe que representa a tabela de cadastro dinâmico.
 *
 *
 *
 *
 */
class cadastroDinamico extends bTipo
{

    public function __construct($chave)
    {
        parent::__construct($chave);
        $this->tiposRelacionados[] = 'tabelaReferenciada';
    }
    
    /**
     * Verifica se identificador existe na base.
     *
     * @param string $identificador Identificador do cadastro dinâmico.
     * @return boolean Retorna positivo caso tenha um cadastro dinâmico para o identificador.
     */
    public static function verificarIdentificador($modulo, $identificador)
    {
        $msql = new MSQL();
        $msql->setTables('cadastrodinamico');
        $msql->setColumns('count(*)');
        $msql->setWhere('identificador = ?');
        $msql->setWhere('modulo = ?');
        
        $retorno = bBaseDeDados::consultar($msql, array($identificador, $modulo));

        return ($retorno[0][0] > 0);
    }

    /**
     * Método público para popular o cadastro dinâmico através do módulo e identificador.
     * 
     * @param string $modulo Módulo do cadastro dinâmico.
     * @param string $identificador Identificador do cadastro dinâmico.
     */
    public function popularPorIdentificador($modulo, $identificador)
    {
        $filtro = new stdClass();
        $filtro->modulo = $modulo;
        $filtro->identificador = $identificador;
        
        $resultado = $this->buscar($filtro, 'cadastrodinamicoid');

        if ( is_array($resultado) )
        {
            $this->definir($resultado[0]);
            $this->popular();
        }
    }
    
    /**
     * Obtém as tabelas que são relacionadas do a tabela principal.
     * 
     * @return array Vetor com o nome das tabelas que são relacionadas. 
     */
    public function obterTabelasRelacionadas()
    {
        $tabelasRelacionadas = $this->dadosTiposRelacionados['tabelaReferenciada'];
        
        if ( is_array($tabelasRelacionadas) )
        {
            $referencias = array();
            foreach ( $tabelasRelacionadas as $tabelaRelacionada )
            {
                $referencia = explode('.', $tabelaRelacionada->referencia);
                $referencias[] = end($referencia);
            }
            
            return $referencias;
        }
    }
}

?>