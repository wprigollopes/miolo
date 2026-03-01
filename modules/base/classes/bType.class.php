<?php

/**
 * <--- Copyright 2012 Solis - Cooperativa de Soluções Livres Ltda.
 *
 * This file is part of the Base program.
 *
 * Base is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation (FSF); version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License/GPL
 * for more details.
 *
 * You should have received a copy of the GNU General Public License, under
 * the title "LICENCA.txt", along with this program. If not, visit the
 * Brazilian Public Software Portal at www.softwarepublico.gov.br or write
 * to the Free Software Foundation (FSF) Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301, USA --->
 *
 *  Class that manipulates and represents a database table.
 *          
 * @author Jader Osvino Fiegenbaum [jader@solis.coop.br]
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Class created on 25/06/2012
 */
class bType
{
    /**
     * For user log records.
     * 
     * @var String
     */
    public $username;
    
    /**
     * For alteration/creation date log records. 
     * 
     * @var String
     */
    public $datetime;
    
    /**
     * For IP log records.
     * 
     * @var String;
     */
    public $ipadress;

    /**
     * @var Array Column names and primary key of the table.
     */
    protected $colunas;

    /**
     * @var string Table name
     */
    protected $tabela;

    /**
     * @var string $funcao Form function. 
     */
    protected $funcao;

    /**
     * @var array $estruturaTabela Array with the table structure.
     */
    protected $estruturaTabela = array( );

    /**
     * @var array $chavesPrimarias Array with the primary keys of the table. 
     */
    protected $chavesPrimarias = array( );

    /**
     * @var string $esquema Schema where the table is located. 
     */
    protected $esquema = 'public';

    /**
     * @var array $tiposRelacionados Array with the names of types that are related to the main type.
     */
    protected $tiposRelacionados = array( );

    /**
     * @var array $dadosTiposRelacionados Array with the related types.
     */
    protected $dadosTiposRelacionados = array( );

    /**
     * @var string $status Status of the type. 
     */
    public $status;
    
    /**
     * @var String Table comment. 
     */
    private $comentarioDaTabela;
    
    /**
     * @var String Descriptive column of the table
     */
    protected $colunaDescritiva;
    
    /**
     * @var Array Foreign column values
     */
    private $valorDaColunaEstrangeira;
    
    /**
     * @var Array Relationships of this table
     */
    private $relacionamentos;
    
    /**
     * @var String Default table ordering
     */
    protected $ordenacaoPadrao;
    
    /**
     * @var String Default mask for timestamp fields
     */
    protected $mascaraTimeStamp = 'DD/MM/YYYY HH24:MI';

    /**
     * Class constructor.
     *
     * @param FormData $dados Form data.
     * @param boolean $popular Indicates whether the class should be populated with database data.
     */
    public function __construct($chave = null)
    {
        if ( !$chave )
        {
            $chave = get_class($this);
        }
        
        // Ticket #38602 - adjusting schema
        $esquema = bCatalogo::listarEsquemas($chave);
        $this->esquema = (strlen($esquema[0][0]) > 0 ) ? $esquema[0][0] : 'public';
        
        $this->tabela = $chave;
        $this->definirEstruturaDaTabela();
        $this->definirComentarioDaTabela();
        $this->definirRelacionamentos();
    }
    
    /**
     * Returns the table name that the grid is using
     * 
     * @return string
     */
    public function getTabela()
    {
        return $this->tabela; 
    }
    
    /**
     *
     * @return string
     */
    public function obterValorColuna($coluna)
    {
        return $this->$coluna;
    }
    
    public function definirValorColuna($coluna, $valor)
    {
        $this->$coluna = $valor;
    }
    
    /**
     *
     * @return boolean
     */
    public function valorFoiDefinido($coluna)
    {
        return isset($this->$coluna);
    }

    /**
     * Method to search records in the database.
     * 
     * @param stdClass $filtros Filters for the search condition.
     * @param string $colunas Columns to search in.
     * @return array of objects Array with the results. 
     */
    public function buscar($filtros = NULL, $colunas = NULL)
    {
        $sql = $this->obterConsulta($filtros, $colunas);

        // Performs the search in the database.
        $resultado = bBaseDeDados::obterInstancia()->_db->query($sql, NULL, NULL, PostgresQuery::FETCH_OBJ);
        
        return is_array($resultado) ? $resultado : $resultado->result;
    }
    
    private function obterObjetoDeConsultaDescritiva($filtros=NULL, $colunas=NULL)
    {
        if ( !$colunas )
        {
            $colunaDescritiva = $this->obterColunaDescritiva() ? $this->obterColunaDescritiva() : $this->colunas[1];
            $colunas = $this->colunas[0] . ','. $colunaDescritiva;
        }
        
        $sql = $this->obterConsulta($filtros, $colunas);
        
        return $sql;
    }
    
    /**
     * Method to search results for a selection field.
     * 
     * @param stdClass $filtros Filters for the search condition.
     * @param string $colunas Columns to search in.
     * @return array Array with values.  
     */
    public function buscarParaSelection($filtros=NULL, $colunas=NULL)
    {
        $sql = $this->obterObjetoDeConsultaDescritiva($filtros, $colunas);
        
        $query = bBaseDeDados::obterInstancia()->_db->query($sql);
        
        $retorno = is_array($query) ? $query : $query->result;
        
        // If no records are returned, adds a message to appear in the form.
        if ( !is_array($retorno) )
        {
            $retorno = array(_M('Registros não encontrados'));
        }
        
        return $retorno;
    }
    
    /**
     * Method to search results for a choice field.
     * 
     * @param stdClass $filtros Filters for the search condition.
     * @param array $colunas Columns to search in.
     * @return array Array with values.  
     */
    public function buscarParaEscolha($filtro=NULL, $colunas=NULL, $limit=NULL)
    {
        $colunaDescritiva = $this->obterColunaDescritiva();
        
        if ( !$colunas )
        {
            if ( strlen($colunaDescritiva) > 0 )
            {
                $colunas = array($colunaDescritiva, $this->colunas[0]);
            }
            else
            {
                $colunas = array($this->colunas[1], $this->colunas[0]);
            }
        }
        else
        {
            if ( strlen($colunaDescritiva) > 0 )
            {
                $colunas = array($colunaDescritiva, $colunas[0]);
            }
            else
            {
                $colunas = array($colunas[1], $colunas[0]);
            }
        }
        
        $filtros = new stdClass();
        $filtros->{$colunas[0]} = $filtro;

        $colunasBusca = implode(',', $colunas);

        $sql = $this->obterConsulta($filtros, $colunasBusca, $limit);
        $query = bBaseDeDados::obterInstancia()->_db->query($sql);

        $retorno = is_array($query) ? $query : $query->result;

        return $retorno;
    }
    
    /**
     * Method to search results for an autocomplete choice field.
     * 
     * @param stdClass $filtros Filters for the search condition.
     * @param array $colunas Columns to search in.
     * @return array Array with values.  
     */
    public function buscarParaAutoCompletarEscolha($codigo=NULL, $colunas=NULL)
    {
        $retorno = NULL;
        
        if ( strlen($codigo) )
        {
            if ( !$colunas )
            {
                $colunas = array($this->colunas[0], $this->colunas[1]);
            }

            $filtros = new stdClass();
            $filtros->{$colunas[0]} = $codigo;
            
            $colunasBusca = implode(',', $colunas);

            $sql = $this->obterConsulta($filtros, $colunasBusca);

            $query = bBaseDeDados::obterInstancia()->_db->query($sql);
            
            $retorno = is_array($query) ? $query : $query->result;
        }
        
        return $retorno;
    }

    /**
     * Method to set the class attribute values.
     * 
     * @param FormData $dados Form data.
     */
    public function definir($dados)
    {
        foreach ( $this->colunas as $coluna )
        {
            if ( isset($dados->$coluna) )
            {
                $this->$coluna = $dados->$coluna;
            }
        }

        // Sets the status if available.
        if ( strlen($dados->dataStatus) )
        {
            $this->status = $dados->dataStatus;
        }

        // Sets the related types data.
        if ( is_array($this->tiposRelacionados) )
        {
            // Iterates through the related types to check if there is data for them in the form data.
            foreach ( $this->tiposRelacionados as $tipoRelacionado )
            {
                $dados = (array) $dados;

                // Checks if the related type is found in the data.
                if ( in_array($tipoRelacionado, array_keys($dados)) )
                {
                    // Instantiates the related types.
                    foreach ( $dados[$tipoRelacionado] as $valores )
                    {
                        $tipo = self::instantiateType($tipoRelacionado);
                        $tipo->definir($valores);
                        $this->dadosTiposRelacionados[$tipoRelacionado][] = $tipo;
                    }
                }
            }
        }
    }

    /**
     * Public method to set the type function.
     * 
     * @param string $funcao Type function, can be insert, update or delete. 
     */
    public function definirFuncao($funcao)
    {
        $this->funcao = $funcao;
    }
    
    /**
     * Gets the type function.
     * 
     * @return String Type function. 
     */
    public function obterFuncao()
    {
        return $this->funcao;
    }

    /**
     * Method that edits the record in the database.
     *
     * @return boolean Returns true on success.
     */
    public function editar()
    {
        $sql = new MSQL();
        $colunas = $this->obterColunasInsercaoOuEdicao();

        $sql->setColumns(implode(',', $colunas));
        $sql->setTables($this->tabela);

        $parametros = array( );

        // Sets the data.
        foreach ( $colunas as $coluna )
        {
            $parametros[] = $this->$coluna;
        }

        $whereBasLog = '';
        foreach ( $this->chavesPrimarias as $coluna )
        {
            $sql->setWhere("$coluna = ?");
            $parametros[] = $this->$coluna;

            $whereBasLog .= " AND {$coluna} = '{$this->$coluna}' ";
        }

        // Applies the basLog update
        bBaseDeDados::executar(self::obterUpdadeDaBasLog($this->tabela, ' WHERE ' . substr($whereBasLog, 4)));

        $retorno = bBaseDeDados::executar($sql->update($parametros));

        // Edits the related types data.
        if ( $retorno && is_array($this->dadosTiposRelacionados) )
        {
            foreach ( $this->dadosTiposRelacionados as $nomeTipo )
            {
                foreach ( $nomeTipo as $tipo )
                {
                    $tipo instanceof bType;
                    
                    $dados = $tipo->obter();

                    // Sets the missing primary key.
                    foreach ( $this->chavesPrimarias as $chave )
                    {
                        $dados->$chave = $this->$chave;
                    }

                    $tipo->definir($dados);

                    // Inserts the related data.
                    if ( $tipo->status == MSubDetail::STATUS_ADD )
                    {
                        $tipo->inserir();
                    }
                    elseif ( $tipo->status == MSubDetail::STATUS_EDIT )
                    {
                        // Edits the related data.
                        $tipo->editar();
                    }
                    elseif ( $tipo->status == MSubDetail::STATUS_REMOVE )
                    {
                        // Deletes the related data.
                        $tipo->excluir();
                    }
                }
            }
        }

        return $retorno;
    }

    public static function obterUpdadeDaBasLog($table, $where)
    {
        $MIOLO = MIOLO::getInstance();

        $sql = "UPDATE {$table}
                   SET userName = '{$MIOLO->GetLogin()->id}',
                       dateTime = NOW(),
                       ipAddress = '" . $_SERVER['REMOTE_ADDR'] . "'
                     {$where} ";

        return $sql;
    }

    /**
     * Method that deletes the record from the database.
     *
     * @return boolean Returns true on success.
     */
    public function excluir()
    {
        $exclusaoRelacionado = array( );
        $filtros = new stdClass();

        foreach ( $this->chavesPrimarias as $coluna )
        {
            $filtros->$coluna = $this->$coluna;
        }
        
        if ( is_array($this->tiposRelacionados) )
        {
            foreach ( $this->tiposRelacionados as $tabela )
            {
                $exclusao[] = self::excluirDadosDaTabela($tabela, $filtros);
            }
        }

        if ( !in_array(FALSE, $exclusaoRelacionado) )
        {
            $sql = new MSQL();
            $sql->setTables($this->tabela);

            $parametros = array( );

            foreach ( $filtros as $coluna => $filtro )
            {
                $sql->setWhere("$coluna = ?");
                $parametros[] = $filtro;
            }

            return bBaseDeDados::executar($sql->delete($parametros));
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Protected static method to delete data from any table. Very useful for deleting related types data.
     * 
     * @param string $tabela Name of the table to be deleted from.
     * @param stdClass $condicao Object with the fields that are conditions for deletion.
     * @return boolean Returns true if data was deleted.
     */
    protected static function excluirDadosDaTabela($tabela, stdClass $condicao)
    {
        $sql = new MSQL();
        $sql->setTables($tabela);

        $parametros = array( );

        foreach ( $condicao as $campo => $valor )
        {
            $sql->setWhere("$campo = ?");
            $parametros[] = $valor;
        }

        if ( !count($parametros) )
        {
            throw new Exception(_M('Informe o menos uma condição para a exclusão.'));
            return FALSE;
        }

        return bBaseDeDados::executar($sql->delete($parametros));
    }

    /**
     * Method that inserts the record in the database, testing whether the key is sequential or non-sequential.
     * 
     * @return boolean Returns true on success.
     */
    public function inserir()
    {
        // Tests if the type has more than one primary key.
        if ( strlen($this->chavesPrimarias['sequencial'])  )
        {
            // If it is only one key, performs a sequential key insertion.
            $retorno = $this->inserirChaveSequencial();
        }
        else
        {
            // If it has, performs a composite key insertion.
            $retorno = $this->inserirChaveNaoSequencial();
        }

        // Insert the related data.
        if ( $retorno && is_array($this->tiposRelacionados) )
        {
            // Iterates through the related types to check if there is data for them in the form data.
            foreach ( $this->tiposRelacionados as $tipoRelacionado )
            {
                foreach ( $this->dadosTiposRelacionados[$tipoRelacionado] as $tipo )
                {
                    // Only inserts the data if the status is set to insert.
                    if ( $tipo->status == MSubDetail::STATUS_ADD )
                    {
                        $dados = $tipo->obter();

                        // Sets the missing primary key.
                        foreach ( $this->chavesPrimarias as $chave )
                        {
                            $dados->$chave = $this->$chave;
                        }

                        $tipo->definir($dados);
                        $tipo->inserir();
                    }
                }
            }
        }

        return $retorno;
    }

    /**
     * Method that inserts the record in the database with a sequential key.
     *
     * @return boolean Returns true on success.
     */
    private function inserirChaveSequencial()
    {
        $sql = new MSQL();
        $colunasSemChavePrimaria = $this->obterColunasInsercaoOuEdicao();

        $sql->setTables($this->tabela);
        $sql->setColumns(implode(',', $colunasSemChavePrimaria));

        $parametros = array( );

        // Columns that are not primary key.
        foreach ( $colunasSemChavePrimaria as $coluna )
        {
            $parametros[] = $this->$coluna;
        }

        $retorno = bBaseDeDados::inserir($sql, $parametros);

        if ( is_array($retorno) )
        {
            $contador = 0;

            foreach ( $this->colunas as $coluna )
            {
                $this->$coluna = $retorno[$contador];
                $contador++;
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Method that inserts the record in the database with a composite key.
     *
     * @return boolean Returns true on success.
     */
    private function inserirChaveNaoSequencial()
    {
        $sql = new MSQL();
        $sql->setTables($this->tabela);
        $sql->setColumns(implode(',', $this->colunas));

        $parametros = array( );

        foreach ( $this->colunas as $coluna )
        {
            $parametros[] = $this->$coluna;
        }

        return bBaseDeDados::executar($sql->insert($parametros));
    }

    /**
     * Get type data.
     * 
     * @return stdClass Object with type data. 
     */
    public function obter()
    {
        return $this;
    }

    /**
     * Public method to get the table primary keys.
     * 
     * @return array Array with the table primary keys. 
     */
    public function obterChavesPrimarias()
    {
        return $this->chavesPrimarias;
    }
    
    /**
     * Returns primary key values
     *
     * @return array
     */
    public function obterValorChavesPrimarias()
    {
        $valores = array();
        
        foreach ( $this->obterChavesPrimarias() as $chave )
        {
            $valores[$chave] = $this->obterValorColuna($chave);
        }
        
        return $valores;
    }
    
    /**
     * Returns TRUE if all primary keys have been populated with a value.
     *
     * @return boolean
     */
    public function foiPopuladoChavesPrimarias()
    {
        return count(array_filter($this->obterValorChavesPrimarias())) == count($this->obterChavesPrimarias());
    }

    /**
     * Protected method that gets the table columns that are not primary key.
     * 
     * @return array Array with columns that are not primary key. 
     */
    public function obterColunasSemChavePrimaria()
    {
        $colunas = array( );

        foreach ( $this->colunas as $coluna )
        {
            if ( !in_array($coluna, $this->chavesPrimarias) )
            {
                $colunas[] = $coluna;
            }
        }

        return $colunas;
    }
    
    /**
     * Gets columns that should be included in the query UPDATE or INSERT statement.
     * Excludes columns that have no value set in the Type (NULL counts as a set value).
     * 
     * @return array
     */
    public function obterColunasInsercaoOuEdicao()
    {
        $colunas = $this->obterColunasSemChavePrimaria();
        
        foreach ( $colunas as $chave => $coluna )
        {
            if ( !isset($this->$coluna) )
            {
                unset($colunas[$chave]);
            }
        }
        
        return $colunas;
    }

    /**
     * Gets SQL query, used in search, lookup search and data Grid.
     *
     * @param FormData $filtros Values to be used as filters.
     * @param string Table columns separated by comma.
     * @return string SQL query.
     */
    public function obterConsulta($filtros = NULL, $colunas=NULL, $limit=NULL)
    {
        $sql = $this->obterObjetoConsulta($filtros, $colunas, $limit);

        return $sql->select();
    }
    
    /**
     * Allows overriding the default Type query object.
     * 
     * @see See the obterConsulta() method
     * 
     * @return MSQL 
     */
    public function obterObjetoConsulta($filtros = NULL, $colunas=NULL, $limit=NULL)
    {
        $sql = new MSQL();
        $sql->setTables('ONLY ' . $this->esquema . '.' . $this->tabela);
        
        $colunas = $this->formatarColunas($colunas);

        $sql->setColumns(implode(',', $colunas));

        $parametros = array( );

        if ( is_object($filtros) )
        {
            foreach ( $filtros as $idFiltro => $filtro )
            {
                $tipoColuna = $this->estruturaTabela[$idFiltro]->type;

                if ( strlen($tipoColuna) && strlen($filtro) )
                {
                    switch ( $tipoColuna )
                    {
                        case bColumnInfo::TYPE_LONG_TEXT:
                            $sql->setWhere("unaccent(lower($idFiltro)) LIKE unaccent(lower(?))");
                            $parametros[] = '%' . $filtro . '%';
                            break;

                        case bColumnInfo::TYPE_TEXT:
                            $sql->setWhere("unaccent(lower($idFiltro)) LIKE unaccent(lower(?))");
                            $parametros[] = $filtro . '%';
                            break;
                        
                        case bColumnInfo::TYPE_CHAR:
                            $sql->setWhere("unaccent(lower($idFiltro)) LIKE unaccent(lower(?))");
                            $parametros[] = $filtro . '%';
                            break;
                        
                        default:
                            $sql->setWhere("$idFiltro = ?");
                            $parametros[] = $filtro;
                            break;
                    }
                }
            }
        }
        
        if ( strlen($this->ordenacaoPadrao) > 0 )
        {
            $sql->setOrderBy($this->ordenacaoPadrao);
        }
        
        $sql->setParameters($parametros);
        
        if ( $limit )
        {
            $sql->setLimit($limit);
        }
        
        return $sql;
    }
    
    /**
     * Function to add masks to columns, according to their type.
     * 
     * @param Array $colunas
     * @return Array
     */
    private function formatarColunas($colunas)
    {
        if ( strlen($colunas) )
        {
            $columns = explode(',', $colunas);
        }
        else
        {
            $columns = $this->colunas;
        }
        
        foreach($columns as $key => $column)
        {
            $dadosDaColuna = bCatalogo::buscarDadosDaColuna($column, $this->tabela);
            
            // If the column is timestamp, applies the mask defined in the type.
            if ( $dadosDaColuna->type == bColumnInfo::TYPE_TIMESTAMP )
            {
                $columns[$key] = "to_char($column, '{$this->mascaraTimeStamp}') as $column";
            }
        }
        
        return $columns;
    }

    /**
     * Method that populates the object with database data.
     */
    public function popular()
    {
        $sql = new MSQL();
        $colunasSemChavePrimaria = $this->obterColunasSemChavePrimaria();

        $sql->setTables($this->tabela);
        $sql->setColumns(implode(',', $colunasSemChavePrimaria));

        $parametros = array( );

        foreach ( $this->chavesPrimarias as $chavePrimaria )
        {
            $sql->setWhere("$chavePrimaria = ?");
            $parametros[] = $this->$chavePrimaria;
        }

        $consulta = bBaseDeDados::consultar($sql, $parametros);

        $contador = 0;
        
        foreach ( $colunasSemChavePrimaria as $coluna )
        {
            $this->$coluna = $consulta[0][$contador];
            $contador++;
        }

        // Populates related data.
        if ( is_array($this->tiposRelacionados) )
        {
            foreach ( $this->tiposRelacionados as $tipo )
            {
                // Builds the filter
                $filtro = new stdClass();

                foreach ( $this->chavesPrimarias as $chave )
                {
                    $filtro->$chave = $this->$chave;
                }
                
                // Gets an instance of the type to be able to search all related types.
                $intanciaTipo = bType::instantiateType($tipo);
                $resultado = $intanciaTipo->buscar($filtro);
                
                if ( is_array($resultado) )
                {
                    foreach ( $resultado as $valor )
                    {
                        $tipoObjeto = bType::instantiateType($tipo);
                        
                        $relacionamentos = bCatalogo::obterRelacionamentos($tipo);
                        foreach($relacionamentos as $relacionamento)
                        {
                            $tipoRelacionado = self::instantiateType($relacionamento->tabela_ref);
                            
                            if ( strlen($tipoRelacionado->obterColunaDescritiva()) > 0 )
                            {
                                $descricaoItem = $tipoRelacionado->obterDescricaoDoItem($relacionamento->atributo_ref, $valor->{$relacionamento->atributo});
                                $tipoObjeto->adicionarValorDaColunaEstrangeira($relacionamento->atributo, $descricaoItem);
                            }
                        }                        
                        
                        $tipoObjeto->definir($valor);

                        // Adding relationship description key
                        foreach ( $tipoObjeto->estruturaTabela as $rel )
                        {                            
                            $idDescription = $rel->name . 'Descricao';
                            $tipoObjeto->$idDescription = $tipoObjeto->valorDaColunaEstrangeira[$rel->name];
                        }
                        
                        $this->dadosTiposRelacionados[$tipo][] = $tipoObjeto;
                    }
                }
            }
        }
    }

    /**
     * Method that saves the record in the database, editing or inserting as appropriate.
     *
     * @return boolean Returns true on success.
     */
    public function salvar()
    {
        $resultado = FALSE;
        
        if ( $this->funcao == FUNCAO_INSERIR || !$this->foiPopuladoChavesPrimarias() )
        {
            $resultado = $this->inserir();
        }
        else
        {
            $resultado = $this->editar();
        }
        
        return $resultado;
    }

    /**
     * Method that defines the structure of the table fields mapped in the type.
     */
    private function definirEstruturaDaTabela()
    {
        // Gets columns that are in the table.
        $colunas = bCatalogo::obterColunasDaTabela($this->esquema, $this->tabela);
        if ( is_array($colunas) )
        {
            foreach ( $colunas as $coluna => $dados )
            {
                if ( $dados->constraint == 'p' )
                {
                    if ( substr($dados->defaultValue, 0, 7) == 'nextval' )
                    {
                        $this->chavesPrimarias['sequencial'] = $coluna;
                    }
                    else
                    {
                        $this->chavesPrimarias[] = $coluna;
                    }
                }
            }
            
            // Sets the table columns.
            $this->colunas = array_keys($colunas);
        }
        else
        {
            throw new Exception(_M('A tabela especificada não existe.'));
        }

        // Sets the table structure.
        $this->estruturaTabela = $colunas;
    }
    
    /**
     * Public method to get the table structure.
     * 
     * @return array Array with the table fields. 
     */
    public function obterEstruturaDaTabela()
    {
        return $this->estruturaTabela;
    }
    
    /**
     * @return string
     */
    public function obterTipoColuna($coluna)
    {
        $estrutura = $this->obterEstruturaDaTabela();
        
        return $estrutura[$coluna]->type;
    }
    
    /**
     * Returns whether the column type is numeric (int, bigint, numeric..).
     * 
     * @return boolean
     */
    public function colunaTipoNumerico($coluna)
    {
        return in_array($this->obterTipoColuna($coluna), array(bColumnInfo::TYPE_INTEGER, bColumnInfo::TYPE_BIG_INTEGER, bColumnInfo::TYPE_NUMERIC));
    }
    
    /**
     * Public static method that instantiates a type or a bType object with data from the desired table.
     * 
     * @param string $nomeDoTipo Name/key of the desired type.
     * @return bType Instance of the bType object.
     */
    public static function instantiateType($nomeDoTipo, $modulo=NULL)
    {
        if ( !strlen($nomeDoTipo) )
        {
            return;
        }
        
        $MIOLO = MIOLO::getInstance();
        
        if ( !$modulo )
        {
            $modulo = MIOLO::getCurrentModule();
        }
        
        $tipo = $MIOLO->getModulePath($modulo, 'tipos/' . $nomeDoTipo . '.class.php');
        
        // Checks if the type code exists.
        if ( file_exists($tipo) )
        {
            $MIOLO->uses("tipos/$nomeDoTipo.class.php", $modulo);
            $tipo = new $nomeDoTipo($nomeDoTipo);
        }
        else
        {
            // Instantiates a type dynamically.
            $tipo = new bType($nomeDoTipo);
        }

        return $tipo;
    }
   
    /**
     * Public method to add a related type.
     * 
     * @param string $tipoRelacionado Name of the related type.
     */
    public function adicionarTipoRelacionado($tipoRelacionado)
    {
        $this->tiposRelacionados[] = $tipoRelacionado;
    }
    
    /**
     * Public method to remove a related type.
     * 
     * @param int $indice Position of the related type to be removed.
     */
    public function removerTipoRelacionado($indice)
    {
        unset($this->tiposRelacionados[$indice]);
    }
    
    /**
     * Public method to set a related type.
     * 
     * @param array $tiposRelacionados Array with the related types.
     */
    public function definirTiposRelacionados(array $tiposRelacionados)
    {
        $this->tiposRelacionados = $tiposRelacionados;
    }
    
    /**
     * Public method to get the related types.
     * 
     * @return array Array with the related types. 
     */
    public function obterTiposRelacionados()
    {
        return $this->tiposRelacionados;
    }
    
    /**
     * Sets the type's table comment.
     * 
     * @param String $comentario Table comment.
     */
    public function definirComentarioDaTabela($comentario=NULL)
    {
        if ( !strlen($comentario) )
        {
            $comentario = bCatalogo::obterComentarioDaTabela($this->tabela);
        }
        
        $this->comentarioDaTabela = $comentario;
    }
    
    /**
     * Gets the table comment.
     * 
     * @return String table comment. 
     */
    public function obterComentarioDaTabela()
    {
        return $this->comentarioDaTabela ? $this->comentarioDaTabela : ucfirst($this->tabela);
    }
    
    /**
     * Sets the related types data.
     * 
     * @param array $dadosTiposRelacionados Array with related types data.
     */
    public function definirDadosTiposRelacionados($dadosTiposRelacionados)
    {
        $this->dadosTiposRelacionados = $dadosTiposRelacionados;
    }
    
    /**
     * Gets the related types data.
     * 
     * @return array Array with related types data. 
     */
    public function obterDadosTiposRelacionados()
    {
        return $this->dadosTiposRelacionados;
    }
    
    /**
     * Public method to validate the type data.
     * 
     * @return boolean Returns true if validation passed.
     */
    public function validar()
    {
        if ( is_array($this->estruturaTabela) )
        {
            $camposInvalidos = array();
            
            foreach ( $this->estruturaTabela as $campo => $estrutura )
            {
                $estrutura instanceof bColumnInfo;
                
                if ( $this->chavesPrimarias['sequencial'] == $campo )
                {
                    continue;
                }
                
                // Checks if required field was filled.
                if ( ($estrutura->required == DB_TRUE) && (strlen($this->$campo) == 0) && ( $estrutura->defaultValue == DB_FALSE ) )
                {
                    $camposInvalidos[$campo] = _M('Este campo é obrigatório');
                }
                
                // Checks if the number of characters exceeds the field size in the database.
                if ( $estrutura->size && (strlen($this->$campo) > $estrutura->size) )
                {
                    $camposInvalidos[$campo] = _M('O campo excede o número de caracteres permitido', NULL, $estrutura->title);
                }
            }
            
            if ( count($camposInvalidos) )
            {
                throw new MValidationException($camposInvalidos, array_keys($camposInvalidos));
            }
            
        }
        
        return TRUE;
    }

    /**
     * Performs query based on reference columns.
     *
     * @param array $colunas Array of SInfoColuna objects.
     * @param array $filtros Filters to be applied to the query.
     * @return MSQL MSQL object of the query result.
     */
    public function buscarNaReferencia($colunas, $valoresFiltrados=array())
    {
        $parametros = array();
        $condicao = '';
        $joins = array();

        $colunasString = array();
        $tabelas = array();
        $tabelasString = '';
        $esquemaAnterior = '';
        $tabelaAnterior = '';
        $filtros = new stdClass();
        $filtros->generico = $valoresFiltrados->generico;
        
        $orderBy = $this->ordenacaoPadrao;
        // $chave is schema.table.column
        foreach ( $colunas as $chave => $coluna )
        {
            $coluna instanceof bColumnInfo;
            
            if ( $orderBy == NULL || strlen($orderBy) == 0 )
            {
                $orderBy = $coluna->ordenar;
            }

            // Adjusts the filters coming from the form.
            if ( $coluna->filterable )
            {
                $filtros->{$coluna->field} = $valoresFiltrados->{$coluna->field};
            }

            if ( !in_array("$coluna->schema.$coluna->table", $tabelas) )
            {
                $tabelas[] = "$coluna->schema.$coluna->table";

                $colunasString[] = "$coluna->schema.$coluna->table.$coluna->name";

                if ( $tabelaAnterior == '' )
                {
                    $esquemaAnterior = $coluna->schema;
                    $tabelaAnterior = $coluna->table;

                    // Gets data from the previous table.
                    $dadosDaTabelaAnterior = bCatalogo::buscarChavesEstrangeirasDaTabela($tabelaAnterior, $esquemaAnterior);
                }

                if ( $tabelasString == '' )
                {
                    $tabelasString .= "$coluna->schema.$coluna->table ";
                }
                else
                {
                    $dadosDaTabela = bCatalogo::buscarChavesPrimariasDaTabela($coluna->table, $coluna->schema);

                    foreach ( $dadosDaTabela as $pk )
                    {
                        list($pkColuna, $pkTipo) = $pk;

                        foreach ( $dadosDaTabelaAnterior as $fk )
                        {
                            list($fkFromSchema, $fkFromTable, $fkFromColumn, $fkToSchema, $fkToTable, $fkToColumn, $fkObrigatorio) = $fk;
//        var_dump($coluna->schema.' - '.$coluna->table );

                            $join = $fkToSchema.$fkToTable;
                            
                            if ( $fkToColumn == $pkColuna && !in_array($join, $joins) )
                            {  
                                $join = $fkObrigatorio == DB_TRUE ? 'INNER' : 'LEFT';
                                $tabelasString .= " $join JOIN ONLY $fkToSchema.$fkToTable ON $fkToSchema.$fkToTable.$fkToColumn = $fkFromSchema.$fkFromTable.$fkFromColumn";
                                
                                $joins[] = $fkToSchema.$fkToTable;
                            }
                        }
                    }

                }
            }
            else
            {
                $colunasString[] = "$coluna->schema.$coluna->table.$coluna->name";
            }
        }
        
        $colunasString = implode(',', $colunasString);
        
        $msql = new MSQL();
        $msql->setTables($tabelasString);
        $msql->setColumns($colunasString);
        if ( strlen($orderBy) > 0 )
        {
            $msql->setOrderBy($orderBy);
        }

        $fazerSubCondicao = false;
        foreach ($filtros as $filtro )
        {
            if ( strlen($filtro) > 0 )
            {
                $fazerSubCondicao = true;
                break;
            }
        }
        
        if ( $fazerSubCondicao )
        {
            $msql->startSubCondition();
        }
        
        foreach ( $filtros as $chave => $valor )
        {
            if ( $chave == 'generico' )
            {
                continue;
            }

            $chave = str_replace('__', '.', $chave);
            
            switch( $colunas[$chave]->type )
            {
                case bColumnInfo::TYPE_TEXT:
                case bColumnInfo::TYPE_LONG_TEXT:
                    
                    if ( strlen($valor) )
                    {
                        $msql->setWhere("UNACCENT($chave) ILIKE UNACCENT(?)");
                        $parametros[] = $valor . '%';
                    }

                    // Searches by the generic field value.
                    if ( strlen($filtros->generico) )
                    {
                        $msql->setWhereOr("UNACCENT($chave) ILIKE UNACCENT(?)");
                        $parametros[] = $filtros->generico . '%';
                    }

                    break;
                    
                case bColumnInfo::TYPE_LIST:
                    
                    if (strlen($valor) )
                    {
                        $relacionamentos = bCatalogo::obterRelacionamentos($this->tabela, $colunas[$chave]->table);
                        if ( $relacionamentos[0] )
                        {
                            $chaveEstrangeira = "{$relacionamentos[0]->esquema}.{$relacionamentos[0]->tabela_ref}.{$relacionamentos[0]->atributo_ref}";
                            $msql->setWhere("$chaveEstrangeira = ?");
                            $parametros[] = $valor;
                        }
                        else
                        {
                            $msql->setWhere("$chave = ?");
                            $parametros[] = $valor;
                        }
                    }
                    
                    break;
                    
                case bColumnInfo::TYPE_INTEGER:
                case bColumnInfo::TYPE_BIG_INTEGER:
                case bColumnInfo::TYPE_NUMERIC:
                case bColumnInfo::TYPE_DECIMAL:
                    
                    if ( strlen($valor) && is_numeric($valor) )
                    {
                        $msql->setWhere("$chave = ?");
                        $parametros[] = $valor;
                    }
                    
                    // Searches by the generic field value.
                    if ( strlen($filtros->generico) && is_numeric($filtros->generico) )
                    {
                        $msql->setWhereOr("$chave = ?");
                        $parametros[] = $filtros->generico;
                    }
                    
                    break;

                default:
                    
                    if ( strlen($valor) )
                    {
                        $msql->setWhere("$chave = ?");
                        $parametros[] = $valor;
                    }
                    
                    // Searches by the generic field value.
                    if ( strlen($filtros->generico) )
                    {
                        $msql->setWhereOr("UNACCENT($chave::varchar) ILIKE (?)");
                        $parametros[] = $filtros->generico . '%';
                    }
                    break;
            }
        }
        
        if ( $fazerSubCondicao )
        {
            // Prevents errors if there are no filters. Do not remove.
            if ( count($parametros) == 0 )
            {
                $msql->setWhere('1=1');
            }
            $msql->endSubCondition();
        }
        
        $msql->setParameters($parametros);
        return $msql;
    }
    
    /**
     * Returns the incremental id of the last insertion
     *
     * @return int
     */
    public function obterUltimoIdInserido()
    {
        return bBaseDeDados::obterUltimoIdInserido($this->tabela);
    }
    
    public function obterColunaDescritiva()
    {
        $colunaDescritiva = $this->colunaDescritiva;
        
        if ( strlen($colunaDescritiva) == 0 )
        {
            $nomesPossiveis = array('nome', 'descricao', 'name', 'description');
            foreach( $this->colunas as $coluna )
            {
                if ( in_array($coluna, $nomesPossiveis) )
                {
                    $colunaDescritiva = $coluna;
                    break;
                }
            }
        }
        
        return $colunaDescritiva;
    }

    public function setColunaDescritiva($colunaDescritiva)
    {
        $this->colunaDescritiva = $colunaDescritiva;
    }
    
    public function adicionarValorDaColunaEstrangeira($colunaEstrangeira, $valor)
    {
        $this->valorDaColunaEstrangeira[$colunaEstrangeira] = $valor;
    }

    public function obterDescricaoDoItem($chavePrimaria, $valor)
    {
        $descricao = null;
        $coluna = $this->obterColunaDescritiva();
        
        if ( strlen($coluna) > 0 && strlen($chavePrimaria) > 0 )
        {
            $sql = new MSQL();
            $sql->setTables($this->tabela);
            $sql->setColumns($coluna);
            $sql->setWhere("$chavePrimaria = ?");
            $sql->addParameter($valor);
            
            $resultado = bBaseDeDados::consultar($sql);
            $descricao = $resultado[0][0];
        }
        
        return $descricao;
    }
    
    public function definirRelacionamentos()
    {
        $this->relacionamentos = bCatalogo::obterRelacionamentos($this->tabela);
    }
    
    public function obterRelacionamentos()
    {
        return $this->relacionamentos;
    }
    
    /**
     * @param array $valoresRestritivos - These values will restrict the query (they are related to the primary key).
     * @return array
     */
    public function obterArrayAssociativo($valoresRestritivos = null)
    {
        $retorno = array();
        $sql = $this->obterObjetoDeConsultaDescritiva();

        if ( is_array($valoresRestritivos) && strlen($valoresRestritivos[0]) > 0 || !(is_array($valoresRestritivos)) )
        {
            if ( is_array($valoresRestritivos) )
            {
                foreach ( $valoresRestritivos as $valor )
                {
                    $whereIn .= "'" . $valor . "',";
                }

                $sql .= ' WHERE ' . current($this->chavesPrimarias) . ' IN (' . rtrim($whereIn, ','). ')';
            }
            
            $query = bBaseDeDados::obterInstancia()->_db->query($sql);

            $query = is_array($query) ? $query : $query->result;

            $retorno = array();
            foreach( $query as $linha )
            {
                $retorno[$linha[0]] = $linha[1];
            }
        }
        
        return $retorno;
    }
    
    public function getOrdenacaoPadrao()
    {
        return $this->ordenacaoPadrao;
    }

    public function setOrdenacaoPadrao(String $ordenacaoPadrao)
    {
        $this->ordenacaoPadrao = $ordenacaoPadrao;
    }
    
    /**
     * Gets values related to the type.
     * 
     * @param string $coluna
     * @param string $colunaDeComparacao
     * @param string $valorColunaDeComparacao
     * @return array
     */
    public function obterIdsRelacionados($coluna, $colunaDeComparacao, $valorColunaDeComparacao)
    {
        $where = ' WHERE ' . $colunaDeComparacao . ' = ' . $valorColunaDeComparacao;
        if ( is_array($valorColunaDeComparacao) )
        {
            $where = ' WHERE ' . $colunaDeComparacao . ' IN ( ' . implode(', ', $valorColunaDeComparacao) . ')';
        }
        
        $sql = ' SELECT ' . $coluna . ' FROM ONLY ' . $this->esquema . '.' . $this->tabela . $where;

        $result = bBaseDeDados::obterInstancia()->_db->query($sql);
        
        foreach ( $result as $r )
        {
            $return[] = $r[0];
        }
        
        return $return;
    }
}
