<?
class frmWinCurso extends MFormAjax
{
	function __construct()
    {   
		parent::__construct('Window Sample: Curso');
        $this->setClose($this->getCloseWindow());
		$this->eventHandler();
	}

    public function createFields()
	{
        $sala = $this->manager->getBusiness('example','sala');
        $ui = $this->manager->getUI();
        $img = $this->manager->getUI()->getImage('','button_refresh.png');

        // define the URLs and create instances of MWindow objects
        $urlSala = $this->manager->getActionURL('example','windows:modal_ajax','form2'); 
        $winSala = new MWindow('winSala',array('url'=>$urlSala,'title'=>'Inclusão de Nova Sala'));

        // In this sample, dialogs are called from button clicks
        // $dialog->getLink() return the javascript link to create dialogs
        $sel = new MSelection("selSala","","Sala",$sala->listAll()->result);
        $sel->formMode = MFormControl::FORM_MODE_SHOW_ABOVE;
        $fields = array(
           new MTextField("txtCurso","","Curso"),
           new MLabel('Selecione uma sala existente ou cadastre uma nova','',true),
           array(
              new MDiv('m_selSala', $sel),
              new MButtonImage('btnRefreshSala','',':ajax_btnSelSala;',$img),
              new MLink('lnkNovaSala','&nbsp;', $winSala->getLink(true, false),'Nova Sala')
           )
        );
	    $this->setFields($fields);
        $btnPost = new MButton('btnPostCurso', 'Cadastrar Curso');
        $buttons = array( $btnPost );
        $this->setButtons( $buttons );
	}

    public function ajax_btnSelSala()
    {
		$sala = $this->manager->getBusiness('example','sala');
        $query = $sala->listAll();
        $sel = new MSelection("selSala","","Sala",$query->chunkResult(0,1));
        $sel->formMode = MFormControl::FORM_MODE_SHOW_ABOVE;
        $this->setResponse($sel,'m_selSala');
    }

    public function btnPostCurso_click()
    {
        $curso = $this->manager->getBusiness('example','curso');
        $curso->nome = $this->getFieldValue('txtCurso');
        $curso->idSala = $this->getFieldValue('selSala');
        $curso->save();
    }
}
