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
                    // Se tiver uma permissão mais alta do que a procurada, retorna true.
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
?>
