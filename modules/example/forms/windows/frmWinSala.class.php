<?
class frmWinSala extends MForm
{
	function __construct()
    {   
		parent::__construct('Window Sample: Sala');
        $this->setClose($this->getCloseWindow());
		$this->eventHandler();
	}

    public function createFields()
	{
        $fields = array(
           new MTextField("txtSala","","Sala"),
        );
	    $this->setFields($fields);
        $btnPost = new MButton('btnPost', 'Cadastrar Sala');
        $buttons = array( $btnPost );
        $this->setButtons( $buttons );
	}

    public function btnPost_click()
    {
        $sala = $this->manager->getBusiness('example','sala');
        $sala->descricao = $this->getFieldValue('txtSala');
        $sala->save();
    }
}
?>