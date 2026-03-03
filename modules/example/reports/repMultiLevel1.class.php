<?
/*
   Cria um report com vÃ¡rias quebras, sem usar subreports
   As quebras sÃ£o controladas atravÃ©s da anÃ¡lise de cada linha antes da impressÃ£o,
   no mÃ©todo MyRowMethod 
*/

    class repMultiLevel1 extends MPDFReport
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

            parent::__construct(NULL, NULL);
            $this->setPDF(new MezPDFReport());

            // simula multiplos niveis de quebra
            for ($level1 = 0; $level1 < 2; $level1++)             // level 1 - 2 itens
            {
                for ($level2 = 0; $level2 < 3; $level2++)         // level 2 - 3 itens
                { 
                    for ($level3 = 0; $level3 < 5; $level3++)     // level 3 - 5 itens
                    { 
                        for ($datalevel = 0; $datalevel < 9; $datalevel++) // data level - 10 itens
                        {
                            // cria a matriz de dados:
                            // 1a. coluna: level1
                            // 2a. coluna: level2
                            // 3a. coluna: level3
                            // 4a. coluna: dados 
                            $data[] = array($level1, $level2, $level3, $datalevel );
                        }
                    }
                }
            }

            $ui = $MIOLO->getUI();
            $this->img = $ui->getImageSrc('logonet.png','tutorial');
            // define as colunas do report
            $columns = array (
                new MPDFReportColumn('level1', '<b><i>Level 1</i></b>', 'right', true, 0, true),
                new MPDFReportColumn('level2', '<b><i>Level 2</i></b>', 'right', true, 0, true),
                new MPDFReportColumn('level3', '<b><i>Level 3</i></b>', 'right', true, 0, true),
                new MPDFReportColumn('level4', '<b><i>Dados</i></b>', 'right', true, 0, true)
            );
            parent::__construct($data, $columns, 45);
            $this->getPDF()->setFont('monofont.afm');
            $this->setTitle('Exemplo MPDFReport MultiLevel sem Subreport');
            $this->setOption('showTableTitle', 1);
            $this->setRowMethod('repMultiLevel1', 'MyRowMethod');
            $this->timestamp = date('d/m/Y G:i');
        }

        public function myRowMethod($row, $columns, $slot, $report)
        {
            // se Ã© o mesmo valor na coluna level1, nÃ£o imprime
            if ($row[0] === $slot['level1'])
            {
                $row[0] = '';
            }
            else
            {
                $slot['level1'] = $row[0];
            }

            // se Ã© o mesmo valor na coluna level2, nÃ£o imprime
            if ($row[1] === $slot['level2'])
            {
                $row[1] = '';
            }
            else
            {
                $slot['level2'] = $row[1];
            }

            // se Ã© o mesmo valor na coluna level3, nÃ£o imprime
            if ($row[2] === $slot['level3'])
            {
                $row[2] = '';
            }
            else
            {
                $slot['level3'] = $row[2];
            }
        }

        public function generatePageHeader()
        {
            $h = $this->pdf->getFontHeight(12);
            $this->pdf->ezImage($this->img, 5, 50, 'none', 'left');
            $this->pdf->addText(90, 800, 12, 'UFJF - Universidade Federal de Juiz de Fora');
            $this->pdf->addText(520, 800, 12, 'PÃ¡gina: ' . $this->pn->pageNumber . '/' . $this->pn->pageCount);
            $this->pdf->addText(90, 800 - $h, 12, 'CGCO - Centro de GestÃ£o do Conhecimento Organizacional');
            $this->pdf->addText(90, 800 - $h - $h, 12, 'SIGA - Sistema Integrado de GestÃ£o AcadÃªmica');
            $this->pdf->ezSetY(800 - $h - $h - $h - $h);
        }

        public function generatePageFooter()
        {
            $this->pdf->ezSetDy(-10);
            $this->pdf->ezText($this->timestamp, 9, array('justification' => 'right'));
        }
    }
?>
