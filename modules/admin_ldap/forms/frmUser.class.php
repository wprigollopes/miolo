<?
class frmUser extends MForm
{
	public $home;
    public $objGrupo;
    public $objUser;
    public $objModule;
    public $schema;

	function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();

        $this->home = $MIOLO->getActionURL($module,$action);
        $this->objGrupo = $MIOLO->getBusiness($module,'group');
        $this->objModule= $MIOLO->getBusiness($module,'module');
        $this->objUser  = $MIOLO->getBusiness($module,'user');
        $this->schema = $this->objUser->schema;
		parent::__construct('Usuários');
        $this->setWidth('65%');
        $this->setIcon($MIOLO->getUI()->getImage('admin','user1.png'));
        $this->setClose($MIOLO->getActionURL('admin','main'));
	    $this->eventHandler();
	}

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();

        $grupos     = $this->objGrupo->listAll(false);
        $mtFieldGrp = array(array('mtgrupo' ,'Grupos','',$grupos));
        $modulos    = $this->objModule->listAll(false);
        $permissoes = $this->objModule->listAllAccess(false);
        $mtFieldAcs = array(array('mtmodule','Módulo','',$modulos), array('mtaccess','Permissão','',$permissoes));
        
        $fields = array(
            new MHiddenField('key',''),
            new MTextField('txtLogin','','Login',20),
            new MTextField('edtNome','','Nome',40),
            new MTextField('codAluno','','Código de aluno',10),
            new MTextField('edtEmail','','Email',40),
            new MPasswordField('edtPassword','','Senha',20),
            new MMultiTextField2('mtfGrupos',NULL,'Grupos',$mtFieldGrp,300,true,'vertical'),
            new MMultiTextField2('mtfAccess',NULL,'Permissões',$mtFieldAcs,300,true,'vertical'),
        );
        $this->setFields($fields);

        $this->setFieldsVisible(false);

        $buttons = array(
            new MButton('btnEdit',   'Editar'),
            //new MButton('btnNew',    'Incluir'),
            //new MButton('btnDelete', 'Excluir'),
            new MButton('btnList',   'Relação')
        );
        $this->setButtons($buttons);
    }

    public function setFieldsVisible($value)
    {
	   $this->setFieldAttr('txtIdUser','visible',$value);
	   $this->setFieldAttr('edtPassword','visible',$value);
	   $this->setFieldAttr('hidIdPessoa','visible',$value);
	   $this->setFieldAttr('edtEmail','visible',$value);
	   $this->setFieldAttr('edtNick','visible',$value);
	   $this->setFieldAttr('mtfGrupos','visible',$value);
	   $this->setFieldAttr('mtfAccess','visible',$value);

       if($this->schema == 'system')
       {
           $this->setFieldAttr('edtEmail','readonly',true);
           $this->setFieldAttr('edtPassword','visible',false);
       }
    }

/*
    GetData: obtém os valores fornecidos no formulario e cria um objeto FormData
             cujos attributos têm o mesmo nome dos atributos do objeto que vai receber os valores.
    A implementação default do GetData cria um objeto FormData cujos atributos têm
    o mesmo nome dos campos do formulário.   
*/
	function getData()
	{
        $data = new FormData();
		$data->idUser   = $this->getFieldValue('key');
		$data->login    = $this->getFieldValue('txtLogin');
		$data->password = $this->getFieldValue('edtPassword');
		$data->grupos   = $this->getFieldValue('mtfGrupos');
        $data->nome     = $this->getFieldValue('edtNome');
        $data->codAluno = $this->getFieldValue('codAluno');
        $data->email    = $this->getFieldValue('edtEmail');
        return $data;
	}

/*
    SetData: obtém os valores fornecidos através do parâmetro $data (geralmente um
             objeto de negócio) e preenche os campos do formulário.
    A implementação default do SetData assume que os atributos do objeto $data têm
    o mesmo nome dos campos do formulário.   
*/
	function setData($data)
	{
		$this->setFieldValue('key'        , $data->idUser);
		$this->setFieldValue('txtIdUser'  , $data->idUser);
		$this->setFieldValue('txtLogin'   , $data->login);
		$this->setFieldValue('edtPassword', $data->password);
		$this->setFieldValue('edtNome'    , $data->nome);
		$this->setFieldValue('codAluno'   , $data->codAluno);
		$this->setFieldValue('edtEmail'   , $data->email);
        // $data->grupos is an array of objects; GetAttribute traverses this array and gets an array with
        // the IdGrupo attribute
        $grupos = $data->groups; //$this->getAttribute($data->groups,'idGroup');//,'group'));
        $this->getField('mtfGrupos')->setCodeValue($grupos);

        $rights     = $this->objUser->getRights($data->login);

        $r = array();
        foreach($rights as $module=>$rights)
        {
            foreach($rights as $right)
                $r[] = array($module, $right);
        }
        $this->getField('mtfAccess')->setCodeValue($r);
	}

	function btnPost_click()
	{
		$MIOLO = MIOLO::getInstance();

        $key = $this->getFieldValue('key');  // insert or edit?
        $objUser = $this->objUser;     // just a shortcut
        if ($key != '')
        {
            $objUser->getById($key); // if editing, get the current data of the object
        }
        // set the object attributes with the form field values
		$objUser->setData($this->getData());
        // groups must be handled separately, as they must generate an array of objects
        $grupos = $this->getField('mtfGrupos')->getCodeValue();
        foreach($grupos as $g)
        {
            $data->grupos[] = $g[0]; // get the idGrupo
        }
		$objUser->setArrayGroups($data->grupos);
        $objUser->setArrayRights($this->getField('mtfAccess')->getCodeValue());

        try
        {
            $objUser->save();
            $MIOLO->information('Usuário atualizado com sucesso.', $this->home );
        }
        catch (EMioloException $e)
        {
            $this->addError($e->getMessage());
	    }
	}

	function btnList_click()
	{   
		$MIOLO = MIOLO::getInstance();
		$module = MIOLO::getCurrentModule();
		$action = MIOLO::getCurrentAction();

        // clear the form
        //$this->clearFields();
        $this->clearButtons();
        $this->defaultButton = false;

        // define the field for filtering
        $fields = array(
            array(
               new MTextField('txtLogin','','Login',25),
               new MTextField('edtNome','','Nome',40),
               new MTextField('codAluno','','Código de aluno',10),
               new MButton('btnList','Relação')
            )
        );
        $this->setFields($fields);

        // DataGrid columns
        $columns = array(
           new MDataGridColumn('iduser','Id','right', true, '10%',true),
           new MDataGridColumn('login','Login','left', true, '20%',true, NULL, true,true),
           new MDataGridColumn('nome','Nome','left',true, '70%',true, NULL, true,true),
        );

        // reference link for the grid
		$hrefDatagrid = $MIOLO->getActionURL($module,$action,'', Array('event'=>'btnList_click'));

        // value defined as filter
        $login = MUtil::NVL($this->getFieldValue('txtLogin'), '');

        // execute the query
        $name  = $this->edtNome->value;
        $cod   = $this->codAluno->value;
        $query = $this->objUser->listByLogin($login, $name, $cod);

        // instantiate the datagrid
        $datagrid = new MGrid($query, $columns, $hrefDatagrid, 20);
        $datagrid->setTitle('Relação de Usuários');
        $datagrid->setClose($MIOLO->getActionURL($module,$action));

 	    $href_edit = $MIOLO->getActionURL($module,$action,'%0%',Array('event'=>'btnEdit:click'));
	    //$href_dele = $MIOLO->getActionURL($module,$action,'%0%',Array('event'=>'btnDelete:click'));
        $datagrid->addActionUpdate($href_edit);
	    //$datagrid->addActionDelete($href_dele);
        // place the datagrid in the form
		$this->addField($datagrid);
	}

	function btnEdit_click($sender, $key='')
	{
        global $item;
        $this->setFieldAttr('edtNome','readonly',true);
        $this->setFieldAttr('codAluno','readonly',true);

        $login = $this->getFieldValue('txtLogin');
        if(!$login) $login = $this->getFieldValue('codAluno');
        // check if it is being executed through the grid event
        if($item)
        {
            $this->objUser->getById($item);
        }		
        else
        {
            $this->objUser->getByLogin($login);
        }

        if ($this->objUser->idUser)
        {
            // place the object data into the form fields
            $this->setData($this->objUser);
            // display the fields
            $this->setFieldsVisible(true);
            $this->setFieldAttr('txtLogin','readonly',true);
            $this->setFieldValue('key',$this->objUser->idUser);
            $this->addButton(new MButton('btnCancel', 'Cancelar', $this->home));
            $this->setButtonAttr('btnEdit','name','btnPost');
            $this->setButtonAttr('btnPost','label','Gravar');
            $this->setButtonAttr('btnNew','visible', false);
            //$this->addValidator(new RequiredValidator('edtPassword'));
        }
        else
        {
            $this->addError("Usuário [$login] não encontrado!");
        }
	}

	function btnCancel_click()
	{   
        $this->setFieldValue('txtLogin','');
    }

	function btnNew_click($sender)
	{   
        $data = new FormData();
        $data->login = $this->getFieldValue('txtLogin');
        if ($data->login != '')
        {
            $this->setData($data);
            $this->setFieldsVisible(true);
            $this->setFieldAttr('txtIdUser','visible',false);
            $this->setFieldAttr('txtLogin','readonly',true);
            $this->addButton(new MButton('btnCancel', 'Cancelar', $this->home));
            $this->setButtonAttr('btnEdit','name','btnPost');
            $this->setButtonAttr('btnPost','label','Gravar');
            $this->setButtonAttr('btnNew','visible', false);
            $this->addValidator(new RequiredValidator('edtPassword'));
            $this->addValidator(new RequiredValidator('lkpNome'));
        }
        else
        {
        //    $this->addError("Please enter the login for the new user!");
        }
	}

	function btnDelete_click($sender, $key='')
	{   
		global $item, $self;
		$MIOLO = MIOLO::getInstance();
		$module = MIOLO::getCurrentModule();
		$action = MIOLO::getCurrentAction();
		$url = MIOLO::getCurrentURL();

        $objUser = $this->objUser;
        $login = $this->getFieldValue('txtLogin');
        // check if it is being executed through the grid event
        $item = ($key != '') ? $key : $item;
        if ($item != '')
        {
            $objUser->getById($item);
        }
        else
        {
            $objUser->getByLogin($login);
        }		
        if ($objUser->idUser)
        {
            $conf = $this->getFormValue('conf');
	        if ( $conf == 'sim')
	        {
                try
                {
                     $objUser->delete();
                     $MIOLO->prompt(Prompt::information("Usuário [$objUser->login] excluído com sucesso.",$this->home));
                }
                catch (EMioloException $e)
                {
		             $MIOLO->prompt(Prompt::information( $objUser->getErrors(),$this->home));
                }
	        }
	        elseif ( $conf == 'nao')
            {
	            $MIOLO->prompt(Prompt::information('Exclusão cancelada.',$this->home));
            }
	        else
	        {
		        $action_sim = $MIOLO->getActionURL($module,$action,$objUser->idUser, array('event'=>'btnDelete:click','conf'=>'sim'));
		        $action_nao = $MIOLO->getActionURL($module,$action,$objUser->idUser, array('event'=>'btnDelete:click','conf'=>'nao'));
	            $MIOLO->prompt(Prompt::question("Confirma a exclusão do usuário [$objUser->login]?", $action_sim, $action_nao));
            }
        }
        else
        {
            $this->addError("Usuário [$login] não encontrado!");
        }
	}

    public function getAttribute($array, $attr)
    {
        $rs = array();
        if (!is_null($array))
        {
            foreach($array as $c)
            {
                //if(is_array($attr))
                //{
                //    $rs[] = array($c->$attr[0]);//,$c->$attr[1]);
                //}
                //else
                    $rs[] = $c->$attr;
            }
       }
        return $rs;
    }
}

?>
