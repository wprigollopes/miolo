<?php
class frmConf extends MForm
{
    public $conf, $isModule;

    /**
     * form constructor
     */
    public function __construct()
    {   
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();

        /** 
         * get module to be configured from _REQUEST and set path
         */
        $confModule = MIOLO::_REQUEST('confModule');

        if( $confModule and $confModule != 'GLOBAL' )
        {
            $this->isModule = true;
            $this->conf     = $MIOLO->getConf('home.modules').'/'.$confModule.'/etc/module.php';
            $bkpFileName    = 'module.php';
        }
        else
        {
            $this->conf     = $MIOLO->getConf('home.etc').'/miolo.php';
            $this->isModule = false;
            $bkpFileName    = 'miolo.php';
        }

        /* backup the conf file */
        $time   = date('Ymd-Hi');
        $bkpDir = MUtil::getSystemTempDir();
        $bkpFileName = $bkpDir . '/'. $bkpFileName .'-bkp_'.$time;

        if( ! @copy($this->conf, $bkpFileName) )
        {
            $MIOLO->information(_M("Backup failed. It wasn't possible to create a backup file [@1] of your current configuration!", 'admin', $bkpFileName), null, null, false);
        }
        else
        {
            $MIOLO->getTheme()->insertContent( MPrompt::information( _M("Backup file [@1] created.",'admin', $bkpFileName), null ) );
        }

        /* permission test */
        if( ! is_writable($this->conf) )
        {
            $this->addError( _M("READ-ONLY MODE!<br/>You don't have permission to write the configuration file:<br/> [@1]", 'admin', $this->conf) );
        }

        parent::__construct( _M('Configuration', $module) );

        $this->eventHandler();
    }
    
    /**
     * method to create form fields
     */
    public function createFields()
    {  
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();
        
        $db = $MIOLO->getBusiness($module, 'module');
        $rsModules = $db->listAll()->result;
        $modules['miolo'] = 'GLOBAL';
        if( ! empty($rsModules) )
        {
            foreach($rsModules as $rsModule)
            {
                $modules[$rsModule[0]] = $rsModule[0];
            }
        }
        $confModule = 'miolo';
        if( MIOLO::_REQUEST('confModule') )
        {
            $confModule = MIOLO::_REQUEST('confModule');
        }
        $fields[0] = new MSelection('confModule', $confModule, _M('Configuration', $module), $modules);
        $url = $MIOLO->getActionUrl($module, $action);
        
        $fields[1] = new MTabbedBaseGroup('configBsg');
        
        $fields[1]->createTab('paths', _M('Paths'), array($this->manager->getUi()->getForm('admin', 'frmConfPaths', $this->conf)));
        $fields[1]->createTab('theme', _M('Theme'), array($this->manager->getUi()->getForm('admin', 'frmConfTheme', $this->conf)));
        $fields[1]->createTab('options', _M('Options'), array($this->manager->getUi()->getForm('admin', 'frmConfOptions', $this->conf)));
        $fields[1]->createTab('admin', _M('Administration'), array($this->manager->getUi()->getForm('admin', 'frmConfAdmin', $this->conf)));
        $fields[1]->createTab('db', _M('DB'), array($this->manager->getUi()->getForm('admin', 'frmConfDb', $this->conf)));
        
        $this->setShowPostButton(false);
        $this->setFields($fields);

        $button = new MButton('btnSave', _M('Save',$module), "javascript:save();");
        $this->addField($button); 

    }
    
    /**
     * Save form data at the conf file
     * @returns (boolean) true if success
     */
    public function saveTab($formValues)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        /* get tab and remove from array */
        $thisTab    = $formValues[0];
        $formValues = $formValues[1];

        /* manipulate form values */
        $formValues = explode('&', $formValues);
        $data = new stdClass();
        foreach( $formValues as $value )
        {
            $aux = explode('=', $value);
            $data->$aux[0] = urldecode($aux[1]);
        }
        /* it's data ok? */
        if( !$data->version )
        {
            echo 'false';
            return;
        }
        /* get form */
        $form = $MIOLO->getUi()->getForm($module, 'frmConf'.$thisTab, $this->conf);
        /* set data */
        $form->setData($data);

        /* get old conf, set new values, generate xml and save the the file */
        $confArray    = $this->getConfArray($this->conf);
        $newConfArray = $form->setConfArray($confArray);
//        $xml = $this->generateXml($newConfArray);
        $php = $MIOLO->conf->generateConfigPHP($newConfArray);
        echo file_put_contents($this->conf, $php);
    }

    public function getTab($tab)
    {
        global $theme;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $ui = $MIOLO->getUi();
        $tab = $tab[0];
        $form = $ui->getForm($module, 'frmConf'.$tab, $this->conf);
        echo $form->generate();
    }

    /**
     * Get array with conf values
     * @params conf file path
     * @returns (array) with the conf data
     */
    public function getConfArray($conf)
    {
        $data = require $conf;
        $confArray = [];

        if (!is_array($data)) {
            return $confArray;
        }

        $this->flattenForAdmin($data, '', $confArray);
        return $confArray;
    }

    private function flattenForAdmin(array $array, string $prefix, array &$result): void
    {
        foreach ($array as $key => $value) {
            $flatKey = ($prefix !== '') ? $prefix . '.' . $key : $key;
            if (is_array($value) && !array_is_list($value)) {
                $this->flattenForAdmin($value, $flatKey, $result);
            } else {
                $result[$flatKey] = $value;
            }
        }
    }

}
