<?
    class repDOMPDF3 extends MDOMPDFReport
    {
        public function createFields()
        {
            $aluno = $this->manager->getBusiness('tutorial','aluno');
            $query = $aluno->listAll();
            $table = new MTableRaw('RelaÃ§Ã£o de Alunos',$query->result);
            $table->setAlternate(true);
            $table->table->setAttributes('cellspacing=1 width=80% cellpadding=3 border=0 align=center'); 
            $fields[] = $table;
            $this->setFields($fields); 
        }
    }
?>
