<?php

class frmTextTable extends MForm
{
    function __construct()
    {
        parent::__construct('MTextTable');
        $this->eventHandler();
        // creates a link to view the source
        $this->addField( new ViewSource( __FILE__ ) );
    }

    public function createFields()
    {
        $module = MIOLO::getCurrentModule();

        $data[] = array( '1200', 'Unicode', '(BMP of ISO/IEC-10646)' );
        $data[] = array( '1250', 'Windows 3.1', 'Eastern European' );
        $data[] = array( '1251', 'Windows 3.1', 'Cyrillic' );
        $data[] = array( '1252', 'Windows 3.1', 'US (ANSI)' );
        $data[] = array( '1253', 'Windows 3.1', 'Greek' );
        $data[] = array( '1254', 'Windows 3.1', 'Turkish' );
        $data[] = array( '1255', 'Hebrew', 'X' );
        $data[] = array( '1256', 'Arabic', 'X' );
        $data[] = array( '1257', 'Baltic', 'X' );
        $data[] = array( '1361', 'Korean', '(Johab)' );

        $select = $this->manager->getActionURL('example', 'controls:texttable', '%0%', array( 'event' => 'btnSelect:click' ));
        $tbl = new MTextTable('tt0', $data, _M('Encodings', $module), $select);
        $tbl->setScrollHeight('80px');
        $tbl->setScrollWidth('300px');

        $selectAluno = $this->manager->getActionURL('example', 'controls:texttable', '', array( 'event' => 'btnSelectAluno:click;%0%' ));
//       $selectAluno = $this->manager->getActionURL('example','controls:texttable');
        $aluno = $this->manager->getBusiness('example', 'aluno');
        $query = $aluno->listAll();
        $tblData = new MTextTable('tdata', $query->result, _M('Students', $module), $selectAluno);
        $tblData->setTitle(array(
            _M('Id', $module).':30',
            _M('Name', $module).':250',
            _M('Gender', $module).':35',
            _M('Phone', $module).':50',
            _M('Room', $module).':'
        ));
        $tblData->setScrollHeight('120px');
        $tblData->setScrollWidth('430px');
        $tableId = $tblData->getTableId();
        $formId = $this->page->getFormId();

        // o codigo javascript abaixo mostra como executar uma função quando uma linha é selecionada
        // a função usa a url $selectAluno para chamar o método btnSelectAluno_click
        // $tableId : id do elemento table
        // customSelect : método executado quando uma linha é selecionada
        $tblData->addCode("miolo.page.controls.get('{$tableId}').customSelect = function() { " .
                // row : linha que foi selecionada
                "   var row = miolo.getElementById(miolo.page.controls.get('{$tableId}').rowSelected); " .
                // cols : colunas (elementos TD) da linha selecionada
//          "   var cols = xGetElementsByTagName('TD',row); ".
                "   var cols = dojo.query('TD',row); " .
                // url : url que serÃ¡ chamada
                "   url = '{$selectAluno}'; " .
                // troca o %0% pelo valor da coluna 1 (idAaluno)
//          "   url = url.replace( '%0%', cols[1].firstChild.nodeValue); ".
                // chama a url
                "   miolo.doPostBack('btnSelectAluno:click',cols[1].firstChild.nodeValue,'{$formId}'); " .
                "};");

        $k = 0;
        $src = $this->manager->getUI()->getIcon('edit-on');
        foreach ( $query->result as $row )
        {
            $l = new MLinkButton('', $row[1], str_replace('%0%', $row[0], $selectAluno));
            $l->setClass('m-link-normal', false);
            $edit = new MImageLink('', '', "javascript:alert({$row[0]});", $src);
            $r[$k][0] = $edit->generate();
            $r[$k++][1] = $l->generate();
        }

        $tblLink = new MTextTable('tlink', $r, _M('Students (with link)', $module));
        $tblLink->setScrollHeight('120px');
        $tblLink->setScrollWidth('350px');

        $fields = array(
            $tbl,
            new MSpacer('15px'),
            $tblData,
            new MSpacer('15px'),
            $tblLink,
            new MSpacer('15px'),
        );
        $this->setFields($fields);
        $buttons = array(
            new MBackButton(),
            new MButton('btnPost', _M('Send', $module)),
        );
        $this->setButtons($buttons);
    }

    public function btnSelect_click()
    {
        global $item;
        $module = MIOLO::getCurrentModule();
        $this->addField(new MLabel(_M('Selected item', $module).": $item"));
    }

    public function btnSelectAluno_click($id)
    {
        $module = MIOLO::getCurrentModule();
        $aluno = $this->manager->getBusiness('example', 'aluno', $id);
        $this->addField(new MLabel(_M('Selected student', $module) . ': ' . $aluno->nome));
    }

    public function btnPost_click()
    {
        // obtem o valor da controle MTextTable com um array
        $data = $this->getFieldValue('tt0');
        $this->addField(new MTableRaw('', $data));
    }
}
?>
