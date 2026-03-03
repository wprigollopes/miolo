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
 * @author Eduardo Bonfandini [eduardo@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Equipe Solis [sagu2@solis.coop.br]
 *
 * @since
 * Class created on 06/10/2010
 *
 **/
class bSyncDatabaseView implements bSync
{
    protected $file;
    protected $syncModule;

    public function __construct($file, $module)
    {
        if ( !$file )
        {
            throw new Exception(_M('Ã‰ necessÃ¡rio informar um arquivo para sincronizaÃ§Ã£o de visÃµes.'));
        }

        $this->file = $file;

        if ( !$module )
        {
            throw new Exception(_M('Ã‰ necessÃ¡rio informar um modulo para sincronizaÃ§Ã£o de visÃµes.'));
        }

        $this->module = $module;
    }

    /**
     * Faz a sincronizaÃ§Ã£o do arquivo com o banco
     * 
     * @return stdClass
     */
    public function syncronize()
    {
        $content = file_get_contents($this->file);
        
        if ( ! $content )
        {
            return false;
        }
        
        bBaseDeDados::consultar($content);

        return true;
    }

    /**
     * Faz parser do arquivo sql obtendo a listagem de funÃ§Ãµes
     * 
     * @param string $content conteÃºdo do arquivo sql
     * @return array of stdClass
     * 
     */
    protected function getViews($content)
    {
        preg_match_all("/CREATE OR REPLACE VIEW (.*) AS/", $content, $matches);

        return $matches[1];
    }

    /**
     * Retorna um array com os arquivos de sincronizaÃ§Ã£o de base do mÃ³dulo informado.
     * 
     * @param string $module
     * @return array 
     */
    public static function listSyncFiles($module)
    {
        $MIOLO = MIOLO::getInstance();
        $path = $MIOLO->getConf('home.miolo').'/modules/'.$module.'/syncdb/views/*.sql';
        $files = glob($path);
        
        return $files;
    }
}

?>
