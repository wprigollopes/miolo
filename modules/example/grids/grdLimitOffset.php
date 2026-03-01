<?php

class grdLimitOffset extends MGrid
{
    public function __construct()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        // get the url of the current page
        $listURL = $MIOLO->getCurrentURL();
        // creates an curso object
        $objCurso = $MIOLO->getBusiness('example', 'curso');
        // execute the listAll of curso.class
        $queryCurso = $objCurso->listAll();
        // get the curso::nome
        $cursos = $queryCurso->chunkResult();
        // define the grid columns
        $columns = array(
            new MGridColumn(_M('Id', $module), 'right', TRUE, '10%', TRUE, NULL, TRUE, TRUE),
            new MGridColumn(_M('Name', $module), 'left', TRUE, '70%', TRUE, NULL, TRUE, TRUE),
            new MGridColumn(_M('Course', $module), 'left', TRUE, '20%', TRUE, $cursos, FALSE, TRUE)
        );

        parent::__construct(NULL, $columns, $listURL, 15);
        
        $this->setTitle(_M('Students', $module));


        $sql = "SELECT idaluno, nome, idcurso FROM aluno";
        $this->setQuery($sql, 'example');
    }
}
