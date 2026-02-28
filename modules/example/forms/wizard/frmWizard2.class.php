<?
class frmWizard2 extends MFormDialog
{
    private $table;
    private $tableFields = array();
    private $group;

    public function __construct() 
	{
        $MIOLO = MIOLO::getInstance();
// get the table chossen at first step
        $this->table = MForm::getFormValue('table');
// get the names of fields
        $db = $MIOLO->getDatabase('example');
        $info = $db->getTableInfo($this->table);
        foreach($info as $i) $this->tableFields[$i[0]] = $i[0];
// a group of controls
        $selection = new MSelection('tfield[]','','Select a field to show', $this->tableFields);
//        $btnAdd = new MButton('btnAdd[]','+Add another field','ajaxAdd.call();');
        $space = new MSpacer('5px');
//        $this->group = new MDiv('', array($selection));
        $this->group = $selection;
// here we are using AJAX capabilities..
	    parent::__construct('Wizard Sample - Step 2');
$this->manager->trace(implode(' , ',$_REQUEST));
        $this->setClose($this->manager->getActionURL('tutorial','forms'));
// register the methods callable by CPAINT
        $this->registerMethod('ajax_btnAdd');
        $this->eventHandler();
	}

	function createFields()
	{
           $this->page->addJsCode(file_get_contents(dirname(__FILE__) . '/frmWizard2.js'));
//            $this->setFormCSS(0,400);
// a div to join the selection and the button
//            $divAdd = new MDiv('divAdd', $this->divContent);
// normal fields
            $divAdd = new MDiv('divAdd', array($this->group));
            $btnAdd = new MButton('btnAdd[]','+Add another field','ajaxAdd.call();');
            $bg = new MBaseGroup('groupcontrols','Fields:',array($divAdd,$btnAdd),'vertical');
            $bg->setScrollHeight('120px');
            $fields = array(
               new MLabel('This form shows how to integrate dialog+ajax. New fields are added as user demands.'),
               new MSpacer('5px'),
               new MLabel('The selected table was: ' . $this->table),
               new MSpacer('5px'),
               new MLabel('Now, you must select the fields of ' . $this->table),
               new MSpacer('5px'),
               $bg,
            );
            $this->addFields($fields);
// normal buttons
			$buttons = array(
				new MButton('btnBack','<< Back'),
				new MButton('btnNext','Next >>'),
				new MButton('btnClose','Close',$this->linkFree)
			);
			$this->setButtons($buttons);
		}
 
        public function ajax_btnAdd($args)
        {
// again, a div to join the selection and the button
            $selection = new MSelection('tfield[]','','Select a field to show', $this->tableFields);
            $group = new MDiv('', array($selection));
            $this->manager->getTheme()->setAjaxContent($this->group);
        }

		function btnNext_click()
		{
            // pass information using SESSION variables
            $this->manager->getSession()->set('table',$this->table);
            $fields = MForm::getFormValue('tfield');
            $this->manager->getSession()->set('tablefields',$fields);
            $next = $this->manager->getActionURL('tutorial','forms:wizard','3');
            $this->page->redirect($next);
		}

		function btnBack_click()
		{
            $back = $this->manager->getActionURL('tutorial','forms:wizard','1');
            $this->page->redirect($back);
		}
}
?>
