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
 * Gnuteca is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation (FSF); version 2 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License/GPL
 * for more details.
 * 
 * You should have received a copy of the GNU General Public License, under
 * the title "LICENCA.txt", along with this program. If not, visit the
 * Brazilian Public Software Portal at www.softwarepublico.gov.br or write
 * to the Free Software Foundation (FSF) Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301, USA --->
 * 
 *
 *
 * 
 *
 **/
set_time_limit(10000);
ini_set('max_execution_time', 10000);
ini_set('max_input_time',10000);

$theme->clearContent();

$syncModule = MIOLO::_REQUEST('syncModule');
$MIOLO->uses( 'classes/bBaseDeDados.class.php','base');
$MIOLO->uses( 'classes/BString.class.php','base');
$MIOLO->uses( 'classes/bCatalogo.class.php','base');
$MIOLO->uses( 'classes/bColumnInfo.class.php','base');

if ( !$syncModule )
{
    // Adds required classes.
    $MIOLO->uses( 'classes/bForm.class.php','base');
    $MIOLO->uses( 'classes/bFormRegistration.class.php','base');
    $MIOLO->uses( 'classes/bFormSearch.class.php','base');
    $MIOLO->uses( 'classes/bType.class.php','base');
    $MIOLO->uses( 'classes/bBarraDeFerramentas.class.php','base');
    $MIOLO->uses( 'classes/bJavascript.class.php','base');
    $MIOLO->uses( 'classes/bBooleano.class.php','base');
    $MIOLO->uses( 'classes/bChoice.class.php','base');

    // Adds the Base module javascript library.
    $MIOLO->page->addScript('base.js','base');

    // Defines the database.
    define(DB_NAME, 'base');
    
    // Constants for the correct operation of the module.
    define(DB_TRUE, 't');
    define(DB_FALSE, 'f');
    define(FUNCAO_BUSCAR, 'buscar');
    define(FUNCAO_EDITAR, 'editar');
    define(FUNCAO_INSERIR, 'inserir');
    define(FUNCAO_REMOVER, 'remover');
    define(FUNCAO_EXPLORAR, 'explorar');
    define(MODULO, 'base');

    // Constants for field size.
    define(T_CODIGO, 10);
    define(T_INTEIRO, 10);
    define(T_DESCRICAO, 25);

    // Includes the handler.
    $chave = MIOLO::_REQUEST('chave');
    
    if ( strlen($chave) > 0 )
    {
        $MIOLO->uses('handlers/manipulador.inc.php', 'base');
    }
    else
    {
        $shiftAction = $context->shiftAction();

        if ( $shiftAction )
        {
            $MIOLO->invokeHandler($module, $shiftAction);
        }
    }
}
else
{
    $MIOLO->uses( 'classes/bSync.interface.php','base');
    $MIOLO->uses( 'classes/bSyncDatabase.class.php','base');
    $MIOLO->uses( 'classes/bSyncDatabaseContent.class.php','base');
    $MIOLO->uses( 'classes/bSyncDatabaseFunction.class.php','base');
    $MIOLO->uses( 'classes/bSyncDatabaseView.class.php','base');

    if ( !defined('DB_NAME') )
    {
        define('DB_NAME', $syncModule );
    }
    
    $MIOLO->page->addJsCode(
    "
        /* Hides / shows content of a table */
        function showHideTable( element )
        {
            if ( element.tBodies[0].style.display == '' )
            {
                element.tBodies[0].style.display ='none'
            }
            else
            {
                element.tBodies[0].style.display ='';
            }
        }
    "
    );

    $MIOLO->page->onload("

    // iterates through all tables adding hide/show support
    tables = document.getElementsByClassName('mSimpleTable  mTableRaw');
    //tables = document.getElementsByTagName('table');

    for ( i =0 ; i < tables.length ; i++ )
    {
        tables[i].style.width ='100%';
        tables[i].tBodies[0].style.display ='none';

        if ( tables[i].caption != null ) 
        {
            tables[i].caption.style.cursor= 'pointer';
            tables[i].caption.setAttribute('onclick', 'showHideTable(this.parentNode)' );
        }
    }");

    try
    {    
        //$db = new bBaseDeDados($syncModule);
        bBaseDeDados::iniciarTransacao();

        if ( ! $syncModule )
        {
            throw new Exception( new BString('É necessário informar modulo de sincronização (syncModule).') );
        }

        // creates a top space
        $fields[] = new MDiv('','<br/><br/><br/>');

        // executes the initialization script
        $startScript = $MIOLO->getConf('home.miolo').'/modules/'.$syncModule.'/syncdb/start.php';

        if ( file_exists( $startScript ) )
        {
            require $startScript;
        }

        $syncFiles = BSyncDatabase::listSyncFiles( $syncModule );

        $syndDb = null;
        $tablesById = null;

        if ( is_array( $syncFiles ) ) 
        {
            foreach ( $syncFiles as $line => $syncFile )
            {
                $syncDb = new bSyncDatabase( file_get_contents( $syncFile ) );
                $syncDb->setModule($syncModule);
                $tablesById = $syncDb->syncronize();
            }
        }

        $functionFiles = BSyncDatabaseFunction::listSyncFiles( $syncModule );

        // performs database function synchronization
        if ( is_array( $functionFiles ) ) 
        {
            foreach ( $functionFiles as $line => $function )
            {
                $function = new BSyncDatabaseFunction( $function , $syncModule );
                $fResult = $function->syncronize();
                $functions = array_values( $fResult->missing );

                $resultTable[0] = array( _M('Inicial'), $fResult->start );
                $resultTable[1] = array( _M('Arquivo'), $fResult->file );
                $resultTable[2] = array( _M('Final'), $fResult->final );
                $resultTable[3] = array( _M('Faltando'), ( $fResult->final- $fResult->file) );

                if ( $functions )
                {
                    $myTable = new MTableRaw( _M( new bString( 'Faltantes' ) ) , $functions, array(),'', true );
                    $resultTable[3][1] .= '<br/> ' .$myTable->generate();
                }

                $fields[] = new MTableRaw( _M( new bString( 'Funções' ) ) , $resultTable, array( new bString( _M('Situação') ),_M( new bString( 'Contagem') ) ), 'functions', true);

                if ( $functions )
                {
                    if ( $fResult->sql )
                    {
                        $fields[] = new MMultiLineField('sqlFunctions', $fResult->sql );
                    }
                }

                $fields[] = new MSeparator('<br/>');
            }
        }

        if ( $syncDb != null)
        {
            $syncDb->syncronizeTriggersAndContraints($tablesById);
            $messages = $syncDb->getMessages();

            $fields[] = new MTableRaw(new BString('Sincronização de estrutura de base de dados'), $messages, array( new BString('Mensagem') ), 'syncDatabase', true);
            $fields[] = new MSeparator('<br/>');
        }

        $views = BSyncDatabaseView::listSyncFiles( $syncModule );

        // performs database view synchronization
        if ( is_array( $views ) ) 
        {
            foreach ( $views as $line => $view )
            {
                $view = new BSyncDatabaseView( $view, $syncModule );
                $vResult = $view->syncronize();

                $missing= array_values( $vResult->missing );

                $resultTable = array();
                $resultTable[0] = array( _M('Inicial'), $vResult->start );
                $resultTable[1] = array( _M('Arquivo'), $vResult->file );
                $resultTable[2] = array( _M('Final'), $vResult->final );
                $resultTable[3] = array( _M('Faltando'), ( $vResult->final- $vResult->file) );

                if ( $missing )
                {
                    $myTable = new MTableRaw( _M( new bString( 'Faltantes' ) ) , $missing, array(),'', true );
                    $resultTable[3][1] .= '<br/> ' .$myTable->generate();
                }

                $fields[] = new MTableRaw( _M( new bString( 'Visões' ) ) , $resultTable, array( new bString( _M('Situação') ),_M( new bString( 'Contagem') ) ), 'views', true);

                if ( $functions )
                {
                    if ( $vResult->sql )
                    {
                        $fields[] = new MMultiLineField('viewsMissing', $vResult->sql );
                    }
                }

                $fields[] = new MSeparator('<br/>');
            }
        }

        // gets list of xml files to synchronize
        $files = BSyncDatabaseContent::listSyncFiles( $syncModule );

        if ( is_array( $files ) )
        {
            foreach ( $files as $line => $file )
            {
                $tableExtra = null;
                $resultA = null;
                //$fields = null;

                $fileBase = str_replace('.xml', '', basename($file));

                $basConfig = new BSyncDatabaseContent( );
                $basConfig->setXmlPath($file);
                $basConfig->setModule( $syncModule );

                if ( strpos($file, 'miolo_') )
                {
                    $basConfig->setModule( 'admin' );
                }
                else
                {
                    $basConfig->setModule( $syncModule );
                }

                $result = $basConfig->syncronize();
                $resultExtras = null;

                $resultA = array();
                $resultA[0] = array( new BString( _M('Contagem xml') ), $result->countXml );
                $resultA[1] = array( new BString( _M('Contagem inicial') ),$result->countStart );
                $resultA[2] = array( new BString( _M('Atualizações') ) ,$result->updateCount );
                $resultA[3] = array( new BString( _M('Inserções') ),$result->insertCount );
                $resultA[4] = array( new BString( _M('Remoções') ),$result->deleteCount );
                $resultA[5] = array( new BString( _M('Contagem final') ),$result->countEnd );

                if ( is_array( $result->extras ) )
                {
                    foreach ( $result->extras as $line => $extra )
                    {
                        $resultE = array();
                        $columns = array();

                        foreach ( $extra as $l => $item )
                        {
                            $columns[] = $l;
                            $resultE[] = $item;
                        }

                        $resultExtras[] = $resultE;
                    }

                    $tableExtra[] = new MTableRaw( '', $resultExtras, array_values($columns), '', true );

                    $resultA[6] = array( _M('Itens sobrando'), $tableExtra );
                }

                $title = $fileBase ;

                if ( $resultA[6] )
                {
                    $title .= new bString( _M(' - Atualizado') );
                }

                $fields[] = new MTableRaw( $title , $resultA, array(_M('Tipo'),_M('Quantidade')), 'result'.$fileBase , true );
                $fields[] = new MSeparator('<br/>');

                // includes differences xml if it exists
                $xml = $basConfig->makeXMLfromResult( $result->extras );

                if ( $xml )
                {
                    $fields[] = new MultilineField('xml'.$fileBase , $xml);
                }
            }
        }

        $theme->appendContent( $fields );

        // if a synchronization script exists, executes it
        $syncScript = $MIOLO->getConf('home.miolo').'/modules/'.$syncModule.'/syncdb/sync.php';

        if ( file_exists( $syncScript ) )
        {
            require $syncScript;
        }

        bBaseDeDados::finalizarTransacao();
    }
    catch (Exception $e)
    {
        //$db = new bBaseDeDados($syncModule);
        die( bBaseDeDados::obterUltimoErro() .' -<br/>SQL = '.  bBaseDeDados::obterUltimaInstrucao() . '<br/>Mensagem='.$e->getMessage().'-'.$e->getFile().'-'.$e->getLine());
    }
}
?>
