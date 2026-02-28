<?php

class frmEzPDF extends MForm
{
    public $fonts;

    public function __construct()
    {
        $module = MIOLO::getCurrentModule();
        parent::__construct(_M('Using @1 Reports', $module, 'EzPDF'));
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        // array com os nomes das fontes e arquivos correspondentes
        $this->fonts = array(
            'arial.afm' => 'Arial',
            'vera.afm' => 'BitStream Vera Sans',
            'veramono.afm' => 'BitStream Vera Sans Mono',
            'verase.afm' => 'BitStream Vera Serif',
            'Courier.afm' => 'Courier',
            'Helvetica.afm' => 'Helvetica',
            'monofont.afm' => 'MonoFont',
            'Symbol.afm' => 'Symbol',
            'tahoma.afm' => 'Tahoma',
            'Times.afm' => 'Times',
            'verdana.afm' => 'Verdana',
            'ZapfDingbats.afm' => 'ZapfDingbats'
        );

        $fields = array(
            new MButton('btnRep0', _M('Generate with fonts and sizes', $module), 'PDF'),
            new MSeparator(),
            array(
                new MSelection('font', 'arial.afm', _M('Font', $module), $this->fonts),
                new MButton('btnRep1', _M('Generate document with numbered lines', $module), 'PDF')
            ),
            new MSeparator(),
            new MButton('btnRep2', _M('Generate Table', $module) . ' ezTable', 'PDF'),
            new MSeparator(),
            new MButton('btnRep3', _M('Generate Coordinates', $module), 'PDF'),
            new MSeparator(),
            new MButton('btnRep4', _M('Generate MPDFReport - Students', $module), 'PDF'),
            new MSeparator(),
            new MButton('btnRep5', _M('Generate MPDFReport with one page', $module), 'PDF'),
            new MSeparator(),
            new MButton('btnRep6', _M('Generate MPDFReport with break (SubReport)', $module), 'PDF'),
            new MSeparator(),
            new MButton('btnRep7', _M('Generate Report - MultiLevel without SubReport', $module), 'PDF'),
            new MSeparator(),
            new MButton('btnRep8', _M('Generate Report - MultiLevel Grouped', $module), 'PDF'),
            new MSeparator(),
            new MButton('btnRep9', _M('Generate Report - MultiLevel with Bands', $module), 'PDF')
        );
        $this->setFields($fields);
        $this->defaultButton = false;
    }

    public function btnRep0_click()
    {
        $module = MIOLO::getCurrentModule();
        // instancia um novo report 
        $report = new MEzPDFReport();
        // obtem o objeto pdf
        $pdf = $report->getPdf();
        // texto base
        $texto = _M('The quick brown fox jumps over the lazy dog.', $module);
        foreach ( $this->fonts as $f => $n )
        {
            if ( $n != 'Symbol' )
            {
                // seleciona a fonte
                $pdf->selectFont($f);
                $pdf->ezText(_M('Font', $module) . ": $n  - File: $f");
                for ( $size = 6; $size < 13; $size+=2 )
                {
                    // adiciona o texto no documento - avanÃ§a automaticamente para proxima linha
                    $pdf->ezText(_M('Size', $module) . " $size: $texto", $size);
                }
                // texto em negrito (bold)
//            $pdf->ezText("<b>$texto</b>",10);
                // texto em italico
//            $pdf->ezText("<i>$texto</i>",10);
                $pdf->ezText('');
            }
        }
        // executa o report (gera o arquivo pdf e repassa para o browser)
        $report->execute();
    }

    public function btnRep1_click()
    {
        $module = MIOLO::getCurrentModule();
        // instancia um novo report 
        $report = new MEzPDFReport();
        // obtem o objeto pdf
        $pdf = $report->getPdf();
        // obtem o nome do arquivo correspondente a fonte selecionada e seleciona a fonte
        $font = $this->getFieldValue('font');
        $pdf->selectFont($font);
        // escreve 100 linhas no documento
        for ( $i = 0; $i < 100; $i++ )
        {
            $r = $i / 100;
            $g = $i / 200;
            $b = (100 - $i) / 100;
            // altera a cor da fonte
            $pdf->setColor($r, $g, $b);
            // adiciona o texto no documento - avanÃ§a automaticamente para proxima linha
            $pdf->ezText((($i == 0) ? _M('Font', $module) . ': ' . $font . ' - ' : '') . _M('Line', $module) . ' = ' . $i . '[ y = ' . $pdf->y . ' ] ');
        }
        // executa o report (gera o arquivo pdf e repassa para o browser)
        $report->execute();
    }

    public function btnRep2_click()
    {
        $MIOLO = MIOLO::getInstance();

        $report = new MezPDFReport();
        $data = array( );
        $aluno = $MIOLO->getBusiness('example', 'aluno');
        $query = $aluno->listAll();
        $options['textCol'] = array( 0, 0, 0 );
        $options['shaded'] = 1;
        $options['fontSize'] = 10;
        $options['titleFontSize'] = 12;
        $cols = array(
            _M('ID', $module),
            _M('Name', $module),
            _M('Gender', $module),
            _M('Phone', $module),
            _M('Course ID', $module)
        );
        $report->setFont('veramono.afm');
        $report->pdf->ezTable($query->result, $cols, _M('Students', $module), $options);
        $report->execute();
    }

    public function btnRep3_click()
    {
        $report = new MezPDFReport();
        $report->setFont('veramono.afm');
        $pdf = $report->getPdf();
        $h = $pdf->getFontHeight(12);
        $y = round($pdf->y / 10) * 10;
        $pdf->addText(100, $y - 85, 12, _M('Coordinates', $module));
        $pdf->addText(100, $y - 100, 12, 'A4 = 595.4 x 842     -  ' . _M('letter', $module) . ' = 612 x 792');
        $x = 0;
        while ( $y > 0 )
        {
            if ( $y % 50 == 0 )
            {
                $pdf->line($x, $y, $x + 15, $y);
                $pdf->addText($x + 20, $y, 12, $y);
            }
            else
            {
                $pdf->line($x, $y, $x + 10, $y);
            }
            $y -= 10;
        }
        $y = $pdf->y;
        $y -= $h;
        for ( $x = 0; $x < 600; $x+=10 )
        {
            if ( $x % 50 == 0 )
                $pdf->line($x, $y, $x, $y - 10);
            else
                $pdf->line($x, $y, $x, $y - 5);
        }
        $y -= ( 2 * $h);
        for ( $x = 0; $x < 600; $x+=10 )
        {
            if ( $x % 50 == 0 )
                $pdf->addText($x, $y, 12, $x);
        }
        $report->execute();
    }

    public function btnRep4_click()
    {
        $MIOLO = MIOLO::getInstance();

        $report = $MIOLO->getUI()->getReport('example', 'repAluno');
        $report->generate();
    }

    public function btnRep5_click()
    {
        $MIOLO = MIOLO::getInstance();

        $report = $MIOLO->getUI()->getReport('example', 'repAtestado');
        $report->generate();
    }

    public function btnRep6_click()
    {
        $MIOLO = MIOLO::getInstance();

        $ui = $MIOLO->getUI();
        $report = $ui->getReport('example', 'repAlunoBreak');
        $report->generate();
    }

    public function btnRep7_click()
    {
        $MIOLO = MIOLO::getInstance();

        $ui = $MIOLO->getUI();
        $report = $ui->getReport('example', 'repMultiLevel1');
        $report->generate();
    }

    public function btnRep8_click()
    {
        $MIOLO = MIOLO::getInstance();

        $ui = $MIOLO->getUI();
        $report = $ui->getReport('example', 'repMultiLevel2');
        $report->generate();
    }

    public function btnRep9_click()
    {
        $MIOLO = MIOLO::getInstance();

        $ui = $MIOLO->getUI();
        $report = $ui->getReport('example', 'repBand1');
        $report->generate();
    }
}
?>
