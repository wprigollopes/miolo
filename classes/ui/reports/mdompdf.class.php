<?php
use Dompdf\Dompdf;

class MDOMPDF extends MReport
{
    public $dompdf;

    public function __construct($paper = 'a4', $orientation = 'portrait')
    {
        $old_limit = ini_set("memory_limit", "80M");

        if ($paper=='')
        {
            $paper = 'a4';
        }
        if ($orientation=='')
        {
            $orientation = "portrait";
        }
        $this->dompdf = new Dompdf();
        $this->dompdf->setPaper($paper, $orientation);
    }

    public function setInput($str,$params=NULL)
    {
        if ($params)
        {
           global $var;
           $var = $params;
        }

        if ( $str != '') {
          $this->dompdf->loadHtml($str);
        } else
          $this->dompdf->loadHtmlFile($file);

        if ( isset($base_path) ) {
          $this->dompdf->setBasePath($base_path);
        }
    }

    public function execute()
    {
        global $MIOLOCONF;
        $MIOLO = MIOLO::getInstance();
        $page = $MIOLO->getPage();

        $this->dompdf->render();

        $pdfcode = $this->dompdf->output();
    
        $fname = substr(uniqid(md5(uniqid(""))), 0, 10) . '.pdf';
        $this->fileexp = $MIOLO->getConf('home.reports') . '/' . $fname;
        $fp = fopen($this->fileexp, 'x');
        fwrite($fp, $pdfcode);
        fclose ($fp);
        $this->fileout = $MIOLO->getActionURL('miolo', 'reports:' . $fname);

//        file_put_contents($this->fileout, $pdfcode);
        $page->redirect($this->fileout);
    }
}
?>
