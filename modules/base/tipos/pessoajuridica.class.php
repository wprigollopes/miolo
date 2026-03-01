<?php

class pessoajuridica extends bType
{
    protected $ordenacaoPadrao = 'baslegalperson.name';
    
    public function __construct()
    {
        parent::__construct('baslegalperson');
    }    
}

?>
