<?php
    
    /*
     * Apresenta a janela para seleção de registro, de acordo com o item passado
     */

	//Consulta de Pessoas
    if ( $item == 'pessoa' )
	{
		$data = $lookup->LookupPessoa($frm_filtro,20,$page,$total);
		
		$labels = array(new ListingColumn('ID'),
						new ListingColumn('Nome'));
						
		$theme->Lookup('Filtro Pessoas',
					   $module,
					   $item,
					   $data,
					   $labels,
					   $filtro,
					   $total,
					   $page,
					   20);
	}
	
    // Consulta de Instituições
    else if ( $item == 'instituicao' )
	{
		$data = $lookup->LookupInstituicoes($frm_filtro,20,$page,$total);
		
		$labels = array(new ListingColumn('ID'),
						new ListingColumn('Nome'));
						
		$theme->Lookup('Filtro Instituções',
					   $module,
					   $item,
					   $data,
					   $labels,
					   $filtro,
					   $total,
					   $page,
					   20);
	}
    
?>
