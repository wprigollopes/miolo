<?

/*
   Cria um report com subreports
*/

class repAlunoBreak extends MPDFReport
{
	function __construct()
    {
        global $self, $perms;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();
        $page = $MIOLO->getPage();
        $context = $MIOLO->getContext();

        parent::__construct(NULL,NULL);
//        $this->setPDF(new MEzPDFReport());
        $this->getPDF()->setFont('monofont.afm');

        // obtem os registros com os campos: nomeCurso, nomeAluno, telefone
        $aluno = $MIOLO->getBusiness('tutorial','aluno');
        $criteria =  $aluno->getCriteria();
        $criteria->addColumnAttribute('curso.nome');
        $criteria->addColumnAttribute('nome');
        $criteria->addColumnAttribute('telefone');
        $criteria->addOrderAttribute('curso.nome');
        $criteria->addOrderAttribute('nome');
        $query = $criteria->retrieveAsQuery();

        // cria a hierarquia com base no nome dos Cursos
        $t = new MTreeArray($query->result,'0','1,2');
        $n = count($t->tree);
        foreach($t->tree as $k=>$node)
        {
            // for each node (course name), create a new report passing the data array
            // and the course name
            $rep = new repAlunoBreakReport($node, $k);
            // set the subreport PDF object to be the same as this report's
            $rep->setPDF($this->getPDF());
            // generate the subreport
            $rep->generate();
            // new page, after generating the subreport
            if ($k != $n) $this->getPDF()->newPage();
        }
 	}

    public function generate()
    {
        // this method overrides the parent class method, just to prevent this report from being generated
        // since this report has no data...it acts only as a container for the subreports
        $this->setOutput();
        $this->execute();
    }
}

class repAlunoBreakReport extends MPDFReport
{
    public $img;
    public $timestamp;
    public $nomeCurso;

	function __construct($data, $nomeCurso)
    {
        global $context, $self, $perms;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();
        $page = $MIOLO->getPage();

        // this subreport is a report like any other....
        $this->nomeCurso = strtoupper($nomeCurso);
        $ui = $MIOLO->getUI();
        $this->img = $ui->getImageSrc('logonet.png','tutorial');
        $columns = array (
            new MPDFReportColumn('nome', '<b><i>Nome</i></b>', 'left', true, 60, true),
            new MPDFReportColumn('telefone', '<b><i>Telefone</i></b>', 'left', true, 40, true),
        );
        parent::__construct($data, $columns, 45);
        $this->setColumnAttr('nome', 'index', 0);
        $this->setColumnAttr('telefone', 'index', 1);
        $this->setTitle('Relação de Alunos do Curso');
        $this->setOption('showTableTitle', 1);
        $this->timestamp = date('d/m/Y G:i');
	}

    public function generatePageHeader()
    {   
        $h = $this->pdf->getFontHeight(12);
        $this->pdf->ezImage($this->img,5,50,'none','left');
        $this->pdf->addText(90,800,12,'UFJF - Universidade Federal de Juiz de Fora');
        $this->pdf->addText(520,800,12,'Página: ' . $this->pn->pageNumber . '/' . $this->pn->pageCount);
        $this->pdf->addText(90,800-$h,12,'CGCO - Centro de Gestão do Conhecimento Organizacional');
        $this->pdf->addText(90,800-$h-$h,12,'SIGA - Sistema Integrado de Gestão Acadêmica');
        $this->pdf->addText(90,800-$h-$h-$h,12,"<b>Curso: " . $this->nomeCurso . "</b>");
        $this->pdf->ezSetY(800-$h-$h-$h-$h);
    }
   
    public function generatePageFooter()
    {   
        $this->pdf->ezSetDy(-10);
        $this->pdf->ezText($this->timestamp,9,array('justification'=>'right'));
    }
}
?>