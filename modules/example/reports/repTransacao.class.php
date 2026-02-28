<?
    class repTransacao extends PDFReport
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
            $oGrupo = $MIOLO->getBusinessMAD('group');
            $criteria =  $oGrupo->getCriteria();
            $criteria->addColumnAttribute('idGroup');
            $criteria->addColumnAttribute('group');
            $criteria->addColumnAttribute('access.transaction.transaction');
            $criteria->addOrderAttribute('idGroup');
            $query = $criteria->retrieveAsQuery();
            $columns = array
                (
                new PDFReportColumn('idgroup', 'Id', 'right', true, 0, false),
                new PDFReportColumn('group', '<b><i>Grupo</i></b>', 'left', true, 35, true),
                new PDFReportColumn('transaction', '<b><i>Transação</i></b>', 'left', true, 35, true),
                );

            // only to multiply lines...
            for ($i = 0; $i < 5; $i++)
            { 
                foreach ($query->result as $row)
                {
                    $data[] = $row;
                }
            }

            parent::__construct($data, $columns, 25);
            $this->setColumnAttr('group', 'index', 1);
            $this->setColumnAttr('transacao', 'index', 2);
            $this->setIndex(0);
            $this->setTitle('Relação de Transações');
            $this->setOption('showTableTitle', 1);
            $this->setRowMethod('repTransacao', 'MyRowMethod');
            $this->timestamp = date('d/m/Y G:i');
        }

        public function myRowMethod($row, $columns, $slot, $obj)
        {
            if (substr($row[2], 0, 1) == 'S')
            {
                $row[2] .= '*';
            }

            if ($row[1] == $slot['grupo'])
            {
                $row[1] = '';
            }
            else
            {
                $slot['grupo'] = $row[1];
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
            $this->pdf->ezText($this->timestamp, 9, array('justification' => 'right'));
        }
    }
?>
