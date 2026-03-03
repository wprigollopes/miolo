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
 * leia o arquivo “LICENCA.txt” disponível junto ao código deste software. e
 * 
 * 
 *
 *
 * 
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
            throw new Exception( _M('É necessário informar um arquivo para sincronização de funções.') );
        }
        
        $this->file = $file;
        
        if ( !$module )
        {
            throw new Exception( _M('É necessário informar um modulo para sincronização de funções.') );
        }
        
        $this->module =$module;
        
        //cria função padrão drop_function_if_exists
        $this->createDropFunction();
    }
    
    public function syncronize()
    {
        $content = file_get_contents ( $this->file );
        
        if ( ! $content )
        {
            return false;
        }
        
        $fileFunctions = $this->getSqlFunctions($content);
        $dbFunctions = bCatalogo::listarFuncoes();
        $result->start = count( $dbFunctions ) -1; //desconsidera drop functions
        $result->file = count( $fileFunctions );
        
        //make the real syncronization
        $sqlCommands = explode('CREATE OR REPLACE ', $content );
        
        foreach ( $sqlCommands as $line => $sql )
        {
            //necessidade para rodar as funções e mostrar o erro corretamente
            if ( $sql )
            {
                $sql = 'CREATE OR REPLACE ' . $sql;
                bBaseDeDados::executar( $sql );
            }
        }
        
        $finalDbFunctions = bCatalogo::listarFuncoes();
        
        $result->final = count( $finalDbFunctions ) -1 ; //desconsidera drop functions
        
        //alinha funções por nome para fácil localização
        foreach ( $fileFunctions as $line => $info )
        {
            $functionsF[ $info->function ] = $info->params ? $info->params :  ' ';
        }
        
        $sqlResult ='';
        
        foreach ( $finalDbFunctions as $line => $info )
        {
            //registra sql para geração de funções faltantes
            if ( !$functionsF[ $info->function ]  )
            {
                $functions[ $info->function ][0] = $info->function;
                
                //funções "sobresalentes"
                if ( $info->function != 'plpgsql_call_handler'
                     && $info->function != 'plpgsql_validator'
                     && $info->function != 'drop_function_if_exists'
                   )
                {
                    $source = bCatalogo::obterCodigoFonteDaFuncao( $info->function );
                    $source = $source[0][0];
                    $sqlResult .= $source ."\n";
                }
            }
        }
        
        //funções que vem "sobrando" no select
        unset($functions['plpgsql_call_handler']);
        unset($functions['plpgsql_validator']);
        //função criada pela própria classe
        unset($functions['drop_function_if_exists']);
        
        
        $result->missing = $functions;
        $result->sql = $sqlResult;
       
        return $result;
    }
    
    /**
     * Faz parser do arquivo sql obtendo a listagem de funções
     * 
     * @param type $content conteúdo do arquivo sql
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
     * Retorna um array com os arquivos de sincronização de base do módulo informado.
     * @param string $module
     * @return array 
     */
    public static function listSyncFiles($module)
    {
        $MIOLO = MIOLO::getInstance();
        $path = $MIOLO->getConf('home.miolo').'/modules/'.$module.'/syncdb/functions.sql';
       
        return glob($path);
    }

    /**
     * Cria uma função padrão no banco de dados que serve para drop funções somente
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
        
        bBaseDeDados::executar( $sql );
    }
   
}
?>
