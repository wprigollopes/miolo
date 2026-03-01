<?
class frmWinMatricula extends MFormAJAX
{
	function __construct()
    {   
		parent::__construct('Window Sample: Matricula');
		$this->eventHandler();
	}

    public function createFields()
	{
        $curso = $this->manager->getBusiness('example','curso');

        // define the URLs and create instances of MWindow objects
        $urlCurso = $this->manager->getActionURL('example','windows:modal_ajax','form1'); 
        $winCurso = new MWindow('winCurso',array('url'=>$urlCurso,'title'=>'Inclusão de Novo Curso'));

        $img = $this->manager->getUI()->getImage('','button_refresh.png');
       
        // In this sample, dialogs are called from button clicks
        // $dialog->getLink() return the javascript link to create dialogs
        $selAluno = new MSelection("selAluno","","Alunos",array('-- Selecione--'));
        $selAluno->formMode = MFormControl::FORM_MODE_SHOW_ABOVE;
        $selCurso = new MSelection("selCurso","","Curso",$curso->listAll()->result);
        $selCurso->formMode = MFormControl::FORM_MODE_SHOW_ABOVE;
        $fields = array(
            new MLabel('Selecione um aluno pela inicial: usando AJAX','',true),
            array(
                new MTextField("letterSelection","","Inicial",3),
                new MButton('btnSel','[Selecionar]',':ajax_btnSelAluno'), // set onclick
                new MDiv('m_selAluno',$selAluno),
            ),
            new MLabel('Selecione um curso existente ou cadastre um novo','',true),
            array(
                new MDiv('m_selCurso',$selCurso),
                new MButtonImage('btnRefreshCurso','',':ajax_btnSelCurso',$img),
                new MLink('lnkNovoCurso','&nbsp;', $winCurso->getLink(true, false),'Novo Curso')
            )
        );
	    $this->setFields($fields);
        $btnPost = new MButton('btnPost', 'Matricular');
        $buttons = array( $btnPost );
        $this->setButtons( $buttons );
	}

    public function ajax_btnSelAluno($args)
    {
        // $args is a object with the named parameters
        $value = strtoupper($args->letterSelection);
		$curso = $this->manager->getBusiness('example','curso','1');
        // retrieve alunos with name initial = $value (a letter of choice in the form)
        $query = $curso->listAlunos($value);
        $sel = new MSelection("selAluno","","Alunos",$query->chunkResult(1,0));
        $sel->formMode = MFormControl::FORM_MODE_SHOW_ABOVE;
        $this->setResponse($sel,'m_selAluno');
    }

    public function ajax_btnSelCurso()
    {
		$curso = $this->manager->getBusiness('example','curso');
        $query = $curso->listAll();
        $sel = new MSelection("selCurso","","Curso",$query->chunkResult(0,1));
        $sel->formMode = MFormControl::FORM_MODE_SHOW_ABOVE;
        $this->setResponse($sel,'m_selCurso');
    }

    public function btnPost_click()
    {
        $idAluno = $this->getFieldValue('selAluno');
        $aluno = $this->manager->getBusiness('example','aluno', $idAluno);
        $aluno->idCurso = $this->getFieldValue('selCurso');
        $aluno->save();
    }
}
