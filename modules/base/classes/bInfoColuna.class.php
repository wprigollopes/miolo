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
 * Classe que representa uma coluna de uma tabela.
 *
 *
 *
 *
 */

class bInfoColuna
{
    /**
     * @var string 
     */
    public $esquema = 'public';

    /**
     * @var string 
     */
    public $tabela;

    /**
     * @var string 
     */
    public $nome;

    /**
     * @var string 
     */
    public $tipo;

    /**
     * @var string 
     */
    public $titulo;

    /**
     * @var string 
     */
    public $obrigatorio;

    /**
     * @var string 
     */
    public $valorPadrao;

    /**
     * @var string 
     */
    public $tamanho;

    /**
     * @var character Informa se é chave estrangeira (f) ou chave primária (p).
     */
    public $restricao;

    /**
     * @var string 
     */
    public $fkEsquema;

    /**
     * @var string 
     */
    public $fkTabela;

    /**
     * @var string 
     */
    public $fkColuna;

    /**
     * @var string 
     */
    public $valoresPossiveis;

    /**
     * @var string 
     */
    public $editavel;

    /**
     * @var string 
     */
    public $visivel;

    /**
     * @var string 
     */
    public $filtravel;

    /**
     * @var string 
     */
    public $exibirNaGrid;

    /**
     * @var string 
     */
    public $parametros;

    /**
     * @var boolean 
     */
    public $chave;

    /**
     * @var string Nome do atributo da classe (type ou business) pelo qual a coluna é representada.
     */
    public $atributo;

    /**
     * @var string Nome do campo no formulário.
     */
    public $campo;

    /**
     * Constantes para os tipos de coluna.
     */
    const TIPO_TEXTO = 'character varying';
    const TIPO_CHAR = 'character';
    const TIPO_TEXTO_LONGO = 'text';
    const TIPO_INTEIRO = 'integer';
    const TIPO_INTEIRO_LONGO = 'bigint';
    const TIPO_DECIMAL = 'real';
    const TIPO_LISTA = 'list';
    const TIPO_DATA = 'date';
    const TIPO_TIMESTAMP = 'timestamp without time zone';
    const TIPO_TIME = 'time without time zone';
    const TIPO_BOOLEAN = 'boolean';
    const TIPO_NUMERIC = 'numeric';
    const TIPO_DOUBLE = 'double precision';
    const TIPO_INTERVAL = 'interval';

    /**
     * @return array Lista os tipo de campos suportados.
     */
    public static function listarTipos()
    {
        return array(
            self::TIPO_TEXTO => _M('Texto'),
            self::TIPO_TEXTO_LONGO => _M('Texto longo'),
            self::TIPO_INTEIRO => _M('Integer'),
            self::TIPO_DECIMAL => _M('Decimal'),
            self::TIPO_NUMERIC => _M('Numérico'),
            self::TIPO_LISTA => _M('Lista'),
            self::TIPO_DATA => _M('Data'),
            self::TIPO_TIMESTAMP => _M('Timestamp'),
            self::TIPO_BOOLEAN => _M('Boolean')
        );
    }

    /**
     *
     * @return boolean
     */
    public function eChavePrimaria()
    {
        return $this->restricao == 'p';
    }
}

?>