<?php

class grdList1 extends MGrid
{
    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        // get the url of the current page
        $listURL = $MIOLO->getCurrentURL();
        // creates an aluno object
        $objAluno = $MIOLO->getBusiness('example', 'aluno');
        // execute the list of aluno.class
        $query = $objAluno->listAttributes('idAluno,nome,idCurso');
        // creates an curso object
        $objCurso = $MIOLO->getBusiness('example', 'curso');
        // execute the listAll of curso.class
        $queryCurso = $objCurso->listAll();
        // get the curso::nome
        $cursos = $queryCurso->chunkResult();
        // define the grid columns
        $columns = array( 
            $i = new MGridColumn(_M('Id', $module), 'right', TRUE, '10%', TRUE, NULL, TRUE, TRUE),
            new MGridColumn(_M('Name', $module), 'left', TRUE, '70%', TRUE, NULL, TRUE, TRUE),
            new MGridColumn(_M('Course', $module), 'left', TRUE, '20%', TRUE, $cursos, FALSE, TRUE)
        );

        // call the parent's constructor
        parent::__construct($query->result, $columns, $listURL, 15, 0);

        // set the grid title
        $this->setTitle(_M('Students', $module));

        $this->setRowMethod('grdList1', 'generateTooltip');
    }

    /**
     * Generate tooltip for each line
     *
     * @param integer $i Line index
     * @param array $row Row data
     * @param array $actions Action data
     * @param array $columns Column data
     */
    public function generateTooltip($i, $row, $actions, $columns)
    {
        // Create a span with an unique id
        $spanId = "gridRow$i";
        $columns[0]->control[$i] = new MSpan($spanId, $row[0]);

        // Use the second column value as tooltip
        $tooltip = htmlentities($row[1], ENT_QUOTES);

        $js = <<<JS
new dijit.Tooltip({
    // Two parentNode to achieve the line element (tr)
    connectId: [dojo.byId('$spanId').parentNode.parentNode],
    label: '$tooltip'
});
JS;

        $this->page->onload($js);
    }
}
