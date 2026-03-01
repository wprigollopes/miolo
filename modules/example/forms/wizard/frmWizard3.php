<?
class frmWizard3 extends MFormDialog
{
//
// look at frmWizard1 and frmWizard2...no news here...
//
    public function __construct() 
	{
	    parent::__construct('Wizard Sample - Step 3');
        $this->setClose($this->manager->getActionURL('tutorial','forms'));
        $this->eventHandler();
	}

	function createFields()
	{
// getting the session variables
        $tableFields = $this->manager->getSession()->get('tablefields');
        $table = $this->manager->getSession()->get('table');
        $db = $this->manager->getDatabase('example');
        $fields = implode(',',$tableFields);
        $sql = new MSQL($fields,$table);
        $query = $db->getQuery($sql);
// another way to set the form fields...;-)
        $this->addField(new MLabel('Finally, this form shows a MTableRaw with the records from selected table.'));
        $this->addField(new MSpacer('5px'));
        $this->addField(new MLabel('The selected table was: ' . $table));
        $this->addField(new MSpacer('5px'));
        $this->addField(new MTableRaw('Records',$query->result,$query->getColumnNames()));
        $buttons = array(
			new MButton('btnClose','Close Wizard',$this->linkFree),
		);
		$this->setButtons($buttons);
	}
}
