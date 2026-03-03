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
class bSyncDatabaseFunction implements bSync
{
    protected $file;
    protected $syncModule;
    
    public function __construct( $file , $module )
    {
        if ( !$file )
        {
            throw new Exception( _M('Ã‰ necessÃ¡rio informar um arquivo para sincronizaÃ§Ã£o de funÃ§Ãµes.') );
        }
        
        $this->file = $file;
        
        if ( !$module )
        {
            throw new Exception( _M('Ã‰ necessÃ¡rio informar um modulo para sincronizaÃ§Ã£o de funÃ§Ãµes.') );
        }
        
        $this->module =$module;
        
        //cria funÃ§Ã£o padrÃ£o drop_function_if_exists
        $this->createDropFunction();
    }
    
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
     * @param type $content conteÃºdo do arquivo sql
     * @return array of stdClass
     * 
     */
    protected function getSqlFunctions( $content )
    {
        $regexp = "/CREATE OR REPLACE FUNCTION (.*)\((.*)\)/";
        
        preg_match_all($regexp, $content, $matches);
        
        $functions = $matches[1];
        $parameters = $matches[2];
        
        foreach ( $functions as $line => $info)
        {
            $obj = new stdClass();
            $obj->function = strtolower( $info );
            $obj->params = $parameters[$line];
            $result[] = $obj;
        }
        
        return $result;
    }
    
    /**
     * Retorna um array com os arquivos de sincronizaÃ§Ã£o de base do mÃ³dulo informado.
     * @param string $module
     * @return array 
     */
    public static function listSyncFiles($module)
    {
        $MIOLO = MIOLO::getInstance();
        $path = $MIOLO->getConf('home.miolo').'/modules/'.$module.'/syncdb/functions/*.sql';
        $files = glob($path);
        
        return $files;
    }

    /**
     * Cria uma funÃ§Ã£o padrÃ£o no banco de dados que serve para drop funÃ§Ãµes somente
     * se elas existem
     */
    public function createDropFunction()
    {
        $sql ="
            CREATE OR REPLACE FUNCTION drop_function_if_exists( name varchar )
            RETURNS void as \$BODY\$
            DECLARE
            v_sql varchar;
            BEGIN
                FOR v_sql IN SELECT 'DROP FUNCTION '|| proname || '(' ||
                    (
                    SELECT array_to_string( array_agg(coalesce(varname,'') || ' ' ||
                                        ( SELECT coalesce(typname,'') 
                                            FROM pg_type
                                            WHERE oid::varchar = type::varchar 
                                        ) ) , ' , ' )
                                        FROM (    SELECT  unnest( coalesce(proargnames,ARRAY[''] ) ) as varname,
                                                        regexp_split_to_table( proargtypes::varchar , E'\\ +') as type
                                                FROM  pg_proc A
                                                WHERE lower(A.proname) = lower(name)
                                                and pg_proc.oid = oid                                               
                                        ) as foo ) || ');'                                              
            FROM pg_proc                                                                                             
            WHERE lower(proname) = lower( name )
            LOOP
                    EXECUTE v_sql;
                END LOOP;
            END
            \$BODY\$
            LANGUAGE 'plpgsql';


            CREATE OR REPLACE FUNCTION drop_function_if_exists( name varchar, param varchar )
            RETURNS void as \$BODY\$
            BEGIN
                PERFORM drop_function_if_exists(lower(name));
            END
            \$BODY\$
            LANGUAGE 'plpgsql';
        ";
        
        bBaseDeDados::consultar( $sql );
    }
     
   
}
?>
