<?php

class frmGenerate extends MForm
{
    public $variables;

    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $MIOLO->uses('classes/dBInfo.php', $module);
        $MIOLO->uses('classes/mArrayField.php', $module);
        $MIOLO->uses('classes/mIOLOGenerate.php', $module);
        $MIOLO->uses('classes/wizindent/wizindent_core.php', $module);
        $MIOLO->page->addScript('generator.js', MIOLO::getCurrentModule());

        parent::__construct(_M('Miolo Generator', $module));

        $this->eventHandler();
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        parent::createFields();

        $fields[] = MMessage::getMessageContainer();
        $fields[] = MPopup::getPopupContainer();

        $dbOptions['manual'] = _M('Manual', $module);
        $dbOptions['miolo'] = _M('Use miolo.php or module.php', $module);
        $fields['databaseOptions'] = new MSelection('databaseOptions', 'manual', _M('Database configuration', $module), NULL);
        $fields['databaseOptions']->options = $dbOptions;
        $fields['databaseOptions']->addEvent('change', ':changeDatabaseOptions');

        $fields[] = new MSeparator();
        $fields[] = new MFormContainer('cDatabase', $this->getDatabaseFields());

        $fields[] = new MSeparator();
        $fields[] = new MLabel(_M('Vars', $module), NULL, true);
        $fields[] = new MTextField('vModule', '%MODULE%', _M('Module', $module));
        $fields[] = new MTextField('author', 'Name [name@solis.coop.br]', _M('Author', $module), 50);

        $fields[] = new MSeparator();
        $fields[] = new MLabel(_M('Templates', $module), NULL, true);

        // Template groups
        $dirs = self::getTemplatesDirectories();
        foreach ( $dirs as $dir )
        {
            $templates = self::getTemplatesFromDirectory($dir);
            if ( count($templates) > 0 )
            {
                $groupFields = array();
                $groupFields["selectAll$dir"] = new MCheckBox("selectAll$dir", NULL, NULL, false, _M('Select all', $module));
                $groupFields["selectAll$dir"]->addAttribute('onclick', "dojo.query('[id=\'aTemplate{$dir}[]\']').forEach(function(input) { input.checked = dojo.byId('selectAll$dir').checked; });");
                foreach ( $templates as $template )
                {
                    $groupFields[] = new MCheckBox("aTemplate{$dir}[]", $dir.'/'.$template, NULL, false, $template);
                }
                $fields[] = new MBaseGroup('groupTemplates', _M($dir), $groupFields, 'horizontal');
            }
        }

        // Templates from root templates dir
        $templates = self::getTemplates();
        if ( count($templates) > 0 )
        {
            $fields['selectAll'] = new MCheckBox('selectAll', NULL, _M('Select all', $module));
            $fields['selectAll']->addAttribute('onclick', "dojo.query('[id=\'aTemplate[]\']').forEach(function(input) { input.checked = dojo.byId('selectAll').checked; });");
            foreach ( $templates as $template )
            {
                $fields[] = new MCheckBox('aTemplate[]', $template, $template);
            }
        }

        $fields[] = new MSeparator();
        $fields[] = new MLabel(_M('Substitute ids', $module), NULL, true);
        $fields[] = new MDiv('divSubstituteIds', _M('No table selected', $module));

        $img = $MIOLO->getUI()->getImage($module, 'generator-16x16.png');
        $generateButton = new MButton('btnGenerate', _M('Generate', $module), ':ajaxBtnGenerateClick', $img);
        $fields['generate'] = new MDiv(NULL, $generateButton);
        $fields['generate']->addStyle('text-align', 'center');

        $otherF[] = new MTextField('otherVarName', NULL, _M('Var', $module), 20);
        $otherF[] = new MTextField('otherVarValue', NULL, _M('Value', $module), 20);

        $fields[] = new MDiv('divOther', NULL);

        $fields[] = new MSeparator();
        $fields[] = new MLabel(_M('Generated code', $module), NULL, true);
        $fields[] = new MDiv('result', _M('No code generated yet', $module));
        $fields[] = $div = new MDiv('fileResponse', NULL);
        $div->addStyle('display', 'none');

        $this->setShowPostButton(false);
        $this->addFields($fields);
    }

    public function getSubstituteIdsFields($tableName, $substituteIds)
    {
        $module = MIOLO::getCurrentModule();

        $controls['columnId'] = new MTextField('columnId', NULL, _M('Column id', $module));
        $controls['columnId']->setReadOnly(true);
        $controls[] = new MTextField('formId', NULL, _M('Form id', $module));
        $fields['substituteIds'] = new MArrayField('substituteIds', $controls);
        $fields['substituteIds']->setData($substituteIds);

        $table['tableName'] = new MTextField('tableName', $tableName, _M('Table name', $module));
        $table['tableName']->setReadOnly(true);

        $className = MIOLOGenerate::getClassName($tableName);
            
        $table[] = new MTextField('className', $className, _M('Class name', $module));
        $fields['tableNameContainer'] = new MContainer('tableNameContainer', $table, 'horizontal', MFormControl::FORM_MODE_SHOW_NBSP);

        return new MContainer(NULL, $fields);
    }

    public function changeDatabaseOptions($args)
    {
        if ( $args->databaseOptions == 'miolo' )
        {
            $this->setResponse($this->getDbConfFields(), 'cDatabase', true);
        }
        else
        {
            $this->setResponse($this->getDatabaseFields(), 'cDatabase', true);
        }
    }

    private function getDatabaseFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $database[] = new MLabel(_M('Database', $module), NULL, true);

        // Get MIOLO supported databases
        $suportedDbs = $MIOLO->getConf('home.classes').'/database';
        foreach( scandir($suportedDbs) as $dir )
        {
            if( (substr($dir,0,1) != '.') and is_dir($suportedDbs.'/'.$dir) )
            {
                $systems[$dir] = $dir;
            }
        }

        // Currently, only postgres is supported by Miolo Generator
        $database['dbSystem'] = new MSelection('dbSystem', 'postgres', _M('System'), $systems);
        $database['dbSystem']->setReadOnly(true);

        $database['dbHost'] = new MTextField('dbHost', NULL, _M('Host'));
        $database['dbPort'] = new MTextField('dbPort', '5432', _M('Port'));
        $database['dbName'] = new MTextField('dbName', NULL, _M('Name'));
        $database['dbUser'] = new MTextField('dbUser', 'postgres', _M('User'));
        $database['dbPassword'] = new MTextField('dbPassword', NULL, _M('Password'));

        $database['dbSystem']->addEvent('change', ':ajaxLoadTables');
        $database['dbHost']->addEvent('change', ':ajaxLoadTables');
        $database['dbPort']->addEvent('change', ':ajaxLoadTables');
        $database['dbName']->addEvent('change', ':ajaxLoadTables');
        $database['dbUser']->addEvent('change', ':ajaxLoadTables');
        $database['dbPassword']->addEvent('change', ':ajaxLoadTables');

        $table['dbSchema'] = new MSelection('dbSchema', NULL, _M('Schema'), NULL);
        $table['dbSchema']->addEvent('change', ':ajaxLoadTables');
        $table['dbTable'] = new MSelection('dbTable', NULL, _M('Table'), NULL);
        $table['dbTable']->addEvent('change', ':loadTableColumns');

        $database[] = new MFormContainer('cTable', $table);

        return $database;
    }

    public function loadTableColumns($args)
    {
        $dbinfo = new DBInfo($args->dbConf, $args->gModule, $args->dbSystem, $args->dbHost, $args->dbPort, $args->dbName, $args->dbUser, $args->dbPassword);
        $dbinfo->setSchema($args->dbSchema);
        $columns = $dbinfo->getColumns($args->dbTable);
        $vars = array();

        foreach ( $columns as $column )
        {
            $vars[] = (object) array(
                'columnId' => $column[0],
                'formId' => MIOLOGenerate::generateLowerCamelCaseName($column[0]),
            );
        }

        $tableName = MIOLOGenerate::getClassName($args->dbTable);
        $substitute = $this->getSubstituteIdsFields($tableName, $vars);
        $this->setResponse($substitute, 'divSubstituteIds');
    }

    private function getDbConfFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        // Database conf
        $dbConf[] = new MLabel(_M('Database from configuration file', $module), NULL, true);
        $confFile = $MIOLO->getConf('home.miolo') . "/etc/miolo.php";
        $confData = require $confFile;

        // get databases configuration from miolo.php
        if ( isset($confData['db']) && is_array($confData['db']) )
        {
            foreach ( $confData['db'] as $db => $object )
            {
                $dbs[$db] = $db;
            }
        }

        $dbConf['dbConf'] = new MSelection('dbConf', NULL, _M('DB conf', $module), $dbs);
        $dbConf['dbConf']->addEvent('change', ":ajaxLoadTables");

        $dbConf['gModule'] = new MTextField('gModule', NULL, _M('Module'));
        $dbConf['gModule']->addEvent('change', ":ajaxLoadTables");

        $tables[] = new MSelection('dbTable', NULL, _M('Table', $module), array());
        $dbConf[] = new MFormContainer('cTable', $tables);

        return $dbConf;
    }

    public static function getTemplates()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $templates = array();

        $path = $MIOLO->getConf('home.modules') . '/' . $module . '/templates/';

        if ( is_dir($path) )
        {
            foreach ( scandir($path) as $arqs )
            {
                if ( (substr($arqs, 0, 1) != '.') and !is_dir($path . $arqs) )
                {
                    $templates[$arqs] = $arqs;
                }
            }
        }

        return $templates;
    }

    public static function getTemplatesFromDirectory($dir)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $templates = array();

        $path = $MIOLO->getConf('home.modules') . "/$module/templates/$dir/";

        if ( is_dir($path) )
        {
            foreach ( scandir($path) as $arqs )
            {
                if ( (substr($arqs, 0, 1) != '.') and !is_dir($path . $arqs) )
                {
                    $templates[$arqs] = $arqs;
                }
            }
        }

        return $templates;
    }

    public static function getTemplatesDirectories()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $dir = array();

        $path = $MIOLO->getConf('home.modules') . "/$module/templates/";

        if ( is_dir($path) )
        {
            foreach ( scandir($path) as $arqs )
            {
                if ( (substr($arqs, 0, 1) != '.') and is_dir($path . $arqs) )
                {
                    $dir[] = $arqs;
                }
            }
        }

        return $dir;
    }

    public function ajaxLoadTables($args)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $gModule = $this->getFormValue('gModule');
        $dbConf = $this->getFormValue('dbConf');

        if ( $dbConf )
        {
            if ( !$MIOLO->getConf('db.' . $dbConf . '.host') )
            {
                new MMessageWarning(_M('There is no "@1" configuration', $module, $dbConf));
                $this->setResponse(NULL, 'divResponse');
                return;
            }
        }
        else
        {
            $error = $this->validateManualDBConf($args, false);
            if ( $error != '' )
            {
                $this->setResponse(NULL, 'divResponse');
                return;
            }
        }

        try
        {
            $dbinfo = new DBInfo($dbConf, $gModule, $args->dbSystem, $args->dbHost, $args->dbPort, $args->dbName, $args->dbUser, $args->dbPassword);
            $dbinfo->setSchema($args->dbSchema);

            $schemas = $dbinfo->getSchemas();
            if ( is_array($schemas) )
            {
                foreach ( $schemas as $schema )
                {
                    $schemaOptions[$schema[0]] = $schema[0];
                }
                $this->dbSchema->setOptions($schemaOptions);
            }

            $tables = $dbinfo->getTables();

            if ( is_array($tables) )
            {
                foreach ( $tables as $table )
                {
                    $options[$table[0]] = $table[0];
                }

                $this->dbTable->setOptions($options);
            }
            else
            {
                new MMessageWarning(_M('No tables on database', $module));
            }
        }
        catch ( EDatabaseException $e )
        {
            new MMessageError(_M('Invalid database data', $module));
        }

        $this->setResponse(array($this->dbSchema, $this->dbTable), 'cTable', true);
    }

    public function validateManualDBConf($args, $validateTable=true)
    {
        $error = '';
        $validate = '';
        $validate .= $args->dbSystem != '' ? '' : _M('System') . ', ';
        $validate .= $args->dbHost != '' ? '' : _M('Host') . ', ';
        $validate .= $args->dbName != '' ? '' : _M('Name') . ', ';
        if ( $validateTable )
        {
            $validate .= $args->dbName != '' ? '' : _M('Table') . ', ';
        }

        if ( $validate != '' )
        {
            $validate = substr($validate, 0, -2);
            if ( count(explode(',', $validate)) > 1 )
            {
                $error = _M('The folowing fields must be informed: @1', MIOLO::getCurrentModule(), $validate);
            }
            else
            {
                $error = _M('The field "@1" must be informed', MIOLO::getCurrentModule(), $validate);
            }
        }
        return $error;
    }

    public function ajaxBtnGenerateClick($args)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        if ( $args->databaseOptions == 'miolo' )
        {
            $valids[] = new MRequiredValidator('dbConf');
            $valids[] = new MRequiredValidator('gModule');
            $valids[] = new MRequiredValidator('dbTable');
            $this->setValidators($valids);

            if ( !$this->validate() )
            {
                return;
            }

            $dbConf = $this->getFormValue('dbConf');
            $schema = $this->getFormValue('dbSchema');
            $table = $this->getFormValue('dbTable');

            $generate = new MIOLOGenerate($dbConf, $module, $schema, $table);
            $modulePath = $MIOLO->getConf('home.modules') . '/' . strtolower($module) . '/';
        }
        else
        {
            $error = $this->validateManualDBConf($args);
            if ( $error )
            {
                new MMessageWarning($error);
                return;
            }

            $generate = new MIOLOGenerate(NULL, NULL, $args->dbSchema, $args->dbTable, $args->dbSystem, $args->dbHost, $args->dbPort, $args->dbName, $args->dbUser, $args->dbPassword);
        }

        // vars
        $generate->vModule = $this->getFormValue('vModule');
        $generate->author = $this->getFormValue('author');

        $generate->setSubstituteIds($args->columnId, $args->formId);
        if ( $args->className )
        {
            $generate->setClassName($args->className);
        }

        $templates = $this->getFormValue('aTemplate');
        $dirs = self::getTemplatesDirectories();

        foreach ( $dirs as $dir )
        {
            $templatesFromDir = $this->getFormValue("aTemplate$dir");
            $templates = array_merge((array)$templates, (array)$templatesFromDir);
        }

        if ( count($templates) > 0 )
        {
            $codeTab = new MTabbedBaseGroup('codeTab', NULL, false);

            foreach ( $templates as $template )
            {
                // do nothing if template is not defined
                if ( $template == '' )
                {
                    continue;
                }

                $i++;
                $template = $template;

                if ( file_exists($MIOLO->getConf('home.modules') . '/' . strtolower($module) . '/templates/' . $template) )
                {
                    $path = $MIOLO->getConf('home.modules') . '/' . strtolower($module) . '/templates/' . $template;
                }
                elseif ( file_exists($MIOLO->getConf('home.modules') . '/' . strtolower($module) . '/templates/' . $template) )
                {
                    $path = $MIOLO->getConf('home.modules') . '/' . strtolower($module) . '/templates/' . $template;
                }
                else
                {
                    $msg = _M('Template not found.', MIOLO::getCurrentModule());

                    new MMessageWarning($msg);
                }


                $generate->setTemplate($path);
                $class = $generate->generateClass();

                if ( strcasecmp(substr($template, strrpos($template, '.') - 3, 3), 'sql') != 0 )
                {
                    $wiz = new wizindent();
                    list($formatted_source, $frequency, $definitions) = $wiz->wizindent_indent_php('<?php' . $class . '?>');
                    $class = $formatted_source;
                }

                try
                {
                    $fileName = rand() . end(explode('/', $template));
                    $this->manager->getSession()->setValue("filename$i", $fileName);
                    file_put_contents("/tmp/MIOLO_$fileName", $class);
                }
                catch ( Exception $e )
                {
                    $msg = _M('Error: could not create temp file. ', MIOLO::getCurrentModule()) . $e->getMessage();

                    new MMessageWarning($msg);
                }

                $selectCode = new MButton('btnSelectCode' . $i, _M('Select code', $module), "generator.selectCode('#resultHighLight$i');");
                $downloadBtn = new MButton('btnDownload' . $i, _M('Download'), ":ajaxDownloadFile;$i");
                $downloadContainer = new MDiv(null, array( $selectCode, $downloadBtn ));

                $divHL = new MDiv('resultHighLight' . $i, highlight_string($class, true));
                $divHL->setHeight(300);
                $divHL->addStyle('overflow', 'auto');
                $divHL->addStyle('border', '1px solid silver');
                $divHL->addStyle('padding', '10px');

                $flds = array();
                $flds[] = $downloadContainer;
                $flds[] = $divHL;

                $codeTab->createTab('resultHighLight' . $i, $template, $flds);
            }
        }
        else
        {
            $msg = _M('No template selected.', MIOLO::getCurrentModule());

            new MMessageWarning($msg);
        }

        $this->manager->ajax->setResponseControls($codeTab, 'result');
    }

    public function ajaxDownloadFile($param)
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $fileName = $this->manager->getSession()->getValue("filename$param");

        if ( file_exists("/tmp/MIOLO_$fileName") )
        {
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/files/download.php?file=$fileName";
            $this->setResponse("<iframe src='$url'></iframe>", 'fileResponse');
        }
        else
        {
            $msg = _M('Temporary file not found!', MIOLO::getCurrentModule());
            new MMessageWarning($msg);
        }
    }
}
