<?php

class BusinessExampleSala extends MBusiness
{
    public $idSala;
    public $descricao;

    public function __construct($data=NULL)
    {
       parent::__construct('example',$data);
    }

    public function getById($idCurso)
    {
       $this->idSala = $idCurso;
       $this->retrieve();
       return $this;
    }

    public function listAll()
    {
        $criteria = $this->getCriteria();
        $query = $criteria->retrieveAsQuery();
        return $query;
    }
}
