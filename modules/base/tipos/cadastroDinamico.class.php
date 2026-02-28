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
 * Class that represents the dynamic registration table.
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
     * Checks if the identifier exists in the database.
     *
     * @param string $identificador Dynamic registration identifier.
     * @return boolean Returns true if there is a dynamic registration for the identifier.
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
     * Public method to populate the dynamic registration through module and identifier.
     *
     * @param string $modulo Module of the dynamic registration.
     * @param string $identificador Identifier of the dynamic registration.
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
     * Gets the tables that are related to the main table.
     *
     * @return array Array with the names of the related tables.
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