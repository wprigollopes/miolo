<?
class MBandReport extends MPDFReport
{
    public $img;
    public $timestamp;
    public $titleReport;
    public $pageHeader;
    public $pageFooter; 
    public $spaceGroupHeader = array();
    public $groupHeader = array();
    public $level = array();
    public $maxLevel = 0;

    public function __construct($data, $columns, $pageLength=1, $index=0, $orientation = 'portrait', $paper='a4')
    {
        global $self, $perms;
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $action = MIOLO::getCurrentAction();
        $page = $MIOLO->getPage();
        $context = $MIOLO->getContext();

        parent::__construct($data, $columns, $pageLength, $index, $orientation, $paper);
        $ui = $MIOLO->getUI();
        $this->img = $ui->getImageSrc('logonet.png','tutorial');
        $this->timestamp = date('d/m/Y G:i');
        $this->pdf->ezStartPageNumbers(520, 800, 12, 'rigth', 'Página: {PAGENUM}');
        $this->pdf->ezSetMargins(100, 30, 30, 30);
        $this->getPDF()->setFont('monofont.afm');
    }

    public function generateBodyBand($tree)
    {
        $this->setPageHeader();
        $this->setPageFooter();
        $this->generateDataBand($tree->tree, 0);
    }

    public function generateDataBand($data, $level)
    {
        if ($level <= $this->maxLevel)
        {
            ++$level;
            foreach($data as $key=>$d)
            {
                $this->generateGroupHeader($level, $key);
                $this->generateDataBand($d, $level); 
            }
        }
        else
        {
            $this->generateData($data);
        }
    }

    public function addGroupHeader($level, $text)
    {
        $this->maxLevel = max($level,$this->maxLevel);
        for($i = 0; $i <= $level; $i++)
        {
            $this->spaceGroupHeader[$i] += -16;
        }
        $this->groupHeader[$level] = $text;
    }

    public function generateGroupHeader($level, $key)
    {
        $this->setOption('showHeadings', 1);
        $this->setOption('showLines', 0);
        $this->pdf->setStrokeColor(0, 0, 0);
        $x0 = $this->pdf->left;
        $y = $this->pdf->y;
        $this->pdf->ezSetDy($this->spaceGroupHeader[$level], 'makeSpace');
        $this->pdf->ezSetY($y - 16);
        $this->pdf->addText($x0, $y - 16, 12, str_replace('$',$key,$this->groupHeader[$level]));
    }

    public function setPageHeader()
    {
            $x0 = $this->pdf->left;
            $x1 = $this->pdf->right;
            $h = $this->pdf->getFontHeight(12);
            $this->pageHeader = $this->pdf->openObject();
            $this->pdf->saveState();
            $this->pdf->addPngFromFile($this->img, $x0, 770, 50, 50);
            $this->pdf->addText($x0 + 60, 800, 12, 'UFJF _ Universidade Federal de Juiz de Fora');
            $this->pdf->addText($x0 + 60, 800 - $h, 12, 'CGCO _ Centro de Gestão do Conhecimento Organizacional');
            $this->pdf->addText($x0 + 60, 800 - $h - $h, 12, 'SIGA _ Sistema Integrado de Gestão Acadêmica');
            $y = 800 - $h - $h - 5;
            $this->pdf->addText($x0, $y - 16, 14, $this->titleReport);
            $this->pdf->restoreState();
            $this->pdf->closeObject();
            $this->pdf->addObject($this->pageHeader, 'all');
    }

    public function setPageFooter()
    {
            $x0 = $this->pdf->left;
            $x1 = $this->pdf->right;
            $this->pageFooter = $this->pdf->openObject();
            $this->pdf->saveState();
            $this->pdf->setStrokeColor(0, 0, 0);
            $this->pdf->line($x0, 28, $x1, 28);
            $this->pdf->addText($x0, 19, 9, $this->timestamp . ' :: CGCO/DSI/UFJF');
            $this->pdf->restoreState();
            $this->pdf->closeObject();
            $this->pdf->addObject($this->pageFooter, 'all');
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

?>