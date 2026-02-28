<?php
/**
 * Copyright 2005-2017 de Solis Soluções Livres Ltda.
 *
 * Este arquivo é parte do programa SolisGE/Sagu.
 *
 * O SolisGE/Sagu é um software de propriedade da SOLIS, sendo desenvolvido
 * e mantido exclusivamente por esta empresa.
 *
 * A licença de uso está disponível mediante aquisição exclusiva junto à
 * SOLIS. A licença é concedida sem caráter de exclusividade ao licenciado.
 * Os direitos de uso são perpétuos.
 *
 * Embora os códigos fontes sejam fornecidos, o software é de propriedade
 * da SOLIS, não sendo permitido ao adquirente da licença a sua revenda,
 * empréstimo ou cessão (onerosa ou não) à terceiros. Também não é permitido,
 * a qualquer título e tempo, promover no software qualquer tipo de alienação,
 * reprodução, distribuição, divulgação, registro, licenciamento, transferência
 * ou qualquer outro ato que prejudique ou comprometa os direitos de propriedade
 * de software, o nome e a imagem da sua proprietária e do próprio software,
 * além de configurar concorrência à SOLIS.
 *
 * O licenciado, com o acesso ao código fonte do software, terá o direito de
 * promover mudanças no respectivo código. No entanto, nas situações em que ele
 * contar com o suporte oficial prestado pela SOLIS, não poderá promover mudanças
 * no código fonte, sob pena de perda do referido suporte.
 *
 * Para conhecer em detalhes o Termo de Licenciamento do Software SolisGE/Sagu
 * leia o arquivo “LICENCA.txt” disponível junto ao código deste software.
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
?>