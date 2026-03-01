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
 * Dynamic registration management form.
 *
 *
 */
class frmDynamicRegistration extends bFormRegistration
{

    public function __construct($parametros)
    {
        parent::__construct(_M('Cadastro dinâmico', MIOLO::getCurrentModule()), $parametros);
    }

    public function buildFields()
    {
        parent::buildFields();
        $this->setTitle($this->tipo->obterComentarioDaTabela());
        
        if ( MUtil::isFirstAccessToForm() )
        {
            MSubDetail::clearData('tabelaReferenciada');
        }
        
        $campos[] = new MTextField('cadastrodinamicoid', NULL, _M('Código'), 10);
        $campos[] = new MTextField('identificador', NULL, _M('Identificador'), 50);
        $campos[] = new MTextField('referencia', NULL, _M('Referência'), 50);
        $campos[] = new MTextField('modulo_', NULL, _M('Módulo'), 20);
        
        // Validators.
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