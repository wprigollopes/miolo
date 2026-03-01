<?php

class pessoa extends bType
{
    protected $ordenacaoPadrao = 'basperson.name';
    
    public function __construct()
    {
        parent::__construct('basperson');
    }
}
