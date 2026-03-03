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
 *
 * This file handles the connection and actions for basAccess table
 *
 *
 *
 * 
 *
 **/

/**
 * Class to manipulate the basAccess table
 **/
class BusinessBaseBusAccess extends Business
{

    /**
     * Make a connection to the database
     * 
     * @param $module (string): The module whose database we should connect. If null, the actual module database is connected.
     *
     * @return (object): A MIOLO Database connection
     **/
    public function getDatabase($module = null)
    {
        $MIOLO = MIOLO::getInstance();
        $MIOLO->getClass('base','bBaseDeDados');
        $module = is_null($module) ? 'base' : $module;

        return $MIOLO->getDatabase($module);
    }

    /**
     * Do a search on the database table handled by the class
     *
     * @param $filters (object): Search filters
     *
     * @return (array): An array containing the search results
     **/
    public function searchAccess($filters)
    {

        $db  = $this->getDatabase();

        $msql = new MSQL();
        $msql->setTables('basAccess');
        $msql->setColumns('login');
        $msql->setColumns('moduleAccess');
        $msql->setColumns('label');
        $msql->setColumns('image');
        $msql->setColumns('handler');
        $msql->setColumns('count(*)');
        
        if ( strlen($filters->login) > 0 )
        {
            $msql->setWhere(' UPPER(login) = UPPER(?)');
            $args[] = $filters->login;
        }

        $filters->isBookmark = $filters->isBookmark == true ? DB_TRUE : DB_FALSE;

        if ( strlen($filters->isBookmark) > 0 )
        {
            $msql->setWhere(' isBookmark = ? ');
            $args[] = $filters->isBookmark;
        }

        if ( strlen($filters->handler) > 0 )
        {
            $msql->setWhere(' handler ILIKE ? ');
            $args[] = $filters->handler;
        }

        if ( strlen($filters->moduleAccess) > 0 )
        {
            $msql->setWhere(' moduleAccess ILIKE ? ');
            $args[] = $filters->moduleAccess;
        }

        unset($result);
        if ( strlen($filters->moduleAccess) > 0 || strlen($filters->login) > 0 )
        {

             $msql->setWhere(substr($where, 4));
             $msql->setGroupBy('login,
                             moduleAccess,
                             label,
                             image,
                             handler');
             
             $msql->setOrderBy('login,
                             count(*) DESC,
                             moduleAccess,
                             label');
             


             //TODO: inserir na base
            if ( $filters->isBookmark == DB_TRUE )
            {
                $msql->setParameters(bBaseDeDados::obterParametro('BASIC', 'BOOKMARK_LIMIT'));
            }
            else
            {
                $msql->setParameters(bBaseDeDados::obterParametro('BASIC', 'MORE_VISITED_LIMIT'));
            }

            $result = bBaseDeDados::consultar($msql, $args);

        }
        return $result;
    }

    /**
     * Insert a new record
     *
     * @param $data (object): An object of the type handled by the class
     *
     * @return True if succed, otherwise False
     *
     **/
    public function insertAccess($data)
    {

        $db  = $this->getDatabase();

        $sql = 'INSERT INTO basAccess
                            (login,
                             moduleAccess,
                             label,
                             image,
                             handler,
                             isBookmark)
                     VALUES (?,?,?,?,?,?)';

        $data->isBookmark = ($data->isBookmark === true || $data->isBookmark == 't') ? DB_TRUE : DB_FALSE;

        $args = array( 
                       $data->login,
                       $data->moduleAccess,
                       $data->label,
                       $data->image,
                       $data->handler,
                       $data->isBookmark
                     );

        if ( $data->isBookmark == DB_TRUE )
        {
            $res = $this->searchAccess($data);

            if ( count($res) == 0 )
            {
                $result = $db->execute(SAGU::prepare($sql, $args, false));
            }
        }
        else
        {
            $result = $db->execute(SAGU::prepare($sql, $args, false));
        }

        

        return $result;
    }

    /**
     * Delete a record
     *
     * @param $login (string): User login for deletion
     * @param $module (string): User module for deletion
     *
     * @return (boolean): True if succeed, otherwise False
     *
     **/
    public function deleteAccess($login, $moduleAccess=NULL, $isBookmark=false)
    {

        $db  = $this->getDatabase();

        $sql = 'DELETE FROM basAccess
                      WHERE login ILIKE ? ';
        $args[] = $login;

        $isBookmark = ($isBookmark === true || $isBookmark == 't') ? DB_TRUE : DB_FALSE;

        if ( strlen($moduleAccess)>0 )
        {
            $sql .= ' AND moduleAccess ILIKE ?';
            $args[] = $moduleAccess;
        }

        if ( strlen($isBookmark)>0 )
        {
            $sql .= ' AND isBookmark = ?';
            $args[] = $isBookmark;
        }

        $result = $db->execute(SAGU::prepare($sql, $args)); 

        return $result;
    }

}

?>
