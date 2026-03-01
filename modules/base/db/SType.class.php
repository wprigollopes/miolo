<?php
/**
 * Copyright 2005-2017 Solis Soluções Livres Ltda.
 *
 * This file is part of the SolisGE/Sagu program.
 *
 * SolisGE/Sagu is proprietary software of SOLIS, developed and maintained
 * exclusively by this company.
 *
 * The usage license is available through exclusive acquisition from SOLIS.
 * The license is granted on a non-exclusive basis to the licensee.
 * Usage rights are perpetual.
 *
 * Although source code is provided, the software is the property of SOLIS.
 * The licensee is not permitted to resell, lend, or transfer (whether for
 * payment or not) the license to third parties. It is also not permitted,
 * at any time or for any reason, to perform any alienation, reproduction,
 * distribution, disclosure, registration, licensing, transfer, or any other
 * act that may harm or compromise the software property rights, the name
 * and image of its owner and the software itself, or that constitutes
 * competition with SOLIS.
 *
 * The licensee, with access to the software source code, shall have the
 * right to make changes to the respective code. However, in situations
 * where the licensee relies on official support provided by SOLIS, changes
 * to the source code are not permitted, under penalty of losing said support.
 *
 * For detailed information about the SolisGE/Sagu Software Licensing Terms,
 * read the "LICENCA.txt" file included with this software.
 *
 *
 * Classe gerenciadora de types
 *
 *
 *
 *
 */

class SType extends BasLog
{
    /**
     * Atributo reservado MSubDetail
     *
     * @var <type>
     */
    protected $removeData;

    /**
     * Atributo que armazena itens ja populados sob-demanda,
     * util para ser utilizado no metodo __get() do type.
     */
    protected $_checkedPopulate = array();
    
    private $_aliases = array();

    
    public function __get($name)
    {
        $name = $this->checkName($name);

        return $this->$name;
    }


    public function __set($name, $value)
    {
        $name = $this->checkName($name);
        
        $this->$name = $value;
    }
    
    
    /**
     *
     * @param type $name
     * @return type 
     */
    private function checkName($name)
    {
        // Alias
        $alias = $this->_aliases[$name];
        if ( strlen($alias) > 0 )
        {
            $name = $alias;
        }
        
        return $name;
    }


    public function getObjectVars()
    {
        return get_object_vars($this);
    }
    
    
    /**
     * Define um pseudo atributo, que deve ser "redirecionado" para um outro atributo original.
     * Toda vez que for definido (Objeto->atributoAlias = 'valor') sera definido o valor para o atributo original.
     * Toda vez que for obtido (Objeto->atributoAlias) sera obtido o valor do atributo original.
     * Util para casos onde existam atributos na subdetail que sao diferentes do nome do atributo no SType.
     * 
     * Lembre-se que o pseudo atributo tambem deve estar declarado como protected na respectiva classe SType.
     * 
     * Exemplo pratico de uso na classe modules/training/types/TraTeam.class
     *
     * @param type $alias Pseudo nome de atributo
     * @param type $attribute Atributo fonte, que deve ser setado e obtido o valor
     */
    public function addAlias($alias, $attribute)
    {
        $this->_aliases[$alias] = $attribute;
    }


    /**
     * Verifica se deve popular dados sob demanda
     * Util para utilizar no __get() do type.
     *
     * CUIDADO: Ao utilizar esta funcao uma vez, o $name passado sera anotado como "ja populado"
     *
     * @param string $name
     */
    protected function needCheckPopulate($name)
    {
        $inArray = in_array($name, $this->_checkedPopulate);

        if ( !$inArray )
        {
            $this->_checkedPopulate[] = $name;
        }

        return ! $inArray;
    }
    
    /**
     * Obtem o nome da tabela, baseando-se no nome da classe por padrao.
     *
     * @return string
     */
    public function getTableName()
    {
        return get_class($this);
    }
    
    /**
     * Obtem ultimo id inserido
     */
    public function getLastInsertId()
    {
        return SDatabase::getLastInsertId($this->getTableName());
    }
    
    /**
     * Obtem nome da chave primaria
     */
    public function getPrimaryKey()
    {
        return SDatabase::getPrimaryKey($this->getTableName());
    }
}
