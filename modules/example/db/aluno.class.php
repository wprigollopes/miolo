<?php

class BusinessExampleAluno extends MBusiness
{
    public $idAluno;
    public $nome;
    public $sexo;
    public $telefone;
    public $idCurso;
    public $curso;

    public function __construct($data=NULL)
    {
       parent::__construct('example',$data);
    }

    public function getById($idAluno)
    {
       $this->idAluno = $idAluno;
       $this->retrieve();
       return $this;
    }

    public function getCurso()
    {
       $this->retrieveAssociation('curso');
       return $this;
    }

    public function listAll()
    {
        $criteria = $this->getCriteria();
        $query = $criteria->retrieveAsQuery();
        return $query;
    }

    public function listAlunoCurso()
    {
        $criteria = $this->getCriteria();
        $criteria->addColumnAttribute('nome');
        $criteria->addColumnAttribute('sexo');
        $criteria->addColumnAttribute('telefone');
        $criteria->addColumnAttribute('curso.nome');
        $criteria->addOrderAttribute('nome');
        $query = $criteria->retrieveAsQuery();
        return $query;
    }

    public function listAttributes($attributes, $orderby = '')
    {
        $array = explode(',',$attributes); 
        $criteria = $this->getCriteria();
        foreach($array as $a)
        {
            $criteria->addColumnAttribute($a);
        }
        if ($orderby)
        {
            $criteria->addOrderAttribute($orderby);
        }
        $query = $criteria->retrieveAsQuery();
        return $query;
    }
}
?>