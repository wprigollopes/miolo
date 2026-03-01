<?php

class frmDOMPDF extends MForm
{
    public function __construct()
    {
        parent::__construct('Using DOMPDF Reports');
        $this->eventHandler();
    }

    public function createFields()
    {
        $fields = array(
            new MButton('btnRep1', 'Gerar Report DOMPDF - Simple Labels', 'PDF'),
            new MSeparator(),
            new MButton('btnRep2', 'Gerar Report DOMPDF - documento com Fontes', 'PDF'),
            new MSeparator(),
            new MButton('btnRep3', 'Gerar Report DOMPDF - TableRaw', 'PDF'),
            new MSeparator(),
            new MButton('btnRep4', 'Gerar Report DOMPDF - Div Table', 'PDF'),
            new MSeparator(),
            new MButton('btnRep5', 'Gerar Report DOMPDF - Html File', 'PDF'),
            new MSeparator(),
            new MButton('btnRep6', 'Gerar Report DOMPDF - Html Report', 'PDF'),
            new MSeparator(),
            new MButton('btnRep7', 'Gerar Report MDOMPDF + MBTemplate', 'PDF'),
            new MSeparator(),
            new MButton('btnRep8', 'Gerar Pure HTML Report with MBTemplate', 'PDF'),
            new MSpacer('10px')
        );
        $this->setFields($fields);
        $this->defaultButton = false;
    }

    public function btnRep1_click()
    {
        $MIOLO = MIOLO::getInstance();

        $ui = $MIOLO->getUI();
        $report = $ui->getReport('example', 'repDOMPDF1');
        $report->generate();
    }

    public function btnRep2_click()
    {
        $MIOLO = MIOLO::getInstance();
        $ui = $MIOLO->getUI();
        $report = $ui->getReport('example', 'repDOMPDF2');
        $report->generate();
    }

    public function btnRep3_click()
    {
        $MIOLO = MIOLO::getInstance();
        $ui = $MIOLO->getUI();
        $report = $ui->getReport('example', 'repDOMPDF3');
        $report->generate();
    }

    public function btnRep4_click()
    {
        $MIOLO = MIOLO::getInstance();
        $ui = $MIOLO->getUI();
        $report = $ui->getReport('example', 'repDOMPDF4');
        $report->generate();
    }

    public function btnRep5_click()
    {
        $report = new MDOMPDFreport();
        $fileName = $this->manager->getModulePath('example', 'html/files/dompdf.html');

        $params = new stdClass();
        $params->uo = 'FAC SERVIÃO SOCIAL';
        $params->numero = '1884';
        $params->empresa = 'UTIL TRANSPORTES LTDA';
        $params->passagens = '02';
        $params->rubrica = 'X49033';
        $params->origem = 'RIO DE JANEIRO';
        $params->destino = 'JUIZ DE FORA';
        $params->tipo = '1';
        $params->usuario = 'FULANO DE TAL';
        $params->identidade = 'MG-XXXXXX SSP-MG';

        $report->generateFile($fileName, $params);
    }

    public function btnRep6_click()
    {
        $this->manager->profileEnter('frmDOMPDF::btnRep6');
        $report = new MDOMPDFReport();
        $report->setPaper('portrait', 'a4');
        $fileName = $this->manager->getModulePath('example', 'reports/repTemplate1.html');
        $aluno = $this->manager->getBusiness('example', 'aluno');
        $query = $aluno->listAlunoCurso();
        $params = new stdClass();
        $rowsCount = count($query->result);
        $rowsPage = 42;
        $numPages = ceil($rowsCount / $rowsPage);
        $i = 0;
        while ( $i < $rowsCount )
        {
            $l = 0;
            $rows = '';
            while ( ($i < $rowsCount) && ($l < $rowsPage) )
            {
                $rowClass = 'row' . ($i % 2 ? '1' : '0');
                $rows .= "<tr>";
                for ( $c = 1; $c < 5; $c++ )
                {
                    $rows .= "<td class=\"$rowClass col{$c}\">{$query->result[$i][$c - 1]}</td>";
                }
                $rows .= "</tr>";
                $i++;
                $l++;
            }
            $r[] = $rows;
        }
        $params->rows = array_slice($r, 0, 2);
        $report->generateFile($fileName, $params);
        $this->manager->profileExit('frmDOMPDF::btnRep6');
    }

    public function btnRep7_click()
    {
        $pathName = $this->manager->getModulePath('example', 'reports/');
        $aluno = $this->manager->getBusiness('example', 'aluno');
        $query = $aluno->listAlunoCurso();
        $params = new stdClass();
        $rowsCount = count($query->result);
        $rowsPage = 42;
        $numPages = ceil($rowsCount / $rowsPage);
        $i = 0;
        while ( $i < $rowsCount )
        {
            $l = 0;
            $rows = '';
            while ( ($i < $rowsCount) && ($l < $rowsPage) )
            {
                $rowClass = 'row' . ($i % 2 ? '1' : '0');
                $rows .= "<tr>";
                for ( $c = 1; $c < 5; $c++ )
                {
                    $content = trim($query->result[$i][$c - 1]);
                    $rows .= "<td class=\"$rowClass col{$c}\">{$content}</td>";
                }
                $rows .= "</tr>";
                $i++;
                $l++;
            }
            $r[] = $rows;
        }
        $template = new MBTemplate($pathName);
        $template->set('data', $r);
        $template->set('logo', $this->manager->getUI()->getImage('miolo', 'icon_siga.png'));
        $template->set('timestamp', $this->manager->getSysTime());
        $html = $template->fetch('repTemplate2.html');

        $report = new MDOMPDF('a4', 'portrait');
        $report->setInput($html);
        $report->execute();
    }

    public function btnRep8_click()
    {
        $aluno = $this->manager->getBusiness('example', 'aluno');
        $query = $aluno->listAlunoCurso();
        $pathName = $this->manager->getModulePath('example', 'reports/');
        $template = new MBTemplate($pathName);
        $template->set('data', $query->result);
        $template->set('rowsPerPage', 42);
        $template->set('logo', $this->manager->getUI()->getImage('miolo', 'icon_siga.png'));
        $template->set('timestamp', $this->manager->getSysTime());
        $template->execute('repTemplate3.html');
    }
}
