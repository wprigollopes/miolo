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
