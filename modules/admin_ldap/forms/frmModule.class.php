<?
class frmModule extends MForm
{
	var $home;
    public $objModule;

	function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();

        $this->home      = $MIOLO->getActionURL($module,$action);
        $this->objModule = $MIOLO->getBusiness($module,'module');
		parent::__construct('Modules');
        $this->setWidth('65%');
        $this->setIcon($MIOLO->getUI()->getImage('admin','system1.png'));
        $this->setClose($MIOLO->getActionURL('admin','main'));
	    $this->eventHandler();
	}

    public function createFields()
	{  $MIOLO = MIOLO::getInstance();

       $fields = array(
           new MTextField('edtIdModule','','MÃ³dulo',50),
           new MTextField('edtNome','','Nome',50),
           new MMultiLineField( 'edtDescricao','','DescriÃ§Ã£o',30,5,30),
       );
	   $this->setFields($fields);

       $this->setFieldsVisible(false);
	   $this->setFieldAttr('edtIdModule','visible',true);

       $buttons = array(
           new MButton('btnEdit',   'Editar'),
		   new MButton('btnNew',    'Incluir'),
           new MButton('btnDelete', 'Excluir'),
	       new MButton('btnList',   'RelaÃ§Ã£o')
       );
	   $this->setButtons($buttons);
       var_dump(ok);
	}

    public function setFieldsVisible($value)
    {
	   $this->setFieldAttr('edtIdModule','visible',$value);
	   $this->setFieldAttr('edtNome','visible',$value);
	   $this->setFieldAttr('edtDescricao','visible',$value);
    }

/*
    GetData: obtÃ©m os valores fornecidos no formulario e cria um objeto FormData
             cujos attributos tÃªm o mesmo nome dos atributos do objeto que vai receber os valores.
    A implementaÃ§Ã£o default do GetData cria um objeto FormData cujos atributos tÃªm
    o mesmo nome dos campos do formulÃ¡rio.   
*/
	function getData()
	{
        $data = new FormData();
		$data->idModule = $this->getFieldValue('edtIdModule');
		$data->nome     = $data->name = $this->getFieldValue('edtNome');
		$data->descricao= $data->description = $this->getFieldValue('edtDescricao');
        return $data;
	}

/*
    SetData: obtÃ©m os valores fornecidos atravÃ©s do parÃ¢metro $data (geralmente um
             objeto de negÃ³cio) e preenche os campos do formulÃ¡rio.
    A implementaÃ§Ã£o default do SetData assume que os atributos do objeto $data tÃªm
    o mesmo nome dos campos do formulÃ¡rio.   
*/
	function setData($data)
	{
		$this->setFieldValue('edtIdModule' , $data->idModule);
		$this->setFieldValue('edtNome'     , $data->nome);
		$this->setFieldValue('edtDescricao', $data->descricao);
	}

	function btnPost_click()
	{
        $MIOLO = MIOLO::getInstance();

        $module = $this->getFieldValue('edtIdModule');  // inclusÃ£o ou ediÃ§Ã£o?
        
        if ($module != '')
        {
            $this->objModule->getById($module); // se for ediÃ§Ã£o, obtem os dados atuais do objeto
        }
        // seta os atributos do objeto com os valores dos campos do formulario
		$this->objModule->setData($this->getData()); 

        try
        {
            $this->objModule->save();
            $MIOLO->information('MÃ³dulo atualizado com sucesso.', $this->home );
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

        // limpa o formulÃ¡rio
        $this->clearFields();
        $this->clearButtons();
        $this->defaultButton = false;
 
        // define o campo para fazer o filtro
        $fields = array(
            array(
               new MTextField('txtId'  ,'' ,'MÃ³dulo',25),
               new MButton   ('btnList','RelaÃ§Ã£o')
            )
        );
        $this->setFields($fields);

        // colunas do DataGrid
        $columns = array(
           new MDataGridColumn('idmodule' ,'Id'       ,'left', true, '40%',true),
           new MDataGridColumn('name'     ,'Nome'     ,'left', true, '60%',true, NULL, true,true),
        );

        // link de referencia para o grid
		$hrefDatagrid = $MIOLO->getActionURL($module,$action,'', Array('event'=>'btnList_click'));

        // valor definido como filtro
        $id = MUtil::NVL($this->getFieldValue('txtId'), '');

        // executa a query
        $query = $this->objModule->listById($id);

        // instancia o datagrid
        $datagrid = new MDataGrid($query, $columns, $hrefDatagrid, 20);
        $datagrid->setTitle('RelaÃ§Ã£o de MÃ³dulos');
        $datagrid->setClose($MIOLO->getActionURL($module,$action));

 	    $href_edit = $MIOLO->getActionURL($module,$action,'%0%',Array('event'=>'btnEdit:click'));
	    $href_dele = $MIOLO->getActionURL($module,$action,'%0%',Array('event'=>'btnDelete:click'));
        $datagrid->addActionUpdate($href_edit);
	    $datagrid->addActionDelete($href_dele);
        // coloca o datagrid no formulÃ¡rio
		$this->addField($datagrid);
	}

	function btnEdit_click($sender, $key='')
	{   
        global $item;

        $module = $this->getFieldValue('edtIdModule');
        // verifica se estÃ¡ sendo executado atravÃ©s do evento do grid
        $item = ($module != '') ? $module : $item;
        $this->objModule->getById($item);
        
        if ($this->objModule->nome)
        {
            // coloca os dados do objeto nos campos do formulÃ¡rio
            $this->setData($this->objModule);
            // exibe os campos
            $this->setFieldsVisible(true);
            $this->setFieldAttr('idModule','readonly',true);
            $this->addButton(new MButton('btnCancel', 'Cancelar', $this->home));
            $this->setButtonAttr('btnEdit','name'    ,'btnPost');
            $this->setButtonAttr('btnPost','label'   ,'Gravar');
            $this->setButtonAttr('btnNew' ,'visible' , false);
        }
        else
        {
            $this->addError("MÃ³dulo [$module] nÃ£o encontrado!");
        }
	}

	function btnCancel_click()
	{   
        $this->setFieldValue('edtIdModule','');
    }

	function btnNew_click($sender)
	{   
        $data = new FormData();
        $data->idModule = $this->getFieldValue('edtIdModule');
        if ($data->idModule != '')
        {
            $this->setData($data);
            $this->setFieldsVisible(true);
            $this->addButton(new MButton('btnCancel', 'Cancelar', $this->home));
            $this->setButtonAttr('btnEdit','name'   ,'btnPost');
            $this->setButtonAttr('btnPost','label'  ,'Gravar');
            $this->setButtonAttr('btnNew' ,'visible', false);
            $this->addValidator(new RequiredValidator('edtIdModule'));
            $this->addValidator(new RequiredValidator('edtNome'));
        }
        else
        {
            $this->addError("Por favor, informe a identificaÃ§Ã£o para o novo mÃ³dulo!");
        }
	}

	function btnDelete_click($sender, $key='')
	{   
        global $item, $self, $url;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();

        $objModule = $this->objModule;
        $modulo    = $this->getFieldValue('edtIdModule');
        // verifica se estÃ¡ sendo executado atravÃ©s do evento do grid
        $item = ($key != '') ? $key : $item;
        $objModule->getById($item);
        
        if ($objModule->idModule)
        {
            $conf = $this->getFormValue('conf');
	        if ( $conf == 'sim')
	        {
                try
                {
                     $objModule->delete();
                     $MIOLO->prompt(Prompt::information("MÃ³dulo [$objModule->idModule] excluÃ­do com sucesso.",$this->home));
                }
                catch (EMioloException $e)
                {
		             $MIOLO->prompt(Prompt::information( $objModule->getErrors(),$this->home));
                }
	        }
	        elseif ( $conf == 'nao')
            {
	            $MIOLO->prompt(Prompt::information('ExclusÃ£o cancelada.',$this->home));
            }
	        else
	        {
		        $action_sim = $MIOLO->getActionURL($module,$action,$objModule->idUser, array('event'=>'btnDelete:click','conf'=>'sim'));
		        $action_nao = $MIOLO->getActionURL($module,$action,$objModule->idUser, array('event'=>'btnDelete:click','conf'=>'nao'));
	            $MIOLO->prompt(Prompt::question("Confirma a exclusÃ£o do mÃ³dulo [$objModule->idModule]?", $action_sim, $action_nao));
            }
        }
        else
        {
            $this->addError("MÃ³dulo [$modulo] nÃ£o encontrado!");
        }
	}

}

?>
