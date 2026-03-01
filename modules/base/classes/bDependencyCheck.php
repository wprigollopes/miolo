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
 * read the "LICENCA.txt" file included with this software. e
 * 
 * 
 *
 *
 * 
 *
 **/
abstract class BDependencyCheck
{
    private $message;

    /**
     * 
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = new BString( $message );
    }

    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Adds a message with a <br> separator for better display
     *
     * @param string $msg 
     */
    public function addMessage($msg)
    {
        if ( $this->hasMessage() )
        {
            $msg = '<br>' . $msg;
        }
        
        $this->setMessage( $this->getMessage() . $msg );
    }
    
    /**
     * Returns whether any message exists
     *
     * @return string
     */
    public function hasMessage()
    {
        return strlen($this->getMessage()) > 0;
    }
    

    public function listDependency()
    {
        $module = MIOLO::getCurrentModule();

        //Gnuteca
        $list[] = array('gdInstalled', _M('Gd (Biblioteca de imagens)', $module));
        $list[] = array('zipExtension', _M('Suporte a arquivos zip', $module));
        $list[] = array('apacheVersion', _M('Versão do Apache', $module));
        $list[] = array('phpVersion', _M('Versão do PHP', $module));
        $list[] = array('postgresVersion', _M('Versão do PostgreSQL', $module));
        $list[] = array('ldapInstalled', _M('LDAP instalada', $module));
        $list[] = array('filePermission', _M('Permissão padrão dos arquivos', $module));
        $list[] = array('logPermission', _M('Permissão de escrita no Log', $module));
        $list[] = array('emailConfigured', _M('E-mail está configurado e enviando', $module));
        $list[] = array('registerGlobals', _M('Register globals - segurança', $module));
        $list[] = array('magicQuotesGpc', _M('Magic quotes - segurança', $module));
        $list[] = array('magicQuotesSybase', _M('Magic quotes sybase', $module));
        $list[] = array('sessionGcMaxLifeTime', _M('Session - garbage max life time', $module));
        $list[] = array('mioloTimeOut', _M('Configuração do Miolo - session.timeout', $module));
        $list[] = array('dblinkCheck', _M('Checagem dblink', $module));
        
        //converts to BString
        foreach ( $list as $key => $val )
        {
            $list[$key][1] = new BString($val[1]);
        }
        
        return $list;
    }

    public function getDependencyLabel($dependency)
    {
        $list = $this->listDependency();

        if ( is_array($list))
        {
            foreach ( $list as $line => $info )
            {
                if ( $info[0] == $dependency)
                {
                    return $info[1];
                }
            }
        }
    }

    public function gdInstalled()
    {
        $this->setMessage( GD_VERSION );

        return extension_loaded ('gd') && function_exists ('gd_info');
    }

    public function logPermission()
    {
        $MIOLO      = MIOLO::getInstance();
        $logPath    = $MIOLO->getConf('home.logs');

        $this->setMessage($logPath);

        return is_writable($logPath);
    }

    public function ldapInstalled()
    {
        return (function_exists('ldap_connect'));
    }

    public function apacheVersion()
    {
        $version = apache_get_version(); #Apache/2.2.14 (Ubuntu)

        $version = explode('/', $version);
        $version = $version[1]; //2.2.14 (Ubuntu)
        
        if ( ! $version )
        {
            $version = explode('.', $version);
            $this->setMessage( 'Versão do apache não disponível, presumindo que é superior a 2.' );

            return true;
        }
        else
        {
            $this->setMessage('Versão do apache= '.apache_get_version() . ' - ' . new BString(_M('2 ou maior', 'base')));
            return $version[0] >=2;
        }
    }

    public function phpVersion()
    {
        $version = phpversion();
        $version = explode('.', $version);

        $this->setMessage( phpversion(). ' - ' .new BString(_M('5.2 ou maior que não seja a 5.3.2', 'base')));

        //Check the micro-version
        $microVersion = explode('-',$version[2]);
        //Check if PHP version is 5.3.2; if so the test must fail
        //because this version has difficult-to-fix issues
        $goodVersion = !( $version[0] == 5 && $version[1] == 3 && $microVersion[0] == 2 );
        
        //If it is greater than 5.2 and is not 5.3.2, it is OK.
        return ($version[0] >= 5 && $version[1] >= 2 && $goodVersion);
    }

    public function postgresVersion()
    {
        $version = pg_version();

        $server = explode('.', $version['server']);
        $client = explode('.', $version['client']);

        $aviso = new BString(_M('Cliente @1 e servidor @2.', 'base', $version['client'], $version['server']));


        $result = ( ( $client[0] . $client[1] ) >= 83 && ( $server[0] . $server[1] ) >= 83 );

        if ( !$result )
        {
            $this->setMessage( $aviso . ' ' .  new BString(_M('Versão deve ser maior que 8.3', 'base') ));
        }
        else
        {
            $this->setMessage( $aviso );
        }

        return $result;
    }

    public function registerGlobals()
    {
        $globals = ini_get('register_globals');

        if ( $globals )
        {
            $this->setMessage(new BString(_M("Register globals estão ativados em php.ini, isto é um problema de segurança. ", 'base')));
        }

        return !$globals;
    }

    public function magicQuotesGpc()
    {
        // magic_quotes_gpc removed in PHP 5.4; always returns true for compatibility
        return true;
    }

    public function magicQuotesSybase()
    {
		$magicQuotesSybase = strtolower(ini_get('magic_quotes_sybase'));

		//To show the correct image, it is necessary to invert the variable value
		$magicQuotesSybase = $magicQuotesSybase ? false : true;

        if ( !$magicQuotesSybase )
        {
            $this->setMessage(new BString(_M("Magic quotes sybase não pode estar ativa.", 'base')));
        }

        return $magicQuotesSybase;
    }
    
    public function sessionGcMaxLifeTime()
    {
        $gc_maxlifetime = strtolower(ini_get('session.gc_maxlifetime'));

        //To show the correct image, it is necessary to invert the variable value
        $gc_maxlifetime = ($gc_maxlifetime >= 18000);

        if ( !$gc_maxlifetime )
        {
            $this->setMessage(new BString(_M("Sugere-se que o valor de session.gc_maxlifetime seja maior ou igual a 18000 (5 horas).", 'base')));
        }

        return $gc_maxlifetime;
    }
    
    public function mioloTimeOut()
    {
        $MIOLO = MIOLO::getInstance();
        $mTimeOut = strtolower($MIOLO->getConf('session.timeout'));

        //To show the correct image, it is necessary to invert the variable value
        $mTimeOut = ($mTimeOut >= 300);

        if ( !$mTimeOut )
        {
            $this->setMessage(new BString(_M("Sugere-se que o valor de session.timeout do miolo seja maior ou igual a 300 (5 horas).", 'base')));
        }

        return $mTimeOut;
    }

    public function zipExtension()
    {
        $ok = class_exists('ZipArchive');

        if ( !$ok )
        {
            $this->setMessage( new BString(_M("É necessário adicionar a extensão Zip (Classe ZipArchive) ao PHP")));
            return false;
        }

        return $ok;
    }
    
    public function dblinkCheck()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        
        $database = $MIOLO->getDatabase($module);
        
        //Query to the postgres catalog
        $sql = new MSQL();
        $sql->setTables('pg_catalog.pg_proc');
        $sql->setColumns('*');
        $sql->setWhere("proname = 'dblink'");
        $sql = $sql->select();
        
        $result = $database->Query($sql);
        
        return count($result) > 0;
    }

    public abstract function filePermission();
    public abstract function emailConfigured();
}
