<?php
/**
 * <--- Copyright 2005-2010 de Solis - Cooperativa de Soluções Livres Ltda.
 *
 * Este arquivo é parte do programa Sagu.
 *
 * O Sagu é um software livre; você pode redistribuí-lo e/ou modificá-lo
 * dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação
 * do Software Livre (FSF); na versão 2 da Licença.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM
 * NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO
 * ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em
 * português para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a
 * Fundação do Software Livre (FSF) Inc., 51 Franklin St, Fifth Floor, Boston,
 * MA 02110-1301, USA --->
 *
 * Form to manipulate insMaterial table records
 *
 * @author Lucas Rodrigo Gerhardt [lucas_gerhardt@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Equipe Solis [sagu2@solis.coop.br]
 *
 * @since
 * Class created on 10/07/2015
 *
 **/

$MIOLO->uses( 'classes/bSync.interface.php','base');
$MIOLO->uses( 'classes/bSyncDatabase.class.php','base');
$MIOLO->uses( 'classes/bSyncDatabaseContent.class.php','base');
$MIOLO->uses( 'classes/bSyncDatabaseFunction.class.php','base');
$MIOLO->uses( 'classes/bSyncDatabaseView.class.php','base');
$MIOLO->uses( 'classes/BString.class.php','base');
$MIOLO->uses( 'classes/bBaseDeDados.class.php','base');
$MIOLO->uses( 'classes/bCatalogo.class.php','base');

class bSyncExecute
{
    public static function executeSync( $syncModule )
    {
        //Definicao para base de dados funcionar adequadamente utilizando o modulo correto
        if ( !defined('DB_NAME') )
        {
           define('DB_NAME', $syncModule);
        }
        
        //Rodar start.php
        function_exists('consoleOutput') ? consoleOutput(_M("Aplicando start.php.")) : null;
        self::runStartScript($syncModule);
        
        //Sincronizar XMLs
        function_exists('consoleOutput') ? consoleOutput(_M("Aplicando alteracoes de arquivos XML.")) : null;
        self::syncAllXML($syncModule);

        //Sincronizar visões e funções.
        $files = self::obterArquivos($syncModule);
        self::sincronizarViewsEFuncoes($files, $syncModule);
        
        //Rodar sync.php
        function_exists('consoleOutput') ? consoleOutput(_M("Aplicando sync.php.")) : null;
        self::runSyncScript($syncModule);
    }
    
    /**
     * 
     * @param string $syncModule
     */
    public static function runStartScript($syncModule)
    {
        $MIOLO = MIOLO::getInstance();
        
        //executa o script de inicializacao
        $startScript = $MIOLO->getConf('home.miolo').'/modules/'.$syncModule.'/syncdb/start.php';

        if ( file_exists( $startScript ) )
        {
            require $startScript;
        }
    }
    
    /**
     * Faz a sincronizaÃ§Ã£o de todos os arquivos XML da pasta syncdb
     * 
     * @param type $syncModule
     * @return type
     */
    public static function syncAllXML($syncModule)
    {
        $MIOLO = MIOLO::getInstance();
        $ignoreXmls = array();
   
        //obtem lista de arquivos xml a sincronizar
        $files = BSyncDatabaseContent::listSyncFiles( $syncModule );

        if ( is_array( $files ) )
        {
            foreach ( $files as $line => $file )
            {
                $tableExtra = null;
                $resultA = null;

                $fileBase = str_replace('.xml', '', basename($file));

                if ( !in_array(strtolower($fileBase), $ignoreXmls) )
                {
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

                   if ( $fileBase == '00-changes' )
                   {
                       $versao = file_get_contents($MIOLO->getModulePath($syncModule, "VERSION"));
                       bBaseDeDados::consultar("SELECT syncDataBase("  . (int)str_replace('.','',$versao) . ")");
                   }

                   if ( $fileBase == '00-ignorexml' )
                   {
                        if ( bCatalogo::verificarExistenciaDaTabela(NULL, 'ignorexml', $syncModule) )
                        {
                           $xmlFiles = bBaseDeDados::consultar('SELECT lower(xmlname) FROM ignorexml');

                            if ( is_array($xmlFiles) )
                            {
                                foreach ( $xmlFiles as $file )
                                {
                                    $ignoreXmls[] = str_replace('.xml', '', $file[0]);
                                }
                            }
                        }
                    } 
                }
            }
        }
    }
    
    
     /**
     * Sincronizar views e funcoes da base de dados.
     * 
     * @param array $files
     */
    public static function sincronizarViewsEFuncoes($files, $syncModule)
    {
        foreach ( $files as $dbSql )
        {
            if ( $dbSql[1] == 'v' )
            {
                $view = new bSyncDatabaseView($dbSql[0], $syncModule);
                $view->syncronize();
            }
            elseif ( $dbSql[1] == 'f' )
            {
                $function = new bSyncDatabaseFunction($dbSql[0], $syncModule);
                $function->syncronize();
            }
        }
        
        return true;
    }
    
    
    
    /**
     * Caso exista script de sincronizaÃ§Ã£o, executa-o
     * 
     * @param string $syncModule
     */
    public static function runSyncScript($syncModule)
    {
        $MIOLO = MIOLO::getInstance();
        $syncScript = $MIOLO->getConf('home.miolo').'/modules/'.$syncModule.'/syncdb/sync.php';

        if ( file_exists( $syncScript ) )
        {
            include $syncScript;
        }
    }
    
    /**
     * Obtem arquivos de views e funcoes da base de dados.
     * 
     * @param string $syncModule
     * 
     * @return array
     */
    public static function obterArquivos($syncModule)
    {
        $functions = BSyncDatabaseFunction::listSyncFiles($syncModule);
        $views = bSyncDatabaseView::listSyncFiles($syncModule);
        
        $fv = array_merge($functions, $views);
        
        $final = array();
        foreach ( $fv as $content )
        {
            $pathInfo = pathinfo($content);
            $fileName = substr($pathInfo['filename'], 1);
            $final[$fileName][0] = $content;
            $final[$fileName][1] = substr($pathInfo['filename'], 0, 1);
        }
        
        ksort($final);
        
        return $final;
    }


}

?>