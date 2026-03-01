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
 * Class Miolo Permissions
 *
 *
 *
 * 
 *
 **/

class BPermsBase extends MPerms
{
    private $auth;
    public  $perms;
    
    public function __construct()
    {
        parent::__construct();

        $this->auth  = $this->manager->GetAuth();
        
        $this->perms = array
            (
            A_ACCESS  => "SELECT",
            A_INSERT  => "INSERT",
            A_DELETE  => "DELETE",
            A_UPDATE  => "UPDATE",
            A_EXECUTE => "EXECUTE",
            A_ADMIN   => "SYSTEM"
            );

    }

    public function converterFuncaoDaBaseParaAccess($baseFuncao)
    {
        $baseFuncao = strlen($baseFuncao) > 0 ? $baseFuncao : FUNCAO_BUSCAR;
        
        $lista = array();
        $lista[FUNCAO_BUSCAR]  = A_ACCESS;
        $lista[FUNCAO_EDITAR]  = A_UPDATE;
        $lista[FUNCAO_INSERIR] = A_INSERT;
        $lista[FUNCAO_REMOVER] = A_DELETE;
        $lista[FUNCAO_EXPLORAR] = A_EXECUTE;
        return $lista[$baseFuncao];
    }
    
    
    public function checkAccess($transaction, $rights, $deny = false, $group = false)
    {
        $transaction = strtolower($transaction);
        
        if ( $this->auth->isLogged() )
        {            
            $login   = $this->auth->getLogin(); 
            $isAdmin = $login->isAdmin(); 
           // $rights  = $login->rights;
            
            if( $rights )
            {
                $listRights = $this->getRights($login->id);
                $listRights = array_change_key_case($listRights, CASE_LOWER); // Indifere se esta camelCase ou nao. Estava causando problemas.
                
                $login->setRights( $listRights );
            }
            
            if (is_array($listRights[$transaction]))
            {
                $check = in_array($rights, $listRights[$transaction]);
                if ( !$check )
                {
                    // If there is a higher permission than the one being checked, returns true.
                    foreach ( $listRights[$transaction] as $right )
                    {
                        if ( $right > $rights )
                        {
                            $check = true;
                        }
                    }
                }
            }    
        }

        if ( !$check && $deny )
        {
            $msg = _M('Access Denied') . "<br><br>\n" .
                      '<center><big><i><font color=red>' . _M('Transaction: ') . "$transaction\n<br /><br />" .
                   _M('Please inform a valid login/password to access this content.') . "<br>";
           
            $go = $this->manager->getActionURL($this->manager->getConf('options.startup'), 'main' );
            
            $caption = _M('Access Denied');
            $error = Prompt::Error($msg, $go, $caption, '');
            $this->manager->Prompt($error,$deny);
        }
//                                var_dump($check);

        return $check;
    }

    public function getRights($login)
    {
        $this->manager->loadMADConf();
        $db = $this->manager->getDatabase('base');
             
        if ( $this->manager->getSession()->getValue('miolo26_rights') )
        {
            return $this->manager->login->rights;
        }

        $sql = "SELECT DISTINCT T.m_transaction,
                       A.rights
                  FROM miolo_user U, 
                       miolo_groupuser G, 
                       miolo_access A,
                       miolo_transaction T 
                 WHERE U.iduser = G.iduser 
                   AND G.idgroup = A.idgroup 
                   AND U.login = ?
                   AND T.idtransaction = A.idtransaction";
        
        $params = array();
        $params[] = $login;
        $result = $db->query($db->prepare($sql, $params));
        $rows = is_array($result) ? $result : $result->result;
        $rights = array();
        
        foreach((array)$rows as $r)
        {
            $rights[ $r[0] ][] = $r[1];
        }
        
        $this->manager->getSession()->setValue('miolo26_rights', TRUE);
        
        return $rights;        
    }

    public function getTransactionName($transaction)
    {
        $this->manager->loadMADConf();
        $db = $this->manager->getDatabase('base');
        
        $sql = "SELECT A.nametransaction
                  FROM miolo_transaction A
                 WHERE LOWER(A.m_transaction) = ?";
        
        $params = array();
        $params = strtolower($transaction);

        $result = $db->query($db->prepare($sql, $params));
        return is_array($result) ? $result[0][0] : $result->result[0][0];
    }
    
    public function hasTransaction($transaction)
    {
        $transaction = $this->getTransactionName($transaction);
        return strlen($transaction)>0;
    }
}
