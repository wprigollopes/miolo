<?php

class frmLookup extends controlForm
{
    public function __construct()
    {
        parent::__construct(_M('Lookup Sample', MIOLO::getCurrentModule()), __FILE__);
    }

    public function createFields()
    {
        parent::createFields();

        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $curso = $MIOLO->getBusiness('example', 'curso');
        $query = $curso->listAll();

        $fields[] = new MLookupContainer('test', NULL, _M('Test'), 'example', 'aluno', false, 'hint ');

        $fields[] = new MHiddenField('key', '');
        $fields[] = new MSeparator(_M('@1 with 2 fields for lookup with filler', $module, 'MLookupField'));

        $controls[] = new MSelection('selField', '', _M('Course', $module), $query->result);
        $controls[] = new MTextField('txtField', '', _M('Student', $module), 45);
        $controls[] = new MLookupField('lkpField', '&nbsp;', '&nbsp;', '', 'none,txtField,selField,none', 'example', 'cursoaluno', 'filler', 'selField,txtField');
        $fields[] = new MHContainer(NULL, $controls);


        $fields[] = new MSpacer('15px');
        $fields[] = new MSeparator(_M('@1 with autocomplete and filter', $module, 'MLookupTextField'));

        $fields[] = new MSelection('courseFilter', '', _M('Course', $module), $query->result);
        $filter['course'] = 'courseFilter';
        $fields['lkpAlunoId'] = new MLookupTextField('lkpAlunoId', '', _M('Student ID', $module), 5);
        $fields['lkpAlunoId']->setContext('example', 'example', 'alunoid', 'filler', 'lkpAlunoIdNome,lkpAlunoIdCurso', $filter, true);

        $fields[] = new MTextField('lkpAlunoIdNome', '', _M('Student', $module), 45);
        $fields[] = new MTextField('lkpAlunoIdCurso', '', _M('Course', $module), 45);


        $fields[] = new MSpacer('15px');
        $fields[] = new MSeparator(_M('@1 with filler', $module, 'MLookupTextField'));

        $fields['lkpAluno'] = new MLookupTextField('lkpAluno', '', _M('Student', $module), 45);
        $fields['lkpAluno']->setContext('example', 'example', 'aluno', 'filler', 'key,lkpAluno,lkpAlunoCurso');
        $fields[] = new MTextField('lkpAlunoCurso', '', _M('Course', $module), 45);


        $fields[] = new MSpacer('15px');
        $fields[] = new MSeparator(_M('@1 using cursor with filler', $module, 'MLookupTextField'));

        $fields['lkpAlunoObj'] = new MLookupTextField('lkpAlunoObj', '', _M('Student', $module), 45);
        $fields['lkpAlunoObj']->setContext('example', 'example', 'objaluno', 'filler', 'key,lkpAlunoObj');


        $fields[] = new MSpacer('15px');
        $fields[] = new MSeparator(_M('@1 using query with filler', $module, 'MLookupTextField'));

        $fields['lkpAlunoQuery'] = new MLookupTextField('lkpAlunoQuery', '', _M('Student', $module), 45);
        $fields['lkpAlunoQuery']->setContext('example', 'example', 'queryaluno', 'filler', 'key,lkpAlunoQuery');


        $fields[] = new MSpacer('15px');
        $fields[] = new MSeparator(_M('@1 with event', $module, 'MLookupTextField'));

        $fields['lkpAlunoEvent'] = new MLookupTextField('lkpAlunoEvent', '', _M('Student', $module), 45);
        $fields['lkpAlunoEvent']->setContext('example', 'example', 'aluno', 'btnShow:click');
        $fields[] = new MDiv('divAlunoId', NULL);


        $fields[] = new MSpacer('15px');
        $fields[] = new MSeparator(_M('@1 with filler', $module, 'MLookupFieldValue'));

        $fields['lkpFieldTest'] = new MLookupFieldValue('lkpFieldTest', '', _M('Student', $module), 45);
        $fields['lkpFieldTest']->setContext('example', 'example', 'aluno', 'filler', 'key,lkpFieldTest', 'lkpFieldTest');

        $this->addFields($fields);
    }

    public function btnShow_click($key = '')
    {
        $aluno = $this->manager->getBusiness('example', 'aluno', $key);
        $this->setResponse(new MLabel($key . ' - ' . $aluno->nome, NULL, true), 'divAlunoId');
    }
}
