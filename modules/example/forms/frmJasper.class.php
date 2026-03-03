<?php

class frmJasper extends MForm
{
    public $fonts;

    public function __construct()
    {
        parent::__construct(_M('Using Jasper Reports', MIOLO::getCurrentModule()));
        $this->eventHandler();
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();
        $curso = $this->manager->getBusiness('example', 'curso');
        $query = $curso->listAll();

        $fields[] = new MLabel(_M('A Simple Report', $module), '', true);
        $fields[] = new MLabel(_M('Query', $module) . " SQL: select aluno.nome from aluno where (idcurso = [param1])", true);
        $fields[] = new MSelection('idcurso', '', 'Param1', $query->chunkResult());
        $fields[] = new MButton('btnRep', _M('Generate Sample Report', $module));

        $fields[] = new MSpacer('20px');

        $fields[] = new MLabel(_M('A Report with SubReport', $module), '', true);
        $fields[] = new MButton('btnSubRep', _M('Generate Report with Subreport', $module));

        $fields[] = new MSpacer('10px');

        $this->setFields($fields);
        $this->defaultButton = false;
    }

    public function btnRep_click()
    {
        $parameters = array();
        $report = new MJasperReport('example');
        $parameters['int_id'] = $this->getFieldValue('idcurso');
        $parameters['str_description'] = _M('Test');
        $report->execute('example', 'sample', $parameters);
    }

    public function btnSubRep_click()
    {
        $report = new MJasperReport('example');
        $parameters['int_id'] = $this->getFieldValue('idcurso');
        $parameters['str_description'] = _M('Test');
        $report->execute('example', 'samplesub', $parameters);
    }
}

?>