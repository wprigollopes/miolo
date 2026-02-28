<?
class frmWizard1 extends MFormDialog
{
		function __construct() 
		{
			parent::__construct('Wizard Sample - Step 1');
            $this->setClose($this->manager->getActionURL('tutorial','forms'));
            $this->eventHandler();
		}

		function CreateFields()
		{
            $tables = array('curso'=>'curso','aluno'=>'aluno', 'sala'=>'sala');
// Define the fields and buttons, normally
            $fields = array(
               new MLabel('This is a sample wizard. We want to choose a table from database exemplo, then choose a few fields of that table, and finally show a datagrid with the records. Three simple steps...'),
               new MSpacer('20px'),
               array(
                 new MSelection('table','','Select a table', $tables),
               )
            );
            $this->setFields($fields);
			$buttons = array(
				new MButton('btnNext','Next >>'),
				new MButton('btnClose','Close',$this->linkFree)
			);
			$this->setButtons($buttons);
		}

		function btnNext_click()
		{
            // pass information using GET method
            $table = $this->getFieldValue('table');
            $next = $this->manager->getActionURL('tutorial','forms:wizard','2',array('table'=>$table));
            $this->page->redirect($next);
		}
}
?>