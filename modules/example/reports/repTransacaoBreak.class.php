<?
class repTransacaoBreak  extends PDFReport
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
        $this->setPDF(new ezPDFReport('2'));
        $oGrupo = $MIOLO->getBusinessMAD('group');
        $criteria =  $oGrupo->getCriteria();
        $criteria->addColumnAttribute('idGroup');
        $criteria->addColumnAttribute('access.transaction.transaction');
        $criteria->addColumnAttribute('access.rights');
        $criteria->addOrderAttribute('idGroup');
        $query = $criteria->retrieveAsQuery();
        for ($i=0; $i<5; $i++)
           foreach($query->result as $row) $data[] = $row;
        $t = new MTreeArray($data,'0','1,2');
        $n = count($t->tree);
        foreach($t->tree as $k=>$node)
        {
            $rep = new repTransacaoBreakReport($node, $k);
            $rep->setPDF($this->ezpdf);
            $rep->generate();
            if ($k != $n) $this->pdf->ezNewPage();
        }
 	}

    public function generate()
    {
        $this->setOutput();
        $this->execute();
    }
}

class repTransacaoBreakReport extends PDFReport
{
    public $img;
    public $timestamp;
    public $grupo;

	function __construct($data, $idgrupo)
    {
        global $self, $perms;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();
        $page = $MIOLO->getPage();
        $context = $MIOLO->getContext();

        $ui = $MIOLO->getUI();
        $oGrupo = $MIOLO->getBusinessMAD('group');
        $oGrupo->getById($idgrupo);
        $this->grupo = $oGrupo->group; 
            $this->img = $ui->getImageSrc('logonet.png','tutorial');
            $columns = array
                (
                new PDFReportColumn('transaction', '<b><i>TransaÃ§Ã£o</i></b>', 'left', true, 35, true),
                new PDFReportColumn('rights', '<b><i>Direitos</i></b>', 'left', true, 35, true),
                );
            parent::__construct($data, $columns, 25);
            $this->setColumnAttr('transacao', 'index', 0);
            $this->setColumnAttr('direitos', 'index', 1);
            $this->setIndex(0);
            $this->setTitle('RelaÃ§Ã£o de TransaÃ§Ãµes - PageBreak by Group');
            $this->setOption('showTableTitle', 1);
        $this->setRowMethod('repTransacaoBreakReport','MyRowMethod');
        $this->timestamp = date('d/m/Y G:i');
	}

    public function myRowMethod($row,$columns,$slot,$obj)
    {
       if (substr($row[1],0,1) == 'S')
       {
          $row[1] .= '*';
       }
    }

    public function generatePageHeader()
    {   
        $h = $this->pdf->getFontHeight(12);
        $this->pdf->ezImage($this->img,5,50,'none','left');
        $this->pdf->addText(90,800,12,'UFJF - Universidade Federal de Juiz de Fora');
        $this->pdf->addText(520,800,12,'PÃ¡gina: ' . $this->pn->pageNumber . '/' . $this->pn->pageCount);
        $this->pdf->addText(90,800-$h,12,'CGCO - Centro de GestÃ£o do Conhecimento Organizacional');
        $this->pdf->addText(90,800-$h-$h,12,'SIGA - Sistema Integrado de GestÃ£o AcadÃªmica');
        $this->pdf->addText(90,800-$h-$h-$h,12,"<b>Grupo: " . $this->grupo . "</b>");
        $this->pdf->ezSetY(800-$h-$h-$h-$h);
    }
   
    public function generatePageFooter()
    {   
        $this->pdf->ezText($this->timestamp,9,array('justification'=>'right'));
    }
}
?>