<?php
class MAuthMIOLO extends MAuth
{
    public $login;  // objeto Login
    public $iduser; // iduser do usuario corrente
    public $module; // authentication module;
    public $conn; //the ldap connection

    public function __construct()
    {
        parent::__construct();
    }

    public function authenticate($user, $pass, $log=true)
    {
        $MIOLO  = $this->manager;
        $db = $MIOLO->getDatabase('admin');
        $result = $db->queryParams('SELECT name, groups, password FROM cmn_users WHERE login = ?', [$user]);

        if (sizeof($result) > 0 && password_verify($pass, $result[0][2])) {
            $groups = array();
            foreach(explode(',',$result[0][1]) as $group)
            {
                $groups[] = $group;
            }

            if($log)
            {
                $login = new MLogin($user,
                                    $pass,
                                    $result[0][0],
                                    0);
                $login->setGroups($groups);
                $this->setLogin($login);
            }
            return true;
        }
        return false;
    }
}
