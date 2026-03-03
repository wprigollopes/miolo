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
 * */
class bSyncDatabaseView implements bSync
{
    protected $file;
    protected $syncModule;

    public function __construct($file, $module)
    {
        if ( !$file )
        {
            throw new Exception(_M('É necessário informar um arquivo para sincronização de visões.'));
        }

        $this->file = $file;

        if ( !$module )
        {
            throw new Exception(_M('É necessário informar um modulo para sincronização de visões.'));
        }

        $this->module = $module;
    }

    /**
     * Faz a sincronização do arquivo com o banco
     * 
     * @return stdClass
     */
    public function syncronize()
    {
        $content = file_get_contents($this->file);

        if ( !$content )
        {
            return false;
        }

        //lista views no arquivo e no banco
        $fileViews = $this->getViews($content);
        $dbViews = bCatalogo::listarVisoes('public');

        //marca contadores no resultado
        $result = new stdClass();
        $result->file = count($fileViews);
        $result->start = count($dbViews);

        //explode os conteúdo para executar um por um
        $sqlCommands = explode('CREATE OR REPLACE VIEW', $content);
        //filtra array em função de linha em branco
        $sqlCommands = array_values(array_filter($sqlCommands));

        //passa as instruções uma a uma para mostrar o erro corretamente
        foreach ( $sqlCommands as $line => $sql )
        {
            if ( $sql )
            {
                //exclui e recria a view
                $sql = 'DROP VIEW IF EXISTS ' . $fileViews[$line] . ";\n" . 'CREATE OR REPLACE VIEW' . $sql;
                bBaseDeDados::executar($sql);
            }
        }

        //obtem lista atualizada
        $finalDbViews = bCatalogo::listarVisoes();

        //marca no contador
        $result->final = count($finalDbViews);

        $sqlResult = '';

        //busca views a sobrando no banco
        foreach ( $finalDbViews as $line => $view )
        {
            if ( !in_array($view->name, $fileViews) )
            {
                $missing[] = $view->name;
                $sqlResult .= 'CREATE OR REPLACE VIEW ' . $view->name . ' AS ' . $view->source . "\n\n\n";
            }
        }

        //faltantes
        $result->missing = $missing;
        //sql para incluir no views.sql
        $result->sql = $sqlResult;

        return $result;
    }

    /**
     * Faz parser do arquivo sql obtendo a listagem de funções
     * 
     * @param string $content conteúdo do arquivo sql
     * @return array of stdClass
     * 
     */
    protected function getViews($content)
    {
        preg_match_all("/CREATE OR REPLACE VIEW (.*) AS/", $content, $matches);

        return $matches[1];
    }

    /**
     * Retorna um array com os arquivos de sincronização de base do módulo informado.
     * 
     * @param string $module
     * @return array 
     */
    public static function listSyncFiles($module)
    {
        $MIOLO = MIOLO::getInstance();
        $path = $MIOLO->getConf('home.miolo') . '/modules/' . $module . '/syncdb/views.sql';

        return glob($path);
    }
}

?>