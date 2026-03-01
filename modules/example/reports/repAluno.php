<?
    class repAluno extends MPDFReport
    {
        public $img;
        public $timestamp;

        public function __construct()
        {
            global $self, $perms;
            $MIOLO = MIOLO::getInstance();
            $module = MIOLO::getCurrentModule();
            $action = MIOLO::getCurrentAction();
            $page = $MIOLO->getPage();
            $context = $MIOLO->getContext();

            $ui = $MIOLO->getUI();
            $this->img = $ui->getImageSrc('logonet.png','tutorial');
            $aluno = $MIOLO->getBusiness('tutorial','aluno');
            $criteria = $aluno->getCriteria();
            $criteria->addColumnAttribute('idAluno');
            $criteria->addColumnAttribute('nome');
            $criteria->addColumnAttribute('telefone');
            $criteria->addColumnAttribute('curso.nome');
            $query = $criteria->retrieveAsQuery();
            $columns = array (
                new MPDFReportColumn('idaluno', 'Id', 'right', true, 10, true),
                new MPDFReportColumn('nome', '<b><i>Nome</i></b>', 'left', true, 50, true),
                new MPDFReportColumn('telefone', '<b><i>Telefone</i></b>', 'left', true, 20, true),
                new MPDFReportColumn('curso', '<b><i>Curso</i></b>', 'left', true, 20, true),
            );
            parent::__construct($query->result, $columns, 49);
            $this->getPDF()->setFont('monofont.afm');
            $this->setColumnAttr('idaluno', 'index', 0);
            $this->setColumnAttr('nome', 'index', 1);
            $this->setColumnAttr('telefone', 'index', 2);
            $this->setColumnAttr('curso', 'index', 3);
            $this->setTitle('Relação de Alunos');
            $this->setOption('showTableTitle', 1);
            $this->setRowMethod('repAluno', 'MyRowMethod');
            $this->timestamp = date('d/m/Y G:i');
        }

        public function myRowMethod($row, $columns, $slot, $obj)
        {
            if (substr($row[1], 0, 1) == 'F')
            {
                $row[1] .= '**';
            }
        }

        public function generatePageHeader()
        {
            $h = $this->pdf->getFontHeight(12);
            $this->pdf->ezImage($this->img, 5, 50, 'none', 'left');
            $this->pdf->addText(90, 800, 12, 'UFJF - Universidade Federal de Juiz de Fora');
            $this->pdf->addText(520, 800, 12, 'Página: ' . $this->pn->pageNumber . '/' . $this->pn->pageCount);
            $this->pdf->addText(90, 800 - $h, 12, 'CGCO - Centro de Gestão do Conhecimento Organizacional');
            $this->pdf->addText(90, 800 - $h - $h, 12, 'SIGA - Sistema Integrado de Gestão Acadêmica');
            $this->pdf->ezSetY(800 - $h - $h - $h);
        }

        public function generatePageFooter()
        {
            $this->pdf->ezSetDy(-10);
            $this->pdf->ezText($this->timestamp, 9, array('justification' => 'right'));
        }
    }
