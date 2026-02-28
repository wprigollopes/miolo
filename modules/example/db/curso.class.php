<?php

class BusinessExampleCurso extends MBusiness
{
    public $idCurso;
    public $nome;
    public $idSala;
    public $sala;

    public function __construct($data=NULL)
    {
       parent::__construct('example',$data);
    }

    public function getById($idCurso)
    {
       $this->idCurso = $idCurso;
       $this->retrieve();
       return $this;
    }

    public function getData()
	{
		$data = new stdClass();
		$data->idCurso = $this->idCurso;
		$data->nome = utf8_encode($this->nome);
		$data->idSala = $this->idSala;
		$data->sala = $this->sala->descricao;
		return $data;
	}

    public function listAll()
    {
        $criteria = $this->getCriteria();
        $query = $criteria->retrieveAsQuery();
        return $query;
    }

    public function listByNome($nome)
    {
        $criteria = $this->getCriteria();
        $criteria->addCriteria('nome','LIKE', "'$nome%'");
        $query = $criteria->retrieveAsQuery();
        return $query;
    }

    public function listAlunos($nome = '')
    {
        $criteria = $this->getCriteria();
        $criteria->addColumnAttribute('alunos.nome');
        $criteria->addColumnAttribute('alunos.idAluno');
        $criteria->addCriteria('alunos.nome','LIKE', "'$nome%'");
        $criteria->addCriteria('idCurso','=', "{$this->idCurso}");
        $query = $criteria->retrieveAsQuery();
        return $query;
    }

}
?>
