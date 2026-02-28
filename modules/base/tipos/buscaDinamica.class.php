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
 * Classe que representa a tabela de busca dinâmica.
 *
 *
 *
 *
 */
class buscaDinamica extends bTipo
{
    
    public function __construct($chave)
    {
        parent::__construct($chave);
        $this->tiposRelacionados[] = 'campoBuscaDinamica';
    }

    /**
     * Obtém os dados das colunas a serem utilizadas pela busca dinâmica.
     *
     * @param string $modulo Módulo.
     * @param string $identificador Identificador.
     * @return array Vetor com objetos do tipo bInfoColuna.
     */
    public static function buscarDadosDasColunas($modulo, $identificador)
    {
        $msql = new MSQL();
        $msql->setColumns("pg_attribute.attname AS id,
                           campobuscadinamica.tipo,
                           campobuscadinamica.nome AS titulo,
                           pg_attribute.attnotnull AS obrigatorio,
                           campobuscadinamica.valorPadrao,
                           CASE WHEN pg_attribute.atttypmod > 4 THEN ( pg_attribute.atttypmod - 4 ) ELSE NULL END AS tamanho,
                           pg_constraint.contype AS restricao,
                           toSchema.nspname AS fkEsquema,
                           toTable.relname AS fkTabela,
                           toColumn.attname AS fkColuna,
                           campobuscadinamica.valoresPossiveis,
                           campobuscadinamica.editavel,
                           campobuscadinamica.visivel,
                           campobuscadinamica.filtravel,
                           campobuscadinamica.exibirNaGrid,
                           campobuscadinamica.parametros,
                           campobuscadinamica.chave,
                           buscadinamica.modulo,
                           buscadinamica.ordenar,
                           pg_namespace.nspname AS esquema,
                           pg_class.relname AS tabela,
                           pg_namespace.nspname || '__' || pg_class.relname || '__' || pg_attribute.attname AS campo");
         
        $msql->setTables("pg_attribute 
               INNER JOIN pg_class 
                       ON pg_class.oid = pg_attribute.attrelid
                      AND pg_class.relkind in ('r', 'v')
               INNER JOIN pg_namespace
                       ON pg_namespace.oid = pg_class.relnamespace
               INNER JOIN campobuscadinamica
                       ON pg_namespace.nspname = split_part(campobuscadinamica.referencia, '.', 1)
                      AND pg_class.relname = split_part(campobuscadinamica.referencia, '.', 2)
                      AND pg_attribute.attname = split_part(campobuscadinamica.referencia, '.', 3)
               INNER JOIN buscadinamica
                       ON buscadinamica.buscadinamicaid = campobuscadinamica.buscadinamicaid
               -- TYPE
               INNER JOIN pg_type 
                       ON pg_type.oid = pg_attribute.atttypid 
                      AND pg_type.typname NOT IN ('oid', 'tid', 'xid', 'cid')
      -- DEFAULT VALUE
                LEFT JOIN pg_attrdef 
                       ON pg_attrdef.adrelid = pg_attribute.attrelid 
                      AND pg_attrdef.adnum = pg_attribute.attnum      
      -- FKS
                LEFT JOIN pg_constraint
                       ON pg_constraint.conrelid = pg_attribute.attrelid
                      AND pg_attribute.attnum = ANY(pg_constraint.conkey)
                LEFT JOIN pg_class AS toTable
                       ON toTable.oid = pg_constraint.confrelid
                LEFT JOIN pg_namespace AS toSchema
                       ON toSchema.oid = toTable.relnamespace
                LEFT JOIN pg_attribute AS toColumn
                       ON toColumn.attrelid = toTable.oid 
                      AND conkey @> ARRAY[ pg_attribute.attnum ]
                      AND position(toColumn.attnum::text IN array_to_string(confkey, ' ')) <> 0
      -- COMMENT
                LEFT JOIN pg_description
                       ON pg_description.objoid = pg_class.oid
                      AND pg_description.objsubid = pg_attribute.attnum");
        
        $msql->setWhere('lower(buscadinamica.modulo) = lower(?)
                     AND lower(buscadinamica.identificador) LIKE lower(?)');
                
        $parametros = array($modulo, '%'.$identificador);
        
        $msql->setOrderBy('campobuscadinamica.posicao,
                           campobuscadinamica.nome');
//        mutil::flog($msql->select($parametros));

        $resultado = bBaseDeDados::consultar($msql, $parametros);

        $colunas = array();
        
        foreach ( $resultado as $linha )
        {
            $coluna = new bInfoColuna();
            list(
                $coluna->nome,
                $coluna->tipo,
                $coluna->titulo,
                $coluna->obrigatorio,
                $coluna->valorPadrao,
                $coluna->tamanho,
                $coluna->restricao,
                $coluna->fkEsquema,
                $coluna->fkTabela,
                $coluna->fkColuna,
                $coluna->valoresPossiveis,
                $coluna->editavel,
                $coluna->visivel,
                $coluna->filtravel,
                $coluna->exibirNaGrid,
                $coluna->parametros,
                $coluna->chave,
                $coluna->modulo,
                $coluna->ordenar,
                $coluna->esquema,
                $coluna->tabela,
                $coluna->campo
            ) = $linha;

            $chave = "$coluna->esquema.$coluna->tabela.$coluna->nome";
            $colunas[$chave] = $coluna;
        }
        
        return $colunas;
    }
}

?>