<?
class BusinessExampleLookup
{
    public function LookupAluno(&$lookup)
    {
        $module = MIOLO::getCurrentModule();
        $idaluno = $lookup->getFilterValue('idaluno');
        $nome = $lookup->getFilterValue('nome');

        $lookup->addFilterField( new MTextField('idaluno', $idaluno,_M('Id', $module), 10));
        $lookup->addFilterField( new MTextField('nome', $nome,_M('Student', $module), 45));

        $columns[] = new MDataGridColumn('idaluno','Id','right', true,'5%',true);
        $columns[] = new MDataGridColumn('nome','Transaction','left', true,'95%',true);

        $sql = new MSQL("a.idaluno,a.nome,c.nome as curso","aluno a, curso c", "(a.idcurso = c.idcurso)",'a.nome');

        if ( $idaluno )
        {
            $sql->where .= " and ( a.idaluno = '{$idaluno}' )";
        }
        if ( $nome )
        {
            $sql->where .= " and ( upper(a.nome) like upper('{$nome}%') )";
        }

        $lookup->setGrid('example', $sql, $columns, 'Lookup Alunos', 15, 0);
    }

    public function AutoCompleteAluno(&$lookup)
    {
        $MIOLO = MIOLO::getInstance();

        $filter = $lookup->getFilterValue();

        $aluno = $MIOLO->getBusiness('example', 'aluno');

        $criteria = $aluno->getCriteria();
        $criteria->addColumnAttribute('idAluno');
        $criteria->addColumnAttribute('nome');
        $criteria->addCriteria('idAluno', '=', "$filter");

        $query = $criteria->retrieveAsQuery();
        $lookup->setAutoComplete($query->result[0]);
        return $query->result[0];
    }

	function LookupObjAluno(&$lookup)
    {
        $MIOLO = MIOLO::getInstance();
        $filter = MUtil::NVL($lookup->GetFilterValue('filter'), $lookup->GetFilterValue());
        $lookup->AddFilterField( new MTextField('filter', $filter,'Aluno', 45));
        $columns = array(
           new MObjectGridColumn('idAluno','Id','right', true,'5%',true),
           new MObjectGridColumn('nome','Nome','left', true,'95%',true),
        );
        $aluno = $MIOLO->getBusiness('example','aluno');
        $criteria = $aluno->getCriteria();
        $criteria->addColumnAttribute('idAluno');
        $criteria->addColumnAttribute('nome');
        $criteria->addCriteria('upper(nome)','LIKE',"upper('{$filter}%')");
        $criteria->addOrderAttribute('nome');
        $cursor = $criteria->retrieveAsCursor();
        $lookup->setCursorGrid($cursor,$columns, 'Lookup Alunos',15,0);
    }

	function LookupQueryAluno(&$lookup)
    {
        $MIOLO = MIOLO::getInstance();
        $filter = MUtil::NVL($lookup->GetFilterValue('filter'), $lookup->GetFilterValue());
        $lookup->AddFilterField( new MTextField('filter', $filter,'Aluno', 45));
        $columns = array(
           new MDataGridColumn('idAluno','Id','right', true,'5%',true),
           new MDataGridColumn('nome','Nome','left', true,'95%',true),
        );
        $aluno = $MIOLO->getBusiness('example','aluno');
        $query = $aluno->listAll();
        $lookup->setQueryGrid($query,$columns, 'Lookup Alunos',15,0);
    }

	function LookupCursoAluno(&$lookup)
    {   
        $MIOLO = MIOLO::getInstance();
 
        // initialize the filter values
        // "filter0" and "filter1" are values passed by the form
        $fCurso = MUtil::NVL($lookup->GetFilterValue('filter0'), $lookup->GetFilterValue());
        $fAluno = MUtil::NVL($lookup->GetFilterValue('filter1'), '');

        // get data to fill the mselection filter control
        $curso = $MIOLO->GetBusiness('example','curso');
        $query = $curso->listAll();

        // create the controls to build a filter
        $lookup->AddFilterField( new MSelection('filter0', $fCurso,'Curso', $query->result));
        $lookup->AddFilterField( new MTextField('filter1', $fAluno,'Aluno', 30));

        // define the columns of a MDataGrid to show the results of lookup
        $columns = array(
           new MDataGridColumn('idaluno','Id','right', true, '8%',true),
           new MDataGridColumn('nome','Nome','left', true, '50%',true),
           new MDataGridColumn('idcurso','IdCurso','left', true, '8%',true),
           new MDataGridColumn('curso','Curso','left', true, '36%',true),
        );

        // create a MSQL to query database
        $sql = new MSQL("a.idaluno,a.nome,c.idcurso,c.nome as curso","aluno a, curso c", "(a.idcurso = c.idcurso)",'a.nome');
        if ( $fAluno )
        {
            $sql->where .= " and ( upper(a.nome) like upper('{$fAluno}%') )";
        }
        $sql->where .= " and ( c.idcurso = '{$fCurso}' )";

        // $lookup is a MLookup object
        $lookup->setGrid('example',$sql,$columns,'Lookup Curso/Alunos',15,0);
    }

    public function AutoCompleteAlunoId(&$lookup)
    {   
        // $lookup is a MLookup object
        $MIOLO = MIOLO::getInstance();

        $fAluno = $lookup->getFilterValue();
        $fCurso = $lookup->getFilterValue('course');

        $msql = new MSQL('aluno.nome, curso.nome', 'aluno, curso', '(aluno.idcurso = curso.idcurso)');
        $msql->setWhereAnd("aluno.idAluno = {$fAluno}");
        if ( $fCurso != NULL )
        {
            $msql->setWhereAnd("curso.idCurso = {$fCurso}");
        }
        $query = $lookup->getQuery('example', $msql);

        $lookup->setAutoComplete($query->result[0]);
    }

}
?>