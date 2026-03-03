<?
    class repDOMPDF4 extends MDOMPDFReport
    {
        public function createFields()
        {
            $aluno = $this->manager->getBusiness('tutorial','aluno');
            $query = $aluno->listAll();
            foreach($query->result as $row)
            {
                $s1 = new MSpan('',$row[1]);
                $s1->fontFamily = 'Verdana'; 
                $r = new MDiv('', $s1);
                $r->backgroundColor = ($i++)%2 ? '#CCC' : '#FFF';
                $r->width = '80%';
                $r->addBoxStyle('margin-left','auto');
                $r->addBoxStyle('margin-right','auto');
                $fields[] = $r;
            }
            $this->setFields($fields); 
        }
    }
?>
