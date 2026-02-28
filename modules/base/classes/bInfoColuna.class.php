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
 * Class that represents a column of a table.
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
     * @var character Indicates whether it is a foreign key (f) or primary key (p).
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
     * @var string Name of the class attribute (type or business) by which the column is represented.
     */
    public $atributo;

    /**
     * @var string Field name in the form.
     */
    public $campo;

    /**
     * Column type constants.
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
     * @return array Lists the supported field types.
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