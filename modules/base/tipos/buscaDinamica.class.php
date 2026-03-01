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
 * Class that represents the dynamic search table.
 *
 *
 *
 *
 */
class buscaDinamica extends bType
{
    
    public function __construct($chave)
    {
        parent::__construct($chave);
        $this->tiposRelacionados[] = 'campoBuscaDinamica';
    }

    /**
     * Gets the column data to be used by the dynamic search.
     *
     * @param string $modulo Module.
     * @param string $identificador Identifier.
     * @return array Array with bColumnInfo type objects.
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
            $coluna = new bColumnInfo();
            list(
                $coluna->name,
                $coluna->type,
                $coluna->title,
                $coluna->required,
                $coluna->defaultValue,
                $coluna->size,
                $coluna->constraint,
                $coluna->fkSchema,
                $coluna->fkTable,
                $coluna->fkColumn,
                $coluna->possibleValues,
                $coluna->editable,
                $coluna->visible,
                $coluna->filterable,
                $coluna->showInGrid,
                $coluna->parameters,
                $coluna->chave,
                $coluna->modulo,
                $coluna->ordenar,
                $coluna->schema,
                $coluna->table,
                $coluna->field
            ) = $linha;

            $chave = "$coluna->schema.$coluna->table.$coluna->name";
            $colunas[$chave] = $coluna;
        }
        
        return $colunas;
    }
}

?>