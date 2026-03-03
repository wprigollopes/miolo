
/**
 * Business que repesenta a tabela %TABLE%.
 *
 * @author %AUTHOR%
 *
 * \b Maintainers: \n
 * %AUTHOR%
 *
 * @since
 * Creation date %CURRENT_DATE%
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) %CURRENT_YEAR% SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 */
class Business%MODULE_UCFIRST%%TABLE_CLASS_NAME% extends MBusiness
{
    %BUSINESS_ATTRIBUTES%

    /**
 * Construtor do business
 *
 * @param object $data Objeto do tipo stdClass para popular esta classe.
 */
    public function __construct($dados=NULL)
    {
       parent::__construct('%DATABASE%');

       if ( $dados )
       {
           %BUSINESS_POPULATE_ATTRIBUTES%
       }
    }

    %BUSINESS_ATTRIBUTES_METHODS%

    /**
 * Inserir o registro na tabela.
 *
 * @return boolean Retorna true se o registro foi inserido.
 */
    public function insert()
    {
        $msql = new MSQL('%TABLE_INSERT_COLUMNS%', '%TABLE%');
        $sql = $msql->insert(array( %BUSINESS_INSERT_COLUMNS% ));
        return $this->getDb()->execute($sql);
    }

    /**
 * Atualizar o registro na tabela.
 *
 * @return boolean Retorna true se o registro foi atualizado.
 */
    public function update()
    {
        if ( %TYPE_CHECK_PRIMARY_KEYS% )
        {
            return false;
        }
        $msql = new MSQL('%TABLE_UPDATE_COLUMNS%', '%TABLE%', '%WHERE_PRIMARY_KEYS%');
        $msql->setParameters(array(%BUSINESS_PKEYS_PARAMETERS%));
        $sql = $msql->update(array(%BUSINESS_UPDATE_DATA%));
        return $this->getDb()->execute($sql);
    }

    /**
 * Remover o registro da tabela.
 *
 * @return boolean Retorna true se o registro foi removido.
 */
    public function delete()
    {
        if ( %TYPE_CHECK_PRIMARY_KEYS% )
        {
            return false;
        }
        $msql = new MSQL(NULL, '%TABLE%', '%WHERE_PRIMARY_KEYS%');
        $msql->setParameters(array(%BUSINESS_PKEYS_PARAMETERS%));
        $sql = $msql->delete();
        return $this->getDb()->execute($sql);
    }

    /**
 * Buscar na tabela.
 *
 * @param object $filters Objeto to tipo stdClass para filtrar a busca.
 * @return array Resultado da busca em um array bidimensional.
 */
    public function search($filters=NULL, $columns='*')
    {
        $msql = new MSQL($columns, '%TABLE%');
        %BUSINESS_SEARCH_FILTERS%

        $query = $this->getDb()->getQuery($msql);
        return $query->result;
    }
}
