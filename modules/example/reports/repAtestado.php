<?
    class repAtestado extends MPDFReport
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
            $curso = $MIOLO->getBusiness('tutorial','curso');
            $query = $curso->listAll();

            // apenas uma coluna
            $column = new MPDFReportColumn('text', 'Text', 'full', true, 80);

            // gera o array de dados
            foreach ($query->result as $row)
            {
                $data[][0] = "Curso: <b>" . $row[1] . "</b>" 
                                 . "\nThis class is designed to provide a <i>non-module</i>, non-commercial alternative to dynamically creating pdf documents from within PHP.\nObviously this will not be quite as quick as the module alternatives, but it is surprisingly fast, this demonstration page is almost a worst case due to the large number of fonts which are displayed.\nThere are a number of features which can be within a Pdf document that it is not at the moment possible to use with this class, but I feel that it is useful enough to be released.\nMesmo com acentuação no meio do texto, e até com õ, this document describes the possible useful calls to the class, the readme.php file (which will create this pdf) should be sufficient as an introduction.\n";
            }

            // call the constructor (with only one record per page)
            parent::__construct($data, $column, 1);
            $this->pdf->selectFont('verase.afm');

            $this->setOption('showHeadings', 0);
            $this->setOption('showTableTitle', 0);
            $this->timestamp = date('d/m/Y G:i');
        }

        public function generatePageHeader()
        {
            $h = $this->pdf->getFontHeight(12);
            $this->pdf->ezImage($this->img, 5, 50, 'none', 'left');
            $this->pdf->addText(90, 800, 12, 'UFJF - Universidade Federal de Juiz de Fora');
            $this->pdf->addText(90, 800 - $h, 12, 'CGCO - Centro de Gestão do Conhecimento Organizacional');
            $this->pdf->addText(90, 800 - $h - $h, 12, 'SIGA - Sistema Integrado de Gestão Acadêmica');
            $this->pdf->ezSetY(800 - $h - $h - $h);
        }

        public function generatePageTitle()
        {
            $year = date('Y');
            $this->pdf->ezSetDy(-20);
            $this->pdf->ezText("<b>Atestado de Uso EzPDF</b>", 14, array('justification' => 'center'));
            $this->pdf->ezText("<b>Ano: $year</b>", 12, array('justification' => 'center'));
            $this->pdf->ezSetDy(-100);
        }

        public function generatePageFooter()
        {
            $this->pdf->setStrokeColor(0, 0, 0);
            $this->pdf->ezSetDy(-100);
            $this->pdf->line(300, $this->pdf->y, 500, $this->pdf->y);
            $this->pdf->ezSetDy(-15);
            $this->pdf->addTextWrap(300, $this->pdf->y, 200, 10, 'Nome do Responsável', 'center');
        }
    }
