<?
/*
   Cria um report com linhas de agrupamento
*/

    class repMultiLevel2 extends MPDFReport
    {
        public $timestamp;
        public $img;
        public $level = array();

        public function __construct()
        {
            global $self, $perms;
            $MIOLO = MIOLO::getInstance();
            $module = MIOLO::getCurrentModule();
            $action = MIOLO::getCurrentAction();
            $page = $MIOLO->getPage();
            $context = $MIOLO->getContext();

            parent::__construct(NULL, NULL, 55);
            $ui = $MIOLO->getUI();
            $this->img = $ui->getImageSrc('logonet.png','tutorial');
            $this->timestamp = date('d/m/Y G:i');
            $this->pdf->ezStartPageNumbers(520, 800, 12, 'rigth', 'Página: {PAGENUM}');
            $this->pdf->ezSetMargins(100, 30, 30, 30);
            $this->generatePageHeader();
            $this->generatePageFooter();

            // simula multiplos niveis de quebra
            for ($i = 0; $i < 2; $i++)     // level 1
            {
                $soma1 = 0;

                for ($j = 0; $j < 3; $j++) // level 2
                {
                    $soma2 = 0;

                    for ($k = 0; $k < 5; $k++) // level 3
                    {
                        $r = rand(1, 15);
                        $soma3 = 0;

                        for ($l = 0; $l < $r; $l++) // data
                        {
                            $x = rand(1, 15); // simula um valor qualquer
                            $data[] = array($i,$j,$k,$l,'a','b','c',$x);

                            $soma3 += $x;
                        }

                        $summary3[] = array(array("Soma do Level3 ($k):", "<b>$soma3</b>"));
                        $soma2 += $soma3;
                    }

                    $summary2[] = array(array("Soma do Level2 ($j):", "<b>$soma2</b>"));
                    $soma1 += $soma2;
                }

                $summary1[] = array(array("Soma do Level1:", "<b>$soma1</b>"));
            }

            // define as colunas da linha de detalhe
            $columns = array(
                new MPDFReportColumn('level4', '<b><i>Level 4</i></b>', 'right', false, 30, true),
                new MPDFReportColumn('det1', '<b><i>detalhe 1</i></b>', 'right', false, 15, true),
                new MPDFReportColumn('det2', '<b><i>detalhe 2</i></b>', 'left', false, 15, true),
                new MPDFReportColumn('det3', '<b><i>detalhe 3</i></b>', 'center', false, 20, true),
                new MPDFReportColumn('det4', '<b><i>detalhe 4</i></b>', 'right', false, 20, true)
            );

            // define as colunas da linha de grupo (level3)
            $sum3 = array(
                new MPDFReportColumn('texto', 'texto', 'right', false, 80, true),
                new MPDFReportColumn('valor', 'valor', 'right', false, 20, true)
            );

            // define as colunas da linha de grupo (level2)
            $sum2 = array(
                new MPDFReportColumn('texto', 'texto', 'right', false, 80, true),
                new MPDFReportColumn('valor', 'valor', 'right', false, 20, true)
            );

            // define as colunas da linha de grupo (level1)
            $sum1 = array(
                new MPDFReportColumn('texto', 'texto', 'right', false, 80, true),
                new MPDFReportColumn('valor', 'valor', 'right', false, 20, true)
            );

            // Largura do report, em %
            $this->setWidth(100);

            // cria a arvore de dados
            $t = new MTreeArray($data, '0,1,2', '3,4,5,6,7');

            // registra as colunas de detalhes e summary
            $this->setColumns($columns);
            $colDetail = $this->columns;
            $this->setColumns($sum3);
            $colSum3 = $this->columns;
            $this->setColumns($sum2);
            $colSum2 = $this->columns;
            $this->setColumns($sum1);
            $colSum1 = $this->columns;

            // gera o report
            foreach ($t->tree as $this->level[1] => $node1)
            {
                $i = 0;

                foreach ($node1 as $this->level[2] => $node2)
                {
                    $this->setOption('showHeadings', 1);
                    $this->generateGroupHeader(2, $this->level);
                    $j = 0;

                    foreach ($node2 as $this->level[3] => $node3)
                    {
                        $this->setOption('showHeadings', 1);
                        $this->setOption('showLines', 0);
                        $this->columns = $colDetail;
                        $this->generateGroupHeader(3, $this->level);
                        $this->generateData($node3);
                        $this->setOption('showHeadings', 0);
                        $this->setOption('showLines', 1);
                        $this->columns = $colSum3;
                        $this->generateData($summary3[$j++]);
                    }

                    $this->setOption('showHeadings', 0);
                    $this->setOption('showLines', 1);
                    $this->columns = $colSum2;
                    $this->generateData($summary2[$i++]);
                }

                $this->setOption('showHeadings', 0);
                $this->setOption('showLines', 1);
                $this->columns = $colSum1;
                $this->generateData($summary1[0]);
            }
        }

        public function generateGroupHeader($level, $array)
        {
            $this->pdf->setStrokeColor(0, 0, 0);
            $x0 = $this->pdf->left;

            if ($level == 1)
            {
            }
            elseif ($level == 2)
            {
                $this->pdf->ezSetDy(-32, 'makeSpace');
                $y = $this->pdf->y;
                $this->pdf->addText($x0, $y + 19, 12, 'Level 1: ' . $array[1]);
                $this->pdf->addText($x0, $y + 2, 12, '    Level 2: ' . $array[2]);
            }
            elseif ($level == 3)
            {
                $this->pdf->ezSetDy(-50, 'makeSpace');
                $y = $this->pdf->y;
                $this->pdf->addText($x0, $y + 34, 12, '         Level 3: ' . $array[3]);
                $this->pdf->ezSetY($y + 30);
            }
        }

        public function generatePageHeader()
        {
            $x0 = $this->pdf->left;
            $x1 = $this->pdf->right;
            $h = $this->pdf->getFontHeight(12);
            $header = $this->pdf->openObject();
            $this->pdf->saveState();
            $this->pdf->addPngFromFile($this->img, $x0, 770, 50, 50);
            $this->pdf->addText($x0 + 60, 800, 12, 'UFJF - Universidade Federal de Juiz de Fora');
            $this->pdf->addText($x0 + 60, 800 - $h, 12, 'CGCO - Centro de Gestão do Conhecimento Organizacional');
            $this->pdf->addText($x0 + 60, 800 - $h - $h, 12, 'SIGA - Sistema Integrado de Gestão Acadêmica');
            $y = 800 - $h - $h - 5;
            $this->pdf->setColor(0.1,0.1,0.1);
            $this->pdf->filledRectangle($x0, $y-20, $x1-30, 16 );
            $this->pdf->setColor(1,1,1);
            $this->pdf->addText($x0 + 5, $y - 16, 14, 'REPORT MULTILEVEL COM AGRUPAMENTO');
            $this->pdf->restoreState();
            $this->pdf->closeObject();
            $this->pdf->addObject($header, 'all');
        }

        public function generatePageFooter()
        {
            $x0 = $this->pdf->left;
            $x1 = $this->pdf->right;
            $footer = $this->pdf->openObject();
            $this->pdf->saveState();
            $this->pdf->setStrokeColor(0, 0, 0);
            $this->pdf->line($x0, 28, $x1, 28);
            $this->pdf->addText($x0, 19, 9, $this->timestamp . ' - CGCO/DSI');
            $this->pdf->restoreState();
            $this->pdf->closeObject();
            $this->pdf->addObject($footer, 'all');
        }

        public function generateData($data)
        {
            $this->pdf->ezSetDy(-5);
            $this->setData($data);
            $this->rawdata = $this->generateTableData();
            $this->generateBody($this->rawdata);
        }

        public function generate()
        {
            $this->setOutput();
            $this->execute();
        }
    }
